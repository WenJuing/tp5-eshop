<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\Model\Book as BookModel;
use think\Request;
use think\Session;
class Book extends Controller {
    public function add() {
        if(Session::has('nickname')) {
            if(request()->isPost()) {    //如果收到信息执行下面语句
                $books = db('books');
                $request = Request::instance();     //生成一个可以收集信息的对象
                $data = $request->post();      //获取post上传的内容，除了图片，以数组（key为name）形式存储
                $file = request()->file('picture');     //获取文件
                if($file) {     //如果文件获取成功执行下面语句
                    // 移动到服务器的上传目录 并且使用uniqid规则。ROOT_PATH的值：D:\phpstudy_pro\WWW\tp5\                        
                    $info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads');     //DS的值为当前系统分隔符，即\
                    if($info) $data['picture'] = $info->getFilename();  //为$data添加图片名字信息
                    else echo $file->getError();
                }
                $result = $books->insertGetId($data);  //插入图书信息到数据库，insertGetId方法新增数据并返回主键值
                if($result) $this->success('添加成功！',url('Book/add'));
                else $this->error('添加失败！');
            }else{
                return $this->fetch();
            }
        }else{
            $this->error("这是管理页面，请先登录！",url('Login/login'));
        }
    }
    public function all() {
        $data = db('books')->paginate(4);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function del() {
        $bookid = input('id');
        $book = BookModel::get($bookid);
        if($book) {
            $book->delete();
            return $this->success("删除图书《".$book['bookname']."》成功！",url('book/all'));
        }else{
            return $this->error("删除的图书不存在！",url('book/all'));
        }

    }
}