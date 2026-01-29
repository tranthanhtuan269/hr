@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')

<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.chucnangkhac.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Chỉnh sửa cấu hình email </h4> 
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
			<div class="row">
				<div class="col-lg-offset-1 col-lg-9">
					<form class="form-horizontal" method="post" action="" >
						{{ csrf_field()}}
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Tiêu đề <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="title" value="{{old('title',isset($data->title) ? $data->title : null )}}" required>
							</div>
						</div>
						<div class="form-group" style="display: none;">
							<label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
							<div class="col-sm-8">
							  	<select class="form-control" name="type" disabled>
								    <option value="0" <?php echo ( isset($data->type) && $data->type==0 )? 'selected':''; ?>>% lương thử việc</option>
							  	</select>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả </label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" >{{ old('description',isset($data->description) ? $data->description : null ) }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="value" value="{{ old('value',isset($data->value) ? $data->value : null )}}" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
							</div>
						</div>
					</form>
				</div>
			</div>

	</div>
</div>
@endsection