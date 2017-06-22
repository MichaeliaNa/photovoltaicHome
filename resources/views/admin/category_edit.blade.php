@extends('admin.master')

@section('content')
<form action="/admin/service/category/edit" method="post" class="form form-horizontal" id="form-category-edit">
  {{ csrf_field() }}
  <div class="row cl">
    <label class="form-label col-3"><span class="c-red">*</span>名称：</label>
    <div class="formControls col-5">
      <input type="text" class="input-text" value="{{$category-> name}}" placeholder="" name="name" datatype="*" nullmsg="名称不能为空">
      <input type="hidden" class="input-text" value="{{$category-> id}}" name="id">
    </div>
    <div class="col-4"> </div>
  </div>
  <div class="row cl">
    <label class="form-label col-3"><span class="c-red">*</span>序号：</label>
    <div class="formControls col-5">
      <input type="number" class="input-text" value="{{$category-> category_no}}" placeholder="" name="category_no"  datatype="*" nullmsg="序号不能为空">
    </div>
    <div class="col-4"> </div>
  </div>
  <div class="row cl">
    <label class="form-label col-3">父类别：</label>
    <div class="formControls col-5"> <span class="select-box" style="width:150px;">
      <select class="select" name="parent_id" size="1">
        <option value="">无</option>
        @foreach($categories as $temp)
          @if($category->parent_id == $temp->id)
            <option selected value="{{$temp-> id}}">{{$temp-> name}}</option>
          @else
            <option value="{{$temp-> id}}">{{$temp-> name}}</option>
          @endif
        @endforeach
      </select>
      </span>
    </div>
  </div>
  <div class="row cl">
    <label class="form-label col-3">预览图：</label>
    <div class="formControls col-5">
      @if($category->preview != null)
        <img id="preview_id" src="{{ $category-> preview }}" style="border: 1px solid #B8B9B9; width: 100px; height: 100px;" onclick="$('#input_id').click()" />
        {{ $category-> preview }}
        <input type="hidden" class="input-text" value="{{ $category-> preview }}" id="preview_src" name = "preview_src">
      @else
        <p><?php echo "2---null"?></p>
        <img id="preview_id" src="/admin/images/icon-add.png" style="border: 1px solid #B8B9B9; width: 100px; height: 100px;" onclick="$('#input_id').click()" />
        <input type="hidden" class="input-text" value="" id="preview_src" name = "preview_src">
      @endif
      <input type="file" name="file" id="input_id" style="display: none;" onchange="return uploadImageToServer('input_id','images', 'preview_id', 'preview_src');" />
    </div>
  </div>
  <div class="row cl">
    <div class="col-9 col-offset-3">
      <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
    </div>
  </div>
</form>
@endsection

@section('my-js')
<script type="text/javascript">

  $("#form-category-edit").Validform({
    tiptype:2,
    callback:function(form){
      //var srcuri = $('input[id=preview_src]').val();
      var srcuri = $('#preview_id').attr('src');
      console.log('srcuri: ' + srcuri);
      $("#form-category-edit").ajaxSubmit({ //
          type: 'POST', // 提交方式 get/post
          url: '/admin/service/category/edit', // 需要提交的 url
          dataType: 'json',
          cache: false,
          contentType: false,
          processData:false, 
          data: {
            id: $('input[name=id]').val(), 
            name: $('input[name=name]').val(),
            category_no: $('input[name=category_no]').val(),
            parent_id: $('select[name=parent_id] option:selected').val(),
            preview_src : $('input[name=preview_src]').val(),
            //preview_src: ($('#preview_id').attr('src') != '/admin/images/icon-add.png' ? $('#preview_id').attr('src') : 'hello'),
            _token: "{{ csrf_token() }}"
          },
          success: function(data) {
            if(data == null) {
              layer.msg('服务端错误', {icon:2, time:2000});
              return;
            }
            if(data.status != 0) {
              layer.msg(data.message, {icon:2, time:2000});
              return;
            }

            layer.msg(data.message, {icon:1, time:2000});
  				  parent.location.reload(); 
          },
          error: function(xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
            layer.msg('ajax error', {icon:2, time:2000});
          },
          beforeSend: function(xhr){
            layer.load(0, {shade: false});
          },
        });

        return false;
    }
  });
</script>
@endsection
