<?php

namespace App\Http\Controllers\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use Log;

class ProductController extends Controller
{
	public function toCategory($value='')
	{
		Log::info("光伏产品类别");
		$categories = Category::whereNUll('parent_id')->get();
		return view('category')->with('categories',$categories);
	}

	public function toProduct($category_id)
	{
		$products = Product::where('category_id',$category_id)->get();
		return view('product')->with('products',$products);
	}
	public function toPdtContent(Request $request, $product_id)
	{
		$product = Product::find($product_id);                    //目前只有一个content
		//$pdt_content = PdtContent::where('product_id',$product_id)->first();
		$pdt_content = PdtContent::where('product_id', $product_id)->first();
		$pdt_images = PdtImages::where('product_id',$product_id)->get();

		$bk_cart = $request->cookie('bk_cart'); //获取出字符串，cookie只能存取字符串
		$bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());
		$count = 0;
		foreach ($bk_cart_arr as $value) { 
			$index = strpos($value, ':'); //引号的位置
			if(substr($value, 0, $index) == $product_id){
				$count = ((int)substr($value, $index+1));
				break;//找到产品后，跳出循环
			}
		}

		return view('pdt_content')->with('product',$product)
								  ->with('pdt_content',$pdt_content)
								  ->with('pdt_images',$pdt_images)
								  ->with('count',$count);

	}
}