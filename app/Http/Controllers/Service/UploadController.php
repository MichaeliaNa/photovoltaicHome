<?php namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tool\UUID;
use App\Models\M3Result;
use App\Entity\Category;

class UploadController extends Controller {

	/**
	 * @brief 上传文件
	 */
	 public function uploadFile(Request $request, $type)
	 {
	 	$width = $request->input("width", '');
		$height = $request->input("height", '');

		// $category_no = $request->input("category_no", '');
		// if($category_no != null){
		// 	$category = Category::where('category_no',$category_no)->first();
		// }else{
		// 	echo "@@@---null---@@@@";
		// }
			
		$m3_result = new M3Result();

		if( $_FILES["file"]["error"] > 0 )
		{
			$m3_result->status = 2;
			$m3_result->message = "未知错误, 错误码: " . $_FILES["file"]["error"];
			return $m3_result->toJson();
		}
		//文件大小不可超过1M
    	$file_size = $_FILES["file"]["size"];
		if ( $file_size > 1024*1024) {
			$m3_result->status = 2;
			$m3_result->message = "请注意图片上传大小不能超过1M";
			return $m3_result->toJson();
		}
		//把图片保存在一个文件夹里
		$public_dir = sprintf('/upload/%s/%s/', $type, date('Ymd') ); //按照时间计算文件夹名称
		$upload_dir = public_path() . $public_dir;
		if( !file_exists($upload_dir) ) {
      		mkdir($upload_dir, 0777, true); //User <username>
      		//var_dump($upload_dir);
    	}
    	
		// 获取文件扩展名
		$arr_ext = explode('.', $_FILES["file"]['name']);
		$file_ext = count($arr_ext) > 1 && strlen( end($arr_ext) ) ? end($arr_ext) : "unknow";
		// 合成上传目标文件名
		$upload_filename = UUID::create();
		$upload_file_path = $upload_dir . $upload_filename . '.' . $file_ext;
		if (strlen($width) > 0) {
			$public_uri = $public_dir . $upload_filename . '.' . $file_ext;
			$m3_result->status = 0;
			$m3_result->message = "上传成功1";
			$m3_result->uri = $public_uri; //返回原页面位置的src

			// $category->preview = $public_uri;
			// $category->save();
		} else {
			// 从临时目标移到上传目录
			if( move_uploaded_file($_FILES["file"]["tmp_name"], $upload_file_path) )
			{
				$public_uri = $public_dir . $upload_filename . '.' . $file_ext;

				$m3_result->status = 0;
				$m3_result->message = "上传成功2";
				$m3_result->uri = $public_uri;

				// $category->preview = $public_uri;
				// $category->save();
			}
			else
			{
				$m3_result->status = 1;
				$m3_result->message = "上传失败, 权限不足";
			}
		}

		return $m3_result->toJson();
	 }
}
