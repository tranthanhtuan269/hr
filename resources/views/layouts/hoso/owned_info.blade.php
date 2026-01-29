@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row">
   <div class="col-lg-2"></div>
   <div class="col-lg-8">
   @if (session('flash_message_err') != '')
	<div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
	@endif
	@if (session('flash_message_succ') != '')
	<div class="alert alert-success" role="alert">{{ session('flash_message_succ')}}</div>
	@endif
   @if(!empty($data->id))
	   <div class="col-lg-4">
	   	  @if(empty($data->avatar))
	       <div class="avatar text-center">
	       		<img src="{{ asset('images/dashboard/avatar.png') }} " class="user-image" alt="User Image">
	       </div>
	       @else
	        <div class="avatar text-center">
	       		<img style="width:150px;height:150px" src="{{ asset('uploads/personnels/'.$data->avatar) }} " class="user-image" alt="User Image">
	       </div>
	       @endif
	       <br/>
	       <div class="text-center">
	       		<a href="{{ route('getHosoEditInfo',['id'=>Auth::user()->id]) }}" class="btn btn-sm btn-orange">Cập nhật</a>
	       </div>
	   </div>
	    <div class="col-lg-8">
	      <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Họ và tên</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->fullname )?$data->fullname:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Giới tính </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			@if($data->gender == 1)
	   			<p>Nam </p>
	   			@else
				<p>Nữ </p>
	   			@endif
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Ngày sinh </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->birthday )?$data->birthday:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Email </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->email )?$data->email:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Điện thoại</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->phone_number )?$data->phone_number:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Số CMTND</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->indentity_card_id )?$data->indentity_card_id:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Ngày cấp CMTND </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->indentity_card_date )? DateTime::createFromFormat('Y-m-d', $data->indentity_card_date)->format('d/m/Y') :'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Nơi cấp CMTND </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->indentity_card_address )?$data->indentity_card_address:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Quê quán </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->home_town )?$data->home_town:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Nơi ở hiện nay</b></div>
	   		<div class="col-lg-8 col-xs-8">

	   			<p> {{ !empty( $data->address )?$data->address:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Chức danh</b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p> {!! !empty( $data->jobs )?$data->jobs:'...' !!}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Ngày vào công ty</b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p>@if( $data->date_in ) {{  BatvHelper::formatDate($data->date_in,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }} @endif</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Chu kỳ xét tăng lương</b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p>{{ $data->salary_frequency }} <?php echo ( $data->salary_frequency==6 )?"tháng":"năm"; ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Hệ số chức danh </b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p> {{ !empty( $data->ratio )?$data->ratio:'...' }}</p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Đơn vị</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> {{ !empty( $data->title )?$data->title:'...' }}</p>
	   	  	</div>
	   	 </div>
	   		
	   </div>
	@else
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
				<label for="hotenDem" class="col-sm-4 control-label">Họ và tên đệm</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="hotenDem" id="hotenDem" placeholder="Họ và tên đệm"  required @if ($errors->has('hotenDem')) autofocus value="" @else  value="{{ old('hotenDem',isset($data->first_name) ? $data->first_name : null)}} @endif ">
				</div>
			</div>
			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label">Tên</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Họ tên" required @if ($errors->has('inputName')) autofocus value="" @else  value="{{ old('inputName',isset($data->last_name) ? $data->last_name : null)}} @endif ">
				</div>
			</div>
			<div class="form-group">
				<label for="inputGender" class="col-sm-4 control-label">Giới tính</label>
				<div class="col-sm-8">
					<input type="radio" name="gender" value="1"
					@if (old('gender',isset($data->gender) ? $data->gender : null) == 1)
						checked="checked"
					@endif> Nam
						<input type="radio" name="gender" value="0" @if (old('gender',isset($data->gender) ? $data->gender : null) != null && old('gender',isset($data->gender) ? $data->gender : null) == 0) checked="checked" @endif> Nữ
				</div>
			</div>
			<div class="form-group">
				<label for="inputBirthday" class="col-sm-4 control-label">Ngày sinh</label>
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
				<label for="inputPhone" class="col-sm-4 control-label">Điện thoại</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputPhone" id="inputPhone" placeholder="Điện thoại" required @if ($errors->has('inputPhone')) autofocus value="" @else  value="{{ old('inputPhone',isset($data->phone_number) ? $data->phone_number : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label for="inputId" class="col-sm-4 control-label">Số chứng minh thư</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="indentity_card_id" id="indentity_card_id" placeholder="Số chứng minh" required @if ($errors->has('indentity_card_id')) autofocus value="" @else  value="{{ old('indentity_card_id',isset($data->indentity_card_id) ? $data->indentity_card_id : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="col-sm-4 control-label">Quê quán</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="home_town" id="home_town" placeholder="Quê quán" required @if ($errors->has('home_town')) autofocus value="" @else  value="{{ old('home_town',isset($data->home_town) ? $data->home_town : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="col-sm-4 control-label">Nơi ở hiện nay</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address" id="address" placeholder="Nơi ở hiện tại" required @if ($errors->has('address')) autofocus value="" @else  value="{{ old('address',isset($data->address) ? $data->address : null)}}" @endif>
				</div>
			</div>
			<div class="form-group">
				<label for="fileImage" class="col-sm-4 control-label">Ảnh hồ sơ</label>
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
					<a href="{{ route('getHosoInfo',['id'=>Auth::user()->id]) }}" class="btn btn-sm btn-grey">Nhập lại</a>
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
		
	@endif
	</div>
   <div class="col-lg-2"></div>
</div>
@endsection