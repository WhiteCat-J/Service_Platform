<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 18:02
 */

namespace app\admin\common\model;
use think\Model;

class Cate extends Model
{
    protected $pk='id';
    protected $table='zh_article_category';
    protected $autoWriteTimestamp=true;//自动时间戳
    protected $createTime='create_time';
    protected $updateTime='update_time';
}