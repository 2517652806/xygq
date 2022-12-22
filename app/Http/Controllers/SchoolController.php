<?php

namespace App\Http\Controllers;

use App\Http\Requests\School2;
use App\Http\Requests\SchoolLoginRequest;
use App\Models\Original;
use App\Models\OriginalManage;
use App\Models\OriginalSong;
use App\Models\OriginalWord;
use App\Models\Singsong;
use App\Models\Admin;
use App\Models\School;
use App\services\OSS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    /**
     * 注册
     * @param Request $registeredRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function registered(SchoolLoginRequest $registeredRequest)
    {
        $count = School::checknumber($registeredRequest);   //检测账号密码是否存在
        if($count == 0)
        {
            $student_id = School::createUser(self::userHandle($registeredRequest));
            return  $student_id ?
                json_success('注册成功!',$student_id,200  ) :
                json_fail('注册失败!',null,100  ) ;
        }
        else{
            return
                json_success('注册失败!该工号已经注册过了！',null,100  ) ;
        }
    }

    public function school_look_singsong(Request $request)
    {
        $school_name = auth('api')->user()->school_name;
        $res = Singsong::select_singsong_info($school_name);
        return $res ?
            json_success('获取成功', $res, 200) :
            json_fail('获取失败', null, 100);
    }

    /**
     * 查看原创歌曲信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function school_look_original(Request $request)
    {
        $school_name = auth('api')->user()->school_name;
        $original_name = $request['original_name'];
        $data1 = Original::school_lool_original($school_name,$original_name);
        $data2 = OriginalManage::school_lool_original($school_name,$original_name);
        $data3 = OriginalSong::school_lool_original($school_name,$original_name);
        $data4 =OriginalWord::school_lool_original($school_name,$original_name);
        $data1 = array($data1);
        $data2 = array($data2);
        $data3 = array($data3);
        $data4 = array($data4);
        $data = array_merge($data1,$data2,$data3,$data4);
        return $data ?
            json_success('获取成功', $data, 200) :
            json_fail('获取失败', null, 100);
    }


    /**
     *  填报传唱信息
     * @param School $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function school_singsong(\App\Http\Requests\School $request)
    {
        $school_name = auth('api')->user()->school_name;
        $school_name1 = $request['school_name'];
        if($school_name !== $school_name1)
        {
            return json_fail('输入的学校名字错误',null, 100 ) ;
        }
        $cot = DB::table('singsong')->where('school_name',$school_name)->count();
        if ($cot == 0)
        {
            $singsong_name = $request['singsong_name'];
            $singsong_howtime = $request['singsong_howtime'];
            $singsong_time = $request['singsong_time'];
            $singsong_author= $request['singsong_author'];
            $singsong_url = $request['singsong_url'];
            $res = Singsong::singsong_create($school_name,$singsong_name,
                $singsong_howtime,$singsong_time,$singsong_author,$singsong_url);
            return $res?
                json_success('填报成功!',$res,  200):
                json_fail('填报失败',null, 100 ) ;
        } else
        {
            return  json_fail('传唱作品只能上传一个',null, 100 ) ;
        }
    }

    /**
     * 传唱歌曲修改
     * @param \App\Http\Requests\School $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function school_singsong_change(\App\Http\Requests\School $request)
    {
        $school_name = auth('api')->user()->school_name;
        $school_name1 = $request['school_name'];
        if($school_name !== $school_name1)
        {
            return json_fail('输入的学校名字错误',null, 100 ) ;
        }
        $singsong_name = $request['singsong_name'];
        $singsong_howtime = $request['singsong_howtime'];
        $singsong_time = $request['singsong_time'];
        $singsong_author= $request['singsong_author'];
        $singsong_url = $request['singsong_url'];
        $res = Singsong::singsong_update($school_name,$singsong_name,
            $singsong_howtime,$singsong_time,$singsong_author,$singsong_url);
        return $res?
            json_success('修改成功!',$res,  200):
            json_fail('修改失败',null, 100 ) ;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(SchoolLoginRequest $request)
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
        $token = auth('api')->attempt($credentials);   //获取token
//        if(!$token){
//            return response()->json(['error' => 'Unauthorized'],401);
//        }
//        return self::respondWithToken($token, '登录成功!');   //可选择返回方式
        $school_name = $request['school_name'];
        $school_state = DB::table('school')
            ->where('school_name','=',$school_name)
            ->value('school_state');
        if ($school_state == 0)
        {
            return json_fail('该账号被禁用',null, 101 ) ;
        }
        return $token?
            json_success('登录成功!',$token,  200):
            json_fail('登录失败!账号或密码错误',null, 100 ) ;
        //       json_success('登录成功!',$this->respondWithToken($token,$user),  200);
    }
    /**
     * 修改密码
     */
    public function updatePassword(Request $request){
        $school_name = auth('api')->user()->school_name;
        $password = $request['password'];
        $password1 = self::userHandle111($password);
        $res = School::updatePassword($school_name,$password1);
        return $res?
            json_success('修改成功!',$res,  200):
            json_fail('登录失败!账号或密码错误',null, 100 ) ;
    }
    /**
     * 修改密码时从新加密
     */
    public function userHandle111($password)   //对密码进行哈希256加密
    {
        $red = bcrypt($password);
        return $red;
    }
    /**
     * 修改学校名称
     */
    public function updateSchool(Request $request){
        $school_name = $request['school_name'];
        $new_schoolName = $request['new_schoolName'];
        $count = School::checknumberNew($request);   //检测账号密码是否存在
        if($count == 0){
            $res = School::updateSchool($school_name,$new_schoolName);
            return $res?
                json_success('修改成功!',$res,  200):
                json_fail('登录失败!账号或密码错误',null, 100 ) ;
        }else{
            return json_success('注册失败!该工号已经注册过了！',null,100  ) ;
        }
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
        return ['school_name' => $request['school_name'], 'password' => $request['password']];
    }

    protected function userHandle($request)   //对密码进行哈希256加密
    {
        $registeredInfo = $request->except('password_confirmation');
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        return $registeredInfo;
    }
    /**
     * 填报原创信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_school_original(School2 $request)
    {
        $school_name = auth('api')->user()->school_name;
        $cot = DB::table('original')->where('school_name',$school_name)->count();
        if ($cot !== 2)
        {
            $original_name = $request['original_name'];
            $original_howtime = $request['original_howtime'];
            $original_class = $request['original_class'];
            $original_time= $request['original_time'];
            $original_author = $request['original_author'];
            $original_info = $request['original_info'];
            $original_mp3 = $request['original_mp3'];
            $original_word = $request['original_word'];
            $commitment = $request['commitment'];
            $manage= $request['manage'];
            $song = $request['song'];
            $word= $request['word'];
            $res1 = Original::original_add($school_name,$original_name,$original_howtime,$original_class
            ,$original_time,$original_author,$original_info,$original_mp3,$original_word,$commitment);
            $res2 = OriginalManage::original_add($school_name,$manage,$original_name);
            $res3 = OriginalSong::original_add($school_name,$song,$original_name);
            $res4 = OriginalWord::original_add($school_name,$word,$original_name);
            return $res1?
                json_success('填报成功!',$res1,  200):
                json_fail('填报失败',null, 100 ) ;
        }else
        {
            return  json_fail('原创作品只能上传两个',null, 100 ) ;
        }
    }

    /**
     * 修改原创填报信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_school_original(School2 $request)
    {
        $school_name = auth('api')->user()->school_name;
            $original_name = $request['original_name'];
            $original_howtime = $request['original_howtime'];
            $original_class = $request['original_class'];
            $original_time= $request['original_time'];
            $original_author = $request['original_author'];
            $original_info = $request['original_info'];
            $original_mp3 = $request['original_mp3'];
            $original_word = $request['original_word'];
            $commitment = $request['commitment'];
            $manage= $request['manage'];
            $song = $request['song'];
            $word= $request['word'];
            $res1 = Original::original_change($school_name,$original_name,$original_howtime,$original_class
                ,$original_time,$original_author,$original_info,$original_mp3,$original_word,$commitment);
            $res2 = OriginalManage::original_change($school_name,$manage,$original_name);
            $res3 = OriginalSong::original_change($school_name,$song,$original_name);
            $res4 = OriginalWord::original_change($school_name,$word,$original_name);
            return $res1?
                json_success('修改成功!',$res1,  200):
                json_fail('修改失败',null, 100 ) ;
    }



    /**
     * OSS
     * @param $file
     * @return string
     */
    public function upload(Request $request){
        $file = $request->file('file');//读取file文件
        $tmppath = $file->getRealPath();//获取文件的真实路径
        $fileName = rand(1000,9999).$file->getFilename().time().date('ymd').'.'.$file->getClientOriginalExtension();
        //拼接文件名
        $pathName = date('Y-m/d').'/'.$fileName;
        OSS::publicUpload('zhouyangtest',$pathName,$tmppath,['ContentType'=>'inline']);
        //获取文件URl
        $url  =OSS::getPublicObjectURL('zhouyangtest',$pathName);
        return $url?
            json_success('上传成功!',$url,  200):
            json_fail('上传失败',null, 100 );
    }


    /**
     * 渲染原创歌曲信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rendering_original_info(Request $request)
    {
        $school_name = auth('api')->user()->school_name;
        $res = Original::rendering_original_info($school_name);
        return $res?
            json_success('渲染成功!',$res,  200):
            json_fail('渲染失败',null, 100 ) ;
    }

    public function rendering_singsong_info(Request $request)
    {
        $school_name = auth('api')->user()->school_name;
        $res = Singsong::rendering_singsong_info($school_name);
        return $res?
            json_success('渲染成功!',$res,  200):
            json_fail('渲染失败',null, 100 ) ;
    }
}
