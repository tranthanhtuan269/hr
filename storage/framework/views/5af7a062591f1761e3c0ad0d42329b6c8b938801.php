

<?php $__env->startSection('title', 'Chấm công'); ?>

<?php $__env->startSection('content'); ?>
    <form class="row overtime">
        <div class="col-lg-2">
            <?php echo $__env->make('layouts.lam-them-gio.menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="col-lg-10">
            <h4 class="title-fuction">Quản lý nhân viên</h4>
            <div class="box_search" style="margin-top:15px;">
                <div class="row" action="">
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
                </div>
            </div>
            <div class="form-group col-lg-12">
                <div class="alert alert-warning">
                   Bạn có <strong id="count_reports_pending"><?php echo e($number_report_pending); ?></strong> báo cáo chưa duyệt
                </div>
                <label><input type="checkbox" name="show_reports_pending" <?php if(Request::get('show_reports_pending') == 1): ?> checked <?php endif; ?> value="1"> Chỉ hiển thị các báo cáo chưa duyệt</label>
            </div>
            <?php if(count($list) > 0): ?>
                <table class="table current" style="margin-bottom: 5px;">
                    <thead>
                        <tr>
                            <th style="width: 1% !important" class="text-center">STT</th>
                            <th style="width: 15% !important">Họ  tên</th>
                            <th style="width: 15% !important">Ngày làm thêm</th>
                            <th class="text-center" style="width: 10% !important">Số giờ</th>
                            <th class="text-center" style="width: 30% !important">Nội dung công việc</th>
                            <th class="text-center" style="width: 10% !important">Tiến độ</th>
                            <th class="text-center" style="width: 10% !important">Trạng thái</th>
                            <th class="text-center" style="width: 10% !important">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if( !isset($_GET['page']) || $_GET['page']==1 ){
                                $i  = 1;
                            }else{
                                $i = ($_GET['page']*10 -10) +1;
                            }

                        ?>
                        <?php foreach($list as $key => $value): ?>
                            <?php foreach($value->detail as $k_detail => $v_detail): ?>
                                <tr>
                                    <td  class="text-center">
                                        <?php echo e($i); ?>

                                        <?php $i++; ?>
                                    </td>
                                    <td>
                                        <?php echo $value->fullname; ?>

                                    </td> 
                                    <td>
                                        <?php echo ($v_detail->day_id != 8) ? 'Thứ '.$v_detail->day_id : 'Chủ nhật'; ?>

                                        (<?php echo e(BatvHelper::formatDate($v_detail->time_day,'Y-m-d',$formatDate='d-m-Y',$timeFormat='H:i:s',$time=false)); ?>)
                                    </td>
                                    <td class="text-center">
                                        <?php echo e($v_detail->hour); ?>

                                    </td>
                                    <td style="text-align: justify; ">
                                        <?php if(strlen($v_detail->content_report) >= 290): ?>
                                            <?php echo BatvHelper::smartStr(nl2br($v_detail->content_report) , 290, ' (...)'); ?> 
                                            <a href="javascript:void(0)" data-trigger="focus" data-toggle="popover"  data-placement="top" data-html="true"  data-content='<?php echo nl2br($v_detail->content_report); ?>' style="float:right; font-style: italic;font-size:12px">Xem thêm</a>
                                        <?php else: ?>
                                            <?php echo nl2br($v_detail->content_report); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                            aria-valuenow="<?php echo e($v_detail->progress); ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo e($v_detail->progress); ?>%">
                                            <?php echo e($v_detail->progress); ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($v_detail->score == 0): ?>
                                            <div class="status-0">Chưa hoàn thành</div>
                                        <?php elseif($v_detail->score == 1): ?>
                                            <div class="status-1">Đã đồng ý</div>
                                        <?php elseif($v_detail->score == 2): ?>
                                            <div class="status-2">Đã từ chối</div>
                                        <?php elseif($v_detail->score == 3): ?>
                                            <div class="status-3">Chờ duyệt</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($v_detail->score == 3): ?>
                                            <button type="button" class="detail-info-overtime btn btn-xs btn-primary" data-full-name="<?php echo e($value->fullname); ?>" data-score="<?php echo e($v_detail->score); ?>" data-time="<?php echo ($v_detail->day_id != 8) ? 'Thứ '.$v_detail->day_id : 'Chủ nhật'; ?> (<?php echo e(BatvHelper::formatDate($v_detail->time_day,'Y-m-d',$formatDate='d-m-Y',$timeFormat='H:i:s',$time=false)); ?>)" data-over-time-id="<?php echo e($value->id); ?>" data-day-id="<?php echo e($v_detail->day_id); ?>" data-content="<?php echo e($v_detail->content_report); ?>" data-content-manager="<?php echo e($v_detail->comment_manager); ?>" data-hour="<?php echo e($v_detail->hour); ?>" data-progress="<?php echo e($v_detail->progress); ?>">
                                                Phê duyệt
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <?php echo e(count($list) > 0 ? $list->appends(Request::all())->links() : ''); ?> 
                </div>
            <?php endif; ?>

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
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <label class="control-label">Họ và tên:</label> <span class="full-name"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Giờ làm thêm:</label><br>
                                    <select class="select2 narrow time" disabled>
                                   
                                        <?php for($i = 0.5; $i < 12.5; $i += 0.5): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Kết quả tiến độ (%):</label>
                                    <input class="form-control input-sm" type="number" value="" style="width:55% !important" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group clearfix">
                                    <label class="control-label">Phê duyệt:</label>
                                    <div>
                                        <label class="radio-inline"><input type="radio" name="action" checked value="1">Đồng ý</label>
                                        <label class="radio-inline"><input type="radio" name="action" value="2">Từ chối</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <label class="control-label">Nội dung báo cáo công việc của nhân viên:</label>
                                    <textarea class="form-control"  data-autoresize  rows="6"  disabled name="content_report"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12"> 
                                <div class="comment_manager form-group clearfix hidden">
                                    <label class="control-label">Lý do từ chối:</label>
                                    <textarea class="form-control"  data-autoresize  rows="6" name="comment_manager"></textarea>
                                </div>
                            </div>
                        </div>
                        <input class="day-id hidden" type="text" value="">
                        <input class="over-time-id hidden" type="text" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="actionManager()">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <script>
        $(document).on('click','input[name=show_reports_pending]',function(){
            $("button[type=submit]").trigger("click");
        });

        $(document).on('click','input[name=action]',function(){
            if ($(this).val() == 2) {
                $('.comment_manager').removeClass('hidden');
            } else {
                $('.comment_manager').addClass('hidden');
            }
        });

        $(document).on('click','.detail-info-overtime',function(){
            $('.comment_manager').addClass('hidden');
            var full_name = $(this).attr('data-full-name');
            var time = $(this).attr('data-time');
            var hour = $(this).attr('data-hour');
            var day_id = $(this).attr('data-day-id');
            var over_time_id = $(this).attr('data-over-time-id');
            var content_report = $(this).attr('data-content');
            var comment_manager = $(this).attr('data-content-manager');
            var progress = $(this).attr('data-progress');
            var score = $(this).attr('data-score');
            if (parseInt(score) == 0 || parseInt(score) == 3) {
                score = 1;
            }
            if (score == 2) {
                $('.comment_manager').removeClass('hidden');
            }

            $('#editReport h4.modal-title').html('Báo cáo công việc - ' + time);
            $('#editReport .full-name').html(full_name);
            $('#editReport textarea[name=content_report]').val(content_report);
            $('#editReport textarea[name=comment_manager]').val(comment_manager);
            $('#editReport input[type=number]').val(progress);
            $('#editReport input.day-id').val(day_id);
            $('#editReport input.over-time-id').val(over_time_id);
      
            $("#editReport select.time").select2().select2('val', hour);
            $('#editReport input[name=action]').filter('[value="'+ score +'"]').prop('checked', true);
            $('#editReport').modal('show'); 
        });

        function actionManager(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        score : $('input[name=action]:checked').val(),
                        comment_manager : $('#editReport textarea[name=comment_manager]').val().trim(),
                        day_id : $('#editReport input.day-id').val(),
                        over_time_id :  $('#editReport input.over-time-id').val()
                    };
            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/manager-report-overtime")); ?>',
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
                        allowOutsideClick: false
                    })
                }
            });

            return false;
        } 

        $(document).ready(function(){
          $('[data-toggle="popover"]').popover(); 
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>