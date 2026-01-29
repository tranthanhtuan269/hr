@extends('layouts.master')

@section('title', 'Quá trình công tác')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.hoso.menuleft')
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Quản trị quá trình công tác</h4>
				@if (session('flash_message_succ') != '')
					 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
				@endif
				<form class="form-horizontal" method="get" action="">
					<div class="form-group col-lg-6">
						<label for="hoten" class="col-sm-4 control-label">Họ tên</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="hoten" id="hoten" autocomplete="off" placeholder="Họ và tên" value="{{Request::get('hoten')}}">
						</div>
					</div>
					<div class="form-group col-lg-6">
						<label for="inputBirthday" class="col-sm-4 control-label">Đơn vị</label>
						<div class="col-sm-8">	
			               <select name="selectDepart" class="form-control select2 narrow wrap" >
				                <option value="0"> -- Đơn vị -- </option>
				                {!! $department !!}
				            </select>
				            <script type="text/javascript">
								var $select2 = $('.select2').select2({
								    containerCssClass: "wrap"
								})
				            </script>
		                </div>
					</div>
					<div class="form-group col-lg-12">
						 <div class="form-group">
				          <div class="text-center">
				            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
				          </div>
				        </div>
			        </div>
			        {{ csrf_field()}}
				</form>
			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách hồ sơ</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>Họ và tên</th>
						      <th>Email </th>    
						      <th>Đơn vị</th>
						      <th>Thâm niên</th>
						      <th>Quá trình công tác</th>
						    </tr>
						    @if(!empty($data))
						     	@foreach ($data as $val)
						     <tr>
						      <td>{{ $val->fullname}}</td>
						      <td> {{ $val->email }} </td>
						      <td>{{ $val->title }}</td>
						      <td>{{ BatvHelper::getSeniority($val->id) }}</td>
						      <td>
									@if(in_array('quatrinh-detail',$arr_route))
									   <a href="{{ route('getHistoryDetail',['id'=>$val->id ]) }}">Chi tiết</a>
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