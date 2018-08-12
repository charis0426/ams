<?php
namespace app\index\controller;
use app\index\model\FileResourceTag;
use app\index\model\Index as indexModel;
use app\index\model\FileResourceTag as file;
use think\Session;
class Index extends Base
{

    protected $indexModel;
    /*
     * 构造函数
     *
     */
    public function _initialize(){
        parent::initialize();
        $this->indexModel=new indexModel();
    }
   
    /*
     * 财务登陆方法
     *
     */
    public function login()
    {
        //判断方法类型
        if(IS_POST){
            //接受json
            $res_data=request()->post();
            if(isset($res_data['data'])&& $res_data['data']!=""){
              $data=$res_data['data'];
            //判断用户名和密码是否为空
              if(!isset($data['userName'])){
                    return returnJson("1006");
              }
              else{
                   $userName = $data['userName'];
              }
              if(!isset($data['password'])){
                    return returnJson("1007");
              }
              else{
                   $password = $data['password'];
              }
              //调用model验证方法
              $res = $this->indexModel->checkLogin($userName);
              if($res != null){
                //验证密码是否正确
                if($password == $res['password']){
                    //保存token
                    Session::set($password,$res['id']);
                    //返回json
                    $map['id']=$res['id'];
                    $map['token']=$password;
                    return returnJson("2000",$map);
                }
                return returnJson("2003");
              }
              return returnJson("2004");


            }else{
                return returnJson("2001");
            }
        }
        else{
            return returnJson("2002");
        }
    }

    /*
     *
     *注册方法
     * 
     */
    public function register()
    {
     # code...
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
