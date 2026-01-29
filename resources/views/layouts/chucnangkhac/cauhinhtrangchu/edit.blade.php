@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')

<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.chucnangkhac.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Chỉnh sửa vùng hiển thị trang chủ </h4> 
			@if(count($errors) > 0)
				<div class="alert alert-danger" role="alert">
				<ul>
				    @foreach ($errors->all() as $error)
				        <li>{{ $error }}</li>
				    @endforeach
				</ul>
				</div>
			@endif
			@if (session('flash_message_succ') != '')
				 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
			@endif
			<div class="row">
				<div class="col-sm-12">
					<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
						{{ csrf_field()}}
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">Tiêu đề <span class="required">*</span></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="title" value="{{old('title',isset($data->title) ? $data->title : null )}}" required>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nội dung <span class="required">*</span></label>
                            <div class="col-sm-10">
					            <textarea rows="4" onkeydown="expandtext(this);" name="content" requried>{{ old('content',isset($data->content) ? $data->content : null ) }}</textarea>
					            <script type="text/javascript">
					              CKEDITOR.replace( 'content');
					            </script>
                            </div>
                        </div>
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">Icon </label>
							<div class="col-sm-10">
								<input type="file"  name="icon" id="fileImage" accept="image/*">	
				                @if(!empty($data->icon))
				                  <img class="icon_cat_home" src="{{ asset('uploads/icon-cat-home/'.$data->icon) }}">
				                @endif
				                <img id="blah" src="#" style="display: none;" />
								<script type="text/javascript">
								  function readURL(input) {
								      if (input.files && input.files[0]) {
								          var reader = new FileReader();
								          reader.onload = function (e) {
								              $('#blah').attr('src', e.target.result);
								          }

								          reader.readAsDataURL(input.files[0]);
								           $('#blah').show();
								           $('.icon_cat_home').hide();
								      }else{
								        $('#blah').hide();
								           $('.icon_cat_home').show();
								      }
								  }

								  $("#fileImage").change(function(){
								      readURL(this);
								  });
								</script>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">Màu nền </label>
							<div class="col-sm-10">
								<input name="background_color" type="color" value="{{ old('background_color',isset($data->background_color) ? $data->background_color : null ) }}">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12  text-center">
								<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
							</div>
						</div>
					</form>
				</div>
			</div>
	</div>
</div>
@endsection