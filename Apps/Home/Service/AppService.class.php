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

class AppService extends BaseService
{
    const RANk_TYPE_DOWNLOAD = 0;       //下载榜
    const RANK_TYPE_PAY = 1;            //畅销榜
    const RANK_TYPE_NEW = 2;            //新游榜

    const DATA_SOURCE_WEEK = 1;         //周榜
    const DATA_SOURCE_MONTH = 2;        //月榜
    const DATA_SOURCE_TOTAL = 3;        //总榜

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
            'alist.publish_time' => array('lt', time()) //上架时间小于当前时间
        );

        $hotAppList = M('app_list')->alias('alist')
            ->field('alist.app_id, list.status, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IF(alist.final_hot_sort=0, 9999999, alist.final_hot_sort) as final_hot_sort, (list.app_down_num + list.cardinal) as app_down_num')
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
            'alist.publish_time' => array('lt', time()) //上架时间小于当前时间
        );
        $appList = M('app_list')->alias('alist')
            ->field('list.app_id, list.status, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, lib.start_score as yz_start_score, lib.update_time as zy_update_time, alib.start_score, alib.update_time')
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
            if (empty($app['app_name'])) {
                $app['app_name'] = $app['zy_app_name'];
                unset($app['zy_app_name']);
            }
            if (empty($app['icon'])) {
                $app['icon'] = $app['zy_icon'];
                unset($app['zy_icon']);
            }
            //取游戏的第一个字符，转换为拼音
            $firstChar = mb_substr($app['app_name'], 0, 1, 'UTF-8');
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
            if (!empty($numberToEn[$firstChar])) {
                //如果第一个是数字，则找对应的英文
                $firstCharPy = $numberToEn[$firstChar];
            } else {
                //如果是汉字则转换为拼音
                $firstCharPy = PinYin::utf8_to($firstChar);
            }
            $char = strtoupper(mb_substr($firstCharPy, 0, 1, 'UTF-8'));
            $app['pin_yin'] = strtoupper($char);
            $tempArr[$char][] = $app;
        }
        $returnArr = array(
            'ABCD' => array(),
            'EFGH' => array(),
            'IJKL' => array(),
            'MNOP' => array(),
            'RSTU' => array(),
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
                $returnArr['RSTU'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('U','V','W','X'))){
                $returnArr['UVWX'][$letter] = $tempArr[$letter];
            }
            if(in_array($letter, array('Y','Z'))){
                $returnArr['YZ'][$letter] = $tempArr[$letter];
            }
        }
        //var_dump($returnArr);die;
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
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL ';

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
            ->order(' sgl.final_hot_sort ASC, down_num DESC ')
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
     * @return bool
     */
    public function getHotAppRankWeek($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['app_down_num'] = array('neq', 0);
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, adown.`down_num` AS app_down_num, arank.rank_id, IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 )) as final_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE YEARWEEK(date_format(FROM_UNIXTIME(down_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1 GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个礼拜下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('final_sort ASC, app_down_num DESC')
            ->where($where)
            ->limit($limit)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        return $result;
    }

    /**
     * 获取首页的下载榜月榜指定条数的数据
     * @author xy
     * @since 2017/08/30 15:40
     * @param int $limit
     * @return bool
     */
    public function getHotAppRankMonth($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['app_down_num'] = array('neq', 0);
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, adown.`down_num` AS app_down_num, arank.rank_id, IF( arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, count(*) AS `down_num` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_down WHERE date_format(FROM_UNIXTIME(down_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') GROUP BY `app_id` ) as adown on adown.app_id = alist.app_id')//关联查询上一个月下载量的的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('final_sort ASC, app_down_num DESC')
            ->where($where)
            ->limit($limit = 10)
            ->select();
//        var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        return $result;
    }

    /**
     * 获取首页指定条数下载榜总榜的数据
     * @author xy
     * @since 2017/08/30 15:48
     * @param int $limit
     * @return bool
     */
    public function getHotAppRankTotal($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $where['app_down_num'] = array('neq', 0);

        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, (list.app_down_num + list.cardinal) as app_down_num, arank.rank_id, IF(arank.final_sort=0, 9999999, IFNULL( arank.final_sort, 9999999 ) ) as final_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, final_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 0 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('final_sort ASC, app_down_num DESC')
            ->where($where)
            ->limit($limit)
            ->select();
        if($result === false){
            $this->setError('查询失败');
            return false;
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
     * @return bool
     */
    public function getPopularAppRankWeek($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, pay.`total_money`, arank.rank_id, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 )) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE YEARWEEK(date_format(FROM_UNIXTIME(create_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1  AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个礼拜app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 1 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('pre_sort ASC, total_money DESC')
            ->where($where)
            ->limit($limit)
            ->select();
        //var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        return $result;
    }

    /**
     * 获取指定条数的游戏畅销榜月榜的数据
     * @author xy
     * @since 2017/08/30 16:07
     * @param int $limit 查询条数
     * @return bool
     */
    public function getPopularAppRankMonth($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, pay.`total_money`, arank.rank_id, IF( arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱游戏列表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT `app_id`, sum(money) AS `total_money` FROM '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'sdk_order WHERE date_format(FROM_UNIXTIME(create_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\') AND (`status` = 0 OR `status` = 4) GROUP BY `app_id` ) as pay on pay.app_id = alist.app_id')//关联查询上一个月app充值(已完成和支付成功的)的子查询
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 2 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('pre_sort ASC, total_money DESC')
            ->where($where)
            ->limit($limit)
            ->select();
//        var_dump(M('app_list')->getLastSql());
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        return $result;
    }

    /**
     * 获取指定条数的游戏畅销榜总榜的数据
     * @author xy
     * @since 2017/08/30 16:07
     * @param int $limit 查询条数
     * @return bool
     */
    public function getPopularAppRankTotal($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, (list.pay_total_money ) as total_money, arank.rank_id, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 1 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('pre_sort ASC, total_money DESC')
            ->where($where)
            ->limit($limit)
            ->select();
        if($result === false){
            $this->setError(M('app_list')->getDbError());
            return false;
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
            $rankList = $this->getPopularAppRankMonth($limit);
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
     * @return bool
     */
    public function getNewAppRankTotal($limit = 10){
        //获取媒体站已上架的游戏的礼包
        $where['alist.is_publish'] = array('IN', array(1));
        $result = M('app_list')->alias('alist')
            ->field('alist.`app_id`, IFNULL(alib.app_name, lib.app_name) as app_name, IFNULL(alib.icon, lib.icon) as icon, IFNULL(alib.app_type, lib.app_type) as app_type, list.`status`, list.`sj_time`, arank.`rank_id`, IF(arank.pre_sort=0, 9999999, IFNULL( arank.pre_sort, 9999999 ) ) as pre_sort')
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_list list on list.app_id = alist.app_id')//关联指娱app_list表
            ->join('INNER JOIN '.C('DB_ZHIYU.DB_NAME').'.'.C('DB_ZHIYU.DB_PREFIX').'app_lib lib on lib.app_id = alist.app_id')//关联指娱游戏库表
            ->join('LEFT JOIN '.C('DB_NAME').'.'.C('DB_PREFIX').'app_lib alib on alib.app_id = alist.app_id')//关联媒体站游戏库表
            ->join('LEFT JOIN ( SELECT id as rank_id, app_id, pre_sort FROM '.C('DB_NAME').'.'.C('DB_PREFIX').'app_ranking WHERE ranking_type = 2 AND data_source = 3 ) AS arank ON arank.app_id = alist.app_id')//关联排行榜表
            ->order('pre_sort ASC, sj_time DESC')
            ->where($where)
            ->limit($limit)
            ->select();
        //echo M('app_list')->getLastSql();die;
        if($result === false){
            $this->setError('查询失败');
            return false;
        }
        return $result;
    }

    /**
     * 根据给定的时间获该礼拜发布的未删除的专题信息
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
        return $appTopic;
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
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL AND YEARWEEK(date_format(FROM_UNIXTIME(receive_time), \'%Y-%m-%d\')) = YEARWEEK(now())-1';

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
            ->order(' sgl.final_hot_sort ASC, down_num DESC ')
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
     * 获取指定数量游戏礼包周榜列表
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
        $subQueryThree = ' SELECT gift_id, count(*) AS code_use_num FROM ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'gift_lib_code WHERE use_type = 3 AND use_status IS NOT NULL AND date_format(FROM_UNIXTIME(receive_time), \'%Y-%m\') = date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),\'%Y-%m\')';

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
            ->order(' sgl.final_hot_sort ASC, down_num DESC ')
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
}