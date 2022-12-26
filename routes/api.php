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

Route::post('get_all_audit_trail', 'ProvinceController@get_all_audit_trail');//获取所有学校的审核记录
Route::post('audit_trail_by_schoolname', 'ProvinceController@audit_trail_by_schoolname');//省/市级端通过学校名查询审核信息


Route::get('school_look_singsong', 'SchoolController@school_look_singsong')->middleware('jwt.role:user','jwt.auth');//查看传唱歌曲信息
Route::post('school_look_original', 'SchoolController@school_look_original')->middleware('jwt.role:user','jwt.auth');//查看原创歌曲信息
Route::post('/get_all_song','ProvinceController@getAllSong');//省/市级端获取传唱所有节目
Route::post('/get_song_by_schoolname','ProvinceController@getSongBySchoolName');//省/市级端通过学校名获取传唱所有节目
Route::post('/get_song_info','ProvinceController@getSongInfo');//获取节目审批详情
Route::post('/songs_overrule','ProvinceController@songsOverrule');//节目批量驳回
Route::post('/song_overrule','ProvinceController@songOverrule');//节目驳回
Route::post('/song_pass','ProvinceController@songPass');//节目通过



Route::post('admin_delete','AdminController@admin_delete');//删除学校
Route::post('admin_reset','AdminController@admin_reset');//重置学校密码
Route::post('admin_state','AdminController@admin_state');//账号状态
Route::post('admin_add','AdminController@admin_add');//添加学校
Route::get('admin_rendering','AdminController@admin_rendering');//渲染学校信息
Route::post('admin_search','AdminController@admin_search'); //搜索学校
Route::post('registered','SchoolController@registered');//注册
Route::post('school_login','SchoolController@login');//学校端登录
Route::post('admin_login','AdminController@login');//超级管理员登录
Route::post('login_province','ProvinceController@login');//省级端登录
Route::post('login_city','CityController@login');//市级端登录
Route::post('school_password','SchoolController@updatePassword')->middleware('jwt.role:user','jwt.auth');//学校端修改密码
Route::post('admin_modify_account','SchoolController@updateSchool');//学校端修改学校账号
Route::post('school_singsong','SchoolController@school_singsong')->middleware('jwt.role:user','jwt.auth');//填报传唱信息
Route::post('add_school_original','SchoolController@add_school_original')->middleware('jwt.role:user','jwt.auth');//填报原创信息
Route::post('change_school_original','SchoolController@change_school_original')->middleware('jwt.role:user','jwt.auth');//修改原创信息
Route::post('school_singsong_change','SchoolController@school_singsong_change')->middleware('jwt.role:user','jwt.auth');//修改传唱信息

Route::post('upload','SchoolController@upload');//测试OSS
Route::get('rendering_original_info','SchoolController@rendering_original_info');//渲染原创歌曲信息
Route::get('rendering_singsong_info','SchoolController@rendering_singsong_info');//渲染传唱歌曲信息

Route::get('school_already_singsong','SchoolController@school_already_singsong');//判断是否可以填表传唱歌曲
Route::get('school_already_original','SchoolController@school_already_original');//判断是否可以填表原创歌曲
