<?php

namespace app\admin\controller;
use app\admin\model\Brand as BrandModel;
class Brand extends BaseController
{
    public function lst(){
        $brand = BrandModel::order('create_time desc')->paginate(5);
        $this->assign('brandRes',$brand);
        return view('list');
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            if(stripos($data['brand_url'],'http://') === false){
                $data['brand_url'] = 'http://'.$data['brand_url'];
            }
            if($_FILES['brand_img']['tmp_name']){
                $data['brand_img'] = $this->upload('brand_img', 'brand');
            }
            $result = BrandModel::addData($data);
            if($result){
                $this->success('添加品牌成功','lst');
            }else{
                $this->error('添加品牌失败');
            }
            return;
        }
        return view();
    }
    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            if(stripos($data['brand_url'],'http://') === false){
                $data['brand_url'] = 'http://'.$data['brand_url'];
            }
            if($_FILES['brand_img']['tmp_name']){
                $oldBrands = db('brand')->field('brand_img')->find($id);
                $oldBrandsImg = IMG_UPLOADS.$oldBrands['brand_img'];
                if(file_exists($oldBrandsImg)){
                    @unlink($oldBrandsImg);
                }
                $data['brand_img'] = $this->upload('brand_img', 'brand');
            }
            $result = BrandModel::editData($data, $id);
            if($result){
                $this->success('修改品牌成功','lst');
            }else{
                $this->error('修改品牌失败');
            }
            return;
        }
        $brands = BrandModel::get($id);
        $this->assign('brands',$brands);
        return view();
    }
    public function del($id){
        $del = BrandModel::delData($id);
        if($del){
            $this->success('删除品牌成功','lst');
        }else{
            $this->error('删除品牌失败');
        }
    }


}