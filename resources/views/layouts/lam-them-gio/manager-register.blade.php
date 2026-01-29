@extends('layouts.master')

@section('title', 'Chấm công')

@section('content')
    <div class="row overtime">
        <div class="col-lg-2">
            @include('layouts.lam-them-gio.menu')
        </div>

        <div class="col-lg-10">            
            <h4 class="title-fuction">Danh sách nhân viên đăng ký làm thêm giờ chưa duyệt</h4>
            @if (count($pending) > 0)
                <table class="table" style="margin-bottom: 5px;" width="50%">
                    <thead>
                        <tr>
                            <th style="width: 16%">Họ và tên</th>
                            <th>Ngày đăng ký</th>
                            <th>Hình thức làm thêm</th>
                            <th>Công việc đề xuất</th>
                            <th style="width: 10%" class="text-center">Trạng thái</th>
                            <th style="width: 18%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pending as $key => $value)
                            <tr>
                                <td>{{ $value->fullname }}</td>
                                <td>{{ BatvHelper::formatDate($value->created_at, "Y-m-d", "d/m/Y", "H:i:s", false) }}</td>
                                <td>
                                    @if ($value->type == 1)
                                        Làm tiến độ dự án
                                        <?php $days_config = $setting_overtime->days_short; ?>
                                    @else
                                        Làm thường xuyên lâu dài
                                        <?php $days_config = $setting_overtime->days_long; ?>
                                    @endif
                                </td>
                                <td>{!! nl2br($value->content ) !!}</td>
                                <td>
                                    @if($value->status == 1)
                                        <div class="status-3">Chờ duyệt</div>
                                    @elseif($value->status == 2)
                                        <div class="status-1">Đã đồng ý</div>
                                    @elseif($value->status == 3)
                                        <div class="status-2">Đã từ chối</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($value->status == 1)
                                        <button type="button" data-days-config='{{ $days_config }}' data-id-record='{{ $value->id }}' class="btn btn-xs btn-primary" data-toggle="modal" data-target="#approvedRegisterOvertime"> Đồng ý </button>
                                        <button type="button" data-id-record='{{ $value->id }}' class="btn btn-xs btn-danger" data-toggle="modal" data-target="#rejectRegisterOvertime"> Từ chối </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning fade in alert-dismissible">
                    Không có dữ liệu
                </div>
            @endif

            <h4 class="title-fuction">Danh sách nhân viên đăng ký làm thêm giờ đã được duyệt</h4>
            @if (count($approved) > 0)
                <table class="table" style="margin-bottom: 5px;" width="50%">
                    <thead>
                        <tr>
                            <th style="width: 16%">Họ và tên</th>
                            <th>Ngày ĐK</th>
                            <th>Khoảng t/g hiệu lực</th>
                            <th>Công việc đề xuất</th>
                            <th style="width: 10%" class="text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approved as $key => $value)
                            <tr>
                                <td>{{ $value->fullname }}</td>
                                <td>{{ BatvHelper::formatDate($value->created_at, "Y-m-d", "d/m/Y", "H:i:s", false) }}</td>
                                <td>
                                    
                                    @if ($value->updated_at != '') 
                                        <?php 
                                            $date = new DateTime($value->updated_at);
                                            $date->modify('+' .$value->days_config . ' day');
                                        ?>
                                        Từ {{ BatvHelper::formatDate($value->updated_at, "Y-m-d", "d/m/Y", "H:i:s", false) }} đến 
                                        {{ $date->format('d/m/Y') }}
                                    @endif
                                        
                                  
                                </td>
                                <td>{!! nl2br($value->content ) !!}</td>
                                <td>
                                    @if($value->status == 1)
                                        <div class="status-3">Chờ duyệt</div>
                                    @elseif($value->status == 2)
                                        <div class="status-1">Đã đồng ý</div>
                                    @elseif($value->status == 3)
                                        <div class="status-2">Đã từ chối</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning fade in alert-dismissible">
                    Không có dữ liệu
                </div>
            @endif
        </div>
        <div id="rejectRegisterOvertime" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Từ chối làm thêm giờ</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <label class="control-label">Lý do từ chối:</label>
                                    <textarea class="form-control"  data-autoresize  rows="6" name="reason"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" class="id_record">
                        <button type="button" class="btn btn-danger" onclick="rejectRegisterOvertime()">Từ chối</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="approvedRegisterOvertime" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Giao công việc làm thêm cho NV</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <label class="control-label">Nội dung:</label>
                                    <textarea class="form-control"  data-autoresize  rows="6" name="list_work"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group clearfix">
                                    <label class="control-label">Số ngày hiệu lực:</label>
                                    <input type="number" class="form-control" name="days_config" min="1"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" class="id_record">
                        <button type="button" class="btn btn-primary" onclick="approvedRegisterOvertime()">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click','.detail-info-overtime',function(){
            var day_id = $(this).attr('data-day-id');
            var over_time_id = $(this).attr('data-over-time-id');
            var content_report = $(this).attr('data-content');
            var comment_manager = $(this).attr('data-content-manager');
            var progress = $(this).attr('data-progress');
            $('#editReport textarea[name=content_report]').val(content_report);
            $('#editReport textarea[name=comment_manager]').val(comment_manager);
            $('#editReport input[type=number]').val(progress);
            $('#editReport input.day-id').val(day_id);
            if ($(this).attr('data-score') == 2) {
                $('#editReport textarea[name=comment_manager]').val(comment_manager);
                $('.comment_manager').removeClass('hidden');
            }
            $('#editReport input.over-time-id').val(over_time_id);
            $('#editReport').modal('show'); 
        });

        $('button[data-target="#approvedRegisterOvertime"]').on('click', function () {
            $('#approvedRegisterOvertime .id_record').val($(this).attr('data-id-record'));
            days_config_max = $(this).attr('data-days-config');
            $('#approvedRegisterOvertime input[name=days_config]').val(days_config_max);
        });

        function approvedRegisterOvertime(){
            var days_config = $('#approvedRegisterOvertime input[name="days_config"]').val().trim();

            if (days_config > days_config_max) {
                Swal.fire({
                    type: 'warning',
                    html: 'Số ngày hiệu lực không được vượt quá ' + days_config_max + ' ngày',
                    allowOutsideClick: false
                })

                return;
            }

            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        id : $('#approvedRegisterOvertime .id_record').val(),
                        list_work : $('#approvedRegisterOvertime textarea[name="list_work"]').val().trim(),
                        days_config : $('#approvedRegisterOvertime input[name="days_config"]').val().trim(),
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/approved-register-overtime") }}',
                data:data, 
                dataType: 'json',
                beforeSend: function() {
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
                    }else{
                        Swal.fire({
                            type: 'warning',
                            html: response.message,
                            allowOutsideClick: false
                        })
                    }
                },
                error: function (error) {
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                    })
                }
            });

            return false;
        } 

        $('button[data-target="#rejectRegisterOvertime"]').on('click', function () {
            $('#rejectRegisterOvertime .id_record').val($(this).attr('data-id-record'));
        });

        function rejectRegisterOvertime(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        id : $('#rejectRegisterOvertime .id_record').val(),
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/check-reject-register-overtime") }}',
                data:data, 
                dataType: 'json',
                beforeSend: function() {
                    $(".ajax_waiting").addClass("loading");
                },
                complete: function() {
                    $(".ajax_waiting").removeClass("loading");
                },
                success: function (response) {
                    function handling() {
                        var data = {
                            id : $('#rejectRegisterOvertime .id_record').val(),
                            reason : $('#rejectRegisterOvertime textarea[name="reason"]').val().trim(),
                        };

                        $.ajax({
                            method: "POST",
                            url: '{{ url("toh_hrm/api/reject-register-overtime") }}',
                            data:data, 
                            dataType: 'json',
                            beforeSend: function() {
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
                                }else{
                                    Swal.fire({
                                        type: 'warning',
                                        html: response.message,
                                        allowOutsideClick: false
                                    })
                                }
                            },
                            error: function (error) {
                            
                                console.log(error)
                                var obj_errors = error.responseJSON;
                                var txt_errors = '';
                                for (k of Object.keys(obj_errors)) {
                                    txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                                }
                                Swal.fire({
                                    type: 'warning',
                                    html: txt_errors,
                                })
                            }
                        });
                    }

                    if(parseInt(response.flag) == 0){
                        handling();
                    }else{
                        Swal.fire({
                            type: 'warning',
                            html: response.message,
                            allowOutsideClick: false,
                            showCancelButton: true,
                            confirmButtonText: 'Tiếp tục',
                            cancelButtonText:'Hủy bỏ',
                        }).then(function(result){
                            if(result.value){
                                handling();
                            } else {
                                location.reload();
                            }
                        })
                    }
                },
                error: function (error) {
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                    })
                }
            });

            return false;
        } 
    </script>
@endsection