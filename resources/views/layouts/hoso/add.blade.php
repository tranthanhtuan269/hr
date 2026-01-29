@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row">
   <div class="col-lg-3"></div>
   <div class="col-lg-7">
   @if (count($errors) > 0)
    <div class="alert alert-danger">
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
				<label for="hotenDem" class="col-sm-4 control-label">Họ và tên đệm <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="hotenDem" id="hotenDem" placeholder="Họ và tên đệm" value="{{old('hotenDem')}}">
				</div>
			</div>
			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label">Tên <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Tên" value="{{old('inputName')}}">
				</div>
			</div>
			<div class="form-group">
				<label for="inputGender" class="col-sm-4 control-label">Giới tính <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="radio" name="gender" value="1"
					@if (old('gender') == 1)
						checked="checked"
					@endif> Nam
						<input type="radio" name="gender" value="0" @if (old('gender') != null && old('gender') == 0) checked="checked" @endif> Nữ
				</div>
			</div>
			<div class="form-group">
				<label for="inputBirthday" class="col-sm-4 control-label">Ngày sinh <span class="required">*</span></label>
				<div class="col-sm-8">	
		          <input type='text' name="inputBirthday" class="datepicker form-control" id="datepicker" value="{{old('inputBirthday')}}"/>
		        </div>
			</div>
			<div class="form-group">
				<label for="inputPhone" class="col-sm-4 control-label">Điện thoại <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputPhone" id="inputPhone" placeholder="Điện thoại" value="{{old('inputPhone')}}">
				</div>
			</div>
			<div class="form-group">
				<label for="inputId" class="col-sm-4 control-label">Số chứng minh thư <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputId" id="inputId" placeholder="Số chứng minh" value="{{old('inputId')}}">
				</div>
			</div>
			<div class="form-group">
				<label for="inputId" class="col-sm-4 control-label">Chức danh <span class="required">*</span></label>
				<div class="col-sm-8">
					<select name="selectJobs" class="form-control">
						<option value=""> -- Chức danh -- </option>
						@if(!empty($listJobs))
							@foreach($listJobs as $job)
								<option value="{{ $job->id }}" @if(old('selectJobs') == $job->id) selected="selected" @endif>{{ $job->title }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="inputId" class="col-sm-4 control-label">Đơn vị <span class="required">*</span></label>
				<div class="col-sm-8">
	               <select name="selectDepart" class="form-control select2 narrow wrap" >
		                <option value="0"> -- Đơn vị -- </option>
		                {!! $department !!}
		            </select>
		            <script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})
		            </script>
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="col-sm-4 control-label">Quê quán <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address" id="address" placeholder="Quê quán" value="{{old('address')}}">
				</div>
			</div>
			<div class="form-group">
				<label for="addressRecent" class="col-sm-4 control-label">Nơi ở hiện nay <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="addressRecent" id="addressRecent" placeholder="Nơi ở hiện tại" value="{{old('addressRecent')}}">
				</div>
			</div>
			<div class="form-group">
				<label for="fileImage" class="col-sm-4 control-label">Ảnh hồ sơ </label>
				<div class="col-sm-8">
					<input type="file"  name="fileImage" id="fileImage" accept="image/*">
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<a href="{{ route('getPersonnelList') }}" class="btn btn-sm btn-grey">Nhập lại</a>
				</div>
			</div>
			
		</form>
	</div>
   <div class="col-lg-2"></div>
</div>
@endsection