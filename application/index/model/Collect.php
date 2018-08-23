<?php
namespace app\index\model;

use think\Model;
use think\Db;   // 引用数据库操作类
class Collect extends Model
{
    protected $table='received_info';

  public  function add($data){
  		$data=Db::table($this->table)->insert($data);
  		return $data;
  }
}


?>