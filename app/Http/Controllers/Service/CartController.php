<?php

namespace App\Http\Controllers\Service;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use Illuminate\Http\Request;//从cookie里面获取

class CartController extends Controller
{
	public function addCart(Request $request, $product_id)
	{
		$bk_cart = $request->cookie('bk_cart'); //获取出字符串，cookie只能存取字符串
		//echo 'cookie ' . $bk_cart;
		$bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());
		$count = 1;
		foreach ($bk_cart_arr as &$value) { //引用
			$index = strpos($value, ':'); //引号的位置
			if(substr($value, 0, $index) == $product_id){
				$count = ((int)substr($value, $index+1)) + 1;
				$value = $product_id . ':' . $count; //Concatenate strings:php->. JS->+
				//echo ' value is now ' . $value;
				break;//找到产品后，跳出循环
			}
		}
		//there is no match found, so this is a new product, so we add it into the array
		if($count == 1){
			array_push($bk_cart_arr, $product_id . ':' . $count);
		}

		$m3_result = new M3Result;
		$m3_result->status = 0;
		$m3_result->message = '填加购物车成功';

		return response($m3_result->toJson())->withCookie('bk_cart', implode(',', $bk_cart_arr));
	}
}