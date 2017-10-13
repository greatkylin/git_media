<?php
/**
 * 礼包中心相关业务操作
 * User: xy
 * Date: 2017/9/12
 * Time: 14:26
 */

namespace Home\Service;

use Common\Service\BaseService;

class GiftService extends BaseService
{
    const USE_TYPE_MEDIA = 3; // gift_lib 表中 use_type = 3表示媒体站使用

    /**
     * 获取热门礼包列表
     * @author xy
     * @since 2017/09/26 15:02
     * @param int $limit
     * @param bool $isPreview 是否预览
     * @return bool|array
     */
    public function getHotGiftList($limit = 8, $isPreview = false){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        $orderBy = 'final_sort ASC, down_num DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, down_num DESC';
        }
        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, 
                gl.gift_name, gl.gift_icon, alist.publish_time,
                CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_sort, 
                IF(sgl.pre_hot_sort=0, 999999999, IFNULL(sgl.pre_hot_sort,999999999)) as pre_sort,
                (list.app_down_num + list.cardinal) as down_num, 
                IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc) as gift_desc, 
                CONCAT(gl.gift_name, gl.original_name) AS short_gift_name,
                gl.start_time, gl.use_rule, gl.end_time
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->order($orderBy)
            ->group('short_gift_name')
            ->limit($limit)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }
        //计算使用量 剩余量等信息
        if(!empty($giftList)){
            foreach ($giftList as $key=>&$gift){
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
            }
        }
        return $giftList;
    }

    /**
     * 获取新游礼包列表
     * @author xy
     * @since 2017/09/26 15:02
     * @param int $limit
     * @param bool $isPreview 是否预览
     * @return bool|array
     */
    public function getNewGiftList($limit = 8, $isPreview = false){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));
        $orderBy = 'final_sort ASC, alist.publish_time DESC';
        if($isPreview){
            $orderBy = 'pre_sort ASC, alist.publish_time DESC';
        }
        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, 
                gl.gift_name, gl.gift_icon, alist.publish_time,
                CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                IF(sgl.final_new_sort=0, 999999999, IFNULL(sgl.final_new_sort,999999999)) as final_sort,
                IF(sgl.pre_hot_sort=0, 999999999, IFNULL(sgl.pre_hot_sort,999999999)) as pre_sort,
                (list.app_down_num + list.cardinal) as down_num, 
                IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc) as gift_desc, 
                CONCAT(gl.gift_name, gl.original_name) AS short_gift_name,
                gl.start_time, gl.use_rule, gl.end_time
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->order($orderBy)
            ->group('short_gift_name')
            ->limit($limit)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }
        //计算使用量 剩余量等信息
        if(!empty($giftList)){
            foreach ($giftList as $key=>&$gift){
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
            }
        }
        return $giftList;
    }

    /**
     * 获取指定数量的热门礼包游戏
     * @author xy
     * @since 2017/09/12 17:29
     * @param int $limit 查询数量
     * @return bool|array
     */
    public function getHotGiftAppList($limit = 8){

        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        /*
         * total_num 指礼包库中游戏对应的礼包被媒体站占用的礼包码的总量
         * use_num 指礼包库中游戏对应的礼包在媒体站占用且被领取的数量
         * residue_rate 指游戏对应媒体站礼包码的剩余率
         */
        $hotGiftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, 
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc), gl.use_rule,
                IFNULL(alib.app_name, lib.app_name) as app_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->limit($limit)
            ->order('final_hot_sort ASC, down_num DESC')
            ->group('gl.app_id')
            ->select();

        if ($hotGiftList === false) {
            return $this->setError('查询失败');
        }
        //计算媒体站的礼包的数量，以及已使用量，还有剩余量
        if(!empty($hotGiftList)){
            foreach ($hotGiftList as $key => &$gift) {
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
                //该游戏下最新的3款礼包
                $giftList = $this->getLatestAppGiftList($gift['app_id']);
                if(!empty($giftList)){
                    $latestGift = '';
                    $count = count($giftList) - 1;
                    foreach($giftList as $k => $val) {
                        $latestGift .= $val['gift_name'];
                        if($key < $count) {
                            $latestGift .= '、';
                        }
                    }
                    $gift['latest_gift'] = $latestGift;
                }
            }
        }

        return $hotGiftList;
    }

    /**
     * 获取指定数量的新游礼包列表
     * @author xy
     * @since 2017/09/14 10:00
     * @param int $limit
     * @return bool|array
     */
    public function getNewGiftAppList($limit = 8){

        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        /*
         * total_num 指礼包库中游戏对应的礼包被媒体站占用的礼包码的总量
         * use_num 指礼包库中游戏对应的礼包在媒体站占用且被领取的数量
         * residue_rate 指游戏对应媒体站礼包码的剩余率
         */
        $newGiftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                IF(sgl.final_new_sort=0, 999999999, IFNULL(sgl.final_new_sort,999999999)) as final_new_sort, 
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc), gl.use_rule,
                IFNULL(alib.app_name, lib.app_name) as app_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->limit($limit)
            ->order('final_new_sort ASC, alist.publish_time DESC')
            ->group('gl.app_id')
            ->select();

        if ($newGiftList === false) {
            return $this->setError('查询失败');
        }
        //计算媒体站的礼包的数量，以及已使用量，还有剩余量
        if(!empty($newGiftList)){
            foreach ($newGiftList as $key => &$gift) {
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
                //该游戏下最新的3款礼包
                $giftList = $this->getLatestAppGiftList($gift['app_id']);
                if(!empty($giftList)){
                    $latestGift = '';
                    $count = count($giftList) - 1;
                    foreach($giftList as $k => $val) {
                        $latestGift .= $val['gift_name'];
                        if($key < $count) {
                            $latestGift .= '、';
                        }
                    }
                    $gift['latest_gift'] = $latestGift;
                }
            }
        }

        return $newGiftList;
    }

    /**
     * 计算媒体站对应游戏礼包的总量或者某款礼包的数据
     * @author xy
     * @since 2017/09/12 16:21
     * @param int $appId 游戏id
     * @param string $giftName 礼包名称
     * @param string $originalName 礼包原始名称
     * @return bool
     */
    public function countMediaAppGift($appId, $giftName = '', $originalName=''){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        // 查询礼包已领数量
        $whe = array(
            'gl.app_id' => $appId,
            'gl.is_del' => array('neq', 1),
            'glc.use_type' => self::USE_TYPE_MEDIA,
        );
        if (!empty($giftName)) {
            $whe['gl.gift_name'] = $giftName;
        }
        if (!empty($originalName)) {
            $whe['gl.original_name'] = $originalName;
        }
        $allCount = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->alias('glc')
            ->where($whe)
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`gift_id` = glc.`gift_id`')
            ->count();
        if($allCount === false){
            return $this->setError('查询游戏礼包总数失败');
        }
        return $allCount;
    }

    /**
     * 计算媒体站对应游戏礼包的使用量
     * @author xy
     * @since 2017/09/12 16:21
     * @param int $appId 游戏id
     * @param string $giftName 礼包名称
     * @param string $originalName 礼包原始名称
     * @return bool
     */
    public function countMediaAppGiftUse($appId, $giftName = '', $originalName=''){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        // 查询礼包已领数量
        $whe = array(
            'gl.app_id' => $appId,
            'gl.is_del' => array('neq', 1),
            'glc.status' => array('IN', '1,4'),
            'glc.use_type' => self::USE_TYPE_MEDIA,
        );
        if ($giftName) {
            $whe['gl.gift_name'] = $giftName;
        }
        if ($originalName) {
            $whe['gl.original_name'] = $originalName;
        }
        $useCount = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->alias('glc')
            ->where($whe)
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`gift_id` = glc.`gift_id`')
            ->count();
        return $useCount;
    }

    /**
     * 获取媒体站礼包最新的几款礼包
     * @author xy
     * @since 2017/09/12 16:44
     * @param int $appId 游戏id
     * @param int $limit 获取的数量
     * @return bool|array
     */
    public function getLatestAppGiftList($appId, $limit = 3){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $giftList = M(C('DB_NAME') . '.' . 'sync_gift_lib', C('DB_PREFIX'))->alias('sgl')
            ->field('sgl.gift_id, gift_name, original_name')
            ->where("gl.app_id={$appId}")
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`gift_id` = sgl.`gift_id`')
            ->order('gl.create_time desc')
            ->limit($limit)
            ->group('CONCAT(gl.app_id,gl.gift_name,gl.original_name)')
            ->select();
        if($giftList === false){
            return $this->setError('查询礼包数据失败');
        }

        return $giftList;
    }

    /**
     * 计算媒体站有礼包的游戏数量
     * @author xy
     * @since 2017/09/12 17:26
     * @param array $where 查询条件
     * @return bool
     */
    public function countGiftAppTotalNum($where = array()){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));


        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->group('gl.app_id')
            ->select();
        if($giftList=== false){
            return $this->setError('计算拥有礼包的游戏数量失败');
        }
        return count($giftList);
    }

    /**
     * 分页获取有礼包的游戏列表
     * @author xy
     * @since 2017/09/12 17:37
     * @param array $where
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @param null $orderBy 排序规制
     * @return bool|array
     */
    public function getGiftAppListByPage($where = array(), $currentPage = 0, $pageSize = 8, $orderBy = NULL){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        if(empty($orderBy)){
            $orderBy = 'final_hot_sort ASC, down_num DESC';
        }

        /*
         * total_num 指礼包库中游戏对应的礼包被媒体站占用的礼包码的总量
         * use_num 指礼包库中游戏对应的礼包在媒体站占用且被领取的数量
         * residue_rate 指游戏对应媒体站礼包码的剩余率
         */
        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, 
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, 
                IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc), gl.use_rule,
                IFNULL(alib.app_name, lib.app_name) as app_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order($orderBy)
            ->group('gl.app_id')
            ->select();
        //echo M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->getLastSql();die;
        if ($giftList === false) {
            return $this->setError('查询失败');
        }
        //计算媒体站的礼包的数量，以及已使用量，还有剩余量
        if(!empty($giftList)){
            foreach ($giftList as $key => &$gift) {
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
                //该游戏下最新的3款礼包
                $latestGiftList = $this->getLatestAppGiftList($gift['app_id']);
                if(!empty($latestGiftList)){
                    $latestGift = '';
                    $count = count($latestGiftList) - 1;
                    foreach($latestGiftList as $k => $val) {
                        $latestGift .= $val['gift_name'];
                        if($key < $count) {
                            $latestGift .= '、';
                        }
                    }
                    $gift['latest_gift'] = $latestGift;
                }
                //获取该游戏下的礼包种类数量
                $gift['gift_kind_num'] = $this->countAllGiftBelongAppId($gift['app_id']);
                //获取今天更新的礼包种类的数量
                $gift['gift_publish_num'] = $this->countPublishGiftBelongAppId($gift['app_id']);
            }
        };
        return $giftList;
    }

    /**
     * 计算媒体站有礼包的游戏数量
     * @author xy
     * @since 2017/09/12 17:26
     * @param array $where 查询条件
     * @return bool
     */
    public function countAppGiftTotalNum($where = array()){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));


        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->group('full_gift_name')
            ->select();
        if($giftList=== false){
            return $this->setError('计算拥有礼包的游戏数量失败');
        }
        return count($giftList);
    }

    /**
     * 分页获取有礼包的游戏列表
     * @author xy
     * @since 2017/09/12 17:37
     * @param array $where
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @param null $orderBy 排序规制
     * @return bool|array
     */
    public function getAppGiftListByPage($where = array(), $currentPage = 0, $pageSize = 8, $orderBy = NULL){
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        if(empty($orderBy)){
            $orderBy = 'final_hot_sort ASC, down_num DESC';
        }

        /*
         * total_num 指礼包库中游戏对应的礼包被媒体站占用的礼包码的总量
         * use_num 指礼包库中游戏对应的礼包在媒体站占用且被领取的数量
         * residue_rate 指游戏对应媒体站礼包码的剩余率
         */
        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field(
                'sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, 
                gl.original_name, gl.gift_name,
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, 
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, 
                IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc), gl.use_rule,
                IFNULL(alib.app_name, lib.app_name) as app_name'
            )
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->limit($currentPage, $pageSize)
            ->order($orderBy)
            ->group('full_gift_name')
            ->select();
        //echo M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->getLastSql();die;
        if ($giftList === false) {
            return $this->setError('查询失败');
        }
        //计算媒体站的礼包的数量，以及已使用量，还有剩余量
        if(!empty($giftList)){
            foreach ($giftList as $key => &$gift) {
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
            }
        };
        return $giftList;
    }

    /**
     * 根据游戏id获取游戏礼包页的banner
     * @author xy
     * @since 2017/09/12 18:22
     * @param int $appId 游戏id
     * @return bool|mixed
     */
    public function getGiftBannerByAppId($appId){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $giftBanner = M('gift_banner')->where(array('app_id' => $appId))->find();
        if($giftBanner === false){
            return $this->setError('查询礼包banner失败');
        }
        return $giftBanner;
    }

    /**
     * 计算某款游戏下的所有有效的礼包数量
     * @author xy
     * @since 2017/09/13 16:48
     * @param int $appId 游戏id
     * @return bool
     */
    public function countAllGiftBelongAppId($appId){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $where['gl.app_id'] = $appId;
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name,
                CONCAT(gl.gift_name, gl.original_name) AS short_gift_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->group('short_gift_name')
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }
        return count($giftList);
    }

    /**
     * 分页获取指定游戏下的所有有效的礼包数据
     * @author xy
     * @since 2017/09/13 16:59
     * @param int $appId 游戏id
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @param null $orderBy
     * @return bool|array
     */
    public function getAllGiftBelongAppIdByPage($appId, $currentPage, $pageSize, $orderBy = NULL){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $where['gl.app_id'] = $appId;
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        if(empty($orderBy)){
            $orderBy = 'final_hot_sort ASC, down_num DESC';
        }

        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, 
                gl.gift_name, gl.gift_icon, 
                CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort,
                (list.app_down_num + list.cardinal) as down_num, 
                IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc) as gift_desc, 
                CONCAT(gl.gift_name, gl.original_name) AS short_gift_name,
                gl.start_time, gl.use_rule, gl.end_time
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->order($orderBy)
            ->group('short_gift_name')
            ->limit($currentPage, $pageSize)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }

        if(!empty($giftList)){
            foreach ($giftList as $key=>&$gift){
                $gift['total_num'] = $this->countMediaAppGift($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['use_num'] = $this->countMediaAppGiftUse($gift['app_id'], $gift['gift_name'], $gift['original_name']);
                $gift['residue_rate'] = (($gift['total_num'] - $gift['use_num'])/$gift['total_num']) * 100;
            }
        }

        return $giftList;
    }

    /**
     * 获取某款游戏下今天更新的礼包数量
     * @author xy
     * @since 2017/09/13 ：18:42
     * @param  int $appId 游戏id
     * @return bool|int
     */
    public function countPublishGiftBelongAppId($appId){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        $where['gl.app_id'] = $appId;
        //礼包更新时间为今天的的
        $todayStart = strtotime(date('Y-m-d', time()));
        $todayEnd = $todayStart + 86400;
        $where['sgl.publish_time'] = array(array('gt', $todayStart),array('lt', $todayEnd), 'AND');
        //礼包库中有效的未删除的礼包
        $where['gl.start_time'] = array('lt', time());
        $where['gl.end_time'] = array('gt', time());
        $where['gl.is_del'] = array('neq', 1);
        // 媒体站已发布的游戏
        $where['alist.is_publish'] = array('IN', array(1));

        $giftList = M(C('DB_NAME') . '.' . 'app_list', C('DB_PREFIX'))->alias('alist')
            ->field('
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name,
                CONCAT(gl.gift_name, gl.original_name) AS short_gift_name
                ')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS list ON list.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS alib ON alib.`app_id` = alist.`app_id`')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib AS gl ON gl.`app_id` = alist.`app_id`')
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'sync_gift_lib AS sgl ON sgl.`gift_id` = gl.`gift_id`')
            ->where($where)
            ->group('short_gift_name')
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }
        return count($giftList);
    }

    /**
     * 获取礼包中心首页轮播
     * @author xy
     * @since 2017/09/15 18:31
     * @param int $limit
     * @return bool
     */
    public function getGiftIndexSlide($limit = 3){
        //有效时间内，上架状态的轮播数据
        $where = array(
            'gs.start_time' => array('lt', time()),
            'gs.end_time' => array('gt', time()),
            'gs.is_publish' => 1,
            'gs.show_position' => 1,
        );
        $slideList = M('gift_slide')->alias('gs')
            ->field('gs.*, IF(gs.sort = 0, 999999999, gs.sort) as new_sort')
            ->where($where)
            ->order('new_sort ASC')
            ->limit($limit)
            ->select();
        if($slideList === false){
            return $this->setError('查询失败');
        }
        if(!empty($slideList)){
            foreach ($slideList as &$slide){
                if($slide['relation_type'] == 1){
                    $slide['url'] = U('Home/Gift/gift_page', array('app_id'=>$slide['app_id']));
                }else{
                    $slide['url'] = U('Home/Gift/all_gift');
                }
            }
        }

        return $slideList;
    }

    /**
     * 获取礼包中心首页的右侧广告
     * @author xy
     * @since 2017/09/15 18:36
     * @param int $limit
     * @return bool
     */
    public function getGiftIndexLeftAd($limit = 1){
        $slideCate = M('slide_cat')->where(array('is_delete' => 1, 'keyword' => 'GIFT_INDEX_LEFT_AD'))->getField('cid');
        // 获取搜索条件
        if($slideCate === false){
            $this->setError('获取分类信息失败');
        }
        //有效的指定分类的广告
        $where = array(
            's.slide_cid' => $slideCate['cid'],
            's.start_time' => array('lt', time()),
            's.end_time' => array('gt', time()),
            's.is_publish' => 1
        );
        $adList = M('slide')->alias('s')
            ->field('s.*, IF(s.sort = 0, 999999999, s.sort) as new_sort, au.nickname')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'admin_user au ON s.admin_id = au.id')
            ->where($where)
            ->order('new_sort ASC')
            ->limit($limit)
            ->select();
        if($adList === false){
            return $this->setError('查询失败');
        }

        return $adList;
    }

    /**
     * 获取指定数量游戏礼包周榜列表
     * @author xy
     * @since 2017/09/04 16:17
     * @param int $limit 查询数量
     * @return bool
     */
    public function getHotAppGiftWeekList($limit = 10){
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

        //礼包库中被媒体站占用的上周被领取的礼包码数量
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL AND YEARWEEK(date_format(FROM_UNIXTIME(receive_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1  GROUP BY gift_id';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field(
                'sgl.gift_id as sync_gift_id, sgl.limited_count, 
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, 
                gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, 
                CONCAT(al.app_name,gl.gift_name,gl.original_name) AS full_gift_name, 
                CONCAT(al.app_id,gl.gift_name,gl.original_name) AS id_gift_name, 
                sum(cn.total_code_num) as total_code_num, sum(un.code_use_num) as total_use_num, 
                (alist.app_down_num+alist.cardinal) as down_num, IFNULL(lib.icon, al.icon) as icon'
            )
            //获取游戏礼包媒体站占用的礼包数量, INNER JOIN 避免取到没有申请到媒体站的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //获取游戏礼包媒体站已领取的礼包数量
            ->join('LEFT JOIN (' . $subQueryThree . ') AS un ON un.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = list.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = list.`app_id`')
            //关联获取下载量与上架时间
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS alist ON alist.`app_id` = list.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('INNER JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,gl.gift_name,gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->order(' sgl.final_hot_sort ASC, down_num DESC ')
            ->limit($limit)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }

        return $giftList;
    }

    /**
     * 获取指定数量游戏礼包月榜列表
     * @author xy
     * @since 2017/09/04 16:17
     * @param int $limit 查询数量
     * @return bool
     */
    public function getHotAppGiftMonthList($limit = 10){
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

        //礼包库中被媒体站占用的上月被领取的礼包码数量
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL AND date_format(FROM_UNIXTIME(receive_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\')  GROUP BY gift_id';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field(
                'sgl.gift_id as sync_gift_id, sgl.limited_count, 
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, 
                gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, 
                CONCAT(al.app_name,gl.gift_name,gl.original_name) AS full_gift_name, 
                CONCAT(al.app_id,gl.gift_name,gl.original_name) AS id_gift_name, 
                sum(cn.total_code_num) as total_code_num, sum(un.code_use_num) as total_use_num, 
                (alist.app_down_num+alist.cardinal) as down_num, IFNULL(lib.icon, al.icon) as icon'
            )
            //获取游戏礼包媒体站占用的礼包数量, INNER JOIN 避免取到没有申请到媒体站的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //获取游戏礼包媒体站已领取的礼包数量
            ->join('LEFT JOIN (' . $subQueryThree . ') AS un ON un.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = list.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = list.`app_id`')
            //关联获取下载量与上架时间
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS alist ON alist.`app_id` = list.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('INNER JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,gl.gift_name,gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->order(' sgl.final_hot_sort ASC, down_num DESC ')
            ->limit($limit)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }

        return $giftList;
    }

    /**
     * 获取指定数量的热门游戏礼包的日榜列表
     * @author xy
     * @since 2017/09/18 15:04
     * @param int $limit 获取的数量
     * @return bool
     */
    public function getHotAppGiftDailyList($limit = 10){
        //每天的开始时间与结束时间
        $dailyTimeStart = strtotime(date('Y-m-d', time()));
        $dailyTimeEnd = $dailyTimeStart + (86400 - 1);
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

        //礼包库中被媒体站占用的当天被领取的礼包码数量
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL AND (receive_time >="'.$dailyTimeStart.'" AND receive_time <= "'.$dailyTimeEnd.'") GROUP BY gift_id';

        $giftList = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib', C('DB_ZHIYU.DB_PREFIX'))->alias('gl')
            ->field(
                'sgl.gift_id as sync_gift_id, sgl.limited_count, 
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort, 
                gl.gift_id, gl.app_id, gl.gift_name, gl.original_name, gl.gift_icon, al.app_name, 
                CONCAT(al.app_name,gl.gift_name,gl.original_name) AS full_gift_name, 
                CONCAT(al.app_id,gl.gift_name,gl.original_name) AS id_gift_name, 
                sum(cn.total_code_num) as total_code_num, sum(un.code_use_num) as total_use_num, 
                (alist.app_down_num+alist.cardinal) as down_num, IFNULL(lib.icon, al.icon) as icon'
            )
            //获取游戏礼包媒体站占用的礼包数量, INNER JOIN 避免取到没有申请到媒体站的礼包
            ->join('INNER JOIN (' . $subQueryOne . ') AS cn ON cn.`gift_id` = gl.`gift_id`')
            //获取游戏礼包媒体站已领取的礼包数量
            ->join('LEFT JOIN (' . $subQueryThree . ') AS un ON un.`gift_id` = gl.`gift_id`')
            //INNER JOIN 媒体站游戏列表 避免取到未同步到媒体站的游戏 的礼包
            ->join('INNER JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_list AS list ON list.`app_id` = gl.`app_id`')
            //关联获取游戏名称
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_lib AS al ON al.`app_id` = list.`app_id`')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'app_lib AS lib ON lib.`app_id` = list.`app_id`')
            //关联获取下载量与上架时间
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'app_list AS alist ON alist.`app_id` = list.`app_id`')
            //关联获取设置上限数量的礼包
            ->join('INNER JOIN (' . $subQueryTwo . ') AS sgl ON  CONCAT(al.app_id,gl.gift_name,gl.original_name) = sgl.`id_gift_name`')
            ->where($where)
            ->group('full_gift_name')
            ->order(' sgl.final_hot_sort ASC, down_num DESC ')
            ->limit($limit)
            ->select();
        if($giftList === false){
            return $this->setError('查询失败');
        }

        return $giftList;
    }

    /**
     * 获取游戏每日一题页的礼包广告图片
     * @author xy
     * @since 2017/09/21 16:07
     * @param $appId
     * @return bool|array
     */
    public function getAppQuesGiftImageAd($appId){
        if(empty($appId)){
            return $this->setError('必填参数缺失');
        }
        //有效时间内，上架状态的轮播数据
        $where = array(
            'gs.start_time' => array('lt', time()),
            'gs.end_time' => array('gt', time()),
            'gs.is_publish' => 1,
            'gs.app_id' => $appId,
            'gs.show_position' => 2,
        );
        $slide = M('gift_slide')->alias('gs')
            ->field('gs.*, IF(gs.sort = 0, 999999999, gs.sort) as new_sort')
            ->where($where)
            ->order('new_sort ASC')
            ->find();
        if($slide === false){
            return $this->setError('查询失败');
        }

        if(!empty($slide)){
            if ($slide['relation_type'] == 1) {
                $slide['url'] = U('Home/Gift/gift_page', array('app_id' => $slide['app_id']));
            } else {
                $slide['url'] = U('Home/Gift/all_gift');
            }
        }

        return $slide;
    }

    /**
     * 计算用户领取的未删除的礼包的数量
     * @author xy
     * @since 2017/09/29 13:30
     * @param int $userId 用户id
     * @return bool
     */
    public function countUserGiftNum($userId){
        $where = array(
            'glc.uid' => $userId,
            'glc.use_status' => 0, // 0:未使用
            'glc.status' => array('neq', 4), // 已删除的不显示
        );
        $giftNum = M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->alias('glc')
            ->field(
                'glc.code_id, glc.gift_code, glc.status, glc.use_status, 
                gl.start_time, gl.end_time, gl.gift_icon, 
                gl.gift_name,gl.original_name, al.app_name'
            )
            ->join( C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .  'gift_lib gl ON gl.gift_id = glc.gift_id', 'LEFT')
            ->join( C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .  'app_lib al ON al.app_id = gl.app_id', 'LEFT')
            ->where($where)
            ->order('glc.status ASC, glc.receive_time DESC')
            ->count();
        if($giftNum === false){
            return $this->setError('计算用户领取的礼包数失败');
        }
        return $giftNum;
    }

    /**
     * 分页获取用户的礼包列表
     * @author xy
     * @since 2017/09/30 09:58
     * @param int $userId 用户id
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return bool|array
     */
    public function getUserGiftListByPage($userId, $currentPage, $pageSize){
        $where = array(
            'glc.uid' => $userId,
            'glc.use_status' => 0, // 0:未使用
            'glc.status' => array('neq', 4), // 已删除的不显示
        );
        $giftList =  M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->alias('glc')
            ->field(
                'glc.code_id, glc.gift_code, glc.status, glc.use_status, gl.start_time, gl.end_time, 
                gl.gift_icon, al.app_name,gl.gift_name,gl.original_name, gl.gift_desc, 
                IFNULL(alib.icon, al.icon) as icon'
            )
            ->join( C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .  'gift_lib gl ON gl.gift_id = glc.gift_id', 'LEFT')
            ->join( C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') .  'app_lib al ON al.app_id = gl.app_id', 'LEFT')
            ->join( C('DB_NAME') . '.' . C('DB_PREFIX') .  'app_lib alib ON al.app_id = alib.app_id', 'LEFT')
            ->where($where)
            ->page($currentPage, $pageSize)
            ->order('glc.status ASC, glc.receive_time DESC')
            ->select();
        if($giftList === false){
            return $this->setError('查询我的礼包失败');
        }
        if(!empty($giftList)){
            foreach($giftList AS &$value) {
                $value['gift_icon'] = format_url($value['gift_icon']);
                $value['icon'] = format_url($value['icon']);
                if(empty($value['gift_icon'])){
                    $value['gift_icon'] = $value['icon'];
                }
                if ($value['status'] !=3 && $value['end_time'] < time()) {
                    $value['status'] = 3;
                    // 更新过期状态
                    M(C('DB_ZHIYU.DB_NAME') . '.' . 'gift_lib_code', C('DB_ZHIYU.DB_PREFIX'))->save(array('code_id' => $value['code_id'], 'status' => 3));
                }
                $value['start_time'] = date('Y-m-d', $value['start_time']);
                $value['end_time'] = date('Y-m-d', $value['end_time']);
            }
        }
        return $giftList;
    }
}