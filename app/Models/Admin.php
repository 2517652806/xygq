<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Admin extends Model
{
    //
    // 指定数据表
    protected $table = "admin";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    //删除学校
    public static function admin_delete($school_name){
        $data = DB::table('school')->where('school_name','=',$school_name)->delete();
        return $data;
    }

    //重置学校密码
    public static function admin_reset($password,$school_name){

     $data= DB::table('school')->where('school_name','=',$school_name)->update([
        'password' => $password
    ]);
     return $data;
    }


    //账号状态
    public static function admin_state($school_name){
       $state = DB::table('school')->where('school_name','=',$school_name)->value('school_state');
       $data = DB::table('school')->where('school_name','=',$school_name)->update([
           'school_state' => !$state
       ]);
       return $data;
    }

    //判断学校是否已经添加
    public static function check_school($school_name){
        $res = DB::table('school')->where('school_name','=',$school_name)->exists();
        return $res;

    }
    //添加学校
    public static function admin_add($school_name,$password){
        $res = self::check_school($school_name);
        if ($res){
            return 0;
        }else {
            $data = DB::table('school')->insert([
                'school_name' => $school_name,
                'password' => $password,
                'school_state' => 1
            ]);
            return $data;
        }
    }


    //渲染学校信息
    public static function admin_rendering(){
        $data = DB::table('school')->select('school_name','school_state')->get();
        return $data;
    }

    //搜索学校
    public static function admin_search($school_name){
        $data = DB::table('school')->where('school_name','like','%'.$school_name.'%')->get();

        return $data;

    }

}
