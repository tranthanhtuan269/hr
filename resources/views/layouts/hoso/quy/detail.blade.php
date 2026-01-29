@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<?php
	// echo "<pre>";
	// print_r($data);die;
?>
<div class="row">
  	<div class="col-lg-3"></div>
  	<div class="col-lg-7">
  	    @if (session('flash_message_succ') != '')
	      <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
	     @endif
	     <h4 class="text-center">Họ tên : <span> {{ $name }} </span></h4>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Quỹ</th>
		       <th class="text-center">
					@if(in_array('hoso-themquynhanvien',$arr_route))
		       			<a href="{{ route('getFundsAddPersonnel',['id'=>$id])}}"><img src="{{ asset('images/general/add_2.png') }}"></a> 
					@endif	
		       </th>
		    </tr>
		    
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Thuộc quỹ</td>
		    	<td></td>
		    </tr>
		    @if(!empty($data))
		    	@foreach($data as $val)
		    <tr>
		    	<td>Từ <b>{{ BatvHelper::formatDate($val->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</b> đến <b>{{ BatvHelper::formatDate($val->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</b>  </td>
		    	<td><b>{{ $val->title }}</b></td>
		    	<td>
					@if(in_array('hoso-suaquynhanvien',$arr_route))
						<a href="{{ url('toh_hrm/hoso/suaquynhanvien',[$id,$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a> 
					@endif	
		    	
					@if(in_array('hoso-xoaquynhanvien',$arr_route))
						<a class="btn-delete" href="{{ url('toh_hrm/hoso/xoaquynhanvien',[$id,$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="{{ asset('images/general/remove.png') }}"></a>
					@endif	
		    		
		    	</td>
		    </tr>
		        @endforeach
		    @endif

		    </tbody>
	    </table>
	</div>
	<div class="col-lg-3"></div>

</div>
@endsection