<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/9/9
 * Time: 10:45
 */

namespace app\common\model;
use think\Model;

class Policy extends Model
{
    protected $pk='id';//默认主键
    protected $table='zh_policy';//默认数据库表
}