<?php

namespace app\admin\validate;
use think\Validate;

class Cate extends Validate
{
    protected $rule = [
        'cate_name' => 'require|unique:cate'
    ];

    protected $message = [
        'cate_name.require' => '必须填写分类名称',
        'cate_name.unique' => '分类名称不可重复'
    ];

}