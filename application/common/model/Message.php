<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/11/26
 * Time: 13:06
 */
namespace app\common\model;
use think\Model;

class Message extends Model
{
    protected $pk='name';//默认主键
    protected $table='zh_message';//默认数据库表
}