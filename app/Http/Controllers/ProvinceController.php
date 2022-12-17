<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvinceRequest1;
use App\Http\Requests\ProvinceRequest2;
use App\Http\Requests\ProvinceRequest3;
use App\Http\Requests\ProvinceRequest4;
use App\Models\Original;
use App\Models\Singsong;
use App\Models\Province;
use Illuminate\Http\Request;



class ProvinceController extends Controller
{

    /*
     * 省/市级端获取所有审核记录
     */
    public function get_all_audit_trail(ProvinceRequest1 $request)
    {
        $table_name = $request['table_name'];
        $state = $request['state'];
        if($table_name == 'original')
        {
            $res = Original::get_trail($state);
            return $res ?
                json_success('获取成功', $res, 200) :
                json_fail('获取失败', null, 100);
        }
        if($table_name == 'singsong')
        {
            $res = Singsong::get_trail($state);
            return $res ?
                json_success('获取成功', $res, 200) :
                json_fail('获取失败', null, 100);
        }
    }


    /*
     * 省/市级端通过学校名查询审核信息
     */
    public function audit_trail_by_schoolname(ProvinceRequest2 $request)
    {
        $table_name = $request['table_name'];
        $state = $request['state'];
        $school_name = $request['school_name'];
        if($table_name == 'original')
        {
            $res = Original::select_trail($state,$school_name);
            return $res ?
                json_success('获取成功', $res, 200) :
                json_fail('获取失败', null, 100);
        }
        if($table_name == 'singsong')
        {
            $res = Singsong::select_trail($state,$school_name);
            return $res ?
                json_success('获取成功', $res, 200) :
                json_fail('获取失败', null, 100);
        }
    }
    /**
     * 注册
     * @param Request $registeredRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function registered(Request $registeredRequest)
    {
        $count = Province::checknumber($registeredRequest);   //检测账号密码是否存在
        if($count == 0)
        {
            $student_id = Province::createUser(self::userHandle($registeredRequest));
            return  $student_id ?
                json_success('注册成功!',$student_id,200  ) :
                json_fail('注册失败!',null,100  ) ;
        }
        else{
            return
                json_success('注册失败!该工号已经注册过了！',null,100  ) ;
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = self::credentials($request);   //从前端获取账号密码
        //以手机号登录测试，具体根据自己的业务逻辑
        //    $user = DB::table('users')->first();
        /*   if(!$user){
              $user = new UsersModel();
              $user->phone = $phone;
              $user->save();
          }*/
        //方式一
        // $token = JWTAuth::fromUser($user);
        //方式二
        $token = auth('province')->attempt($credentials);   //获取token
//        if(!$token){
//            return response()->json(['error' => 'Unauthorized'],401);
//        }
//        return self::respondWithToken($token, '登录成功!');   //可选择返回方式
        return $token?
            json_success('登录成功!',$token,  200):
            json_fail('登录失败!账号或密码错误',null, 100 ) ;
        //       json_success('登录成功!',$this->respondWithToken($token,$user),  200);
    }

    //封装token的返回方式
    protected function respondWithToken($token, $msg)
    {
        // $data = Auth::user();
        return json_success( $msg, array(
            'token' => $token,
            //设置权限  'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ),200);
    }
    protected function credentials($request)   //从前端获取账号密码
    {
        return ['province_account' => $request['province_account'], 'password' => $request['password']];
    }

    protected function userHandle($request)   //对密码进行哈希256加密
    {
        $registeredInfo = $request->except('password_confirmation');
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        return $registeredInfo;
    }
    //
    /**
     * 省/市级端获取所有节目
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getAllSong(ProvinceRequest1 $request)
    {
        $table =  $request['table_name'];
        $state = $request['state'];
        if ($table == 'original')
        {
            $data = Original::getAllSong($state);
            return $data ?
                json_success('获取成功!', $data, 200) :
                json_fail('获取失败!', null, 100);
        }
        if ($table == 'singsong')
        {
            $data = Singsong::getAllSong($state);
            return $data ?
                json_success('获取成功!', $data, 200) :
                json_fail('获取失败!', null, 100);
        }
    }


    /**
     * 省/市级端根据学校名查询节目
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getSongBySchoolName(ProvinceRequest2 $request)
    {
        $table =  $request['table_name'];
        $state = $request['state'];
        $schoolName = $request['school_name'];
        if ($table == 'original')
        {
            $data = Original::getSongBySchoolName($state,$schoolName);
            return $data ?
                json_success('获取成功!', $data, 200) :
                json_fail('获取失败!', null, 100);
        }
        if ($table == 'singsong')
        {
            $data = Singsong::getSongBySchoolName($state,$schoolName);
            return $data ?
                json_success('获取成功!', $data, 200) :
                json_fail('获取失败!', null, 100);
        }
    }

    /**
     * 省/市级端获取节目审批详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getSongInfo(ProvinceRequest3 $request)
    {
        $table =  $request['table_name'];
        $state = $request['state'];
        $id = $request['id'];

        if ($table == 'original')
        {
            $data = Original::getSongInfo($state,$id);
            return $data ?
                json_success('获取成功!', $data, 200) :
                json_fail('获取失败!', null, 100);
        }
        if ($table == 'singsong')
        {
            $data = Singsong::getSongInfo($state,$id);
            return $data ?
                json_success('获取成功!', $data, 200) :
                json_fail('获取失败!', null, 100);
        }
    }


    /**
     * 批量驳回
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */

    public function songsOverrule(ProvinceRequest3 $request)
    {
        $table =  $request['table_name'];
        $state = $request['state'];
        $id = $request['id'];

        if ($table == 'original')
        {
            $data = Original::songsOverrule($state,$id);
            return $data ?
                json_success('操作成功!', $data, 200) :
                json_fail('操作失败!', null, 100);
        }
        if ($table == 'singsong')
        {
            $data = Singsong::songsOverrule($state,$id);
            return $data ?
                json_success('操作成功!', $data, 200) :
                json_fail('操作失败!', null, 100);
        }
    }



    /**
     * 单个作品驳回
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */

    public function songOverrule(ProvinceRequest4 $request)
    {
        $table =  $request['table_name'];
        $state = $request['state'];
        $id = $request['id'];
        $why = $request['why'];
        if ($table == 'original')
        {
            $data = Original::songOverrule($state,$id,$why);
            return $data ?
                json_success('操作成功!', $data, 200) :
                json_fail('操作失败!', null, 100);
        }
        if ($table == 'singsong')
        {
            $data = Singsong::songOverrule($state,$id,$why);
            return $data ?
                json_success('操作成功!', $data, 200) :
                json_fail('操作失败!', null, 100);
        }
    }




    /**
     * 作品通过
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */

    public function songPass(ProvinceRequest3 $request)
    {
        $table =  $request['table_name'];
        $state = $request['state'];
        $id = $request['id'];
        if ($table == 'original')
        {
            $data = Original::songPass($state,$id);
            return $data ?
                json_success('操作成功!', $data, 200) :
                json_fail('操作失败!', null, 100);
        }
        if ($table == 'singsong')
        {
            $data = Singsong::songPass($state,$id);
            return $data ?
                json_success('操作成功!', $data, 200) :
                json_fail('操作失败!', null, 100);
        }
    }
}
