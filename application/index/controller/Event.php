<?php
namespace app\index\controller;
use app\index\model\Event as eventModel;
class Event
{
	/*
     * 事件查询
     *
     */
 public function query()
    {
        $res=new eventModel();
        return json_encode($res->query());
    }

}

?>