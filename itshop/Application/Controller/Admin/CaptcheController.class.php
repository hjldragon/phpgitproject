<?php

/**
 * Created by PhpStorm.
设置验证码控制器
 */

class CaptcheController extends Controller
{
    public function index(){
        //1生成随机字符串
        //1a准备固定好的字符串
        $string="ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        //1b打乱字符串
        $string=str_shuffle($string);
        //1c截取字符串
        $rows=substr($string,0,4);
        //2因为将随机生成字符串保存到session中所以要开启session机制
        @session_start();
        $_SESSION['USER_INFO']=$rows;
//        echo $rows;
//        var_dump($rows);exit;
        //3建立随机背景
        //3a获取背景图片地址
        $captcha_path=PUBLIC_PATH."Admin/captcha/captcha_bg".mt_rand(1,5).".jpg";
//        echo $captcha_path;exit;
        //3b获取图片信息
        $imageinfo=getimagesize($captcha_path);
        //3c获取图片高宽
        list($width,$height)=$imageinfo;
        //3d用url创建一个新图像
        $image=imagecreatefromjpeg($captcha_path);
        //4建立边框
        //4a选择颜色选择
        $color=imagecolorallocate($image,254,0,0);
        //4b生成边框
        imagerectangle($image,0,0,$width-1,$height-1,$color);
        //5随机字体颜色
        $black=imagecolorallocate($image,0,0,0);
        //生成颜色
        imagestring($image,5,$width/3,$height/6,$rows,mt_rand(0,1)?$color:$black);
      //设置图片响应头
        header("Content-Type:".$imageinfo['mime'].";charest=utf-8");
        //销毁图片
        imagejpeg($image);
        imagedestroy($image);

    }
}