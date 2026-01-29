

<?php $__env->startSection('title', 'Tín dụng'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        tbody.special tr td{
            padding: 3px;
        }
    </style>
    <div class="row overtime" style="min-height:500px;">
        <div class="col-lg-2">
            <?php echo $__env->make('layouts.vay-von.menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="col-lg-10">
            <h4 class="title-fuction">
                Tín dụng
                <div class="pull-right" style="position: relative;bottom:5px;right:10px">
                    <?php if($status_pending == 'Hiện tại bạn không có khoản vay mới nào.' && !$detail_loan_capital): ?>
                        <button type="button" class="btn btn-sm btn-success " data-toggle="modal" data-target="#loanRegister">Đăng ký ngay</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-danger " data-toggle="modal" data-target="#loanEstimate">Dự tính khoản vay</button>
                </div>
            </h4>

            <?php if($detail_loan_capital): ?>
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 22%">Họ và tên</th>
                            <th class="text-center">Số tiền vay</th>
                            <th class="text-center">Ngày <br>giải ngân</th>
                            <th class="text-center">Ngày n/v <br>bắt đầu trả</th>
                            <th class="text-center">Ngày kết thúc</th>
                            <th class="text-center">L/s<br> (% năm)</th>
                            <th class="text-center">L/s ưu đãi <br> (% năm)</th>
                            <th class="text-center">Số tháng ưu đãi (từ lúc bắt đầu trả nợ)</th>
                            <th class="text-center">L/s trả chậm<br> (% năm)</th>
                            <th class="text-center">L/s nếu không hoàn thiện hồ sơ (% năm)</th>
                            <th class="text-center">Tổng <br>tiền lãi</th>
                            <th class="text-center">Phạt trả chậm</th>
                            <th class="text-center">Đã trả</th>
                            <th class="text-center">Còn lại</th>
                        </tr>
                    </thead>
                    <tbody class="special">
                            <?php $total_paid_money_1 = $total_interest_1 = $total_interest_incurred_1 = 0; ?>

                            <?php foreach($detail_loan_capital as $value): ?>
                                <?php 
                                    $total_paid_money_1 += $value->paid_money; 
                                    $total_interest_1 += $value->interest;
                                    $total_interest_incurred_1 += $value->interest_incurred;
                                ?>
                            <?php endforeach; ?>
                        <td class="text-center"><?php echo e($detail_loan_capital[0]->fullname); ?></td>
                        <td class="text-center"><?php echo e(number_format($detail_loan_capital[0]->remaining_principal)); ?></td>
                        <td class="text-center"><?php echo e(BatvHelper::formatDate($detail_loan_capital[0]->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false)); ?></td>
                        <td class="text-center"><?php echo e(BatvHelper::formatDate($detail_loan_capital[1]->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false)); ?></td>
                        <td class="text-center"><?php echo e(BatvHelper::formatDate($detail_loan_capital[count($detail_loan_capital) - 1]->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false)); ?></td>
                        <td class="text-center"><?php echo e($config_loan_capital->interest_rate); ?></td>
                        <td class="text-center"><?php echo e($config_loan_capital->preferential_interest_rate); ?></td>
                        <td class="text-center"><?php echo e($config_loan_capital->count_month_preferential); ?></td>
                        <td class="text-center"><?php echo e($config_loan_capital->deferred_interest); ?></td>
                        <td class="text-center"><?php echo e($config_loan_capital->interest_file_late); ?></td>
                        <td class="text-center"><?php echo e(number_format($total_interest_1)); ?></td>
                        <td class="text-center"><?php echo e(number_format($total_interest_incurred_1)); ?></td>
                        <td class="text-center"><?php echo e(number_format($total_paid_money_1)); ?></td>
                        <td class="text-center"><?php echo e(number_format($detail_loan_capital[0]->remaining_principal + $total_interest_1 + $total_interest_incurred_1 - $total_paid_money_1)); ?></td>
                    </tbody>
                </table>
      
                <?php
                    $total_remaining_principal = 0;
                    $total_principal = 0;
                    $total_wanting_month_prev_money = 0;
                    $total_redundancy_month_prev_money = 0;
                    $total_interest = 0;
                    $total_interest_incurred = 0;
                    $total_tmp_all = 0;
                    $total_tmp = 0;
                    $total_real = 0;
                    $total_real_all  = 0;
                    $total_paid_money = 0;
                    $flag = true;
                    $pay = $detail_loan_capital[0]->pay;
                    $loan_capital_status = $detail_loan_capital[0]->loan_capital_status;
                    $final_settlement = $detail_loan_capital[0]->final_settlement;
                    $partial_settlement = $detail_loan_capital[0]->partial_settlement;
                    // echo $pay;die;
                ?>
                <h4 class="title-fuction">
                    Lịch trả nợ
                    <div class="pull-right" style="position: relative;bottom:5px;right:10px"> 
                        <?php if($final_settlement == 1): ?>
                            <button class="btn btn-sm btn-warning remove-btn">Trả sớm toàn bộ đang chờ xác nhận</button>
                        <?php elseif($partial_settlement == 1): ?>
                            <button class="btn btn-sm btn-warning remove-btn">Trả sớm một phần đang chờ xác nhận</button>
                        <?php else: ?>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#remindPayAllNowByUser">Trả sớm toàn bộ</button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="remindPayPartialSettlementByUser()" data-target="#remindPayPartialSettlementByUser">Trả sớm một phần</button>
                        <?php endif; ?>
                    </div> 
                </h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Kỳ trả nợ</th>
                            <th class="text-center">Số gốc còn lại</th>
                            <th class="text-center">Gốc</th>
                            <th class="text-center">Tiền thiếu tháng trước</th>
                            <th class="text-center">Tiền dư tháng trước</th>
                            <th class="text-center">Lãi</th>
                            <th class="text-center">Phạt trả chậm</th>
                            <th class="text-center">Tổng gốc + Lãi + Phạt trả chậm</th>
                            <th class="text-center">Số tiền thực phải trả trong tháng</th>
                            <th class="text-center">Số tiền đã thanh toán</th>
                            <th class="text-center">Ngày trả</th>
                            <?php if($loan_capital_status != 4 && $pay == 2): ?>
                            <th class="text-center">Thao tác</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $price_final_settlement = 0; ?>
                        
                        <?php foreach($detail_loan_capital as $key => $item): ?>
                            <tr <?php if($item->status == 1): ?> style="background:#449d44;color:#fff" <?php endif; ?>>
                                <?php
                                    $total_principal += $item->principal;
                                    // $total_wanting_month_prev_money += $item->wanting_month_prev_money;
                                    // $total_redundancy_month_prev_money += $item->redundancy_month_prev_money;
                                    $total_interest += $item->interest;
                                    $total_interest_incurred += $item->interest_incurred;
                                    $total_real = $item->principal + $item->interest + $item->interest_incurred;
                                    $total_real_all += $total_real;
                                    $total_tmp = $item->principal + $item->interest + $item->interest_incurred + $item->wanting_month_prev_money - $item->redundancy_month_prev_money;
                                    $total_tmp = ($total_tmp > 0) ? $total_tmp : 0;
                                    $total_tmp_all += $total_tmp;
                                    $total_paid_money += $item->paid_money;
                                    $repayment_period = BatvHelper::formatDate($item->repayment_period, 'Y-m-d', 'd/m/Y', 'H:i:s', false);
                                ?>
                                <td class="text-center"><?php echo e($repayment_period); ?></td>
                                <td class="text-center"><?php echo e($item->month); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->remaining_principal)); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->principal)); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->wanting_month_prev_money)); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->redundancy_month_prev_money)); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->interest)); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->interest_incurred)); ?></td>
                                <td class="text-center"><?php echo e(number_format($total_real)); ?></td>
                                <td class="text-center"><?php echo e(number_format($total_tmp)); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->paid_money)); ?></td>
                                <td class="text-center"><?php echo e($item->received_date); ?></td>
                                <?php if($loan_capital_status != 4 && $pay == 2): ?>
                                <td class="text-center">
                                    <?php if($item->month > 0 && $item->status == 0 && $flag && (($final_settlement == 0 || $final_settlement == 2 ) && ($partial_settlement == 0 || $partial_settlement == 2))): ?>
                                        <?php if($detail_loan_capital[$key]->type == 0): ?>
                                            <button type="button" class="btn btn-xs btn-primary" onclick="remindPayMonthNowByUser(<?php echo e($item->id); ?>)">Trả bây giờ</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-xs btn-warning  remove-btn" >Đang chờ xác nhận</button>
                                            <script>
                                                $("button[data-target='#remindPayAllNowByUser']").remove();
                                                $("button[data-target='#remindPayPartialSettlementByUser']").remove();
                                            </script>
                                        <?php endif; ?>
                                        <?php 
                                            $flag = false;
                                            $price_final_settlement = $item->remaining_principal +  $item->principal  -  $item->redundancy_month_prev_money + $item->wanting_month_prev_money + $item->interest + $item->interest_incurred - $item->paid_money;
                                        ?>
                                    <?php endif; ?>
                                </td>
                                <?php else: ?>
                                    <?php if($key > 0 && $item->status == 0 && $flag): ?>
                                        <?php 
                                            $flag = false;
                                            $price_final_settlement = $item->remaining_principal +  $item->principal  -  $item->redundancy_month_prev_money + $item->wanting_month_prev_money + $item->interest + $item->interest_incurred - $item->paid_money;
                                        ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                            <tr>
                                <td class="text-center"><b>TỔNG</b></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"><b></b></td>
                                <td class="text-center"><b></b></td>
                                <td class="text-center"><b></b></td>
                                <td class="text-center"><b><?php echo e(number_format($total_interest)); ?></b></td>
                                <td class="text-center"><b><?php echo e(number_format($total_interest_incurred)); ?></b></td>
                                <td class="text-center"><b></b></td>
                                <td></td>
                                <?php /* <td class="text-center"><b><?php echo e(number_format($total_tmp_all)); ?></b></td> */ ?>
                                <td class="text-center"><b><?php echo e(number_format($total_paid_money)); ?></b></td>
                                <?php if($loan_capital_status != 4 && $pay == 2): ?>
                                <td class="text-center"><b></b></td>
                                <td class="text-center"></td>
                                <?php endif; ?>
                            </tr>
                    </tbody>
                </table>
                <div id="remindPayAllNowByUser" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Trả sớm toàn bộ</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <div style="font-size:14px;">
                                            Tổng số tiền phải trả còn lại để có thể tất toán là <span style="color:red;font-size:16px;" id="price_final_settlement"></span> VNĐ. Xin vui lòng chuyển khoản đến tài khoản của công ty và gửi thông báo để bộ phận kế toán kiểm duyệt.
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="remindPayAllNowByUser()">Gửi thông báo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>

                    $('#remindPayAllNowByUser').on('shown.bs.modal', function (e) {
                        $.ajaxSetup(
                        {
                            headers:
                            {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        var data = {
                                    loan_capital_id : "",
                                };

                        $.ajax({
                            method: "POST",
                            url: '<?php echo e(url("toh_hrm/api/money-final-settlement")); ?>',
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
                                    $('#price_final_settlement').html(formatNumber(response.total_price_real, '.', ','));
                                }
                            },
                            error: function (error) {
                            
                                console.log(error)
                                var obj_errors = error.responseJSON;
                                var txt_errors = '';
                                for (k of Object.keys(obj_errors)) {
                                    txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                }
                                Swal.fire({
                                    type: 'warning',
                                    html: txt_errors,
                                })
                            }
                        });
                    })

                    function remindPayAllNowByUser(){
                        $.ajaxSetup(
                        {
                            headers:
                            {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        var data = {

                                };
                                
                        $.ajax({
                            method: "POST",
                            url: '<?php echo e(route("remind-pay-all-now-by-user")); ?>',
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
                                    var obj_errors = response.message;
                                    var txt_errors = '';
                                    for (k of Object.keys(obj_errors)) {
                                        txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                    }
                                    Swal.fire({
                                        type: 'warning',
                                        html: txt_errors,
                                        allowOutsideClick: false
                                    })
                                }
                            },
                            error: function (error) {
                        
                                var obj_errors = error.responseJSON;
                                var txt_errors = '';
                                for (k of Object.keys(obj_errors)) {
                                    txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                }
                                Swal.fire({
                                    type: 'warning',
                                    html: txt_errors,
                                })
                            }
                        });
                    }

                    function remindPayPartialSettlementByUser(){
                        Swal.fire({
                            type: 'warning',
                            text: 'Bạn đã chuyển khoản trả nợ tất toán một phần cho công ty? và bây giờ bạn muốn gửi email thông báo cho bộ phận kế toán?',
                            showCancelButton: true,
                            confirmButtonText: 'Có',
                            cancelButtonText: 'Không',
                        }).then(function (result) {
                            if(result.value){ 
                                $.ajaxSetup(
                                {
                                    headers:
                                    {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
        
                                var data = {
  
                                        };
                                        
                                $.ajax({
                                    method: "POST",
                                    url: '<?php echo e(route("remind-pay-partial-settlement-by-user")); ?>',
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
                                            var obj_errors = response.message;
                                            var txt_errors = '';
                                            for (k of Object.keys(obj_errors)) {
                                                txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                            }
                                            Swal.fire({
                                                type: 'warning',
                                                html: txt_errors,
                                                allowOutsideClick: false
                                            })
                                        }
                                    },
                                    error: function (error) {
                                
                                        var obj_errors = error.responseJSON;
                                        var txt_errors = '';
                                        for (k of Object.keys(obj_errors)) {
                                            txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                        }
                                        Swal.fire({
                                            type: 'warning',
                                            html: txt_errors,
                                        })
                                    }
                                });
                            }
                        })
                    }

                    function remindPayMonthNowByUser(id_history_pay_loan_capital){
                        Swal.fire({
                            type: 'warning',
                            text: 'Bạn đã chuyển khoản trả nợ định kỳ cho công ty? và bây giờ bạn muốn gửi email thông báo cho bộ phận kế toán?',
                            showCancelButton: true,
                            confirmButtonText: 'Có',
                            cancelButtonText: 'Không',
                        }).then(function (result) {
                            if(result.value){ 
                                $.ajaxSetup(
                                {
                                    headers:
                                    {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
        
                                var data = {
                                            id_history_pay_loan_capital : id_history_pay_loan_capital,
                                        };
                                        
                                $.ajax({
                                    method: "POST",
                                    url: '<?php echo e(route("remind-pay-month-now-by-user")); ?>',
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
                                            var obj_errors = response.message;
                                            var txt_errors = '';
                                            for (k of Object.keys(obj_errors)) {
                                                txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                            }
                                            Swal.fire({
                                                type: 'warning',
                                                html: txt_errors,
                                                allowOutsideClick: false
                                            })
                                        }
                                    },
                                    error: function (error) {
                                
                                        var obj_errors = error.responseJSON;
                                        var txt_errors = '';
                                        for (k of Object.keys(obj_errors)) {
                                            txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                        }
                                        Swal.fire({
                                            type: 'warning',
                                            html: txt_errors,
                                        })
                                    }
                                });
                            }
                        })
                    }
                </script>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 22%">T/g có hiệu lực</th>
                            <th class="text-center">L/s<br> (% năm)</th>
                            <th class="text-center">L/s ưu đãi<br> (% năm)</th>
                            <th class="text-center">T/g ưu đãi (từ lúc bắt đầu trả nợ)<br> (tháng)</th>
                            <th class="text-center">L/s trả chậm<br> (% năm)</th>
                            <th class="text-center">L/s nếu không hoàn thiện hồ sơ<br> (% năm)</th>
                            <th class="text-center">T/g vay tối đa (tháng)</th>
                            <th class="text-center">Lượng vay tối đa (x tháng lương)</th>
                            <th class="text-center">T/g hoàn thiện hồ sơ (ngày)</th>
                            <th class="text-center">T/g bắt đầu phải trả tiền tính từ từ ngày giải ngân (tháng)</th>
                            <th class="text-center">Điểm tín nhiệm của tôi</th>
                            <th class="text-center">Điểm tín nhiệm <br>tối thiểu</th>
                        </tr>
                    </thead>
                    <tbody class="special">
                        <?php if($config_loan_capital): ?>
                            <tr>
                                <td class="text-center">
                                    Từ <?php echo e(BatvHelper::formatDate($config_loan_capital->apply_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> 
                                    đến <?php echo e(BatvHelper::formatDate($config_loan_capital->apply_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> 
                                </td>
                                <td class="text-center"><?php echo e($config_loan_capital->interest_rate); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->preferential_interest_rate); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->count_month_preferential); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->deferred_interest); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->interest_file_late); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->month_time_max); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->x_salary); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->time_complete_file); ?></td>
                                <td class="text-center"><?php echo e($config_loan_capital->start_month_pay); ?></td>
                                <td class="text-center">
                                    <style>
                                        .popover {
                                            width:400px;
                                            max-width:none;
                                        }
                                        .overtime .popover-content {
                                            width: 100%;
                                        }
                                    </style>
                                    <a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true"  data-content='<table class="table table-bordered"><thead><tr> <th class="text-center">Điểm thâm niên</th> <th class="text-center">Điểm chức danh</th> <th class="text-center">Khác</th> </tr> </thead> <tbody> <tr class="text-center"><td><?php echo e($personnel->score_seniority); ?></td><td><?php echo e($personnel->score_position); ?></td><td><?php echo e($personnel->score_faith - $personnel->score_seniority - $personnel->score_position); ?></td></tr></tbody></table>'><?php echo e($score_faith); ?></a>
                                </td>
                                <td class="text-center"><?php echo e($config_loan_capital->score_min); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if(count($history_loan_capital) > 0): ?>
                    <h4 class="title-fuction">
                        Lịch sử vay
                    </h4>
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center">Số tiền vay</th>
                                <th class="text-center">Ngày <br>giải ngân</th>
                                <th class="text-center">Ngày n/v <br>bắt đầu trả</th>
                                <th class="text-center">Ngày <br>kết thúc</th>
                                <th class="text-center">L/s<br> (% năm)</th>
                                <th class="text-center">L/s ưu đãi <br> (% năm)</th>
                                <th class="text-center">Số tháng ưu đãi (từ lúc bắt đầu trả nợ)</th>
                                <th class="text-center">L/s trả chậm<br> (% năm)</th>
                                <th class="text-center">Tổng <br>tiền lãi</th>
                                <th class="text-center">Phạt trả chậm</th>
                                <th class="text-center">Đã trả</th>
                                <th class="text-center">Còn lại</th>
                            </tr>
                        </thead>
                        <?php foreach($history_loan_capital as $history): ?>
                        <tbody>
                            <td class="text-center"><?php echo e(number_format($history->max_money)); ?></td>
                            <td class="text-center"><?php echo e(BatvHelper::formatDate($history->disbursement_date,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false)); ?></td>
                            <td class="text-center"><?php echo e(BatvHelper::formatDate($history->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false)); ?></td>
                            <td class="text-center"><?php echo e(BatvHelper::formatDate($history->received_date,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false)); ?></td>
                            <td class="text-center"><?php echo e($history->interest_rate); ?></td>
                            <td class="text-center"><?php echo e($history->preferential_interest_rate); ?></td>
                            <td class="text-center"><?php echo e($history->count_month_preferential); ?></td>
                            <td class="text-center"><?php echo e($history->deferred_interest); ?></td>
                            <td class="text-center"><?php echo e(number_format($history->total_interest )); ?></td>
                            <td class="text-center"><?php echo e(number_format($history->total_interest_incurred )); ?></td>
                            <td class="text-center"><?php echo e(number_format($history->total_paid_money )); ?></td>
                            <td class="text-center">
                                <?php echo e(number_format(round(($history->max_money + $history->total_interest + $history->total_interest_incurred) - $history->total_paid_money ))); ?>

                            </td>
                        </tbody>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>

                <?php if($status_pending != null): ?>
                    <div style="color:#3c763d;font-size:15px;">
                        <?php echo e($status_pending); ?>

                    </div>
                    <?php if($status_pending == 'Bạn đang có yêu cầu vay chờ duyệt.'): ?>
                        <h4 class="title-fuction">
                            Thông tin đăng ký
                        </h4>
                        <?php if($updated_file > 0): ?>
                            <div class="row" id="editLoanRegister">
                                <div class="col-sm-3 form-group">
                                    <label>Số tiền vay (VND)</label>
                                    <input data-type="currency" class="form-control input-sm" name="money" type="text" value="<?php echo e(number_format($loan_capital_pending->max_money)); ?>">
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>T/g vay (tháng)</label>
                                    <input class="form-control input-sm" name="month_time" type="text" value="<?php echo e($loan_capital_pending->month_time); ?>" maxlength="3"  onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>T/g mong muốn giải ngân</label>
                                    <input type="text" class="form-control input-sm" name="disbursement_date_by_user" pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo e(BatvHelper::formatDate($loan_capital_pending->disbursement_date_by_user, 'Y-m-d', 'd/m/Y', 'H:i:s', false)); ?>" autocomplete="off">
                                    <script>
                                        $(function() {
                                            $( "input[name=disbursement_date_by_user]" ).datepicker({
                                                    changeMonth: true,
                                                    changeYear: true,
                                                    yearRange: "2019:2050",
                                                    dateFormat: 'dd/mm/yy',
                                                }	
                                            );
                                        });
                                    </script>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Điểm tín nhiệm</label>
                                    <div>
                                        <?php echo e($score_faith); ?>/<?php echo e($config_loan_capital->score_min); ?>

                                        <?php if($score_faith >= $config_loan_capital->score_min): ?>
                                            <span class="daduyet">Đủ tiêu chuẩn</span>
                                            <?php else: ?>
                                            <span class="dahuy">Chưa đủ tiêu chuẩn</span>
                                            <?php /* <div style="color:red; margin-top:10px;">Bạn vẫn có thể đăng ký khi chưa đủ điểm tín nhiệm.</div> */ ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>Mục đích vay</label><br>
                                    <div class="">
                                        <input type="radio" name="loan_purpose" value="1" <?php if($loan_capital_pending->loan_purpose == 1): ?> checked <?php endif; ?>> Vay mua nhà
                                    </div>
                                    <div class="">
                                        <input type="radio" name="loan_purpose" value="2" <?php if($loan_capital_pending->loan_purpose == 2): ?> checked <?php endif; ?>> Vay mua xe
                                    </div>
                                    <div class="loan-purpose">
                                        <input type="radio" name="loan_purpose" value="3" <?php if($loan_capital_pending->loan_purpose == 3): ?> checked <?php endif; ?>> Mục đích khác
                                        <?php if($loan_capital_pending->loan_purpose == 3): ?> 
                                            <textarea class="form-control" data-autoresize="" rows="5" placeholder="Nêu mục đích..." name="another_purpose"><?php echo e($loan_capital_pending->another_purpose); ?></textarea>
                                        <?php else: ?>
                                            <textarea class="form-control hidden" data-autoresize="" rows="5" placeholder="Nêu mục đích..." name="another_purpose"></textarea>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>Hình thức giải ngân</label>
                                    <div>
                                        <input type="radio" name="disbursement_form" <?php if($loan_capital_pending->disbursement_form == 1): ?> checked <?php endif; ?> value="1"> Giải ngân trực tiếp tới đơn vị thụ hưởng
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="disbursement_form" <?php if($loan_capital_pending->disbursement_form == 2): ?> checked <?php endif; ?> value="2"> Giải ngân tới người vay
                                    </div>
                                    <textarea class="form-control" data-autoresize="" rows="5" placeholder="Thông tin tài khoản nhận giải ngân..." name="info_receive_disbursement"><?php echo e($loan_capital_pending->info_receive_disbursement); ?></textarea>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>Hình thức trả</label>
                                    <div>
                                        <input type="radio" name="pay" value="1" <?php if($loan_capital_pending->pay == 1): ?> checked <?php endif; ?>> Trừ vào lương
                                    </div>
                                    <?php /* <div>
                                        <input type="radio" name="pay" value="2" <?php if($loan_capital_pending->pay == 2): ?> checked <?php endif; ?>> Tự trả qua chuyển khoản
                                    </div> */ ?>
                                </div>
                                <?php if($loan_capital_pending->status_file == 0): ?>
                                <div class="col-sm-12 form-group">
                                    <label>Hồ sơ</label>
                                    <div class="dropzone dz-clickable clearfix" id="myDrop">
                                        <div class="dz-default dz-message" data-dz-message="">
                                            <i class="fa fa-upload fa-4x" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <!-- Dropzone Preview Template -->
                                    <div id="preview-template" style="display: none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-image"><img data-dz-thumbnail=""></div>
                                            <div class="dz-details">
                                                <?php /* <div class="dz-size"><span data-dz-size=""></span></div> */ ?>
                                                <div class="dz-filename"><span data-dz-name=""></span></div>
                                            </div>
                                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                            <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-sm-12 form-group">
                                    <div class="alert alert-danger fade in alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <p>Hồ sơ trước: Hợp đồng mua bán, biên bản hoặc hợp đồng đặt cọc.</p>
                                        <p>Hồ sơ hoàn thiện: Giấy chứng nhận quyền sử dụng (sổ đỏ) hoặc hợp đồng mua bán nếu mua nhà theo tiến độ, đăng ký xe.</p>
                                        <p>LƯU Ý : khả năng được phê duyệt cao hơn nếu có trước hồ sơ, hình thức giải ngân trực tiếp đơn vị thụ hưởng, vay mua nhà hoặc mua xe.</p>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="editLoanRegister()">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    myDropzone = new Dropzone("div#myDrop", 
                                    { 
                                        paramName: "files", // The name that will be used to transfer the file
                                        addRemoveLinks: true,
                                        uploadMultiple: true,
                                        autoProcessQueue: false,
                                        parallelUploads: 50,
                                        maxFilesize: 50, // MB
                                        thumbnailWidth: null,
                                        thumbnailHeight: null,
                                        dictRemoveFile: '<i class="fa fa-times fa-3" aria-hidden="true"></i>',
                                        // acceptedFiles: ".png, .jpeg, .jpg, .gif",
                                        previewTemplate: document.querySelector('#preview-template').innerHTML,
                                        url: "<?php echo e(route('droponejs-file')); ?>",
                                        headers: {
                                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                                        },
                            
                                        success: function(file, response){
                                            $(".fa-times").remove();
                                            $(".ajax_waiting").removeClass("loading");

                                            Swal.fire({
                                                type: "success",
                                                html: response.message,
                                                allowOutsideClick: false
                                            }).then(function(result){
                                                if(result.value){
                                                    location.reload();
                                                }
                                            })
                                            
                                        },
                                        accept: function(file, done) {
                                            check_action_droponejs = true;
                                            done();
                                        },
                                        error: function(file, message, xhr){
                                            file.previewElement.remove()
                                            Swal.fire({
                                                type: 'warning',
                                                html: message,
                                            })
                                    
                                        },
                                        sending: function(file, xhr, formData) {    
            
                                        },
                                        complete: function complete(file) {
                                            if (file._removeLink) {
                                                file._removeLink.innerHTML = this.options.dictRemoveFile;
                                            }
                                            if (file.previewElement) {
                                                return file.previewElement.classList.add("dz-complete");
                                            }
                                        },
                                        init: function() {
                                            var thisDropzone = this;
                                            str = '<?php echo e($loan_capital_pending->file); ?>';
                                            data = str.split(",");
            
                                            $.each(data, function(key,value){
                                                if (value) {
                                                    var mockFile = { name: value };
                                                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                                                    var ext = value.split('.').pop();
                                    
                                                    if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                                        var image_path = "<?php echo e(url('/images/general/document.png')); ?>";
                                                    } else {
                                                        var image_path = "<?php echo e(url('/images')); ?>" + "/" +value;
                                                    }
                                                    
                                                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, image_path);
                                                    $('#editLoanRegister').append('<input type="hidden" name="file_old[]" value="' + value + '" data-id="0">')
                                                }
                                            });
                            
                                            this.on('addedfile', function(file) {
                                                if (file.size == 0) {
                                                    Swal.fire({
                                                        type: 'warning',
                                                        html: 'Xin vui lòng nhập file hợp lệ!',
                                                    })
                                                    
                                                    file.previewElement.remove()
                                                    return;
                                                }

                                                var ext = file.name.split('.').pop();

                                                if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                                    $(file.previewElement).find(".dz-image img").attr("src", "<?php echo e(asset('images/general/document.png')); ?>");
                                                }

                                                if (file.name in uploadedDocumentMap) {
                                                    Swal.fire({
                                                        type: 'warning',
                                                        html: 'File đã tồn tại!',
                                                    })
                                                    file.previewElement.remove()
                                                } else {
                                                    uploadedDocumentMap[file.name] = file.name
                                                }

                                            });

                                            this.on('removedfile', function(file) {
                                                file.previewElement.remove()
                                                var name = uploadedDocumentMap[file.name]
                                                $('#editLoanRegister').find('input[name="file_old[]"][value="' + name + '"]').attr('data-id', 1)
                                                delete uploadedDocumentMap[file.name];
                                            });

                                            // this.on('thumbnail', function(file, dataUri) {
                                            //     arr_link_base64.push(dataUri);
                                            // });
                                        },
                                    });
                                });

                                // $("#editLoanRegister input[name=money]").keyup(function() {
                                //     $('#editLoanRegister input[name=money_real]').val($(this).val().replace(/,/g,''));
                                //     var str = $(this).val().replace(/\D+/g, '');
                                //     $(this).val(str.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
                                // });

                                function editLoanRegister(){
                                    $.ajaxSetup(
                                    {
                                        headers:
                                        {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });

                                    var loan_purpose = $('#editLoanRegister input[name=loan_purpose]:checked').val();
                                    var another_purpose = $('#editLoanRegister textarea[name=another_purpose]').val();

                                    if (loan_purpose == 3 && another_purpose == '') {
                                        Swal.fire({
                                                    type: 'warning',
                                                    html: 'Xin vui lòng nêu mục đích vay!',
                                                    allowOutsideClick: false
                                                })
                                        
                                        return;
                                    }

                                    var file_old_delete = $('#editLoanRegister input[name^=file_old][data-id=1]').map(function(idx, elem) {
                                        return $(elem).val();
                                    }).get()

                                    var file_old = $('#editLoanRegister input[name^=file_old][data-id=0]').map(function(idx, elem) {
                                        return $(elem).val();
                                    }).get()

                                    var data = {
                                                max_money : $('#editLoanRegister input[name=money]').val().replace(/,/g,''),
                                                month_time : $('#editLoanRegister input[name=month_time]').val().replace(/,/g,''),
                                                disbursement_date_by_user : $('#editLoanRegister input[name=disbursement_date_by_user]').val(),
                                                loan_purpose : loan_purpose,
                                                another_purpose : another_purpose,
                                                disbursement_form : $('#editLoanRegister input[name=disbursement_form]:checked').val(),
                                                info_receive_disbursement : $('#editLoanRegister textarea[name=info_receive_disbursement]').val(),
                                                pay : $('#editLoanRegister input[name=pay]:checked').val(),
                                                file_old : file_old,
                                                file_old_delete : file_old_delete,
                                            };

                                    $.ajax({
                                        method: "POST",
                                        url: '<?php echo e(url("toh_hrm/api/user-edit-register-loan-capital")); ?>',
                                        data:data, 
                                        dataType: 'json',
                                        beforeSend: function() {
                                            $(".ajax_waiting").addClass("loading");
                                        },
                                        complete: function() {
                                            if (check_action_droponejs == false) {
                                                $(".ajax_waiting").removeClass("loading");
                                            }
                                        },
                                        success: function (response) {
                                            if(response.status == 200){
                                                myDropzone.processQueue();
                                                if (check_action_droponejs == false) {
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
                                            } else if(response.status == 401) {
                                                Swal.fire({
                                                    type: "warning",
                                                    html: response.message,
                                                    allowOutsideClick: false
                                                })
                                                
                                                $('#loanRegister').modal('toggle');
                                            } else{
                                            
                                                var obj_errors = response.message;
                                                var txt_errors = '';
                                                for (k of Object.keys(obj_errors)) {
                                                    txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                                }
                                                Swal.fire({
                                                    type: 'warning',
                                                    html: txt_errors,
                                                    allowOutsideClick: false
                                                })
                                            }
                                        },
                                        error: function (error) {
                                        
                                            console.log(error)
                                            var obj_errors = error.responseJSON;
                                            var txt_errors = '';
                                            for (k of Object.keys(obj_errors)) {
                                                txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                            }
                                            Swal.fire({
                                                type: 'warning',
                                                html: txt_errors,
                                            })
                                        }
                                    });
                                } 
                            </script>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Số tiền vay (VND)</label>
                                    <div><?php echo e(number_format($loan_capital_pending->max_money)); ?></div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>T/g vay (tháng)</label>
                                    <div><?php echo e($loan_capital_pending->month_time); ?></div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>T/g mong muốn giải ngân</label>
                                    <div><?php echo e(BatvHelper::formatDate($loan_capital_pending->disbursement_date_by_user, 'Y-m-d', 'd/m/Y', 'H:i:s', false)); ?></div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Điểm tín nhiệm</label>
                                    <div>
                                        <?php echo e($score_faith); ?>/<?php echo e($config_loan_capital->score_min); ?>

                                        <?php if($score_faith >= $config_loan_capital->score_min): ?>
                                            <span class="daduyet">Đủ tiêu chuẩn</span>
                                            <?php else: ?>
                                            <span class="dahuy">Chưa đủ tiêu chuẩn</span>
                                            <?php /* <div style="color:red; margin-top:10px;">Bạn vẫn có thể đăng ký khi chưa đủ điểm tín nhiệm.</div> */ ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Mục đích vay</label><br>
                                    <?php if($loan_capital_pending->loan_purpose == 1): ?>
                                        <div>Vay mua nhà</div>
                                    <?php elseif($loan_capital_pending->loan_purpose == 2): ?>
                                        <div>Vay mua xe</div>
                                    <?php else: ?>
                                        <div><?php echo e($loan_capital_pending->another_purpose); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Hình thức giải ngân</label>
                                    <?php if($loan_capital_pending->disbursement_form == 1): ?>
                                        <div>Giải ngân trực tiếp tới đơn vị thụ hưởng</div>
                                    <?php else: ?>
                                        <div>Giải ngân tới người vay</div>
                                    <?php endif; ?>

                                    <?php if($loan_capital_pending->info_receive_disbursement != ''): ?>
                                        <label class="control-label">Thông tin tài khoản nhận giải ngân:</label>
                                        <div>
                                            <?php echo $loan_capital_pending->info_receive_disbursement; ?>

                                        </div>
                                    <?php endif; ?>


                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Hình thức trả</label>
                                    <?php if($loan_capital_pending->pay == 1): ?>
                                        <div>Trừ vào lương</div>
                                    <?php else: ?>
                                        <div>Tự trả qua chuyển  khoản</div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label>Hồ sơ</label>
                                    <div class="dropzone dz-clickable clearfix" id="myDrop">
                                        <div class="dz-default dz-message" data-dz-message="">
                                            <i class="fa fa-upload fa-4x" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <!-- Dropzone Preview Template -->
                                    <div id="preview-template" style="display: none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-image"><img data-dz-thumbnail=""></div>
                                            <div class="dz-details">
                                                <?php /* <div class="dz-size"><span data-dz-size=""></span></div> */ ?>
                                                <div class="dz-filename"><span data-dz-name=""></span></div>
                                            </div>
                                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                            <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    myDropzone = new Dropzone("div#myDrop", 
                                    { 
                                        clickable: false,
                                        paramName: "files", // The name that will be used to transfer the file
                                        addRemoveLinks: true,
                                        uploadMultiple: true,
                                        autoProcessQueue: false,
                                        parallelUploads: 50,
                                        maxFilesize: 50, // MB
                                        thumbnailWidth: null,
                                        thumbnailHeight: null,
                                        dictRemoveFile: '',
                                        // acceptedFiles: ".png, .jpeg, .jpg, .gif",
                                        previewTemplate: document.querySelector('#preview-template').innerHTML,
                                        url: "<?php echo e(route('droponejs-file')); ?>",
                                        headers: {
                                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                                        },
                            
                                        success: function(file, response){
                                            $(".fa-times").remove();
                                            $(".ajax_waiting").removeClass("loading");

                                            Swal.fire({
                                                type: "success",
                                                html: response.message,
                                                allowOutsideClick: false
                                            }).then(function(result){
                                                if(result.value){
                                                    location.reload();
                                                }
                                            })
                                            
                                        },
                                        accept: function(file, done) {
                                            check_action_droponejs = true;
                                            done();
                                        },
                                        error: function(file, message, xhr){
                                            file.previewElement.remove()
                                            Swal.fire({
                                                type: 'warning',
                                                html: message,
                                            })
                                    
                                        },
                                        sending: function(file, xhr, formData) {    
            
                                        },
                                        complete: function complete(file) {
                                            if (file._removeLink) {
                                                file._removeLink.innerHTML = this.options.dictRemoveFile;
                                            }
                                            if (file.previewElement) {
                                                return file.previewElement.classList.add("dz-complete");
                                            }
                                        },
                                        init: function() {
                                            var thisDropzone = this;
                                            str = '<?php echo e($loan_capital_pending->file); ?>';
                                            data = str.split(",");
            
                                            $.each(data, function(key,value){
                                                if (value) {
                                                    var mockFile = { name: value };
                                                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                                                    var ext = value.split('.').pop();
                                    
                                                    if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                                        var image_path = "<?php echo e(url('/images/general/document.png')); ?>";
                                                    } else {
                                                        var image_path = "<?php echo e(url('/images')); ?>" + "/" +value;
                                                    }
                                                    
                                                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile, image_path);
                                                    $('#editLoanRegister').append('<input type="hidden" name="file_old[]" value="' + value + '" data-id="0">')
                                                }
                                            });
                            
                                            this.on('addedfile', function(file) {
                                                if (file.size == 0) {
                                                    Swal.fire({
                                                        type: 'warning',
                                                        html: 'Xin vui lòng nhập file hợp lệ!',
                                                    })
                                                    
                                                    file.previewElement.remove()
                                                    return;
                                                }

                                                var ext = file.name.split('.').pop();

                                                if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                                    $(file.previewElement).find(".dz-image img").attr("src", "<?php echo e(asset('images/general/document.png')); ?>");
                                                }

                                                if (file.name in uploadedDocumentMap) {
                                                    Swal.fire({
                                                        type: 'warning',
                                                        html: 'File đã tồn tại!',
                                                    })
                                                    file.previewElement.remove()
                                                } else {
                                                    uploadedDocumentMap[file.name] = file.name
                                                }

                                            });

                                            this.on('removedfile', function(file) {
                                                file.previewElement.remove()
                                                var name = uploadedDocumentMap[file.name]
                                                $('#editLoanRegister').find('input[name="file_old[]"][value="' + name + '"]').attr('data-id', 1)
                                                delete uploadedDocumentMap[file.name];
                                            });
                                        },
                                    });
                                });
                            </script>
                        <?php endif; ?>
                    <?php else: ?>
                        <div id="loanRegister" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Đăng ký vay vốn</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label>Số tiền vay (VND)</label>
                                                <?php
                                                    $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
                                                    $max_money_placeholder = 0; 
                                                    $month_time_max = 36;

                                                    if($config_loan_capital) {
                                                        $salary_official_default = BatvHelper::ltt('',Auth::id(),$time,$type=1,'',$option=1,$convert_ratio='');
                                                        $month_time_max = $config_loan_capital->month_time_max;
                                                        $max_money_placeholder = number_format(0.7*$month_time_max * $salary_official_default);
                                                    }

                                                ?>
                                                <input data-type="currency" class="form-control input-sm" name="money" type="text" value="" placeholder="Tối đa: <?php echo e($max_money_placeholder); ?> VNĐ">
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>T/g vay (tháng)</label>
                                                <input class="form-control input-sm" name="month_time" type="text" maxlength="3" onkeyup="this.value=this.value.replace(/[^\d]/,'')" placeholder="Tối đa: <?php echo e($month_time_max); ?> tháng">
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>T/g mong muốn giải ngân</label>
                                                <input type="text" class="form-control input-sm" name="disbursement_date_by_user" pattern="\d{1,2}/\d{1,2}/\d{4}" value="" autocomplete="off">
                                                <script>
                                                    $(function() {
                                                        $( "input[name=disbursement_date_by_user]" ).datepicker({
                                                                changeMonth: true,
                                                                changeYear: true,
                                                                yearRange: "2019:2050",
                                                                dateFormat: 'dd/mm/yy',
                                                            }	
                                                        );
                                                    });
                                                </script>
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label>Điểm tín nhiệm</label>
                                                <div>
                                                    <?php echo e($score_faith); ?>/<?php echo e($config_loan_capital->score_min); ?>

                                                    <?php if($score_faith >= $config_loan_capital->score_min): ?>
                                                        <span class="daduyet">Đủ tiêu chuẩn</span>
                                                    <?php else: ?>
                                                        <span class="dahuy">Chưa đủ tiêu chuẩn</span>
                                                        <div style="color:red; margin-top:10px;">Bạn vẫn có thể đăng ký khi chưa đủ điểm tín nhiệm.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label>Mục đích vay</label><br>
                                                <div class="">
                                                    <input type="radio" name="loan_purpose" checked value="1"> Vay mua nhà
                                                </div>
                                                <div class="">
                                                    <input type="radio" name="loan_purpose" value="2"> Vay mua xe
                                                </div>
                                                <div class="loan-purpose">
                                                    <input type="radio" name="loan_purpose" value="3"> Mục đích khác
                                                    <textarea class="form-control hidden" data-autoresize="" rows="5" placeholder="Nêu mục đích..." name="another_purpose"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 form-group">
                                                <label>Hình thức giải ngân</label>
                                                <div>
                                                    <input type="radio" name="disbursement_form" checked value="1"> Giải ngân trực tiếp tới đơn vị thụ hưởng
                                                </div>
                                                <div class="form-group">
                                                    <input type="radio" name="disbursement_form" value="2"> Giải ngân tới người vay
                                                </div>
                                                <textarea class="form-control" data-autoresize="" rows="5" placeholder="Thông tin tài khoản nhận giải ngân..." name="info_receive_disbursement"></textarea>
                                            </div>

                                            <div class="col-sm-4 form-group">
                                                <label>Hình thức trả</label>
                                                <div>
                                                    <input type="radio" name="pay" checked value="1"> Trừ vào lương
                                                </div>
                                                <?php /* <div>
                                                    <input type="radio" name="pay" value="2"> Tự trả qua chuyển khoản
                                                </div> */ ?>
                                            </div>
                                            <div class="col-sm-12 form-group">
                                                <label>Hồ sơ</label>
                                                <div class="dropzone dz-clickable clearfix" id="myDrop">
                                                    <div class="dz-default dz-message" data-dz-message="">
                                                        <i class="fa fa-upload fa-4x" aria-hidden="true"></i>
                                                        <?php /* <span>Kéo thả file ảnh của bạn vào đây để upload</span> */ ?>
                                                    </div>
                                                </div>
                                                <!-- Dropzone Preview Template -->
                                                <div id="preview-template" style="display: none;">
                                                    <div class="dz-preview dz-file-preview">
                                                        <div class="dz-image"><img data-dz-thumbnail=""></div>
                            
                                                        <div class="dz-details">
                                                            <?php /* <div class="dz-size"><span data-dz-size=""></span></div> */ ?>
                                                            <div class="dz-filename"><span data-dz-name=""></span></div>
                                                        </div>
                                                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 form-group">
                                                <div class="alert alert-danger fade in alert-dismissible">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                                    <p>Hồ sơ trước: Hợp đồng mua bán, biên bản hoặc hợp đồng đặt cọc.</p>
                                                    <p>Hồ sơ hoàn thiện: Giấy chứng nhận quyền sử dụng (sổ đỏ) hoặc hợp đồng mua bán nếu mua nhà theo tiến độ, đăng ký xe.</p>
                                                    <p>LƯU Ý : khả năng được phê duyệt cao hơn nếu có trước hồ sơ, hình thức giải ngân trực tiếp đơn vị thụ hưởng, vay mua nhà hoặc mua xe.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="loanRegister()">Đăng ký</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                myDropzone = new Dropzone("div#myDrop", 
                                { 
                                    paramName: "files", // The name that will be used to transfer the file
                                    addRemoveLinks: true,
                                    uploadMultiple: true,
                                    autoProcessQueue: false,
                                    parallelUploads: 50,
                                    maxFilesize: 50, // MB
                                    thumbnailWidth: null,
                                    thumbnailHeight: null,
                                    dictRemoveFile: '<i class="fa fa-times fa-3" aria-hidden="true"></i>',
                                    // acceptedFiles: ".png, .jpeg, .jpg, .gif",
                                    previewTemplate: document.querySelector('#preview-template').innerHTML,
                                    url: "<?php echo e(route('droponejs-file')); ?>" + "?register=1",
                                    headers: {
                                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                                    },
                        
                                    success: function(file, response){
              
                                        $(".ajax_waiting").removeClass("loading");

                                        Swal.fire({
                                            type: 'success',
                                            html: response.message,
                                        }).then(function(result){
                                            if(result.value){
                                                location.reload();
                                            }
                                        })
                                        
                                    },
                                    accept: function(file, done) {
                                        check_action_droponejs = true;
                                        done();
                                    },
                                    error: function(file, message, xhr){
                                        file.previewElement.remove()
                                        
                                        Swal.fire({
                                            type: 'warning',
                                            html: message,
                                        })
                                    },
                                    sending: function(file, xhr, formData) {
                
                                    },
                                    complete: function complete(file) {
                                        if (file._removeLink) {
                                            file._removeLink.innerHTML = this.options.dictRemoveFile;
                                        }
                                        if (file.previewElement) {
                                            return file.previewElement.classList.add("dz-complete");
                                        }

                                    },
                                    init: function() {
                                        var thisDropzone = this;
                                        this.on('addedfile', function(file) {

                                            if (file.size == 0) {
                                                file.previewElement.remove()
                                                Swal.fire({
                                                    type: 'warning',
                                                    html: 'Xin vui lòng nhập file hợp lệ!',
                                                })

                                                return;
                                            }

                                            var ext = file.name.split('.').pop();

                                            if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                                $(file.previewElement).find(".dz-image img").attr("src", "<?php echo e(asset('images/general/document.png')); ?>");
                                            }

                                            if (file.name in uploadedDocumentMap) {
                                                Swal.fire({
                                                    type: 'warning',
                                                    html: 'File đã tồn tại!',
                                                })
                                                file.previewElement.remove()
                                            } else {
                                                uploadedDocumentMap[file.name] = file.name
                                            }

                                        });

                                        this.on('removedfile', function(file) {
                                            file.previewElement.remove()
                                            var name = uploadedDocumentMap[file.name]
                                            $('#editLoanRegister').find('input[name="file_old[]"][value="' + name + '"]').attr('data-id', 1)
                                            delete uploadedDocumentMap[file.name];
                                        });

                                    },
                                });


                                $('#loanRegister').on('hidden.bs.modal', function (e) {
                                    $('#loanRegister input[type=text],#loanRegister textarea').val('');
                                    $("input[name=loan_purpose][value=1]").prop('checked',true);
                                    $("input[name=disbursement_form][value=1]").prop('checked',true);
                                    $("input[name=pay][value=1]").prop('checked',true);
                                    $("#loanRegister textarea[name=another_purpose]").addClass('hidden');
                                    myDropzone.removeAllFiles(true); 
                                })
                            });

                            $('#loanRegister input[type=radio][name=loan_purpose]').change(function() {
                                if (this.value == 3) {
                                    $('#loanRegister .loan-purpose textarea').removeClass('hidden');
                                } else {
                                    $('#loanRegister .loan-purpose textarea').addClass('hidden');
                                }
                            });

                            function loanRegister(){
                                $.ajaxSetup(
                                {
                                    headers:
                                    {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                var loan_purpose = $('#loanRegister input[name=loan_purpose]:checked').val();
                                var another_purpose = $('#loanRegister textarea[name=another_purpose]').val();

                                if (loan_purpose == 3 && another_purpose == '') {
                                    Swal.fire({
                                                type: 'warning',
                                                html: 'Xin vui lòng nêu mục đích vay!',
                                                allowOutsideClick: false
                                            })
                                    
                                    return;
                                }
                                
                                var file_old_delete = $('#loanRegister input[name^=file_old][data-id=1]').map(function(idx, elem) {
                                    return $(elem).val();
                                }).get()

                                var file_old = $('#loanRegister input[name^=file_old][data-id=0]').map(function(idx, elem) {
                                    return $(elem).val();
                                }).get()

                                var data = {
                                            max_money : $('#loanRegister input[name=money]').val().replace(/,/g,''),
                                            month_time : $('#loanRegister input[name=month_time]').val().replace(/,/g,''),
                                            disbursement_date_by_user : $('#loanRegister input[name=disbursement_date_by_user]').val(),
                                            loan_purpose : loan_purpose,
                                            another_purpose : another_purpose,
                                            disbursement_form : $('#loanRegister input[name=disbursement_form]:checked').val(),
                                            info_receive_disbursement : $('#loanRegister textarea[name=info_receive_disbursement]').val(),
                                            pay : $('#loanRegister input[name=pay]:checked').val(),
                                            file_old : file_old,
                                            file_old_delete : file_old_delete,
                                        };

                                $.ajax({
                                    method: "POST",
                                    url: '<?php echo e(url("toh_hrm/api/user-register-loan-capital")); ?>',
                                    data:data, 
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $(".ajax_waiting").addClass("loading");
                                    },
                                    complete: function() {
                                        if (check_action_droponejs == false) {
                                            $(".ajax_waiting").removeClass("loading");
                                        }
                                    },
                                    success: function (response) {
                                        if(response.status == 200){
                                            myDropzone.processQueue()

                                            if (check_action_droponejs == false) {
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

                                        } else if(response.status == 401) {
                                            $(".ajax_waiting").removeClass("loading");
                                            Swal.fire({
                                                type: "warning",
                                                html: response.message,
                                                allowOutsideClick: false
                                            })
                                            
                                            $('#loanRegister').modal('toggle');
                                        } else{
                                            $(".ajax_waiting").removeClass("loading");
                                            var obj_errors = response.message;
                                            var txt_errors = '';
                                            for (k of Object.keys(obj_errors)) {
                                                txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                            }
                                            Swal.fire({
                                                type: 'warning',
                                                html: txt_errors,
                                                allowOutsideClick: false
                                            })
                                        }
                                    },
                                    error: function (error) {
                                        $(".ajax_waiting").removeClass("loading");
                                        console.log(error)
                                        var obj_errors = error.responseJSON;
                                        var txt_errors = '';
                                        for (k of Object.keys(obj_errors)) {
                                            txt_errors += '<p style="text-align: left;">' + obj_errors[k][0] + '</p>';
                                        }
                                        Swal.fire({
                                            type: 'warning',
                                            html: txt_errors,
                                        })
                                        
                                    }
                                });
                            } 
                        </script>
                    <?php endif; ?>
                <?php endif; ?> 
            <?php endif; ?>
        </div>
    </div>
    <?php
        $interest_rate = $preferential_interest_rate = $count_month_preferential = $start_month_pay = 0;
    ?>
    <?php if($config_loan_capital_current): ?>
        <?php
            $interest_rate = $config_loan_capital_current->interest_rate;
            $preferential_interest_rate = $config_loan_capital_current->preferential_interest_rate;
            $count_month_preferential = $config_loan_capital_current->count_month_preferential;
            $start_month_pay = $config_loan_capital_current->start_month_pay;
        ?>
    <?php endif; ?>
    <div id="loanEstimate" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tính lịch trả nợ với dư nợ giảm dần</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>L/s (% năm)</label>
                            <input class="form-control input-sm" maxlength="4" name="interest_rate" type="text" value="<?php echo e($interest_rate); ?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>L/s ưu đãi (% năm)</label>
                            <input class="form-control input-sm" maxlength="4" name="preferential_interest_rate" type="text" value="<?php echo e($preferential_interest_rate); ?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>T/g ưu đãi (từ lúc bắt đầu trả nợ) (tháng)</label>
                            <input class="form-control input-sm" maxlength="3"   name="count_month_preferential" value="<?php echo e($count_month_preferential); ?>" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Số tiền vay (VND)</label>
                            <input data-type="currency" class="form-control input-sm" name="money_loan" type="text" value="" maxlength="15">
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>T/g vay (tháng)</label>
                            <input class="form-control input-sm" name="month_time"  type="text" maxlength="3"  onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Ngày giải ngân</label>
                            <input class="form-control input-sm" name="disbursement_date" id="date" type="text" value="" autocomplete="off">
                            <script>
                                $(function() {
                                    $( "#date" ).datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: "2019:2050",
                                            dateFormat: 'dd/mm/yy',
                                        }	
                                    );
                                });
                            </script>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>T/g bắt đầu phải trả tiền định kỳ</label>
                            <input class="form-control input-sm" type="text" name="time_to_pay" disabled>
                        </div>
                        <div class="col-sm-12 text-center form-group">
                            <button class="btn btn-primary btn-sm" onclick="calcLoan();" type="button"> Tính </button>
                        </div>
                        <div class="col-sm-12 text-center form-group">
                            <div id="listRepayment" class="tableFixHead"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#editLoanRegister input[type=radio][name=loan_purpose]').change(function() {
            if (this.value == 3) {
                $('#editLoanRegister .loan-purpose textarea').removeClass('hidden');
            } else {
                $('#editLoanRegister .loan-purpose textarea').addClass('hidden');
            }
        });

        $(document).ready(function(){
            $('[data-toggle="popover"]').popover(); 

            $('.txt-loan-repayment').keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, ''); // number only
            });

            // $("#loanEstimate input[name=money_loan]").keyup(function() {
            //     var str = $(this).val();
            //     str = str.replace(/\D+/g, '');
            //     $(this).val(str.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
            // });
        });

        function converNumber(number) {
            var strNumber = "";
            strNumber = String(number);
            return strNumber.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,')
        }

        $('#loanEstimate').on('hidden.bs.modal', function (e) {
            $('#loanEstimate input[name=money_loan],#loanEstimate input[name=month_time],#loanEstimate input[name=disbursement_date],#loanEstimate input[name=time_to_pay]').val('');
            $('#loanEstimate input[name=interest_rate]').val('<?php echo e($interest_rate); ?>');
            $('#loanEstimate input[name=preferential_interest_rate]').val('<?php echo e($preferential_interest_rate); ?>');
            $('#loanEstimate input[name=count_month_preferential]').val('<?php echo e($count_month_preferential); ?>');
            $('#listRepayment').html('');
        })
        start_month_pay = 2;
        // alert(config_loan_capital_all[0].apply_from);

        $("#date").change(function(){
            var disbursement_date = $('#date').val();
            if (disbursement_date != '') {
                if (validationDate(disbursement_date)) {
                    $.ajaxSetup(
                    {
                        headers:
                        {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var data = {
                                disbursement_date : disbursement_date,
                            };

                    $.ajax({
                        method: "POST",
                        url: '<?php echo e(url("toh_hrm/api/info-interest-rate-config")); ?>',
                        data:data, 
                        dataType: 'json',
                        beforeSend: function() {
                            $(".ajax_waiting").addClass("loading");
                        },
                        complete: function() {
                            $(".ajax_waiting").removeClass("loading");
                        },
                        success: function (response) {
                            if(response.config_loan_capital != null){
                                $('#loanEstimate input[name=interest_rate]').val(response.config_loan_capital.interest_rate)
                                $('#loanEstimate input[name=preferential_interest_rate]').val(response.config_loan_capital.preferential_interest_rate)
                                $('#loanEstimate input[name=count_month_preferential]').val(response.config_loan_capital.count_month_preferential)
                                var start_month_pay = response.config_loan_capital.start_month_pay;
                                var dateObject = $("#date").datepicker('getDate');
                                dateObject.setMonth(dateObject.getMonth() + 1); 
                                var day = dateObject.getDate(); 
                                var month = dateObject.getMonth() + 1; 
                                var year = dateObject.getFullYear(); 
                                var time_to_pay = (day < 10 ? '0' : '') + day + '/' + (month < 10 ? '0' : '') + month + '/' + year;
                                $('#loanEstimate input[name=time_to_pay]').val(time_to_pay);
                            }
                        },
                        error: function (error) {

                        }
                    });
                } else {
                    $('#loanEstimate input[name=time_to_pay]').val('');
                }
                
            } else {
                $('#loanEstimate input[name=time_to_pay]').val('');
            }
        });

        function calcLoan() {
            if ($("#loanEstimate input[name=interest_rate]").val() == "") {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập lãi suất năm!",
                        allowOutsideClick: false
                    })
                // $("#loanEstimate input[name=interest_rate]").focus();
                return;
            }

            if ($("#loanEstimate input[name=preferential_interest_rate]").val() == "") {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập lãi suất ưu đãi!",
                        allowOutsideClick: false
                    })
                // $("#loanEstimate input[name=preferential_interest_rate]").focus();
                return;
            }

            
            if ($("#loanEstimate input[name=count_month_preferential]").val() == "") {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập T/g ưu đãi!",
                        allowOutsideClick: false
                    })
                // $("#loanEstimate input[name=count_month_preferential]").focus();
                return;
            }

            if ($("#loanEstimate input[name=money_loan]").val() == "") {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập số tiền cần vay!",
                        allowOutsideClick: false
                    })
                $("#loanEstimate input[name=money_loan]").focus();
                return;
            } else if($("#loanEstimate input[name=money_loan]").val() == 0) {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập số tiền cần vay hợp lệ!",
                        allowOutsideClick: false
                    })

                // $("#loanEstimate input[name=month_time]").focus();
                return;
            }

            if ($("#loanEstimate input[name=month_time]").val() == "") {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập T/g vay!",
                        allowOutsideClick: false
                    })
                // $("#loanEstimate input[name=month_time]").focus();
                return;
            } else if($("#loanEstimate input[name=month_time]").val() == 0) {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "T/g vay phải lớn hơn 0",
                        allowOutsideClick: false
                    })

                // $("#loanEstimate input[name=month_time]").focus();
                return;
            } else if($("#loanEstimate input[name=month_time]").val() > 1000) {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "T/g vay đã quá giới hạn!",
                        allowOutsideClick: false
                    })

                // $("#loanEstimate input[name=month_time]").focus();
                return;
            }

            if ($("#loanEstimate input[name=disbursement_date]").val() == "") {
                document.getElementById("listRepayment").innerHTML = '';
                Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày giải ngân!",
                        allowOutsideClick: false
                    })
                // $("#loanEstimate input[name=disbursement_date]").focus();
                return;
            } else {
                if (!validationDate($('#date').val())) {
                    document.getElementById("listRepayment").innerHTML = '';
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày giải ngân hợp lệ!",
                        allowOutsideClick: false
                    })
                    // $("#loanEstimate input[name=disbursement_date]").focus();
                    return;
                }
            }

            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var money_loan = $('#loanEstimate input[name=money_loan]').val().replace(/,/g,'');
            money_loan = money_loan.replace(/[^0-9.-]+/g,"");

            var data = {
                        interest_rate : $('#loanEstimate input[name=interest_rate]').val().replace(/,/g,''),
                        preferential_interest_rate : $('#loanEstimate input[name=preferential_interest_rate]').val().replace(/,/g,''),
                        count_month_preferential : $('#loanEstimate input[name=count_month_preferential]').val().replace(/,/g,''),
                        money_loan : money_loan,
                        month_time : $('#loanEstimate input[name=month_time]').val().replace(/,/g,''),
                        disbursement_date : $('#loanEstimate input[name=disbursement_date]').val(),
                    };
                    
            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/calculate-demo-loan-capital")); ?>',
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
                        var data = response.data;
                        $('#loanEstimate input[name=time_to_pay]').val(data[1]['repayment_period']);
                        var strResult = "";
                        strResult = "<table class=\"table table-bordered\">"
                        var total_principal = total_interest = 0;
                        strResult += "<thead><tr>" +
                            "<th width=\"20%\" class=\"text-center\" colspan=\"2\">Kỳ trả nợ</th>" +
                            "<th width=\"22%\" class=\"text-center\">Số gốc còn lại</th>" +
                            "<th width=\"18%\" class=\"text-center\">Gốc</th>" +
                            "<th width=\"18%\" class=\"text-center\">Lãi</th>" +
                            "<th width=\"22%\" class=\"text-center\">Tổng gốc + Lãi</th>" +
                            "</tr></thead>";

                        strResult += "<tbody>";

                            for (var i = 0; i < data.length; i++) {
                                total_principal += data[i]['principal'];
                                total_interest += data[i]['interest'];

                                strResult += "<tr>" +
                                    "<td width=\"15%\" class=\"text-center\">" + data[i]['repayment_period'] + "</td>" +
                                    "<td width=\"5%\" class=\"text-center\">" + data[i]['month'] + "</td>" +
                                    "<td width=\"22%\" class=\"text-center\">" + converNumber(Math.round(data[i]['remaining_principal'])) + "</td>" +
                                    "<td width=\"18%\" class=\"text-center\">" + converNumber(Math.round(data[i]['principal']))+ "</td>" +
                                    "<td width=\"18%\" class=\"text-center\">" + converNumber(Math.round(data[i]['interest'])) + "</td>" +
                                    "<td width=\"22%\" class=\"text-center\">" + converNumber(Math.round(data[i]['principal'] + data[i]['interest'])) + "</td>" +
                                    "</tr>"
                            }

                            strResult += "<tr>" +
                                "<th width=\"15%\" class=\"text-center\">Tổng</th>" +
                                "<th width=\"5%\" class=\"text-center\"></th>" +
                                "<th width=\"22%\" class=\"text-center\"></th>" +
                                "<th width=\"18%\" class=\"text-center\">" + converNumber(Math.round(total_principal)) + "</th>" +
                                "<th width=\"18%\" class=\"text-center\">" + converNumber(Math.round(total_interest)) + "</th>" +
                                "<th width=\"22%\" class=\"text-center\">" + converNumber(Math.round(total_principal + total_interest)) + "</th>" +
                                "</tr>"

                            strResult += "</tbody></table>";
                            
                            document.getElementById("listRepayment").innerHTML = strResult;
                    }
                },
                error: function (error) {
                }
            });
            
        }

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>