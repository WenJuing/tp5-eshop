<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User as u;
use think\Session;

class User extends Controller
{
    public function user()
    {
        if (Session::has('nickname')) {
            $user = u::get(['nickname' => Session::get('nickname')]);
            $this->assign('user', $user);
            return $this->fetch();
        } else {
            return $this->error('请先登录！', 'index/index');
        }
    }
}
