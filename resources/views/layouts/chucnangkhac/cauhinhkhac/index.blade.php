@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.chucnangkhac.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Cấu hình khác</h4>	
			  @if (session('flash_message_succ') != '')
		     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		      @endif
				<form class="form-horizontal" method="get" action="">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Tiêu đề</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="title" value="{{ Request::get('title') }}">
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
				<h4 class="title-fuction">Danh sách 
					@if(in_array('chucnangkhac-themcauhinhkhac',$arr_route))
						<a href="{{ route('addsettingOthers')}}"><img src="{{ asset('images/general/add.png') }}"></a>
					@endif
				</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>ID</th>
						      <th>Tên cấu hình </th>
						      <th>Giá trị</th>
						      <th>Mô tả</th>
						      <th>&nbsp;&nbsp;</th>
						    </tr>
					    @if(!empty($data))
					     	@foreach ($data as $val)
						     <tr>
						      <td>{{ $val->id }}</td>
						      <td> {{ $val->title }} </td>
						      <td> {{ $val->value }} </td>
						      <td> {{ $val->description }} </td>
						      <td>
									@if(in_array('chucnangkhac-suacauhinhkhac',$arr_route))
								       <a class="btn-edit" href="{{ route('editsettingOthers',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
									@endif
									@if(in_array('chucnangkhac-xoacauhinhkhac',$arr_route))
								       <a class="btn-delete" href="{{ route('deletesettingOthers',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> <img src="{{ asset('images/general/remove.png') }}"></a>
									@endif
						      </td>  
						    </tr>
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