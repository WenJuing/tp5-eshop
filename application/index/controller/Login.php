<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model\User as user;

class Login extends Controller
{
    public function check() // 检查登录
    {
        $who['username'] = $_POST['nickname'];
        $who['password'] = $_POST['password'];
        $verify = $_POST['verify'];
        $user = user::get(['username'=>$who['username']]);
        if($user && $who['password'] == $user->password) {   // 若该用户民的密码是输入的密码，则登录成功
            session('nickname',$user->nickname);
            $this->success('登录成功', 'index/index', 3);
        }
        else{
            $this->error('用户名或密码输出错误！', 'index/index', 3);
        }
    }
    public function out()   // 注销
    {
        session(null);
        return redirect('index/index');
    }
    public function add()   // 注册（添加用户信息）
    {
        $user = new user;
        $user->nickname = $_POST['nickname'];
        $user->password = $_POST['password'];
        $user->username = $_POST['username'];
        $user->tel = $_POST['tel'];
        $user->address = $_POST['address'];
        // 查询是否有重复昵称
        $user2 = user::get(['nickname'=>$user->nickname]);
        if ($user2 != NULL) 
        {
            $this->error('该昵称已存在，请重新注册！', 'index/index', 3);
        } 
        else 
        {
            if ($user->save()) 
            {
                $this->success('用户'.$user->nickname.'注册成功！', 'index/index', 3);
            } 
            else 
            {
                return $user->getError();
            }
        }

    }
}