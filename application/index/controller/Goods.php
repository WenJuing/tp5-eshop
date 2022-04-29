<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model\Carts as cart;
use app\index\model\Books as book;
use app\index\model\User as user;
use app\index\model\Orders as order;
use think\db;
class Goods extends Controller {
    public function goods() {
        $bookid = input('id');
        $info = db('books')->where('bookid', $bookid)->setInc('hot', 1);  // 文字点击数
        $data = db('books')->where('bookid',$bookid)->find();
        $res = db::query("select sum(booknum) from orders where bookid='$bookid'");
        # 计算累计销量
        if ($res[0]['sum(booknum)'])
            $data['sell'] = $res[0]['sum(booknum)'];
        else 
            $data['sell'] = '无';
        $data['index'] = round(($res[0]['sum(booknum)']*100 + $data['hot']) / $data['price'], 2);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function mycart() {
        if (Session::has('nickname')) {
            $cart = Db('carts')->alias('a')
            ->where('a.nickname', '=', session::get('nickname'))
            ->join('books b', 'a.bookid = b.bookid')
            ->field('a.cartid,a.booknum,b.bookname,b.price,b.bookid')
            ->select();
            $this->assign('cart', $cart);
            return $this->fetch();
        } else {
            return $this->error('请先登录！', 'index/index');
        }
    }
    public function addcart() {
        if (Session::has('nickname')) {
            $nickname = session::get('nickname');
            $bookid = input('id');
            //从购物车表中查找购买了该书的记录
            $res = cart::get(['nickname'=>$nickname, 'bookid'=>$bookid]);
            if ($res) { // 若已购买，则该图书购买量+1
                $info = $res->setInc('booknum', 1);
                return $this->success('购物车中已购该图书，购买数量已增加1！', 'index/index');
            } else {    //没购买该书，购物表新增字段
                $cart = new cart;
                $cart->nickname = $nickname;
                $cart->bookid = $bookid;
                $cart->booknum = 1;
                $cart->addtime = date('Y-m-d H:i:s', time());
                $cart->save();
                $this->success('加入购物车成功！', 'goods/mycart');
            }
        } else {
            return $this->error('请先登录！', 'index/index');
        }
    }
    public function delcart() {
        $cartid = input('id');
        $res = cart::destroy($cartid);
        return $this->success('删除成功！', 'goods/mycart');
    }
    public function submitcart() {
        // 根据购物车表生成订单表
        $nickname = session::get('nickname');
        $cart_list = cart::all(['nickname'=>$nickname]);
        $userid = user::get(['nickname'=>$nickname])->userid;
        $format_time = date('Y-m-d H:i:s', time());
        $num_time = date("YmdHis");
        foreach ($cart_list as $cl) {
            $order = new order;
            $order->orderid = $num_time.$userid;
            $order->nickname = $nickname;
            $order->bookid = $cl->bookid;
            $order->orderprice = book::get(['bookid'=>$cl->bookid])->price;
            $order->booknum = $cl->booknum;
            $order->ordertime = $format_time;
            $order->save();
        }
        if (cart::destroy(['nickname'=>$nickname]))
            return $this->success('订单已生成！', 'goods/myorder');
        else
            return $this->error('提交失败！', 'goods/myorder');
    }
    public function buy() {
        $bookid = input('id');
        $booknum = $_POST['booknum'];
        $nickname = session::get('nickname');
        $userid = user::get(['nickname'=>$nickname])->userid;

        $order = new order;
        $order->orderid = date("YmdHis").$userid;
        $order->nickname = $nickname;
        $order->bookid = $bookid;
        $order->orderprice = book::get(['bookid'=>$bookid])->price;
        $order->booknum = $booknum;
        $order->ordertime = date('Y-m-d H:i:s', time());
        if ($order->save())
            return $this->success('订单已生成！', 'goods/myorder');
        else
            return $order->getError();
    }
    public function myorder() {
        if (Session::has('nickname')) {
            // 查找该用户是否有下单记录
            $nickname = session::get('nickname');
            $order = Db('orders')->alias('o')
            ->where('o.nickname', '=', $nickname)
            ->order('ordertime desc')  // 根据时间降序排序
            ->join('books b', 'o.bookid = b.bookid')
            ->join('users u', 'u.nickname = o.nickname')
            ->field('o.orderid,o.bookid,b.bookname,o.booknum,b.price,b.picture,o.ordertime,u.address,u.tel,u.username')
            ->paginate(2);
            $this->assign('order', $order);
            return $this->fetch();
        } else {
            return $this->error('请先登录！', 'index/index');
        }
    }
}