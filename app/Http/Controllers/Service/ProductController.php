<?php

namespace App\Http\Controllers\Service;
use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Models\M3Result;

class ProductController extends Controller
{
	public function getCategoryByParentId($parent_id)
	{
		$categories = Category::where('parent_id',$parent_id)->get();//以laravel形式查询的结果会自动显示Json类型

		$m3_result = new M3Result;
		$m3_result->status = 0;
		$m3_result->message = '返回成功';
		$m3_result->categories = $categories;//script语言(Js,php)随时定义成员变量

		return $m3_result->toJson();
	}
}