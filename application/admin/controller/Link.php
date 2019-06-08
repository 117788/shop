<?php
namespace app\admin\controller;
use app\admin\model\Link as LinkModel;

class Link extends BaseController
{
    public function lst()
    {
    	$linkRes=LinkModel::order('id DESC')->paginate(6);
    	$this->assign([
    		'linkRes'=>$linkRes,
    		]);
        return view('list');
    }

    public function add()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		// $data['link_url'];  http://
    		if($data['link_url'] && stripos($data['link_url'],'http://') === false){
    			$data['link_url']='http://'.$data['link_url'];
    		}
    		//处理图片上传
            if($_FILES['logo']['tmp_name']){
                $data['logo'] = $this->upload('logo', 'link');
            }
    		$add=LinkModel::addData($data);
    		if($add){
    			$this->success('添加链接成功！','lst');
    		}else{
    			$this->error('添加链接失败！');
    		}
    		return;
    	}
        return view();
    }

    public function edit()
    {
    	if(request()->isPost()){
    		$data=input('post.');
    		// $data['link_url'];  http://
    		if($data['link_url'] && stripos($data['link_url'],'http://') === false){
    			$data['link_url']='http://'.$data['link_url'];
    		}
    		//处理图片上传
    		if($_FILES['logo']['tmp_name']){
    			$oldlinks=db('link')->field('logo')->find($data['id']);
    			$oldlinkImg=IMG_UPLOADS.$oldlinks['logo'];
    			if(file_exists($oldlinkImg)){
    				@unlink($oldlinkImg);
    			}
                $data['logo'] = $this->upload('logo', 'link');
    		}
    		$save= LinkModel::editData($data, $data['id']);
    		if($save !== false){
    			$this->success('修改链接成功！','lst');
    		}else{
    			$this->error('修改链接失败！');
    		}
    		return;
    	}
    	$id=input('id');
    	$links=db('link')->find($id);
    	$this->assign([
    		'links'=>$links,
    		]);
        return view();
    }

    public function del($id)
    {
    	$del=LinkModel::delData($id);
    	if($del){
			$this->success('删除链接成功！','lst');
		}else{
			$this->error('删除链接失败！');
		}
    }





}