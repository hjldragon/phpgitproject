<?php

/**
 * Created by PhpStorm.
 * 商品列表模型
 */
class GoodsModel  extends Model
{
//    //获取商品所有数据方法
//        public function getAll(){
//            //准备查询商品所有数据的sql
//            $sql="select *from goods";
//            //执行SQL
//            $rows = $this->db->fetchAll($sql);
//            //因为$rows数组里面都个status;因为要显示出状态所以要将status分开,所有要用个遍历将其分开显示状态
//            foreach($rows as &$row){
//                //图片的显示为0和1
//                $row['is_best']=($row['status'] & 1)> 0 ? 1 :0;
//                $row['is_new']=($row['status'] & 2)> 0 ? 1 :0;
//                $row['is_hot']=($row['status'] & 4)> 0 ? 1 :0;
//
//            }
//            //返回结果集
//            return $rows;
//        }
    //创建一个保存上传商品的方法
    public function  addsave($data){

        //因为data里吗的status诗歌数组所以要先处理status的状态
        //将其合并为一个变量
        //现将status默认为0
        $status= 0;
        if(isset($data['status'])){
            foreach($data['status'] as $v){
                $status =$status | $v;
            }
        }
        //将is_on_sale设置为默认为不上架
        $is_on_sale=$data['is_on_sale']==1?1:0;
//        var_dump($data);exit;
        //并将上传时间处理
        $add_time=time();
        //准备保存到数据库的SQL语句
        $sql = "insert into goods(`name`,sn,category_id,shop_price,market_price,logo,thumb_logo,intro,num,status,is_on_sale,add_time)
              values ('{$data['name']}','{$data['sn']}',{$data['category_id']},{$data['shop_price']},
            {$data['market_price']},'{$data['logo']}','','{$data['intro']}',{$data['num']},$status,$is_on_sale,{$add_time})";
        //执行SQL语句
        $result=$this->db->query($sql);
//        var_dump($result);exit;
        //返回结果
        return $result;
    }
    //保存删除方法
    public function delete($id){
        //根据需要删除的id号来删除数据所准备SQL
        $sql="delete from goods where id='{$id}'";
        //执行sql
        $result=$this->db->query($sql);
        //返回结果
        return $result;
    }
    //建立一个显示一条默认数据的方法
    public  function updateone($id){
        //通过ID来查询数据准备SQL
        $sql="select *from goods where id={$id}";
        //执行sql
        $row=$this->db->fetchRow($sql);
        //返回结果因为此处显示status是1个状态，所以要通过位运算&将其status分为三个状态来表示
        $row['is_best']=($row['status'] & 1)>0 ? 1 : 0;
        $row['is_new']=($row['status'] & 2)>0 ? 1 : 0;
        $row['is_hot']=($row['status'] & 4)>0 ? 1 : 0;
        //返回结果
        return $row;
    }
    //建立保存修改的方法
    public function updatesave($data){
//        var_dump($data);exit;
        //因为传送过的status显示的是一个数字的是三个状态所以要通过位运算|将其合并
        $status=0;//设置一个status的默认值，默认为0
        //先检测传送过来的status是否有存在有存在就执行位运算合并
        if(isset($data['status'])) {
            foreach ($data['status'] as $v) {
                $status = $status | $v;
            }
        }
        //根据id修改每条数据准备sql
        $sql="update goods set name='{$data['name']}',sn='{$data['sn']}',category_id={$data['category_id']},shop_price='{$data['shop_price']}',
        market_price='{$data['market_price']}',logo='{$data['logo']}',num='{$data['num']}',is_on_sale='{$data['is_on_sale']}',
        status='{$status}',intro='{$data['intro']}' where id={$data['id']}";
        //执行sql
        $result=$this->db->query($sql);
//        var_dump($result);exit;
        //返回结果
        return $result;
    }
    //分页方法
    public  function getPage($page,$pageSize){
        //获取总条数
        //根据count(*)来获取商品的总条数
        $sql_count="select count(*) from goods limit 1";
        //执行SQL
        $count=$this->db->fetchColumn($sql_count);
        //总页数为总条数除以每页页数并向上取整
        $totalPage= ceil($count/$pageSize);
        //判断一个页码数是否大于总页数，如果大于就显示总页数
        $page>$totalPage ? $totalPage : $page;
        //算出开始记录页数,页数-1*每页显示条数
        $start=($page-1)*$pageSize;
        //做个判断如果开始页数小于就返回开始记录页数，因为不能为负数
        $start = $start < 0 ?0 :$start;
        //从数据库获取这页我所要显示的所有数据，准备的sql
        $sql_rows="select *from goods order by id asc limit {$start},{$pageSize}";
        //执行SQL
        $rows=$this->db->fetchAll($sql_rows);
        //因为返回的结果集$rows数组里面都个status;因为要显示出状态所以要将status分开,所有要用个遍历将其分开显示状态
        foreach($rows as &$row){
            //图片的显示为0和1
            $row['is_best']=($row['status'] & 1)> 0 ? 1 :0;
            $row['is_new']=($row['status'] & 2)> 0 ? 1 :0;
            $row['is_hot']=($row['status'] & 4)> 0 ? 1 :0;

        }
        //返回所有分页的数据
        return ['rows'=>$rows,'count'=>$count,'pageSize'=>$pageSize,'page'=>$page,
            'totalPage'=>$totalPage];
    }
}