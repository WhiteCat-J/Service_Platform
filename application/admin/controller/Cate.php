<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 18:04
 */

namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Cate as CateModel;
use think\facade\Request;
use think\facade\Session;

class Cate extends Base
{
    //分类管理的首页
    public function index(){
        //1.检查用户是否登录
        $this->isLogin();
        //2.登录成功之后，就直接跳转到分类管理界面
        return $this->redirect('cateList');
    }
    //分类列表
    public function cateList(){
        //1.检查用户是否登录f
        $this->isLogin();
        //2.获取所有的分类
        $cateList=CateModel::all();
        //3.设置模板变量
        $this->assign('title','分类管理');
        $this->assign('empty','<span style="color: red">没有分类</span>');
        $this->assign('cateList',$cateList);
        //4.渲染分类显示的模板
        return $this->fetch('cateList');
    }
    //渲染编辑分类的界面
    public function cateEdit(){
        //1.获取到分类的id
       $cateId= Request::param('id');
       //2.根据组件查询要更新分类的信息
        $cateInfo=CateModel::where('id',$cateId)->find();
        //3.设置模板变量
        $this->assign('title','编辑分类');
        $this->assign('cateInfo',$cateInfo);
        //渲染模板
        return $this->fetch('cateedit');
    }
    //执行编辑操作
    public function doEdit(){
        //1.获取用户提交的信息,$data是个数组
        $data=Request::param();
        //2.取出主键
        $id=$data['id'];
        //4删除主键id
        unset($data['id']);
        //5.执行更新操作
        if(CateModel::where('id',$id)->data($data)->update()){
            return $this->success('更新成功','cateList');
        }
        //6.更新失败
        $this->error('没有更新或者更新失败');
    }
    //删除操作
    public function doDelete(){
        //1.获取要删除用户的id
        $id= Request::param('id');
        //2.执行删除操作
        if(CateModel::where('id',$id)->delete()){
            return $this->success('删除成功','cateList');
        }
        //3.删除失败
        $this->error('删除失败');
    }
    //渲染添加分类界面
    public function cateAdd(){
        return $this->fetch('cateadd',['title'=>'添加分类']);
    }
    //添加分类
    public function doAdd(){
        //1.获取到要添加的分类信息
        $data=Request::param();
        //2.执行新增判断是否成功
        if(CateModel::create($data)){
            $this->success('添加成功','catelist');
        }
        //3.失败
        $this->error('添加失败');
    }
}