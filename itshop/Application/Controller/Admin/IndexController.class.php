<?php

/**
 * Created by PhpStorm.
 *主要用于显示后台主页页面控制器
 */
class IndexController   extends  PaltformController
{
    public function index(){
        //显示页面
        //整个后台主页面
        $this->display('index');
    }
    public function menu(){
        //显示页面
        //后台主页面中导航页面
        $this->display('menu');
    }
    public function main(){
        //显示页面
        //后台主页面的的主要页面
    $this->display('main');
}
    public function top(){
        //显示页面
        //后台主页面的头部页面
        $this->display('top');
    }
}