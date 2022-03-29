<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model\Carts as cart;
use app\index\model\Books as book;
use think\db;
class Goods extends Controller {
    public function goods() {
        $bookid = input('id');
        $data = db('books')->where('bookid',$bookid)->find();
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
            return $this->error('请先登录！', 'login/login');
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
            return $this->error('请先登录！', 'login/login');
        }
    }
    public function delcart() {
        $cartid = input('id');
        $res = cart::destroy($cartid);
        return $this->success('删除成功！', 'goods/mycart');
    }
}