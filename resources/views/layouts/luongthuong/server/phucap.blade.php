@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<?php
	if( isset($_GET['selectMonth']) ){
		$selectMonth = $_GET['selectMonth'];
		$selectYear = $_GET['selectYear'];
	}else{
		$selectMonth = date('m');
		$selectYear = date('Y');
	}
?>
<div class="row box_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thông tin Thưởng &amp; phụ cấp</h4>
			<div class="box_search">
				<div class="row">
					<form action="" method="get">
						<div class="form-group col-lg-3">
							<label for="selectMonth" class="col-sm-4 control-label" style="padding-top: 7px;">Tháng</label>
							<div class="col-sm-8">
								 <select name="selectMonth" class="form-control">
								 <?php 
					                for ($i = 1; $i <= 12; $i++){
									    $month = ($i < 10) ? '0'.$i : $i ;
									    echo '<option value="'.$month.'"';
									    if (!empty(Request::input('selectMonth'))) {
									    	if ($i == Request::input('selectMonth')) echo ' selected="selected"';
									    }else{
									    	if ($i == date("n")) echo ' selected="selected"';
									    }						    
									    echo '>'.$month.'</option>';
									}
								?>
					             </select>
							</div>
						</div>
						<div class="form-group col-lg-3">
							<label for="enddate" class="col-sm-4 control-label" style="padding-top: 7px;">Năm</label>
							<div class="col-sm-8">
								<select name="selectYear" class="form-control">
									<?php
									for($i=date("Y")-5;$i<=date("Y");$i++) {
										 if (!empty(Request::input('selectYear'))) {
									    	$sel = ($i == Request::input('selectYear')) ? 'selected' : '';
									    }else{
									    	$sel = ($i == date('Y')) ? 'selected' : '';
									    }	   
									    echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group col-lg-6">
							<label for="enddate" class="col-sm-6 control-label" style="padding-top: 7px;">Ngày chốt lương thưởng Tết</label>
							<div class="col-sm-6">
                                <input type="text" class="datepicker form-control" id="dayLatches" value="{{ BatvHelper::getDayLatches( $selectMonth,$selectYear ) }}">
							</div>
						</div>
				         <button type="submit" class="btn btn-sm btn-orange hidden" id="autoClick">Tìm kiếm</button>
					</form>
				</div>
			</div>
			<form action="" method="post">
			  @if(count($errors) > 0)
		      <div class="alert alert-danger" role="alert">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		      </div>
		      @endif
		      @if (session('flash_message_err') != '')
				<div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
			  @endif
			  @if (session('flash_message_succ') != '')
		     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		      @endif
		        <div id="pre_ajax_loading" style="display: none;text-align: center;"><img src="{{ asset('images/general/bx_loader.gif') }}"></div>
		        <div class="ajax_response" style="display: none;"></div>
		        
				<h4 class="title-fuction">
						Thông tin Thưởng &amp; phụ cấp 
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
						?>
					<div class="pull-right" >
						@if( empty($data) || $data[0]->status ==1)
							<button type="button" class="btn btn-sm btn-orange special bonus"><img src="{{ asset('images/general/calculator.png') }}"></button>
						@endif
					</div>
				</h4>
				<div class="table-responsive" id="parent">
				    <table id="fixTable" class="table table-bordered table-striped">
				        <thead>
				            <tr>
				                <th class="text-center" style="min-width: 210px;">Họ và tên</th>
				                <th class="text-center"> <small>KI(nq)</small></th>
				                <th class="text-center"> <small>KI(tn)</small></th>
				                <th class="text-center"> <small>KI(hs)</small></th>
				                <th class="text-center"> <small>Thưởng ngày lễ</small></th>
				                <th class="text-center"> <small>Thưởng dự án</small></th>
				                <th class="text-center"> <small>Phụ cấp ăn trưa</small></th>
				                <th class="text-center"> <small>Phụ cấp xăng xe</small></th>
				                <th class="text-center"> <small>Phụ cấp tiền gửi xe</small></th>
				                <th class="text-center"> <small>Phụ cấp ĐT</small></th>
				                <th class="text-center"> <small>Phụ cấp trách nhiệm</small></th>
				                <th class="text-center"> <small>Phụ cấp khác(Nếu ko tham gia BH)</small></th>
				                <th class="text-center"> <small>Phụ cấp laptop cá nhân</small></th>
								<th class="text-center"> <small>Phụ cấp phong trào</small></th>
				                <th class="text-center"> <small>Tổng</small></th>
				            </tr>
				        </thead>
				        <tbody>
						    @if(!empty($data))
					    		<?php
					    			$total_holiday_bonus = $total_work_bonus = $total_lunch_allowance =$total_travel_allowance = $total_parking_fee_allowance = $total_phone_allowance=$total_management_allowance = $total_other_tax_allowance = $total_laptop_allowance = $total_movement_allowance = 0;
					    		?>
						     	@foreach ($data as $val)
									@if (empty($val->date_out) || (!empty($val->date_out) && strtotime($val->date_out) > strtotime( $selectYear.'-'.$selectMonth.'-01' )))
						    		<?php
						    			$total_holiday_bonus += $val->holiday_bonus;
						    			$total_work_bonus += $val->work_bonus;
						    			$total_lunch_allowance += $val->lunch_allowance;
						    			$total_travel_allowance += $val->travel_allowance;
						    			$total_parking_fee_allowance += $val->parking_fee_allowance;
						    			$total_phone_allowance += $val->phone_allowance;
						    			$total_management_allowance += $val->management_allowance;
						    			$total_other_tax_allowance += $val->other_tax_allowance;
						    			$total_laptop_allowance += $val->laptop_allowance;
										$total_movement_allowance += $val->movement_allowance;
										$ki_rules = ($val->ki_rules == 1) ? 1 : $val->ki_rules;
										$ki_performance = ($val->ki_performance == 1) ? 1 : $val->ki_performance;
										$ki_seniority = ($val->ki_seniority == 1) ? 1 : $val->ki_seniority;
						    		?>
									     <tr>
									      	<td class="text-nowrap" scope="row" style="min-width: 210px;"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
									      	<td>{{ $ki_rules }}</td>
									      	<td>{{ $ki_seniority }}</td>
									      	<td>{{ $ki_performance }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus) }}</td>
											<td>{{ BatvHelper::formatPrice($val->work_bonus) }}</td>
											<td>{{ BatvHelper::formatPrice($val->lunch_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->travel_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->parking_fee_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->phone_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->management_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->other_tax_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->laptop_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->movement_allowance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->holiday_bonus+$val->work_bonus+$val->lunch_allowance+$val->travel_allowance+$val->parking_fee_allowance+$val->phone_allowance+$val->management_allowance+$val->other_tax_allowance+$val->laptop_allowance+$val->movement_allowance) }}</td>
									    </tr>
									@endif
						    	@endforeach
				    					<tr style="background: rgba(255, 0, 0, 0.56);">
				    						<td><b>TỔNG HỢP</b></td>
				    						<td></td>
				    						<td></td>
				    						<td></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_holiday_bonus) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_work_bonus) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_lunch_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_travel_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_parking_fee_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_phone_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_management_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_other_tax_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_laptop_allowance) }}</b></td>
											<td><b>{{ BatvHelper::formatPrice($total_movement_allowance) }}</b></td>
				    						<td><b>{{ BatvHelper::formatPrice($total_holiday_bonus+$total_work_bonus+$total_lunch_allowance+$total_travel_allowance+$total_parking_fee_allowance+$total_phone_allowance+$total_management_allowance+$total_other_tax_allowance+$total_laptop_allowance+$total_movement_allowance) }}</b></td>
				    					</tr>
						    @endif
				        </tbody>
				    </table>
				</div>
				{{ csrf_field()}}
			</form>
			<script type="text/javascript">
				$(document).ready(function(){
					$('.box_salary table').on('scroll', function () {
						
					    $(".box_salary table > *").width($("table").width() + $("table").scrollLeft());
					});
					var $stickyHeader = $('.box_salary table thead tr th:first-child');
					var $stickyCells = $('.box_salary table tbody tr td:first-child');

					$('.box_salary table').on('scroll', function () {
					    $stickyHeader.css('left', ($(this).scrollLeft()+'px'));
					    $stickyCells.css('left', ($(this).scrollLeft()+'px'));
					});
				});

				$('body').on('click','.special.bonus',function(){

					var link = "{{ route('getAllowanceAjax') }}";

					//alert(string_id);
					var data = {
							selectMonth:<?php echo isset($_GET['selectMonth'])?$_GET['selectMonth']:date('m') ?>,
							selectYear:<?php echo isset($_GET['selectYear'])?$_GET['selectYear']:date('Y') ?>,
							dayLatches:$('#dayLatches').val(),
						};
					// console.log(data);return false;
					$.ajax({
						url: link, //Relative or absolute path to response.php file
						data: data,
			            beforeSend: function() {
			                $("#pre_ajax_loading").show();
			            },
			            complete: function() {
			                $("#pre_ajax_loading").hide();
			                $(".result-alert").show();
			            },
				        success: function (response) {
							var obj = $.parseJSON(response);
							if(obj.Response=='Error')
							{
								$(".ajax_response").removeClass('alert-success').addClass("alert-danger");
								$(".ajax_response").html(obj.Error);
								$(".ajax_response").show('slow');
							}else{
								$(".ajax_response").removeClass('alert-danger').addClass("alert-success");
								$(".ajax_response").html(obj.Message);
								$(".ajax_response").show('slow');
							}
							setTimeout(function() {
								window.location.reload();
							}, 3000);

				        },
				        error: function (data) {
				            console.log('Error:', data);
				        }
					});
				})
			</script>
	</div>
</div>
@endsection