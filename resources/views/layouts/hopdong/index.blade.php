@extends('layouts.master')

@section('title', 'Hợp đồng')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.hoso.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Quản trị hợp đồng</h4>
				  @if (session('flash_message_succ') != '')
			     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
			      @endif
				<form class="form-horizontal" method="get" action="">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Tên hợp đồng</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="title"  autocomplete="off" placeholder="Tên hợp đồng" value="{{ Request::get('title') }}">
						</div>
					</div>
					 <div class="form-group col-lg-6">
			          <div class="text-center">
			            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
			          </div>
			        </div>
			        {{ csrf_field()}}
				</form>


			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách hợp đồng <!-- <a href="{{ route('addContract')}}"><img src="{{ asset('images/general/add.png') }}"></a> --></h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>STT</th>
						      <th>Tên hợp đồng </th>
						      <th>&nbsp;&nbsp;</th>
						    </tr>
						    @if(!empty($data))
								<?php 
									if( !isset($_GET['page']) || $_GET['page']==1 ){
										$i  = 1;
									}else{
										$i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
									}
								?>
						     	@foreach ($data as $val)
								     <tr>
								      <td>{{$i}}</td>
								      <td> {{ $val->title }} </td>
								      <td>
											@if(in_array('hoso-suahopdong',$arr_route))
									      	  <a class="btn-edit" href="{{ route('getContractEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
											@endif

		<!-- 							       <a class="btn-delete" href="{{ route('getContractDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
									        <img src="{{ asset('images/general/remove.png') }}"></a> -->
								      </td>  
								    </tr>
						    	<?php $i++ ?>
						    	@endforeach
						    @endif
					    </tbody>
					</table>
				</div>
			</div>
			<div class="col-lg-12 text-right">
				{{ $data->appends(Request::all())->links() }} 
			</div>
		</div>
	</div>
</div>

@endsection