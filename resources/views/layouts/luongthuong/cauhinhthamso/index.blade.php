@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Cấu hình bộ tham số </h4> 
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
			@if (session('flash_message_err') != '')
				 <div class="alert alert-danger" role="alert"></span> {{ session('flash_message_err') }}</div>
			@endif
			<div class="row">
				@include('layouts.luongthuong.menusetting')
		        <form class="form-horizontal" method="get" action="">
		          <div class="form-group col-lg-6">
		            <div class="row">

		              <div class="col-lg-offset-3 col-lg-3"><label for="inputBirthday" class="control-label">Tên tham số</label></div>
		              <div class="col-lg-6">
		                <input type="text" class="form-control" name="title" value="{{ Request::get('title') }}">
		              </div>
		            </div>
		          </div>
		           <div class="form-group col-lg-6">
		                <div class="text-center">
		                  <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
		                  <input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
		                </div>
		              </div>
		              {{ csrf_field()}}
		        </form>
			      <div class="col-lg-12">
			        <h4 class="title-fuction">Danh sách bộ tham số  
						@if(in_array('luongthuong-themcauhinhthamso',$arr_route))
				      	   <a href="{{ route('addParametersConfig')}}"><img src="{{ asset('images/general/add.png') }}"></a>
						@endif
			        </h4>
			        <div class="table-responsive"> 
			          <table class="table table-hover">
			            <tbody>
			              <tr>
			                <th>STT</th>
			                <th>Tên tham số </th>
			                <th>Kiểu</th>
			                <th>Giá trị</th>
			                <th></th>
			              </tr>
							<?php 
								if( !isset($_GET['page']) || $_GET['page']==1 ){
									$i  = 1;
								}else{
									$i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
								}
							?>
			              @foreach($data as $val)
			              <tr>
			                <td>{{ $i }} </td> 
			                <td>{{ str_limit( $val->title, $limit = 45, $end = '...') }}</td>
			                <td>{{ BatvHelper::getTypeParameters($val->is_fixed) }}</td>
			                <td>{{ $val->value_org }} </td>
			                <td>
								@if(in_array('luongthuong-suacauhinhthamso',$arr_route))
									<a class="btn-edit" href="{{ route('editParametersConfig',['id'=>$val->id ]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
								@endif
								@if(in_array('luongthuong-xoacauhinhthamso',$arr_route))
				                  <a class="btn-delete" href="{{ route('deleteParametersConfig',['id'=>$val->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
					                    <img src="{{ asset('images/general/remove.png') }}"></a>
								@endif
			                </td>  
			              </tr>
			               	<?php $i++ ?>
			              @endforeach
			             
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
</div>
@endsection