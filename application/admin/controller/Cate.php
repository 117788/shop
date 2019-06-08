<?php

namespace app\admin\controller;
use app\admin\model\Cate as CateModel;
use catetree\Catetree;
use app\admin\model\Article as ArticleModel;
class Cate extends BaseController
{
    public function lst(){
        $cateTree = new Catetree();
        $obj = new CateModel();
        if (request()->isPost()){
            $data = input('post.');
            $cateTree->cateSort($data['sort'], $obj);
            $this->success('排序成功','lst');
        }
        $cateRes = CateModel::order('sort asc')->select();

        $cateRes = $cateTree->catetree($cateRes);
        $this->assign('cateRes',$cateRes);
        return view('list');
    }
    public function add(){
        if(request()->isPost()){
            $data = input('post.');
            $result = CateModel::addData($data);
            if($result){
                $this->success('添加分类成功','lst');
            }else{
                $this->error('添加分类失败');
            }
            return;
        }
        $cateRes = CateModel::select();
        $cateTree = new Catetree();
        $cateRes = $cateTree->catetree($cateRes);
        $this->assign('cateRes',$cateRes);
        return view();
    }
    public function edit($id){
        if(request()->isPost()){
            $data = input('post.');
            $result = CateModel::editData($data, $id);
            if($result){
                $this->success('修改分类成功','lst');
            }else{
                $this->error('修改分类失败');
            }
            return;
        }
        $cates = CateModel::get($id);
        $cateRes = CateModel::select();
        $cateTree = new Catetree();
        $cateRes = $cateTree->catetree($cateRes);
        $this->assign([
            'cates'=>$cates,
            'cateRes'=>$cateRes
        ]);
        return view();
    }
    public function del($id){
        $cate = db('cate');
        $cateTree  = new Catetree();
        $sonIds = $cateTree->childrenids($id, $cate);
        $sonIds[] = intval($id);
        //删除分类前判断该分类下的文章和文章相关缩略图
        $article=new ArticleModel();
        foreach ($sonIds as $k => $v) {
            $artRes=$article->field('id,thumb')->where(array('cate_id'=>$v))->select();
            foreach ($artRes as $k1 => $v1) {
                $article->delData($v1['id']);
            }
        }
        $del = CateModel::delData($sonIds);
        if($del){
            $this->success('删除分类成功','lst');
        }else{
            $this->error('删除分类失败');
        }
    }


}