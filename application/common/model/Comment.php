<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/6
 * Time: 17:42
 */

namespace app\common\model;
use think\Model;

class Comment extends Model
{
    protected $pk='id';//默认主键
    protected $table='zh_user_comments';//默认数据库表

    protected $autoWriteTimestamp=true;//自动时间戳
    protected $createTime='create_time';
    protected $updateTime='update_time';

    //自动完成设置
    protected $auto =[];//无论是新增还是更新都要设置的字段
    //仅仅是新增的时候有效
    protected $insert=['create_time','status'=>1];
    //仅仅是更新的时候有效
    protected $update=['update_time'];
}