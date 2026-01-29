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
							<div class="col-sm-5">
								<input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-offset-1 col-sm-2 control-label">Tháng <span class="required">*</span></label>
							<div class="col-sm-2">
								@for($i=1;$i<=12;$i++)
									<div class="checkbox">
									  <label><input type="checkbox" value="{{ $i }}" name="month[]">{{ $i }}</label>
									</div>
								@endfor
							</div>
							<label class="col-sm-1 control-label">Năm <span class="required">*</span></label>
							<div class="col-sm-2">
								<select name="year" class="form-control">
									<?php
										for($i=date("Y")-2;$i<=date("Y");$i++) {
											 if (!empty(Request::input('year'))) {
										    	$sel = ($i == Request::input('year')) ? 'selected' : '';
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
							<label class="col-sm-offset-1 col-sm-2 control-label">Số ngày nghỉ phép</label>
							<div class="col-sm-5">
								<input type="number" name="number_days" class="form-control" value="{{ old('number_days') }}" min="0.5" max="1" step="0.5" required>
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