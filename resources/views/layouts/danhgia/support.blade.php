@extends('layouts.master')

@section('title', 'Đánh giá')

@section('content')

<div class="row content-support">
	<div class="col-lg-2">
		<h4 class="title-fuction">Danh mục</h4>
		@if(in_array('danhgia-viethuongdan',$arr_route))
        	<p><a href="{{route('getEvaluationSupport')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hướng dẫn đánh giá</a></p>
		@endif
		@if(in_array('danhgia-danhsachbotieuchi',$arr_route))
        	<p><a href="{{route('listDepartmentCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tiêu chí</a></p>
		@endif
		@if(in_array('danhgia-danhsachtieuchi',$arr_route))
        	<p><a href="{{route('getEvaluationCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tiêu chí chi tiết</a></p>
		@endif
	</div>
	<div class="col-lg-10">
		<h4 class="title-fuction"> Hướng dẫn đánh giá</h4>
		<div class="table-responsive">
			<table class="table table-hover">
			    <tbody>
			        <tr class="text-center">
			            <th>Nội dung</th>
			            <th>Thao tác</th>
			        </tr>
			        <tr>
			            <td>
			            	{!! $data->criteria_content !!}
			            </td>
			            <td>
							@if(in_array('danhgia-edit',$arr_route))
                            	<a class="btn-edit" href="{{ route('editEvaluationSupport',['id'=>$data->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
							@endif
<!--                             <a class="btn-delete" href="{{ route('deleteEvaluationSupport',['id'=>$data->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                            <img src="{{ asset('images/general/remove.png') }}"></a> -->
			            </td>
			        </tr>

	            </tbody>
			</table>
		</div>
	</div>
</div>
@endsection