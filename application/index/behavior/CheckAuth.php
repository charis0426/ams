<?php
namespace app\index\behavior;

use think\Controller;
use think\Request;
use think\Session;
use think\Exception;
use firebase\JWT\JWT;
use think\Config;

/**
* 
*/
class CheckAuth
{
    
    //绑定到CheckAuth标签，用于检测Session以用来判断用户是否登录
    public function run(&$params){
    	//获取token和用户id
    	$token = request()->header('x-Tokend');
        //验证请求方法是否合法
        if(!IS_POST){
            exit(returnJson("2002"));
        }
        if($token == ""){
            exit(returnJson("403"));
        }
        $decode = $this->decodeToken($token);
        if(!is_object($decode)){
            //验证失败返回消息
            exit(returnJson("403"));
    	}else{
            $arr = json_decode(json_encode($decode), true);
            return $arr;
        }
        
    }
    //解密token
    public function decodeToken($token){
        $key = Config::get("render.key");
        //exit(json_encode($key));
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $e) {
            $decoded = 0;
        }
        return $decoded;
    }
}




