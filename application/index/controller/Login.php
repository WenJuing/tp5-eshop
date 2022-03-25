<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use app\index\model\User as user;

class Login extends Controller
{
    public function login() // 登录页面
    {
        return $this->fetch();
    }
    public function check() // 检查登录
    {
        $who['username'] = $_POST['nickname'];
        $who['password'] = $_POST['password'];
        $user = user::get(['username'=>$who['username']]);
        if($who['password'] == $user->password) {
            session('username',$who['username']);
            $this->success('登录成功', 'index/index', 3);
        }
        else{
            $this->error('登录失败！');
        }
    }
    public function register()  // 注册页面
    {
        return $this->fetch();
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
            $this->error('该昵称已存在，请重新注册！', 'register', 3);
        } 
        else 
        {
            if ($user->save()) 
            {
                session('username', $user->username);
                $this->success('用户'.$user->nickname.'注册成功！', 'index/index', 3);
            } 
            else 
            {
                return $user->getError();
            }
        }

    }
}