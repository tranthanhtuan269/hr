@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<?php
	$data = array( 'job_title_id' ,'department_id');
?>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thêm cấu hình nhóm người <a href="{{ route('addParametersConfig') }}"><img src="{{ asset('images/general/add.png') }}"></a></h4> 
			@if(count($errors) > 0)
				<div class="alert alert-danger" role="alert">
				<ul>
				    @foreach ($errors->all() as $error)
				        <li>{{ $error }}</li>
				    @endforeach
				</ul>
				</div>
			@endif
			@if (session('flash_message_succ') != '')
				 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
			@endif
			<div class="row">
				@include('layouts.luongthuong.menusetting')
				<div class="col-lg-offset-2 col-lg-7">
					<form class="form-horizontal" method="post" action="" id="add_group_personnel">
						{{ csrf_field()}}
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Tên nhóm <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="title" value="{{ old('title')}}" required>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả </label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" >{{ old('description')}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Loại <span class="required">*</span> </label>
							<div class="col-sm-8">
								<div class="table-responsive settingGroup">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
												<th class="text-center">Danh sách
												</th>
								                <th>
								                	<input type="checkbox" id="checkAllType">
								                </th>
								            </tr>
								        </thead>
								        <tbody class="detailType">
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Lương
								                </th>
								                <td>
								                	<input type="checkbox" name="type[0]" value="0" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Thưởng ngày lễ
								                </th>
								                <td>
								                	<input type="checkbox" name="type[1]" value="1" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Thưởng dự án
								                </th>
								                <td>
								                	<input type="checkbox" name="type[2]" value="2" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp ăn trưa
								                </th>
								                <td>
								                	<input type="checkbox" name="type[3]" value="3" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp xăng xe
								                </th>
								                <td>
								                	<input type="checkbox" name="type[4]" value="4" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp điện thoại
								                </th>
								                <td>
								                	<input type="checkbox" name="type[5]" value="5" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp trách nhiệm
								                </th>
								                <td>
								                	<input type="checkbox" name="type[6]" value="6" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Phụ cấp tiền gửi xe
								                </th>
								                <td>
								                	<input type="checkbox" name="type[13]" value="13" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Lương mặc đinh
								                </th>
								                <td>
								                	<input type="checkbox" name="type[7]" value="7" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Thuế
								                </th>
								                <td>
								                	<input type="checkbox" name="type[8]" value="8" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Bảo hiểm (nhân viên phải đóng)
								                </th>
								                <td>
								                	<input type="checkbox" name="type[9]" value="9" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Bảo hiểm (công ty phải đóng)
								                </th>
								                <td>
								                	<input type="checkbox" name="type[14]" value="14" >
								                </td>
								            </tr>
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Đi làm muộn
								                </th>
								                <td>
								                	<input type="checkbox" name="type[10]" value="10" >
								                </td>
								            </tr>
								            <tr>
												<th class="text-nowrap" scope="row">
													Tiền nghỉ phép
												</th>
												<td>
													<input type="checkbox" name="type[11]" value="11" >
												</td>
								            </tr> 
											<tr>
							                	<th class="text-nowrap" scope="row">
								                	Phụ cấp khác( P/c nếu không đóng bảo hiểm )
								                </th>
								                <td>
								                	<input type="checkbox" name="type[15]" value="15" >
								                </td>
								            </tr> 
											<tr>
												<th class="text-nowrap" scope="row">
													Sử dụng Laptop cá nhân
												</th>
												<td>
													<input type="checkbox" name="type[16]" value="16" >
												</td>
								            </tr> 
											<tr>
												<th class="text-nowrap" scope="row">
													Tiền liên hoan
												</th>
												<td>
													<input type="checkbox" name="type[17]" value="17" >
												</td>
								            </tr> 
											<tr>
							                	<th class="text-nowrap" scope="row">
								                	Phụ cập nhà ở
								                </th>
								                <td>
								                	<input type="checkbox" name="type[18]" value="18" >
								                </td>
								            </tr> 
											<tr>
							                	<th class="text-nowrap" scope="row">
								                	Phụ cập phong trào
								                </th>
								                <td>
								                	<input type="checkbox" name="type[19]" value="19" >
								                </td>
								            </tr> 
								            <tr>
								                <th class="text-nowrap" scope="row">
								                	Chi phí khác
								                </th>
								                <td>
								                	<input type="checkbox" name="type[12]" value="12" >
								                </td>
								            </tr>

								        </tbody>
								    </table>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-4 control-label">Chọn nhân viên<span class="required">*</span></label>
							<div class="col-sm-8">
								<div class="table-responsive settingGroup">
								    <table class="table table-bordered table-striped">
								        <thead>
								            <tr>
								                <th class="text-center">Họ và tên &nbsp;
													<input type="checkbox" checked id="working"> Đang làm việc &nbsp;
													<input type="checkbox" checked id="un-working"> Đã nghỉ việc
												</th>
								                <th>
								                	<input type="checkbox" id="checkAll">
								                </th>
								            </tr>
								        </thead>
								        <tbody class="detail">
								            @foreach ($listPersonnel as $value)
												<tr class="{{ ($value->date_out != '') ? 'un-working' : 'working' }}">
													<th class="text-nowrap" scope="row">
														{{ str_limit( $value->fullname, $limit = 35, $end = '...') }}
														<div class="ajax_response {{ $value->id }}" style="display: none;"></div>
													</th>
													<td>
														<input type="checkbox" name="personnel_id[{{ $value->id }}]" value="{{ $value->id }}" >
													</td>
								            	</tr>
								            @endforeach

								        </tbody>
								    </table>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
							</div>
						</div>
						<div class="ajax_response_special" style="display: none;"></div>
					</form>
				</div>
			</div>	
			<script type="text/javascript">
				$(document).ready(function() {
					$('#add_group_personnel').submit(function(event) {
						var link = "{{ route('addGroupPersonalConfigAjax') }}";
						var selected = [];
						$('.detail input:checked').each(function() {
						    selected.push($(this).val());
						});

						var selected_type = [];
						$('.detailType input:checked').each(function() {
						    selected_type.push($(this).val());
						});
						var title = $('input[name="title"]').val();
						var description = $('textarea[name="description"]').val();

						var data = {
							title : title,
							description : description,
							selected : selected,
							selected_type : selected_type,
						};
						$.ajax({
							url: link, 
							data: data,
					        success: function (response) {
								var obj = $.parseJSON(response);
			                    $.each(obj, function (index, value) {
			                    	if( value.Response=='Error' ){
										$(".ajax_response."+value.personnel_id).removeClass('alert-success').addClass("alert-error");
										$(".ajax_response."+value.personnel_id).html(value.Error);
										$(".ajax_response."+value.personnel_id).show('slow');
			                    	}else if( value.Response=='Error_special' ){
										$(".ajax_response_special").removeClass('alert-success').addClass("alert-error");
										$(".ajax_response_special").html(value.Error_special);
										$(".ajax_response_special").show('slow');
			                    	}else{
										$(".ajax_response_special").removeClass('alert-error').addClass("alert-success");
										$(".ajax_response_special").html(value.Message);
										$(".ajax_response_special").show('slow');
										setTimeout(function() {
											window.location.reload();
										}, 3000);
			                    	}
			                    });
					        },
					        error: function (data) {
					            console.log('Error:', data);
					        }
						});
						$(".ajax_response_special").hide();
						$(".ajax_response").hide();
						return false;
					})
				});

				$("#checkAll").click(function(){
				    $('.detail input:checkbox').not(this).prop('checked', this.checked);
				});

				$("#checkAllType").click(function(){
				    $('.detailType input:checkbox').not(this).prop('checked', this.checked);
				});

				$("#working").click(function(){
				    if ($('#working:checkbox:checked').length > 0) {
						$('.working').removeClass('hidden')
					} else {
						$('.working').addClass('hidden')
					}
				});

				$("#un-working").click(function(){
				    if ($('#un-working:checkbox:checked').length > 0) {
						$('.un-working').removeClass('hidden')
					} else {
						$('.un-working').addClass('hidden')
					}
				});

			</script>
	</div>
</div>
@endsection