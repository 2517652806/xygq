<?php

namespace App\Http\Controllers;


use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\SchoolNameRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * 注册
     * @param Request $registeredRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function registered(AdminLoginRequest $registeredRequest)
    {
        $count = Admin::checknumber($registeredRequest);   //检测账号密码是否存在
        if($count == 0)
        {
            $student_id = Admin::createUser(self::userHandle($registeredRequest));
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
    public function login(AdminLoginRequest $request)
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
        $token = auth('admin')->attempt($credentials);   //获取token
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
        return ['admin_account' => $request['admin_account'], 'password' => $request['password']];
    }

    protected function userHandle($request)   //对密码进行哈希256加密
    {
        $registeredInfo = $request->except('password_confirmation');
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        return $registeredInfo;
    }
    //密码加密
    protected function adminhandle($request)
    {
        $upregisteredInofo = bcrypt($request);
        return $upregisteredInofo;
    }


    //删除学校
    public static function admin_delete(SchoolNameRequest $request){
        $school_name = $request['school_name'];
        $res = Admin::admin_delete($school_name);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!",null,100);
    }
    //重置学校密码
    public static function admin_reset(SchoolNameRequest $request){
        $school_name = $request['school_name'];
        $school_password = '123456';
        $password = (new AdminController)->adminhandle($school_password);
        $res = Admin::admin_reset($password,$school_name);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!",null,100);
    }

    //账号状态
    public static function admin_state(SchoolNameRequest $request){
        $school_name = $request['school_name'];
        $res = Admin::admin_state($school_name);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!",null,100);
    }

    //添加学校
    public static function admin_add(SchoolNameRequest $request){
        $school_name = $request['school_name'];
        $school_password = '123456';
        $password = (new AdminController)->adminhandle($school_password);
        $res = Admin::admin_add($school_name,$password);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!,学校已经添加过了",null,100);

    }


    //渲染学校信息
    public static function admin_rendering(){
        $res= Admin::admin_rendering();
        return $res?
            json_success("操作成功!",$res,200):
            json_fail("操作失败!",null,100);

    }


    //搜索学校
    public static function admin_search(Request $request){
        $school_name = $request['school_name'];
        $res = Admin::admin_search($school_name);
        return $res?
            json_success("操作成功!",$res,200):
            json_fail("操作失败!",null,100);
    }
}
