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
						<div class="form-group">
							<label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
							<div class="col-sm-8">
							  	<select class="form-control" name="type">
								    <option value="0" <?php echo ( isset($data->type) && $data->type==0 )? 'selected':''; ?>>Gửi Email tin tức</option>
								    <option value="1" <?php echo ( isset($data->type) && $data->type==1 )? 'selected':''; ?>>Gửi Email đi muộn</option>
								    <option value="2" <?php echo ( isset($data->type) && $data->type==2 )? 'selected':''; ?>>Gửi Email lương</option>
                                    <option value="3" <?php echo ( isset($data->type) && $data->type==3 )? 'selected':''; ?>>Gửi Email thông báo được xét tăng lương</option>
                                    <option value="4" <?php echo ( isset($data->type) && $data->type==4 )? 'selected':''; ?>>Gửi Email cho quản lý khi n/v đánh giá xét tăng lương</option>
                                    <option value="5" <?php echo ( isset($data->type) && $data->type==5 )? 'selected':''; ?>>Email nhận thông tin quỹ khi được thêm mới</option>
                                    <option value="6" <?php echo ( isset($data->type) && $data->type==6 )? 'selected':''; ?>>Gửi Email thông báo kết quả xét tăng lương cho n/v</option>
                                    <option value="7" <?php echo ( isset($data->type) && $data->type==7 )? 'selected':''; ?>>Gửi Email thông báo kết quả xét tăng lương cho bộ phận kế toán,quản lý của n/v</option>
									<option value="8" <?php echo ( isset($data->type) && $data->type==8 )? 'selected':''; ?>>Gửi Email thông báo cho n/v khi được duyệt tiền truy lĩnh</option>
									<option value="9" <?php echo ( isset($data->type) && $data->type==9 )? 'selected':''; ?>>Gửi Email khi n/v gửi thông báo trả nợ đinh kỳ</option>
									<option value="10" <?php echo ( isset($data->type) && $data->type==10 )? 'selected':''; ?>>Gửi Email khi n/v gửi thông báo tất toán</option>
									<option value="11" <?php echo ( isset($data->type) && $data->type==11 )? 'selected':''; ?>>Gửi Email khi kế toán CHỐT LƯƠNG</option>
									<option value="12" <?php echo ( isset($data->type) && $data->type==12 )? 'selected':''; ?>>Gửi Email khi có cập nhật thay đổi hệ số, phụ cấp</option>
									<option value="13" <?php echo ( isset($data->type) && $data->type==13 )? 'selected':''; ?>>Gửi Email nhắc xét tăng lương</option>
							  	</select>
							</div>
						</div>
						@if($data->type != 9 && $data->type != 10) 
						<div class="form-group">
							<label for="title" class="col-sm-4 control-label">Mô tả </label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" >{{ old('description',isset($data->description) ? $data->description : null ) }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="mail_subject" class="col-sm-4 control-label">Tiêu đề email <span class="required">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="mail_subject" value="{{ old('mail_subject',isset($data->mail_subject) ? $data->mail_subject : null )}}" required>
							</div>
						</div>
						<div class="form-group">
							<label for="mail_content" class="col-sm-4 control-label">Nội dung mail</label>
							<div class="col-sm-8">
							  	<textarea rows="4" onkeydown="expandtext(this);" name="mail_content" class="form-control" requried>{{ old('mail_content',isset($data->mail_content) ? $data->mail_content : null ) }}</textarea>
					            <script type="text/javascript">
					              CKEDITOR.replace('mail_content');
					            </script>
							</div>
						</div>
						@endif
<!-- 						Nếu là loại Gửi Email thông báo xét tăng lương vs Cấu hình Email báo cho quản lý khi n/v đánh giá xét tăng lương xong thì bỏ phầm mày( Ko cần thiết ) -->
						@if($data->type != 3 && $data->type != 4) 
							@if($data->type != 9 && $data->type != 10 && $data->type != 11  && $data->type != 12 && $data->type != 13) 
							<div class="form-group">
								<label class="col-sm-4 control-label">CC mail tới Quản lý <span class="required">*</span></label>
								<div class="col-sm-8">
									<div class="checkbox">
									  <label><input type="checkbox" value="1" name="cc_email" <?php echo ( isset($data->cc_email) && $data->cc_email==1 )?"checked":""; ?>></label>
									</div>
								</div>
							</div>
							@endif
							<div class="form-group">
								<label  class="col-sm-4 control-label">Chọn địa chỉ mail được gửi đến <span class="required">*</span></label>
								<div class="col-sm-8">
		                            <select name="mail_to[]" id="my-select-mail-to" multiple="multiple" >
		                                @foreach($listPersonnel as $key=>$value)
		                                     <option value="{{ $value['id'] }}" <?php echo ( isset($listPersonnel[$key]['ticket']) && $listPersonnel[$key]['ticket']==1 )?"selected":""; ?> >{{ $value['fullname'] }}</option>
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
						@endif
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