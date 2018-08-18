<?php 
namespace app\index\controller;

use think\Controller;
use firebase\JWT\JWT;
use think\Config;

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
		$token = array(
		'id' => $data['id'],
		'userName' => $data['userName'],
		'phone'=>$data['phone'],
		'lastLoginTime'=>$data['lastLoginTime']
		);

		$jwt= JWT::encode($token, $key);
		return $jwt;
	}
}
