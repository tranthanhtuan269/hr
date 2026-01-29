@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row content-function">
    <div class="col-lg-3"></div>
	<div class="col-lg-7">
		<h4 class="title-fuction">Sửa khoảng thời gian nhân viên nghỉ thai sản</h4>
		@if (session('flash_message_err') != '')
			 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
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
<?php
	// echo "<pre>";
	// print_r($data);die;
?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			{{ csrf_field()}}
			<div class="form-group">
				<label for="apply_from" class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="apply_from" id="apply_from" required @if ($errors->has('apply_from')) autofocus value="" @else  value="{{ old('apply_from',isset($data->apply_from) ? BatvHelper::formatDate($data->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false): null)}}" @endif > 
				</div>
			</div>
			<div class="form-group">
				<label for="apply_to" class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="apply_to" id="apply_to" required @if ($errors->has('apply_to')) autofocus value="" @else  value="{{ old('apply_to',isset($data->apply_to) ? BatvHelper::formatDate($data->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)  : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Tham gia bảo hiểm</label>
				<div class="col-sm-8">
					<label class="radio-inline"><input type="radio" name="join_insurance" @if($data->join_insurance == 1) checked @endif  value="1">Có</label>
					<label class="radio-inline"><input type="radio" name="join_insurance" @if($data->join_insurance == 0)  checked @endif value="0">Không</label>
				</div>
			</div>
            <div class="text-center">
                <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
            </div>
		</form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection