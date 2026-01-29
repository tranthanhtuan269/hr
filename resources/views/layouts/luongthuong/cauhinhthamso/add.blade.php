@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<style type="text/css">
	.setting_salary .reference{ display: none; }
</style>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Cấu hình bộ tham số <a href="{{ route('addParametersConfig') }}"><img src="{{ asset('images/general/add.png') }}"></a></h4> 
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
			@if (session('flash_message_err') != '')
				 <div class="alert alert-err" role="alert"></span> {{ session('flash_message_err') }}</div>
			@endif
			<div class="row">
				@include('layouts.luongthuong.menusetting')
				<div class="col-lg-offset-2 col-lg-7">
					<form class="form-horizontal" method="post" action="">
						{{ csrf_field()}}
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Tên tham số <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="title" required @if ($errors->has('title')) autofocus value="" @else  value="{{ old('title') }}" @endif>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả<span class="required">*</span></label>
							<div class="col-sm-8">
								<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" required>@if ($errors->has('description')) autofocus  @else  {{ old('description') }} @endif</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Kiểu <span class="required">*</span></label>
							<div class="col-sm-8">
							  	<select class="form-control" name="type" id="mySelect">
								    <option value="1" selected>Fixed</option>
								    <option value="0">Reference</option>
							  	</select>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
							<div class="col-sm-8 fixed">
								<input type="text" class="form-control" name="value_1" required @if ($errors->has('value_1')) autofocus value="" @else  value="{{ old('value_1') }}" @endif>
							</div>
							<div class="col-sm-8 reference">
								<select class="form-control" name="value_2">
								<?php
									if( count($setting)>0 ){
										foreach ($setting as $value) {
								?>
											<option value="<?php echo $value->setting_value; ?>"><?php echo $value->setting_key; ?></option>

								<?php
										}
									}

								?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		<script type="text/javascript">
			$('#mySelect').on('change', function() {
			  	if( this.value == 0 ){
			  		$('.fixed input').remove();
			  		$('.reference').css("display", "block");
			  	}else{
			  		$('.fixed').append('<input type="text" class="form-control" name="value_1" required @if ($errors->has("value_1")) autofocus value="" @else  value="{{ old("value_1") }}" @endif> ');
			  		$('.reference').css("display", "none");
			  	}
			})
		</script>
	</div>
</div>
@endsection