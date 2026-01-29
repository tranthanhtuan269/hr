@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
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
			      <div class="col-lg-12">
			        <h4 class="title-fuction">Sửa tham số </h4>
					<form class="form-horizontal" method="post" action="">
						{{ csrf_field()}}
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Tên tham số <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="title" required @if ($errors->has('title')) autofocus value="" @else  value="{{ old('title',isset($data->title) ? $data->title : null) }}" @endif>
								<input type="hidden" class="form-control" name="id" value="{{ (isset($data->id) ? $data->id: null )}}" >
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả<span class="required">*</span></label>
							<div class="col-sm-6">
								<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" required>@if ($errors->has('description')) autofocus @else  {{ old('description',isset($data->description)?$data->description : null) }} @endif</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Kiểu <span class="required">*</span></label>
							<div class="col-sm-6">
							  	<select class="form-control" name="type" id="mySelect">
								    <option value="1" <?php echo (isset($data->is_fixed) && $data->is_fixed==1 )?"selected":""; ?> >Fixed</option>
								    <option value="0" <?php echo (isset($data->is_fixed) && $data->is_fixed==0 )?"selected":""; ?>>Reference</option>
							  	</select>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
								<?php
									if( $data->is_fixed==1 ){
								?>
										<style type="text/css">
											.setting_salary .reference{ display: none; }
										</style>
								<?php
									}else{
								?>
										<style type="text/css">
											.setting_salary .fixed{ display: none;}
										</style>
								<?php
									}
								?>
								<div class="col-sm-6 fixed">
									<input type="text" class="form-control" name="value_1" value="{{old('value',isset($data->value) ? $data->value	: null )}}" required @if ($errors->has('value_1')) autofocus value="" @else  value="{{ old('value_1',isset($data->value_1) ? $data->value_1 : null)}}" @endif>
								</div>
								<div class="col-sm-6 reference">
									<select class="form-control" name="value_2" id="mySelect">
									<?php
										if( count($setting)>0 ){
											foreach ($setting as $value) {
									?>
												<option value="<?php echo $value->setting_value; ?>" <?php echo (isset($data->value) && $data->value==$value->setting_value)?'selected':'' ?>><?php echo $value->setting_key; ?></option>

									<?php
											}
										}

									?>
									</select>
								</div>


						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-6">
								<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
							</div>
						</div>
					</form>
			      </div>
			    </div>
			</div>
			<script type="text/javascript">
				$('#mySelect').on('change', function() {
				  	if( this.value == 0 ){
				  		$('.fixed input').remove();
				  		$('.reference').css("display", "block");
				  	}else{
				  		$('.fixed input').remove();
				  		$('.fixed').append('<input type="text" class="form-control" name="value_1" required @if ($errors->has("value_1")) autofocus value="" @else  value="{{ old("value_1",isset($data->value_1) ? $data->value_1 : null) }}" @endif> ');
				  		$('.fixed').css("display", "block");
				  		$('.reference').css("display", "none");
				  	}
				})
			</script>
	</div>
</div>
@endsection