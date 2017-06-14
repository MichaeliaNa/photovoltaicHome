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
Route::get('/product/category_id/{category_id}', 'View\ProductController@toProduct');
Route::get('/product/{product_id}','View\ProductController@toPdtContent');
Route::get('/register', 'View\MemberController@toRegister');
Route::get('/cart','View\CartController@toCart');
Route::get('/category','View\ProductController@toCategory');
Route::get('/pay',function(){
	return view('alipay');
});

Route::group(['middleware' => 'check.login'],function(){
	Route::get('/order_commit/{product_ids}', 'View\OrderController@toOrderCommit');
	Route::get('/order_list', 'View\OrderController@toOrderList');
});

// service/request=>post
Route::group(['prefix' => 'service'],function(){
	Route::get('validate_code/create', 'Service\ValidateController@create');
	Route::post('validate_phone/send', 'Service\ValidateController@sendSMS');
	Route::post('register', 'Service\MemberController@register');
	Route::post('login', 'Service\MemberController@login');

	Route::get('category/parent_id/{parent_id}','Service\ProductController@getCategoryByParentId');//{}->controller的输入参数
	Route::get('cart/add/{product_id}', 'Service\CartController@addCart');
	Route::get('cart/delete', 'Service\CartController@deleteCart');
	Route::post('/pay', 'Service\PayController@alipay');
	Route::post('/pay/notify', 'Service\PayController@notify');
});
