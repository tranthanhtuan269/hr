@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<style type="text/css">
	.setting_salary .repice_reference{ display: none; }
</style>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thêm cấu hình công thức </h4> 
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
				 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
			@endif
			<div class="row">
				@include('layouts.luongthuong.menusetting')
				<div class="col-lg-12">
					<form class="form-horizontal" method="post" action="" >
						{{ csrf_field()}}
						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Tiêu đề </label>
							<div class="col-sm-8">
								<input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Ngày <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="selectDay" class="form-control">
									@for( $i=1;$i<=31;$i++ )
										@if( $i<10 )
											<option value='{{ "0".$i }}' <?php if( !empty( old('selectDay') ) && $i== "0".old('selectDay')){ echo "selected" ;} ?> >{{ "0".$i }}</option>
										@else

											<option value='{{ $i }}' <?php if( !empty( old('selectDay') ) && $i== old('selectDay')){ echo "selected" ;}  ?>>{{ $i }}</option>
										@endif


									@endfor
								</select>
							</div>
							<label class="col-sm-1 control-label">Tháng <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="selectMonth" class="form-control">
									 <?php 
						                for ($i = 1; $i <= 12; $i++){
										    $month = ($i < 10) ? '0'.$i : $i ;
										    echo '<option value="'.$month.'"';
										    if (!empty(Request::input('selectMonth'))) {
										    	if ($i == Request::input('selectMonth')) echo ' selected="selected"';
										    }else{
										    	if ($i == date("n")) echo ' selected="selected"';
										    }						    
										    echo '>'.$month.'</option>';
										}
									 ?>
								</select>
							</div>
							<label class="col-sm-1 control-label">Năm <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="selectYear" class="form-control">
										<option value="*">*</option>
									<?php
										for($i=date("Y")-5;$i<=date("Y") + 2;$i++) {
											 if (!empty(Request::input('selectYear'))) {
										    	$sel = ($i == Request::input('selectYear')) ? 'selected' : '';
										    }else{
										    	$sel = ($i == date('Y')) ? 'selected' : '';
										    }	   
										    echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Lý do </label>
							<div class="col-sm-8">
								<textarea rows="4" onkeydown="expandtext(this);" name="reason" class="form-control">{{ old('reason') }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="text-center">
								<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
							</div>
						</div>
					</form>
				</div>
			</div>


	</div>
</div>
@endsection