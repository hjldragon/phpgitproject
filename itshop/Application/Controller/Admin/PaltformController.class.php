<?php

/**
 * Created by PhpStorm.
 *平台统一验证控制器
 */
//所有有显示页面的平台都需要继承该控制器来进行验证并判断是否登录
class PaltformController  extends Controller
{
    //因为构造方法里面的方法会初始化所有方法，所以建立一个构造方法自动帮我进行验证用户是否登录了
    public function __construct(){
        //调用验证信息方法来获取结果，判断是否验证登录成功与否
        if($this->checkLogin() ===false){
            //没有登录就直接给于返回跳转到登录界面
            $this->redirect("index.php?p=Admin&c=Login&a=login","没有登录拒绝访问",2);
        }

    }
    //建立一个方法来验证用户是否登录了
    private function checkLogin(){
        //先使用session中的用户信息来判断是否登录
        @session_start();
//      var_dump($_SESSION['ROWS']);EXIT;
        if(!isset($_SESSION['ROWS'])){
            //如果有登录先在cookie判断是否有id和password及先检查cookie中的ID和password
            if(isset($_COOKIE['id']) && isset($_COOKIE['password'])){
                //如果有就将id和password取出于数据库进行对比及接收参数
                $id=$_COOKIE['id'];
                $pwd=$_COOKIE['password'];
//                var_dump($id);EXIT;
                //处理数据调用数据用户信息里的model里checkByCookie方法来进行验证
                $AdminModel = new AdminModel();
               $result = $AdminModel->checkByCookie($id,$pwd);
                //判断返回的结果集是否正确
                if($result !== false){
                    //如果成功将数据保存到session中
                    $_SESSION['ROWS'] = $result;
                    return true;
                }else{
                    //失败返回上级
                    return false;
                }
            }
            //没有就返回false跳转到登录页面
            return false;
        }

    }
}