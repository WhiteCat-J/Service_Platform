<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/6
 * Time: 17:49
 */

namespace app\common\validate;
use think\Validate;

class ArtCate extends Validate
{
    protected $rule=[
        'name|标题'=>'require|length:3,20|chsAlpha',
    ];
}