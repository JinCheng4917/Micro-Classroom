<?php
namespace app\admin\model;
use think\Model;    // 使用前进行声明
/**
 * Student 学生表
 */
class Student extends Model
{
    protected $dateFormat = 'Y年m月d日';    // 日期格式

 public function index()
 {

 }

    /**
     * 输出性别的属性
     
     */
    public function getSexAttr($value)
    {
        $status = array('0'=>'男','1'=>'女');
        $sex = $status[$value];
        if (isset($sex))
        {
            return $sex;
        } else {
            return $status[0];
        }
    } 
     public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    /**
    */
    public function Klass()
    {
        return $this->belongsTo('klass');
    }
    public function Teacher()
    {
        return $this->belongsTo('teacher');
    }
}