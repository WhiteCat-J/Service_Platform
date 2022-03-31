<?php
/**
 * zh_user用户表的用户模型
 */

namespace app\common\model;
use think\Model;

class User extends Model
{
    protected $pk='id';//默认主键
    protected $table='zh_user';//默认数据库表

    protected $autoWriteTimestamp=true;//自动时间戳
    protected $createTime='create_time';
    protected $updateTime='update_time';

    //获取器:意义就是将数据库表中一些有特殊意义的字段自动转换
    //就相当于将status和is_admin的值转换成用户能明白的字段
    public function getStatusAttr($value)
    {
        $status=['1'=>'启用','0'=>'禁用'];
        return $status[$value];
    }
    public function getIsAdminAttr($value)
    {
        $status=['1'=>'管理员','0'=>'注册会员'];
        return $status[$value];
    }
    //修改器
    //就像是保存用户的密码在数据库中能够看待，修改器就可以做到用户在注册的时候保存到数据库中的密码就是加密的
    public function setPasswordAttr($value)
    {
       return sha1($value);
    }

}