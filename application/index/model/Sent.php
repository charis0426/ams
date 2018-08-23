<?php
namespace app\index\model;

use think\Model;
use think\Db;   // 引用数据库操作类
class Sent extends Model
{
  protected $table='sent_info';
  /*
   *
   * 送礼记录分页查询
   */
  public  function query($data){
      $res=Db::name($this->table)->where('name','like','%'.$data['name'].'%')->page($data['page'].','.$data['pageSize'])->select(); 
      return $res;
  }
  /*
   *
   * 添加送礼记录
   */
  public function add($data){
  	$res = DB::table($this->table)->insert($data);
  	return $res;
  }
   /*
   *
   * 修改送礼记录
   */
  public function updateSentInfo($data){
  	$res = DB::table($this->table)->update($data);
  	return $res;
  }
}


?>