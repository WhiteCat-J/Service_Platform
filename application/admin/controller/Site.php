<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 11:37
 */

namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Site as SiteModel;
use think\facade\Request;
use think\facade\Session;
class Site extends Base
{
    //1.站点管理首页
    public function index(){
        //1,获取一下站点信息
        $siteInfo=SiteModel::get(['status'=>1]);
        //2.模板赋值
        $this->assign('siteInfo',$siteInfo);
        //3.渲染模板
        return $this->fetch('index');
    }
    //保存站点的修改信息
    public function save(){
        //1,获取数据
        $data=Request::param();
        //2.更新
        if(SiteModel::update($data)){
            $this->success('更新成功','index');
        }
        //失败
        $this->error('更新失败','index');
    }
}