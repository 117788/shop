<?php

namespace app\admin\controller;

use sms\lib\MESSAGEMultiSend;
use sms\lib\message;
use sms\lib\MESSAGEsend;

class Sms extends BaseController
{
    public static function sendMessage(){
        //以下代码在app_config.php文件下提取出来
        $server = 'http://api.mysubmail.com/';
        $message_configs['sign_type'] = 'normal';
        $message_configs['server'] = $server;
        $message_configs['appid'] = '36596';
        $message_configs['appkey'] = '6e67b15ed4d69c47cc8ae91232ca0db9';
        //以上代码在app_config.php文件下提取出来
        //以下代码在message_send_demo.php文件下提取出来
        $submail=new MESSAGEMultiSend($message_configs);
        $contacts=array("18856303065","18056326635");
        $status = array();
        $temp = array();
        foreach($contacts as $contact){
            $submail->SetContent('【NEWX大学生网络文化工作室】你好，感谢选择NEWX大学生网络文化工作室，我们的第一次考核定于***，请提前准备，收到回复！！');
            $temp['to'] = $contact;
            $submail->addMulti($temp);

            //$status[$contact] = $send['status'];
        }
        $send = $submail->multisend();
        dump($send);
        //以上代码在message_send_demo.php文件下提取出来
        //dump($status);
    }

}