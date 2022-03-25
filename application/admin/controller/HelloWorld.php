<?php
namespace app\admin\controller;
use think\Controller;   //方法一
use think\Request;      //方法二

class HelloWorld extends Controller {
    public function index(Request $request, $name = 'World')    //方法二
    {
        echo 'URL:'.$this->request->url()."<br />";     //方法一
        echo 'URL:'.$request->url()."<br />";           //方法二
        echo "模块：".$request->module()."<br />";
        echo "控制器：".$request->controller()."<br />";
        echo "操作：".$request->action().'<br />';
        echo '路由信息：';
        dump($request->routeInfo());
        echo '调度信息：';
        dump($request->dispatch());
        $leimu = ['name' => 'leimu', 'age' => '16years'];
        // return json($leimu);
        echo "ROOT_PATH的值：".ROOT_PATH."<br />";
        echo "DS的值：".DS."<br />";
        // echo "ROOT的值：".__ROOT__."<br />";
        return 'Hello,'.$name.'!'."<br />";
    }
    public function pageJump($username='') {
        if($username == 'laowang') {
            // $this->success('欢迎您隔壁的老王！',url('index/Index/index'),'',10);
            return redirect('admin/Login/login');
        }else{
            $this->error('您不是老王！','guest');
        }
    }
    public function hello() {
        return 'hello,老王王王！';
    }
    public function guest() {
        return 'hello,你是是谁谁谁？';
    }
}