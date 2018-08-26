<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: hewei <497714858@qq.com>
// +----------------------------------------------------------------------
//++++++++++++系统后台配置参数++++++++++++++//
return [
	//jwt加密指定key值
	"key"=>"ams1234567890",
	"pageSize"=>"1",
    //聚合数据短信接口地址
    "messageApi"=>"http://v.juhe.cn/sms/send",
    //短信api模板id
    'messageId'=>'96186',
    //短信api开发者key
    'messageKey'=>'5eac1ebd7d177a4b21a58d4c613e0d29',
    //微信API
    'weixin'=>'https://api.weixin.qq.com/sns/jscode2session',
    //微信appID
    'appid'=>'wx991bbb040c6333ed',
    //微信API钥匙
    'appSecret'=>'d5910621143cce5e2de9d494c5dde9b5',
    'grant_type'=>'authorization_code'

];