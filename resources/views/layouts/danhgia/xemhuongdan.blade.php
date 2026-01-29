@extends('layouts.master')

@section('title', 'Đánh giá')

@section('content')

<div class="row content-support">
	<div class="col-lg-12">
		<h4 class="title-fuction">Hướng dẫn đánh giá</h4>
		<div class="content detail-page detail">
			{!! $data->criteria_content !!}
		</div>
	</div>
</div>
@endsection