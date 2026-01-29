@extends('layouts.master')

@section('title', 'Quá trình công tác')

@section('content')
<div class="row content-function">
    <div class="col-lg-3"></div>
	<div class="col-lg-7">
		<h4 class="title-fuction">Thêm hệ số</h4>
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
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			{{ csrf_field()}}
			<div class="form-group">
				<label for="startDate" class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="startDate" id="startDate" required @if ($errors->has('startDate')) autofocus value="" @else  value="{{ old('startDate',isset($data->apply_from) ? $data->apply_from : null)}}" @endif > 
				</div>
			</div>
			<div class="form-group">
				<label for="endDate" class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="endDate" id="endDate" required @if ($errors->has('endDate')) autofocus value="" @else  value="{{ old('endDate',isset($data->apply_to) ? $data->apply_to : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label for="heso" class="col-sm-4 control-label">Hệ số</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="heso" id="heso" required @if ($errors->has('heso')) autofocus value="" @else  value="{{ old('heso',isset($data->ratio) ? $data->ratio : null)}}" @endif >	
				</div>
			</div>
			 <div class="form-group">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
	            <a class="btn btn-sm btn-grey" href="{{ route('getHistoryDetail',['id'=>$id])}}">Nhập lại</a>
	          </div>
	        </div>
		</form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection