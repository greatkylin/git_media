<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Think;

class UserPage{
    public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows; // 总行数
    public $totalPages; // 分页总页面数
    public $rollPage   = 5;// 分页栏每页显示的页数
	public $lastSuffix = true; // 最后一页是否显示总页数

    private $p       = 'p'; //分页参数名
    private $url     = ''; //当前链接URL
    private $nowPage = 1;

    private $isAjax = true;

	// 分页显示定制
    private $config  = array(
        'header' => '',
        'prev'   => '',
        'next'   => '',
        'first'  => '1',
        'last'   => '%TOTAL_PAGE%',
        'theme'  => '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%',
    );

    /**
     * 架构函数
     * @param int $totalRows  总的记录数
     * @param int $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     * @param boolean $isAjax  是否ajax分页
     */
    public function __construct($totalRows, $listRows=20, $parameter = array(), $isAjax = true) {
        C('VAR_PAGE') && $this->p = C('VAR_PAGE'); //设置分页参数名称
        /* 基础设置 */
        $this->totalRows  = $totalRows; //设置总记录数
        $this->listRows   = $listRows;  //设置每页显示行数
        $this->parameter  = empty($parameter) ? $_GET : $parameter;
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);
        $this->isAjax = $isAjax;
    }

    /**
     * 定制分页链接设置
     * @param string $name  设置名称
     * @param string $value 设置值
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }

    /**
     * 组装分页链接
     * @return string
     */
    public function show() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页临时变量 */
        $now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		//$this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
        if($this->isAjax){
            $up_page = $up_row > 0 ? '<section class="pc-pageleft page-jump" data-href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</section>' : '';
        }else{
            $up_page = $up_row > 0 ? '<a class="pc-pageleft" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';
        }

        //下一页
        $down_row  = $this->nowPage + 1;
        if($this->isAjax){
            $down_page = ($down_row <= $this->totalPages) ? '<section class="pc-pageright page-jump" data-href="' . $this->url($down_row) . '">' . $this->config['next'] . '</section>' : '';
        }else{
            $down_page = ($down_row <= $this->totalPages) ? '<a class="pc-pageright" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';
        }

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            if($this->isAjax) {
                $the_first = '<div class="pagenum page-jump" data-href="' . $this->url(1) . '">' . $this->config['first'] . '</div>';
                $the_first .= '<div class="pagenum">……</div>';
            }else{
                $the_first = '<a class="pagenum" href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
                $the_first .= '<a class="pagenum">……</a>';
            }
        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            if($this->isAjax){
                $the_end = '<div class="pagenum">……</div>';
                $the_end .= '<div class="pagenum page-jump" data-href="' . $this->url($this->totalPages - 1) . '">' . ($this->totalPages - 1). '</div>';
                $the_end .= '<div class="pagenum page-jump" data-href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</div>';
            }else{
                $the_end = '<a class="pagenum">……</a>';
                $the_end .= '<a class="pagenum page-jump" data-href="' . $this->url($this->totalPages - 1) . '">' . ($this->totalPages - 1). '</a>';
                $the_end .= '<a class="pagenum page-jump" data-href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
            }
        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
			if(($this->nowPage - $now_cool_page) <= 0 ){
				$page = $i;
			}elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
				$page = $this->totalPages - $this->rollPage + $i;
			}else{
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    if($this->isAjax){
                        $link_page .= '<div class="pagenum page-jump" data-href="' . $this->url($page) . '">' . $page . '</div>';
                    }else{
                        $link_page .= '<a class="pagenum" href="' . $this->url($page) . '">' . $page . '</a>';
                    }
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    if($this->isAjax){
                        $link_page .= '<div class="pagenum pc-pageactive page-jump" data-href="'.$this->url($this->nowPage).'">' . $page . '</div>';
                    }else{
                        $link_page .= '<a class="pagenum pc-pageactive" href="'.$this->url($this->nowPage).'">' . $page . '</a>';
                    }
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
            $this->config['theme']);
        return $page_str;
    }
}
