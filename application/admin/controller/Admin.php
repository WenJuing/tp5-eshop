<?php
namespace app\admin\controller;
use think\Controller;
class Admin extends Controller {
    public function admin() {
        if(session('username') == 'admin') {
        return $this->fetch();
        }
        else{
            $this->error('这是管理页面，请先登录！',url('Login/login'));
        }
    }
}