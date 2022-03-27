<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Admin extends Controller {
    public function admin() {
        if(Session::has('nickname')) {
        return $this->fetch();
        }
        else{
            $this->error('这是管理页面，请先登录！',url('Login/login'));
        }
    }
}