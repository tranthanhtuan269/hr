@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<?php
	$data = array( 'job_title_id' ,'department_id');
?>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

	<div class="col-lg-10">
		<h4 class="title-fuction">Cấu hình chung </h4> 
		@if(count($errors) > 0)
			<div class="alert alert-danger" role="alert">
			<ul>
			    @foreach ($errors->all() as $error)
			        <li>{{ $error }}</li>
			    @endforeach
			</ul>
			</div>
		@endif
		@if (session('flash_message_succ') != '')
			 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		@endif
		<div class="row">
			@include('layouts.luongthuong.menusetting')
		</div>
	</div>
</div>
@endsection