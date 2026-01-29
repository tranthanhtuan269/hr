@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row">
  	<div class="col-lg-3"></div>
  	<div class="col-lg-7">
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Quá trình công tác</th>
		    </tr>
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Chức danh - Đơn vị</td>
		    </tr>
		    @if(!empty($data))
		    	@foreach($data as $val)
				    <tr>
				    	<td>{{ $val->date_start }} - {{  $val->date_end }}</td>
				    	<td>{{$val->job}} - {{$val->title}}</td>
				    </tr>
				@endforeach
		    @endif
		    </tbody>
	    </table>
	    <table class="table table-hover table-bordered text-center">
		    <tbody>
		    <tr>
		      <th colspan="2">Hệ số chức danh</th>
		    </tr>
		    <tr>
		    	<td>Thời gian</td>
		    	<td>Hệ số chức danh</td>
		    </tr>
		    @if(!empty($ratio))
		    	@foreach($ratio as $val)
		    <tr>
		    	<td>{{ $val->apply_from }} - {{  $val->apply_to }}</td>
		    	<td>{{ $val->ratio }}</td>
		    </tr>
				@endforeach
		    @endif
		    </tbody>
	    </table>
	</div>
	<div class="col-lg-3"></div>

</div>
@endsection