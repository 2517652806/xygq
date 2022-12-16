<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Singsong extends Model
{
    //
    // 指定数据表
    protected $table = "singsong";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];


    public static function get_trail($state)
    {
        try {
            if ($state == '0')
            {
                $res = self::where('singsong_state','<=','2')
                    ->select('school_name','id','updated_at','singsong_state')
                    ->get();
                return $res;
            }
            if($state == '1')
            {
                $res = self::where('singsong_state','>','2')
                    ->select('school_name','id','updated_at','singsong_state')
                    ->get();
                return $res;
            }
        }catch (\Exception $e) {
            logError('查询学校的审核记录失败！', [$e->getMessage()]);
            return false;
        }

    }



    public static function select_trail($state,$school_name)
    {
        try {
            if ($state == '0')
            {
                $res = self::orwhere('school_name','like','%'.$school_name.'%')
                    ->where('singsong_state','<=','2')
                    ->select('school_name','id','updated_at','singsong_state')
                    ->get();
                return $res;
            }
            if($state == '1')
            {
                $res = self::orwhere('school_name','like','%'.$school_name.'%')
                    ->where('singsong_state','>','2')
                    ->select('school_name','id','updated_at','singsong_state')
                    ->get();
                return $res;
            }
        }catch (\Exception $e) {
            logError('查询学校的审核记录失败！', [$e->getMessage()]);
            return false;
        }

    }



    public static function select_singsong_info($school_name)
    {
        $res = self::where('school_name',$school_name)
            ->select()
            ->get();
        return $res;
    }

    /**
     * 省/市级端获取传唱所有节目
     * @param $state
     * @return false
     */
    public static function getAllSong($state)
    {
        try {
            $data = self::select('school_name', 'id', 'singsong_name')
                ->where('singsong_state', $state)
                ->get();
            return $data;
        } catch (\Exception $e) {
            logError('获取节目列表失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 省/市级端根据学校名查询传唱节目
     * @param $state
     * @param $schoolName
     * @return false
     */
    public static function getSongBySchoolName($state, $schoolName)
    {
        try {
            $data = self::select('school_name', 'id', 'singsong_name')
                ->where('school_name', 'like', '%' . $schoolName . '%')
                ->where('singsong_state', '=', $state)
                ->get();
            return $data;
        } catch (\Exception $e) {
            logError('获取节目列表失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 省/市级端获取节目审批详情
     * @param $state
     * @param $id
     * @return false
     *
     */

    public static function getSongInfo($state, $id)
    {
        try {
            $data = self::select('singsong_name', 'school_name', 'singsong_author', 'singsong_url', 'singsong_howtime', 'singsong_time')
                ->where('id', $id)
                ->where('singsong_state', '=', $state)
                ->get();
            return $data;
        } catch (\Exception $e) {
            logError('获取节目列表失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 批量驳回
     * @param $state
     * @param $id
     * @return false|int
     */
    public static function songsOverrule($state, $id)
    {
        try {
            $id = array($id);
            $num = count($id);
            for ($i = 0; $i < $num; ++$i) {
                self::where('id', $id[$i])
                    ->update([
                        'singsong_state'=> $state
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
     * @return false|int
     */
    public static function songOverrule($state, $id,$why)
    {
        try {
                $data = self::where('id', $id)
                    ->update([
                        'singsong_state'=> $state,
                        'singsong_why'=>$why
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
                    'singsong_state'=> $state
                ]);
            return $data;
        } catch (\Exception $e) {
            logError('操作失败！', [$e->getMessage()]);
            return false;
        }
    }


    public static function singsong_update($school_name,$singsong_name,$singsong_howtime,$singsong_time,$singsong_author,$data)
    {
        $cnt = self::where('school_name', $school_name)
            ->update([
                'singsong_name' => $singsong_name,
                'singsong_howtime' => $singsong_howtime,
                'singsong_time' => $singsong_time,
                'singsong_author' => $singsong_author,
                'singsong_url' => $data,
            ]);
        return $cnt;
    }

    public static function singsong_create($school_name,$singsong_name,$singsong_howtime,$singsong_time,$singsong_author,$data)
    {
        $cnt = self::where('school_name', $school_name)
            ->create([
                'school_name' => $school_name,
                'singsong_name' => $singsong_name,
                'singsong_howtime' => $singsong_howtime,
                'singsong_time' => $singsong_time,
                'singsong_author' => $singsong_author,
                'singsong_url' => $data,
            ]);
        return $cnt;
    }
}
