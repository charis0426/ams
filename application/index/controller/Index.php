<?php
namespace app\index\controller;
use app\index\model\FileResourceTag;
use app\index\model\Index as indexModel;
use app\index\model\FileResourceTag as file;
use think\Session;
use think\Log;
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
        //接受json
        $res_data=request()->post();
        if(checkData($res_data,'data')){
            $data=$res_data['data'];
            //判断用户名和密码是否为空
            if(!checkData($data,'userName')){
                return returnJson("1006");
            }
            $userName = $data['userName'];
            if(!checkData($data,'password')){
                return returnJson("1007");
            }
            $password = $data['password'];
            //调用model验证方法
             $res = $this->indexModel->checkLogin($userName);
            if($res != null){
                //验证密码是否正确
                if(password_verify($password, $res['password'])){
                    //更新数据库登录时间
                    $update_res = $this->indexModel->updateLoginInfo(time(),$res['id']);
                    if($update_res != 1){
                        //写日志记录没有更新
                        Log::notice('用户：'.$userName.' 异常：登录时间没有更新');
                    }
                    //生成token
                    $token = parent::createToken($res);
                    //返回json
                    $map['token'] = $token;
                    return returnJson("2000",$map);
                }
                return returnJson("2003");
            }
            return returnJson("2004");
        }
        return returnJson("2001");  
    }

    /*
     *
     *注册方法
     * 
     */
    public function register()
    {
        //接受json数据
        $res_data=request()->post();
        if(checkData($res_data,'data')){
            $data=$res_data['data'];
            //判断注册用户名是否为空
            if(!checkData($data,'userName')){
                return returnJson("1008");
            }
            $new_data['userName'] = $data['userName'];
            //判断注册密码是否为空
            if(!checkData($data,'password')){
                return returnJson("1009");
            }
            $new_data['password'] = parent::getPassHash($data['password']);
            //判断注册电话是否为空
            if(!checkData($data,'phone')){
                return returnJson("1010");
            }
            $new_data['phone'] = $data['phone'];
            //获取当前时间戳
            $new_data['lastLoginTime'] = time();  
            $new_data['createTime'] = time();
            //return json_encode($new_data);
            //调用model数据库添加方法
            $res=$this->indexModel->add($new_data);

            return json_encode($res);

        }
        return returnJson("2001");
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
