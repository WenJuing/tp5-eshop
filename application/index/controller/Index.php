<?php
namespace app\index\controller;
use think\Controller;
use think\db;
use app\index\model\Books as book;

class Index extends Controller
{
    public function index()
    {
        // 新书发售
        $book = db('books');
        $new = $book->order('pubdate desc')->limit(5)->select();
        $this->assign('new',$new);
        // 热卖图书
        $res = db::query('select bookid,sum(booknum) from orders group by bookid order by sum(booknum) desc');
        for ($i = 0; $i < 5; $i++) {
            $hot[$i] = book::get($res[$i]['bookid']);
            $hot[$i]['sell'] = $res[$i]['sum(booknum)'];  // 增加销量属性
            // 推荐图书
            $hot[$i]['index'] = round(($res[0]['sum(booknum)']*100 + $hot[$i]['hot']) / $hot[$i]['price'], 2);
    }
        $this->assign('hot',$hot);
        $rec = $hot;
        $index = array_column($hot, 'index');  // 获取index字段的所有值
        array_multisort($index, SORT_DESC, $rec);  // 将数组$rec按$index值降序排序
        $this->assign('rec',$rec);
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
