@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<div class="row box_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.client.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thông tin các khoản khác</h4>
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
				            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
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
				<h4 class="title-fuction">
						Chi tiết 
						<?php
							echo isset( $_GET['selectMonth'] )?$_GET['selectMonth']. "/":date('m') . "/" ;
							echo isset( $_GET['selectYear'] )?$_GET['selectYear']:date('Y');
						?>
				</h4>
				<div class="table-responsive" >
				    <table class="table table-bordered">
				        <thead>
				            <tr>
				                <th class="text-center">Họ và tên</th>
				                <th class="text-center">Chi tiết ( VNĐ )</th>
				            </tr>
				        </thead>
				        <tbody>
						    @if(!empty($data['list']))
						     	@foreach ($data['list'] as $key=>$val)
								    <tr>
								      	<td class="text-nowrap" scope="row"> {{ str_limit( $val['fullname'], $limit = 35, $end = '...') }} </td> 
								      	<td style="text-align: left; padding-left: 30px;">
								     	@foreach ( $val['income_value'] as $k=>$v )
											@if( !empty($v) )
												<b>{{ $k }}</b> : {{ BatvHelper::formatPrice($v) }} <br>
											@endif
									    @endforeach
									    </td>
								    </tr>
							    @endforeach
						    @endif
				        </tbody>
				    </table>
				</div>
				{{ csrf_field()}}
			</form>
	</div>
</div>
@endsection