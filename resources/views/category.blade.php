@extends('master')
@section('title','产品分类')
@section('content')

	<div class="weui_cells_title">选择产品类别</div>
		<div class="weui_cells weui_cells_split">
		    <div class="weui_cell weui_cell_select">
		        <div class="weui_cell_bd weui_cell_primary">
		        	<!--SELECT一级选择器-->
		            <select class="weui_select" name="category">
		                @foreach($categories as $category)
		                  <option value="{{$category->id}}">{{$category->name}}</option> 
		                @endforeach
		            </select>
		        </div>
			</div>
		</div>

	<div class="weui_cells weui_cells_access">
	    <a class="weui_cell" href="javascript:;">
	        <div class="weui_cell_bd weui_cell_primary">
	            <p>cell standard</p>
	        </div>
	        <div class="weui_cell_ft"></div>
	    </a>
	    <a class="weui_cell" href="javascript:;">
	        <div class="weui_cell_bd weui_cell_primary">
	            <p>cell standard</p>
	        </div>
	        <div class="weui_cell_ft"></div>
	    </a>
	</div>

@endsection

@section('my-js')
<script type="text/javascript">

_getCategory();//刚进入页面的时候就监听
//点击选择也监听,一旦发生改变就change
$('.weui_select').change(function(event) {
  _getCategory()
});

//监听方法
function _getCategory() {
  var parent_id = $('.weui_select option:selected').val();
  console.log('parent_id: ' + parent_id);
  $.ajax({
    type: "GET",
    url: '/service/category/parent_id/' + parent_id,
    dataType: 'json',
    cache: false,
    success: function(data) {
    	//打印数据
      console.log("获取类别数据:");
      console.log(data);//不一定是string，有可能是object
      if(data == null) {
        $('.bk_toptips').show();
        $('.bk_toptips span').html('服务端错误');
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return;
      }
      if(data.status != 0) {
        $('.bk_toptips').show();
        $('.bk_toptips span').html(data.message);
        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
        return;
      }
      //console.log(data);
      $('.weui_cells_access').html('');//清空内容
      for(var i=0; i<data.categories.length; i++) {
        var next = '/product/category_id/' + data.categories[i].id;
        var node = '<a class="weui_cell" href= "'+ next +'">'
                		+'<div class="weui_cell_bd weui_cell_primary">'
                    		+'<p>'+  data.categories[i].name  +'</p>'
                		+'</div>'
                		+'<div class="weui_cell_ft">介绍与查询</div>'
            		+'</a>';
        $('.weui_cells_access').append(node);
      }
    },
    error: function(xhr, status, error) {
      console.log(xhr);
      console.log(status);
      console.log(error);
    }
  });
}


</script>
@endsection