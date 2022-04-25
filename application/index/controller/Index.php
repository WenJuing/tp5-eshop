<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        $book = db('books');    //将books表的信息放入变量$book中
        $data = $book->order('bookid desc')->limit(5)->select();    //select()查询，得到一个二维数组
        $this->assign('data',$data);    //assign(模板取值的时候所使用的变量名,要传递的值)
        return $this->fetch();
    }
    public function python()
    {
        $path = APP_PATH."python/test.py";
        $content = shell_exec($path." 3 4");
        echo $content;
        echo "<br>";
    }
    public function all()
    {
        $book = db('books');    //将books表的信息放入变量$book中
        $data = $book->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
}
