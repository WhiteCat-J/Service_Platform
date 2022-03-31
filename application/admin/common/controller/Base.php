<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 11:26
 * 后台公共控制器
 */

namespace app\admin\common\controller;
use think\Controller;
use think\facade\Session;
class Base extends Controller
{
    //初始化
    protected function initialize()
    {

    }
    //检测用户是否是否登录
    public function isLogin()
    {
        if(!Session::has('admin_id'))
        {
            $this->error('请先登录','admin/user/login');
        }
    }
}