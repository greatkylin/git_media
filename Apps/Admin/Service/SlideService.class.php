<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 17:39
 */

namespace Admin\Service;


use Common\Service\BaseService;

class SlideService extends BaseService
{
    /**
     * 根据分类id验证分类是否存在
     * @author xy
     * @since 2017/09/15 10:29
     * @param $cid
     * @return bool
     */
    public function isSlideCidExist($cid){
        $cid = intval($cid);
        if(empty($cid)){
            return false;
        }
        $where['cid'] = $cid;
        $where['is_delete'] = 1;
        $category = M('slide_cat')->where($where)->find();
        if(empty($category)){
            return $this->setError('分类不存在或者已删除');
        }
        return true;
    }

    /**
     * 计算幻灯片的数量
     * @author xy
     * @since 2017/09/15 17:13
     * @param array $where 查询条件
     * @return mixed
     */
    public function countSlideNum($where = array()){
        $totalNum = M('slide')->alias('s')
            ->field('s.*, IF(s.sort = 0, 999999999, s.sort) as new_sort, au.nickname')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'admin_user au ON s.admin_id = au.id')
            ->where($where)
            ->count();
        if($totalNum === false){
            return $this->setError('查询轮播图片数量失败');
        }
        return $totalNum;
    }

    /**
     * 分页获取幻灯片列表
     * @author xy
     * @since 2017/09/15 17:11
     * @param array $where 查询条件
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return mixed
     */
    public function getSlideListByPage($where = array(), $currentPage, $pageSize){
        $slideList = M('slide')->alias('s')
            ->field('s.*, IF(s.sort = 0, 999999999, s.sort) as new_sort, au.nickname, sc.cat_name')
            ->join('LEFT JOIN ' . C('DB_NAME') . '.' . C('DB_PREFIX') . 'slide_cat sc ON s.slide_cid = sc.cid')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'admin_user au ON s.admin_id = au.id')
            ->where($where)
            ->order('new_sort ASC')
            ->limit($currentPage. ',' .$pageSize)
            ->select();
        if($slideList === false){
            return $this->setError('查询轮播失败');
        }
        $now_time = time();
        foreach ($slideList as &$value) {
            # 自动下架
            if ($value['end_time'] < $now_time) {
                M('slide')->save(array('slide_id' => $value['slide_id'], 'is_publish' => 2));
            }
            if ($now_time < $value['start_time'] && $value['is_publish'] == 1) {
                $value['publish_name'] = '待上线';
            } elseif ($value['start_time'] < $now_time && $value['end_time'] > $now_time && $value['is_publish'] == 1) {
                $value['publish_name'] = '已上线';
            } elseif ($value['end_time'] < $now_time || $value['is_publish'] == 2) {
                $value['publish_name'] = '已下线';
            }
        }
        return $slideList;
    }
}