<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        $book = db('books');    //将books表的信息放入变量$book中
        $data = $book->order('bookid desc')->limit(4)->select();    //select()查询，得到一个二维数组
        $this->assign('data',$data);    //assign(模板取值的时候所使用的变量名,要传递的值)
        return $this->fetch();
    }
}
