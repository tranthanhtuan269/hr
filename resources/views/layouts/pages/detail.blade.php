@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')

<div class="special"></div>
<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-sm-offset-1 col-sm-10">
<!-- 				<h3 style="text-transform: uppercase;padding-bottom: 10px;">{{ $data->title }}</h3> -->
				<div class="content detail-page">
					{!! $data->content !!}
				</div>
			</div>	
		</div>
	</div>
</div>

@endsection