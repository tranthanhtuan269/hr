@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')

<div class="row box_salary">
	<!-- Danh muc -->
	@include('layouts.chucnangkhac.menuleft')

	<div class="col-lg-10">

			<h4 class="title-fuction">
				Cấu hình chấm công nghỉ phép
			</h4>
			@if(count($errors) > 0)
				<div class="alert alert-danger" role="alert">
				<ul>
				    @foreach ($errors->all() as $error)
				        <li>{{ $error }}</li>
				    @endforeach
				</ul>
				</div>
			@endif
			<div class="col-lg-12">
				@if (session('flash_message_succ') != '')
					 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
				@endif
			</div>
			<form  class="form-horizontal" method="POST">
				<div class="form-group">
					<label class="col-sm-3 control-label">Chọn nhân viên <span class="required">*</span></label>
					<div class="col-sm-5">	
                        @if(!empty($listPersonnel))
                            <select id="my-select-2" name="personnel_id[]" multiple="multiple">
                                @foreach($listPersonnel as $val)
                                     <option value="{{ $val->id }}" {{ ( is_array(old('personnel_id')) && in_array($val->id, old('personnel_id')) ) ? 'selected ' : '' }} >{{ $val->fullname }}</option>
                                @endforeach
                            </select>
                        @endif
						<script type="text/javascript">
							$(function() {
							    $('#my-select-2').searchableOptionList({
							        showSelectAll: true,
							        maxHeight: '250px',
							    });
							});    
						</script>
				    </div>
				</div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Thời gian hiệu lực <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="datepicker form-control" name="apply_from" pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ old('apply_from') }}" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Thời gian hết hiệu lực <span class="required">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="datepicker form-control" name="apply_to" pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ old('apply_to') }}" required>
                    </div>
                </div>
				<div class="text-center">
					<input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Thêm mới">
				</div>
	            {{ csrf_field()}}
			</form>

			<h4 class="title-fuction">Danh sách nhân viên được cấu hình</h4>
			<div class="table-responsive" >
			    <table class="table table-bordered table-striped">
			        <thead>
			            <tr>
			                <th class="text-center">STT</th>
			                <th class="text-center">Họ và tên</th>
			                <th class="text-center">Thời gian</th>
			            </tr>
			        </thead>
			        <tbody>	
						    @if(!empty($data))
						    	<?php $tmp=1; ?>
						     	@foreach ($data as $key=>$val)
							     <tr>
							      	<td class="text-center"> {{ $tmp }} </td> 
							      	<td style="text-align: left; padding-left: 30px;">
							      		{{ str_limit( BatvHelper::getInfoUser($val->personnel_id), $limit = 35, $end = '...') }}
								    </td>
								    <td>
								    	<?php
								    		$apply_from = explode(',', $val->apply_from);
								    		$apply_to = explode(',', $val->apply_to);
							    		?>
							    		@foreach ($apply_from as $k => $v) 
							    			<p>Từ {{ BatvHelper::formatDate( $v,'Y-m-d', $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }} đến {{ BatvHelper::formatDate( $apply_to[$k],'Y-m-d', $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
					                        @if(in_array('chucnangkhac-xoacauhinhchamcongnghiphep',$arr_route))
									       		<a class="btn-delete" href="{{ route('deletesettingAbsentAttendance',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
									       		<img src="{{ asset('images/general/remove.png') }}"></a>
					                        @endif
							    			</p>
							    		@endforeach
								    </td>
							    </tr>
							    <?php $tmp++; ?>
							    @endforeach
						    @endif
			        </tbody>
			    </table>
			</div>
	</div>
</div>
@endsection