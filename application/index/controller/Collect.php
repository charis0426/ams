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
        //判断方法类型
        if(IS_POST){
            //接受json
            $res_data=request()->post();
            //return json_encode($res_data);
            //判断是否有data数据,判断数据是否为空
            if(isset($res_data['data'])&& $res_data['data']!=""){
                $data=$res_data['data'];
                //判断name是否存在
                if(!isset($data['name'])){
                    return returnJson("1001");
                }else{
                    $new_data['name']=$data['name'];
                }
                //判断event是否存在
                if(!isset($data['event'])){
                    return returnJson("1002");
                }else{
                    $new_data['event']=$data['event'];
                }
                //判断scene是否存在
                if(!isset($data['scene'])){
                    return returnJson("1003");
                }else{
                    $new_data['scene']=$data['scene'];
                }
                //判断money是否存在
                if(!isset($data['money'])){
                    return returnJson("1004");
                }else{
                    $new_data['money']=$data['money'];
                }
                //判断remark是否存在
                if(!isset($data['remark'])||$data['remark']==''){
                    return returnJson("1005");
                }else{
                    $new_data['remark']=$data['remark'];
                }
                //调用model层add方法插入数据
                $res=$this->collectModel->add($new_data);
                return json_encode($res);

            }
            else{
                return returnJson("2001");
            }

        }else{
            return returnJson("2002");
        }
    }

}

?>