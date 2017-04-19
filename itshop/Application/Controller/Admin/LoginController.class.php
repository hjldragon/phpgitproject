<?php

/**
 * Created by PhpStorm.
 *后台登录页面控制器
 */

class LoginController extends Controller
{
//后台登录页面显示
    public function Login(){
        //显示页面
        $this->display('login');
    }
    //登录后台验证方法
    public function check(){
        //因为验证验证码在没有输入的情况下也会显示所以要先将验证码进行处理
        //接收验证码参数
        $captcha = $_POST['captcha'];
        //处理数据
        //因为这验证码和生成的随机字符串的在session中进行对比的时候会有区分大小写
        //所有要用函数strtolower这个函数将其统一为小写来验证
        //因为要执行$_SESSION所以必须先开启session机制
        @session_start();
//        var_dump($_SESSION['USER_INFO']);
        //将输入的验证码和session中验证码进行对比，如果错误返回错误信息正确执行下一步输入
        if(strtoupper ($captcha) != strtoupper ($_SESSION['USER_INFO'])){
            $this->redirect("index.php?p=Admin&c=Login&a=login","验证码输入错误",3);
        }
        //接收输入账号和密码参数
        $username=$_POST['username'];
        $password=$_POST['password'];
        //接收参数后需要处理接收数据
        //新建一个对象
        //并用新对象调用模型中的一个方法check方法来验证输入的参数
        $adminModel= new AdminModel();
        $result=$adminModel->check($username,$password);
//        var_dump($result);exit;
        //在模型中验证后的结果将其返回的结果进行判断
        if($result === false){
            //判断后显示页面如果错误跳转到登录页面并提示错误
            $this->redirect("index.php?p=Admin&c=Login&c=login",$adminModel->getError(),3);
        }else {
            //因为成功后要将输入的的密码和账号保存到session中及用session保存得到的信息
            $_SESSION['ROWS']=$result;
//            var_dump($_SESSION['ROWS']);
//         var_dump($result);exit;
            //保存后判断是否点击了保存登录，如果点击及要保存到cookie中
            if(isset($_POST['remember'])){
                //因为点击了所以将其id和password保存到cookie信息中
                setcookie('id',$result['id'],time()+7*24*3600,'/');
                //因为在保存密码时需要加密所以先这里对密码进行加密工作
                $password=md5($result['password']);
                setcookie('password',$password,time()+7*24*3600,'/');
            }
            //如果以上执行正确就跳转到后台页面
            //显示页面
            $this->redirect("index.php?p=Admin&c=Index&a=index");
        }
    }
    //建立注销登录方法
    public  function loginout(){
        //接收参数
        //处理数据
        //执行SESSION时开起机制
        @session_start();
        //删除session中的所有数据
        $_SESSION=[];
        //删除cookie中的数据
        setcookie('id',null,-1,'/');
        setcookie('pwd',null,-1,'/');
        //删除完毕后显示页面
        $this->redirect("index.php?p=Admin&c=Login&a=login","注销成功",2);
    }
}