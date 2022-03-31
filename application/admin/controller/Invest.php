<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/11/28
 * Time: 15:00
 */

namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Invest as InvestModel;
use think\facade\Request;
use think\facade\Session;

class Invest extends Base
{
    //文章管理的首页
    public function index(){
        //1.检查用户是否登录
        $this->isLogin();
        //2.登录成功之后，就直接跳转到文章管理界面
        return $this->redirect('investList');
    }
    //文章列表
    public function investList(){
        //1.检查用户是否登录f
        $this->isLogin();
        //2.获取当前用户的id和用户级别
        $userId=Session::get('user_id');
        $isAdmin=Session::get('is_admin');
        //3.获取当前用户发表的文章
        $investList= InvestModel::paginate(15);
        //4.如果是超级管理员能够获得全部的文章
        if($isAdmin == 1)
        {
            echo "11";
            $investList= InvestModel::paginate(15);
        }
        //5.设置模板变量
        $this->assign('title','文章管理');
        $this->assign('empty','<span style="color: red">没有文章</span>');
        $this->assign('investList',$investList);
        //4.渲染分类显示的模板
        return $this->fetch('investList');
    }
    //执行文章删除操作
    public function doDelete(){
        //1、获取文章的id
        $artId=Request::param('id');
        //2.执行删除操作并判断是否成功
        if(InvestModel::destroy($artId)){
            $this->success('删除成功');
        }
        //3.失败
        $this->error('删除失败');
    }
}