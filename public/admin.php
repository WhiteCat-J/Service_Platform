<?php
/**
 * Created by PhpStorm.
 * User: 王瑞霞
 * Date: 2019/5/19
 * Time: 11:16
 * 后台的入口文件
 */
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();