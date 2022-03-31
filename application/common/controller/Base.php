<?php
/**
 * 基础控制器
 * 必须继承自think\Controller.php
 */

namespace app\common\controller;
use think\App;
use think\Controller;
use think\facade\Session;
use app\admin\common\model\Site;
use app\common\model\ArtCate;
use app\common\model\Article;
use think\facade\Request;

class Base extends Controller
{
    //初始化主要是用来创建常量和公共方法
    //在所有的方法之前被调用
    protected function initialize()
    {
        //显示分类导航
        $this->showNav();
        //检测网站是否关闭
        $this->is_open();
        //获取右侧数据
        $this->getHotArt();
    }
    //防止重复登录
    public function logined()
    {
        if(Session::has('user_id'))
        {
            $this->error('客官，你已经登陆了','index/index');
        }
    }
    //检查是否已经登录，放在需要登录操作的方法的最前面，例如：发布文章
    public function isLogin()
    {
        if(!Session::has('user_id'))
        {
            $this->error('客官，您是不是已经忘记登录了···','user/login');
        }
    }
    //显示分类导航
    protected function showNav()
    {
        //1.查询分类表获取到所有的分类信息
        $cateList=ArtCate::all(function ($query){
            $query->where('status',1)
                ->order('sort','asc');
        });
        //2.将分类信息赋值给模板、nav.html
        $this->view->assign('cateList',$cateList);
    }

    //检测站点是否关闭
    public function is_open(){
        //1.获取当前站点的状态
        $isOpen=Site::where('status',1)->value('is_open');

        //2.如果站点已经被关闭了，那么我们只允许关闭前台的，后台不能关闭
        if($isOpen == 0 && Request::module()=='index'){
            //关闭网站

            $info=<<< 'INFO'
<body style="background-color: #333">
<h1 style="color: #eee;text-align: center;margin: 200px">站点维护中......</h1>
</body>
INFO;

            exit($info);
        }
    }
    //检测注册是否关闭
    public function is_reg(){
        //1.检查注册状态
        $isReg=Site::where('status',1)->value('is_reg');
        //如果说已经关闭，直接跳到首页
        if($isReg==0){
            $this->error('注册关闭','index/index');

        }
    }
    //热门部分根据阅读量PV来获取内容
    public function getHotArt()
    {
        $hotArtList=Article::where('status',1)->order('pv','desc')->limit(12)->select();
        $this->view->assign('hotArtList',$hotArtList);
    }
}