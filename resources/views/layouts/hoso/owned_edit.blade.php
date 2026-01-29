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
				<label class="col-sm-4 control-label">Họ và tên đệm</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="hotenDem" id="hotenDem" required @if ($errors->has('hotenDem')) autofocus value="" @else  value="{{ old('hotenDem',isset($data->first_name) ? $data->first_name : null)}} @endif ">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Tên</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" required @if ($errors->has('inputName')) autofocus value="" @else  value="{{ old('inputName',isset($data->last_name) ? $data->last_name : null)}} @endif ">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Giới tính</label>
				<div class="col-sm-8">
					<input type="radio" name="gender" value="1" @if (old('gender',isset($data->gender) ? $data->gender : null) == 1) checked="checked" @endif> Nam
					<input type="radio" name="gender" value="0" @if (old('gender',isset($data->gender) ? $data->gender : null) != null && old('gender',isset($data->gender) ? $data->gender : null) == 0) checked="checked" @endif> Nữ
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Ngày sinh</label>
				<div class="col-sm-8">	

		          <input type="text" name="inputBirthday" class="form-control" id="datepicker" @if ($errors->has('inputBirthday')) autofocus value="" @else  value="{{ old('inputBirthday',isset($data->birthday) ? $data->birthday : null)}}" @endif>
		        </div>
			</div>
			<script>
			  $(function() {
			    $( "#datepicker" ).datepicker({
			    		changeMonth: true,
							changeYear: true,
							yearRange: "1970:2020",
							dateFormat: 'dd/mm/yy'

			    	}	
			    );
			  });
			  </script>
			 <div class="form-group">
				<label class="col-sm-4 control-label">Email</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputEmail" id="inputEmail"  value="{{old('inputEmail',isset($data->email) ? $data->email : null)}}" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Điện thoại</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="inputPhone" id="inputPhone" required @if ($errors->has('inputPhone')) autofocus value="" @else  value="{{ old('inputPhone',isset($data->phone_number) ? $data->phone_number : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Số chứng minh thư</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="indentity_card_id" id="indentity_card_id" required @if ($errors->has('indentity_card_id')) autofocus value="" @else  value="{{ old('indentity_card_id',isset($data->indentity_card_id) ? $data->indentity_card_id : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Ngày cấp CMTND</label>
				<div class="col-sm-8">	
	              <input type='text' name="indentity_card_date" class="datepicker form-control" required @if ($errors->has('indentity_card_date')) autofocus value="" @else  value="{{ old('indentity_card_date',isset($data->indentity_card_date) ? BatvHelper::formatDate($data->indentity_card_date,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) : null)}}" @endif >
                </div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Nơi cấp CMTND</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="indentity_card_address" required @if ($errors->has('indentity_card_address')) autofocus value="" @else  value="{{ old('indentity_card_address',isset($data->indentity_card_address) ? $data->indentity_card_address : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Quê quán</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="home_town" id="home_town" required @if ($errors->has('home_town')) autofocus value="" @else  value="{{ old('home_town',isset($data->home_town) ? $data->home_town : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Nơi ở hiện nay</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address" id="address" required @if ($errors->has('address')) autofocus value="" @else  value="{{ old('address',isset($data->address) ? $data->address : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Ảnh hồ sơ</label>
				<div class="col-sm-8">
					<img id="blah" src="#" style="width:150px;height:150px;display: none;" />
				    @if(!empty($data->avatar))
				    	<img class="avatar_first" style="width:150px;height:150px" src="{{ asset('uploads/personnels/'.$data->avatar) }}" alt="avatar">
				    @endif
					<input type="file"  name="fileImage" id="fileImage" accept="image/*">
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
				</div>
			</div>
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
		</form>
	</div>
   <div class="col-lg-2"></div>
</div>
@endsection