<?php

/**
 * Created by PhpStorm.
 *建立商品列表控制器
 */
class GoodsController   extends PaltformController
{
        //建立一个url=index.php?p=Admin&c=Goods&a=index
    //显示商品列表的方法
        public function index(){
            //接收参数
            //处理数据
            //因为分页方法中已经把我所有数据情况显示出来了，所以我只需要调用分页的方法来显示我的所有数据
            //创建一个新对象
            $goodsModel= new GoodsModel();
            //检测显示页面，如果没有传PAGE也就直接显示第一页
            $page= isset($_GET['page'])?$_GET['page']:1;
            //设置在页面上需要显示的数据条数
            $pageSize = 4;
            $rows=$goodsModel->getPage($page,$pageSize);
//        var_dump($rows);exit;
//            var_dump($rows);
            //将分页面的结果集返回到添加页面上
            $this->assign($rows);
            //显示页面
            $this->display('index');
        }
    //商品添加方法
    //需要一个添加视图add.html
    public function add(){
        //因为判断是有什么传送方式传送过来的，因为get是显示页面post是添加数据
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            //接收参数接收要添加的数据的参数
            $data=$_POST;
            $img=$_FILES['logo'];
//            var_dump($img);exit;
            //处理数据
            //因为要使用对象帮我们处理上传文件所以要调用文件模型中的方法来处理并给其设置一个goods路径
            $uploadModel= new UploadModel();
            //调用upload方法并将参数传入进去
            $logo_path=$uploadModel->upload($img,'goods/');
//            var_dump($logo_path);exit;
            //需要判断文件上传是否成功
            if($logo_path === false){
                //不成功就显示添加页面重新开始
                $this->redirect("index.php?p=Admin&c=Goods&a=add",$uploadModel->getError(),3);
            }else{
                //如果成功就将其字转化为到data的logo中
                $data['logo']=$logo_path;
            }
//            exit;
            //调用模型上的方法来保存接收过来的数据
            $goodsModel = new GoodsModel();
            $goodsModel->addsave($data);
//            var_dump($rows);exit;
            //显示页面
            $this->redirect("index.php?p=Admin&c=Goods&a=index");
        }else{
            //此处是通过get方式传送过来的集显示添加页面
            //接收参数
            //处理数据
            //因为要显示商品分类的数据所以要处理商品分类数据显示选择情况
            $categoryModel= new CategoryModel();
           $rows = $categoryModel->getlist();
//            var_dump($rows);
            //将商品分类的结果集返回到添加页面上
            $this->assign('rows',$rows);
            //显示页面
            $this->display('add');
        }

    }
    //建立删除的方法
    public function delete(){
        //接收参数
        $id=$_GET['id'];
        //处理数据
        $goodsModel = new GoodsModel();
        $goodsModel->delete($id);
        //显示页面
        $this->redirect("index.php?p=Admin&c=Goods&a=index");
    }
    //建立修改方法
    public function update(){
        //建立一个url=inex.php?p=Admin&c=Goods&a=update;
        //因为要默认修改显示数据,所以要进行传送方式的判断
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            //接收参数
            $data=$_POST;
            $img=$_FILES['logo'];
            //处理数据
//            var_dump($data);
//            echo '<br/>';
            var_dump($img);
            //先判断图片是否上传成功，如果成功执行upload方法
            if($img['error'] == 0){
                //上传成功后执行
                $uploadModel = new UploadModel();
                $new_img=$uploadModel->upload($img,'goods/');
                //图片保存路径成功后
                if($new_img !==false){
                    //再将新路径保存到data里
                    $data['logo']=$new_img;
                }else{
                    return false;
                }
            }else{
                //没有上传的话就保留原有图片路径，是通过隐藏域传过来的
                $data['logo']=$data['old_logo'];
            }
//            var_dump($data);exit;
            $goodsModel= new GoodsModel();
            //调用模型中方法修改所有数据
            $result =$goodsModel->updatesave($data);
            //显示页面
            if($result === false){
                $this->redirect("index.php?p=Admin&c=Goods&a=update","修改失败",3);
            }
            $this->redirect("index.php?p=Admin&c=Goods&a=index");

        }else{
            //接收参数
            $id=$_GET['id'];
            //处理数据
            //调用商品列表中的显示一条数据的方法根据id来显示
            $goodsModel= new GoodsModel();
            $rows=$goodsModel->updateone($id);
            //因为这里要显示商品分类情况，所以要调用商品分类中的显示方法
            $categoryModel= new CategoryModel();
            $cates=$categoryModel->getlist();
            //分配数据
            //分配商品分类的数据
            $this->assign('cates',$cates);
            //分配获取的结果及显示分类
            $this->assign('rows',$rows);
            //显示页面
            $this->display('update');

        }

    }

}