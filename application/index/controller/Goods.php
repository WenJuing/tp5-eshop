<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model\Carts as cart;
use app\index\model\Books as book;
class Goods extends Controller {
    public function goods() {
        $bookid = input('id');
        $data = db('books')->where('bookid',$bookid)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function mycart() {
        if (Session::has('nickname')) {
            $cart = cart::get(['nickname'=>session::get('nickname')]);
            $this->assign('cart', $cart);
            if ($cart)  // 若该用户有商品加入购物车，则获取图书信息
                $this->assign('book', book::get(['bookid'=>$cart->bookid]));
            return $this->fetch();
        } else {
            return $this->error('请先登录！', 'login/login');
        }
    }
}