@extends('layouts.master')

@section('title', 'Chức danh')

@section('content')

<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Thêm chức danh</h4>
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
		          <div class="form-group error">
		          <label class="col-sm-3 control-label" for="inputRolename"> Tên chức danh</label>
		            <div class="col-sm-9">
		              <input type="text" class="form-control has-error" name="title" required>
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