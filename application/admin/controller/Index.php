<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 11:13
 */

namespace app\admin\controller;
use app\admin\common\controller\Base;

class Index extends Base
{
    public function index(){
        //验证用户是否登录
        $this->isLogin();
        return $this->redirect('user/userList');
    }
}