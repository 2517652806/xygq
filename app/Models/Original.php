<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Original extends Model
{
    //
    // 指定数据表
    protected $table = "original";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];


    public static function get_trail($state)
    {
        try {
            if($state == '0')
            {
                $res = self::whereBetween('original_state',[1,2])
                    ->select('school_name','id','updated_at','original_state')
                    ->get();
                return $res;
            }
            if($state == '1')
            {
                $res = self::where('original_state','>','2')
                    ->select('school_name','id','updated_at','original_state')
                    ->get();
                return $res;
            }
        } catch (\Exception $e) {
            logError('查询学校的审核记录失败！', [$e->getMessage()]);
            return false;
        }
    }



    public static function select_trail($state,$school_name)
    {
        try {
            if($state == '0')
            {
                $res = self::orwhere('school_name','like','%'.$school_name.'%')
                    ->whereBetween('original_state',[1,2])
                    ->select('school_name','id','updated_at','original_state')
                    ->get();
                return $res;
            }
            if($state == '1')
            {
                $res = self::orwhere('school_name','like','%'.$school_name.'%')
                    ->where('original_state','>','2')
                    ->select('school_name','id','updated_at','original_state')
                    ->get();
                return $res;
            }
        } catch (\Exception $e) {
            logError('查询学校的审核记录失败！', [$e->getMessage()]);
            return false;
        }
    }



    /**
     * 省/市级端获取原创所有节目
     * @param $state
     * @return false
     */
    public static function getAllSong($state)
    {
        try {
            $data = self::select('school_name', 'id', 'original_name')
                ->where('original_state', $state)
                ->get();
            return $data;
        } catch (\Exception $e) {
            logError('获取节目列表失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 省/市级端根据学校名查询原创节目
     * @param $state
     * @param $schoolName
     * @return false
     */
    public static function getSongBySchoolName($state, $schoolName)
    {
        try {
            $data = self::select('school_name', 'id', 'original_name')
                ->where('school_name', 'like', '%' . $schoolName . '%')
                ->where('original_state', '=', $state)
                ->get();
            return $data;
        } catch (\Exception $e) {
            logError('获取节目列表失败！', [$e->getMessage()]);
            return false;
        }
    }

    public static function getSongInfo($state, $id)
    {
        try {
            //获取作品信息
            $data1 = self::select('original_class', 'original_name', 'original_author', 'school_name', 'original_howtime',
                'original_time', 'original_mp3', 'original_info', 'original_word', 'commitment')
                ->where('original.id', $id)
                ->where('original_state', '=', $state)
                ->get();
            $school_name = $data1[0]->attributes['school_name'];
            $original_name = $data1[0]->attributes['original_name'];
            //获取负责人信息
            $data2 = OriginalManage::get_manage_info($school_name,$original_name);
            //获取曲作者信息
            $data3 = OriginalSong::get_song_info($school_name,$original_name);
            //获取词作者信息
            $data4 = OriginalWord::get_word_info($school_name,$original_name);
            $data1 = array($data1);
            $data2 = array($data2);
            $data3 = array($data3);
            $data4 = array($data4);

            $data = array_merge($data1,$data2,$data3,$data4);
            return $data;
        } catch (\Exception $e) {
            logError('获取节目列表失败！', [$e->getMessage()]);
            dd($e->getMessage());
            return false;
        }
    }


    /**
     * 批量驳回
     * @param $state
     * @param $id
     * @return false|int
     */
    public static function songsOverrule($state, $id,$why)
    {
        try {
            $num = count($id);
            for ($i = 0; $i < $num; ++$i) {
                self::where('id', $id[$i])
                    ->update([
                        'original_state' => $state,
                        'original_why' => $why
                    ]);
            }
            return $num;
        } catch (\Exception $e) {
            logError('操作失败！', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 单个作品驳回
     * @param $state
     * @param $id
     * @param $why
     * @return false
     */
    public static function songOverrule($state, $id, $why)
    {
        try {
            $data = self::where('id', $id)
                ->update([
                    'original_state' => $state,
                    'original_why' => $why
                ]);
            return $data;
        } catch (\Exception $e) {
            logError('操作失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 作品通过
     * @param $state
     * @param $id
     * @return false
     */
    public static function songPass($state, $id)
    {
        try {
            $data = self::where('id', $id)
                ->update([
                    'original_state' => $state
                ]);
            return $data;
        } catch (\Exception $e) {
            logError('操作失败！', [$e->getMessage()]);
            return false;
        }
    }


    public static function original_add($school_name,$original_name,$original_howtime,$original_class
        ,$original_time,$original_author,$original_info,$original_mp3,$original_word,$commitment)
    {
        $cnt = self::create([
                'school_name' => $school_name,
                'original_name' => $original_name,
                'original_howtime' => $original_howtime,
                'original_class' => $original_class,
                'original_time' => $original_time,
                'original_author' => $original_author,
                'original_info' => $original_info,
                'original_mp3' => $original_mp3,
                'original_word' => $original_word,
                'commitment' => $commitment,
            ]);
        return $cnt;
    }

    public static function original_change($school_name,$original_name,$original_howtime,$original_class
        ,$original_time,$original_author,$original_info,$original_mp3,$original_word,$commitment)
    {
        $cnt = self::where('school_name',$school_name)
            ->where('original_name',$original_name)
            ->update([
            'school_name' => $school_name,
            'original_name' => $original_name,
            'original_howtime' => $original_howtime,
            'original_class' => $original_class,
            'original_time' => $original_time,
            'original_author' => $original_author,
            'original_info' => $original_info,
            'original_mp3' => $original_mp3,
            'original_word' => $original_word,
            'commitment' => $commitment,
        ]);
        return $cnt;
    }



    public static function school_lool_original($school_name,$original_name)
    {

            //获取作品信息
            $data = self::select('original_class', 'original_name', 'original_author', 'school_name', 'original_howtime',
                'original_time', 'original_mp3', 'original_info', 'original_word', 'commitment','original_state','original_why')
                ->where('school_name', $school_name)
                ->where('original_name', '=', $original_name)
                ->get();
            return $data;

    }



    public static function rendering_original_info($school_name)
    {
        $data =self::select('school_name','original_name','id')
            ->where('school_name',$school_name)
            ->get();
        return $data;

    }

}


