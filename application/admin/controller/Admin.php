<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\db;
use app\admin\model\Administrator as administrator;
use app\admin\model\User as user;
use app\admin\model\Book as book;

class Admin extends Controller {
    public function admin() {
        if(Session::has('nickname')) {
            $data['admin_sum'] = count(administrator::all());
            $data['user_sum'] = count(user::all());
            $data['book_sum'] = count(book::all());
            // 计算今天流水
            $res = db('orders')->whereTime('ordertime', 'today')->select();
            $data['today_income'] = 0;
            foreach($res as $v) {
                $data['today_income'] += $v['booknum'] * $v['orderprice'];
            }
            // 计算总流水
            $res = db('orders')->select();
            $data['all_income'] = 0;
            foreach($res as $v) {
                $data['all_income'] += $v['booknum'] * $v['orderprice'];
            }
            $this->assign('data', $data);
            // 计算近一周流水
            // 获取日期
            $format = 'Y-m-d';
            $time = '';
            $time = $time != '' ? $time : time();
            for ($i=1; $i<=7; $i++){
                $date[$i] = date($format ,strtotime('+'.($i-7).' days', $time));
            }
            // 前六天
            for ($i = 0; $i < 6; $i++) {
                $income[$i] = 0;
                $res = db('orders')->whereTime('ordertime', 'between', [$date[$i+1], $date[$i+2]])->select();
                foreach($res as $v) {
                    $income[$i] += $v['booknum'] * $v['orderprice'];
                }
            }
            // 今天
            $income[6] = 0;
            $res = db('orders')->whereTime('ordertime', 'd')->select();
            foreach($res as $v) {
                $income[6] += $v['booknum'] * $v['orderprice'];
            }
            // 获取日期坐标
            $date = [];
            $format = 'm-d';
            $time = '';
            $time = $time != '' ? $time : time();
            for ($i=0; $i<7; $i++){
                $date[$i] = date($format ,strtotime('+'.($i-6).' days', $time));
            }
            $this->assign('income', json_encode($income));
            $this->assign('date', json_encode($date));

        return $this->fetch();
        }
        else{
            $this->error('这是管理页面，请先登录！',url('Login/login'));
        }
    }
}