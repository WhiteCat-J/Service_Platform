<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 18:04
 */

namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use think\facade\Request;
use think\facade\Session;
use app\admin\common\model\Cate;
class Article extends Base
{
    //文章管理的首页
    public function index(){
        //1.检查用户是否登录
        $this->isLogin();
        //2.登录成功之后，就直接跳转到文章管理界面
        return $this->redirect('artList');
    }
    //文章列表
    public function artList(){
        //1.检查用户是否登录f
        $this->isLogin();
        //2.获取当前用户的id和用户级别
        $userId=Session::get('user_id');
        $isAdmin=Session::get('is_admin');
        //3.获取当前用户发表的文章
       $artList= ArtModel::paginate(8);
        //4.如果是超级管理员能够获得全部的文章
        if($isAdmin == 1)
        {
            echo "11";
            $artList= ArtModel::order('create_time','desc')->paginate(5);
        }
        //5.设置模板变量
        $this->assign('title','文章管理');
        $this->assign('empty','<span style="color: red">没有文章</span>');
        $this->assign('artList',$artList);
        //4.渲染分类显示的模板
        return $this->fetch('artList');
    }
    //渲染编辑文章的界面
    public function artEdit(){
        //1.获取到文章的id
        $artId= Request::param('id');
        //2.根据组件查询要更新分类的信息
        $artInfo=ArtModel::where('id',$artId)->find();
        //3.查询分类
        $cateList=Cate::all();
        //3.设置模板变量
        $this->assign('title','编辑文章');
        $this->assign('cateList',$cateList);
        $this->assign('artInfo',$artInfo);
        //渲染模板
        return $this->fetch('artedit');
    }
    //处理文章的编辑操作
    public function doEdit(){
        //1、获取用户提交的数据
        $data=Request::param();
        //2.获取上传的图片信息
        $file=Request::file('title_img');
        //文件信息验证，验证成功后再上传到服务器上的指定目录,以public为起始目录
        $info=$file->validate([
            'size'=>1000000,
            'ext'=>'jpg,png,gif,jpeg',
        ])->move('uploads/');
        if($info){
            $data['title_img'] =$info->getSavename();

        }else{
            $this->error($file->getError());
        }
        //将数据写入数据库的表中
        if(ArtModel::update($data)){
            $this->success('文章更新成功','artList');
        }else{
            $this->error('文章更新失败');
        }

    }
    //执行文章删除操作
    public function doDelete(){
        //1、获取文章的id
        $artId=Request::param('id');
        //2.执行删除操作并判断是否成功
        if(ArtModel::destroy($artId)){
            $this->success('删除成功');
        }
        //3.失败
        $this->error('删除失败');
    }
}