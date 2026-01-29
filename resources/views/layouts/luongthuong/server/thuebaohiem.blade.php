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
			<h4 class="title-fuction">Thông tin Thuế &amp; Bảo hiểm</h4>
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
				          <div class="text-center">
				            <button type="submit" class="btn btn-sm btn-orange hidden" id="autoClick">Tìm kiếm</button>
				          </div>
						</div>
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
						Thuế &amp; Bảo hiểm
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
						?>
					<div class="pull-right" >
						@if( empty($data) || $data[0]->status ==1)
							<button type="button" class="btn btn-sm btn-orange special taxInsurrance"><img src="{{ asset('images/general/calculator.png') }}"></button>
						@endif
					</div>
				</h4>
				<div class="table-responsive" id="parent">
				    <table id="fixTable" class="table table-bordered table-striped">
				        <thead>
				            <tr>
				                <th class="text-center">Họ và tên</th>
				                <th class="text-center"> <small>Thuế TNCN</small></th>
				                <th class="text-center"> <small>Bảo hiểm(nhân viên phải đóng)</small></th>
				                <th class="text-center"> <small>Bảo hiểm(công ty phải đóng)</small></th>
				            </tr>
				        </thead>
				        <tbody>
						    @if(!empty($data))
					    		<?php
					    			$total_tax = $total_insurance = $total_insurance_by_company = 0 ;
					    		?>
						     	@foreach ($data as $val)
								 	@if (empty($val->date_out) || (!empty($val->date_out) && strtotime($val->date_out) > strtotime( $selectYear.'-'.$selectMonth.'-01' )))
										<?php
											$total_tax += $val->tax;
											$total_insurance += $val->insurance;
											$total_insurance_by_company += $val->insurance_by_company;
										?>
										<tr>
											<td class="text-nowrap" scope="row"> {{ str_limit( $val->fullname, $limit = 35, $end = '...') }} </td> 
											<td>{{ BatvHelper::formatPrice($val->tax) }}</td>
											<td>{{ BatvHelper::formatPrice($val->insurance) }}</td>
											<td>{{ BatvHelper::formatPrice($val->insurance_by_company ) }}</td>
										</tr>
									@endif
						    	@endforeach
			    					<tr style="background: rgba(255, 0, 0, 0.56);">
			    						<td><b>TỔNG HỢP</b></td>
			    						<td><b>{{ BatvHelper::formatPrice($total_tax) }}</b></td>
			    						<td><b>{{ BatvHelper::formatPrice($total_insurance) }}</b></td>
			    						<td><b>{{ BatvHelper::formatPrice($total_insurance_by_company) }}</b></td>
			    					</tr>
						    @endif
				        </tbody>
				    </table>
				</div>
				{{ csrf_field()}}
			</form>
			<script type="text/javascript">
				$('body').on('click','.special.taxInsurrance',function(){

					var link = "{{ route('getTaxInsurranceAjax') }}";

					//alert(string_id);
					var data = {
							selectMonth:<?php echo isset($_GET['selectMonth'])?$_GET['selectMonth']:date('m') ?>,
							selectYear:<?php echo isset($_GET['selectYear'])?$_GET['selectYear']:date('Y') ?>,
						};
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