<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 11:30
 */

namespace app\admin\common\model;
use think\Model;

class User extends Model
{
    protected $pk='id';
    protected $table='zh_user';
}