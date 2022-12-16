<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalManage extends Model
{
    //
    // 指定数据表
    protected $table = "original_manage";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    public static function original_add($school_name,$manage,$original_name)
    {
//        $sites = array
//        (
//            array
//            (
//                "菜鸟教程",
//                "http://www.runoob.com"
//            ),
//            array
//            (
//                "Google 搜索",
//                "http://www.google.com"
//            ),
//            array
//            (
//                "淘宝",
//                "http://www.taobao.com"
//            )
//        );
//        $cot = count($sites);
        $cot = count($manage);
        for ($i = 0; $i < $cot;$i++)
        {
            $cnt = self::create([
                    'school_name' => $school_name,
                    'original_name' => $original_name,
                    'manage_name' => $manage[$i][0],
                    'manage_phone' => $manage[$i][1],
                ]);
        }

        return $cnt;
    }


    public static function original_change($school_name,$manage,$original_name)
    {
//        $sites = array
//        (
//            array
//            (
//                "菜鸟教程",
//                "http://www.runoob.com"
//            ),
//            array
//            (
//                "Google 搜索",
//                "http://www.google.com"
//            ),
//            array
//            (
//                "淘宝",
//                "http://www.taobao.com"
//            )
//        );
//        $cot = count($sites);
        $cot = count($manage);
        for ($i = 0; $i < $cot;$i++)
        {
            $cnt = self::where('school_name',$school_name)
                ->where('original_name',$original_name)
                ->update([
                'school_name' => $school_name,
                'original_name' => $original_name,
                'manage_name' => $manage[$i][0],
                'manage_phone' => $manage[$i][1],
            ]);
        }

        return $cnt;
    }
}
