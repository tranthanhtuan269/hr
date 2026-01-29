@extends('layouts.master')

@section('title', 'Đánh giá')

@section('content')

<div class="row content-support">
	<div class="col-lg-2">
		<h4 class="title-fuction">Danh mục</h4>
        <p><a href="{{route('getEvaluationSupport')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hướng dẫn đánh giá</a></p>
        <p><a href="{{route('listDepartmentCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tiêu chí</a></p>
        <p><a href="{{route('getEvaluationCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tiêu chí chi tiết</a></p>

	</div>
	<div class="col-lg-10">
		<h4 class="title-fuction"> Sửa hướng dẫn đánh giá</h4>
		@if(count($errors) > 0)
			<div class="alert alert-danger" role="alert">
			<ul>
			    @foreach ($errors->all() as $error)
			        <li>{{ $error }}</li>
			    @endforeach
			</ul>
			</div>
		@endif
		@if (session('flash_message_err') != '')
			<div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
		@endif
		@if (session('flash_message_succ') != '')
			 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		@endif
		<form action="" method="post">
			<textarea rows="4" onkeydown="expandtext(this);" name="criteria_content" requried>{{ old('criteria_content',isset($data->criteria_content) ? $data->criteria_content : null ) }}</textarea>
			<script type="text/javascript">
				CKEDITOR.replace( 'criteria_content');
			</script>
			<div class="form-group col-lg-12">
			  <div class="text-center">
			    <input type="submit" class="btn btn-sm btn-orange" name="save" value="Cập nhật">
			  </div>
			</div>
			{{ csrf_field()}}
		</form>
	</div>
</div>
@endsection

