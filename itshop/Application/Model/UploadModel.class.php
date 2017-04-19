<?php

/**
 * Created by PhpStorm.
 * 文件上传模型
 */
date_default_timezone_set('PRC');
class UploadModel extends Model
{
    //文件模型上传方法
    public function upload($img,$ird)//$ird是通过goods建立的形参
    {
//        var_dump($img);exit;
        //判断文件上传是否成功
        if ($img['error'] != 0) {
            $this->error = "文件上传失败";
            return false;
        }
        //判断文件类型是符合要求

        $type = ["image/jpeg", "image/png", "image/gif"]; //设置文件类型
        if (!in_array($img['type'],$type)) {
            $this->error = "文件类型不符合要求";
            return false;
        }
        //判断文件大小是否符合要求

        $size=2*1024*1024; //设置文件大小
        if($img['size']>$size){
            $this->error="文件大小不符合要求";
            return false;
        }
        //判断文件是否是通过http POST的传送方式上传
        if(!is_uploaded_file($img['tmp_name'])){
            $this->error="文件不是通过浏览器上传";
            return false;
        }
        //分目录来存储文件
        //设置一个目录的文件名
        $dir = UPLOADS_PATH.$ird.date('Ymd').'/';
        //检查是否有该文件，没有就自动创建用跌打
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
//        var_dump($dir);exit;
    //设置上传文件的名字
        $filename=uniqid("YO_").strrchr($img['name'],'.');
//        var_dump($filename);exit;
        //判断文件是否保存成功并保存在创建的文件名中
        if(move_uploaded_file($img['tmp_name'],$dir.$filename)){
            //如果成功用函数str_replace将是转化为字符串并返回图片路径
            return str_replace(UPLOADS_PATH,'',$dir.$filename);
        }else{
            //如果失败返回false
            $this->error ="移动文件失败!!";
            return false;
        }

    }

}