<?php
namespace app\index\controller;
use think\Controller;

class Goods extends Controller {
    public function goods() {
        $bookid = input('id');
        $data = db('books')->where('bookid',$bookid)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }
}