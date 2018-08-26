<?php 
namespace app\index\controller;

use think\Controller;
use firebase\JWT\JWT;
use think\Config;
use app\common\apiClient as api;

/*
 *
 * 基础控制器
 *
 */

class Base extends Controller
{
    public function initialize()
    {
       // parent::initialize();
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,PATCH,OPTIONS');
        header('Access-Control-Allow-Headers:Content-Type, X-ELEME-USERID, X-Eleme-RequestID, X-Shard,X-Shard, X-Eleme-RequestID,X-Adminid,X-Token');
    }
	/**
	 * 用户注册密码加密
	 * 
	 */
	public function getPassHash($password){
		$options = [
 		 'cost' => 12 // the default cost is 10
		];
		$hash = password_hash($password, PASSWORD_DEFAULT, $options);
		return $hash;
	}
	/**
	 * 登录成功生产token
	 * 
	 */
	public function createToken($data){
		$key = Config::get("render.key");
		$jwt= JWT::encode($data, $key);
		return $jwt;
	}
	/*
	 *
	 * 微信API 换取openid
	 * 
	 */
	public function getWeixinInfo($code){
	 	$weixin['js_code'] = $code;
        $weixin['appid'] = Config::get("render.appid");
        $weixin['secret'] = Config::get("render.appSecret");
        $weixin['grant_type'] = Config::get("render.grant_type");
        $api_url = Config::get("render.weixin");
        $rest = new api($api_url, $weixin, 'get');
        $info = $rest->doRequest();
        $json = json_decode($info);//对json数据解码
        $arr = get_object_vars($json);
        if(!checkData($arr,'openid')||!checkData($arr,'session_key')){
            exit(returnJson("3002"));
        }
        return $arr;
	}
}
