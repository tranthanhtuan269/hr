@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row">
   <div class="col-lg-1"></div>
   <div class="col-lg-10">
   @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	@endif
   @if (session('flash_message_err') != '')
	<div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
	@endif
	@if (session('flash_message_succ') != '')
	<div class="alert alert-success" role="alert">{{ session('flash_message_succ')}}</div>
	@endif

<?php
	// echo "<pre>";
	// print_r($data);die;
?>
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			{{ csrf_field()}}
			<div class="form-group">
				<label class="col-sm-3 control-label">Họ và tên đệm</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="hotenDem" id="hotenDem" placeholder="Họ và tên đệm" required @if ($errors->has('hotenDem')) autofocus value="" @else  value="{{ old('hotenDem',isset($data->first_name) ? $data->first_name : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Tên</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Họ tên" required @if ($errors->has('inputName')) autofocus value="" @else  value="{{ old('inputName',isset($data->last_name) ? $data->last_name : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Giới tính</label>
				<div class="col-sm-9">
					<input type="radio" name="gender" value="1"
					@if (old('gender',isset($data->gender) ? $data->gender : null) == 1)
						checked="checked"
					@endif> Nam
  					<input type="radio" name="gender" value="0" @if (old('gender',isset($data->gender) ? $data->gender : null) != null && old('gender',isset($data->gender) ? $data->gender : null) == 0) checked="checked" @endif> Nữ
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Ngày sinh</label>
				<div class="col-sm-9">	
	              <input type='text' name="inputBirthday" class="datepicker form-control" id="datepicker" required @if ($errors->has('inputBirthday')) autofocus value="" @else  value="{{ old('inputBirthday',isset($data->birthday) ? $data->birthday : null)}}" @endif >
                </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Điện thoại</label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="inputPhone" id="inputPhone" placeholder="Điện thoại" required @if ($errors->has('inputPhone')) autofocus value="" @else  value="{{ old('inputPhone',isset($data->phone_number) ? $data->phone_number : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Số chứng minh thư</label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="indentity_card_id" placeholder="Số chứng minh" required @if ($errors->has('indentity_card_id')) autofocus value="" @else  value="{{ old('indentity_card_id',isset($data->indentity_card_id) ? $data->indentity_card_id : null)}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Ngày cấp CMTND</label>
				<div class="col-sm-9">	
	              <input type='text' name="indentity_card_date" class="datepicker form-control" required @if ($errors->has('indentity_card_date')) autofocus value="" @else  value="{{ old('indentity_card_date',isset($data->indentity_card_date) ? BatvHelper::formatDate($data->indentity_card_date,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) : null)}}" @endif >
                </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Nơi cấp CMTND</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="indentity_card_address" required @if ($errors->has('indentity_card_address')) autofocus value="" @else  value="{{ old('indentity_card_address',isset($data->indentity_card_address) ? $data->indentity_card_address : null)}}" @endif >
				</div>
			</div>
			<div class="form-group hoso">
				<label class="col-sm-3 control-label">Chức danh</label>
				<div class="col-sm-9">
					@if(!empty($listJobs))
						@foreach($listJobs as $job)
						    <div class="checkbox">
						      <label><input type="checkbox" name="job[]" value="{{ $job->id }}" <?php echo ( isset($job->selected) && $job->selected==1 )?"checked":""; ?> >{{ $job->title }}</label>
						    </div>
						@endforeach
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Quỹ</label>
				<div class="col-sm-9">
					<p style="position: relative;top: 6.5px;">{{  BatvHelper::getInfoFundsbyPersonnel( $data->id ) }} (<a href="{{ route('getFundsDetail',['id'=>$data->id]) }}">Click</a>)</p>
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Giờ chấm công đi làm</label>
				<div class="col-sm-9">
					<input type="time" class="form-control" name="time_attendance_machine" required @if ($errors->has('time_attendance_machine')) autofocus value="" @else  value="{{ old('time_attendance_machine',isset($data->time_attendance_machine) ? $data->time_attendance_machine : null)}}" @endif >
					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Ngày vào công ty</label>
				<div class="col-sm-9">	
	              <input type='text' name="date_in" class="datepicker form-control" id="datepicker_date_in" required @if ($errors->has('date_in')) autofocus value="" @else  value="{{ old('date_in',isset($data->date_in) ? BatvHelper::formatDate($data->date_in,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)  : null)}}" @endif >
                </div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Số tháng thâm niên trước năm 2017</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="seniority" value="{{old('seniority',isset($data->seniority) ? $data->seniority : null )}}" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Ngày nghỉ việc</label>
				<div class="col-sm-9">	
	              <input type='text' name="date_out" class="datepicker form-control" id="datepicker_date_out" @if ($errors->has('date_out')) autofocus value="" @else  value="{{ old('date_out',isset($data->date_out) ? BatvHelper::formatDate($data->date_out,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)  : null)}}" @endif >
                </div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Chu kỳ xét tăng lương</label>
				<div class="col-sm-3">
					<select name="salary_frequency"  class="form-control" >
						@if( $period )
							@foreach( $period as $value )
								<option value="{{ $value->value }}" {{ old('salary_frequency',isset($data->salary_frequency) && $data->salary_frequency  == $value->value ? "selected":"") }} >@if( $value->value == 0 ) {{ $value->description }}  @else {{ $value->value }} năm  @endif   </option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="col-sm-5">
					<a href="{{ route('getSettingPeriodSalary')}}" style="position: relative;top: 6.5px;" target="_blank">Quản trị</a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Loại hợp đồng</label>
				<div class="col-sm-9">
	            	<div class="row" style="margin: 10px 0px;">
	            		<div class="col-sm-4"><input type="checkbox" name="selectContract[5]" value="5" id="selectContract_5"  <?php if( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[5]) ) { echo "checked"; } ?>> Thực tập parttime</div>
	            		<div class="col-sm-4">
	            			Từ <input type='text' name="apply_from_contract[5]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[5]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[5]["apply_from"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            		<div class="col-sm-4">
	            			đến <input type='text' name="apply_to_contract[5]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[5]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[5]["apply_to"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            	</div>
	            	<div class="row" style="margin: 10px 0px;">
	            		<div class="col-sm-4"><input type="checkbox" name="selectContract[3]" value="3" id="selectContract_3"  <?php if( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[3]) ) { echo "checked"; } ?>> Thực tập fulltime</div>
	            		<div class="col-sm-4">
	            			Từ <input type='text' name="apply_from_contract[3]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[3]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[3]["apply_from"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            		<div class="col-sm-4">
	            			đến <input type='text' name="apply_to_contract[3]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[3]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[3]["apply_to"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            	</div>
	            	<div class="row" style="margin: 10px 0px;">
	            		<div class="col-sm-4"><input type="checkbox" name="selectContract[4]" value="4" id="selectContract_4"  <?php if( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[4]) ) { echo "checked"; } ?>> Part time</div>
	            		<div class="col-sm-4">
	            			Từ <input type='text' name="apply_from_contract[4]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[4]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[4]["apply_from"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            		<div class="col-sm-4">
	            			đến <input type='text' name="apply_to_contract[4]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[4]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[4]["apply_to"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            	</div>
	            	<div class="row" style="margin: 10px 0px;">
	            		<div class="col-sm-4"><input type="checkbox" name="selectContract[1]" value="1" id="selectContract_1"  <?php if( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[1]) ) { echo "checked"; } ?>> Hợp đồng thử việc</div>
	            		<div class="col-sm-4">
	            			Từ <input type='text' name="apply_from_contract[1]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[1]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[1]["apply_from"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            		<div class="col-sm-4">
	            			đến <input type='text' name="apply_to_contract[1]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[1]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[1]["apply_to"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            	</div>
	            	<div class="row" style="margin: 10px 0px;">
	            		<div class="col-sm-4"><input type="checkbox" name="selectContract[2]" value="2" id="selectContract_2"  <?php if( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[2]) ) { echo "checked"; } ?>> Hợp đồng chính thức</div>
	            		<div class="col-sm-4">
	            			Từ <input type='text' name="apply_from_contract[2]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[2]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[2]["apply_from"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?> >
	            		</div>
	            		<div class="col-sm-4">
	            			đến <input type='text' name="apply_to_contract[2]" class="datepicker" <?php echo ( count($listContractsbyPersonnel)>0 && isset($listContractsbyPersonnel[2]) )?"required value=".BatvHelper::formatDate($listContractsbyPersonnel[2]["apply_to"], 'Y-m-d', $formatDate='d/m/Y',$timeFormat='H:i:s',$time=false):""; ?>>
	            		</div>
	            	</div>
	            	<script type="text/javascript">
						$( document ).ready(function() {

							$("#selectContract_1").change(function() {
							    if(this.checked) {
							       $('input[name="apply_from_contract[1]"],input[name="apply_to_contract[1]"]').prop('required',true);
							    }else{
							    	$('input[name="apply_from_contract[1]"],input[name="apply_to_contract[1]"]').removeAttr('required');
							    }
							});

							$("#selectContract_2").change(function() {
							    if(this.checked) {
							       $('input[name="apply_from_contract[2]"],input[name="apply_to_contract[2]"]').prop('required',true);
							    }else{
							    	$('input[name="apply_from_contract[2]"],input[name="apply_to_contract[2]"]').removeAttr('required');
							    }
							});

							$("#selectContract_3").change(function() {
							    if(this.checked) {
							       $('input[name="apply_from_contract[3]"],input[name="apply_to_contract[3]"]').prop('required',true);
							    }else{
							    	$('input[name="apply_from_contract[3]"],input[name="apply_to_contract[3]"]').removeAttr('required');
							    }
							});
							$("#selectContract_4").change(function() {
							    if(this.checked) {
							       $('input[name="apply_from_contract[4]"],input[name="apply_to_contract[4]"]').prop('required',true);
							    }else{
							    	$('input[name="apply_from_contract[4]"],input[name="apply_to_contract[4]"]').removeAttr('required');
							    }
							});
						});

	            	</script>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Đơn vị</label>
				<div class="col-sm-9">
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
				<label class="col-sm-3 control-label">Mức lương cơ bản đóng bảo hiểm</label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="insurrance" required @if ($errors->has('insurrance')) autofocus value="" @else  value="{{ old('insurrance',!empty($data->insurrance) ? $data->insurrance : "")}}" @endif >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Thời gian nghỉ thai sản(nếu có)</label>
				<div class="col-sm-9">
					<a href="{{ route('getMaternityLeave',['id'=>$data->id])}}" style="position: relative;top: 6.5px;" target="_blank">Quản trị</a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Số người phụ thuộc</label>
				<div class="col-sm-9">
					<input type="number" class="form-control" name="number_dependent_person" value="{{old('number_dependent_person',isset($data->number_dependent_person) ? $data->number_dependent_person : null )}}" required min="0">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Quê quán</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="home_town" id="home_town" placeholder="Quê quán" value="{{old('home_town',isset($data->home_town) ? $data->home_town : null )}}" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Nơi ở hiện nay</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="address" id="address" placeholder="Nơi ở hiện tại" value="{{old('address',isset($data->address) ? $data->address : null )}}" required>
				</div>
			</div>
			<div class="form-group">
				<label for="fileImage" class="col-sm-3 control-label">Ảnh hồ sơ</label>
				<div class="col-sm-9">
					<img id="blah" src="http://hr.tohsoft.com/uploads/personnels/1490164467.hinh-anh-ve-me-12.jpg" style="width:150px;height:150px;display: none;" />
	
				    @if(!empty($data->avatar))
				    	<img class="avatar_first" style="width:150px;height:150px" src="{{ asset('uploads/personnels/'.$data->avatar) }}" alt="avatar">
				    @endif
				 
					<input type="file"  name="fileImage" id="fileImage" accept="image/*">
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
<!-- 					<a href="{{ route('getPersonnelList') }}" class="btn btn-sm btn-grey">Nhập lại</a> -->
					<input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
				</div>
			</div>
			
		</form>
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

   <div class="col-lg-1"></div>
</div>
@endsection