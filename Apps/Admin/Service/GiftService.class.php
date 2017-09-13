<?php
/**
 * 礼包中心业务层
 * User: xy
 * Date: 2017/8/9
 * Time: 10:20
 */

namespace Admin\Service;

use Common\Service\BaseService;

class GiftService extends BaseService
{

    /**
     * 计算礼包列表各种类型礼包的数量
     * @author xy
     * @since 2017/08/10 14:46
     * @param array $where 查询条件
     * @return bool|int
     */
    public function getGiftInfoListTotalNum(array $where = array())
    {
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);

        //礼包码中未发放，去向礼包库的礼包码的数量
        $subQueryOne = ' SELECT `gift_id`, count(*) AS code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE status = 0 AND use_type = 0 GROUP BY gift_id ';

        //媒体站礼包库中礼包id对应的游戏以及礼包全名，避免通过gift_id关联查询时对应的礼包已删除无法正确关联正确的礼包
        $subQueryTwo = ' SELECT a.*, CONCAT(glib.app_id,\'-\',glib.gift_name,\'-\',glib.original_name) AS id_gift_name FROM ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS a LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS glib ON glib.gift_id = a.gift_id ';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field('gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, CONCAT(al.app_name,\'-\',gl.gift_name,\'-\',gl.original_name) AS full_gift_name, count(gl.gift_id) as batch_num')
            //INNER JOIN避免取到没有礼包码的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = gl.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('INNER JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->select();
        if ($giftList === false) {
            $this->setError('查询失败');
            return false;
        }
        return count($giftList);
    }

    /**
     * 根据条件获取礼包列表各种礼包以及礼包的可以发放的礼包码数量
     * @author xy
     * @since 2017/08/10 14:34
     * @param integer $type 1媒体站热门礼包列表，2媒体站新游礼包列表
     * @param integer $currentPage 当前页
     * @param integer $pageSize 页大小
     * @param array $where 查询条件
     * @return bool
     */
    public function getGiftInfoListByPage($type, $currentPage, $pageSize, array $where = array())
    {
        // $type 等于1 为热门礼包列表，$type = 2为新游礼包列表
        if (!in_array($type, array(1, 2))) {
            $this->setError('参数错误无法获取列表');
            return false;
        }
        if ($type == 1) {
            $orderBy = 'list.is_publish ASC, pre_hot_sort ASC, down_num DESC ';
        } else {
            $orderBy = 'list.is_publish ASC, pre_new_sort ASC, list.publish_time DESC ';
        }
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);

        //礼包码中未发放，去向礼包库的礼包码的数量
        $subQueryOne = ' SELECT gift_id, count(*) AS code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE status = 0 AND use_type = 0 GROUP BY gift_id ';

        //媒体站礼包库中礼包id对应的游戏以及礼包全名，避免通过gift_id关联查询时对应的礼包已删除无法正确关联正确的礼包
        $subQueryTwo = ' SELECT a.*, CONCAT(glib.app_id,\'-\',glib.gift_name,\'-\',glib.original_name) AS id_gift_name FROM ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS a LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS glib ON glib.gift_id = a.gift_id ';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field('sgl.gift_id as sync_gift_id, sgl.limited_count, IF(sgl.pre_hot_sort=0, 999999999, IFNULL(sgl.pre_hot_sort,999999999)) as pre_hot_sort, IF(sgl.pre_new_sort=0, 999999999, IFNULL(sgl.pre_new_sort,999999999)) as pre_new_sort, sgl.final_hot_sort, sgl.final_new_sort, gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, CONCAT(al.app_name,\'-\',gl.gift_name,\'-\',gl.original_name) AS full_gift_name, CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) AS id_gift_name, sum(cn.code_num) as total_num, (alist.app_down_num+alist.cardinal) as down_num, alist.sj_time')
            //INNER JOIN避免取到没有礼包码的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = gl.`app_id`')
            //关联获取下载量与上架时间
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS alist ON alist.`app_id` = gl.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('INNER JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->order($orderBy)
            ->limit($currentPage, $pageSize)
            ->select();
        if ($giftList === false) {
            $this->setError('查询失败');
            return false;
        }
        return $giftList;
    }

    /**
     * 根据条件获取礼包列表各种礼包以及礼包的可以发放的礼包码数量
     * @author xy
     * @since 2017/08/10 14:34
     * @param integer $type 1媒体站热门礼包列表，2媒体站新游礼包列表
     * @param array $where 查询条件
     * @return bool
     */
    public function getAllGiftInfoList($type, array $where = array())
    {
        // $type 等于1 为热门礼包列表，$type = 2为新游礼包列表
        if (!in_array($type, array(1, 2))) {
            $this->setError('参数错误无法获取列表');
            return false;
        }
        if ($type == 1) {
            $orderBy = 'list.is_publish ASC, pre_hot_sort ASC, down_num DESC ';
        } else {
            $orderBy = 'list.is_publish ASC, pre_new_sort ASC, list.publish_time DESC ';
        }
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);


        //礼包码中未发放，去向礼包库的礼包码的数量
        $subQueryOne = ' SELECT gift_id, count(*) AS code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE status = 0 AND use_type = 0 GROUP BY gift_id ';

        //媒体站礼包库中礼包di对应的游戏以及礼包全名，避免通过gift_id关联查询时对应的礼包已删除无法正确关联正确的礼包
        $subQueryTwo = ' SELECT a.*, CONCAT(glib.app_id,\'-\',glib.gift_name,\'-\',glib.original_name) AS id_gift_name FROM ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS a LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS glib ON glib.gift_id = a.gift_id ';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field('sgl.gift_id as sync_gift_id, sgl.limited_count, IF(sgl.pre_hot_sort=0, 999999999, IFNULL(sgl.pre_hot_sort,999999999)) as pre_hot_sort, IF(sgl.pre_new_sort=0, 999999999, IFNULL(sgl.pre_new_sort,999999999)) as pre_new_sort, sgl.final_hot_sort, sgl.final_new_sort, gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, CONCAT(al.app_name,\'-\',gl.gift_name,\'-\',gl.original_name) AS full_gift_name, CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) AS id_gift_name, sum(cn.code_num) as total_num, (alist.app_down_num+alist.cardinal) as down_num, alist.sj_time')
            //INNER JOIN避免取到没有礼包码的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //INNER JOIN 媒体站游侠详情表 只显示有游戏详情的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = gl.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = gl.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS alist ON alist.`app_id` = gl.`app_id`')
            ->join('LEFT JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->order($orderBy)
            ->select();
        if ($giftList === false) {
            $this->setError('查询失败');
            return false;
        }
        return $giftList;
    }

    /**
     * 判断礼包排序数据是否存在
     * @author xy
     * @since 2017/08/10 16:19
     * @param int $giftId 礼包库id
     * @return bool
     */
    public function isGiftSortDataExist($giftId)
    {
        $result = M('sync_gift_lib')->find($giftId);

        if (empty($result)) {
            return false;
        }
        return true;
    }

    /**
     * 获取礼包排序的数据
     * @author xy
     * @since 2017/08/10 16:43
     * @param int $giftId 礼包id
     * @return bool
     */
    public function loadGiftSortDataByGiftId($giftId)
    {
        $result = M('sync_gift_lib')->find($giftId);
        if (empty($result)) {
            $this->setError('数据不存在');
            return false;
        }
        return $result;
    }

    /**
     * 修改礼包的预排序
     * @author xy
     * @since 2017/08/10 16:22
     * @param int $giftId 礼包库id
     * @param array $data 排序更新数据
     * @return bool
     */
    public function updateGiftPreSortByGiftId($giftId, array $data)
    {
        if (empty($data)) {
            $this->setError('更新数据的数组不能为空');
            return false;
        }
        $isExist = $this->isGiftSortDataExist($giftId);
        if (!$isExist) {
            $result = M('sync_gift_lib')->add($data);
            if (empty($result)) {
                $this->setError('添加数据失败');
                return false;
            }
        } else {
            $result = M('sync_gift_lib')->where('gift_id=' . $giftId)->save($data);
            if ($result === false) {
                $this->setError(M('sync_gift_lib')->getDbError());
                return false;
            }
            if ($result === 0 || $result === '0') {
                $this->setError('排序未改变');
                return false;
            }
        }

        return true;
    }

    /**
     * 修改排序排序后自动修改其他礼包的排序 +1 或者 -1
     * @author xy
     * @since 2017/08/10 16:39
     * @param int $giftId 礼包id
     * @param int $listType 列表类型 1热门礼包，2新游礼包
     * @param int $fromSort 原排序
     * @param int $targetSort 目标排序
     * @return bool
     */
    public function autoUpdateOtherGiftPreSort($giftId, $listType, $fromSort, $targetSort)
    {
        if (empty($giftId) || !is_numeric($giftId)) {
            $this->setError('id参数错误');
            return false;
        }
        if (empty($listType) || !in_array($listType, array(1, 2))) {
            $this->setError('列表类型参数错误');
            return false;
        }
        if (empty($targetSort) || !is_numeric($targetSort)) {
            $this->setError('gift_id 为' . $giftId . '的数据的目标排序参数错误');
            return false;
        }
        if ($targetSort == $fromSort) {
            $this->setError('目标排序与原排序相同，无需修改');
            return false;
        }

        if ($listType == 1) {
            $field = 'pre_hot_sort';
        } else {
            $field = 'pre_new_sort';
        }
        //判断目标排序是否存在
        $isExist = M('sync_gift_lib')->where($field . ' = ' . $targetSort . ' AND `gift_id` <> ' . $giftId)->count();
        if ($isExist) {
            if ($fromSort <= 0) {
                $where['_string'] = '`' . $field . '` >= ' . $targetSort . ' AND `gift_id` <> ' . $giftId;
                $result = M('sync_gift_lib')->where($where)->setInc($field, 1);
            } else {
                if ($fromSort < $targetSort) {
                    //原排序小于目标排序,自减1
                    $where['_string'] = '`' . $field . '` >= ' . $fromSort . ' AND `' . $field . '` <= ' . $targetSort . ' AND `gift_id` <> ' . $giftId;
                    $result = M('sync_gift_lib')->where($where)->setDec($field, 1);
                } else if ($fromSort > $targetSort) {
                    //原排序大于目标排序,自增1
                    $where['_string'] = '`' . $field . '` >= ' . $targetSort . ' AND `' . $field . '` <= ' . $fromSort . ' AND `gift_id` <> ' . $giftId;
                    $result = M('sync_gift_lib')->where($where)->setInc($field, 1);
                } else {
                    $this->setError('目标排序与原排序相同，无需修改');
                    return false;
                }
            }
            if ($result === false) {
                $this->setError(M('sync_gift_lib')->getDbError());
                return false;
            }
        }

        return true;
    }

    /**
     * 更新礼包列表的最终排序
     * @author xy
     * @since 2017/08/11 09:44
     * @param int $type 1热门礼包，2新游礼包
     * @return bool
     */
    public function updateGiftFinalSort($type)
    {
        if (empty($type) || !in_array($type, array(1, 2))) {
            $this->setError('列表类型参数错误');
            return false;
        }
        if ($type == 1) {
            $fromField = 'pre_hot_sort';
            $toField = 'final_hot_sort';
        } else {
            $fromField = 'pre_new_sort';
            $toField = 'final_new_sort';
        }
        $table = M('sync_gift_lib')->getTableName();
        if ($table === false) {
            $this->setError('媒体站的礼包库数据表不存在');
            return false;
        }
        //将预定义的排序设置成最终排序
        $updateSql = 'UPDATE `' . $table . '` SET `' . $toField . '` = `' . $fromField . '` WHERE ( `' . $toField . '` <> `' . $fromField . '`)';
        //设置排序与最终排序不一致的才更新
        $result = M('sync_gift_lib')->execute($updateSql);
        if ($result === false) {
            $this->setError(M('sync_gift_lib')->getDbError());
            return false;
        }
        if ($result === '0' || $result === 0) {
            $this->setError('已是最新排序，无需更新');
            return false;
        }
        return true;
    }

    /**
     * 根据礼包id获取所有同名礼包的有效库存量
     * @author xy
     * @since 2017/08/11 15:07
     * @param int $giftId 礼包id
     * @return bool
     */
    public function getGiftCodeNumByGiftId($giftId)
    {

        $giftIds = $this->getGiftIdStringByGiftId($giftId);

        if (empty($giftIds)) {
            return false;
        }

        $codeWhere['use_type'] = 0;
        $codeWhere['status'] = 0;
        $codeWhere['gift_id'] = array('IN', $giftIds);

        $stockNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->alias('glc')
            ->field('count(code_id) as stock_num')
            ->where($codeWhere)
            ->find();
        if (empty($stockNum)) {
            return $this->setError('获取礼包库存失败');
        }
        $stockNum = $stockNum['stock_num'];
        return $stockNum;

    }

    /**
     * 计算拥有礼包的游戏的列表数量
     * @author xy
     * @since 2017/08/14 17:25
     * @param array $where 查询条件
     * @return bool
     */
    public function getAppAllGiftListNum(array $where = array())
    {
        //礼包库中被媒体站占用的礼包码数量
        $subQueryOne = ' SELECT gift_id, count(*) AS code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 GROUP BY gift_id ';

        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);

        $giftInfoList = M('app_list')->alias('alist')
            ->field('alist.app_id, alib.app_name, GROUP_CONCAT(gl.gift_id) AS gift_ids, sum(gn.code_num) AS total_num')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            //关联获取游戏礼包名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON alist.`app_id` = gl.`app_id`')
            //关联 有设置媒体站礼包的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->join('LEFT JOIN (' . $subQueryOne . ') AS gn ON gn.`gift_id` = gl.gift_id')
            ->where($where)
            ->group('alist.app_id')
            ->select();

        if ($giftInfoList === false) {
            return $this->setError('查询失败');
        }

        return count($giftInfoList);


    }

    /**
     * 分页获取拥有礼包的游戏列表以及对应的礼包数据，媒体站占用的礼包码总量，以及媒体站领取的礼包数量
     * @author xy
     * @since 2017/08/14 16:59
     * @param int $order 排序规则，1剩余率升序，2剩余率降序
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @param array $where 查询条件
     * @return bool
     */
    public function getAppAllGiftList($order = 1, $currentPage, $pageSize, array $where = array())
    {
        if (!in_array($order, array(1, 2))) {
            $this->setError('排序类型错误');
            return false;
        }
        if ($order == 1) {
            $order = 'residue_rate ASC';
        } else {
            $order = 'residue_rate DESC';
        }
        //礼包库中被媒体站占用的礼包码数量
        $subQueryOne = ' SELECT gift_id, count(*) AS code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 GROUP BY gift_id ';

        //礼包库中被媒体站占用的被领取的礼包码数量
        $subQueryTwo = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL GROUP BY gift_id ';

        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);

        /*
         * total_num 指礼包库中游戏对应的礼包被媒体站占用的礼包码的总量
         * total_use_num 指礼包库中游戏对应的礼包在媒体站占用且被领取的数量
         * residue_rate 指游戏对应媒体站礼包码的剩余率
         */

        $giftInfoList = M('app_list')->alias('alist')
            ->field('alist.app_id, alib.app_name, GROUP_CONCAT(gl.gift_id) AS gift_ids, IFNULL(sum(gn.code_num),0) AS total_num, IFNULL(sum(gnt.code_use_num),0) AS total_use_num, ( 1 - IFNULL(sum(gnt.code_use_num),0) / sum(gn.code_num) * 1.000 ) AS residue_rate ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON alist.`app_id` = gl.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->join('LEFT JOIN (' . $subQueryOne . ') AS gn ON gn.`gift_id` = gl.gift_id')
            ->join('LEFT JOIN (' . $subQueryTwo . ') AS gnt ON gnt.`gift_id` = gl.gift_id')
            ->where($where)
            ->order($order)
            ->group('alist.app_id')
            ->limit($currentPage, $pageSize)
            ->select();

        if ($giftInfoList === false) {
            return $this->setError('查询失败');
        }

        return $giftInfoList;


    }

    /**
     * 通过游戏id获取已设置礼包上限的礼包
     * @author xy
     * @since 2017/08/15 11:37
     * @param integer $appId 游戏id
     * @param array $where 查询条件
     * @return bool
     */
    public function getSetUponNumGiftListByAppId($appId, array $where = array())
    {
        if (empty($appId) || !is_numeric($appId)) {
            return $this->setError('游戏id参数错误');
        }
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        $where['gl.app_id'] = array('eq', $appId);
        //设置上限数量不为0
        $where['sgl.limited_count'] = array('neq', 0);

        //获取礼包库中对应礼包的礼包码库可用数量
        $subQueryOne = ' SELECT gl.gift_id, count(*) AS all_code_num, CONCAT(gl.app_id, \'-\', gl.gift_name, \'-\', gl.original_name) AS id_full_name FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code AS glc LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.gift_id = glc.gift_id WHERE gl.app_id = ' . $appId . ' AND glc.use_type = 0 AND glc.status = 0 GROUP BY id_full_name ';

        //礼包库中被媒体站占用的礼包码总数量
        $subQueryTwo = ' SELECT gl.gift_id, count(*) AS code_num, CONCAT(gl.app_id, \'-\', gl.gift_name, \'-\', gl.original_name) AS id_full_name FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code AS glc LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.gift_id = glc.gift_id WHERE gl.app_id = ' . $appId . ' AND glc.use_type = 3 GROUP BY id_full_name ';

        //礼包库中被媒体站占用的被领取的礼包码数量
        $subQueryThree = ' SELECT gl.gift_id, count(*) AS code_use_num, CONCAT(gl.app_id, \'-\', gl.gift_name, \'-\', gl.original_name) AS id_full_name FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code AS glc LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.gift_id = glc.gift_id WHERE gl.app_id = ' . $appId . ' AND glc.use_type = 3 AND glc.use_status IS NOT NULL GROUP BY id_full_name ';

        $giftList = M('sync_gift_lib')->alias('sgl')
            ->field('sgl.gift_id, sgl.limited_count, sgl.gift_detail, gl.gift_desc, CONCAT(alib.app_name, \'-\', gl.gift_name, \'-\', gl.original_name) AS gift_full_name, IFNULL(acn.all_code_num,0) AS all_code_num, IFNULL(cn.code_num,0) AS code_num, IFNULL(cun.code_use_num,0) AS code_use_num')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON sgl.`gift_id` = gl.`gift_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = gl.`app_id`')
            ->join('LEFT JOIN (' . $subQueryOne . ') AS acn ON acn.`id_full_name` = CONCAT(alib.app_id, \'-\', gl.gift_name, \'-\', gl.original_name)')
            ->join('LEFT JOIN (' . $subQueryTwo . ') AS cn ON cn.`id_full_name` = CONCAT(alib.app_id, \'-\', gl.gift_name, \'-\', gl.original_name)')
            ->join('LEFT JOIN (' . $subQueryThree . ') AS cun ON cun.`id_full_name` = CONCAT(alib.app_id, \'-\', gl.gift_name, \'-\', gl.original_name)')
            ->where($where)
            ->group('gift_full_name')
            ->select();
        if ($giftList === false) {
            return $this->setError('获取游戏礼包失败');
        }
        return $giftList;

    }

    /**
     * 通过游戏id获取游戏下可以设置上限数量的礼包
     * @author xy
     * @since 2017/08/25 16:13
     * @param int $appId 游戏id
     * @return bool
     */
    public function getAppGiftByAppId($appId){
        if (empty($appId) || !is_numeric($appId)) {
            return $this->setError('游戏id参数错误');
        }
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        $where['gl.app_id'] = array('eq', $appId);

        //礼包码中未发放，去向礼包库的礼包码的数量
        $subQueryOne = ' SELECT gift_id, count(*) AS code_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE status = 0 AND use_type = 0 GROUP BY gift_id ';

        //媒体站礼包库中礼包id对应的游戏以及礼包全名，避免通过gift_id关联查询时对应的礼包已删除无法正确关联正确的礼包
        $subQueryTwo = ' SELECT a.*, CONCAT(glib.app_id,\'-\',glib.gift_name,\'-\',glib.original_name) AS id_gift_name FROM ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS a LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS glib ON glib.gift_id = a.gift_id ';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field('sgl.gift_id as sync_gift_id, sgl.limited_count, sgl.gift_detail, gl.gift_desc, gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, CONCAT(al.app_name,\'-\',gl.gift_name,\'-\',gl.original_name) AS gift_full_name, CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) AS id_gift_name, sum(cn.code_num) as all_code_num ')
            //INNER JOIN避免取到没有礼包码的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = gl.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('LEFT JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,\'-\',gl.gift_name,\'-\',gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('gift_full_name')
            ->select();
        if(empty($giftList)){
            $this->setError('未找到对应礼包数据');
            return false;
        }
        return $giftList;

    }

    /**
     * 通过礼包id获取对应的礼包的申请信息
     * @author xy
     * @since 2017/08/15 17:33
     * @param integer $giftId 礼包id
     * @return bool
     */
    public function getGiftApplyInfoByGiftId($giftId)
    {
        if (empty($giftId) || !is_numeric($giftId)) {
            return $this->setError('礼包id参数错误');
        }
        //获取相同礼包的礼包id字符串
        $giftIds = $this->getGiftIdStringByGiftId($giftId);

        if ($giftIds === false) {
            return false;
        }
        //该礼包设置的上限数量信息
        $giftApplyInfo = M('sync_gift_lib')->alias('sgl')
            ->field('sgl.gift_id, sgl.limited_count, alib.app_name, CONCAT(gl.gift_name, \'-\', gl.original_name) AS gift_full_name')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON sgl.`gift_id` = gl.`gift_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = gl.`app_id`')
            ->where('sgl.gift_id = ' . $giftId)
            ->find();
        if ($giftApplyInfo === false) {
            return $this->setError('未找到对应的礼包数据');
        }

        if (empty($giftApplyInfo)) {
            return $this->setError('未找到对应的礼包数据');
        }

        //计算该礼包的所有批次的可以使用的礼包码总量
        $allCodeNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('count(*) AS all_code_num')
            ->where('gift_id IN (' . $giftIds . ') AND use_type = 0 AND status = 0')
            ->find();
        if ($allCodeNum === false) {
            return $this->setError('获取可使用礼包码总量失败');
        }
        $giftApplyInfo['all_code_num'] = empty($allCodeNum['all_code_num']) ? 0 : $allCodeNum['all_code_num'];

        //计算该礼包的媒体站占用的总量
        $codeNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('count(*) AS code_num')
            ->where('gift_id IN (' . $giftIds . ') AND use_type = 3')
            ->find();
        if ($codeNum === false) {
            return $this->setError('获取媒体站礼包码总量失败');
        }
        $giftApplyInfo['code_num'] = empty($codeNum['code_num']) ? 0 : $codeNum['code_num'];

        //计算该礼包的媒体站占用且被领取的数量
        $useCodeNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('count(*) AS use_code_num')
            ->where('gift_id IN (' . $giftIds . ') AND use_type = 3 AND use_status IS NOT NULL ')
            ->find();
        if ($useCodeNum === false) {
            return $this->setError('获取已使用礼包码数量失败');
        }
        $giftApplyInfo['use_code_num'] = empty($useCodeNum['use_code_num']) ? 0 : $useCodeNum['use_code_num'];

        //计算媒体站礼包剩余数量

        $giftApplyInfo['residual_num'] = $giftApplyInfo['code_num'] - $giftApplyInfo['use_code_num'];

        return $giftApplyInfo;

    }

    /**
     * 通过礼包id获取所有同名礼包的id字符串
     * @author xy
     * @since 2017/08/15 18:37
     * @param integer $giftId 礼包id
     * @return bool
     */
    public function getGiftIdStringByGiftId($giftId)
    {
        if (empty($giftId) || !is_numeric($giftId)) {
            return $this->setError('礼包id参数错误');
        }
        $giftNameInfo = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))
            ->field('app_id , gift_name , original_name')
            ->where('gift_id = ' . $giftId)
            ->find();
        if (empty($giftNameInfo)) {
            return $this->setError('未找到对应的礼包');
        }
        $where['app_id'] = $giftNameInfo['app_id'];
        $where['gift_name'] = $giftNameInfo['gift_name'];
        $where['original_name'] = $giftNameInfo['original_name'];
        //在有效期内未删除的礼包
        $where['is_del'] = array('neq', 1);
        $where['start_time'] = array('lt', time());
        $where['end_time'] = array('gt', time());

        //获取所有符合条件的所有批次的礼包id
        $giftIds = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))
            ->field('GROUP_CONCAT(gift_id) AS gift_ids')
            ->where($where)
            ->find();
        if (empty($giftIds)) {
            return $this->setError('获取礼包id失败');
        }
        return $giftIds['gift_ids'];
    }


    /**
     * 验证申请数量是否超过库可用数量以及上限数量
     * @author xy
     * @since 2017/08/15 19:01
     * @param integer $giftId 礼包id
     * @param integer $applyNum 申请的数量
     * @return bool
     */
    public function checkGiftApplyNum($giftId, $applyNum)
    {
        if (empty($giftId) || !is_numeric($giftId)) {
            return $this->setError('礼包id参数错误');
        }
        if (empty($applyNum) || !is_numeric($applyNum)) {
            $this->setError('申请数量不符合要求');
        }

        //获取该礼包设置的上限数量信息
        $giftApplyInfo = M('sync_gift_lib')->alias('sgl')
            ->field('sgl.limited_count')
            ->where('sgl.gift_id = ' . $giftId)
            ->find();
        if ($giftApplyInfo === false) {
            return $this->setError('未找到对应的礼包数据');
        }
        if (empty($giftApplyInfo)) {
            return $this->setError('未找到对应的礼包数据');
        }
        $limitedCount = empty($giftApplyInfo['limited_count']) ? 0 : $giftApplyInfo['limited_count'];

        $giftIds = $this->getGiftIdStringByGiftId($giftId);
        if ($giftIds === false) {
            return false;
        }
        //计算该礼包的所有批次的可以使用的礼包码总量
        $allCodeNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('count(*) AS all_code_num')
            ->where('gift_id IN (' . $giftIds . ') AND use_type = 0 AND status = 0')
            ->find();
        if ($allCodeNum === false) {
            return $this->setError('获取可使用礼包码总量失败');
        }
        $allCodeNum = empty($allCodeNum['all_code_num']) ? 0 : $allCodeNum['all_code_num'];

        //计算该礼包的媒体站占用的总量
        $codeNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('count(*) AS code_num')
            ->where('gift_id IN (' . $giftIds . ') AND use_type = 3')
            ->find();
        if ($codeNum === false) {
            return $this->setError('获取媒体站礼包码总量失败');
        }
        $codeNum = empty($codeNum['code_num']) ? 0 : $codeNum['code_num'];

        //计算该礼包的媒体站占用且被领取的数量
        $useCodeNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
            ->field('count(*) AS use_code_num')
            ->where('gift_id IN (' . $giftIds . ') AND use_type = 3 AND use_status IS NOT NULL ')
            ->find();
        if ($useCodeNum === false) {
            return $this->setError('获取已使用礼包码数量失败');
        }
        $useCodeNum = empty($useCodeNum['use_code_num']) ? 0 : $useCodeNum['use_code_num'];

        $residualNum = $codeNum - $useCodeNum;

        //判断是否超过库可用的数量
        if ($applyNum > $allCodeNum) {
            return $this->setError('申请的数量不得超过礼包库可用数量');
        }

        if ($applyNum > ($limitedCount - $residualNum)) {
            return $this->setError('申请的数量不得超过上限数量');
        }

        return true;

    }

    /**
     * 从礼包库申请礼包对应的礼包
     * @author xy
     * @since 2017/08/17 16:29
     * @param integer $giftId 礼包id
     * @param integer $applyNum 申请数量
     * @return bool
     */
    public function applyGiftFromZhiYu($giftId, $applyNum)
    {
        if (empty($giftId) || !is_numeric($giftId)) {
            return $this->setError('礼包id参数错误');
        }
        if (empty($applyNum) || !is_numeric($applyNum)) {
            $this->setError('申请数量不符合要求');
        }
        //获取同名礼包未过期删除的的礼包id
        $giftIds = $this->getGiftIdStringByGiftId($giftId);
        if ($giftIds === false) {
            return false;
        }
        //获取同名礼包各个批次礼包码的可用数量
        $giftCodeData = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->alias('glc')
            ->field('glc.gift_id, count(glc.code_id) AS count, gl.end_time')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib gl ON glc.gift_id = gl.gift_id')
            ->where('glc.gift_id IN (' . $giftIds . ') AND glc.use_type = 0 AND glc.status = 0')
            ->order('gl.end_time ASC')
            ->group('glc.gift_id')
            ->select();

        if (empty($giftCodeData)) {
            return $this->setError('为获取到对应礼包码信息');
        }

        //开启事务
        M()->startTrans();
        //申请礼包码操作
        foreach ($giftCodeData as $value) {
            $code_where = array(
                'gift_id' => $value['gift_id'],
                'status' => 0,
                'use_type' => 0,
            );
            $code_data = array(
                'status' => 2,   //表示被占用
                'use_type' => 3  //去向媒体站
            );
            if ($applyNum - $value['count'] > 0) { # 这批不够
                $applyNum = $applyNum - $value['count'];
                # 更新礼包码表
                $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->where($code_where)->limit($value['count'])->save($code_data);
                if (!$result) {
                    M()->rollback();
                    return $this->setError('申请礼包失败');
                    break;
                }
                continue;
            } else {    # 够
                $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->where($code_where)->limit($applyNum)->save($code_data);
                if (!$result) {
                    M()->rollback();
                    return $this->setError('申请礼包失败');
                    break;
                }
                break;
            }
        }
        //申请礼包时，媒体站礼包的发布时间为当前时间
        M('sync_gift_lib')->where(array('gift_id' => $giftId))->save(array('publish_time' => time()));
        M()->commit();
        $userInfo = self::getUserInfo();
        $adminId = $userInfo['id'];
        //指娱记录礼包去向
        $this->giftLibIncome($giftId, 17, -$applyNum, $adminId, '上架到媒体站礼包中心');
        //媒体站记录申请数量
        $applyData = array(
            'gift_id' => $giftId,
            'opt_count' => $applyNum,
            'opt_type' => 0, //申请礼包
            'admin_id' => $adminId,
            'remark' => '申请礼包码',
            'create_time' => time(),
        );
        M('gift_opt_record')->add($applyData);
        return true;
    }

    /**
     * 删除设置上限的礼包,若有设置媒体站占用礼包码则退回礼包库中
     * @author xy
     * @since 2017/08/17 16:28
     * @param integer $giftId 礼包id
     * @return bool
     */
    public function deleteGift($giftId)
    {
        if (empty($giftId) || !is_numeric($giftId)) {
            return $this->setError('礼包id参数错误');
        }
        if (empty($applyNum) || !is_numeric($applyNum)) {
            $this->setError('申请数量不符合要求');
        }
        $isExist = $this->isGiftSortDataExist($giftId);
        if (!$isExist) {
            return $this->setError('此礼包id对应的数据不存在');
        }
        //获取同名礼包未过期删除的的礼包id
        $giftIds = $this->getGiftIdStringByGiftId($giftId);
        if ($giftIds === false) {
            return false;
        }
        $userInfo = self::getUserInfo();
        //开启事务
        M()->startTrans();
        //礼包码中被媒体站占用的礼包，退回到礼包库中
        $where = array(
            'gift_id' => array('IN', "{$giftIds}"),
            'status' => 2,
            'use_type' => 3
        );
        //当前被媒体站礼包中心占用的礼包码数量
        $currentCount = count(M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->field('code_id')->where($where)->select());

        //删除礼包设置上限记录
        $result = M('sync_gift_lib')->where('gift_id = ' . $giftId)->delete();
        if ($result === false) {
            M()->rollback();
            return $this->setError('删除礼包失败');
        }
        //退回礼包中心的数量，默认0，当有被媒体站礼包中心占用时才执行退回操作
        $updateCount = 0;
        if ($currentCount > 0) {
            //媒体站占用礼包退回礼包库
            $updateCount = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))
                ->where($where)
                ->save(array('status' => 0, 'use_type' => 0));
            if ($updateCount === false) {
                M()->rollback();
                return $this->setError('删除礼包失败');
            }
            //记录礼包动态
            $result = $this->giftLibIncome($giftId, 3, $updateCount, $userInfo['id'], '媒体站礼包中心下架退回礼包库');
            if (!$result) {
                M()->rollback();
                return $this->setError($this->getFirstError());
            }
        }

        //媒体站记录礼包动态
        $optData = array(
            'gift_id' => $giftId,
            'opt_count' => $updateCount,
            'opt_type' => 0, //申请礼包
            'admin_id' => $userInfo['id'],
            'remark' => '删除礼包，礼包码退回礼包库，数量' . $updateCount,
            'create_time' => time(),
        );
        $result = M('gift_opt_record')->add($optData);
        if ($result === false) {
            M()->rollback();
            return $this->setError('田间礼包操作信息失败');
        }
        M()->commit();
        return true;
    }

    /**
     * 记录礼包码的去向
     * @author xy
     * @since 2017/08/16 10:45
     * @param integer $gift_id 礼包id
     * @param integer $incomeType //进向：1新增、2补货、3退回 去向：11礼包中心(热门礼包中心)、12导出、13活动、14夺宝、15激活码、16任务中心、17媒体站',
     * @param integer $count 礼包码数量
     * @param integer $adminId 操作者id
     * @param string $notes 备注信息
     * @return string
     */
    public function giftLibIncome($gift_id, $incomeType, $count, $adminId, $notes)
    {
        if (in_array($incomeType, array(1, 2, 3)) && $count < 0) {
            return $this->setError('数量必须是正数');
        }
        if (in_array($incomeType, array(11, 12, 13, 17)) && $count > 0) {
            return $this->setError('数量必须是正数');
        }
        $data = array(
            'gift_id' => $gift_id,
            'income_type' => $incomeType,
            'count' => $count,
            'admin_id' => $adminId,
            'notes' => $notes,
            'create_time' => time()
        );
        $result = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_income', C('DB_ZHIYU.DB_PREFIX'))->add($data);
        if ($result) {
            return true;
        }
        $this->setError('记录礼包动态失败');
        return false;
    }


}