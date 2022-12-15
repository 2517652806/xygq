<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('registered','SchoolController@registered');//注册
Route::post('school_login','SchoolController@login');//学校端登录
Route::post('admin_login','AdminController@login');//管理员登录
Route::post('login_province','ProvinceController@login');//管理员登录
Route::post('login_city','CityController@login');//管理员登录
Route::post('school_password','SchoolController@updatePassword')->middleware('jwt.role:user','jwt.auth');//学校端修改密码
Route::post('admin_modify_account','SchoolController@updateSchool');//学校端修改学校账号
