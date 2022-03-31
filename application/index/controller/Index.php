<?php
namespace app\index\controller;
use app\common\controller\Base;//导入公共控制器
use app\common\model\ArtCate;
use app\common\model\Article;
use app\common\model\Message;
use app\common\model\Investment;
use app\common\model\Comment;
use app\common\model\Policy;
use think\Db;
use think\facade\Request;
class Index extends Base
{
    //网站首页
    public function index(){
        //园区信息message表
        $map2=[];//将所有的查询条件封装到数组中
        $messageList= Db::table('zh_message')->where($map2)->paginate(36);
        $this->view->assign('messageList',$messageList);

        //招商引资
        $map=[];//将所有的查询条件封装到数组中
        $artList= Db::table('zh_investment')->where($map)
                ->paginate(9);
        $this->view->assign('artList',$artList);

        //政策通道
        $map1=[];//将所有的查询条件封装到数组中
        $policyList= Db::table('zh_policy')->where($map1)
            ->paginate(9);
        $this->view->assign('policyList',$policyList);


        $this->view->assign('title','主页面');
        return $this->view->fetch('index');
    }
    //爬取数据内容详情页
    public function content(){
        //招商引资
        $artId=Request::param('id');
        $art=Investment::get(function ($query)use ($artId){
            $query->where('id','=',$artId);
        });
        if(!is_null($art)){
            $this->view->assign('art',$art);
        }
        $this->view->assign('title','详情页面');
        return $this->view->fetch('content');
    }
    //园区各信息政策
    public function message(){
        //园区各信息政策
        //主键是园区名称name
        $Name=Request::param('name');
        $message=Message::get(function ($query)use ($Name){
            $query->where('name','=',$Name);
        });
        if(!is_null($message)){
            $this->view->assign('message',$message);
        }
        $this->view->assign('title','园区信息');
        return $this->view->fetch('message');
    }
    //政策通道内容
    public function policycontent(){
        //政策通道
        $artId=Request::param('id');
        $policy=Policy::get(function ($query)use ($artId){
            $query->where('id','=',$artId);
        });
        if(!is_null($policy)){
            $this->view->assign('policy',$policy);
        }
        $this->view->assign('title','优惠政策');
        return $this->view->fetch('policycontent');
    }
    //搜索功能
    public function search(){
        //全局查询条件
        $map=[];//将所有的查询条件封装到数组中
        //实现搜索功能
        $keywords=Request::param('keywords');
        if(!empty($keywords)){
            //条件2：
            $map[]=['title','like','%'.$keywords.'%'];
        }
            $this->view->assign('cateName','相关文章');
            $artList= Db::table('zh_investment')
                ->where($map)
                ->paginate(10);

        $this->view->assign('empty','<h3>没有文章</h3>');
        $this->view->assign('artList',$artList);
        return $this->fetch('search');
    }
    //内容首页
    public function trans()
    {
        //全局查询条件
        $map=[];//将所有的查询条件封装到数组中
        //条件1:
        $map[]=['status','=',1];//这里的等于号不能省略
        //实现搜索功能
        $keywords=Request::param('keywords');
        if(!empty($keywords)){
            //条件2：
            $map[]=['title','like','%'.$keywords.'%'];
        }
        //分类信息的显示
        $cateId=Request::param('cate_id');
        //如果存在这个分类id
        if(isset($cateId)){
            //条件3：
            $map[]=['cate_id','=',$cateId];
            $res=ArtCate::get($cateId);
            $artList= Db::table('zh_article')
                ->where($map)
                ->order('create_time','desc')
                ->paginate(2);
            $this->view->assign('cateName',$res->name);
        }else{
            $this->view->assign('cateName','全部文章');
            $artList= Db::table('zh_article')
                ->where($map)
                ->order('create_time','desc')
                ->paginate(2);
        }
        $this->view->assign('empty','<h3>没有文章</h3>');
        $this->view->assign('artList',$artList);
        return $this->fetch('trans',['name'=>'php.cn']);
    }
    //添加文章界面
    public function insert()
    {
        //1.用户必须登录才可以发布文章
        $this->isLogin();
        //2.设置页面标题
        $this->view->assign('title','发布文章');
        //3.获取一下栏目的信息
        $cateList=ArtCate::all();
        if(count($cateList)>0)
        {
            //将查询到的栏目信息赋值给模板
            $this->assign('cateList',$cateList);
        }else{
            $this->error('请先添加栏目','index/index');
        }
        //4.发布界面渲染
        return $this->view->fetch('insert');
    }
    //保存文章
    public function save()
    {
        //判断提交类型
       if(Request::isPost()) {
           //获取用户提交的文章信息
           $data=Request::post();
           $res=$this->validate($data,'app\common\validate\Article');
           if(true!==$res)
           {
               //验证失败
               echo '<script>alert("'.$res.'");location.back()</script>';
           }else
           {
               //验证成功
               //获取上传的图片信息
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
               if(Article::create($data)){
                   $this->success('文章发布成功','index/index');
               }else{
                   $this->error('文章保存失败');
               }
           }
       }else
        {
            $this->error('请求类型错误');

        }
    }
    //详情页面
    public function detail(){
        $artId=Request::param('id');
        $art=Article::get(function ($query)use ($artId){
            $query->where('id','=',$artId)
            ->setInc('pv');
        });
        if(!is_null($art)){
            $this->view->assign('art',$art);
        }
        //添加评论信息
        $this->view->assign('commentList',Comment::all(function ($query) use ($artId){
            $query->where('status',1)
            ->where('article_id',$artId)
            ->order('create_time','desc');
        }));
        $this->view->assign('title','详情页面');
        return $this->view->fetch('detail');
    }
    //收藏
    public function fav()
    {
        if(!Request::isAjax()){
            return ['status'=>-1,'message'=>'请求类型错误'];
        }
        //获取从前端过来的数据
        $data=Request::param();
        //判断用户是否登录
        if(empty($data['session_id'])){
            return ['status'=>-2,'message'=>'登录之后才能收藏！！'];

        }

        //查询条件
        $map[]=['user_id','=',$data['user_id']];
        $map[]=['article_id','=',$data['article_id']];
        $fav=Db::table('zh_user_fav')
            ->where($map)
            ->find();
        if(is_null($fav)){
            Db::table('zh_user_fav')
                ->data([
                    'user_id'=>$data['user_id'],
                    'article_id'=>$data['article_id'],
                ])->insert();
            return ['status'=>1,'message'=>'收藏成功'];
        }else{
            Db::table('zh_user_fav')->where($map)
                ->delete();
            return ['status'=>0,'message'=>'已取消'];
        }
    }
    //点赞
    public function ok()
    {
        if(!Request::isAjax()){
            return ['status'=>-1,'message'=>'请求类型错误'];
        }
        //获取从前端过来的数据
        $data=Request::param();
        //判断用户是否登录
        if(empty($data['session_id'])){
            return ['status'=>-2,'message'=>'登录之后才能点赞！！'];

        }

        //查询条件
        $map[]=['user_id','=',$data['user_id']];
        $map[]=['article_id','=',$data['article_id']];
        $ok=Db::table('zh_user_like')
            ->where($map)
            ->find();
        if(is_null($ok)){
            Db::table('zh_user_like')
                ->data([
                    'user_id'=>$data['user_id'],
                    'article_id'=>$data['article_id'],
                ])->insert();
            return ['status'=>1,'message'=>'点赞成功'];
        }else{
            Db::table('zh_user_like')->where($map)
                ->delete();
            return ['status'=>0,'message'=>'已取消'];
        }
    }

    public function insertComment(){
        if(Request::isAjax()){
            //1.获取到评论
            $data=Request::param();
            //halt($data);
            //2.将用户留言存到表中
            if(Comment::create($data,true)){
                return ['status'=>1,'message'=>'评论发表成功'];
             }
            return ['status'=>0,'message'=>'评论发表失败'];
        }

    }
    //修改密码界面
    public function revise(){
        $this->assign('title','修改信息');
        return $this->view->fetch();
    }
}
