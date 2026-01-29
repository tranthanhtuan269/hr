@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')
<div class="row content-function">
    <!-- Danh muc -->
    @include('layouts.chiphi.menuleft')
    <div class="col-lg-10">
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
				<h4 class="title-fuction">
					Chỉnh sửa cài đặt ngoại tệ
                </h4>
				<form  class="form-horizontal" method="POST">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Tiêu đề <span class="required">*</span></label>
                        <div class="col-sm-5">  
                            <input type="text" name="title" class="form-control" value="{{old('title',isset($data['title']) ? $data['title'] : null )}}" required>
                        </div>
                    </div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
						<div class="col-sm-5">	
		                    <input type="text" onkeyup="format_curency( this.value );" id="numFormatResult" class="form-control" value="{{old('value',isset($data['value']) ? BatvHelper::formatPriceSpecial($data['value']) : null )}}" required>
		                    <input type="hidden" name="value" id="result" value="{{old('value',isset($data['value']) ? $data['value'] : null )}}">
                            <span style="color: red;font-style: italic;font-weight: 600;font-size: 12px;">(1$ = ... VNĐ)</span>
					    </div>
					</div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Thời gian hiệu lực <span class="required">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="apply_from" value='{{ BatvHelper::formatDate($data["apply_from"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Thời gian hết hiệu lực <span class="required">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="apply_to" value='{{ BatvHelper::formatDate($data["apply_to"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}'>
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