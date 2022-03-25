<?php
namespace app\admin\controller;
use think\Controller;
class Login extends Controller {
    public function login() {
        return $this->fetch();
    }
    public function doLogin() {
        $captcha = $_POST['captcha'];
        if(!captcha_check($captcha)) {
            $this->error('验证码错误！',url('Login/login'));
        }
        else{
            $who['username'] = $_POST['nickname'];
            $who['password'] = $_POST['password'];
            $administrator = db('administrators');
            $res = $administrator->field('admin_id')->where($who)->find();  //$res数组，键名为admin_id
            if($res && $who['username'] == 'admin') {
                session('username',$who['username']);
                $this->success(('登录成功'),url('Admin/admin'));
            }
            else{
                $this->error('登录失败！');
            }
        }
    }
    public function loginout() {
        session(null);
        $this->redirect('Login/login');     //跳转redirect
    }
}