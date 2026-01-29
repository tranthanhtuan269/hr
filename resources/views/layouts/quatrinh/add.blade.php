@extends('layouts.master')

@section('title', 'Quá trình công tác')

@section('content')
<div class="row content-function">
	<div class="col-lg-12">
		<h4 class="title-fuction">Thêm quá trình công tác</h4>
		 
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
			<div class="form-group col-lg-6">
				<label for="startDate" class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="startDate" id="startDate" required @if ($errors->has('startDate')) autofocus value="" @else  value="{{ old('startDate',isset($data->startDate) ? $data->startDate : null)}}" @endif >
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="endDate" class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="endDate" id="endDate" required @if ($errors->has('endDate')) autofocus value="" @else  value="{{ old('endDate',isset($data->endDate) ? $data->endDate : null)}}" @endif >
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="inputPossition" class="col-sm-4 control-label">Chức danh</label>
				<div class="col-sm-8">
					<select name="selectJobs" class="form-control" @if ($errors->has('selectJobs')) autofocus @endif >
						<option value=""> -- Chức danh -- </option>
						@if(!empty($listJobs))
							@foreach($listJobs as $job)
								<option value="{{ $job->id }}" @if(old('selectJobs') == $job->id) selected="selected" @endif>{{ $job->title }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="inputBirthday" class="col-sm-4 control-label">Đơn vị</label>
				<div class="col-sm-8">	
	              <select name="selectDepart" class="form-control" @if ($errors->has('selectDepart')) autofocus @endif>
		                <option value=""> -- Đơn vị -- </option>
		                {!! $department !!}
		            </select>
                </div>
			</div>
			 <div class="form-group col-lg-12">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
	            <a class="btn btn-sm btn-grey" href="{{ route('getHistoryDetail',['id'=>$id])}}">Nhập lại</a>
	          </div>
	        </div>
		</form>
	</div>
</div>

@endsection