<?php
namespace app\index\controller;
use app\index\model\Collect as collectModel ;
class Collect
{
	/*
     * 送出礼金检索
     *
     */
 public function query()
    {
        $res=new collectModel();
        return json_encode($res->query());
    }
    /*
     * 测试
     *
     */
 public function test()
    {
        return 'test';
    }

}

?>