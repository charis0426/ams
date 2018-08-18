<?php
namespace app\index\controller;
use app\index\model\Event as eventModel;
use think\Log;
use think\Config;
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
        //接受json
        $res_data=request()->post();
        //判断是否有data数据,判断数据是否为空
        if(checkData($res_data,'data')){
            $data=$res_data['data'];
            //判断page 参数是否正确
            if(!checkData($data,'page',0,1)){ 
                $page = 1;
            }
            $new_data['page'] = $data['page'];
            //判断查询name的关键字可以为空字符串
            if(!checkData($data,'name',1)){
                return returnJson("10013");
            }
            $new_data['name'] = $data['name'];
            $new_data['pageSize'] = Config::get("render.pageSize");
            $res = $this->eventModel->query($new_data);
            if(count($res)==0){
                $map['total'] = 0;
                $map['list'] = [];   
            }else{
                $map['total'] = count($res);
                $map['list'] = $res;
            }
            return returnJson("2000",$map);
        }
        return returnJson("2001");
    }
    /*
     * 事件添加
     *
     */
    public function add()
    {
        //接受json
        $res_data=request()->post();
        //判断是否有data数据,判断数据是否为空
        if(checkData($res_data,'data')){
            $data=$res_data['data'];
            //判断事件名称是否为空
            if(!checkData($data,'name')){
                return returnJson("10011");
            }
            $new_data['name'] = $data['name'];
            //判断时间日期是否为空
            if(!checkData($data,'date')){
                return returnJson("10012");
            }
            $new_data['date'] = strtotime($data['date']);
            //判断备注是否合法
            if(!checkData($data,'info',1)){
                return returnJson("1005");
            }
            $new_data['info']=$data['info'];
            //获取当前创建时间
            $new_data['createTime'] = time();
            //插入数据
            $res=$this->eventModel->add($new_data);
            if($res==1){
                return returnJson("2000");
            }
            //写日志记录
            Log::notice('添加事件：'.json_encode($new_data).' 异常：插入失败'.json_encode($res));
            return returnJson("3001");
        }
        return returnJson("2001");  
    }
    /*
     * 编辑事件
     * 可编辑参数为'name' 'info' 'date'
     */
    public function update(){
        //接受json
        $res_data=request()->post();
        //判断是否有data数据,判断数据是否为空
        if(checkData($res_data,'data')){
            $data=$res_data['data'];
            //判断是否有修改ID存在
            if(!checkData($data, 'id', 0,1)){
                return returnJson("10014");
            }
            $new_data['id'] = $data['id'];
            //判断事件名称是否为空
            if(!checkData($data,'name')){
                return returnJson("10011");
            }
            $new_data['name'] = $data['name'];
            //判断时间日期是否为空
            if(!checkData($data,'date')){
                return returnJson("10012");
            }
            $new_data['date'] = strtotime($data['date']);
            //判断备注是否合法
            if(!checkData($data,'info',1)){
                return returnJson("1005");
            }
            $new_data['info']=$data['info'];
            //修改数据
            $res=$this->eventModel->updateEvent($new_data);
            if($res==1){
                return returnJson("2000");
            }else if($res==0){
                return returnJson("2005");
            }
            //写日志记录
            Log::notice('修改事件：'.json_encode($new_data).' 异常：修改失败'.json_encode($res));
            return returnJson("3001");
        }
        return returnJson("2001");  
    }


}

?>