@extends('layouts.master')

@section('title', 'Phòng ban')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.hoso.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Quản trị phòng ban</h4>	
			  @if (session('flash_message_succ') != '')
		     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		      @endif
			  @if (session('flash_message_err') != '')
		     	 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
		      @endif
				<form class="form-horizontal" method="get" action="">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Tên phòng ban</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="title"  autocomplete="off" placeholder="Tên phòng ban" value="{{ Request::get('title') }}">
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
				<h4 class="title-fuction">Danh sách phòng ban 			                        
					@if(in_array('hoso-themphongban',$arr_route))
						<a href="{{ route('addDepartment')}}"><img src="{{ asset('images/general/add.png') }}"></a>
					@endif
            	</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>STT</th>
						      <th>Tên phòng ban </th>
						      <th>Thuộc phòng ban </th>
						      <th>Người quản lý</th>
						      <th>&nbsp;&nbsp;</th>
						    </tr>
						    @if(!empty($data))
								<?php 
									if( !isset($_GET['page']) || $_GET['page']==1 ){
										$i  = 1;
									}else{
										$i = ($_GET['page']*10 -10) +1;
									}
								?>
						     	@foreach ($data as $val)
						     <tr>
						      <td>{{$i}}</td>
						      <td> {{ $val->title }} </td>
						      <td> {{ BatvHelper::getNameDepartmentbyId($val->parent_id) }} </td>
						      <td> {{ BatvHelper::getNamePersonnelbyId($val->manager_id)}} </td>
						      <td>
			                        @if(in_array('hoso-suaphongban',$arr_route))
							       		<a class="btn-edit" href="{{ route('getDepartmentEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
			                        @endif
			                        @if(in_array('hoso-xoaphongban',$arr_route))
							       		<a class="btn-delete" href="{{ route('getDepartmentDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
							       		<img src="{{ asset('images/general/remove.png') }}"></a>
			                        @endif

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