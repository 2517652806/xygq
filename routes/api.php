<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::post('admin_delete','AdminController@admin_delete');//删除学校
Route::post('admin_reset','AdminController@admin_reset');//重置学校密码
Route::post('admin_state','AdminController@admin_state');//账号状态
Route::post('admin_add','AdminController@admin_add');//添加学校
Route::get('admin_rendering','AdminController@admin_rendering');//渲染学校信息
Route::post('admin_search','AdminController@admin_search'); //搜索学校
