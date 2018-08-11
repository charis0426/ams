<?php
namespace app\index\controller;
use app\index\model\Event as eventModel;
class Event extends BaseCheckAuth
{
	protected $eventModel;
    /*
     * 构造函数
     *
     */
    public function _initialize(){
        parent::initialize();
        $this->eventModel=new eventModel();
    }
	/*
     * 事件查询
     *
     */
 public function query()
    {
        return json_encode($this->adminInfo);
    }
    /*
     * 事件添加
     *
     */
 public function add()
    {
       
        return json_encode($this->eventModel->query());
    }

}

?>