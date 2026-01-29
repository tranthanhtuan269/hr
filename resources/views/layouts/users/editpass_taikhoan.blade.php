@extends('layouts.master')

@section('title', 'Tài khoản')

@section('content')
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
	 @if(count($errors) > 0)
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	      </div>
	    @endif
			<form class="form-horizontal" method="post" action="">
			<div class="form-group">
				<label for="passwordCurrent" class="col-sm-4 control-label">Mật khẩu hiện tại</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="passwordCurrent" id="passwordCurrent" value="{{ old('passwordCurrent') }}" required @if ( ($errors->has('inputPassword')==NULL) && $errors->has('inputPassword_confirmation')==NULL ) autofocus @elseif($errors->has('field')) autofocus @endif >
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-sm-4 control-label">Mật khẩu mới</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword" id="inputPassword" value="{{ old('inputPassword') }}" required @if ($errors->has('inputPassword')) autofocus @endif >
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword_confirmation" class="col-sm-4 control-label">Nhập lại mật khẩu</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" value="{{ old('inputPassword_confirmation') }}"  required @if ($errors->has('inputPassword_confirmation')) autofocus @endif >
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<a href="{{ route('getTaikhoanInfo',['id'=>Auth::user()->id]) }}" class="btn btn-sm btn-grey">Nhập lại</a>
				</div>
			</div>
			{{ csrf_field()}}
		</form>
	</div>
	<div class="col-lg-3"></div>
</div>
@endsection