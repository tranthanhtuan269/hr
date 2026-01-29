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
			<h4 class="title-fuction">Các khoản khác</h4>
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
						Thông tin các khoản khác 
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
						?>
					<div class="pull-right" >
						@if( empty($data) || $data['status'] ==1)
							<button type="button" class="btn btn-sm btn-orange special salaryOther"><img src="{{ asset('images/general/calculator.png') }}"></button>
						@endif
					</div>
				</h4>
				<div class="table-responsive" id="parent">
				    <table id="fixTable" class="table table-bordered table-striped">
				        <thead>
				            <tr>
				                <th class="text-center">Họ và tên</th>
				                <th class="text-center">Chi tiết ( VNĐ )</th>
				            </tr>
				        </thead>
				        <tbody>
						    @if(!empty($data['list']))
								<?php $total = 0; ?>
						     	@foreach ($data['list'] as $key=>$val)
								 	@if (empty($val->date_out) || (!empty($val->date_out) && strtotime($val->date_out) > strtotime( $selectYear.'-'.$selectMonth.'-01' )))
									    <tr>
									      	<td class="text-nowrap" scope="row"> {{ str_limit( $val['fullname'], $limit = 35, $end = '...') }} </td> 
									      	<td style="text-align: left; padding-left: 30px;">
									     	@foreach ( $val['income_value'] as $k=>$v )
									    		<?php
									    			$total += $v;
									    		?>
												@if( !empty($v) )
													<b>{{ $k }}</b> : {{ BatvHelper::formatPrice($v) }} <br>
												@endif
										    @endforeach
										    </td>
									    </tr>
									@endif
							    @endforeach
		    					<tr style="background: rgba(255, 0, 0, 0.56);">
		    						<td><b>TỔNG HỢP</b></td>
		    						<td><b>{{ BatvHelper::formatPrice($total) }}</b></td>
		    					</tr>
						    @endif
				        </tbody>
				    </table>
				</div>
				{{ csrf_field()}}
			</form>
			<script type="text/javascript">
				$('body').on('click','.special.salaryOther',function(){

					var link = "{{ route('getSalaryOtherAjax') }}";

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