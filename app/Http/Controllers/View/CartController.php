<?php

namespace App\Http\Controllers\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\CartItem;
use App\Models\M3Result;
use App\Entity\Product;

class CartController extends Controller
{
	public function toCart(Request $request)
	{
		$cart_items = array();

		$bk_cart = $request->cookie('bk_cart'); 
		$bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());
		//产看用户是否登陆
		$member = $request->session()->get('member', '');
	    if($member != '') {
	    	//在数据库操作(同步)购物车
	      $cart_items = $this->syncCart($member->id, $bk_cart_arr);
	      return response()->view('cart', ['cart_items' => $cart_items])->withCookie('bk_cart', null);
	    }
		//所有被加入购物车的产品
		foreach ($bk_cart_arr as $key => $value) { 
			$index = strpos($value, ':'); 
			$cart_item = new CartItem;
			$cart_item->id = $key;
			$cart_item->product_id = substr($value, 0, $index);
			$cart_item->count = (int)substr($value, $index+1);
			$cart_item->product = Product::find($cart_item->product_id);
			if($cart_item->product != null){//用户有可能删除购物车中的物品
				array_push($cart_items, $cart_item);
			}

		}
		return view('cart')->with('cart_items',$cart_items);
	}

	  private function syncCart($member_id, $bk_cart_arr)
	  {
	    $cart_items = CartItem::where('member_id', $member_id)->get();

	    $cart_items_arr = array();
	    //处理cookie中的购物车商品
	    foreach ($bk_cart_arr as $value) {
	      $index = strpos($value, ':');
	      $product_id = substr($value, 0, $index);
	      $count = (int) substr($value, $index+1);

	      // 判断离线购物车(cookie)中product_id 是否存在 数据库中
	      $exist = false;
	      foreach ($cart_items as $temp) {
	        if($temp->product_id == $product_id) {
	          if($temp->count < $count) {//无论是cookie还是数据库，选择商品数量多的进行更新
	            $temp->count = $count;
	            $temp->save();
	          }
	          $exist = true;
	          break;
	        }
	      }

	      // 不存在则存储进来
	      if($exist == false) {
	        $cart_item = new CartItem;
	        $cart_item->member_id = $member_id;
	        $cart_item->product_id = $product_id;
	        $cart_item->count = $count;
	        $cart_item->save();
	        $cart_item->product = Product::find($cart_item->product_id);//加一个属性
	        array_push($cart_items_arr, $cart_item);
	      }
	    }

	    // 为每个对象附加产品对象便于显示
	    foreach ($cart_items as $cart_item) {
	      $cart_item->product = Product::find($cart_item->product_id); //购物车里的商品都加一个属性
	      array_push($cart_items_arr, $cart_item);
	    }

	    return $cart_items_arr;
	  }
}