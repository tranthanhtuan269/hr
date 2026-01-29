@extends('layouts.master')

@section('title', 'Thiết bị')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.thietbi.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Sửa danh mục thiết bị</h4>
				@if (session('flash_message_succ') != '')
					 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
				@endif
				@if (session('flash_message_err') != '')
					 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
				@endif
		        <form  class="form-horizontal" method="POST">
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Tên danh mục</label>
			            <div class="col-sm-6">
			              <input type="text" class="form-control" name="title" value="{{old('title',isset($data['title']) ? $data['title'] : null )}}" required="required">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Thuộc danh mục</label>
			            <div class="col-sm-6">
                            <select name="parent_id" class="form-control">
                                <option value="0" selected>--Trống--</option>
                                @if(!empty($cateDevice))
									{!! $cateDevice !!}
								@endif
                            </select>
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

@endsection