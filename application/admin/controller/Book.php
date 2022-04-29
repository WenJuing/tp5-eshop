<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\Model\Book as BookModel;
use app\admin\Model\Tempbook as tempbook;
use app\admin\Model\Order as order;
use think\Request;
use think\Session;
use think\Db;

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
                $data['hot'] = 0;
                $result = $books->insertGetId($data);  //插入图书信息到数据库，insertGetId方法新增数据并返回主键值
                if($result) $this->success('添加成功！',url('Book/add'));
                else $this->error('添加失败！');
            }else{
                $tempbook = db('tempbooks')->paginate(10);
                $this->assign('tempbook', $tempbook);
                return $this->fetch();
            }
        }else{
            $this->error("这是管理页面，请先登录！",url('Login/login'));
        }
    }
    // 运行爬虫脚本，获取图书数据
    public function getdata() {
        $keyword = $_POST['keyword'];
        $page = $_POST['page'];
        $res = db::execute('TRUNCATE TABLE tempbooks');  // 清空数据库记录
        chdir(APP_PATH."python/dangDang");  // 改变为当前目录
        $content = shell_exec("scrapy crawl books -a num=".$page." -a url=http:/"."/bang.dangdang.com/books/".$keyword);
        $this->success('爬取成功', url('book/add'));
    }
    public function all() {
        if (Session::has('nickname')) {
            $data = db('books')->paginate(10);
            $this->assign('data',$data);
            return $this->fetch();
        } else {
            $this->error("这是管理页面，请先登录！",url('Login/login'));
        }
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
    public function edit() {    // 修改图书页面
        $bookid = input('id');
        $book = BookModel::get($bookid);
        $this->assign('book', $book);
        return $this->fetch();
    }
    public function update() {
        $request = Request::instance();
        $data = $request->post();
        $file = request()->file('picture');
        if($file) {
            $info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads');     //DS的值为当前系统分隔符，即\
            if($info) $data['picture'] = $info->getFilename();  //为$data添加图片名字信息
            else echo $file->getError();
        }
        $result = BookModel::update($data);
        return $this->success("更新成功！", url('book/all'));
    }
    public function order() {
        if (Session::has('nickname')) {
            $data = Db('orders')->alias('o')
            ->join('books b', 'o.bookid = b.bookid')
            ->field('o.orderid,b.bookname,o.booknum,b.price,o.ordertime,o.nickname')
            ->paginate(10);
            $this->assign('data',$data);
            return $this->fetch();
        } else {
            $this->error("这是管理页面，请先登录！",url('Login/login'));
        }
    }
    public function savetoexcel() {
        $path = ROOT_PATH."extend/phpexcel/PHPExcel/PHPExcel.php";
        require $path;//引入文件
        $objPHPExcel = new \PHPExcel();//实例化PHPExcel类==新建一个excel表格
        $objSheet = $objPHPExcel->getActiveSheet();//获得当前活动sheet的操作对象
        $tit = array("A1"=>"订单编号", "B1"=>"下单昵称", "C1"=>"下单书籍", "D1"=>"下单价格", "E1"=>"下单数量", "F1"=>"订单总额", "G1"=>"下单时间");
        // 获取订单数据
        $data = Db('orders')->alias('o')
        ->join('books b', 'o.bookid = b.bookid')
        ->field('o.orderid,b.bookname,o.booknum,b.price,o.ordertime,o.nickname')->select();
        foreach($tit as $key=>$val) {
            $objSheet->setCellValue($key,$val);     //输出表头
        }
        $j = 2;
        foreach($data as $key=>$val) {
            $objSheet->setCellValue("A".$j,$val['orderid'])->setCellValue("B".$j,$val['nickname'])->setCellValue("C".$j,$val['bookname'])->setCellValue("D".$j,$val['price'])->setCellValue("E".$j,$val['booknum'])->setCellValue("F".$j,$val['price']*$val['booknum'])->setCellValue("G".$j,$val['ordertime']);
            $j++;
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");//按照指定格式生成excel文件
        $result = $objWriter->save(ROOT_PATH."public/static/file/order.xlsx");
        if($result) {
            $this->success('导出失败！', 'book/order');
        }else{
            $this->success('导出成功！', 'book/order');
        }
    }
}