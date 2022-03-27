<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Administrator as admin;
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
            $who['username'] = $_POST['username'];
            $who['password'] = $_POST['password'];
            $admin = admin::get(['username'=>$who['username']]);
            if($who['password'] == $admin->password) {   // 若该用户民的密码是输入的密码，则登录成功
                session('nickname',$admin->nickname);
                $this->success('登录成功', 'admin/admin', 3);
            }
            else{
                $this->error('用户名或密码输出错误！', 'login/login', 3);
            }
        }
    }
    public function loginout() {    // 注销
        session(null);
        $this->redirect('Login/login');     //跳转redirect
    }
}