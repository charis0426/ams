<?php
namespace app\index\behavior;

use think\Controller;
use think\Request;
use think\Session;
use think\Exception;

/**
* 
*/
class CheckAuth
{
    
    //绑定到CheckAuth标签，用于检测Session以用来判断用户是否登录
    public function run(&$params){
    	//获取token和用户id
    	$token = request()->header('x-Tokend');
    	$id = request()->header('x-Adminid');
        //验证请求方法是否合法
        if(!IS_POST){
            exit(returnJson("2002"));
        }
        if(Session::has($token) == null || Session::get($token)!=$id){
            //验证失败返回消息
            exit(returnJson("403"));

    	}
        
    }
}




