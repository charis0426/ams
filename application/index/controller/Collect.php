<?php
namespace app\index\controller;
use app\index\model\Collect as collectModel ;
use think\Controller;
class Collect extends Controller
{
    protected $collectModel;
    /*
     * 构造函数
     *
     */
    public function _initialize(){
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
                    $map['errCode']=1003;
                    $map['errMsg']='姓名不能为空';
                    return json_encode($data['name']);
                }else{
                    $new_data['name']=$data['name'];
                }
                //判断event是否存在
                if(!isset($data['event'])){
                    $map['errCode']=1004;
                    $map['errMsg']='事件不能为空';
                    return json_encode($data['event']);
                }else{
                    $new_data['event']=$data['event'];
                }
                //判断scene是否存在
                if(!isset($data['scene'])){
                    $map['errCode']=1005;
                    $map['errMsg']='现场不能为空';
                    return json_encode($data['scene']);
                }else{
                    $new_data['scene']=$data['scene'];
                }
                //判断money是否存在
                if(!isset($data['money'])){
                    $map['errCode']=1006;
                    $map['errMsg']='金额不能为空';
                    return json_encode($data['money']);
                }else{
                    $new_data['money']=$data['money'];
                }
                //判断remark是否存在
                if(!isset($data['remark'])||$data['remark']==''){
                    $map['errCode']=1007;
                    $map['errMsg']='备注不能为空';
                    return json_encode($data['remark']);
                }else{
                    $new_data['remark']=$data['remark'];
                }
                //调用model层add方法插入数据
                $res=$this->collectModel->add($new_data);
                return json_encode($res);

            }
            else{
                $map['errCode']=1002;
                $map['errMsg']='请求参数错误';
                return json_encode($map);
            }

        }else{
            $map['errCode']=1001;
            $map['errMsg']='请求方法错误';
            return json_encode($map);
        }
    }

}

?>