<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\Entity\Member;

Route::get('/', function () {
    return view('login');
});

// view => get
Route::get('/login', 'View\MemberController@toLogin');
Route::get('/register', 'View\MemberController@toRegister');
Route::get('/category','View\ProductController@toCategory');
Route::get('/product/category_id/{category_id}', 'View\ProductController@toProduct');
Route::get('/product/{product_id}','View\ProductController@toPdtContent');

// service/request=>post
Route::group(['prefix' => 'service'],function(){
	Route::get('validate_code/create', 'Service\ValidateController@create');
	Route::post('validate_phone/send', 'Service\ValidateController@sendSMS');
	Route::post('register', 'Service\MemberController@register');
	Route::post('login', 'Service\MemberController@login');

	Route::get('category/parent_id/{parent_id}','Service\ProductController@getCategoryByParentId');//{}->controller的输入参数
});
