<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/28
 * Time: 11:13
 */

namespace Admin\Service;
use Common\Service\BaseService;
use Org\Util\PinYin;

class AppService extends BaseService
{
    const LIST_TYPE_HOT = 1; //人气排行
    const LIST_TYPE_NEW = 2; //最新上线

    const SHELVE_GAME = 'SHELVE_GAME';      //游戏发布
    const UN_SHELVE_GAME = 'UN_SHELVE_GAME';  //游戏下架

    /**
     * 获取当前媒体站app_list表中数据最大的id值
     * @author xy
     * @since 2017/07/03 11:53
     * @return bool|int
     */
    public function getMaxIdFromMediaAppList(){
        $result = M('app_list')
            ->field('max(`id`) as max_id')
            ->find();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        if(empty($result['max_id'])){
            return 0;
        }
        return $result['max_id'];
    }

    /**
     * 从指娱总库获取app_list表的id，app_id数据
     * @author xy
     * @since 2017/06/29 11:20
     * @param NULL $where 查询的条件
     * @return bool|mixed
     */
    public function getIdAndAppIdFromZhiYu($where = NULL){
        $defaultField = ' list.id, list.app_id, lib.app_name ';
        $zyAppListData = M(C('DB_ZHIYU.DB_NAME').'.'.'app_list', C('DB_ZHIYU.DB_PREFIX'))->alias('list')
            ->field($defaultField)
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=list.app_id')
            ->where($where)
            ->order('id ASC')
            ->select();
        if($zyAppListData === false){
            $this->setError(M(C('DB_ZHIYU.DB_NAME').'.'.'app_list', C('DB_ZHIYU.DB_PREFIX'))->getDbError());
            return false;
        }
        if(count($zyAppListData)>0){
            return $zyAppListData;
        }
        $this->setError('没有需要同步的数据');
        return false;
    }

    /**
     * 从指娱的app_list表同步数据到媒体站的app_list表,只同步已上架的游戏
     * @author xy
     * @since 2017/06/29 12:00
     * @return bool
     */
    public function insertDataIntoMediaAppList(){
        set_time_limit(0);//防止php执行超时
        ini_set('memory_limit','1024M');//防止php执行时超过最大内存限制
        $maxId = $this->getMaxIdFromMediaAppList();
        if($maxId === false){
            return false;
        }
        if(empty($maxId)){
            $where = array();
        }else{
            $where = array(
                'id' => array('gt', $maxId)
            );
        }
        //获取已上架与测试上架的游戏
        $where['status'] = array('IN', array(1,2));

        //根据当前媒体站app_list表的最大id,如果无则从指娱的app_list获取所有数据，有则取id>$maxId的数据
        $appList = $this->getIdAndAppIdFromZhiYu($where);
        if(!$appList){
            return false;
        }
        $numberToEn = array(
            '1' => 'One',
            '2' => 'Two',
            '3' => 'Three',
            '4' => 'Four',
            '5' => 'Five',
            '6' => 'Six',
            '7' => 'Seven',
            '8' => 'Eight',
            '9' => 'Nine',
            '10' => 'Ten',
        );
        //添加插入到媒体站app_list表的字段
        $insertData = array();
        foreach ($appList as $key=>$app){
            //取游戏的第一个字符，转换为拼音
            $char = get_string_first_char_pinyin($app['app_name']);
            if(empty($char)){
                $char = 'A';
            }
            $insertData[$key] = $app;
            $insertData[$key]['final_hot_sort'] = 0;
            $insertData[$key]['final_new_sort'] = 0;
            $insertData[$key]['pre_hot_sort'] = 0;
            $insertData[$key]['pre_new_sort'] = 0;
            $insertData[$key]['edit_time'] = 0;
            $insertData[$key]['sync_time'] = time();
            $insertData[$key]['is_publish'] = 2;    //默认同步过来的游戏列表未发布状态
            $insertData[$key]['pin_yin'] = strtoupper($char); //首字母拼音
        }
        $appList = NULL;
        unset($appList);

        M()->startTrans();

        //把最新的指娱的app_list的数据同步到媒体站
        $result = M('app_list')->addAll(array_values($insertData));
        if($result !== false ){
            M()->commit();
            return true;
        }else{
            M()->rollback();
            $this->setError(M('app_list')->getDbError());
            return false;
        }

    }

    /**
     * 根据条件获取媒体站app_list的数据并分页
     * @author xy
     * @since 2017/06/29 14:44
     * @param integer $type 查询列表类型 1人气排行，2近期更新
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @param null $where 查询条件
     * @return bool|mixed
     */
    public function getAppSortListFromMediaByPage($type, $currentPage, $pageSize, $where = NULL){
        if(empty($type)){
            $this->setError('列表类型参数错误');
            return false;
        }
        if(!is_numeric($type)){
            $this->setError('列表类型参数错误');
            return false;
        }
        $listTypeArr = self::getListTypeArr();
        if(!in_array($type,$listTypeArr)){
            $this->setError('列表类型参数错误');
            return false;
        }
        if($type == self::LIST_TYPE_HOT){
            //获取下载量高的app,并且先按自定义顺序正序排序，再按下载量倒序排序
            $field = 'alist.id, alist.app_id, alist.is_publish, IF(alist.pre_hot_sort=0, 9999999, alist.pre_hot_sort) as pre_hot_sort, IF(alist.pre_new_sort=0, 9999999, alist.pre_new_sort) as pre_new_sort, IFNULL(alib.app_name, lib.app_name) as app_name, list.status, (list.app_down_num + list.cardinal) as app_down_num';
            $orderBy = 'pre_hot_sort asc, app_down_num desc, alist.is_publish ASC';
        }else {
            //获取近期更新的app,并且先按自定义顺序正序排序，再按更新时间倒序排序
            $field = 'alist.id, alist.app_id, alist.is_publish, IF(alist.pre_hot_sort=0, 9999999, alist.pre_hot_sort) as pre_hot_sort, IF(alist.pre_new_sort=0, 9999999, alist.pre_new_sort) as pre_new_sort, IFNULL(alib.app_name, lib.app_name) as app_name, list.status, list.sj_time';
            $orderBy = 'pre_new_sort asc, alist.publish_time desc';
        }

        $appList = M('app_list')->alias('alist')
            ->field($field)
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->where($where)
            ->order($orderBy)
            ->limit($currentPage,$pageSize)
            ->select();
        if($appList === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $appList;
    }

    /**
     * 根据条件获取媒体站app_list的数据的总数量
     * @author xy
     * @since 2017/06/29 14:48
     * @param NULL $where 查询条件
     * @return bool
     */
    public function getAppListFromMediaTotalCount($where = NULL){
        $totalCount = M('app_list')->alias('alist')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id') //关联总库的游戏库获取游戏名
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->where($where)
            ->count();
        if($totalCount === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $totalCount;
    }

    /**
     * 根据主键获取app_list的数据
     * @author xy
     * @since 2017/07/03
     * @param $id
     * @return bool|mixed
     */
    public function loadAppListByPk($id){
        $result = M('app_list')
            ->where(array('id'=>$id))
            ->find();
        if(empty($result)){
            return false;
        }
        return $result;
    }

    /**
     * 根据id判断媒体站app_list表数据是否存在
     * @author xy
     * @since 2017/07/04 11:00
     * @param $id
     * @return bool
     */
    public function isMediaAppListDataExistByPk($id){
        if(empty($id) || !is_numeric($id)){
            $this->setError('id参数错误');
            return false;
        }
        $result = M('app_list')->where('id='.$id)->count();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据app_id判断媒体站app_list表数据是否存在
     * @author xy
     * @since 2017/07/06 09:20
     * @param $appId
     * @return bool
     */
    public function isMediaAppListDataExistByAppId($appId){
        if(empty($appId) || !is_numeric($appId)){
            $this->setError('app_id参数错误');
            return false;
        }
        $result = M('app_list')->where('app_id='.$appId)->count();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据主键修改媒体站app_list的排序
     * @author xy
     * @since 2017/07/04 11:17
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateMediaAppListSortByPk($id, array $data){
        $result = $this->isMediaAppListDataExistByPk($id);
        if ($result === false){
            return false;
        }
        if($result == 0){
            $this->setError('id为'.$id.'的数据不存在');
            return false;
        }
        if(empty($data)){
            $this->setError('更新数据的数组不能为空');
            return false;
        }

        $result = M('app_list')->where('id='.$id)->save($data);
        if ($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        if($result === 0 || $result === '0'){
            $this->setError('排序未改变');
            return false;
        }
        return true;
    }

    /**
     * 给id为$id的行设置目标排序n后，原排序为n以及n以后的数据的排序变为自增1
     * @author xy
     * @since 2017/07/04 13:36
     * @param integer $id 媒体站app_list表id
     * @param integer $type 游戏列表类型 1人气最高，2近期更新
     * @param integer $fromSort id为$id的数据的原排序
     * @param integer $targetSort id为$id的数据的目标排序
     * @return bool
     */
    public function autoUpdateOtherMediaAppSort($id,$type,$fromSort, $targetSort){
        if(empty($id) || !is_numeric($id)){
            $this->setError('id参数错误');
            return false;
        }
        if(empty($type) || !in_array($type,self::getListTypeArr())){
            $this->setError('列表类型参数错误');
            return false;
        }
        if(empty($targetSort) || !is_numeric($targetSort)){
            $this->setError('id为'.$id.'的数据的目标排序参数错误');
            return false;
        }
        if($targetSort == $fromSort){
            $this->setError('目标排序与原排序相同，无需修改');
            return false;
        }

        if($type == self::LIST_TYPE_HOT){
            $field = 'pre_hot_sort';
        }else{
            $field = 'pre_new_sort';
        }
        //判断目标排序是否存在
        $isExist = M('app_list')->where($field.' = '.$targetSort.' AND id <> '.$id)->count();
        if($isExist){
            if($fromSort <= 0){
                $where['_string'] = '`'.$field.'` >= '.$targetSort.' AND `id` <> '.$id;
                $result = M('app_list')->where($where)->setInc($field,1);
            }else{
                if($fromSort < $targetSort){
                    //原排序小于目标排序,自减1
                    $where['_string'] = '`'.$field.'` >= '.$fromSort.' AND `'.$field.'` <= '.$targetSort.' AND `id` <> '.$id;
                    $result = M('app_list')->where($where)->setDec($field,1);
                }else if($fromSort > $targetSort){
                    //原排序大于目标排序,自增1
                    $where['_string'] = '`'.$field.'` >= '.$targetSort.' AND `'.$field.'` <= '.$fromSort.' AND `id` <> '.$id;
                    $result = M('app_list')->where($where)->setInc($field,1);
                }else{
                    $this->setError('目标排序与原排序相同，无需修改');
                    return false;
                }
            }
            if($result === false){
                $this->setError(M('app_list')->getDbError());
                return false;
            }
        }

        return true;
    }

    /**
     * 更新媒体站app_list表的最终排序，用于台展示
     * @author xy
     * @since 2017/07/04 16:12
     * @param $type
     * @return bool
     */
    public function updateAppListFinalSortByType($type){
        if(empty($type) || !in_array($type,self::getListTypeArr())){
            $this->setError('列表类型参数错误');
            return false;
        }
        if($type == self::LIST_TYPE_HOT){
            $fromField = 'pre_hot_sort';
            $toField = 'final_hot_sort';
        }else{
            $fromField = 'pre_new_sort';
            $toField = 'final_new_sort';
        }
        $table = M('app_list')->getTableName();
        if($table === false){
            $this->setError('媒体站的游戏库列表不存在');
            return false;
        }
        //将预定义的排序设置成最终排序
        $updateSql = 'UPDATE `'.$table.'` SET `'.$toField.'` = `'.$fromField.'` WHERE ( `'.$toField.'` <> `'.$fromField.'`)';
        //设置排序与最终排序不一致的才更新
        $result = M('app_list')->execute($updateSql);
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        if($result === '0' || $result === 0){
            $this->setError('已是最新排序，无需更新');
            return false;
        }
        return true;
    }


    /**
     * 根据条件获取游戏库详情页列表（按详情创建时间正序排序）
     * @author xy
     * @since 2017/07/04 18:13
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 页大小
     * @return bool
     */
    public function getAppLibDetailPageList($where = NULL,$currentPage,$pageSize,$orderBy = NULL ){
        //获取app_list 按app详情的创建时间升序排序
        $field = 'alist.id, alist.app_id, alist.is_publish, IF(alib.create_time=0, 9999999999, IFNULL(alib.create_time, 9999999999)) as lib_create_time, IFNULL(alib.app_name, lib.app_name) as app_name, list.status';
        $defaultOrderBy = 'lib_create_time asc';
        if(!empty($orderBy)){
            $defaultOrderBy .= ', '.$orderBy;
        }
        $appList = M('app_list')->alias('alist')
            ->field($field)
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id') //关联总库的app_list获取发布状态
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id') //关联总库的游戏库获取游戏名
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')
            ->where($where)
            ->order($defaultOrderBy)
            ->limit($currentPage,$pageSize)
            ->select();

        if($appList === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $appList;
    }

    /**
     * 媒体站游戏发布操作, 此方法弃用，since 2017/08/24 17:49 xy
     * @author xy
     * @since 2017/07/05 10:19
     * @param integer $listId app_list表id
     * @param string $optType 游戏发布操作类型 发布或者下架
     * @return bool
     */
    public function mediaAppShelveOption($listId,$optType){
        if(empty($listId) || !is_numeric($listId)){
            $this->setError('游戏列表id参数错误');
            return false;
        }
        //判断是否属于发布、下架操作中的一种
        if(empty($optType) || !in_array($optType,self::getAppShelveOptionArray())){
            $this->setError('游戏操作类型参数错误');
            return false;
        }
        //判读媒体站app_list表id为$listId的数据是否存在
        $appData = $this->loadAppListByPk($listId);
        if($appData === false){
            $this->setError('游戏列表id为'.$listId.'的数据不存在');
            return false;
        }
        //判读是否已经添加游戏详情
        $appLibData = M('app_lib')->where('app_id = '.$appData['app_id'])->find();
        if(empty($appLibData)){
            $this->setError('尚未添加游戏详情，无法发布或下架游戏');
            return false;
        }
        if($optType == self::SHELVE_GAME){
            //执行发布操作，如果是未发布状态，执行发布操作，否则返回已发布提示
            if($appData['is_publish'] != 1){
                $appData['is_publish'] = 1;
                $appData['publish_time'] = time();
                $result = M('app_list')->save($appData);
                if($result === false){
                    $this->setError('游戏发布失败');
                    return false;
                }
                return true;
            }else{
                $this->setError('该游戏已是发布状态');
                return false;
            }
        }else if($optType == self::UN_SHELVE_GAME){
            //执行下架操作，如果是未发布状态，则提示已下架，否则执行下架操作
            if($appData['is_publish'] == 2){
                $this->setError('该游戏已是下架状态');
                return false;
            }else{
                $appData['is_publish'] = 2;
                $result = M('app_list')->save($appData);
                if($result === false){
                    $this->setError('游戏下架失败');
                    return false;
                }
                return true;
            }
        }else{
            $this->setError('游戏操作类型参数错误');
            return false;
        }
    }

    /**
     * 根据app_id主键判读指娱总库是否存在app_id为$appId的数据
     * @author xy
     * @since 2017/07/06 09:32
     * @param integer $appId 指娱app_lib表主键
     * @return bool
     */
    public function isZhiYuAppLibExistByPk($appId){
        if(empty($appId) || !is_numeric($appId)){
            $this->setError('id参数错误');
            return false;
        }
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'app_lib', C('DB_ZHIYU.DB_PREFIX'))
            ->where('app_id='.$appId)
            ->count();
        if($result === false){
            $this->setError(M(C('DB_ZHIYU.DB_NAME').'.'.'app_lib', C('DB_ZHIYU.DB_PREFIX'))->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据主键id从指娱app_lib表获取数据
     * @author xy
     * @since 2017/07/06
     * @param integer $appId 主键id
     * @return bool|mixed
     */
    public function getAppLibInfoFromZhiYuByPk($appId){
        if(empty($appId) || !is_numeric($appId)){
            $this->setError('id参数错误');
            return false;
        }
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'app_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('lib')
            ->field('lib.*,s.supplier_name')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on s.supplier_id=lib.supplier_id')//关联游戏厂商表
            ->where('app_id='.$appId)
            ->find();
        if($result === false){
            $this->setError(M(C('DB_ZHIYU.DB_NAME').'.'.'app_lib', C('DB_ZHIYU.DB_PREFIX'))->getDbError());
            return false;
        }
        if(empty($result)){
            $this->setError('app_id为'.$appId.'的数据不存在');
            return false;
        }
        return $result;
    }

    /**
     * 根据app_id从媒体站的app_lib表获取数据
     * @author xy
     * @since 2017/07/06 11:04
     * @param $appId
     * @return bool|mixed
     */
    public function getAppLibDataFromMediaByPk($appId){
        if(empty($appId) || !is_numeric($appId)){
            $this->setError('id参数错误');
            return false;
        }
        $result = M('app_lib')->alias('alib')
            ->field('alib.*, s.supplier_name')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on s.supplier_id=alib.supplier_id')//关联游戏厂商表
            ->where('alib.app_id = '.$appId)
            ->find();
        if($result === false){
            $this->setError($this->getDbError());
            return false;
        }
        if(empty($result)){
            $this->setError('app_id为'.$appId.'的数据不存在');
            return false;
        }

        return $result;
    }

    /**
     * 从指娱app_type表获取游戏类型
     * @author xy
     * @since 2017/07/06 11:55
     * @return bool
     */
    public function getAppTypeFromZhiYu(){
        $result = M(C('DB_ZHIYU.DB_NAME').'.'.'app_type', C('DB_ZHIYU.DB_PREFIX'))->alias('atype')
            ->field('atype.id, atype.type_name as label, atype.parent_id')
            //->where('`parent_id` IS NULL OR `parent_id` = 0 OR `parent_id` = ""')
            ->order('sort ASC')
            ->select();
        if($result === false){
            $this->setError(M(C('DB_ZHIYU.DB_NAME').'.'.'app_type', C('DB_ZHIYU.DB_PREFIX'))->getDbError());
            return false;
        }
        if(empty($result)){
            $this->setError('未找到数据');
            return false;
        }
        foreach ($result as $key => $value)
        {
            // 添加记录深度的字段
            $result[$key]['level'] = 0;
            // 添加是否有子类的字段
            $result[$key]['has_child'] = 0;
            // 查找是否有子类
            foreach ($result as $temp_key => $temp_value)
            {
                if($result[$key]['id'] == $temp_value['parent_id'])
                {
                    $result[$key]['has_child'] = 1;
                    break;
                }
            }
        }
        $result = get_app_type_tree_recursive($result,0,1);
        return $result;
    }

    /**
     * 根据app_id查询指定条数的游戏详情页攻略
     * @author xy
     * @since 2017/07/06 16:36
     * @param $appId
     * @param integer $limit
     * @return bool|mixed
     */
    public function getAppGuideByAppId($appId,$limit = 6){
        if(empty($appId) || !is_numeric($appId)){
            $this->setError('id参数错误');
            return false;
        }
        if($limit !== NULL){
            if(!is_numeric($limit)){
                $this->setError('查询条数有误');
                return false;
            }
        }
        $appGuideList = M('app_guide')
            ->where('app_id = '.$appId)
            ->limit($limit)
            ->order('sort ASC')
            ->select();
        if($appGuideList === false){
            $this->setError(M('app_guide')->getDbError());
            return false;
        }
        $appGuideSortList = NULL;
        if($appGuideList !== NULL){
            foreach ($appGuideList as $list){
                $appGuideSortList[$list['sort']] = $list;
            }
        }
        return $appGuideSortList;
    }

    /**
     * 保存游戏库app详情信息以及图文攻略，修改app的发布状态
     * @author xy
     * @since 2017/07/07 13:39
     * @param integer $appId  游戏的appid
     * @param array $appLibData 表单提交的游戏详情
     * @param array $appLibGuide 表单提交的图文攻略
     * @param int $publish 是否发布 0否 1是
     * @return bool
     */
    public function saveAppLibDetainInfo($appId,array $appLibData,array $appLibGuide,$publish=0){
        if(empty($appId) || !is_numeric($appId)){
            $this->setError('id参数错误');
            return false;
        }
        if(!is_array($appLibData)||empty($appLibData)){
            $this->setError('游戏库数据格式不正确');
            return false;
        }
        if(!is_array($appLibGuide)||empty($appLibGuide)){
            $this->setError('游戏库图文攻略数据格式不正确');
            return false;
        }
        M()->startTrans();
        //保存游戏库详情数据
        $count = M('app_lib')->where('app_id = '.$appId)->count();
        if($count){
            $result = M('app_lib')->where('app_id = '.$appId)->save($appLibData);
        }else{
            $result = M('app_lib')->add($appLibData);
        }

        if($result === false){
            $this->setError(M('app_lib')->getDbError());
            M()->rollback();
            return false;
        }

        //删除原来保存的图文攻略
        $result = M('app_guide')->where('app_id = '.$appId)->delete();
        if($result === false){
            $this->setError(M('app_guide')->getDbError());
            M()->rollback();
            return false;
        }
        //保存新的图文攻略
        $result = M('app_guide')->addAll($appLibGuide);
        if($result === false){
            $this->setError(M('app_guide')->getDbError());
            M()->rollback();
            return false;
        }
        //判读媒体站app_list表是否有app_id为$appId的数据
        $result = $this->isMediaAppListDataExistByAppId($appId);
        if($result === false){
            M()->rollback();
            return false;
        }
        if($result === 0 || $result === '0'){
            $this->setError('媒体站游戏列表不存在app_id为'.$appId.'的数据');
            M()->rollback();
            return false;
        }
        //更新游戏发布状态
        if(!empty($publish)){
            $data['is_publish'] = 1; // 1为发布
            $result = M('app_list')->where('app_id = '.$appId)->save($data);
            if($result === false){
                $this->setError('更新游戏发布状态失败');
                M()->rollback();
                return false;
            }
        }
        M()->commit();
        return true;

    }

    /**
     * 通过游戏名称获取游戏id以及名称
     * @author xy
     * @since 2017/07/10 18:45
     * @param string $appName 游戏名称
     * @return mixed
     */
    public function getAppInfoByName($appName){
        $where = '';
        if(!empty($appName)){
            $where = "alib.app_name like '%".$appName."%'";
        }
        $result = M('app_list')->alias('alist')
            ->field('alist.app_id, IFNULL(alib.app_name, lib.app_name) as value, alist.is_publish, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.introduct, lib.introduct) as introduct, alib.video_id, vlib.video_id, vlib.video_name, vlib.video_url, vlib.video_img, vlib.file_id, vlib.iqiyi_index, lib.app_file_url')
            ->join('INNER JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')//关联媒体库游戏库表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')//关联游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'video_lib vlib on vlib.video_id=alib.video_id')
            ->where($where)
            ->select();
        if(!$result){
            return $this->setError('未找到对应数据');
        }
        foreach ($result as $key=>$app){
            //通过视频的file_id从爱奇艺获取托管视频url以及视频相关信息
            if(!empty($app['file_id'])){
                $result[$key]['video_url'] = $this->getIqiyiVideoUrl($app['file_id'], 2, $app['iqiyi_index']);

                if ($result[$key]['video_url']) {
                    $iqiyiVideoInfo = $this->getIqiyiVideoInfo($app['file_id'], $app['iqiyi_index']);
                    $result[$key]['video_info'] = [
                        'video_name' => $app['video_name'],
                        'video_url' => $app['video_url'],
                        'video_img' => format_url($app['video_img']),
                        'video_size' => $iqiyiVideoInfo['fileSize'],
                        'video_duration' => $iqiyiVideoInfo['duration'],
                        'file_id' => $app['file_id'],
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * 根据游戏id获取游戏 相关信息
     * @author xy
     * @since 2017/08/18 15:43
     * @param $appId
     * @return bool
     */
    public function getAppInfoByAppId($appId){
        $where = array(
            //已上架与测试上架的游戏
            'alist.is_publish' => array('IN', array(1))
        );
        if(!empty($appId)){
            $where['alist.app_id '] = $appId;
        }
        //媒体站有游戏详情的优先读取详情数据，没有的话则读取库的数据
        $result = M('app_list')->alias('alist')
            ->field('alist.app_id, IFNULL(alib.app_name, lib.app_name) as value, IFNULL(alib.introduct, lib.introduct) as introduct, IFNULL(alib.app_name, lib.app_name) as app_name, vlib.video_id, vlib.video_name, vlib.video_url, IFNULL(vlib.video_img, alib.cover_img) as video_img, vlib.file_id, vlib.iqiyi_index, lib.app_file_url')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id=alist.app_id')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')//关联游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')//关联媒体库游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'video_lib vlib on vlib.video_id=lib.video_id')
            ->where($where)
            ->select();

        if(!$result){
            return $this->setError('未找到对应数据');
        }
        foreach ($result as $key=>$app){
            //通过视频的file_id从爱奇艺获取托管视频url以及视频相关信息
            if(!empty($app['file_id'])){
                $result[$key]['video_url'] = $this->getIqiyiVideoUrl($app['file_id'], 2, $app['iqiyi_index']);

                if ($result[$key]['video_url']) {
                    $iqiyiVideoInfo = $this->getIqiyiVideoInfo($app['file_id'], $app['iqiyi_index']);
                    $result[$key]['video_info'] = [
                        'video_name' => $app['video_name'],
                        'video_url' => $result[$key]['video_url'],
                        'video_img' => format_url($app['video_img']),
                        'video_size' => $iqiyiVideoInfo['fileSize'],
                        'video_duration' => $iqiyiVideoInfo['duration'],
                        'file_id' => $app['file_id'],
                    ];
                }
            }
            //前台游戏详情链接
            $result[$key]['detail_url'] = C('BASE_URL').U('Home/App/app_detail/', array('app_id' => $app['app_id']));
        }
        return $result;
    }

    /**
     * 根据游戏id获取游戏 相关信息
     * @author xy
     * @since 2017/08/18 15:43
     * @param $appId
     * @return bool
     */
    public function getAllAppInfoByAppId($appId){
        $where = array();
        if(!empty($appId)){
            $where['alist.app_id '] = $appId;
        }
        //媒体站有游戏详情的优先读取详情数据，没有的话则读取库的数据
        $result = M('app_list')->alias('alist')
            ->field('alist.app_id,  IFNULL(alib.app_name, lib.app_name) as value,  IFNULL(alib.app_name, lib.app_name) as app_name')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list a on a.app_id=alist.app_id')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id=alist.app_id')//关联游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id=alist.app_id')//关联媒体库游戏库表
            ->where($where)
            ->select();

        if(!$result){
            return $this->setError('未找到对应数据');
        }
        return $result;
    }

    /**
     * 根据条件获取app下载量月榜的数据的条数
     * @author xy
     * @since 2017/07/20 14:11
     * @param null $where 查询条件
     * @return bool
     */
    public function getLastMonthAppDownloadListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE date_format(FROM_UNIXTIME(down_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个月下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        //var_dump($this->appListModel->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app上个月的下载量分页列表
     * @author xy
     * @since 2017/07/20 13:35
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getLastMonthAppDownloadListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, list.`status`, adown.`down_num` AS app_down_num, arank.rank_id, IF( arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE date_format(FROM_UNIXTIME(down_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个月下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC, pre_sort ASC, app_down_num DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
//        var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据条件获取app下载量周榜的数据的条数
     * @author xy
     * @since 2017/07/20 14:11
     * @param null $where 查询条件
     * @return bool
     */
    public function getLastWeekAppDownloadListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE YEARWEEK(date_format(FROM_UNIXTIME(down_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1 GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个月下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app上周的下载量分页列表
     * @author xy
     * @since 2017/07/20 13:35
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getLastWeekAppDownloadListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, list.`status`, adown.`down_num`  AS app_down_num, arank.rank_id, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE YEARWEEK(date_format(FROM_UNIXTIME(down_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1 GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个月下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC, pre_sort ASC, app_down_num DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据条件获取app下载量总榜的数据的条数
     * @author xy
     * @since 2017/07/20 14:11
     * @param null $where 查询条件
     * @return bool
     */
    public function getAppDownloadListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app下载总量分页列表
     * @author xy
     * @since 2017/07/20 14:04
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getAppDownloadListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, list.`status`, (list.app_down_num + list.cardinal) as app_down_num, arank.rank_id, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC , pre_sort ASC, app_down_num DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 更新媒体站app_ranking表的最终排序，用于前台展示
     * @author xy
     * @since 2017/07/24 10:02
     * @param int $rankType 榜单类型 下载榜 畅游版 总榜
     * @param int $dataSource 判断是周榜，月榜，总榜
     * @return bool
     */
    public function updateAppRankFinalSortByType($rankType,$dataSource){
        $fromField = 'pre_sort';
        $toField = 'final_sort';

        $table = M('app_ranking')->getTableName();
        if($table === false){
            $this->setError('媒体站排行榜表不存在');
            return false;
        }
        //将预定义的排序设置成最终排序
        $updateSql = 'UPDATE `'.$table.'` SET `'.$toField.'` = `'.$fromField.'` WHERE ( `'.$toField.'` <> `'.$fromField.'` AND `ranking_type` = '.$rankType.' AND `data_source` = '.$dataSource.')';
        //设置排序与最终排序不一致的才更新
        $result = M('app_ranking')->execute($updateSql);
        if($result === false){
            $this->setError(M('app_ranking')->getDbError());
            return false;
        }
        if($result === '0' || $result === 0){
            $this->setError('已是最新排序，无需更新');
            return false;
        }
        return true;
    }

    /**
     * 根据条件获取app充值周榜的数据的条数
     * @author xy
     * @since 2017/07/24 10:43
     * @param null $where 查询条件
     * @return bool
     */
    public function getLastWeekAppPayListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE YEARWEEK(date_format(FROM_UNIXTIME(create_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1 AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个礼拜app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app上周的充值金额分页列表
     * @author xy
     * @since 2017/07/24 10:44
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getLastWeekAppPayListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, list.`status`, pay.`total_money`, arank.rank_id, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE YEARWEEK(date_format(FROM_UNIXTIME(create_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1  AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个礼拜app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC, pre_sort ASC, total_money DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据条件获取app充值金额月榜的数据的条数
     * @author xy
     * @since 2017/07/24 10:59
     * @param null $where 查询条件
     * @return bool
     */
    public function getLastMonthAppPayListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE date_format(FROM_UNIXTIME(create_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\')  AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个月充值金额(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app上个月的充值金额分页列表
     * @author xy
     * @since 2017/07/24 11:01
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getLastMonthAppPayListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, list.`status`, pay.`total_money`, arank.rank_id, IF( arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE date_format(FROM_UNIXTIME(create_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个月app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC, pre_sort ASC, total_money DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
//        var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据条件获取app充值总榜的数据的条数
     * @author xy
     * @since 2017/07/24 11:02
     * @param null $where 查询条件
     * @return bool
     */
    public function getAppPayListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app充值总量分页列表
     * @author xy
     * @since 2017/07/24 11:04
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getAppPayListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, list.`status`, (list.pay_total_money ) as total_money, arank.rank_id, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC, pre_sort ASC, total_money DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }

    /**
     * 根据条件获取app新游总榜的数据的条数
     * @author xy
     * @since 2017/07/24 11:02
     * @param null $where 查询条件
     * @return bool
     */
    public function getAppNewListTotalNum($where = NULL){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 2 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->where($where)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return count($result);
    }

    /**
     * 根据条件获取app新游榜总榜分页列表
     * @author xy
     * @since 2017/07/24 11:04
     * @param null $where 查询条件
     * @param integer $currentPage 当前页
     * @param integer $pageSize 每页大小
     * @return bool
     */
    public function getAppNewListByPage($where = NULL, $currentPage, $pageSize){
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, alib.`app_name`, alist.is_publish, alist.publish_time, list.`status`, list.`sj_time`, arank.`rank_id`, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 2 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('alist.is_publish ASC, pre_sort ASC, alist.publish_time DESC')
            ->where($where)
            ->limit($currentPage,$pageSize)
            ->select();
        //echo M('app_list')->getLastSql();die;
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        return $result;
    }


    /**
     * 获取游戏发布操作的数组
     * @author xy
     * @since 2017/07/05 09:42
     * @return array
     */
    public static function getAppShelveOptionArray(){
        return array(
            self::SHELVE_GAME => self::SHELVE_GAME,
            self::UN_SHELVE_GAME => self::UN_SHELVE_GAME,
        );
    }

    /**
     * 获取列表的类型数组
     * @author xy
     * @since 2017/07/03 17:04
     * @return array
     */
    public static function getListTypeArr(){
        return array(
            self::LIST_TYPE_HOT => self::LIST_TYPE_HOT,
            self::LIST_TYPE_NEW => self::LIST_TYPE_NEW,
        );
    }

    /**
     * 获取游戏库的游戏id以及游戏名称
     * @author xy
     * @since 2017/07/28 11:19
     * @return bool
     */
    public static function getAllAppIdAndName(){
        $appList = M(C('DB_ZHIYU.DB_NAME').'.'.'app_list', C('DB_ZHIYU.DB_PREFIX'))->alias('alist')
            ->field('alist.`app_id`, alib.`app_name` as value, alib.`app_name`')
            ->JOIN('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->select();
        if(empty($appList)){
            return false;
        }
        return $appList;
    }

    /**
     * 通过app_id获取游戏的id与名称
     * @author xy
     * @since 2017/07/28 11:23
     * @param $appId
     * @return bool
     */
    public static function getAppIdAndNameByAppId($appId){
        if(empty($appId)){
            return false;
        }
        $app =  M(C('DB_ZHIYU.DB_NAME').'.'.'app_list', C('DB_ZHIYU.DB_PREFIX'))->alias('alist')
            ->field('alist.`app_id`, alib.`app_name` as value, alib.`app_name`')
            ->JOIN('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->where('alist.app_id = '.$appId)
            ->find();
        if(empty($app)){
            return false;
        }
        return $app;

    }

    /**
     * 通过app_id获取游戏名称
     * @author xy
     * @since 2017/07/31 18:13
     * @param $appId
     * @return bool
     */
    public static function getAppNameByAppId($appId){
        if(empty($appId)){
            return false;
        }
        $appInfo = M(C('DB_ZHIYU.DB_NAME').'.'.'app_lib', C('DB_ZHIYU.DB_PREFIX'))
            ->field('app_name')
            ->where('app_id = '.$appId)
            ->find();
        if(!empty($appInfo)){
            return $appInfo['app_name'];
        }
        return false;
    }


    /**
     * 从指娱总库获取应用厂商数据
     * @author xy
     * @since 2017/08/09 17:39
     * @param $supplierId
     * @return bool|mixed
     */
    public function getSupplierInfoFromZhiYu($supplierId){
        $where = array();
        if(empty($supplierId)){
            $supplierList = M(C('DB_ZHIYU.DB_NAME').'.'.'supplier', C('DB_ZHIYU.DB_PREFIX'))
                ->field('*,supplier_name as value')
                ->where($where)
                ->select();
        }else{
            $where['supplier_id'] = $supplierId;
            $supplierList = M(C('DB_ZHIYU.DB_NAME').'.'.'supplier', C('DB_ZHIYU.DB_PREFIX'))
                ->field('*,supplier_name as value')
                ->where($where)
                ->select();
        }
        if(empty($supplierList)){
            $this->setError('未找到对应数据');
            return false;
        }
        return $supplierList;
    }


}