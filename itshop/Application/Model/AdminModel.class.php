<?php

/**
 * Created by PhpStorm.
 * 用来验证控制用户信息的模型
 */
class AdminModel extends Model
{
    //验证用户信息是否正确的check方法
    public function check($username,$password){
        //准备SQL
        //因为数据库的密码是加密的所以要用到md5对传进行来验证的密码进行个加密再去验证
        $password = md5($password);
        //准备SQL及在数据库里查询用户名和密码信息相符合的的
        $sql="select *from admin where username='{$username}' and password='{$password}' limit 1";
//        var_dump($sql);exit;
        //执行SQL调用db类中的方法
        $result=$this->db->fetchRow($sql);
        //返回结果前进行个判读，如果没有输入错误就显示错误
        if(empty($result)){
            $this->error="用户名和密码错误!!";
            return false;
        }
        //如果正确返回结果
//        var_dump($result);exit;
        return $result;

    }
    //建立保存登录情况方法并用SQL语句来验证id和password
    public function checkBycookie($id,$password){
        //准备验证SQL语句根据ID查询数据库信息
        $sql="select * from admin where id='{$id}' limit 1";
        //执行SQL
        $rows=$this->db->fetchRow($sql);
        //返回结果
        //判断其返回的结果集
        if(empty($rows)){
            return false;
        }else{
        //因为根据id查询到数据库对应的用户信息中要将password取出来进行对比。所有要对现输入的加密才能进行对比
            $password_login=$rows['password'];
            $password_login=md5($password_login);
            //判断输入密码和数据库密码是否相符合
            if($password == $password_login){
                //密码符合就直接返回结果
                return $rows;
            }else{
                return false;
            }

        }
    }
}