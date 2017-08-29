<?php

/**
 * 商品: /product/{iid}.html   /product/{iid}.html   用英文
 * 栏目: /category_{cid}.html /category_{cid}_{page}.html   category-
 * 导航栏目: /category_n_{navCatID}.html /category_n_{navCatID}_{page}.html  category
 * 搜索: /search_{keywords}.html 
 * 新品: /new.html /new_{page}.html
 * 热卖: /hot.html /hot_{page}.html
 * 文章: /news/{id}.html
 * 文章列表: /news_category_{id}.html /news_category_{id}_{page}.html
 */
class URLService {

    var $url = CONFIG_SITEURL;

    /**
     * 进行生成后续参数
     * @param array|string $param
     * @param array $filter
     * @return string
     */
    function build($param, $filter = array('_URL_', 'page')) {
        if (empty($param)) {
            $param = "";
        } elseif (is_array($param)) {
            if (count($param) > 0) {
                if (count($filter) > 0) {
                    foreach ($filter as $value) {
                        unset($param[$value]);
                    }
                }
                if (count($param) > 0) {
                    $param = "?" . http_build_query($param);
                } else {
                    $param = "";
                }
            } else {
                $param = "";
            }
        } else {
            if (strpos($param, '?') !== 0) {
                $param = "?" . $param;
            }
        }
        return $param;
    }

    /**
     * 构造商品页面url
     * @param type $iid
     * @return type 
     */
    function item($iid, $flag = false) {
        if ($flag) {
            $return_url = $this->url . '/product/' . $iid . '.html';
        } else {
            $return_url = '/product/' . $iid . '.html';
        }
        return $return_url;
    }

    /**
     * 构造栏目 url
     * @param type $cid
     * @param type $page
     * @return type 
     */
    function setCategoryUrl($cid, $page = 0, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/category_' . $cid . '.html';
        } else {
            $url = $this->url . '/category_' . $cid . '_' . $page . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //导航栏目
    function setCategoryNUrl($catid, $page = 0, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/category_n_' . $catid . '.html';
        } else {
            $url = $this->url . '/category_n_' . $catid . '_' . $page . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //搜索url
    function setSearchUrl($key, $page = 0, $suffix = "") {

        if (empty($page)) {
            $url = $this->url . '/search_' . trim($key) . '.html';
        } else {
            $url = $this->url . '/search_' . trim($key) . '_' . $page . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //新品
    function setNewUrl($page = 0, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/new.html';
        } else {
            $url = $this->url . '/new_' . trim($page) . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //售完欣赏
    function setSellOutUrl($page = 0, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/sellout.html';
        } else {
            $url = $this->url . '/sellout_' . trim($page) . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //全积分兑换
    function setScoreUrl($page = 0, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/score.html';
        } else {
            $url = $this->url . '/score_' . trim($page) . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //现金专区
    function setCashUrl($page = 0, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/cash.html';
        } else {
            $url = $this->url . '/cash_' . trim($page) . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //热卖
    function setHotUrl($page = 0) {
        if (empty($page)) {
            return $this->url . '/hot.html';
        } else {
            return $this->url . '/hot_' . trim($page) . '.html';
        }
    }

    //文章
    function setNewsUrl($id) {
        if (!empty($id)) {
            return $this->url . '/news/' . $id . '.html';
        } else {
            return false;
        }
    }

    //文章列表
    function setNewsCatId($id, $page, $suffix = "") {
        if (empty($page)) {
            $url = $this->url . '/news_category_' . $id . '.html';
        } else {
            $url = $this->url . '/news_category_' . $id . '_' . $page . '.html';
        }
        $url = $url . $this->build($suffix);
        return $url;
    }

    //构造带类别的文章地址
    function setNewsRootId($id, $rootid) {
        if ($id != '' AND $rootid != '') {
            return $this->url . '/news_root_' . $id . '-' . $rootid . '.html';
        } else {
            return false;
        }
    }

}

?>