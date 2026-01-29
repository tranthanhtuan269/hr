@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<div class="row">
   <div class="col-lg-1"></div>
   <div class="col-lg-10">
   <h4 class="title-fuction">Thêm chu kỳ xét tăng lương</h4>
   @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	@endif
   @if (session('flash_message_err') != '')
	<div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
	@endif
	@if (session('flash_message_succ') != '')
	<div class="alert alert-success" role="alert">{{ session('flash_message_succ')}}</div>
	@endif

<?php
	// echo "<pre>";
	// print_r($data);die;
?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			{{ csrf_field()}}
			<div class="form-group">
				<label class="col-sm-3 control-label">Giá trị</label>
				<div class="col-sm-5">
					<input type="number" class="form-control" name="value" required step="0.5" value="{{ old('value') }}" min="0">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Mô tả</label>
				<div class="col-sm-5">
					<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control">{{ old('description') }}</textarea>
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
				</div>
			</div>
		</form>

			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách các chu kỳ</h4>
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th>STT</th>
						      <th>Giá trị</th>
						      <th> Mô tả </th>
						      <th>&nbsp;&nbsp;</th>
						    </tr>
						    @if(!empty($data))
								<?php 
									$i =  1;
								?>
						     	@foreach ($data as $val)
						     <tr>
						      <td>{{ $i }}</td>
						      <td> {{ $val->value }} </td>
						      <td> {{ $val->description }} </td>
						      <td>
									@if(in_array('luongthuong-suachukyxettangluong',$arr_route))
							       		<a class="btn-edit" href="{{ route('getSettingPeriodSalaryEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
									@endif
									@if(in_array('luongthuong-xoachukyxettangluong',$arr_route))
										<a class="btn-delete" href="{{ route('deleteSettingPeriodSalary',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="{{ asset('images/general/remove.png') }}"></a>
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
	</div>



   <div class="col-lg-1"></div>
</div>
@endsection