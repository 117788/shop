<?php

namespace app\admin\controller;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Cate as CateModel;
use catetree\Catetree;

class Article extends BaseController
{
    public function lst(){
        $artRes = ArticleModel::order('a.create_time desc')
            ->field('a.*,c.cate_name')
            ->alias('a')
            ->join('cate c','c.id=a.cate_id')
            ->paginate(5);
        $this->assign('artRes',$artRes);
        return view('list');
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            if(stripos($data['link_url'],'http://') === false){
                $data['link_url'] = 'http://'.$data['link_url'];
            }
            if($_FILES['thumb']['tmp_name']){
                $data['thumb'] = $this->upload('thumb', 'article');
            }
            $result = ArticleModel::addData($data);
            if($result){
                $this->success('添加文章成功','lst');
            }else{
                $this->error('添加文章失败');
            }

            return;
        }

        $ArticleRes = CateModel::select();
        $cateTree= new Catetree();
        $cateRes = $cateTree->catetree($ArticleRes);
        $this->assign('cateRes',$cateRes);
        return view();
    }
    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            if(stripos($data['link_url'],'http://') === false){
                $data['link_url'] = 'http://'.$data['link_url'];
            }
            if($_FILES['thumb']['tmp_name']){
                $oldBrands = db('article')->field('thumb')->find($id);
                $oldBrandsImg = IMG_UPLOADS.$oldBrands['thumb'];
                if(file_exists($oldBrandsImg)){
                    @unlink($oldBrandsImg);
                }
                $data['thumb'] = $this->upload('thumb', 'article');
            }
            $result = ArticleModel::editData($data, $id);
            if($result){
                $this->success('修改文章成功','lst');
            }else{
                $this->error('修改文章失败');
            }
            return;
        }
        $Articles = ArticleModel::get($id);
        $cateRes = CateModel::select();
        $cateTree = new Catetree();
        $cateRes = $cateTree->catetree($cateRes);
        $this->assign([
            'arts'=>$Articles,
            'cateRes'=>$cateRes
        ]);
        return view();
    }
    public function del($id){
        $del = ArticleModel::delData($id);
        if($del){
            $this->success('删除文章成功','lst');
        }else{
            $this->error('删除文章失败');
        }
    }

    //ueditor图片管理
    public function imglist(){
        $_files=my_scandir();
        $files=array();
        foreach ($_files as $k => $v) {
            if(is_array($v)){
                foreach ($v as $k1 => $v1) {
                    $v1=str_replace(UEDITOR, HTTP_UEDITOR, $v1);
                    $files[]=$v1;
                }
            }else{
                $v=str_replace(UEDITOR, HTTP_UEDITOR, $v);
                $files[]=$v;
            }
        }
        // dump($files); die;
        $this->assign([
            'imgRes'=>$files,
        ]);
        return view();
    }

    public function delimg(){
        $imgsrc=input('imgsrc');
        $imgsrc=DEL_UEDITOR.$imgsrc;
        if(file_exists($imgsrc)){
            if(@unlink($imgsrc)){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }
}