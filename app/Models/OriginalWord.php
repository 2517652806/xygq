<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalWord extends Model
{
    //
    // 指定数据表
    protected $table = "original_word";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];


    public static function original_add($school_name,$word,$original_name)
    {
        $cot = count($word);
        for ($i = 0; $i < $cot;$i++)
        {
            $cnt = self::create([
                'school_name' => $school_name,
                'original_name' => $original_name,
                'word_name' => $word[$i]['name'],
                'word_phone' => $word[$i]['num'],
                'word_card' => $word[$i]['idcard'],
            ]);
        }

        return $cnt;
    }


    public static function original_change($school_name,$word,$original_name)
    {

        $cot = count($word);
        for ($i = 0; $i < $cot;$i++)
        {
            $cnt = self::where('school_name',$school_name)
                ->where('original_name',$original_name)
                ->update([
                'school_name' => $school_name,
                'original_name' => $original_name,
                'word_name' => $word[$i]['name'],
                'word_phone' => $word[$i]['num'],
                'word_card' => $word[$i]['idcard'],
            ]);
        }

        return $cnt;
    }
    //获取词作者信息
    public static function get_word_info($school_name,$original_name)
    {
        try {
            $data4 = self::select('word_name', 'word_phone', 'word_card')
                ->where('school_name', $school_name)
                ->where('original_name', $original_name)
                ->get();
            return $data4;
        } catch (\Exception $e) {
            logError('获取失败！', [$e->getMessage()]);
            return false;
        }
    }


    public static function school_lool_original($school_name,$original_name)
    {
        //获取词作者信息
        $data = self::select('word_name', 'word_phone', 'word_card')
            ->where('school_name', $school_name)
            ->where('original_name', '=', $original_name)
            ->get();
        return $data;
    }
}
