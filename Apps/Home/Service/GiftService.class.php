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
     * 获取指定数量的热门礼包游戏
     * @author xy
     * @since 2017/09/12 17:29
     * @param int $limit 查询数量
     * @return bool|array
     */
    public function getHotGiftList($limit = 8){

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
                    $gift['residue_rate'] = $latestGift;
                }
                //获取该游戏下的礼包种类数量
                $gift['gift_kind_num'] = $this->countAllGiftBelongAppId($gift['app_id']);
                //获取今天更新的礼包种类的数量
                $gift['gift_publish_num'] = $this->countPublishGiftBelongAppId($gift['app_id']);
            }
        }

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
                sgl.gift_id as sync_gift_id, sgl.limited_count, gl.gift_id, gl.app_id, gl.original_name, gl.gift_name,
                gl.gift_icon, CONCAT(lib.app_name, gl.gift_name, gl.original_name) AS full_gift_name, 
                CONCAT(gl.app_id, gl.gift_name, gl.original_name) AS id_gift_name, 
                IF(sgl.final_hot_sort=0, 999999999, IFNULL(sgl.final_hot_sort,999999999)) as final_hot_sort,
                (list.app_down_num + list.cardinal) as down_num, IFNULL(alib.platform, lib.platform) as platform,
                IFNULL(alib.icon, lib.icon) as icon,
                IFNULL(alib.app_name, lib.app_name) as app_name,
                gl.start_time,gl.end_time, IFNULL(sgl.gift_detail, gl.gift_desc), gl.use_rule,
                CONCAT(gl.gift_name, gl.original_name) AS short_gift_name
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
        $where['sgl.publish_time'] = array('gt', $todayStart);
        $where['sgl.publish_time'] = array('lt', $todayEnd);
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

}