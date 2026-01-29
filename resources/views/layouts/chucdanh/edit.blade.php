@extends('layouts.master')

@section('title', 'Chức danh')

@section('content')

<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Sửa chức danh</h4>
		<div class="row">
			<div class="col-lg-12">
			   @if (count($errors) > 0)
			    <div class="alert alert-danger">
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
			</div>
			<div class="col-lg-offset-2 col-lg-8">
		        <form  class="form-horizontal" method="POST">
		          <div class="form-group error">
		          <label class="col-sm-3 control-label" for="inputRolename"> Tên chức danh</label>
		            <div class="col-sm-9">
		              <input type="text" class="form-control" name="title"  value="{{old('title',isset($data->title) ? $data->title : null )}}" required="required">
		            </div>
		          </div>
		          <div class="text-center">
		            <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
		          </div>
		            {{ csrf_field()}}
		        </form>
			</div>
		</div>
	</div>
		
</div>



@endsection