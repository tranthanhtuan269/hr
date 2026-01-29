@extends('layouts.master')

@section('title', 'Thiết bị')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.thietbi.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh mục thiết bị 			                        
					@if(in_array('thietbi-themdanhmucthietbi',$arr_route))
						<a href="{{ route('getCateDeviceAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
					@endif
            	</h4>
				@if (session('flash_message_succ') != '')
					 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
				@endif
				@if (session('flash_message_err') != '')
					 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
				@endif
				<div class="table-responsive">
                    @if(!empty($cateDevice))
						{!! $cateDevice !!}
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection