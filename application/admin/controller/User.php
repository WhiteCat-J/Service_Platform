<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 11:37
 */

namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;
class User extends Base
{
    //渲染登录界面
    public function login(){
        $this->view->assign('title','管理员登录');
        return $this->view->fetch('login');
    }
    //验证后台登录
    public function checkLogin(){
        $data=Request::param();
        //查询条件
        $map[]=['email','=',$data['email']];
        $map[]=['password','=',sha1($data['password'])];
        $result=UserModel::where($map)->find();
        if($result){
            Session::set('admin_id',$result['id']);
            Session::set('admin_name',$result['name']);
            Session::set('admin_level',$result['is_admin']);
            $this->success('登陆成功','admin/user/userList');
        }
     $this->error('登录失败');
    }
    //退出登录
    public function logout(){
        //1.清除一下当前的session
        Session::clear();
        //2.退出登录并跳转到登录页面
        $this->success('退出成功','admin/user/login');
    }
    //用户列表：
    public function userList(){
        //1.获取当前用户的id和级别is_admin
        $data['admin_id']=Session::get('admin_id');
        $data['admin_level']=Session::get('admin_level');
        //2.获取当前用户的信息
        $userList=UserModel::where('id',$data['admin_id'])->select();
        //3.如果是超级管理员，获取到全部信息
        if($data['admin_level']==1)
        {
            $userList=UserModel::select();
        }
        //4.模板赋值
        $this->view->assign('title','用户管理');
        $this->view->assign('empty','<span style="color:red">没有任何数据</span>');
        $this->view->assign('userList',$userList);
        //5.渲染出用户列表的模板
        return $this->view->fetch('userList');
    }
    //渲染用户的编辑界面
    public function userEdit(){
        //1.获取要更新的用户的主键
        $userId=Request::param('id');
        //2.根据主键进行查询
        $userInfo=UserModel::where('id',$userId)->find();
        //3.设计编辑界面的模板变量
        $this->assign('title','编辑用户');
        $this->assign('userInfo',$userInfo);
        //渲染模板
        return $this->fetch('userEdit');
    }
    //执行用户修改信息的保存
    public function doEdit(){
        //1.获取用户提交的信息,$data是个数组
        $data=Request::param();
        //2.取出主键
        $id=$data['id'];
        //3.将用户密码加密后再保存回去
        $data['password']=sha1( $data['password']);
        //4删除主键id
        unset($data['id']);
        //5.执行更新操作
        if(UserModel::where('id',$id)->data($data)->update()){
            return $this->success('更新成功','userList');
        }
        //6.更新失败
        $this->error('没有更新或者更新失败');
    }
    //执行用户的删除操作
    public function doDelete(){
        //1.获取要删除用户的id
       $id= Request::param('id');
       //2.执行删除操作
        if(UserModel::where('id',$id)->delete()){
            return $this->success('删除成功','userList');
        }
        //3.删除失败
        $this->error('删除失败');
    }
}