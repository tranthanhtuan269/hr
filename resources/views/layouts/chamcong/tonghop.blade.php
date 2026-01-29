@extends('layouts.master')

@section('title', 'Tổng hợp')

@section('content')
<div class="row content-function">
	<div class="col-lg-12">
		<h4 class="title-fuction">Thông tin chấm công tổng hợp</h4>
		@if (session('flash_message_succ') != '')
			 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		@endif
		@if ( !empty($message_err) )
			 <div class="alert alert-danger" role="alert"> {{ $message_err }}</div>
		@endif
		<form class="form-horizontal" method="get" action="">
			<div class="form-group col-lg-6">
				<label for="startDate" class="col-sm-4 control-label">Từ ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="startDate" id="startDate" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo !empty( Request::get('startDate') )?Request::get('startDate'):date("d/m/Y", strtotime("first day of this month")) ?>">
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="endDate" class="col-sm-4 control-label">Đến ngày</label>
				<div class="col-sm-8">
					<input type="text" class="datepicker form-control" name="endDate" id="endDate" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo !empty( Request::get('endDate') )?Request::get('endDate'):date('d/m/Y') ?>">
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
				<div class="col-sm-8">	
	               <select name="selectDepart" id="selectDepart" class="form-control select2 narrow wrap" >
		                @if (!empty($department)) 
		                	{!! $department !!}
	                	@endif
		            </select>
		            <script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})
		            </script>
                </div>
			</div>
			<div class="form-group col-lg-6">
				<label for="selectPersonnel" class="col-sm-4 control-label">Nhân sự</label>
				<div class="col-sm-8">
					<select name="selectPersonnel" id="selectPersonnel" class="form-control select2 narrow wrap" >
							<option value="0">--Chọn nhân sự--</option>
		                @if (!empty($listPersonal))
		                	@foreach ($listPersonal as $key => $value)
		                	<option value="{{ $key }}" @if ($key == $_GET['selectPersonnel']) {{ "selected" }} @endif>{{ $value }}</option>
		                	@endforeach
		                @endif
					</select>
					<script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})
					</script>
				</div>
			</div>
			 <div class="form-group col-lg-12">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange" name="search">Tìm kiếm</button>
	            <input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
	          </div>
	        </div>
	        {{ csrf_field()}}
		</form>
	</div>
	<div class="col-lg-10 col-lg-offset-2 thongtin-chamcong">
		<h4>Từ ngày  {{ !empty( Request::get('startDate') )?Request::get('startDate'): date("d/m/Y", strtotime("first day of this month")) }}  - Đến ngày {{ !empty( Request::get('endDate') )?Request::get('endDate'): date('d/m/Y') }} </h4>
        <table class="attendance table table-striped table-condensed">
              <thead>
              <tr>
				<th class="text-center">Họ và tên</th>
				<th class="text-center">Số ngày đi làm </th>
				<th class="text-center">Số lần đi muộn</th>
				<th class="text-center">Số ngày nghỉ</th>
<!-- 				<th class="text-center">Số ngày nghỉ hưởng lương</th>    -->                                      
              </tr>
          </thead>   
          <tbody>
			@if(!empty($result))
				@foreach($result as $value)
				                <tr class="text-center">
				                	<td class="">{{ str_limit( $value['fullname'], $limit = 45, $end = '...') }}</td>
				                    <td>{{ $value['list']['dayWork'] }}</td>
				                    <td>{{ $value['list']['dayWorkLate'] }}</td>
				                    <td>{{ $value['list']['dayWorkLeave'] }}</td>                                       
				                </tr>
				@endforeach
		    @endif                                  
		  </tbody>
        </table>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
   		 var val = $('#selectDepart').val();
   		 $.ajax({
			type: "GET",
			url: "{{route('getAttendancePersonalAjax')}}",
			//contentType: "application/json; charset=utf-8",
			data:{
					'department_id' : val,
					'selectPersonnel'  : $('#selectPersonnel').val(),
				},
			//dataType: "json",
			success: function(data){
				console.log(data);
				$("#selectPersonnel").html(data);
			}
		});

        $('#selectDepart').on('change',function(){
       		//alert($(this).val());
       		 //alert( this.value );
       		 var val = $(this).val();
       		/* $.ajaxSetup({
			        headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        }
			 });*/
       		 $.ajax({
				type: "GET",
				url: "{{route('getAttendancePersonalAjax')}}",
				//contentType: "application/json; charset=utf-8",
				data:{'department_id' : val},
				//dataType: "json",
				success: function(data){
					console.log(data);
					$("#selectPersonnel").html(data);
					$('#selectPersonnel').trigger('change.select2');
				}
			});

        });
		
	});

</script>
@endsection