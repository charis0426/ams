<?php
namespace app\index\controller;
use app\index\model\Collect as collectModel ;
use think\Controller;
class Collect extends BaseCheckAuth
{
    protected $collectModel;
    /*
     * 构造函数
     *
     */
    public function _initialize(){
        parent::initialize();
        $this->collectModel=new collectModel();
    }
	/*
     * 收到礼金检索
     *
     */
    public function add()
    {   
        //接受json
        $res_data=request()->post();
        //return json_encode($res_data);
        //判断是否有data数据,判断数据是否为空
        if(!checkData($res_data,'data')){
            $data=$res_data['data'];
            //判断name是否存在
            if(!checkData($data,'name')){
                return returnJson("1001");
            }
            $new_data['name']=$data['name'];
            //判断event是否存在
            if(!checkData($data,'event')){
                return returnJson("1002");
            }
            $new_data['event']=$data['event'];
            //判断scene是否存在
            if(!checkData($data,'scene')){
                return returnJson("1003");
            }
            $new_data['scene']=$data['scene'];
            //判断money是否存在
            if(!checkData($data,'money')){
                return returnJson("1004");
            }
            $new_data['money']=$data['money'];
            //判断remark是否存在
            if(!checkData($data,'remark',1)){
                return returnJson("1005");
            }
            $new_data['remark']=$data['remark'];
            //调用model层add方法插入数据
            $res=$this->collectModel->add($new_data);
            return json_encode($res);

        }
        else{
            return returnJson("2001");
        }

    }
    

}

?>