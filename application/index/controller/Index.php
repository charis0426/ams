<?php
namespace app\index\controller;
use app\index\model\FileResourceTag;
use app\index\model\Index as indexModel;
use app\index\model\FileResourceTag as file;
class Index
{

    /*
     * 财务首页方法
     *
     */
    public function index()
    {
       return "财务管理系统";
    }
    /*
     * 财务登陆方法
     *
     */
    public function login()
    {
        return "欢迎登陆";
    }
    /*
     * 测试查询方法
     *
     */
    public function query()
    {   $member=new indexModel();
        return json_encode($member->query());
    }
    /*
         * 测试插入数据方法
         *
         */
    public function add()
    {   $member=new file();
        $member->tag="sds";
        $member->create_time="2018-07-10 17:09:43";
        $member->save();
        return  '新增成功。新增ID为:' . $member->id;
    }
    /*
        * 测试修改数据方法
        *
        */
    public function update()
    {   $member = FileResourceTag::get(1);
         //var_dump($member);
        // 过滤post数组中的非数据表字段数据
        //$member->allowField(true)->save($_POST,['id' => 1]);
        // post数组中只有name和email字段会写入
        //$member->allowField(['name','email'])->save($_POST, ['id' => 1]);
        $member->tag="dddd";
        $member->create_time="2018-07-10 17:09:43";
        $staus = $member->save();
        return  '修改成功。修改ID为:'.$staus;
    }
    /*
     * 测试删除数据方法
     *
     */
    public function del()
    {   $user = file::get(1);
        $user->delete();
        return  '删除成功。删除ID为:' . $user->id;
    }
}


?>
