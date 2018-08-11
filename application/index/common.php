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