<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * 注册
     * @param Request $registeredRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function registered(Request $registeredRequest)
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
        $token = auth('api')->attempt($credentials);   //获取token
//        if(!$token){
//            return response()->json(['error' => 'Unauthorized'],401);
//        }
//        return self::respondWithToken($token, '登录成功!');   //可选择返回方式
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
        $res = School::updateSchool($school_name,$new_schoolName);
        return $res?
            json_success('修改成功!',$res,  200):
            json_fail('登录失败!账号或密码错误',null, 100 ) ;
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
}
