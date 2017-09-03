<?php
/**
 * 首页相关的业务操作
 * User: xy
 * Date: 2017/8/29
 * Time: 18:38
 */

namespace Home\Service;
use Common\Service\BaseService;

class IndexService extends BaseService
{
    /**
     * 通过图片分类关键字选取指定数量的图片
     * @author xy
     * @since 2017/08/30 09:42
     * @param string $keyword 图片分类关键字
     * @param int $limit 选取图片数量
     * @return bool
     */
    public function getAdContentListByCategoryKeyword($keyword, $limit = 3){
        if(empty($keyword)){
            $this->setError('请填写分类关键字');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //图片未删除
            'c.is_publish' => 1, //图片已发布
            'acc.keyword' => strtoupper($keyword), //指定的分类
        );

        $adImageList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->where($where)
            ->limit($limit)
            ->order('sort ASC')
            ->select();

        if($adImageList === false){
            return $this->setError('查询失败');
        }

        return $adImageList;
    }

    /**
     * 通过图片分类关键字选取指定数量的图片
     * @author xy
     * @since 2017/08/30 09:42
     * @param int $id 图片分类id
     * @param int $limit 选取图片数量
     * @return bool
     */
    public function getAdContentListByCategoryId($id, $limit = 3){
        if(empty($id) || !is_numeric($id)){
            $this->setError('请填写分类id');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //图片未删除
            'c.is_publish' => 1, //图片已发布
            'acc.id' => $id, //指定的分类
        );

        $adImageList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->where($where)
            ->limit($limit)
            ->order('c.sort ASC')
            ->select();

        if($adImageList === false){
            return $this->setError('查询失败');
        }

        return $adImageList;
    }


    /**
     * 通过分类关键字选取指定数量的新游测评内容
     * @author xy
     * @since 2017/09/03 20:29
     * @param string $keyword 分类关键字
     * @param int $limit 选取数量
     * @return bool
     */
    public function getNewAppPreviewByCategoryKeyword($keyword, $limit = 3){
        if(empty($keyword)){
            $this->setError('请填写分类关键字');
            return false;
        }

        $where = array(
            'acc.is_delete' => 1, //分类未删除
            'c.is_delete' => 1, //未删除
            'c.is_publish' => 1, //已发布
            'acc.keyword' => strtoupper($keyword), //指定的分类
        );

        $contentList = M('index_column_category')->alias('acc')
            ->field('acc.keyword, c.*, IF(c.sort=0, 9999999, c.sort) AS sort, app.*')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_column_content as c ON c.category_id = acc.id')
            ->join('INNER JOIN '. C('DB_NAME') . '.' . C('DB_PREFIX') . 'index_app_test_open as app ON app.index_content_id = c.id')
            ->where($where)
            ->limit($limit)
            ->order('sort ASC')
            ->select();

        if($contentList === false){
            return $this->setError('查询失败');
        }

        return $contentList;
    }
}