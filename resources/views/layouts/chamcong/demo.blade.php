@extends('layouts.master')

@section('title', 'Chấm công')

@section('content')

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
									<a href="{{ route('editAttendanceSymbolAjax',['id'=>$val->id]) }}" data-toggle="modal" data-target="#myModal_edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>

		<!-- FORM SỬA -->
		<div id="myModal_edit" class="modal fade" role="dialog">
		  <div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Sửa thông tin</h4>
		            <div class="ajax_response text-center" style="display: none;"></div>
				</div>
				<form class="form-horizontal" method="post" action="" id="contactFormSymbol">
					{!! csrf_field()!!}
					<div class="modal-body row">
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Tên ký hiệu <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="symbol" required="" value="{{ $infoAttendanceSymbol->symbol }}">
						    </div>
						</div>
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Chú giải <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="title" required="" value="{{ $infoAttendanceSymbol->title }}">
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



		<!-- FORM THÊM KÝ HIỆU -->
		<p class="text-center"><button type="button" class="btn btn-sm btn-orange" data-toggle="modal" data-target="#myModal">Thêm ký hiệu</button></p>
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
						        <input type="text" class="form-control" name="symbol" required="">
						    </div>
						</div>
						<div class="form-group clearfix">
						    <label class="col-sm-4 control-label">Chú giải <span class="required">*</span></label>
						    <div class="col-sm-6">
						        <input type="text" class="form-control" name="title" required="">
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
	               <select name="selectDepart" id="department" class="form-control select2 narrow wrap" >
		                <option value="0"> -- Đơn vị -- </option>
		                {!! $department !!}
		            </select>
		            <script type="text/javascript">
						var $select2 = $('.select2').select2({
						    containerCssClass: "wrap"
						})
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
						<th>STT</th> 
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
				<?php $i = 1;?>
				@foreach ($data as $val)
			
					<tr> 
						<th scope="row">{{$i}}</th> 
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
					<?php $i++ ?>
				@endforeach
			@endif
				</tbody>
			</table>
			<div class="col-lg-12 text-right">
				{{ $data->appends(Request::query())->render()  }} 
			</div>
			<div id="result" class="text-center"></div>
			 <div class="form-group col-lg-12">
	          <div class="text-center">
	          	<input type="hidden"  name="check_saturday" value="{{ $check_saturday }}">
	            <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
	            <button type="reset" class="btn btn-sm btn-grey">Nhập lại</button>
	          </div>
	        </div>
	        {{ csrf_field()}}
		</form>
		<script>
			$(document).ready(function(){
			    $('input[name="date"]').change(function(){
			        $('.list-personnel').remove();
			    });
			});
		</script>
		<script type="text/javascript">
                $(document).ready(function() {
                    $('#contactFormSymbol').submit(function(event) {
                            var title = $('input[name="title"]').val();
                            var symbol = $('input[name="symbol"]').val();
                            var link = "{!! route('addAttendanceSymbolAjax') !!}";
                            var data = {
                                    title:title,
                                    symbol: symbol,
                                };
                            $.ajax({
                                url: link, //Relative or absolute path to response.php file
                                data: data,
                                success: function (response) {
                                    var obj = $.parseJSON(response);
                                    if(obj.Response=='Error'){
                                        $(".ajax_response").removeClass('alert-success').addClass("alert-danger");
                                        $(".ajax_response").html(obj.Error);
                                        $(".ajax_response").show('slow');
                                    }
                                    else{
                                        $(".ajax_response").removeClass('alert-danger').addClass("alert-success");
                                        $(".ajax_response").html(obj.Message);
                                        $(".ajax_response").show('slow');
                                    }
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 3000);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        return false;
                    });
                });
		</script>

	</div>
</div>
@endsection