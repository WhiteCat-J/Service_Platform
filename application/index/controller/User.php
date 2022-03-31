<?php
namespace app\index\controller;
use app\common\controller\Base;
use think\facade\Request;
use app\common\model\User as UserModel;
use think\facade\Session;
class User extends Base
{
    //注册页面
    public function register()
    {
        //检测是否开启注册
        $this->is_reg();

        $this->assign('title','用户注册');
        return $this->view->fetch();
    }
    //处理用户提交的表单信息
    public function insert()
    {
        //exit是终止程序，程序不会往下面进行，你可以用它来排查你是哪一步出错了
        //刚才你并没有提交到你的控制器，因为你的表单是form  你的按钮有一个submit 属性，所以它会直接提交表单 还没有用到你的AJX 就已经提交了
        $returnArray = array();
        if (Request::isAjax()) {

            //首先需要获取数据
            //需要过滤到数据库中不需要保存的数据,例如：确认密码字段
           $data=Request::post();//要验证的数据
            $rule='app\common\validate\User';//自定义的验证规则
            //开始验证
            $res=$this->validate($data,$rule);
            if(true!==$res)
            {
                return ['status'=>-1,'message'=>$res];
            }else{
                if($user=UserModel::create($data))
                {
                    //注册成功之后，实现自动登录
                    $res=UserModel::get($user->id);
                    Session::set('user_id',$res->id);
                    Session::set('user_name',$res->name);
                    $returnArray = [
                        'status' => 1,
                        'message' => '恭喜，注册成功',
                    ];
                }else{
                    $returnArray = [
                        'status' => 0,
                        'message' => '注册失败',
                    ];
                }
            }
        } else{
            $returnArray = [
                'status' => 0,
                'message' => '请求格式不正确',
            ];
        }
        return $returnArray;
    }

    //用户登录
    public function login()
    {
        $this->logined();
        $this->assign('title','用户登录');
        return $this->view->fetch();
    }
    //用户登录验证与查询
    public function loginCheck()
    {
        $returnArray = array();
        if (Request::isAjax()) {

            //首先需要获取数据
            //需要过滤到数据库中不需要保存的数据,例如：确认密码字段
            $data=Request::post();//要验证的数据
            $rule=[
                'email|邮箱'=>'require|email',
                'password|密码'=>'require|alphaNum',
            ];//自定义的验证规则
            //开始验证
            $res=$this->validate($data,$rule);
            if(true!==$res)
            {
                return ['status'=>-1,'message'=>$res];
            }else{
                //执行查询
                $result=UserModel::get(function ($query) use ($data){
                    $query->where('email',$data['email'])
                        ->where('password',sha1($data['password']));
                });
                if(null==$result)
                {
                    $returnArray = [
                        'status' => 0,
                        'message' => '邮箱或者密码不正确，请检查',
                    ];
                }else{
                    //将用户的数据写到session中
                    Session::set('user_id',$result->id);
                    Session::set('user_name',$result->name);
                    $returnArray = [
                        'status' => 1,
                        'message' => '登录成功',
                    ];
                }
            }
        } else{
            $this->error('请求类型错误','login');
        }
        return $returnArray;
    }
    //退出登录
    public function logout()
    {
        //方法一：
        /*Session::delete('user_id');
        Session::delete('user_name');*/
        //方法二：
        Session::clear();
        $this->success('退出登录成功','index/index');
    }
}