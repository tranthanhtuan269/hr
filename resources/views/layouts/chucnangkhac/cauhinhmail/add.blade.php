@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')

<?php
	$data = array( 'job_title_id' ,'department_id');
?>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.chucnangkhac.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Thêm cấu hình email </h4> 
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
								<input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="type">
                                    <option value="0">Gửi Email tin tức</option>
                                    <option value="1">Gửi Email đi muộn</option>
                                    <option value="2">Gửi Email lương</option>
                                    <option value="3">Gửi Email thông báo được xét tăng lương</option>
                                    <option value="4">Gửi Email cho quản lý khi nhân viên đánh giá xét tăng lương </option>
                                    <option value="5">Email nhận thông tin quỹ khi được thêm mới</option>
                                    <option value="6">Gửi Email thông báo kết quả xét tăng lương cho nhân viên</option>
                                    <option value="7">Gửi Email thông báo kết quả xét tăng lương cho bộ phận kế toán,quản lý của nhân viên</option>
									<option value="8">Gửi Email thông báo cho nhân viên khi được duyệt tiền truy lĩnh</option>
									<option value="9">Gửi Email khi n/v gửi thông báo trả nợ đinh kỳ</option>
									<option value="10">Gửi Email khi n/v gửi thông báo tất toán</option>
									<option value="11">Gửi Email khi kế toán CHỐT LƯƠNG</option>
									<option value="12">Gửi Email khi có cập nhật thay đổi hệ số, phụ cấp</option>
									<option value="13">Gửi Email nhắc xét tăng lương</option>
                                </select>
                            </div>
                        </div>
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả </label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" ></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="mail_subject" class="col-sm-4 control-label">Tiêu đề email <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="mail_subject" value="{{ old('mail_subject') }}" required>
							</div>
						</div>
						<div class="form-group">
							<label for="mail_content" class="col-sm-4 control-label">Nội dung mail </label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="mail_content" class="form-control" requried>{{ old('mail_content',isset($data->mail_content) ? $data->mail_content : null ) }}</textarea>
					            <script type="text/javascript">
					              CKEDITOR.replace('mail_content');
					            </script>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">CC mail tới Quản lý <span class="required">*</span></label>
							<div class="col-sm-8">
								<div class="checkbox">
								  <label><input type="checkbox" value="1" checked name="cc_email" ></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-4 control-label">Chọn địa chỉ mail được gửi đến <span class="required">*</span></label>
							<div class="col-sm-8">
	                            <select name="mail_to[]" id="my-select-mail-to" multiple="multiple" >
	                                @foreach($listPersonnel as $key=>$value)
	                                     <option value="{{ $value->id }}" >{{ str_limit( $value->fullname, $limit = 35, $end = '...') }}</option>
	                                @endforeach
	                            </select>
	                    
								<script type="text/javascript">
									$(function() {
									    $('#my-select-mail-to').searchableOptionList({
									        showSelectAll: true,
									        maxHeight: '250px',
									    });
									}); 
								</script>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
							</div>
						</div>
					</form>
				</div>
			</div>

	</div>
</div>
@endsection