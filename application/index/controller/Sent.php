<?php
namespace app\index\controller;
use app\index\model\Sent as sentModel;
class Sent
{
	/*
     * 送出礼金检索
     *
     */
 public function query()
    {
        $res=new sentModel();
        return json_encode($res->query());
    }

}

?>