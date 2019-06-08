<?php

namespace app\admin\model;
use think\Model;
use think\model\concern\SoftDelete;
class BaseModel extends Model
{
    use SoftDelete;
    public static function addData($data){
        $result = self::create($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public static function editData($data, $id){
        $result = self::update($data,['id' => $id]);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public static function delData($id){
        $result = self::destroy($id);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}