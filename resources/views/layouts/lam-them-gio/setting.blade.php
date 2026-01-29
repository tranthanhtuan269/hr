@extends('layouts.master')

@section('title', 'Chấm công')

@section('content')
    <div class="row overtime">
        <div class="col-lg-2">
            @include('layouts.lam-them-gio.menu')
        </div>

        <div class="col-lg-10">
            <h4 class="title-fuction">Cấu hình làm thêm giờ</h4>
            <div class="row">
                <div class="col-lg-offset-2 col-lg-9">
                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Giờ tối thiểu trong ngày thường <span class="required">*</span></label>
                            <div class="col-sm-2">
                                <select class="select2 narrow min_hour_day_normal">
                                    @for ($i = 0.5; $i < 4.5; $i += 0.5)
                                        <option value="{{ $i }}" {{ $setting_overtime->min_hour_day_normal == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <script>
                                    $(document).ready(function() {
                                        $('.min_hour_day_normal').select2({
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
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Giờ tối đa trong ngày thường <span class="required">*</span></label>
                            <div class="col-sm-2">
                                <select class="select2 narrow max_hour_day_normal">
                                    @for ($i = 0.5; $i < 4.5; $i += 0.5)
                                        <option value="{{ $i }}" {{ $setting_overtime->max_hour_day_normal == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <script>
                                    $(document).ready(function() {
                                        $('.max_hour_day_normal').select2({
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
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Giờ tối thiểu trong ngày nghỉ <span class="required">*</span></label>
                            <div class="col-sm-2">
                                <select class="select2 narrow min_hour_day_holiday">
                                    @for ($i = 0.5; $i < 12.5; $i += 0.5)
                                        <option value="{{ $i }}" {{ $setting_overtime->min_hour_day_holiday == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <script>
                                    $(document).ready(function() {
                                        $('.min_hour_day_holiday').select2({
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
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Giờ tối đa trong ngày nghỉ <span class="required">*</span></label>
                            <div class="col-sm-2">
                                <select class="select2 narrow max_hour_day_holiday">
                                    @for ($i = 0.5; $i < 12.5; $i += 0.5)
                                        <option value="{{ $i }}" {{ $setting_overtime->max_hour_day_holiday == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <script>
                                    $(document).ready(function() {
                                        $('.max_hour_day_holiday').select2({
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
                        <div class="form-group hidden">
                            <label class="col-sm-5 control-label">Số ngày đăng ký có hiệu lực <span class="required">*</span></label>
                            <div class="col-sm-2">
                            <input type="text" name="timesheet_x_day" class="form-control input-sm" maxlength="2" onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="{{ $setting_overtime->timesheet_x_day }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Số ngày tối đa làm tiến độ dự án <span class="required">*</span></label>
                            <div class="col-sm-2">
                            <input type="text" name="days_short" class="form-control input-sm" maxlength="2" onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="{{ $setting_overtime->days_short }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Số ngày tối đa làm thường xuyên lâu dài <span class="required">*</span></label>
                            <div class="col-sm-2">
                            <input type="text" name="days_long" class="form-control input-sm" maxlength="2" onkeyup="this.value=this.value.replace(/[^\d]/,'')" value="{{ $setting_overtime->days_long }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Cho phép báo cáo khi đang chờ phê duyệt <span class="required">*</span></label>
                            <div class="col-sm-2">
                                <input type="checkbox" @if ($setting_overtime->report_permission == 1) checked @endif value="1" name="report_permission" style="position: relative; top:5px;"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-2">
                                <button type="button" class="btn btn-sm btn-orange" onclick="settingOvertime()">Cập nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        function settingOvertime(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        // min_hour_week  : $('input[name=min_hour_week]').val().trim(),
                        // max_hour_week  : $('input[name=max_hour_week]').val().trim(),
                        // min_hour_month : $('input[name=min_hour_month]').val().trim(),
                        // max_hour_month : $('input[name=max_hour_month]').val().trim(),
                        min_hour_day_normal : $('.min_hour_day_normal').val(),
                        max_hour_day_normal : $('.max_hour_day_normal').val(),
                        min_hour_day_holiday : $('.min_hour_day_holiday').val(),
                        max_hour_day_holiday : $('.max_hour_day_holiday').val(),
                        timesheet_x_day : $('input[name=timesheet_x_day]').val(),
                        days_short : $('input[name=days_short]').val(),
                        days_long : $('input[name=days_long]').val(),
                        report_permission: $('input[name="report_permission"]:checked').val(),
                    };
            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/setting-overtime") }}',
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
                        txt_errors += '<p style="text-align: left;text-align: justify;">' + obj_errors[k][0] + '</p>';
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