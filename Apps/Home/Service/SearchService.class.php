<?php
/**
 * 搜索相关的业务操作
 * User: xy
 * Date: 2017/9/22
 * Time: 17:27
 */

namespace Home\Service;


use Common\Service\BaseService;

class SearchService extends BaseService
{
    /**
     * 记录搜索的关键字记录
     * @author xy
     * @since 2017/09/25 10:43
     * @param string $keyword 搜索的关键词
     * @return bool
     */
    public function recordSearchLog($keyword){
        $keyword = trim($keyword);
        $clientIp = get_client_ip(0, true);
        $userInfo = get_user_info();
        if($userInfo){
            $userId = $userInfo['id'];
        }else{
            $userId = '';
        }
        $data = array(
            'keyword' => $keyword,
            'user_id' => $userId,
            'client_ip' => $clientIp,
            'create_time' => time(),
        );
        $result = M('search_log')->add($data);
        if(!$result){
            return $this->setError('添加搜索记录失败');
        }
        return true;
    }

    /**
     * 计算搜索关键的的次数
     * @author xy
     * @since 2017/09/25 10:51
     * @param string $keyword 搜索的关键词
     * @return bool
     */
    public function countSearchKeywordNum($keyword){
        $keyword = trim($keyword);
        $isExist = $this->isKeywordExist($keyword);
        //如果存在则更新 search_count 字段，否则添加新记录
        if($isExist){
            $result = M('search_key')->where(array('keyword'=>$keyword))->setInc('search_count');
        }else{
            $data = array(
                'keyword' => $keyword,
                'search_count' => 1,
                'create_time' => time(),
            );
            $result = M('search_key') -> add($data);
        }
        if(!$result){
            return $this->setError('添加搜索记录失败');
        }
        return true;
    }

    /**
     * 判断是否有搜索过该关键词
     * @author xy
     * @since 2017/09/25 10:55
     * @param string $keyword 搜索的关键词
     * @return bool
     */
    public function isKeywordExist($keyword){
        $keyword = trim($keyword);
        $result = M('search_key')->where(array('keyword' => $keyword))->count();
        if($result > 0){
            return true;
        }
        return false;
    }
}