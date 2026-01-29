

<?php $__env->startSection('title', 'Chấm công'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row overtime">
        <div class="col-lg-2">
            <?php echo $__env->make('layouts.lam-them-gio.menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="col-lg-10">
            <h4 class="title-fuction">Giám sát nhân viên</h4>
            <div class="box_search">
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
                    <div class="form-group col-lg-4">
                        <label class="col-sm-3 control-label" style="padding-top: 7px;">Đơn vị</label>
                        <div class="col-sm-9">	
                            <select name="selectDepart" id="department" class="form-control select2 narrow wrap" style="width: 100%">
                                <option value="0"> -- Đơn vị -- </option>
                                <?php echo $department; ?>

                            </select>
                            <script type="text/javascript">
                                var $select2 = $('.select2').select2({
                                    containerCssClass: "wrap"
                                })
                            </script>
                        </div>
                    </div>
                    <div class="form-group col-lg-2">
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php if(count($list) > 0): ?>
            <table class="table" style="margin-bottom: 5px;">
                <thead>
                    <tr>
                        <th style="width: 20%">Họ và tên</th>
                        <th style="width: 25%">Thông tin tuần</th>
                        <th>Thời gian</th>
                        <th class="text-center">Số giờ</th>
                        <th class="text-center">Trạng thái</th>
                        <th style="width: 18%" class="text-center">Tổng giờ được duyệt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $tmp = 0; $total_hour = 0; ?>
                    <?php foreach($list as $key => $value): ?>
                        <?php foreach( $value['over_time_id'] as $k => $v): ?>
                            <?php foreach( $v['info'] as $k_day => $v_day): ?>
                                <tr>
                                    <?php if($tmp == 0): ?>
                                    <td rowspan="<?php echo e($value['count_info']); ?>"> <?php echo $value['fullname']; ?></td>
                                    <?php endif; ?>
                                    
                                    <?php if($k_day == 0): ?>
                                    <td rowspan="<?php echo e(count($v['info'])); ?>">Tuần từ <?php echo e(BatvHelper::formatDate($v['apply_from'],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false)); ?> đến <?php echo e(BatvHelper::formatDate($v['apply_to'],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false)); ?></td>
                                    <?php endif; ?>

                                    <td>
                                        <?php echo ($v_day[4]!= 8) ? 'Thứ '.$v_day[4] : 'Chủ nhật'; ?> (<?php echo e(BatvHelper::formatDate($v_day[6],"Y-m-d", $formatDate="d-m-Y",$timeFormat="H:i:s",false)); ?>)
                                    </td>
                                    <td class="text-center">
                                        <?php echo e($v_day[0]); ?>

                                    </td>
                                    <td class="text-center">
                                            <?php if($v_day[3] == 0): ?>
                                                <div class="status-0">Chưa hoàn thành</div>
                                            <?php elseif($v_day[3] == 1): ?>
                                                <div class="status-1">Đã đồng ý</div>
                                            <?php elseif($v_day[3] == 2): ?>
                                                <div class="status-2">Đã từ chối</div>
                                            <?php elseif($v_day[3] == 3): ?>
                                                <div class="status-3">Chờ duyệt</div>
                                            <?php endif; ?>
                                    </td>
                                    <?php if($tmp == 0): ?>
                                    <td class="text-center" rowspan="<?php echo e($value['count_info']); ?>"><?php echo e($value['total_hour_ok']); ?></td>
                                    <?php  $tmp++; $total_hour += $value['total_hour_ok'];?>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <?php  $tmp = 0; ?>
                    <?php endforeach; ?>
                    <tr style="background: rgba(255, 0, 0, 0.56);">
                        <td colspan="4"></td>
                        <td class="text-center"><b>TỔNG</b></td>
                        <td class="text-center">
                            <b style="font-size:16px"><?php echo e($total_hour); ?></b> 
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <?php echo e($list->appends(Request::all())->links()); ?> 
            </div>
            <?php else: ?>
            <div class="alert alert-warning fade in alert-dismissible">
                Không có dữ liệu
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
                        <div class="form-group clearfix">
                            <label class="control-label">Nội dung báo cáo công việc của nhân viên:</label>
                            <textarea class="form-control"  data-autoresize  rows="6"  disabled name="content_report"></textarea>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label">Kết quả tiến độ (%):</label>
                            <input class="form-control" type="number" value="" style="width:15% !important" disabled>
                        </div>
                        <div class="form-group clearfix comment_manager hidden">
                            <label class="control-label">Lý do không được phê duyệt:</label>
                            <textarea class="form-control"  data-autoresize  rows="6"  disabled name="comment_manager"></textarea>
                        </div>
                        <input class="day-id hidden" type="text" value="">
                        <input class="over-time-id hidden" type="text" value="">
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
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>