@extends('layouts.master')

@section('title', 'Chấm công')

@section('content')

    <div class="row overtime" style="min-height:500px;">
        <div class="col-lg-2">
            @include('layouts.lam-them-gio.menu')
        </div>
        <?php  $selected_day_overtime_next_week = $selected_hour_overtime_next_week = []; ?>
        <div class="col-lg-10">
            <h4 class="title-fuction">
                Làm thêm giờ
                <div class="pull-right" style="position: relative;bottom:5px;right:10px">
                    @if ($check_contract > 0 && $check_register > 0 )
                        <button type="button" class="btn btn-sm btn-success detail-info-overtime" data-day="{{ date("N") + 1 }}" data-day-format="{{ date("d-m-Y") }}">Báo cáo</button>
                    @endif
                    @if(count($check_edit_register) > 0)
                        {{-- <button type="button" class="btn btn-sm btn-success detail-info-overtime" data-day="{{ date("N") + 1 }}" data-day-format="{{ date("d-m-Y") }}">Báo cáo</button> --}}
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editRegisterOvertime">Sửa thông tin đăng ký</button>
                        <div id="editRegisterOvertime" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Sửa thông tin đăng ký làm thêm giờ</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group clearfix">
                                                    <label class="control-label">Thông tin đăng ký:</label>
                                                    <div id="content">
                                                        <textarea class="form-control"  data-autoresize  rows="6" name="content" placeholder="Bạn cần mô tả một số thông tin để quản lý xem xét và phê duyệt, vd:&#10- Số giờ có thể làm thêm mỗi ngày, các ngày x, y, z trong tuần &#10- Các công việc dự án hiện tại hoặc mong muốn sử dụng thời gian rảnh rỗi làm thêm lâu dài">{{ $check_edit_register->content }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                    <input type="radio" name="type" value="1" @if ($check_edit_register->type == 1) checked @endif> Làm tiến độ dự án &nbsp &nbsp
                                                    <input type="radio" name="type" value="2" @if ($check_edit_register->type == 2) checked @endif> Làm thường xuyên lâu dài
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" onclick="editRegisterOvertime()">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                var content = $('#content textarea[name=content]').val();
                                
                                $('#editRegisterOvertime').on('hidden.bs.modal', function () {
                                    $('#content textarea').val(content);
                                });
                            });
                        </script>
                    @endif
                    @if ($check_report_nearest == 0 || (count($check_edit_register) == 0 && $check_register == 0))
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#registerOvertime">Đăng ký</button>
                        <div id="registerOvertime" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Đăng ký làm thêm giờ</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group clearfix">
                                                    <label class="control-label">Thông tin đăng ký:</label>
                                                    <textarea class="form-control"  data-autoresize  rows="6" name="content" placeholder="Bạn cần mô tả một số thông tin để quản lý xem xét và phê duyệt, vd:&#10- Số giờ có thể làm thêm mỗi ngày, các ngày x, y, z trong tuần &#10- Các công việc dự án hiện tại hoặc mong muốn sử dụng thời gian rảnh rỗi làm thêm lâu dài"></textarea>
                                                </div>
                                                <div class="form-group clearfix">
                                                    <input type="radio" name="type" value="1"> Làm tiến độ dự án &nbsp &nbsp
                                                    <input type="radio" name="type" value="2"> Làm thường xuyên lâu dài
                                                </div>
                                            </div>
                                            <!-- <div class="col-sm-12" style="font-size: 13px;line-height: 17px;">
                                                <div class="alert alert-danger fade in alert-dismissible">
                                                <p><strong>LƯU Ý:</strong> Bạn sẽ phải đăng ký và chờ phê duyệt lại nếu không phát sinh báo cáo trong <b>{{ $setting_overtime->timesheet_x_day }}</b> ngày.</p>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" onclick="registerOvertime()">Gửi</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $('#registerOvertime').on('hidden.bs.modal', function () {
                                    $('#registerOvertime textarea[name=content]').val('')
                                });
                        </script>
                        @if ($check_report_nearest == 0)
                            <script>
                                $(document).ready(function(){
                                    Swal.fire({
                                    // title: 'Are you sure?',
                                    text: "Bạn cần đăng ký lại vì có thể bạn không phát sinh báo cáo nào trong {{ $days_config }} ngày hoặc yêu cầu gần nhất của bạn đã bị từ chối.",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Đăng ký ngay',
                                    cancelButtonText : 'Đóng lại'
                                    // showCancelButton: false,
                                    }).then((result) => {
                                    if (result.value) {
                                        $('#registerOvertime').modal('show');
                                    }
                                    })
                                });
                            </script>
                        @endif
                    @endif
                </div>
            </h4>
            <div class="box_search" style="margin-top:15px;">
                <form class="row" action="">
                    <div class="form-group col-lg-3">
                        <label class="col-sm-4 control-label" style="padding-top: 7px;">Tháng</label>
                        <div class="col-sm-8">
                                <select name="selectMonth" class="form-control input-sm">
                                <?php 
                                for ($i = 1; $i <= 12; $i++){
                                    $months = ($i < 10) ? '0'.$i : $i ;
                                    echo '<option value="'.$months.'"';
                                    if (!empty(Request::input('selectMonth'))) {
                                        if ($i == Request::input('selectMonth')) echo ' selected="selected"';
                                    }else{
                                        if ($i == date("n")) echo ' selected="selected"';
                                    }                           
                                    echo '>'.$months.'</option>';
                                }
                                ?>
                                </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="col-sm-4 control-label" style="padding-top: 7px;">Năm</label>
                        <div class="col-sm-8">
                            <select name="selectYear" class="form-control input-sm">
                                <?php
                                for($i=date("Y")-5;$i<=date("Y");$i++) {
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
                    <div class="form-group col-lg-2">
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                        </div>
                    </div>
                    @if (count($list) > 0)
                    <div class="form-group col-lg-12    ">
                        <table class="table" style="margin-bottom: 5px;">
                            <thead>
                                <tr>

                                    <th style="width: 22%">Thông tin tuần</th>
                                    <th style="width: 14%" >Ngày làm thêm</th>
                                    <th class="text-center" style="width: 6.5%" >Số giờ</th>
                                    <th style="width: 30%">Nội dung công việc</th>
                                    <th class="text-center" style="width: 10%">Tiến độ</th>
                                    <th class="text-center" style="width: 13%" >Trạng thái</th>
                                    <th style="width: 9.5%" class="text-center">Tổng giờ được duyệt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php //dd($list);     ?>
                                @foreach ($list as $key => $value)
                                    <?php $flag = 0; ?>
                                    @foreach ( $value['over_time_id'] as $k => $v)
                                        @foreach ( $v['info'] as $k_day => $v_day)
                                            <tr>
                                                @if ($k_day == 0)
                                                <td rowspan="{{ count($v['info']) }}">Từ {{ BatvHelper::formatDate($v['apply_from'],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false) }} đến {{ BatvHelper::formatDate($v['apply_to'],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false) }}</td>
                                                @endif
            
                                                <td>
                                                    <a href="javascript:main(0)" class="detail-info-overtime" data-score="{!! $v_day[3] !!}" data-over-time-id="{{ $k_day }}" data-day="{{ $v_day[4] }}" data-day-format="{{ BatvHelper::formatDate($v_day[6],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false) }}" data-content="{{ $v_day[1] }}" data-content-manager="{{ $v_day[5] }}" data-hour="{{ $v_day[0] }}" data-progress="{{ $v_day[2] }}">{!! ($v_day[4]!= 8) ? 'Thứ '.$v_day[4] : 'Chủ nhật' !!}</a> ({{ BatvHelper::formatDate($v_day[6],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false) }})
                                                </td>
                                                <td class="text-center">
                                                    {{ $v_day[0] }}
                                                </td>
                                                <td style="text-align: justify;">
                                                        @if (strlen($v_day[1]) >= 205)
                                                            {!! BatvHelper::smartStr(nl2br($v_day[1]), 205, ' (...)') !!} 
                                                            <a href="javascript:void(0)" data-trigger="focus" data-toggle="popover"  data-placement="top" data-html="true"  data-content='{!! nl2br($v_day[1]) !!}' style="float:right; font-style: italic;font-size:12px">Xem thêm</a>
                                                        @else
                                                            {!! nl2br($v_day[1]) !!}
                                                        @endif
                                                    </td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                                        aria-valuenow="{{ $v_day[2] }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ $v_day[2] }}%">
                                                        {{ $v_day[2] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                        @if ($v_day[3] == 0)
                                                            <div class="status-0">Chưa hoàn thành</div>
                                                        @elseif($v_day[3] == 1)
                                                            <div class="status-1">Đã đồng ý</div>
                                                        @elseif($v_day[3] == 2)
                                                            <div class="status-2">Đã từ chối</div>
                                                        @elseif($v_day[3] == 3)
                                                            <div class="status-3">Chờ duyệt</div>
                                                        @endif
                                                </td>
                                                @if ($flag == 0)
                                                <td class="text-center" rowspan="{{ $value['count_info'] }}">{{ $value['total_hour_ok'] }}</td>
                                                <?php $flag++; ?>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $list->appends(Request::all())->links() }} 
                        </div>
                    </div>
                    @else
                    <div class="form-group col-lg-12">
                        <div class="alert alert-warning fade in alert-dismissible">
                            Không có dữ liệu
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <div id="editReport" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Báo cáo công việc</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Giờ làm thêm:</label><br>
                                    <select class="select2 narrow time-hour">
                                        @for ($i = $min_hour; $i <= $max_hour; $i += 0.5)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <script>
                                        $(document).ready(function() {
                                            $('.time-hour').select2({
                                                dropdownAutoWidth: true,
                                                language: {
                                                    noResults: function() {
                                                        return 'No result invalid';
                                                    },
                                                },
                                                escapeMarkup: function(markup) {
                                                    return markup;
                                                },
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Kết quả tiến độ (%):</label>
                                    <input class="form-control input-sm" type="number" value="" style="width:55% !important" required min="0" max="100" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Trạng thái:</label>
                                    <div class="status"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <label class="control-label">Nội dung công việc:</label>
                                    <textarea class="form-control"  data-autoresize  rows="6"  required name="content_report"></textarea>
                                </div>
        
                                <div class="form-group clearfix comment_manager hidden">
                                    <label class="control-label">Lý do không được phê duyệt:</label>
                                    <textarea class="form-control"  data-autoresize  rows="6"  required name="comment_manager" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <input class="day hidden" type="text" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" onclick="reportOvertime()">Gửi</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="historyReport" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Làm thêm giờ</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Giờ làm thêm (h):</label> <span class="hour"></span>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix"> 
                                    <label class="control-label">Kết quả tiến độ (%):</label> <span class="result_progress"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Trạng thái:</label>
                                    <span class="status"></span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <label class="control-label">Nội dung công việc:</label> <div class="content content-scroll"></div>
                                </div>
        
                                <div class="form-group clearfix comment_manager hidden">
                                    <label class="control-label">Lý do không được phê duyệt:</label> <div class="content_reason content-scroll"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click','.detail-info-overtime',function(){
            $('#editReport').modal('show'); 
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#editReport .status').html('');
            $("#editReport input,#editReport select,#editReport textarea").removeAttr('disabled');
            $('#editReport .modal-footer').removeClass('hidden');
            $('#editReport .comment_manager').addClass('hidden');

            for (let index = 0; index < 4; index++) {
                $('#editReport .status').removeClass('status-' + index);
            }

            var progress = $(this).attr('data-progress');
            
            var day = $(this).attr('data-day');
            day_format = $(this).attr('data-day-format');
            var data = {
                        day : day
                    };

            $('#editReport input.day').val(day);
            if (day == 8) {
                day = 'Chủ nhật';
            } else {
                day = 'Thứ ' + day;
            }
            // alert(day)

            $('#editReport h4.modal-title').html('Báo cáo công việc - ' + day + ' (' + day_format + ')');

            var hour = $(this).attr('data-hour');
            if (progress) {
                // alert(hour)
                var score = $(this).attr('data-score');
                var content = $(this).attr('data-content');
                var comment_manager = $(this).attr('data-content-manager');
                // alert(score)
                $('#editReport textarea[name=content_report]').val(content);
                $('#editReport input[type=number]').val(progress);
                
                $('.time-hour').select2({
                    dropdownAutoWidth: true,
                    language: {
                        noResults: function() {
                            return 'No result invalid';
                        },
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                });
                                   
                if (score == 0){
                    $('#editReport .status').addClass('status-0');
                    $('#editReport .status').html('Chưa hoàn thành');
                } else if(score == 1) {
                    $('#editReport .status').addClass('status-1');
                    $('#editReport .status').html('Đã đồng ý');
                } else if(score == 2) {
                    $('#editReport .status').addClass('status-2');
                    $('#editReport .status').html('Đã từ chối');
                } else if(score == 3) {
                    $('#editReport .status').addClass('status-3');
                    $('#editReport .status').html('Chờ duyệt');
                }

                if (score == 1 || score == 2) {
                    $('#editReport .modal-footer').addClass('hidden');
                    $("#editReport input,#editReport select,#editReport textarea").attr('disabled','disabled');
                }

                if (score == 2) {
                    $('#editReport textarea[name=comment_manager]').val(comment_manager);
                    $('#editReport .comment_manager').removeClass('hidden');
                }
            } else {
                $('#editReport textarea[name=content_report]').val('');
                $('#editReport input[type=number]').val('');
            }

        
            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/info-overtime-setting") }}',
                data:data, 
                dataType: 'json',
                // beforeSend: function() {
                //     $("#pre_ajax_loading").show();
                // },
                // complete: function() {
                //     $("#pre_ajax_loading").hide();
                // },
                success: function (response) {
                    $("#editReport select.time-hour").html('');

                    for (let index = response.min_hour; index <= response.max_hour; index += 0.5) {
                        $("#editReport select.time-hour").append('<option value="'+ index +'">'+ index +'</option>');
                    }

                    $("#editReport select.time-hour").select2().select2('val', hour);
                },
                error: function (error) {
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += obj_errors[k][0] + '</br>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                    })
                }
            });
           
        });

        function registerOvertime(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        content : $('#registerOvertime textarea[name=content]').val().trim(),
                        type: $('input[name="type"]:checked').val(),
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/register-overtime") }}',
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

        function editRegisterOvertime(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        content : $('#editRegisterOvertime textarea[name=content]').val().trim(),
                        type: $('input[name="type"]:checked').val(),
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/edit-register-overtime") }}',
                data:data, 
                dataType: 'json',
                // beforeSend: function() {
                //     $("#pre_ajax_loading").show();
                // },
                // complete: function() {
                //     $("#pre_ajax_loading").hide();
                // },
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

        
        function reportOvertime(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        day : $('#editReport input.day').val(),
                        day_format:day_format,
                        hour : $('#editReport .time-hour').val(),
                        content_report : $('#editReport textarea[name=content_report]').val().trim(),
                        progress :$('#editReport input[type=number]').val().trim(),
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/report-overtime") }}',
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

        $(document).ready(function(){
          $('[data-toggle="popover"]').popover(); 
        });
    </script>
@endsection