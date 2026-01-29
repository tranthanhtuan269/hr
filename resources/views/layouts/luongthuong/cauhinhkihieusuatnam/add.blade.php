@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<style type="text/css">
	.setting_salary .repice_reference{ display: none; }
</style>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">
			<h4 class="title-fuction">Cấu hình KI(hiệu suất)
<!-- 				@if(in_array('luongthuong-themcauhinhkihieusuatnam',$arr_route))
					<a href="javascript:void(0)" id="addConfigKiPerformance"><img src="{{ asset('images/general/add.png') }}"></a>
				@endif -->
			</h4> 

			<div class="row">
				<div class="col-sm-12">
	                <form class="form-horizontal clearfix" method="get" action="">
	                    <div class="form-group col-lg-4">
	                        <label for="date" class="col-sm-3 control-label">Năm :</label>
	                        <div class="col-sm-8">
								<select name="selectYear" class="form-control">
									<?php
										for($i=date("Y")-3;$i<=date("Y");$i++) {
											 if (!empty(Request::input('selectYear'))) {
										    	$sel = ($i == Request::input('selectYear')) ? 'selected' : '';
										    }else{
										    	$sel = ($i == date('Y')) ? 'selected' : '';
										    }	   
										    echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
										}
									?>
								</select>
	                        </div>
	                    </div>
	                    <div class="form-group  col-lg-5">
	                        <label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
	                        <div class="col-sm-8">  
	                           <select name="selectDepart" id="department" class="form-control select2 wrap">
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
	                    <div class="form-group col-lg-2 text-right">
	                        <input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
	                    </div>
	                    {{ csrf_field() }}
	                </form>
				</div>

				<div class="col-sm-12">
					<h4 class="title-fuction">Danh sách nhân viên có KI(hiệu suất) năm {{ $year }}</h4>
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
						@if (session('flash_message_err') != '')
							 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
						@endif
						<form class="row" action="{{ route('postAddConfigKiPerformance') }}" method="post">
							<label class="col-sm-2" style="padding-left: 20px;">Chọn nhân viên</label>
							<div class="col-sm-4">
								<div class="formAddConfigKiPerformance">
									<div class="form-group">
				                        @if(!empty($listPersonnel))
				                            <select id="my-select-2" name="personnel_ki_performance[]" multiple="multiple">
				                                @foreach($listPersonnel as $val)
				                                     <option value="{{ $val->id }}">{{ $val->fullname }}</option>
				                                @endforeach
				                            </select>
				                        @endif
										<script type="text/javascript">
											$(function() {
											    $('#my-select-2').searchableOptionList({
											        showSelectAll: true,
											        maxHeight: '250px',
											    });
											});    
										</script>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<input type="number" name="ki_performance" placeholder="KI hiệu suất(%)" class="form-control" style="height: 30px;" required step="0.01">
								<input type="number" value="{{ $year }}" name="year" class="hidden">
							</div>
							<div class="col-sm-2">
								<input type="submit" class="btn btn-sm btn-orange" value="Thêm mới">
							</div>
							{{ csrf_field() }}
						</form>

					<div class="table-responsive">
					    <table class="table table-bordered table-striped">
					        <thead>
					            <tr>
					                <th class="text-center">STT</th>
					                <th class="text-center">Họ và tên</th>
					                <th class="text-center">KI hiệu suất(%)</th>
					                <th class="text-center">Đơn vị</th>
					                <th class="text-center"></th>
					            </tr>
					        </thead>
					        <tbody>
				        	@if ($data)
				        		@foreach ($data as $key => $value)
					            <tr>
					                <td class="text-center">{{ $key + 1 }}</td>
					                <td style="text-align: left;" class="fullname{{ $value->personnel_id }}">{{ $value->fullname }}</td>
					                <td class="text-center ki{{ $value->personnel_id }}">{{ $value->ki }}</td>
					                <td class="text-center">{{ $value->title }}</td>
					                <td class="text-center">
					                    <a href="javascript:void(0)" onclick="editItemEditKi({{ $value->personnel_id }})" style="padding-right: 5px;"> <i class="fa fa-pencil" aria-hidden="true"></i> </a>
					                    <a href="javascript:void(0)" onclick="delItemEditKi({{ $value->personnel_id }})"> <i class="fa fa-trash-o" aria-hidden="true"></i> </a>
					                	<div class="ajax_response_del{{ $value->personnel_id }}" style="display: none;text-align: center;margin-top: 10px;padding:5px 0px;"></div>
					                </td>
					            </tr>
					            @endforeach
				            @endif
					        </tbody>
					    </table>
					</div>
				</div>
			</div>


	</div>
</div>
<div class="popup-edit-ki"></div>
<script type="text/javascript">
	function editItemEditKi(id) {
	    $('.popup-edit-ki').html('');
		var fullname = $('.fullname'+id).text();
		var ki = $('.ki'+id).text();
	    var popup = '<div class="modal fade" id="myModalEditKi'+id+'" style="z-index:99999999999"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title text-center">Sửa KI hiệu suất</h4></div><div class="modal-body"><div class="clearfix row"><div class="form-group clearfix"><div class="col-sm-3"><span>Họ tên </span></div><div class="col-sm-9">'+fullname+'</div></div><div class="form-group clearfix"><div class="col-sm-3"><p style="padding-top:6px;">KI hiệu suất(%)</p></div><div class="col-sm-5"><input type="number" class="form-control ki-popup" class="form-control" value="'+ki+'" step="0.01"></div></div></div></div><div class="modal-footer"><div id="pre_ajax_loading" style="display: none;text-align: center;margin-bottom: 18px;"><img src="'+baseURL+'/images/general/bx_loader.gif"></div><div class="ajax_response" style="display: none;text-align: center;margin-bottom: 10px;padding:8px 0px;"></div><button type="button" class="btn btn-sm btn-primary" onclick="doneEditKi('+id+')">Save</button><button type="button" class="btn btn-sm btn-cancel" data-dismiss="modal">Close</button></div></div></div></div>';
	    
	    $('.popup-edit-ki').append(popup);

	    $('#myModalEditKi'+id).modal({
	        backdrop    : 'static',
	        keyboard    : false,
	    });
	}

	function doneEditKi(id) {
	    var ki = $('.ki-popup').val();
	    var year = $('input[name="year"]').val();
	    var link = "{!! route('editKIAjax') !!}";
        var data = {
            ki: ki,
            personnel_id: id,
            year:year,
        };
        $.ajax({
            url: link,
            data: data,
            beforeSend: function() {
                $("#pre_ajax_loading").show();
            },
            complete: function() {
                $("#pre_ajax_loading").hide();
            },
            success: function(response) {
                var obj = $.parseJSON(response);
                if(obj.Response=='Error')
                {
                    $(".ajax_response").removeClass('alert-success').addClass("alert-error");
                    $(".ajax_response").html(obj.Error);
                    $(".ajax_response").show('slow');
                }else{
                    $(".ajax_response").removeClass('alert-error').addClass("alert-success");
                    $(".ajax_response").html(obj.Message);
                    $(".ajax_response").show('slow');

				    setTimeout(function() {
				         window.location.reload();
				    }, 2500);
				}
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });

	    return false;
	}

	function delItemEditKi(id) {
	    var year = $('input[name="year"]').val();
	    var link = "{!! route('delKIAjax') !!}";
        var data = {
            personnel_id: id,
            year:year,
        };
        $.ajax({
            url: link,
            data: data,
            beforeSend: function() {
                $('.ajax_waiting').addClass('loading');
            },
            complete: function() {
                // $("#pre_ajax_loading").hide();
            },
            success: function(response) {
                var obj = $.parseJSON(response);
                if(obj.Response=='Error')
                {
                    $(".ajax_response_del"+id).removeClass('alert-success').addClass("alert-error");
                    $(".ajax_response_del"+id).html(obj.Error);
                    $(".ajax_response_del"+id).show('slow');
                }else{
                	 window.location.reload();
				}
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });

	    return false;
	}

</script>
@endsection