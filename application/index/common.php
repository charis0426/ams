<?php

use app\common\errCode;

/**
 * 公共函数返回json
 */
function returnJson($code,$data=null){
	$map['errCode']=$code;
	$map['errMsg']=errCode::ERRCODE[$code];
	if($data != null){
		$map['data']=$data;
	}
    return json_encode($map); 
}
/*
 *
 *生成sessionid
 *
 */
function createSessionId(){
    //生成第三方sessionId
    $sessionId  = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;
    for($i=0;$i<16;$i++){
        $sessionId .=$strPol[rand(0,$max)];
    }
    return $sessionId;
}
/*
 *
 *判断请求参数是否合法
 *num=1 表示该参数可以为空 =0表示必须不为空
 * 
 */
function checkData($data,$key,$num=0,$type=0){
	if(!isset($data[$key])){
		return false;
	}
	else if(isset($data[$key])&&$data[$key]==""&&$num==0){
		return false;
	}
	else if(isset($data[$key])&&$data[$key]==""&&$num==1){
		return true;
	}
	else if(isset($data[$key])&&empty($data[$key])&&$num==0){
		return false;
	}
	else if(isset($data[$key])&&is_numeric($data[$key])&&$type==0){
		return false;
	}
	else if(isset($data[$key])&&!is_numeric($data[$key])&&$type==1){
		return false;
	}
	return true;
}