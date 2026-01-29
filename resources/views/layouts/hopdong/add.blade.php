@extends('layouts.master')

@section('title', 'Hợp đồng')

@section('content')

<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Thêm hợp đồng</h4>
		<div class="row">
			<div class="col-lg-12">
				@if (session('flash_message_succ') != '')
					 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
				@endif
				@if(count($errors) > 0)
				  <div class="alert alert-danger" role="alert">
				    <ul>
				        @foreach ($errors->all() as $error)
				            <li>{{ $error }}</li>
				        @endforeach
				    </ul>
				  </div>
				@endif
			</div>
			<div class="col-lg-offset-2 col-lg-8">
		        <form  class="form-horizontal" method="POST">
		          <div class="form-group">
			          <label class="col-sm-3 control-label"> Tên hợp đồng</label>
			            <div class="col-sm-9">
			              <input type="text" class="form-control" name="title"  required="required" @if ($errors->has('title')) autofocus value="" @else  value="{{ old('title',isset($data->title) ? $data->title : null)}}" @endif>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-3 control-label"> Mô tả</label>
			            <div class="col-sm-9">
			              <textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control"></textarea>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-3 control-label"> Thời hạn hợp đồng</label>
			            <div class="col-sm-9">
			              <input type="text" class="form-control" name="duration"  required="required" @if ($errors->has('duration')) autofocus value="" @else  value="{{ old('duration',isset($data->duration) ? $data->duration : null)}}" @endif>
			            </div>
		          </div>
		          <div class="text-center">
		            <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Thêm mới">
		          </div>
		            {{ csrf_field()}}
		        </form>
			</div>
		</div>
	</div>
		
</div>



@endsection