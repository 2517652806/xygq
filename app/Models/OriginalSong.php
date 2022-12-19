<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalSong extends Model
{
    //
    // 指定数据表
    protected $table = "original_song";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    public static function getOriginalSong()
    {

    }



    public static function original_add($school_name,$song,$original_name)
    {

        $cot = count($song);
        for ($i = 0; $i < $cot;$i++)
        {
            $cnt = self::create([
                'school_name' => $school_name,
                'original_name' => $original_name,
                'song_name' => $song[$i]['name'],
                'song_phone' => $song[$i]['num'],
                'song_card' => $song[$i]['idcard'],
            ]);
        }

        return $cnt;
    }


    public static function original_change($school_name,$song,$original_name)
    {

        $cot = count($song);
        for ($i = 0; $i < $cot;$i++)
        {
            $cnt = self::where('school_name',$school_name)
                ->where('original_name',$original_name)
                ->update([
                'school_name' => $school_name,
                'original_name' => $original_name,
                'song_name' => $song[$i]['name'],
                'song_phone' => $song[$i]['num'],
                'song_card' => $song[$i]['idcard'],
            ]);
        }

        return $cnt;
    }



    //获取曲作者信息
    public static function get_song_info($school_name,$original_name)
    {
        try {
            $data3 = self::select('song_name', 'song_phone', 'song_card')
                ->where('school_name', $school_name)
                ->where('original_name', $original_name)
                ->get();
            return $data3;
        } catch (\Exception $e) {
            logError('获取失败！', [$e->getMessage()]);
            return false;
        }
    }


    public static function school_lool_original($school_name,$original_name)
    {
        //获取曲作者信息
        $data = self::select('song_name', 'song_phone', 'song_card')
            ->where('school_name', $school_name)
            ->where('original_name', '=', $original_name)
            ->get();
        return $data;
    }
}
