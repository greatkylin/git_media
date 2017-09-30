<?php
/**
 * 搜索相关的操作
 * User: xy
 * Date: 2017/9/25
 * Time: 16:49
 */

namespace Admin\Service;


use Common\Service\BaseService;

class SearchService extends BaseService
{
    /**
     * 计算搜索热词的总量
     * @author xy
     * @since 2017/09/25 17:39
     * @param array $where
     * @return bool|int
     */
    public function countHotKeywordNum($where = array()){
        $totalNum = M('search_recommend')->alias('s')
            ->field('s.*, IF(s.sort = 0, 999999999, s.sort) as new_sort, au.nickname')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'admin_user au ON s.admin_id = au.id')
            ->where($where)
            ->count();
        if($totalNum === false){
            return $this->setError('查询搜索热词数量失败');
        }
        return $totalNum;
    }

    /**
     * 分页获取前台热词推荐列表
     * @author xy
     * @since 2017/09/25 17:42
     * @param array $where 查询条件
     * @param int $currentPage 当前页
     * @param int $pageSize 页大小
     * @return mixed
     */
    public function getHotKeywordListByPage($where = array(), $currentPage, $pageSize){
        $keywordList = M('search_recommend')->alias('s')
            ->field('s.*, IF(s.sort = 0, 999999999, s.sort) as new_sort, au.nickname')
            ->join('LEFT JOIN ' . C('DB_ZHIYU.DB_NAME') . '.' . C('DB_ZHIYU.DB_PREFIX') . 'admin_user au ON s.admin_id = au.id')
            ->where($where)
            ->order('new_sort ASC')
            ->limit($currentPage. ',' .$pageSize)
            ->select();
        if($keywordList === false){
            return $this->setError('查询搜索热词失败');
        }
        $now_time = time();
        foreach ($keywordList as &$value) {
            if ($now_time < $value['start_time']) {
                $value['publish_name'] = '待上线';
            } elseif ($value['start_time'] < $now_time && $value['end_time'] > $now_time) {
                $value['publish_name'] = '已上线';
            } elseif ($value['end_time'] < $now_time) {
                $value['publish_name'] = '已下线';
            }
        }
        return $keywordList;
    }
}