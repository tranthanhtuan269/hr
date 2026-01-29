@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<?php
	// echo "<pre>";
	// print_r($data);die;
?>
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.hoso.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
			  @if (session('flash_message_succ') != '')
		     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		      @endif
				<h4 class="title-fuction">Quản trị hồ sơ</h4>
				<form class="form-horizontal" method="get" action="" name="contact-form">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Họ tên</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="hoten" id="hoten" autocomplete="off" placeholder="Họ tên" value="{{ Request::get('hoten') }}">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="email" class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Email" value="{{ Request::get('email') }}">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="phone" class="col-sm-4 control-label">Điện thoại</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="phone" id="phone" autocomplete="off" placeholder="Số điện thoại" value="{{ Request::get('phone') }}">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="inputBirthday" class="col-sm-4 control-label">Đơn vị</label>
						<div class="col-sm-8">	
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
					<div class="form-group col-lg-12">
			          <div class="text-center">
			            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
						<input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
			          </div>
					</div>
			        {{ csrf_field()}}
				</form>
			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách hồ sơ </h4>
				@if( count($data)>0 )
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th class="text-center">Id</th>
						      <th>Họ và tên</th>
						      <th>Email </th>
						      <th>Ngày sinh </th>
						      <th>Điện thoại</th>
						      <th>Giới tính</th>
						      <th>Chức danh</th>
						      <th>Đơn vị</th>
						      <th style="width: 110px;">&nbsp;&nbsp;</th>
						    </tr>
						    
						     	@foreach ($data as $val)
						     <tr>
					     	  <td class="text-center">{{ $val->id }}</td>
						      <td>{{ str_limit( $val->fullname, $limit = 45, $end = '...') }}</td>
						      <td> {{ $val->email }} </td>
						      <td> @if ( !empty($val->birthday) ) {{ BatvHelper::formatDate($val->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }} @endif </td>
						      <td> {{ $val->phone_number }}</td>
						      @if($val->gender == 1)
						      	<td> Nam </td>
							  @else
							  	<td> Nữ </td>
						      @endif
						      <td>{!! $val->jobs !!}</td>
						      <td>{{ $val->title }}</td>
						      <td>
	                              @if(in_array('hoso-view',$arr_route))
	                                <a href="#" data-toggle="modal" data-target="#myModal_view{{ $val->id }}"><img src="{{ asset('images/general/eye.png') }}"></a>
	                                <!--  DETAIL POPUP FUNDS -->
	                                <div id="myModal_view{{ $val->id }}" class="modal fade" role="dialog">
	                                    <div class="modal-dialog">
	                                        <div class="modal-content clearfix">
	                                            <div class="modal-header">
	                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                                                <h4 class="modal-title text-center">Xem chi tiết</h4>
	                                                <div class="ajax_response text-center" style="display: none;"></div>
	                                            </div>
	                                            <div style="padding: 20px;">
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Họ và tên : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        {{ $val->first_name.' '.$val->last_name }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Giới tính : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															@if ($val->gender == 1)
																{{ 'Nam' }}
															@else
																{{ 'Nữ' }}
															@endif
	                                                    </div>
	                                                </div>
	                                                
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Ngày sinh : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                    	@if( $val->birthday != NULL )
	                                                        {{ BatvHelper::formatDate($val->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
	                                                        @endif
	                                                    </div>
	                                                </div>
	                                                
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Điện thoại : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        {{ $val->phone_number }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Số CMTND : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        {{ $val->indentity_card_id }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Ngày cấp CMTND : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        @if( $val->indentity_card_date != NULL ) {{ BatvHelper::formatDate($val->indentity_card_date,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }} @endif
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Nơi cấp CMTND : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        {{ $val->indentity_card_address }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Chức danh : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{!! $val->jobs !!}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Quỹ : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        {{  BatvHelper::getInfoFundsbyPersonnel( $val->id ) }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Giờ chấm công đi làm : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{{ $val->time_attendance_machine }}
	                                                    </div>
	                                                </div>
	                                                @if( $val->date_in != NULL )
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Ngày vào công ty : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{{ BatvHelper::formatDate($val->date_in,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
	                                                    </div>
	                                                </div>
													@endif

	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Chu kỳ xét tăng lương : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{{  ( $val->salary_frequency > 0 )?$val->salary_frequency.' năm':' Không được xét ' }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Loại hợp đồng : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                    	<?php
	                                                    		$contracts = BatvHelper::getContracts($val->id);
	                                                    	?>
															@if( $contracts )
																@foreach( $contracts as $k_contract => $v_contract )
																	{{ $v_contract->title .': '.BatvHelper::formatDate($v_contract->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false).' - '.BatvHelper::formatDate($v_contract->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)}} </br>
																@endforeach
															@endif
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Đơn vị : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{{ $val->title }}
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Mức lương cơ bản đóng bảo hiểm : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{{ BatvHelper::formatPriceSpecial($val->insurrance) }} VNĐ
	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Quê quán : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
															{{ $val->home_town }}
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                              @endif
									@if(in_array('hoso-edit',$arr_route))
									   <a class="btn-edit" href="{{ route('getPersonnelEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
									@endif
									@if(in_array('hoso-del',$arr_route))
							       		<a class="btn-delete" href="{{ route('getPersonnelDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
							        	<img src="{{ asset('images/general/remove.png') }}"></a>
									@endif
						      </td>  
						    </tr>
						    @endforeach
						    
					    </tbody>
					</table>
				</div>
				@else
					<div class="alert alert-danger" role="alert"> Không tìm thấy kết quả tìm kiếm</div>
				@endif
			</div>
			<div class="col-lg-12 text-right">
				{{ $data->appends(Request::all())->links() }} 
			</div>
		</div>
	</div>
</div>

@endsection