<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //密码加密
    protected function adminhandle($request)
    {
        $upregisteredInofo = bcrypt($request);
        return $upregisteredInofo;
    }


    //删除学校
    public static function admin_delete(Request $request){
        $school_name = $request['school_name'];
        $res = Admin::admin_delete($school_name);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!",null,100);
    }
    //重置学校密码
    public static function admin_reset(Request $request){
        $school_name = $request['school_name'];
        $school_password = '123456';
        $password = (new AdminController)->adminhandle($school_password);
        $res = Admin::admin_reset($password,$school_name);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!",null,100);
    }

    //账号状态
    public static function admin_state(Request $request){
        $school_name = $request['school_name'];
        $res = Admin::admin_state($school_name);
        return $res?
            json_success("操作成功!",null,200):
            json_fail("操作失败!",null,100);
    }

    //添加学校
    public static function admin_add(Request $request){
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
