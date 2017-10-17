<?php
/**
 * 游戏相关的业务操作
 * User: xy
 * Date: 2017/8/29
 * Time: 18:38
 */

namespace Home\Service;
use Common\Service\BaseService;
use Org\Util\PinYin;
use Think\Model;
use Think\Think;

class AppService extends BaseService
{
    const RANk_TYPE_DOWNLOAD = 0;       //下载榜
    const RANK_TYPE_PAY = 1;            //畅销榜
    const RANK_TYPE_NEW = 2;            //新游榜

    const DATA_SOURCE_WEEK = 1;         //周榜
    const DATA_SOURCE_MONTH = 2;        //月榜
    const DATA_SOURCE_TOTAL = 3;        //总榜

    const TOPIC_TYPE_TPL = 1;           //游戏专题展示类型 1模板
    const TOPIC_TYPE_EDITOR = 2;        //游戏专题展示类型 2编辑器
    const TOPIC_TYPE_H5 = 3;            //游戏专题展示类型 3 H5链接

    /**
     * 榜单类型数组
     * @author xy
     * @since 2017/08/30 15:34
     * @return array
     */
    protected function getRankTypeArr(){
        return array(
            self::RANk_TYPE_DOWNLOAD,
            self::RANK_TYPE_PAY,
            self::RANK_TYPE_NEW,
        );
    }

    /**
     * 榜单数量类型
     * @author xy
     * @since 2017/08/21 18:19
     * @return array
     */
    protected function getDataSourceArr (){
        return array(
            self::DATA_SOURCE_WEEK,
            self::DATA_SOURCE_MONTH,
            self::DATA_SOURCE_TOTAL,
        );
    }

    /**
     * 获取已上架游戏的id组成的数组
     * @author xy
     * @since 2017/09/01 09:30
     * @return array
     */
    public function getPublishAppIdArray(){
        $where = array(
            'alist.is_delete' => array('IN', array(1)), //媒体站已上架的游戏
            'alist.publish_time' => array('lt', time()) //上架时间小于当前时间
        );
        //获取已经上架的游戏的id
        $appIdList = M('app_list')->alias('alist')
            ->field('alist.app_id')
            ->where($where)
            ->select();
        $appIdArr = array();
        if(!empty($appIdList)){
            foreach ($appIdList as $value){
                $appIdArr[] = $value['app_id'];
            }
        }
        return $appIdArr;
    }
    /**
     * 获取网站首页的热游推荐游戏列表
     * @author xy
     * @since 2017/08/30 10:48
     * @param int $limit 默认查找的游戏数量
     * @return bool
     */
    public function getIndexHotRecommendAppNameAndIcon($limit = 8){
        //获取已上架游戏的appid
        $where = array(
            'alist.is_publish' => array('IN', array(1)), //媒体站已上架的游戏
            'alist.publish_time' => array('lt', time()), //上架时间小于当前时间
            'alib.is_hot' => 1, //设置为热游推荐
        );

        $hotAppList = M('app_list')->alias('alist')
            ->field(
                'alist.app_id, list.status, IFNULL(alib.start_score, lib.start_score) as start_score, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, 
                IF(alist.final_hot_sort=0, 9999999, alist.final_hot_sort) as final_hot_sort, 
                (list.app_down_num + list.cardinal) as app_down_num
                ')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list list on list.app_id=alist.app_id')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->where($where)
            ->order('final_hot_sort ASC, app_down_num desc')
            ->limit($limit)
            ->select();
        if($hotAppList === false){
            return $this->setError('查询失败');
        }
        if(empty($hotAppList)){
            return $this->setError('未查询到对应游戏数据');
        }
        return $hotAppList;
    }

    /**
     * 获取网站热游推戏列表
     * @author xy
     * @since 2017/08/30 10:48
     * @param int $limit 默认查找的游戏数量
     * @return bool
     */
    public function getHotAppNameAndIcon($limit = 8){
        //获取已上架游戏的appid
        $where = array(
            'alist.is_publish' => array('IN', array(1)), //媒体站已上架的游戏
            'alist.publish_time' => array('lt', time()), //上架时间小于当前时间
        );

        $hotAppList = M('app_list')->alias('alist')
            ->field(
                'alist.app_id, list.status, IFNULL(alib.start_score, lib.start_score) as start_score, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, 
                IF(alist.final_hot_sort=0, 9999999, alist.final_hot_sort) as final_hot_sort, 
                (list.app_down_num + list.cardinal) as app_down_num
                ')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list list on list.app_id=alist.app_id')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->where($where)
            ->order('final_hot_sort ASC, app_down_num desc')
            ->limit($limit)
            ->select();
        if($hotAppList === false){
            return $this->setError('查询失败');
        }
        if(empty($hotAppList)){
            return $this->setError('未查询到对应游戏数据');
        }
        return $hotAppList;
    }

    /**
     * 获取首页新游一览的游戏名称与icon
     * @author xy
     * @since 2017/08/30 11:10
     * @param int $limit 默认展示数量
     * @return bool
     */
    public function getIndexNewAppNameAndIcon($limit = 18){
        //获取已上架游戏的appid
        $where = array(
            'alist.is_publish' => array('IN', array(1)), //媒体站已上架的游戏
            'alist.publish_time' => array('lt', time()) //上架时间小于当前时间
        );
        $newAppList = M('app_list')->alias('alist')
            ->field('list.app_id, list.status, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IF(alist.final_new_sort=0, 9999999, alist.final_new_sort) as final_new_sort, list.sj_time')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list list on list.app_id=alist.app_id')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->where($where)
            ->order('final_new_sort ASC, sj_time desc')
            ->limit($limit)
            ->select();
        if($newAppList === false){
            return $this->setError('查询失败');
        }
        if(empty($newAppList)){
            return $this->setError('未查询到对应游戏数据');
        }

        return $newAppList;
    }

    /**
     * 获取首页的精选游戏（游戏评分要大于4.5）
     * @author xy
     * @since 2017/08/31 11:30
     * @return array|bool
     */
    public function getIndexCarefulChoiceAppNameAndIcon() {
        //获取媒体站已上架游戏的appid
        $where = array(
            '_string' => '(lib.start_score >= 4.5 OR alib.start_score >= 4.5)' ,//游戏评分超过4.5的为精选游戏
            'alist.is_publish' => array('IN', array(1)), //媒体站已上架的游戏
            'alist.publish_time' => array('lt', time()), //上架时间小于当前时间
        );
        $appList = M('app_list')->alias('alist')
            ->field('list.app_id, list.status, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.start_score, lib.start_score) as start_score, lib.update_time as zy_update_time, alib.start_score, alib.update_time')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list list on list.app_id=alist.app_id')
            ->join('INNER JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib lib on lib.app_id=alist.app_id')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->where($where)
            ->order('alib.update_time DESC, zy_update_time DESC')
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        if(empty($appList)){
            return $this->setError('未查询到对应的数据');
        }
        //用来保存处理后的游戏数组
        $tempArr = array();
        foreach ($appList as $key => $app){
            //取游戏的第一个字符，转换为拼音
            $pyChar = get_string_first_char_pinyin($app['app_name']);
            if(empty($pyChar)){
                $pyChar = 'A';
            }
            $tempArr[$pyChar][] = $app;
        }
        $returnArr = array(
            'ABCD' => array(),
            'EFGH' => array(),
            'IJKL' => array(),
            'MNOP' => array(),
            'QRST' => array(),
            'UVWX' => array(),
            'YZ' => array(),
        );
        $arrayKeys = array_keys($tempArr);
        sort($arrayKeys);
        foreach ($arrayKeys as $letter){
            if(in_array($letter, array('A','B','C','D'))){
                $returnArr['ABCD'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('E','F','G','H'))){
                $returnArr['EFGH'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('I','J','K','L'))){
                $returnArr['IJKL'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('M','N','O','P'))){
                $returnArr['MNOP'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('R','S','T','U'))){
                $returnArr['QRST'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('U','V','W','X'))){
                $returnArr['UVWX'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('Y','Z'))){
                $returnArr['YZ'][$letter] = $tempArr[$letter];
            }
        }
        //var_dump($returnArr['ABCD']);die;
        return $returnArr;
    }

    /**
     * 获取指定数量首页的游戏礼包
     * @author xy
     * @since 2017/08/30 14:54
     * @param int $limit 查询数量
     * @return bool
     */
    public function getIndexHotAppGift($limit = 10){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        //获取媒体站已上架的游戏的礼包
        $where['list.is_publish'] = array('IN', array(1));

        //礼包码表中各个批次被媒体站占用的游戏礼包
        $subQueryOne = ' SELECT gift_id, count(*) AS total_code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 GROUP BY gift_id ';

        //媒体站礼包库中礼包id对应的游戏以及礼包全名，避免通过gift_id关联查询时对应的礼包已删除无法正确关联正确的礼包
        $subQueryTwo = ' SELECT a.*, CONCAT(glib.app_id,glib.gift_name,glib.original_name) AS id_gift_name FROM ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS a LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS glib ON glib.gift_id = a.gift_id ';

        //礼包库中被媒体站占用的被领取的礼包码数量
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL GROUP BY gift_id';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field('sgl.gift_id as sync_gift_id, sgl.limited_count, IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, CONCAT(al.app_name,gl.gift_name,gl.original_name) AS full_gift_name, CONCAT(al.app_id,gl.gift_name,gl.original_name) AS id_gift_name, sum(cn.total_code_num) as total_code_num, sum(un.code_use_num) as total_use_num, (alist.app_down_num+alist.cardinal) as down_num')
            //获取游戏礼包媒体站占用的礼包数量, INNER JOIN 避免取到没有申请到媒体站的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //获取游戏礼包媒体站已领取的礼包数量
            ->join('LEFT JOIN (' . $subQueryThree . ') AS un ON un.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = gl.`app_id`')
            //关联获取下载量与上架时间
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS alist ON alist.`app_id` = gl.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('INNER JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,gl.gift_name,gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->order('final_hot_sort ASC, down_num DESC ')
            ->limit($limit)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }
        if(empty($giftList)){
            return $this->setError('未找到对应礼包数据');
        }
        return $giftList;
    }

    /**
     * 获取首页的下载榜周榜指定条数的数据
     * @author xy
     * @since 2017/08/30 15:40
     * @param int $limit
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getHotAppRankWeek($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['adown.down_num'] = array('neq', 0);
        $orderBy = 'final_sort ASC, app_down_num DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, app_down_num DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, 
                IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, adown.`down_num` AS app_down_num, arank.rank_id, 
                IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 )) as final_sort, 
                IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort
                '
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE YEARWEEK(date_format(FROM_UNIXTIME(down_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1 GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个礼拜下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', $tempTypeNameArr);
                    $result[$key]['parent_type_name_str'] = implode('、', $tempParentTypeNameArr);
                }
            }
        }
        return $result;
    }

    /**
     * 获取首页的下载榜月榜指定条数的数据
     * @author xy
     * @since 2017/08/30 15:40
     * @param int $limit
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getHotAppRankMonth($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['adown.down_num'] = array('neq', 0);
        $orderBy = 'final_sort ASC, app_down_num DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, app_down_num DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, 
                IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, 
                IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, adown.`down_num` AS app_down_num, arank.rank_id, 
                IF( arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort, 
                IF( arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE date_format(FROM_UNIXTIME(down_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个月下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
//        var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', $tempTypeNameArr);
                    $result[$key]['parent_type_name_str'] = implode('、', $tempParentTypeNameArr);
                }
            }
        }
        return $result;
    }

    /**
     * 获取首页指定条数下载榜总榜的数据
     * @author xy
     * @since 2017/08/30 15:48
     * @param int $limit
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getHotAppRankTotal($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['(list.app_down_num + list.cardinal)'] = array('neq', 0);
        $orderBy = 'final_sort ASC, app_down_num DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, app_down_num DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, (list.app_down_num + list.cardinal) as app_down_num, arank.rank_id, 
                IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort, 
                IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', array_unique($tempTypeNameArr));
                    $result[$key]['parent_type_name_str'] = implode('、', array_unique($tempParentTypeNameArr));
                }
            }
        }
        return $result;
    }

    /**
     * 根据数据类型获取指定条数的热游榜的数据
     * @author xy
     * @since 2017/08/30 15:51
     * @param int $dataSource 1周榜，2月榜，3总榜
     * @param int $limit 查询数量
     * @return bool
     */
    public function getIndexHotAppRank($dataSource, $limit = 10){
        //判断榜单的数据类型是否正确
        if(!in_array($dataSource, $this->getDataSourceArr())){
            return $this->setError('热游版排行榜数据类型错误');
        }
        if ($dataSource == self::DATA_SOURCE_WEEK) {
            //周榜
            $rankList = $this->getHotAppRankWeek($limit);
        } elseif ($dataSource == self::DATA_SOURCE_MONTH) {
            //月榜
            $rankList = $this->getHotAppRankMonth($limit);
        } else {
            //总榜
            $rankList = $this->getHotAppRankTotal($limit);
        }
        return $rankList;
    }

    /**
     * 获取指定条数的游戏畅销榜周榜的数据
     * @author xy
     * @since 2017/08/30 16:07
     * @param int $limit 查询条数
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getPopularAppRankWeek($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['total_money'] = array('neq', 0);
        $orderBy = 'final_sort ASC, total_money DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, total_money DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, pay.`total_money`, arank.rank_id, 
                IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 )) as final_sort,  
                IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE YEARWEEK(date_format(FROM_UNIXTIME(create_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1  AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个礼拜app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', $tempTypeNameArr);
                    $result[$key]['parent_type_name_str'] = implode('、', $tempParentTypeNameArr);
                }
            }
        }
        return $result;
    }

    /**
     * 获取指定条数的游戏畅销榜月榜的数据
     * @author xy
     * @since 2017/08/30 16:07
     * @param int $limit 查询条数
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getPopularAppRankMonth($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['total_money'] = array('neq', 0);
        $orderBy = 'final_sort ASC, total_money DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, total_money DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, pay.`total_money`, arank.rank_id, 
                IF( arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort,  
                IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE date_format(FROM_UNIXTIME(create_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个月app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
//        var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', $tempTypeNameArr);
                    $result[$key]['parent_type_name_str'] = implode('、', $tempParentTypeNameArr);
                }
            }
        }
        return $result;
    }

    /**
     * 获取指定条数的游戏畅销榜总榜的数据
     * @author xy
     * @since 2017/08/30 16:07
     * @param int $limit 查询条数
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getPopularAppRankTotal($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['(list.pay_total_money)'] = array('neq', 0);
        $orderBy = 'final_sort ASC, total_money DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, total_money DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, (list.pay_total_money ) as total_money, arank.rank_id, 
                IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort,  
                IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', $tempTypeNameArr);
                    $result[$key]['parent_type_name_str'] = implode('、', $tempParentTypeNameArr);
                }
            }
        }
        return $result;
    }

    public function getIndexPopularAppRank($dataSource, $limit = 10){
        //判断榜单的数据类型是否正确
        if(!in_array($dataSource, $this->getDataSourceArr())){
            return $this->setError('热游版排行榜数据类型错误');
        }
        if ($dataSource == self::DATA_SOURCE_WEEK) {
            //周榜
            $rankList = $this->getPopularAppRankWeek($limit);
        } elseif ($dataSource == self::DATA_SOURCE_MONTH) {
            //月榜
            $rankList = $this->getPopularAppRankMonth($limit);
        } else {
            //总榜
            $rankList = $this->getPopularAppRankTotal($limit);
        }
        return $rankList;
    }

    /**
     * 获取指定条数的新游榜总榜数据
     * @author xy
     * @since 2017/08/30 16:19
     * @param int $limit
     * @param bool $isPreview 是否预览
     * @return bool
     */
    public function getNewAppRankTotal($limit = 10, $isPreview = false){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $orderBy = 'final_sort ASC, publish_time DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, publish_time DESC';
        }
        $result = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, alist.publish_time, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                list.`status`, list.`sj_time`, arank.`rank_id`, 
                IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort,  
                IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 2 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order($orderBy)
            ->where($where)
            ->limit($limit)
            ->select();
        //echo M('app_list')->getLastSql();die;
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        if(!empty($result)){
            //获取游戏分类的名称与父级分类名称信息
            foreach ($result as $key=>$value){
                $newWhere['at.id'] = array('IN', $value['app_type']);
                $appTypeInfo = $this->getAppTypeAndParentInfoById($newWhere);
                if($appTypeInfo === false){
                    $this->setError('查询失败');
                    return false;
                }
                if(!empty($appTypeInfo)){
                    $tempTypeNameArr = array();
                    $tempParentTypeNameArr = array();
                    foreach ($appTypeInfo as $val){
                        $tempTypeNameArr[] = $val['type_name'];
                        $tempParentTypeNameArr[] = $val['parent_type_name'];
                    }
                    $result[$key]['type_name_str'] = implode('、', $tempTypeNameArr);
                    $result[$key]['parent_type_name_str'] = implode('、', $tempParentTypeNameArr);
                }
            }
        }
        return $result;
    }

    /**
     * 根据给定的时间获该礼拜发布的未删除的专题信息（默认本周）
     * @author xy
     * @since 2017/09/04 15:47
     * @param int $currentTimeStamp 当前时间戳
     * @return bool
     */
    public function getOneAppTopicByTime($currentTimeStamp){
        //获取一周的开始与结束时间
        if(empty($currentTimeStamp)){
            $currentTimeStamp = time();
        }
        $timeArr = get_a_week_time_start_and_end($currentTimeStamp, 1);
        $where = array(
            't.is_publish' => 1, //发布的游戏专题
            't.is_delete' => 1,  //未删除的游戏专题
            't.time_range_start' => array('egt', $timeArr[0]), //专题开始时间大于或等于本周的开始时间
            't.time_range_end' => array('elt', $timeArr[1]),    //专题的结束时间小于或等于本周结束的时间
        );
        $appTopic = M('app_topic')->alias('t')
            ->field('t.topic_id, t.topic_name, t.time_range_end, t.time_range_start, t.cover_image_path')
            ->join('INNER JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_topic_content tc on t.topic_id = tc.topic_id AND t.topic_type = tc.topic_type')
            ->where($where)
            ->find();
        if($appTopic === false){
            return $this->setError('查询专题失败');
        }
        //生成专题的详情页链接
        if(!empty($appTopic)){
            if($appTopic['topic_type'] == self::TOPIC_TYPE_TPL || $appTopic['topic_type'] == self::TOPIC_TYPE_EDITOR){
                $appTopic['topic_url'] = U('Home/App/app_topic_detail', array('topic_type'=>$appTopic['topic_type'], 'topic_id' => $appTopic['topic_id']));
            }else{
                $appTopic['topic_url'] = htmlspecialchars_decode($appTopic['content']);
            }
        }

        return $appTopic;
    }

    /**
     * 获取游戏的顶级分类ID字符串
     * @author xy
     * @since 2017/09/06 09:57
     * @return bool
     */
    public function getTopLevelAppTypeIdStr(){
        $appTypeId = M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_type', C('DB_ZHIYU.DB_PREFIX'))->alias('at')
            ->field('GROUP_CONCAT(id) as type_ids')
            ->where(array('parent_id' => 0))
            ->find();
        if($appTypeId === false){
            return $this->setError('查询游戏分类失败');
        }
        if(empty($appTypeId)){
            return $this->setError('没有游戏分类数据');
        }

        return $appTypeId['type_ids'];
    }

    /**
     * 通过游戏的顶级分类获取游戏的二级分类
     * @author xy
     * @since 2017/09/06 09:57
     * @return bool
     */
    public function getSecondLevelAppType(){
        $topAppTypeIdStr = $this->getTopLevelAppTypeIdStr();

        if(empty($topAppTypeIdStr)){
            return false;
        }
        $appTypeList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'app_type', C('DB_ZHIYU.DB_PREFIX'))->alias('at')
            ->field('at.id, at.type_name, at.parent_id')
            ->where(array('parent_id IN ('.$topAppTypeIdStr.')'))
            ->select();
        if($appTypeList === false){
            return $this->setError('查询游戏分类失败');
        }
        if(empty($appTypeList)){
            return $this->setError('没有游戏分类数据');
        }

        return $appTypeList;
    }

    /**
     * 获取媒体站已上架游戏数量
     * @author xy
     * @since 2017/09/06 14:16
     * @param array $where 查询条件
     * @return mixed
     */
    public function countPublishAppTotalNum(array $where = array()){
        $where['alist.is_publish'] = array('IN', array(1));
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort, IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, list.`sj_time`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->where($where)
            ->count();
        return $result;
    }

    /**
     * 获取本周上架的游戏的数量
     * @author xy
     * @since 2017/09/06 14:21
     * @return mixed
     */
    public function countCurrentWeekSjAppNum($where){
        $where['alist.is_publish'] = array('IN', array(1));
        $where['_string'] = 'YEARWEEK(date_format(FROM_UNIXTIME(alist.publish_time), \'%Y-%m-%d\')) = YEARWEEK(now())';
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort, IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, list.`sj_time`')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `at` on (FIND_IN_SET(`at`.id, IFNULL(alib.app_type, lib.app_type)))')
            ->where($where)
            ->group('alist.app_id')
            ->select();
        if($result === false){
            return $this->setError('计算游戏数量失败');
        }
        return count($result);
    }

    /**
     * 根据条件获取媒体站上架的游戏的数量
     * @author xy
     * @since 2017/09/06 15:06
     * @param array $where
     * @return mixed
     */
    public function countPublishAppNumByCondition(array $where = array()){
        $where['alist.is_publish'] = array('IN', array(1));

        $result = M('app_list')->alias('alist')
            ->field(
                'IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort, 
                IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort, 
                IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, 
                IFNULL(alib.app_type, lib.app_type) as app_type,  IFNULL(alib.platform, lib.platform) as platform, 
                IFNULL(alib.introduct, lib.introduct) as introduct, alist.`app_id`, alist.publish_time, 
                lib.app_file_url, list.`status`, list.`sj_time`, 
                GROUP_CONCAT(`at`.type_name) as app_type_name_str,
                GROUP_CONCAT(DISTINCT(`att`.type_name)) as parent_app_type_name_str')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `at` on (FIND_IN_SET(`at`.id, IFNULL(alib.app_type, lib.app_type)))')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `att` ON `at`.parent_id = att.id')
            ->where($where)
            ->group('alist.app_id')
            ->select();
        if($result === false){
            return $this->setError('计算游戏数量失败');
        }
        return count($result);
    }

    /**
     * 根据条件获取媒体站上架的游戏列表
     * @author xy
     * @since 2017/09/06 15:06
     * @param array $where
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @param string $orderBy 排序规则
     * @return mixed
     */
    public function getPublishAppByPage(array $where = array(), $currentPage, $pageSize, $orderBy = NULL){
        $where['alist.is_publish'] = array('IN', array(1));

        $appList = M('app_list')->alias('alist')
            ->field(
                'IF(alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort, 
                IF(alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort, 
                IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.start_score, lib.start_score) as start_score, 
                IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, 
                IFNULL(alib.start_score, lib.start_score) as start_score, 
                IFNULL(alib.platform, lib.platform) as platform, 
                IFNULL(alib.version_code,lib.version_code) as version_code, 
                IFNULL(alib.version_name, lib.version_name) as version_name, 
                IFNULL(alib.app_type, lib.app_type) as app_type, 
                IFNULL(alib.introduct, lib.introduct) as introduct, 
                IFNULL(alib.app_size, lib.app_size) as app_size, 
                alist.`app_id`, alist.publish_time, list.`status`, list.`sj_time`, 
                (list.app_down_num + list.cardinal) as app_down_num, list.create_time,
                GROUP_CONCAT(`at`.type_name) as app_type_name_str,
                GROUP_CONCAT(DISTINCT(`att`.type_name)) as parent_app_type_name_str'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `at` on (FIND_IN_SET(`at`.id, IFNULL(alib.app_type, lib.app_type)))')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `att` ON `at`.parent_id = att.id')
            ->where($where)
            ->group('alist.app_id')
            ->order($orderBy)
            ->limit($currentPage.','.$pageSize)
            ->select();
        if($appList === false){
            return $this->setError('计算游戏数量失败');
        }
        //处理游戏所属的二级分类与三级分类数据
        if($appList){
            foreach ($appList as $key=> $app){
                if(!empty($app['app_type_name_str'])){
                    $app['app_type_name_str'] = explode(',', $app['app_type_name_str']);
                    $app['app_type_name_str'] = implode(' ', $app['app_type_name_str']);
                }else{
                    $app['app_type_name_str'] = '未知';
                }
                if(!empty($app['parent_app_type_name_str'])){
                    $app['parent_app_type_name_str'] = explode(',', $app['parent_app_type_name_str']);
                    $app['parent_app_type_name_str'] = implode(' ', $app['parent_app_type_name_str']);
                }else{
                    $app['parent_app_type_name_str'] = '未知';
                }

                if(!empty($app['app_size'])){
                    $app['app_size'] = intval($app['app_size'] / (1024 * 1024));
                }else{
                    $app['app_size'] = 0;
                }

                $appList[$key] = $app;
            }
        }
        return $appList;
    }

    /**
     * 判断媒体站是否存在指定app_id的游戏，存在则判断该游戏是否在媒体站发布
     * @author xy
     * @since 2017/09/07 10:51
     * @param int $appId 游戏id
     * @return bool
     */
    public function isMediaExistAndPublishApp($appId){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $app = M('app_list')->where(array('app_id'=>$appId))->find();
        if(empty($app)){
            return $this->setError('媒体站不存在此游戏');
        }
        if($app['is_publish'] != 1){
            return $this->setError('此游戏未在媒体站发布');
        }
        return true;

    }

    /**
     * 通过游戏id获取游戏名称
     * @author xy
     * @since 2017/09/08 16:19
     * @param $appId
     * @return bool
     */
    public function getAppNameByAppId($appId){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $appName = M('app_list')->alias('alist')
            ->field('IFNULL(alib.app_name, lib.app_name) as app_name')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->where(array('alist.app_id'=>$appId))
            ->find();
        if(empty($appName)){
            return $this->setError('媒体站不存在此游戏');
        }
        return $appName['app_name'];

    }

    /**
     * 通过app_id获取游戏的详情信息
     * @author xy
     * @since 2017/09/07 11:22
     * @param int $appId 游戏id
     * @return bool
     */
    public function getAppDetailInfoByAppId($appId){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $isExist = $this->isMediaExistAndPublishApp($appId);
        if(!$isExist){
            return false;
        }
        $where['alist.app_id'] = $appId;
        $appInfo = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, 
                IF( alist.final_hot_sort=0, 9999999, IFNULL( alist.final_hot_sort, 9999999 ) ) as final_hot_sort, 
                IF( alist.final_new_sort=0, 9999999, IFNULL( alist.final_new_sort, 9999999 ) ) as final_new_sort, 
                IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, 
                IFNULL(alib.app_type, lib.app_type) as app_type, 
                IFNULL(alib.start_score, lib.start_score) as start_score, 
                IFNULL(alib.supplier_id, lib.supplier_id) as supplier_id, 
                IFNULL(alib.platform, lib.platform) as platform, 
                IFNULL(alib.version_code,lib.version_code) as version_code, 
                IFNULL(alib.version_name, lib.version_name) as version_name, 
                IFNULL(alib.about, lib.about) as about, IFNULL(alib.introduct, lib.introduct) as introduct, 
                IFNULL(alib.pic_url, lib.pic_url) as pic_url, IFNULL(alib.app_size, lib.app_size) as app_size, 
                IFNULL(alib.is_landscape, lib.is_landscape) as is_landscape,
                IFNULL(alib.update_time, lib.update_time) as update_time, 
                IFNULL(alib.game_quality, lib.game_quality) as game_quality, 
                IFNULL(alib.game_picture, lib.game_picture) as game_picture, 
                IFNULL(alib.game_gandu, lib.game_gandu) as game_gandu, 
                IFNULL(alib.game_diff, lib.game_diff) as game_diff, 
                IFNULL(alib.game_quality_value, lib.game_quality_value) as game_quality_value, 
                IFNULL(alib.game_picture_value, lib.game_picture_value) as game_picture_value,
                IFNULL(alib.game_gandu_value, lib.game_gandu_value) as game_gandu_value,
                IFNULL(alib.game_diff_value, lib.game_diff_value) as game_diff_value, alib.cover_img, lib.app_file_url, 
                lib.cover_img as zy_cover_img, alib.video_link, lib.video_id, list.`status`, list.`sj_time`, lib.is_my_sdk,
                (list.app_down_num + list.cardinal) as app_down_num, s.supplier_name, s.supplier_icon, s.supplier_info,
                alib.beauty_image, lib.app_lang,
                GROUP_CONCAT(DISTINCT(`at`.type_name)) as app_type_name_str,
                GROUP_CONCAT(DISTINCT(`att`.id)) as app_type_id_str,
                GROUP_CONCAT(DISTINCT(`att`.type_name)) as parent_app_type_name_str'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'supplier s on IFNULL(alib.supplier_id, lib.supplier_id) = s.supplier_id')//关联指娱应用厂商表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `at` on (FIND_IN_SET(`at`.id, IFNULL(alib.app_type, lib.app_type)))')
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `att` ON `at`.parent_id = att.id')
            ->where($where)
            ->find();
        if($appInfo === false){
            return $this->setError('查询失败');
        }
        if(empty($appInfo)){
            return $this->setError('未查询到对应的游戏信息');
        }
        //判断游戏总库是否有设置游戏视频，有的话获取视频链接,无详情页的视频封面则取视频库的视频封面
        if(!empty($appInfo['video_id'])){
            $videoInfo = $this->getAppVideoUrlByVideoId($appInfo['video_id']);
            if($videoInfo){
                $appInfo['video_url'] = $videoInfo['video_url'];
                if(empty($appInfo['zy_cover_img'])){
                    $appInfo['zy_cover_img'] = $videoInfo['video_img'];
                }
            }
        }
        //游戏截图
        if(!empty($appInfo['pic_url'])){
            $appInfo['pic_url'] = explode(',', $appInfo['pic_url']);
        }
        //精美图片
        if(!empty($appInfo['beauty_image'])){

            $appInfo['beauty_image'] = explode(',', $appInfo['beauty_image']);
        }
        //游戏大小转换成兆（M）
        if(!empty($appInfo['app_size'])){
            $appInfo['app_size'] = intval($appInfo['app_size'] / (1024 * 1024));
        }else{
            $appInfo['app_size'] = 0;
        }
        //游戏分类所属的二级分类与三级分类名称数据处理
        if(!empty($appInfo['app_type_name_str'])){
            $appInfo['app_type_name_str'] = explode(',', $appInfo['app_type_name_str']);
            $appInfo['app_type_name_str'] = implode(' ', $appInfo['app_type_name_str']);
        }else{
            $appInfo['app_type_name_str'] = '未知';
        }
        if(!empty($appInfo['parent_app_type_name_str'])){
            $appInfo['parent_app_type_name_str'] = explode(',', $appInfo['parent_app_type_name_str']);
            $appInfo['parent_app_type_name_str'] = implode(' ', $appInfo['parent_app_type_name_str']);
        }else{
            $appInfo['parent_app_type_name_str'] = '未知';
        }
        if($appInfo['app_lang'] == 1){
            $appInfo['app_lang_str'] = '其他';
        }else if($appInfo['app_lang'] == 2){
            $appInfo['app_lang_str'] = '简体中文';
        }else if($appInfo['app_lang'] == 3){
            $appInfo['app_lang_str'] = '繁体中文';
        }else{
            $appInfo['app_lang_str'] = '英文';
        }
        return $appInfo;
    }

    /**
     * 通过视频id从视频库或爱奇艺获取托管的视频信息
     * @author xy
     * @since 2017/09/07 11:53
     * @param $videoId
     * @return array|bool
     */
    public function getAppVideoUrlByVideoId($videoId){
        if(empty($videoId)){
            return $this->setError('请求的参数缺失');
        }
        $where['vlib.video_id'] = $videoId;
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'video_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('vlib')
            ->field('vlib.video_id, vlib.video_name, vlib.video_url, vlib.video_img, vlib.file_id, vlib.iqiyi_index')
            ->where($where)
            ->find();

        if(!$result){
            return $this->setError('未找到对应视频数据');
        }
        //通过视频的file_id从爱奇艺获取托管视频url以及视频相关信息
        if (!empty($result['file_id'])) {
            $result['video_url'] = $this->getIqiyiVideoUrl($result['file_id'], 2, $result['iqiyi_index']);

            if ($result['video_url']) {
                $iqiyiVideoInfo = $this->getIqiyiVideoInfo($result['file_id'], $result['iqiyi_index']);
                $result['video_size'] = $iqiyiVideoInfo['fileSize'];
                $result['video_duration'] = $iqiyiVideoInfo['duration'];
                return $result;
            }else{
                return $this->setError('未找到对应视频数据');
            }
        }else{
            if(!empty($result['video_url'] )){
                return $result;
            }
            return $this->setError('未找到对应视频数据');
        }

    }

    /**
     * 通过游戏id获取游戏详情页的图文攻略
     * @author xy
     * @since 2017/09/07 17;46
     * @param int $appId 游戏app_id
     * @return bool
     */
    public function getAppDetailGuideByAppId($appId){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $where['app_id'] = $appId;
        $guideList = M('app_guide')->where($where)->order('sort ASC')->limit(6)->select();
        if($guideList === false){
            $this->setError('查询精品攻略失败');
        }
        return $guideList;
    }

    /**
     * 从缓存中获取游戏类型信息
     * @author xy
     * @since 2017/09/07 23:38
     * @return bool|mixed
     */
    public static function getAppTypeInfoArrFromCache(){
        $appTypeInfo = S('zy_app_type_info');
        if(empty($appTypeInfo)){
            return $appTypeInfo = self::updateAppTypeInfoInCache();
        }else{
            return $appTypeInfo;
        }
    }

    /**
     * 更新缓存中游戏类型信息
     * @author xy
     * @since 2017/09/07 23:38
     * @param int $expire 默认有效期 1天
     * @return bool|mixed
     */
    public static function updateAppTypeInfoInCache($expire = 86400){
        $appTypeInfo = M(C('DB_ZHIYU.DB_NAME').'.'.'app_type', C('DB_ZHIYU.DB_PREFIX'))
            ->select();
        if(!empty($appTypeInfo)){
            $tempArr = array();
            foreach($appTypeInfo as $key=>$type){
                $tempArr[$type['id']] = $type;
            }
            S('zy_app_type_info', $tempArr, array('expire'=>$expire));
            return $tempArr;
        }
        return false;
    }

    /**
     * 根据游戏id获取与该游戏相同类型的其他游戏
     * @author xy
     * @since 2017/09/08 14:00
     * @param int $appId 游戏id
     * @param int $limit 获取的数量
     * @param int $orderType 排序类型 1下载量，2更新时间，3入库时间
     * @return bool|mixed
     */
    public function getAppsInSameAppTypeByAppId($appId, $limit = 5, $orderType = 1){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $appInfo = $this->getAppTypeInfoByAppId($appId);
        if(!$appInfo){
            return false;
        }
        //不包含当前的游戏
        $where = array('alist.app_id' => array('neq', $appInfo['app_id']));
        //与当前游戏相同的游戏类型
        if(!empty($appInfo['app_type'])){
            $where['at.id'] = array('IN', $appInfo['app_type']);
        }
        if($orderType == 1){
            //按下载量最多的排序
            $orderBy = 'final_hot_sort ASC, app_down_num DESC';
        }else if($orderType == 2){
            //按最新更新的排序
            $orderBy = 'final_new_sort ASC, alist.publish_time DESC';
        }else{
            //按入口时间倒序排序
            $orderBy = 'list.create_time DESC';
        }
        $recommendApp = $this->getPublishAppByPage($where, 0, $limit, $orderBy);
        if($recommendApp === false){
            return false;
        }
        return $recommendApp;
    }

    /**
     * 根据游戏id获取与该游戏相同游戏类型的服类型的其他游戏
     * @author xy
     * @since 2017/09/08 14:00
     * @param int $appId 游戏id
     * @param int $limit 获取的数量
     * @param int $orderType 排序类型 1下载量，2更新时间，3入库时间
     * @return bool|mixed
     */
    public function getAppsInSameParentAppTypeByAppId($appId, $limit = 5, $orderType = 1){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $appInfo = $this->getAppTypeInfoByAppId($appId);
        if(!$appInfo){
            return false;
        }
        //不包含当前的游戏
        $where = array('alist.app_id' => array('neq', $appInfo['app_id']));
        //与当前游戏相同的父级游戏类型
        if(!empty($appInfo['parent_app_type'])){
            $where['at.parent_id'] = array('IN', $appInfo['parent_app_type']);
        }
        if($orderType == 1){
            //按下载量最多的排序
            $orderBy = 'final_hot_sort ASC, app_down_num DESC';
        }else if($orderType == 2){
            //按最新更新的排序
            $orderBy = 'final_new_sort ASC, alist.publish_time DESC';
        }else{
            //按入口时间倒序排序
            $orderBy = 'list.create_time DESC';
        }
        $recommendApp = $this->getPublishAppByPage($where, 0, $limit, $orderBy);
        if($recommendApp === false){
            return false;
        }
        return $recommendApp;
    }

    /**
     * 获取指定游戏的游戏类型以及游戏类型的父级
     * @author xy
     * @since 2017/09/08 13:42
     * @param int $appId 游戏id
     * @return bool
     */
    public function getAppTypeInfoByAppId($appId){
        if(empty($appId)){
            return $this->setError('请求的参数缺失');
        }
        $where = array(
            'alist.app_id' => $appId,
        );
        $appInfo= M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_type, lib.app_type) as app_type, 
                GROUP_CONCAT(DISTINCT(at.parent_id)) as parent_app_type'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `at` on (FIND_IN_SET(`at`.id, IFNULL(alib.app_type, lib.app_type)))')
            ->where($where)
            ->find();
        if($appInfo === false){
            return $this->setError('请求的参数缺失');
        }
        if(empty($appInfo)) {
            return $this->setError('未找到对应的数据');
        }
        return $appInfo;
    }

    /**
     * 根据条件获取游戏分类id，名称，以及父类名称
     * @author xy
     * @since 2017/09/13 14:25
     * @param array $where
     * @return bool|array
     */
    public function getAppTypeAndParentInfoById(array $where = array()){
        $appTypeInfo = M(C('DB_ZHIYU.DB_NAME').'.'.'app_type', C('DB_ZHIYU.DB_PREFIX'))->alias('at')
            ->field(
                '`at`.id, `at`.type_name, `at`.parent_id, `att`.type_name as parent_type_name'
            )
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_type `att` ON `at`.parent_id = att.id')
            ->where($where)
            ->select();
        if($appTypeInfo === false){
            return $this->setError('');
        }
        return $appTypeInfo;
    }

    /**
     * 计算游戏每周专题的总数量
     * @author xy
     * @since 2017/09/11 09:46
     * @return bool
     */
    public function countAppTopicNum(){
        //已发布的未删除的每周专题
        $where = array(
            'is_publish' => 1,
            'is_delete' => 1,
        );
        $totalNum = M('app_topic')->alias('a')
            ->field('a.topic_id')
            ->join('INNER JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_topic_content as c ON c.topic_id = a.topic_id AND c.topic_type = a.topic_type')
            ->where($where)
            ->count();
        if($totalNum === false){
            return $this->setError('查询每周专题数量失败');
        }
        return $totalNum;
    }

    /**
     * 获取游戏每周专题的列表
     * @author xy
     * @since 2017/09/11 09:55
     * @param int $currentPage  当前页
     * @param int $pageSize 页大小
     * @return bool
     */
    public function getAppTopicList($currentPage, $pageSize){
        //已发布的未删除的每周专题
        $where = array(
            'a.is_publish' => 1,   //已上架
            'a.is_delete' => 1,    //未删除
            'a.publish_time' => array(array('gt', 0), array('lt', time())) //上架时间小于当前时间的，表示已上架
        );
        $topicList = M('app_topic')->alias('a')
            ->field('a.*, c.content')
            ->join('INNER JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_topic_content as c ON c.topic_id = a.topic_id AND c.topic_type = a.topic_type')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order('a.publish_time DESC')
            ->select();
        if($topicList === false){
            return $this->setError('查询每周专题失败');
        }
        //游戏专题的链接
        if(!empty($topicList)){
            foreach ($topicList as $key => $topic){
                if($topic['topic_type'] == self::TOPIC_TYPE_TPL || $topic['topic_type'] == self::TOPIC_TYPE_EDITOR){
                    $topicList[$key]['topic_url'] = U('Home/App/app_topic_detail', array('topic_type'=>$topic['topic_type'], 'topic_id' => $topic['topic_id']));
                }else{
                    $topicList[$key]['topic_url'] = htmlspecialchars_decode($topic['content']);
                }
            }
        }

        return $topicList;
    }

    /**
     * 获取游戏每周专题的列表页头部的图片
     * @author xy
     * @since 2017/09/11 17:10
     * @return mixed
     */
    public function getAppTopicListImage(){
        $where = array(
            'c.is_delete' => 1,
            's.is_publish' => 1,
            'c.keyword' => 'APP_TOPIC_TOP_IMAGE', //分类关键词 ,
        );
        $slideInfo = M('slide_cat')->alias('c')
            ->field('c.*, s.*, IF(s.sort = 0, 99999999, s.sort) as sort')
            ->join('INNER JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'slide as s ON s.slide_cid = c.cid')
            ->where($where)
            ->find();
        if($slideInfo === false){
            $this->error('获取图片信息失败');
        }
        return $slideInfo;
    }

    /**
     * 通过每周专题的id获取专题的详情内容
     * @author xy
     * @since 2017/09/11 17:02
     * @param $topicId
     * @return bool
     */
    public function getAppTopicContentByTopicId($topicId){
        if(empty($topicId)){
            $this->setError('必填参数缺失');
        }
        //已发布的未删除的每周专题
        $where = array(
            'a.is_publish' => 1,   //已上架
            'a.is_delete' => 1,    //未删除
            'a.publish_time' => array(array('gt', 0), array('lt', time())), //上架时间小于当前时间的，表示已上架
            'a.topic_id' => $topicId,
        );
        $topicContent = M('app_topic')->alias('a')
            ->field('a.*, c.background_image_path, c.introduce, c.content')
            ->join('INNER JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_topic_content as c ON c.topic_id = a.topic_id AND c.topic_type = a.topic_type')
            ->where($where)
            ->find();
        if($topicContent === false){
            return $this->setError('查询专题详情失败');
        }
        if(empty($topicContent)){
            return $this->setError('该专题不存在或者未发布');
        }
        if($topicContent['topic_type'] == self::TOPIC_TYPE_TPL){
            $topicContent['content'] = unserialize($topicContent['content']);
            foreach ($topicContent['content'] as $key=>$app){
                //获取每款游戏的详情信息
                $topicContent['content'][$key]['app_detail'] = $this->getAppDetailInfoByAppId($key);
                //视频链接的特殊字符转义
                $topicContent['content'][$key]['video_url'] = htmlspecialchars_decode($app['video_url']);
            }
        }
        return $topicContent;
    }

    /**
     * 计算用户下载过的游戏数量
     * @author xy
     * @since 2017/10/10 14:32
     * @param $userId
     * @return bool|int
     */
    public function countUserDownAppNum($userId){
        $where = array(
            'ad.uid' => $userId,
            'alist.is_publish' => array('IN', array(1))
        );
        $totalNum = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down `ad` on list.app_id = ad.app_id')
            ->group('alist.app_id')
            ->where($where)
            ->count();
        if($totalNum === false){
            return $this->setError('查询失败');
        }
        return $totalNum;
    }

    /**
     * 分页获取用户下载过的游戏
     * @author xy
     * @since 2017/10/10 14:52
     * @param int $userId 用户id
     * @param int $currentPage 当前页
     * @param int $pageSize 每页大小
     * @return bool|array
     */
    public function getUserDownAppListByPage($userId, $currentPage, $pageSize){
        $where = array(
            'ad.uid' => $userId,
            'alist.is_publish' => array('IN', array(1))
        );
        $appList = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down `ad` on list.app_id = ad.app_id')
            ->group('alist.app_id')
            ->where($where)
            ->order('ad.down_time DESC')
            ->limit($currentPage, $pageSize)
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return $appList;
    }

    /**
     * 计算用户收藏的游戏数量
     * @author xy
     * @since 2017/10/10 14:51
     * @param int $userId 用户id
     * @return bool
     */
    public function countUserCollectionAppNum($userId){
        //媒体站已上架，用户未取消收藏的
        $where = array(
            'mc.uid' => $userId,
            'mc.status' => 1,
            'alist.is_publish' => array('IN', array(1)),
        );
        $totalNum = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'my_collection `mc` on list.app_id = mc.app_id')
            ->where($where)
            ->count();
        if($totalNum === false){
            return $this->setError('查询失败');
        }
        return $totalNum;
    }

    /**
     * 分页获取用户收藏的游戏
     * @author xy
     * @since 2017/10/10 15:21
     * @param int $userId 用户id
     * @param int $currentPage 当前页
     * @param int $pageSize 每页大小
     * @return bool|array
     */
    public function getUserCollectionAppListByPage($userId, $currentPage, $pageSize){
        $where = array(
            'mc.uid' => $userId,
            'mc.status' => 1,
            'alist.is_publish' => array('IN', array(1)),
        );
        $appList = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'my_collection `mc` on list.app_id = mc.app_id')
            ->where($where)
            ->order('mc.create_time DESC')
            ->limit($currentPage, $pageSize)
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return $appList;
    }

    /**
     * 计算用户预约的游戏数量
     * @author xy
     * @since 2017/10/10 15:06
     * @param int $userId 用户id
     * @return bool|int
     */
    public function countUserSubscribeAppNum($userId){
        $where = array(
            'ms.uid' => $userId,
            'ms.status' => 1,
            'alist.is_publish' => array('IN', array(1)),
        );
        $totalNum = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'my_subscribe `ms` on list.app_id = ms.app_id')
            ->where($where)
            ->count();
        if($totalNum === false){
            return $this->setError('查询失败');
        }
        return $totalNum;
    }

    /**
     * 分页获取用户预约的游戏
     * @author xy
     * @since 2017/10/10 15:22
     * @param int $userId 用户id
     * @param int $currentPage 当前页
     * @param int $pageSize 每页大小
     * @return bool|array
     */
    public function getUserSubscribeAppListByPage($userId, $currentPage, $pageSize){
        $where = array(
            'ms.uid' => $userId,
            'ms.status' => 1,
            'alist.is_publish' => array('IN', array(1)),
        );
        $appList = M('app_list')->alias('alist')
            ->field(
                'alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, 
                IFNULL(alib.icon, lib.icon) as icon, lib.start_down_time'
            )
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'my_subscribe `ms` on list.app_id = ms.app_id')
            ->where($where)
            ->order('lib.start_down_time ASC')
            ->limit($currentPage, $pageSize)
            ->select();
        if($appList === false){
            return $this->setError('查询失败');
        }
        return $appList;
    }

    /**
     * 用户的游戏，包含预约，下载过的，收藏的
     * @author xy
     * @since 2017/10/13 19:10
     * @param int $userId 用户id
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return bool|mixed
     */
    public function getUserAppList($userId, $currentPage, $pageSize){
        $model = new \Think\Model();
        $downAppSql = '
            SELECT alist.`app_id`, IFNULL(alib.app_name,lib.app_name) as app_name, 
            IFNULL(alib.icon,lib.icon) as icon FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_list alist 
            INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id 
            INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id 
            LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id 
            LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down `ad` on list.app_id = ad.app_id 
            WHERE ad.uid = "'.$userId.'" AND alist.is_publish IN (1) 
            GROUP BY alist.app_id 
            ORDER BY ad.down_time DESC
            ';
        $collectAppSql = '
            SELECT alist.`app_id`, IFNULL(alib.app_name,lib.app_name) as app_name, 
            IFNULL(alib.icon,lib.icon) as icon FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_list alist 
            INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id 
            INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id 
            LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id 
            LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'my_collection `mc` on list.app_id = mc.app_id 
            WHERE mc.uid = "'.$userId.'" AND mc.status = 1 AND alist.is_publish IN (1) 
            ORDER BY mc.create_time DESC
            ';
        $subscribeAppSql = '
            SELECT alist.`app_id`,IFNULL(alib.app_name,lib.app_name) as app_name, 
            IFNULL(alib.icon,lib.icon) as icon FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_list alist 
            INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id 
            INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id 
            LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id 
            LEFT JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'my_subscribe `ms` on list.app_id = ms.app_id 
            WHERE ms.uid = "'.$userId.'" AND ms.status = 1 AND alist.is_publish IN (1) 
            ORDER BY lib.start_down_time ASC
            ';

        $unionSql = 'SELECT `app_id`, app_name, icon FROM (('.$downAppSql.') UNION ('.$collectAppSql.') UNION ('.$subscribeAppSql.')) AS app_list ORDER BY app_list.app_id DESC LIMIT '.$currentPage.', '.$pageSize;

        $appList = $model->query($unionSql);

        if($appList === false){
            return $this->setError('查询失败');
        }
        if(!empty($appList)){
            foreach ($appList as $key=>&$app){
                $app['icon'] = format_url($app['icon']);
            }
        }
        return $appList;
    }
}