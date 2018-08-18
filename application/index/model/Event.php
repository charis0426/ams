<?php
namespace app\index\model;

use think\Model;
use think\Db;   // 引用数据库操作类
class Event extends Model
{
    protected $table='event';

  public  function query($data){
      $res=Db::name($this->table)->where('name','like','%'.$data['name'].'%')->page($data['page'].','.$data['pageSize'])->select(); 
      return $res;
  }
  /**
   * 创建时间
   */
  public function add($data){
  	$res = DB::table($this->table)->insert($data);
  	return $res;
  }
}


?>