<?php
namespace app\index\model;

use think\Model;
use think\Db;   // 引用数据库操作类
class Index extends Model
{
    protected $table = 'user';

  public  function query(){
      $data = Db::table($this->table)->select();
      return $data;
  }
  //根据id查询用户信息
  public function queryById($id){
  	  $data = DB::table($this->table)->where('id',$id)->find();
      return $data;
  }
  //检测登录
  public function checkLogin($userName){
      $data = DB::table($this->table)->where('userName',$userName)->find();
      return $data;
  }
  //添加用户，注册
  public function add($data){
    $res = DB::table($this->table)->insert($data);
    return $res;
  }
  //更新数据库登录时间
  public function updateLoginInfo($time,$id){
    $res = DB::table($this->table)->where('id', $id)->update(['lastLoginTime' => $time]);
    return $res;
  }
}