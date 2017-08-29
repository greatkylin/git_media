<?php
namespace Home\Controller;
use Think\Controller;
use Home\Controller\HomeBaseController;

class IndexController extends HomeBaseController {
    public function index(){
        //获取顶部大图

        $this->display();
    }
}