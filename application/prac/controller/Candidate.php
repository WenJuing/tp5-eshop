<?php
namespace app\prac\controller;
use app\prac\model\Candidate as candi;

class Candidate {
    //新增考生数据
    //方法一
    // public function add() {
    //     $candidate            = new candi;
    //     $candidate->institute = '保险学系';
    //     $candidate->school_id = '2180777';
    //     $candidate->name      = '我妻由乃';
    //     $candidate->exam_id   = '20218077720';
    //     $candidate->password  = '123123';
    //     if($candidate->save()) {
    //         return '用户['.$candidate->name.':'.$candidate->id.']新增成功';
    //     }else{
    //         return $candidate->getError();
    //     }
    // }
    //方法二
    //省略模块实例化，插入方式发生变化
    // public function add() {
    //     $candidate['institute'] = '保险学系';
    //     $candidate['school_id'] = '2180777';
    //     $candidate['name']      = '我妻由乃';
    //     $candidate['exam_id']   = '20218077720';
    //     $candidate['password']  = '123123';
    //     if($result = candi::create($candidate)) {
    //         return '用户['.$result->name.':'.$result->id.']新增成功';
    //     }else{
    //         return '新增出错';
    //     }
    // }
    //批量新增
    public function addList() {
        $candidate = new candi;
        $list = [
            ['institute' => '物理学系',
             'school_id' => '2180772',
             'name' => '爱因斯坦',
             'exam_id' => '20218077221',
             'password' => '123123'],
             ['institute' => '物理学系',
             'school_id' => '2180773',
             'name' => '普朗克',
             'exam_id' => '20218077322',
             'password' => '123123'],
        ];
        if($candidate->saveAll($list)) {
            return '用户批量新增成功';
        }else{
            return $candidate->getError();
        }
    }
    //查询数据
    public function read($id='') {
        $candidate = candi::get($id);  //get方法-->获取数据返回模型对象实例，主键为参数
        echo $candidate->name.'<br />';
        echo $candidate->school_id.'<br />';
        echo $candidate['institute'].'<br />';  //可以以数组或对象方式访问
    }
    // 如果不是根据主键查询的话，可以使用 *数组条件查询*
    // public function read() {
    //     $candidate = candi::get(['name'=>'时崎狂三']);
    //     //更复杂的查询则可以使用 *查询构建器* 来完成
    //     $candidate = candi::where('name', '爱因斯坦')->find();
    //     echo $candidate->name.'<br />';
    //     echo $candidate->school_id.'<br />';
    //     echo $candidate['institute'].'<br />';
    // }
    //查询多个数据
    public function getAll() {
        $list = candi::all();   //方法一，直接查出全部数据
        $list = candi::all(['id'=>4]);  //方法二，数组条件查询
        //方法三，查询构建器
        $list = candi::where(['id' => ['between','5,10']])->select();
        $list = candi::where('id','between','10,50')->select();
        foreach($list as $candidate) {
        echo $candidate->name.'<br />';
        echo $candidate->id.'<br />';
        echo $candidate['institute'].'<br />';
        echo '-----------------'.'<br />';
        }
    }
    //更新数据
    // public function update($id) {
    //     $candidate       = candi::get($id);
    //     $candidate->name = '雷姆';
    //     if(false !== $candidate->save()) {
    //         return '更新用户成功';
    //     }else{
    //         return $candidate->getError();
    //     }
    // }
    //更高效的更新方式
    public function update($id) {
        $candidate['id']   = (int)$id;
        $candidate['name'] = '雷姆';
        $result            = candi::update($candidate);
        return '更新成功';
    }
    //删除数据
    public function delete($id) {
        //方法一
        $candidate = candi::get($id);
        if($candidate) {
            $candidate->delete();
            return '删除用户'.$candidate['name'].'成功';
        }else{
            return '删除的用户不存在';
        }
        //方法二
        $result = candi::destroy($id);
        if($result) {
            return '删除用户成功';
        }else{
            return '删除的用户不存在';
        }
    }
}


