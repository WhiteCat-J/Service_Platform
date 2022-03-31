<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/7/11
 * Time: 13:36
 */

namespace app\admin\common\model;
use think\Model;

class Site extends Model
{
    protected $pk='id';
    protected $table='zh_site';
    protected $autoWriteTimestamp=true;//自动时间戳
    protected $createTime='create_time';
    protected $updateTime='update_time';
}