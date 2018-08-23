<?php
namespace app\index\controller;
use app\index\model\Sent as sentModel;
use think\Log;
use think\Config;
class Sent extends BaseCheckAuth
{
	protected $sentModel;
    /*
     * 构造函数
     *
     */
    public function _initialize(){
        parent::initialize();
        $this->sentModel=new sentModel();
    }
    /*
     * 查询
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
            //判断查询name的关键字
            if(!checkData($data,'name',1)){
                return returnJson("10013");
            }
            $new_data['name'] = $data['name'];
            $new_data['pageSize'] = Config::get("render.pageSize");
            $res = $this->sentModel->query($new_data);
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
     * 送出礼金检索
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
            //判断事件名称是否为空
            if(!checkData($data,'money',0,1)){
                return returnJson("1004");
            }
            $new_data['money'] = $data['money'];
            //判断时间日期是否为空
            if(!checkData($data,'info',1)){
                return returnJson("1005");
            }
            $new_data['info'] = $data['info'];
            //判断时间日期是否为空
            if(!checkData($data,'date')){
                return returnJson("10012");
            }
            $new_data['date'] = strtotime($data['date']);
            //获取当前创建时间
            $new_data['createTime'] = time();
            //插入数据
            $res=$this->sentModel->add($new_data);
            if($res==1){
                return returnJson("2000");
            }
            //写日志记录
            Log::notice('添加送礼：'.json_encode($new_data).' 异常：插入失败'.json_encode($res));
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
            //判断送礼人名称是否为空
            if(!checkData($data,'name')){
                return returnJson("10017");
            }
            $new_data['name'] = $data['name'];
            //判断送礼日期是否为空
            if(!checkData($data,'date')){
                return returnJson("10016");
            }
            $new_data['date'] = strtotime($data['date']);
            //判断备注是否合法
            if(!checkData($data,'info',1)){
                return returnJson("1005");
            }
            $new_data['info'] = $data['info'];
            //修改数据
            $res=$this->sentModel->updateSentInfo($new_data);
            if($res==1){
                return returnJson("2000");
            }else if($res==0){
                return returnJson("2005");
            }
            //写日志记录
            Log::notice('修改送礼记录：'.json_encode($new_data).' 异常：修改失败'.json_encode($res));
            return returnJson("3001");
        }
        return returnJson("2001");  
    }

}

?>