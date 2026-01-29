@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<?php
    $turns = ( date('m') >= 1 && date('m') <= 6 )? 1 : 2;
    $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
    if( isset( $_GET['frequency'] ) ){
        if( date('m') >= 1 && date('m') <= 6 ){
        	$turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y') : "đợt 2(tháng 12) năm ".date('Y', strtotime(date('Y').' -1 year'));
        }else{
        	$turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
        	$param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
        }
    }

    if( date('m') >= 1 && date('m')<=6 ){
    	$time_before = 'Đợt T12/'.date('Y', strtotime(date('Y').' -1 year'));
    	$time_after = 'Đợt T6/'.date('Y');
    }else{
    	$time_before = 'Đợt T6/'.date('Y');
    	$time_after = 'Đợt T12/'.date('Y');
    }
?>
<div class="row box_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10">

				<h4 class="title-fuction">
					Danh sách nhân viên đủ tiêu chuẩn tăng lương {{ $param }}
				</h4>
				<div class="col-lg-12">
					@if (session('flash_message_succ') != '')
						 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
					@endif
				</div>
                <form class="form-horizontal clearfix" method="get" action="">
                    <div class="form-group col-lg-6">
                        <label for="date" class="col-sm-4 control-label">Đợt xét :</label>
                        <div class="col-sm-8">
                            <select name="frequency" class="form-control select2 wrap">
                                <option value="2" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 2)?"selected":""; ?> >{{ $time_after }}</option>
                                <option value="1" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 1)?"selected":""; ?> >{{ $time_before }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-lg-6">
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
                    <div class="form-group col-lg-12 text-center">
                        <input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
                        <input type="submit" class="btn btn-sm btn-orange" name="sendemail" onclick="return confirm('Bạn có chắc chắn muốn gửi Email ?')" value="Gửi Email" >
                    </div>
                    {{ csrf_field()}}
                </form>
				<div class="table-responsive" >
				    <table class="table table-bordered table-striped">
				        <thead>
				            <tr>
				                <th class="text-center">STT</th>
				                <th class="text-center" width="20%">Họ và tên</th>
				                <th class="text-center">Ngày ký hợp đồng chính thức</th>
				                <th class="text-center">Hệ số lương hiện tại</th>
				                <th class="text-center">Ngày thay đổi lương gần nhất</th>
				                <th class="text-center">Số tháng từ ngày thay đổi lương gần nhất</th>
				                <th class="text-center">Ngày đủ tiêu chuẩn xét</th>
				                <th class="text-center" width="10%">Chu kỳ xét</th>
				                <th class="text-center" width="6%">Loại xét</th>
								{{-- <th class="text-center" width="15%">Trạng thái</th> --}}
								<th class="text-center" width="15%"></th>
				            </tr>
				        </thead>
				        <tbody>
						    @if(!empty($data))
						    	<?php $tmp=1; ?>
						     	@foreach ($data as $key=>$val)
							     <tr>
							      	<td class="text-center"> {{ $tmp }} </td> 
							      	<td style="text-align: left;">
							      		<a href="{{ route('getPersonnelEdit',['id'=>$val['personnel_id'] ]) }}">{{ str_limit( $val['fullname'], $limit = 35, $end = '...') }}</a>
								    </td>
								    <td>{{ BatvHelper::formatDate($val["date_hdct"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</td>
								    <td>{{ $val['hsl_ht'] }}</td>
								    <td>
										@if( $val["date_nlgn"] )
											{{ BatvHelper::formatDate($val["date_nlgn"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
										@endif
								    </td>
								    <td>{{ $val["number_month_nlgn"] }}</td>
								    <td>{{ BatvHelper::formatDate($val["date_dxnl"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</td>
								    <td>
								    	{{ $val["salary_frequency"] }} năm
								    </td>
								    <td>
                                        @if( $val['type'] == 1 )
                                            <span>Đ.kỳ</span>
                                        @else
                                            <span style="color: green;">Đ.xuất</span>
                                        @endif								    
								    </td>
								    {{-- <td>
								    	@if( $val["status"] == 1 )
											<span class="daduyet" >Đã gửi email</span>
								    	@else
											<span class="chuaguimail" >Chưa gửi email</span>
								    	@endif
									</td> --}}
									<td>
										
								    	@if( $val["status"] == 1 )
											<span class="daduyet" >Đã gửi email</span>
								    	@else
											<button type="button" class="btn btn-primary btn-xs send-email-only" data-personel-id="{{ $val['personnel_id'] }}">Gửi email</button>
											<button type="button" class="btn btn-danger btn-xs delete-only" data-personel-id="{{ $val['personnel_id'] }}"> Xóa </button>
								    	@endif
									</td>
							    </tr>
							    <?php $tmp++; ?>
							    @endforeach
						    @endif
				        </tbody>
				    </table>
				</div>
	</div>
</div>
<script>
	@if (!isset($_GET['frequency']))
		$("#department").select2("val", '{{ $ids[0] }}');
	@endif


	$('.send-email-only').click(function(){
		var personnel_id = $(this).attr('data-personel-id')
		var data    = {
			personnel_id           : personnel_id,
		};

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "POST",
			url: "{{ route('send-email-only-salary') }}",
			data: data,
			dataType:'json',
			beforeSend: function(r, a){
				$(".ajax_waiting").addClass("loading");
			},
			complete: function() {
				$(".ajax_waiting").removeClass("loading");
			},
			success: function (response) {
				if(response.status == 200){
					Swal.fire({
						type: "success",
						html: response.message,
						allowOutsideClick: false
					}).then(function(result){
						if(result.value){
							location.reload();
						}
					})
				}
			},
			error: function (data) {
			}
		});
	});

    $('.delete-only').click(function(){
		Swal.fire({
			title: 'Bạn có chắc chắn muốn xóa?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				var personnel_id = $(this).attr('data-personel-id')
				var data    = {
					personnel_id           : personnel_id,
				};

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					type: "POST",
					url: "{{ route('delete-only-salary') }}",
					data: data,
					dataType:'json',
					beforeSend: function(r, a){
						$(".ajax_waiting").addClass("loading");
					},
					complete: function() {
						$(".ajax_waiting").removeClass("loading");
					},
					success: function (response) {
						if(response.status == 200){
							Swal.fire({
								type: "success",
								html: response.message,
								allowOutsideClick: false
							}).then(function(result){
								if(result.value){
									location.reload();
								}
							})
						}
					},
					error: function (data) {
					}
				});
			}
		})
	});
</script>
@endsection