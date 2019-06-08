<?php

namespace app\admin\controller;
use think\Controller;

class BaseController extends Controller
{
    public function upload($name, $path){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($name);
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move( '../public/static/uploads/'.$path);
        if($info){
            // 成功上传后 获取上传信息
            // 返回 20160820/42a79759f284b767dfcb2a0197904287.jpg
            return  $path.'/'.$info->getSaveName();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

}