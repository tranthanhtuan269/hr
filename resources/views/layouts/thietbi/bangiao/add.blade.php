@extends('layouts.master')

@section('title', 'Thiết bị')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.thietbi.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">Thêm bàn giao thiết bị</h4>
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
			          <label class="col-sm-4 control-label"> Chọn thiết bị <span class="required">*</span></label>
			            <div class="col-sm-6">
	                        @if(!empty($device))
	                            <select name="title" id="my-title" selected disabled >
<!-- 	                            	<option value="0" selected disabled>--Chọn--</option> -->
	                                @foreach($device as $val)
                                    <option value="{{ $val->id }}" @if( old('title') == $val->id ) {{ 'selected' }}  @endif >{{ $val->title }}</option>
	                                @endforeach
	                            </select>
	                        @endif
							<script type="text/javascript">
								$(function() {
								    $('#my-title').searchableOptionList({
								        maxHeight: '250px'
								    });
								}); 
							</script>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Số lượng <span class="required">*</span></label>
			            <div class="col-sm-6">
			              <input type="number" class="form-control" name="number"  required="required" value="{{ old('number') }}">
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Giao cho <span class="required">*</span></label>
			            <div class="col-sm-6">
	                        @if(!empty($listPersonnel))
	                            <select name="personnel_id[]" id="my-select"  multiple="multiple">
	                                @foreach($listPersonnel as $val)
	                                     <option value="{{ $val->id }}" {{ ( is_array(old('personnel_id')) && in_array($val->id, old('personnel_id')) ) ? 'selected ' : '' }}>{{ $val->fullname }}</option>
	                                @endforeach
	                            </select>
	                        @endif
							<script type="text/javascript">
								$(function() {
								    $('#my-select').searchableOptionList({
								        showSelectAll: true,
								        maxHeight: '250px'
								    });
								}); 
							</script>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Ngày giao <span class="required">*</span></label>
			            <div class="col-sm-6">
                    		<input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="date_in" value='<?php echo ( isset( $_POST["date_in"] ) )?$_POST["date_in"]:date("d/m/Y")?>'>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Trạng thái <span class="required">*</span></label>
			            <div class="col-sm-6">
                    		<select name="options" class="form-control">
                    			<option value="1">Đang sử dụng</option>
                    			<option value="0">Ngừng sử dụng</option>
                    		</select>
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Ghi chú</label>
			            <div class="col-sm-6">
                    		<textarea rows="4" onkeydown="expandtext(this);" name="note" class="form-control" id="note">{{ old('note') }}</textarea>
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