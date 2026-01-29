@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
      <h4 class="title-fuction">Sửa tin tức</h4>
    
      @if (session('flash_message_succ') != '')
      <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
      @endif
      @if(count($errors) > 0)
      <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
      @endif
      <form class="form-horizontal" method="POST" action="">
        <div class="form-group">
          <label for="inputHoten" class="col-sm-4 control-label">Tiêu đề tin tức <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="title" value="{{old('title',isset($data->title) ? $data->title : null )}}" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Nội dung <span class="required">*</span></label>
          <div class="col-sm-8">
            <textarea rows="4" onkeydown="expandtext(this);" name="content" required="required" class="special">{{ old('content',isset($data->content) ? $data->content : null ) }}</textarea>
            <script type="text/javascript">
              CKEDITOR.replace('content');
            </script>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Ghim tin <span class="required">*</span></label>
          <div class="col-sm-8">
            <label class="radio-inline"><input type="radio" name="is_pinned" value="1" {{ old('is_pinned',( isset($data->is_pinned) &&  $data->is_pinned==1) ? 'checked' : null ) }}> Có</label>
            <label class="radio-inline"><input type="radio" name="is_pinned" value="0" {{ old('is_pinned',( isset($data->is_pinned) &&  $data->is_pinned==0) ? 'checked' : null ) }}> Không</label>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword_confirmation" class="col-sm-4 control-label">Gửi email khi đăng tin <span class="required">*</span></label>
          <div class="col-sm-8">
            <label class="radio-inline"><input type="radio" name="email_notification" value="1" {{ old('email_notification',( isset($data->email_notification) &&  $data->email_notification==1) ? 'checked' : null ) }}> Có</label>
            <label class="radio-inline"><input type="radio" name="email_notification" value="0"  {{ old('email_notification',( isset($data->email_notification) &&  $data->email_notification==0) ? 'checked' : null ) }}> Không</label>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-4 col-sm-8 text-center">
            <button type="submit" class="btn btn-sm btn-orange" id="save_news">Cập nhật</button>
          </div>
        </div>
          {{ csrf_field()}}
      </form>
	</div>
	<div class="col-lg-1"></div>
</div>
  <script type="text/javascript">
    //window.onbeforeunload = function() { return 'Are you sure you want to navigate away from this page?';}
    // $(document).ready(function(){
    //     $("#save_news").click(function(){
    //       for (var i in CKEDITOR.instances) {
    //         // CKEDITOR.instances[i].on('change', function() {
    //             if( $('.cke_contents iframe').contents().find("body").text() == "" ){
    //               alert('Nội dung không được để trống !!!');
    //               postContentClr = $(".cke_contents iframe").contents().find("body");
    //               postContentClr.focus();
    //               return false;
    //             }
    //         // });
    //       }
    //       window.onbeforeunload = function() {
    //         return 'Are you sure you want to navigate away from this page?';
    //       };
    //     });
  
    // });
  </script>
@endsection

