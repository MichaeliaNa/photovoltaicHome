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
	Route::post('/order_commit', 'View\OrderController@toOrderCommit');
	Route::get('/order_list', 'View\OrderController@toOrderList');
});

// service/request=>post
Route::group(['prefix' => 'service'],function(){
	Route::get('validate_code/create', 'Service\ValidateController@create');
	Route::post('validate_phone/send', 'Service\ValidateController@sendSMS');
  Route::post('upload/{type}','Service\UploadController@uploadFile');

	Route::post('register', 'Service\MemberController@register');
	Route::post('login', 'Service\MemberController@login');

	Route::get('category/parent_id/{parent_id}','Service\ProductController@getCategoryByParentId');//{}->controller的输入参数
	Route::get('cart/add/{product_id}', 'Service\CartController@addCart');
	Route::get('cart/delete', 'Service\CartController@deleteCart');
	Route::post('/alipay', 'Service\PayController@alipay');
	Route::post('/pay/ali_notify', 'Service\PayController@aliNotify');
	Route::get('/pay/ali_result', 'Service\PayController@aliResult');
	Route::get('/pay/ali_merchant', 'Service\PayController@aliMerchant');
});

/***********************************后台相关***********************************/

Route::group(['prefix' => 'admin'], function () {

  Route::get('login', 'Admin\IndexController@toLogin');
  Route::get('exit', 'Admin\IndexController@toExit');
  Route::post('service/login', 'Admin\IndexController@login');
  Route::get('index', 'Admin\IndexController@toIndex');

  //Route::group(['middleware' => 'check.admin.login'], function () {

    Route::group(['prefix' => 'service'], function () {
      Route::post('category/add', 'Admin\CategoryController@categoryAdd');
      Route::post('category/del', 'Admin\CategoryController@categoryDel');
      Route::post('category/edit', 'Admin\CategoryController@categoryEdit');

      Route::post('product/add', 'Admin\ProductController@productAdd');
      Route::post('product/del', 'Admin\ProductController@productDel');
      Route::post('product/edit', 'Admin\ProductController@productEdit');

      Route::post('member/edit', 'Admin\MemberController@memberEdit');

      Route::post('order/edit', 'Admin\OrderController@orderEdit');
    });


    Route::get('category', 'Admin\CategoryController@toCategory');
    Route::get('category_add', 'Admin\CategoryController@toCategoryAdd');
    Route::get('category_edit', 'Admin\CategoryController@toCategoryEdit');

    Route::get('product', 'Admin\ProductController@toProduct');
    Route::get('product_info', 'Admin\ProductController@toProductInfo');
    Route::get('product_add', 'Admin\ProductController@toProductAdd');
    Route::get('product_edit', 'Admin\ProductController@toProductEdit');

    Route::get('member', 'Admin\MemberController@toMember');
    Route::get('member_edit', 'Admin\MemberController@toMemberEdit');

    Route::get('order', 'Admin\OrderController@toOrder');
    Route::get('order_edit', 'Admin\OrderController@toOrderEdit');
  //});
});
