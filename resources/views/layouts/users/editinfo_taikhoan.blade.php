@extends('layouts.master')

@section('title', 'Tài khoản')

@section('content')
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<h4 class="title-fuction">Sửa thông tin tài khoản</h4>
	 @if(count($errors) > 0)
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	      </div>
	    @endif
			<form class="form-horizontal" id="formsubmit" method="post" action="" enctype="multipart/form-data">
			{{ csrf_field()}}
			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label">Họ và tên</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Họ tên" value="{{old('inputName',isset($data->name) ? $data->name : null )}}" required @if ($errors->has('inputName')) autofocus @endif>
				</div>
			</div>
			<div class="form-group">
				<label for="fileImage" class="col-sm-4 control-label">Ảnh đại diện</label>
				<div class="col-sm-8">
				    <div class="avatar">
				    	<img id="blah" src="#" style="width:150px;height:150px;display: none;" />
				    @if(!empty($data->avatar))
				    	<img class="avatar_first" style="width:150px;height:150px" src="{{ asset('uploads/users/'.$data->avatar) }}" alt="avatar">
				    @else
						<img class="avatar_first" style="width:150px;height:150px" src="{{ asset('images/dashboard/avatar.png') }}">
				    @endif
				    </div>
					<input type="file"  name="fileImage" id="fileImage" accept="image/*">
					
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" id="btn-submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<a href="{{ route('getTaikhoanInfo',['id'=>Auth::user()->id]) }}" class="btn btn-sm btn-grey">Nhập lại</a>
				</div>
			</div>
		</form>
		<script type="text/javascript">
			function readURL(input) {
			    if (input.files && input.files[0]) {
			        var reader = new FileReader();
			        reader.onload = function (e) {
			            $('#blah').attr('src', e.target.result);
			        }

			        reader.readAsDataURL(input.files[0]);
			         $('#blah').show();
			         $('.avatar_first').hide();
			    }else{
			    	$('#blah').hide();
			         $('.avatar_first').show();
			    }
			}

			$("#fileImage").change(function(){
			    readURL(this);
			});
		</script>
	</div>
	<div class="col-lg-3"></div>
</div>
@endsection