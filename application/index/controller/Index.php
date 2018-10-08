<?php
namespace app\index\controller;
use app\index\model\FileResourceTag;
use app\index\model\Index as indexModel;
use app\index\model\FileResourceTag as file;
use think\Config;
use think\Log;
use think\Cache;
use app\common\apiClient as api;
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
            if(!checkData($data,'password',1,1)){
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
     * 获取验证码
     *
     */
    public function getCode()
    {
        //接受json数据
        $res_data=request()->post();
        if(checkData($res_data,'data')) {
            $data=$res_data['data'];
            //检测手机号码是否为空
            if(!checkData($data,'phone','',1)){
                return returnJson("10018");
            }
            $phone = $data['phone'];
            //检测微信登录的code
            if(!checkData($data,'code')){
                return returnJson("3002");
            }
            $code = $data['code'];
            //判断手机是否被绑定
            //随机生成6位验证码
            $randCode = rand(100000,999999);
            //session保存验证码和手机号
            $redisDate['bdCode'] = $randCode;
            $redisDate['bdPhone'] = $phone;
            Cache::set($code,$redisDate,3600); 
            $param = array(
                'mobile' => $phone,
                'tpl_id' => Config::get("render.messageId"),
                'tpl_value' => '#code#='.$randCode,
                'key' => Config::get("render.messageKey"),
                'dtype' => 'json'
            );
            $api_url = Config::get("render.messageApi");
            $rest = new api($api_url, $param, 'post');
            $info = $rest->doRequest();
            // $info['error_code']=0;
            // $info['code']=$randCode;
            // $info['sessionId']=$code;
            return json_encode($info);
        }
        return returnJson("2001");
    }

    /*
     * 绑定手机验证
     *
    */
    public function checkBdCode(){
        //接受json数据
        $res_data=request()->post();
        if(checkData($res_data,'data')) {
            $data=$res_data['data'];
            //验证验证码是否传值
            if(!checkData($data,'code','',1)){
                return returnJson("10019");
            }
            $code = $data['code'];
            //验证是否传递了微信个人信息nickName,avatarUrl,city,country,gender,language,province
            if(!checkData($data,'nickName',1)){
                return returnJson("10011");
            }
            $new_data['userName'] = $data['nickName'];
            if(!checkData($data,'avatarUrl',1)){
                return returnJson("10012");
            }
            $new_data['avatarUrl'] = $data['avatarUrl'];
            if(!checkData($data,'city',1)){
                return returnJson("10013");
            }
            $new_data['city'] = $data['city'];
            if(!checkData($data,'country',1)){
                return returnJson("10014");
            }
            $new_data['country'] = $data['country'];
            if(!checkData($data,'gender','',1)){
                return returnJson("10015");
            }
            $new_data['gender'] = $data['gender'];
            if(!checkData($data,'language',1)){
                return returnJson("10016");
            }
            $new_data['language'] = $data['language'];
            if(!checkData($data,'province',1)){
                return returnJson("10020");
            }
            $new_data['province'] = $data['province'];
            //验证验证码是否正确
            $sessionId = request()->header('Cookie');
            if($sessionId == ''){
                return returnJson("3002");
            }
            $cookie = Cache::get($sessionId);
            if($code != $cookie['bdCode']){
                return returnJson("10021");
            }
            //判断该手机是否已经被绑定
            $count_res = $this->indexModel->isBdByPhone($cookie['bdPhone']);
            if($count_res >0){
                return returnJson("10022");
            }
            //通过code换取session_key和openId
            $arr = parent::getWeixinInfo($sessionId);
            $openid = $arr['openid'];
            $session_key = $arr['session_key'];
            $new_data['openId'] = $openid;
            $new_data['phone'] = $cookie['bdPhone'];
            $new_data['password'] = parent::getPassHash("123456");
            $new_data['lastLoginTime'] = time();
            $new_data['createTime'] = time();
            //往数据库加用户
            $res=$this->indexModel->add($new_data);
            if($res<=0){
                return returnJson("3001");
            }
            //存入数据库后，把微信的数据存入到jwt,默认绑定后自动登录
            //生成token
            $token = parent::createToken($new_data);
            //返回json
            $map['token'] = $token;
            return returnJson("2000",$map);
        }
        return returnJson("2001");
    }
    /*
     *检测是否已经绑定，判断登录状态
     * 
     */
    public function checkBd(){
        //接受json数据
        $res_data=request()->post();
        if(checkData($res_data,'data')) {
            $data=$res_data['data'];
        }
        //验证code是否传值小程序的code
        if(!checkData($data,'code',1)){
            return returnJson("3003");
        }
        $code = $data['code'];
        //通过code换取session_key和openId
        $arr = parent::getWeixinInfo($code);
        $openid = $arr['c'];
        $session_key = $arr['session_key'];
        //检测是否已经被绑定
        $res = $this->indexModel->checkBd($openid);
        // if($res != null){
        //     //被绑定就自动将用户信息，返回，实现登陆
        //     return 0;
        // }else if{
        //     return 0;
        // }

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
    public function test()
    {   session_id('bsr1im9a28fdie9hbn92ioh2r1');
        session_start();//这个函数必须在session_id()之后
        echo Session::get('bdCode');
        die;
        return json_encode(session());
    }
    public function test1()
    {
        //生成第三方3rd_session
        $session3rd  = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        for($i=0;$i<16;$i++){
            $session3rd .=$strPol[rand(0,$max)];
        }
        session_start($session3rd);
        Session::set('name','zhangsan');
        return json_encode($session3rd);

    }
}


?>
