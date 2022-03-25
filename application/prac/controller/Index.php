<?php
namespace app\prac\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {   
        // $result = db::table('candidates')->where('id','in',[1,2,3,4,5])->select();
        $result = db('candidates')
                    ->where([
                        'id' => ['>=','10'],
                        'institute' => [['like','%会计%'],['like','%计算%'],'or'],
                        'school_id' => ['between','2180001,2180611'],
                    ])->limit(5)
                    ->select();
        $this->assign('data',$result);
        return $this->fetch();
    }
    public function add() {
        $result = db('candidates')
        ->where([
            'id' => ['>=','10'],
            'institute' => [['like','%会计%'],['like','%计算%'],'or'],
            'school_id' => ['between','2180001,2180611'],
        ])->limit(15)
        // ->select();
        // ->column('*','name');
        ->avg('school_id');
        // ->value('name');
        dump($result);
    }
}



