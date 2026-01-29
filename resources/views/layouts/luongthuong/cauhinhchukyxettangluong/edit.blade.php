@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<div class="row">
   <div class="col-lg-1"></div>
   <div class="col-lg-10">
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
			<div class="col-lg-12">
				<h4 class="title-fuction">Sửa chi tiết chu kỳ</h4>
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
					{{ csrf_field()}}
					<div class="form-group">
						<label class="col-sm-3 control-label">Giá trị</label>
						<div class="col-sm-5">
							<input type="number" class="form-control" name="value" required step="0.5" value="{{old('value',isset($data->value) ? $data->value : null )}}">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Mô tả</label>
						<div class="col-sm-5">
							<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control">{{old('description',isset($data->description) ? $data->description : null )}}</textarea>
						</div>
					</div>
					<div class="form-group text-center">
						<div>
							<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
						</div>
					</div>
				</form>
			</div>
	</div>



   <div class="col-lg-1"></div>
</div>
@endsection