@extends('layouts.master')

@section('title', 'Quá trình công tác')

@section('content')
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
			      <th style="width: 20%;">Thâm niên</th>
			      <th class="text-left">
						{{ BatvHelper::getSeniority($id) }}	      
			      </th>
			    </tr>
		    </tbody>
	    </table>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Quá trình công tác</th>
		      <th class="text-center">
					@if(in_array('quatrinh-add',$arr_route))
						<a href="{{ route('getHistoryAdd',['id'=>$id])}}"><img src="{{ asset('images/general/add_2.png') }}"></a>
					@endif		      
		      </th>
		    </tr>
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Chức danh - Đơn vị</td>
		    	<td></td>
		    </tr>
		    @if(!empty($data))
		    	@foreach($data as $val)
		    <tr>
		    	<td> {{ $val->date_start }} - {{ $val->date_end }} </td>
		    	<td>{{ $val->job }} - {{ $val->title }} </td>
		    	<td>
					@if(in_array('quatrinh-edit',$arr_route))
						<a href="{{ url('toh_hrm/quatrinh/edit',[$id,$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a> 
					@endif	
		    	
					@if(in_array('quatrinh-del',$arr_route))
						<a class="btn-delete" href="{{ url('toh_hrm/quatrinh/del',[$id,$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="{{ asset('images/general/remove.png') }}"></a>
					@endif	
		    		
		    	</td>
		    </tr>
		        @endforeach
		    @endif
		    </tbody>
	    </table>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Hệ số chức danh</th>
		       <th class="text-center">
					@if(in_array('quatrinh-addratio',$arr_route))
		       			<a href="{{ route('getHistoryAddRatio',['id'=>$id])}}"><img src="{{ asset('images/general/add_2.png') }}"></a> 
					@endif	
		       </th>
		    </tr>
		    
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Hệ số chức danh</td>
		    	<td></td>
		    </tr>

		    @if(!empty($ratio))
		    	@foreach($ratio as $val)
		    <tr>
		    	<td>{{ $val->apply_from }} - {{ $val->apply_to }}  </td>
		    	<td>{{ $val->ratio }}</td>
		    	<td>
					@if(in_array('quatrinh-editratio',$arr_route))
		       			<a href="{{ url('toh_hrm/quatrinh/editratio',[$id,$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
					@endif	
					@if(in_array('quatrinh-delratio',$arr_route))
		    			<a class="btn-delete" href="{{ url('toh_hrm/quatrinh/delratio',[$id,$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="{{ asset('images/general/remove.png') }}"></a>
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