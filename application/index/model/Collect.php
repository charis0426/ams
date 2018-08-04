<?php
namespace app\index\model;

use think\Model;
use think\Db;   // 引用数据库操作类
class Collect extends Model
{
    protected $table='sent_info';

  public  function query(){
      $data=Db::name($this->table)->select();
      return $data;
  }
}


?>