@extends('layouts.master')

@section('title', 'Thiết bị')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.thietbi.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Thêm thiết bị</h4>
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

		        <form  class="form-horizontal" method="POST">
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Tên thiết bị <span class="required">*</span></label>
			            <div class="col-sm-6">
			              <input type="text" class="form-control" name="title"  required="required" value="{{ old('title') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Thuộc danh mục <span class="required">*</span></label>
			            <div class="col-sm-6">
                            <select name="parent_id" class="form-control">
                                <option value="0" selected disabled>--Trống--</option>
                                @if(!empty($cateDevice))
									{!! $cateDevice !!}
								@endif
                            </select>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Số lượng <span class="required">*</span></label>
			            <div class="col-sm-6">
			              <input type="number" class="form-control" name="number"  required="required" value="{{ old('number') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Hãng sản xuất</label>
			            <div class="col-sm-6">
			              <input type="text" class="form-control" name="maker" value="{{ old('maker') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Phiên bản hệ điều hành</label>
			            <div class="col-sm-6">
			              <input type="text" class="form-control" name="system" value="{{ old('system') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Cỡ màn hình</label>
			            <div class="col-sm-6">
			              <input type="text" class="form-control" name="screen_size" value="{{ old('screen_size') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Cấu hình</label>
			            <div class="col-sm-6">
			              <input type="text" class="form-control" name="config" value="{{ old('config') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Thông tin khác</label>
			            <div class="col-sm-6">
                    		<textarea rows="4" onkeydown="expandtext(this);" name="others" class="form-control" >{{ old('others') }}</textarea>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Ngày mua <span class="required">*</span></label>
			            <div class="col-sm-6">
                    		<input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="date_buy" value='<?php echo ( isset( $_POST["date_buy"] ) )?$_POST["date_buy"]:date("d/m/Y")?>'>
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

@endsection