<?php

/**
 * Created by PhpStorm.
 * User: machenike
 * Date: 2017/4/15 0015
 * Time: 22:10
 */
class CategoryModel extends Model
{
    //显示商品分类列表方法
    public  function getlist(){
        //准备SQL
        $sql="select *from category";
        //执行SQL
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return $rows;
    }
}