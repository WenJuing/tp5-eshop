<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class People extends Controller {
    public function admin() {
        if(Session::has('nickname')) {
        $data = db('administrators')->paginate(10);
        $this->assign('data',$data);
        return $this->fetch();
        }
        else{
            $this->error('这是管理页面，请先登录！',url('Login/login'));
        }
    }
    public function user() {
        if(Session::has('nickname')) {
        $data = db('users')->paginate(10);
        $this->assign('data',$data);
        return $this->fetch();
        }
        else{
            $this->error('这是管理页面，请先登录！',url('Login/login'));
        }
    }
}