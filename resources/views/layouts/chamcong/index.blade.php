@extends('layouts.master')

@section('title', 'Chấm công')

@section('content')
<?php
	$dateAttendance = ( !empty( Request::get('date') ) ) ? BatvHelper::formatDate( Request::get('date'),'d/m/Y', $formatDate="Y-m-d",$timeFormat="H:i:s",$time=false) : date('Y-m-d');
?>
<div class="row content-function">
    <div class="col-lg-3">
    	<h4 class="title-fuction">Giải thích ký hiệu</h4>
		   <table class="table">
		   		<thead> 
					<tr> 
						<th>Ký hiệu</th> 
						<th>Chú giải</th>  
						<th>Thao tác</th> 
					</tr> 
					</thead> 
				<tbody> 
					@if(!empty($info))
						@foreach($info as $val)
							<tr> 
								<td>{{$val->symbol}}</td>
								<td>
									{{$val->title}}
								</td> 
								<td>
									<a href="#" data-toggle="modal" data-target="#myModal_edit{{ $val->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true" id="edit"></i></a>
									<a href="#" data-toggle="modal" data-target="#myModal_delete{{ $val->id }}"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;" id="delete"></i></a>
								</td>
							</tr>
							<div id="myModal_edit{{ $val->id }}" class="modal fade" role="dialog">
							  <div class="modal-dialog">
							    <div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title text-center">Sửa thông tin</h4>
							            <div class="ajax_response text-center" style="display: none;"></div>
									</div>
									<form class="form-horizontal" id="contactFormSymbolEdit{{ $val->id }}">
										{!! csrf_field()!!}
										<div class="modal-body row">
											<div class="form-group clearfix">
											    <label class="col-sm-4 control-label" style="text-align: right;">Tên ký hiệu <span class="required">*</span></label>
											    <div class="col-sm-6">
											        <input type="text" id="symbol{{ $val->id }}" class="form-control" name="symbol" required value="{{$val->symbol}}" <?php echo ($val->id==1 || $val->id==11)?"disabled":""; ?> >
											    </div>
											</div>
											<div class="form-group clearfix">
											    <label class="col-sm-4 control-label" style="text-align: right;">Chú giải <span class="required">*</span></label>
											    <div class="col-sm-6">
											        <input type="text" id="title{{ $val->id }}" class="form-control" name="title" required value="{{$val->title}}">
											    </div>
											</div>
											<div class="form-group clearfix">
											    <label class="col-sm-4 control-label" style="text-align: right;">Kiểu <span class="required">*</span></label>
											    <div class="col-sm-6">
												    <label class="radio-inline">
												      <input type="radio" name="type{{ $val->id }}" value="1" required <?php if(  isset($val->type) && $val->type==1 ){ echo "checked"; } ?> <?php echo ($val->id==1||$val->id==2 ||$val->id==10  || $val->id==11|| $val->id==12)?"disabled":""; ?>>Hưởng lương
												    </label>
												    <label class="radio-inline">
												      <input type="radio" name="type{{ $val->id }}" value="0" <?php if(  isset($val->type) && $val->type==0){ echo "checked"; } ?> <?php echo ($val->id==1||$val->id==2|| $val->id==11|| $val->id==12)?"disabled":""; ?>>Nghỉ không lương
												    </label>
											    </div>
											</div>
											<input type="hidden" name="id" value="{{ $val->id }}">
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-sm btn-orange" onclick="updateData({{ $val->id }})">Cập nhật</button>
										</div>
									</form>
							    </div>
							  </div>
							</div>
							<!-- FORM XÓA -->
							<div id="myModal_delete{{ $val->id }}" class="modal fade" role="dialog">
							  <div class="modal-dialog">
    							
							    <div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title text-center">Bạn có thực sự muốn xóa ???</h4>
							            <div class="ajax_response text-center" style="display: none;"></div>
									</div>
									<form class="form-horizontal" id="contactFormSymbolEdit{{ $val->id }}">
										{!! csrf_field()!!}
										<div class="modal-footer">
											<button type="button" class="btn btn-sm btn-orange" onclick="deleteData({{ $val->id }})">Có</button>
											<button type="button" class="btn btn-sm btn-grey" data-dismiss="modal">Không</button>
										</div>
									</form>
							    </div>
							  </div>
							</div>
						@endforeach
					@endif
				</tbody>
			</table>


		<!-- FORM THÊM KÝ HIỆU -->
		<p class="text-center"><button type="button" class="btn btn-sm btn-orange" data-toggle="modal" data-target="#myModal" id="add">Thêm ký hiệu</button></p>
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Thêm ký hiệu</h4>
		            <div class="ajax_response text-center" style="display: none;"></div>
				</div>
				<form class="form-horizontal" method="post" action="" id="contactFormSymbol">
					{!! csrf_field()!!}
					<div class="modal-body row">
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Tên ký hiệu <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="symbol" required>
						    </div>
						</div>
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Chú giải <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="title" required>
						    </div>
						</div>
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Kiểu<span class="required">*</span></label>
						    <div class="col-sm-6">
							    <label class="radio-inline">
							      <input type="radio" name="type" value="1" required>Hưởng lương
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="type" value="0">Nghỉ không lương
							    </label>
						    </div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
					</div>
				</form>
		    </div>
		  </div>
		</div>
    </div>
	<div class="col-lg-9">
	   @if(count($errors) > 0)
      <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
      @endif
      @if (session('flash_message_err') != '')
		<div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
	  @endif
	  @if (session('flash_message_succ') != '')
     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
      @endif
		<h4 class="title-fuction">QUẢN TRỊ CHẤM CÔNG</h4>
		<form class="form-horizontal" method="get" action="">
			<div class="form-group col-lg-6">
				<label for="date" class="col-sm-4 control-label">Ngày :</label>
				<div class="col-sm-8">
					@if ( !empty( Request::get('date') ) )
						<input type="text"  class="form-control datepicker" name="date"  required placeholder="Ngày" value="{{ Request::get('date') }}">
					@else
						<input type="text"  class="form-control datepicker" name="date"  required placeholder="Ngày" value="{{ date('d/m/Y') }}">
					@endif

				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
				<div class="col-sm-8">	
	               <select name="selectDepart" id="department" class="form-control select2 wrap">
		                {!! $department !!}
		            </select>
		            <script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})

						var get_selectDepart = '{{ Request::get('selectDepart') }}';

						if (get_selectDepart != '') {
							$("#department").select2().select2('val', "{{ Request::get('selectDepart') }}");
						}

		            </script>
                </div>
			</div>
			<div class="col-lg-12 text-center">
				<input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
			</div>
			{{ csrf_field()}}
		</form>
		<form class="form-horizontal" method="post" action="" id="contactForm">
			<table class="table table-hover" id="data_default"> 
				<thead> 
					<tr> 
						<th>Id</th> 
						<th>Họ và tên</th> 
						<th>Email</th>
						<th>Chấm công đi làm</th> 
						<th>Đơn vị (ngày)</th> 
						<th>Chấm công đi muộn</th> 
						<th>Thời gian đi muộn (phút)</th> 
					</tr> 
				</thead> 
				<tbody class="list-personnel"> 
			@if(!empty($data))
				@foreach ($data as $val)
			
					<tr> 
						<th scope="row">{{ $val->id }}</th> 
						<td>
							{{ str_limit( $val->fullname, $limit = 45, $end = '...') }}
						</td> 
						<td>
							{{$val->email}}
						</td> 
						<td style="text-align: center;">
							<select class="select_type" name='typeAttendance_1[{{ $val->id }}]' id="typeAttendance_1_{{$val->id}}">	
				            @foreach($listType_1 as $v)
				                <option value="{{ $v->id }}" @if( old('typeAttendance_1') && old('typeAttendance_1') == $v->id ) selected='selected' @endif >{{ $v->symbol }}</option>
				            @endforeach
							</select>
						</td> 
						<td style="text-align: center;">
							<select  name='unit_date[{{ $val->id }}]'>	
								@if( $check_saturday == 1)
					           		<option value="1">1</option>
					           		<option value="0.5">0.5</option>
				           		@else
				           			<option value="0.5">0.5</option>
				           		@endif
							</select>
						</td> 
						<td style="text-align: center;">
							<select class="select_type" name='typeAttendance_2[{{ $val->id }}]' id="typeAttendance_2_{{$val->id}}">		
				            @foreach($listType_3 as $v)
				                <option value="{{ $v->id }}" @if( old('typeAttendance_2') && old('typeAttendance_2') == $v->id ) selected='selected' @endif >{{ $v->symbol }}</option>
				            @endforeach
							</select>
							
						</td> 
						<td>
							<input type="text" class="form-control" name="time_late[{{ $val->id }}]">
						</td> 
					</tr> 
				@endforeach
			@endif
				</tbody>
			</table>
			<div class="col-lg-12 text-right">

			</div>
			<div id="result" class="text-center"></div>
	
			@if( ( $paramAttendanceStatus == 1 && BatvHelper::handlingTime(date('Y-m-d')) > BatvHelper::handlingTime($dateAttendance ) ) || ( BatvHelper::handlingTime(date('H:i:s')) >= BatvHelper::handlingTime( '17:00:00' ) && BatvHelper::handlingTime(date('Y-m-d')) == BatvHelper::handlingTime( $dateAttendance  )) )
				<div class="form-group col-lg-12">
		          <div class="text-center">
		          	<input type="hidden"  name="check_saturday" value="{{ $check_saturday }}">
		            <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
		            <button type="reset" class="btn btn-sm btn-orange">Nhập lại</button>
		          </div>
		        </div>
	        	{{ csrf_field()}}
			@endif
		</form>
		<script>
			$(document).ready(function(){
			    $('input[name="date"]').change(function(){
			        $('.list-personnel').remove();
			    });
			    $("#edit,#add,#delete").click(function(){
			        $(".ajax_response div").remove();
			        $(".ajax_response").removeClass('alert-success');
			        $(".ajax_response").removeClass('alert-error');
			    });
			});
		</script>
		<script type="text/javascript">
                function updateData(id){
                		 var param = "type"+id;
                         var title = $('#title'+id).val();
                         var symbol = $('#symbol'+id).val();
                         var type = $('input[name='+param+']:checked').val();
                         var link = "{!! route('editAttendanceSymbolAjax') !!}";
                         var data = {
                         		id:id,
                                title:title,
                                symbol: symbol,
                                type: type,
                             };
                         $.ajax({
                            url: link,
                            data: data,
                            success: function (response) {
                                var obj = $.parseJSON(response);
                                if(obj.Response=='Error'){	
							        $.each( obj.Error, function( key, value) {
                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
                                        $(".ajax_response").append('<div style="padding:5px 0px;">'+value+'</div');
							        });
							        $(".ajax_response").show('slow');
                                }
                                else{
                                    $(".ajax_response").removeClass('alert-danger').addClass("alert-success");
                                    $(".ajax_response").html(obj.Message);
                                    $(".ajax_response").show('slow');
	                                setTimeout(function() {
	                                    window.location.reload();
	                                }, 3000);
                                }
                            },
                             error: function (data) {
                                 console.log('Error:', data);
                             }
                         });
                        $(".ajax_response div").remove();
                     	return false;
                }
                function deleteData(id){
                         var link = "{!! route('deleteAttendanceSymbolAjax') !!}";
                         var data = {
                         		id:id,
                             };
                         $.ajax({
                             url: link,
                             data: data,
                             success: function (response) {
                                 var obj = $.parseJSON(response);
                                 if(obj.Response=='Error'){
                                     $(".ajax_response").removeClass('alert-success').addClass("alert-error");
                                     $(".ajax_response").html('<div style="padding:5px 0px;">'+obj.Error+'</div');
                                     $(".ajax_response").show('slow');
                                 }
                                 else{
                                     $(".ajax_response").removeClass('alert-error').addClass("alert-success");
                                     $(".ajax_response").html(obj.Message);
                                     $(".ajax_response").show('slow');
	                                 setTimeout(function() {
	                                     window.location.reload();
	                                 }, 3000);
                                 }
                             },
                             error: function (data) {
                                 console.log('Error:', data);
                             }
                         });
                     	return false;
                }

                $(document).ready(function() {
                    $('#contactFormSymbol').submit(function(event) {
                            var title = $('#contactFormSymbol input[name="title"]').val();
                            var symbol = $('#contactFormSymbol input[name="symbol"]').val();
                            var type = $('#contactFormSymbol input[name="type"]:checked').val();
                            var link = "{!! route('addAttendanceSymbolAjax') !!}";
                            var data = {
                                    title:title,
                                    symbol: symbol,
                                    type: type,
                                };
                            $.ajax({
                                url: link, //Relative or absolute path to response.php file
                                data: data,
                                success: function (response) {
                                    var obj = $.parseJSON(response);
                                    if(obj.Response=='Error'){	
								        $.each( obj.Error, function( key, value) {
	                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
	                                        $(".ajax_response").append('<div style="padding:5px 0px;">'+value+'</div');
								        });
								        $(".ajax_response").show('slow');
                                    }
                                    else{
                                        $(".ajax_response").removeClass('alert-danger').addClass("alert-success");
                                        $(".ajax_response").html(obj.Message);
                                        $(".ajax_response").show('slow');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 3000);
                                    }
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        $(".ajax_response div").remove();
                        return false;
                    });
                });
		</script>

	</div>
</div>
@endsection