<?php
namespace app\index\controller;
use think\Hook;
use app\index\model\Index;
/*
 *
 * 用户基础控制器
 * 
 */
class BaseCheckAuth extends Base
{  

	public $adminInfo = '';
	public function initialize(){
		parent::initialize();
		//监听登录的钩子
		$params = [];
		Hook::listen('checkAuth',$params);
		//验证成功后 吧登录信息副给info
		$id = request()->header('X-Adminid');
		$indexModel = new Index();
		$loginInfo = $indexModel->queryById($id);
		$this->adminInfo = $loginInfo;
	}



}