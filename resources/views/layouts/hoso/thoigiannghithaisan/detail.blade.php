@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row">
  	<div class="col-lg-3"></div>
  	<div class="col-lg-7">
  	    @if (session('flash_message_succ') != '')
	      <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
	     @endif
	     <h4 class="text-center">Họ tên : <span> {{ \App\Models\Personnel::find(Request::route('id'))->fullname }} </span></h4>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		    	<td><b>Thời gian nghỉ thai sản</b></td>
		       	<td class="text-center">
		       		<a href="{{ route('addMaternityLeave',['id'=>Request::route('id')])}}"><img src="{{ asset('images/general/add_2.png') }}"></a> 
		       </td>
		    </tr>
		    @if(!empty($data))
		    	@foreach($data as $val)
		    <tr>
		    	<td>Từ <b>{{ BatvHelper::formatDate($val->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</b> đến <b>{{ BatvHelper::formatDate($val->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</b>  </td>
		    	<td>
					<a href="{{ url('toh_hrm/hoso/suathoigiannghithaisan',[Request::route('id'),$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
		    	
					<a class="btn-delete" href="{{ url('toh_hrm/hoso/xoathoigiannghithaisan',[Request::route('id'),$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="{{ asset('images/general/remove.png') }}"></a>
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