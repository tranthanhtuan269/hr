

<?php $__env->startSection('title', 'Tín dụng'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        #ui-datepicker-div{
            z-index: 9999 !important;
        }
    </style>
    <div class="row overtime">
        <div class="col-lg-2">
            <?php echo $__env->make('layouts.vay-von.menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="col-lg-10">
            <h4 class="title-fuction">Cấu hình vay vốn</h4>
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a data-toggle="tab" href="#home">Cấu hình quỹ</a></li>
                <li>
                    <a data-toggle="tab" href="#menu2">Cấu hình lãi suất &nbsp <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#insertInterestRateConfig"><span class="glyphicon glyphicon-plus"></span></button></a> 
                </li>
                <li>
                    <a data-toggle="tab" href="#menu3">Cấu hình điểm tín nhiệm </a> 
                </li>
                <li>
                    <a data-toggle="tab" href="#menu4">Cấu hình khác </a> 
                </li>
            </ul>
                  
            <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Tổng quỹ ban đầu</th>
                                <th class="text-center">Đã giải ngân</th>
                                <th class="text-center">Tổng thu nợ</th>
                                <th class="text-center">Thu khác</th>
                                <th class="text-center">Quỹ hiện tại</th>
                                <th class="text-center">Quỹ khả dụng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <?php echo e(number_format($config_fund_loan_capital->value)); ?>

                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#editFundLoanCapital">
                                        <img src="<?php echo e(asset('images/general/edit.png')); ?>">
                                    </a>
                                </td class="text-center">
                                <td class="text-center"><?php echo e(number_format($disbursement_money)); ?></td>
                                <td class="text-center"><?php echo e(number_format($amount_collected)); ?></td>
                                <td class="text-center"><?php echo e(number_format($interest_and_interest_incurred)); ?></td>
                                <td class="text-center"><?php echo e(number_format($config_fund_loan_capital->value + $amount_collected - $disbursement_money)); ?></td>
                                <td class="text-center"><?php echo e(number_format($config_fund_loan_capital->value + $amount_collected - $disbursement_money + $interest_and_interest_incurred)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="menu2" class="tab-pane fade">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 12%">T/g có hiệu lực</th>
                                <th class="text-center">L/s<br> (% năm)</th>
                                <th class="text-center">L/s ưu đãi<br> (% năm)</th>
                                <th class="text-center">T/g ưu đãi (từ lúc bắt đầu trả nợ)<br> (tháng)</th>
                                <th class="text-center">L/s trả chậm<br> (% năm)</th>
                                <th class="text-center">T/g n/v bắt đầu phải trả tiền tính từ NGN (tháng)</th>
                                <th class="text-center">Thời hạn vay tối đa (tháng)</th>
                                <th class="text-center">Lượng vay tối đa(x tháng lương)</th>
                                <th class="text-center">T/g hoàn thiện hồ sơ (ngày)</th>
                                <th class="text-center">L/s nếu không hoàn thiện hồ sơ (% năm)</th>
                                <th class="text-center">Điểm tín nhiệm <br>tối thiểu</th>
                                <th style="width: 8%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($config_loan_capital as $value): ?>
                                <tr>
                                    <td class="text-center">
                                        Từ <?php echo e(BatvHelper::formatDate($value->apply_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> 
                                        đến <?php echo e(BatvHelper::formatDate($value->apply_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> 
                                    </td>
                                    <td class="text-center"><?php echo e($value->interest_rate); ?></td>
                                    <td class="text-center"><?php echo e($value->preferential_interest_rate); ?></td>
                                    <td class="text-center"><?php echo e($value->count_month_preferential); ?></td>
                                    <td class="text-center"><?php echo e($value->deferred_interest); ?></td>
                                    <td class="text-center"><?php echo e($value->start_month_pay); ?></td>
                                    <td class="text-center"><?php echo e($value->month_time_max); ?></td>
                                    <td class="text-center"><?php echo e($value->x_salary); ?></td>
                                    <td class="text-center"><?php echo e($value->time_complete_file); ?></td>
                                    <td class="text-center"><?php echo e($value->interest_file_late); ?></td>
                                    <td class="text-center"><?php echo e($value->score_min); ?></td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" data-id="<?php echo e($value->id); ?>" data-toggle="modal" data-target="#updateInterestRateConfig" class="update-interest-rate-config">
                                            <img src="<?php echo e(asset('images/general/edit.png')); ?>">
                                        </a>
                                        <a class="btn-delete" href="javascript:void(0)" onclick="deleteInterestRateConfig(<?php echo e($value->id); ?>)"> 
                                            <img src="<?php echo e(asset('images/general/remove.png')); ?>">
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="menu3" class="tab-pane fade">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 21%">Thâm niên</th>
                                <th class="text-center">Trưởng SP/Team Leader</th>
                                <th class="text-center">Trưởng phòng/Phó TT</th>
                                <th class="text-center">Trưởng TT</th>
                                <?php /* <th class="text-center">Điểm tín nhiệm tối thiểu</th> */ ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><?php echo e($config_score_tn); ?></td>
                                <td class="text-center"><?php echo e($config_score_tsp_tld); ?></td>
                                <td class="text-center"><?php echo e($config_score_tp_ptt); ?></td>
                                <td class="text-center"><?php echo e($config_score_ttt); ?></td>
                                <?php /* <td class="text-center"><?php echo e($config_score_min); ?></td> */ ?>
                                <td class="text-center">
                                    <a href="javascript:void(0)" data-id="<?php echo e($value->id); ?>" data-toggle="modal" data-target="#updateScoreFaithConfig">
                                        <img src="<?php echo e(asset('images/general/edit.png')); ?>">
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="menu4" class="tab-pane fade">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Nhắc trả hàng tháng trước x (ngày)</th>
                                <th class="text-center">Nhắc quá hạn sau y (ngày)</th>
                                <th class="text-center">Email thủ quỹ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><?php echo e($config_remind_after_x_days); ?></td>
                                <td class="text-center"><?php echo e($config_remind_before_x_days); ?></td>
                                <td class="text-center"><?php echo e($config_email_tq); ?></td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" data-id="<?php echo e($value->id); ?>" data-toggle="modal" data-target="#updateOtherConfig">
                                        <img src="<?php echo e(asset('images/general/edit.png')); ?>">
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="insertInterestRateConfig" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thêm mới cấu hình lãi suất</h4>
                </div>
                <div class="modal-body">
                    <div class="row">                
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s (% năm):</label>
                                <input class="form-control input-sm" name="interest_rate" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" >
                            </div>
                        </div>
                 
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s ưu đãi (% năm):</label>
                                <input class="form-control input-sm" name="preferential_interest_rate" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
    
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g ưu đãi (từ lúc bắt đầu trả nợ) (tháng):</label>
                                <input class="form-control input-sm" name="count_month_preferential" type="text" maxlength="3" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s trả chậm (% năm):</label>
                                <input class="form-control input-sm" name="deferred_interest" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Thời hạn vay tối đa (tháng):</label>
                                <input class="form-control input-sm" name="month_time_max" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Lượng vay tối đa (x tháng lương):</label>
                                <input class="form-control input-sm" name="x_salary" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g hoàn thiện hồ sơ (ngày):</label>
                                <input class="form-control input-sm" name="time_complete_file" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s nếu không hoàn thiện hồ sơ (% năm):</label>
                                <input class="form-control input-sm" name="interest_file_late" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Điểm tín nhiệm tối thiểu:</label>
                                <input class="form-control input-sm" name="score_min" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Áp dụng từ:</label>
                                <input type="text" class="form-control input-sm" name="apply_from"  pattern="\d{1,2}/\d{1,2}/\d{4}" autocomplete="off">
                                <script>
                                    $(function() {
                                        $( "#insertInterestRateConfig input[name=apply_from]" ).datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "2019:2050",
                                                dateFormat: 'dd/mm/yy',
                                            }	
                                        );
                                    });
                                    </script>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Áp dụng đến:</label>
                                <input type="text" class="form-control input-sm" name="apply_to"  pattern="\d{1,2}/\d{1,2}/\d{4}" autocomplete="off">
                                <script>
                                    $(function() {
                                        $( "#insertInterestRateConfig input[name=apply_to]" ).datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "2019:2050",
                                                dateFormat: 'dd/mm/yy',
                                            }	
                                        );
                                    });
                                    </script>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g n/v bắt đầu phải trả tiền tính từ NGN (tháng)</label>
                                <input class="form-control input-sm" name="start_month_pay" type="text" maxlength="3" onkeyup="this.value=this.value.replace(/\D/g,'')">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="insertInterestRateConfig()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
    <div id="updateInterestRateConfig" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cập nhật cấu hình lãi suất</h4>
                </div>
                <div class="modal-body">
                    <div class="row">                
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s (% năm):</label>
                                <input class="form-control input-sm" name="interest_rate" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s ưu đãi (% năm):</label>
                                <input class="form-control input-sm" name="preferential_interest_rate" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
    
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g ưu đãi (từ lúc bắt đầu trả nợ) (tháng):</label>
                                <input class="form-control input-sm" name="count_month_preferential" type="text" maxlength="3" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s trả chậm (% năm):</label>
                                <input class="form-control input-sm" name="deferred_interest" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Thời hạn vay tối đa (tháng):</label>
                                <input class="form-control input-sm" name="month_time_max" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Lượng vay tối đa (x tháng lương):</label>
                                <input class="form-control input-sm" name="x_salary" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g hoàn thiện hồ sơ (ngày):</label>
                                <input class="form-control input-sm" name="time_complete_file" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s không hoàn thiện hồ sơ (% năm):</label>
                                <input class="form-control input-sm" name="interest_file_late" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Điểm tín nhiệm tối thiểu:</label>
                                <input class="form-control input-sm" name="score_min" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Áp dụng từ:</label>
                                <input type="text" class="form-control input-sm" name="apply_from"  pattern="\d{1,2}/\d{1,2}/\d{4}" autocomplete="off">
                                <script>
                                    $(function() {
                                        $( "#updateInterestRateConfig input[name=apply_from]" ).datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "2019:2050",
                                                dateFormat: 'dd/mm/yy',
                                            }	
                                        );
                                    });
                                    </script>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Áp dụng đến:</label>
                                <input type="text" class="form-control input-sm" name="apply_to"  pattern="\d{1,2}/\d{1,2}/\d{4}" autocomplete="off">
                                <script>
                                    $(function() {
                                        $( "#updateInterestRateConfig input[name=apply_to]" ).datepicker({
                                                changeMonth: true,
                                                changeYear: true,
                                                yearRange: "2019:2050",
                                                dateFormat: 'dd/mm/yy',
                                            }	
                                        );
                                    });
                                    </script>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g n/v bắt đầu phải trả tiền tính từ NGN (tháng): </label>
                                <input class="form-control input-sm" name="start_month_pay" type="text" maxlength="3" onkeyup="this.value=this.value.replace(/\D/g,'')">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateInterestRateConfig()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
    <div id="editFundLoanCapital" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cập nhật quỹ</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group clearfix">
                                <label class="control-label">Nhập số tiền quỹ (VND):</label>
                                <input data-type="currency" class="form-control input-sm" name="fund_money" type="text" value="<?php echo e(BatvHelper::formatPriceSpecial($config_fund_loan_capital->value)); ?>">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="personnel_id">
                    <button type="button" class="btn btn-primary" onclick="updateFundLoanCapital()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
    <div id="updateScoreFaithConfig" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cấu hình điểm tín nhiệm </h4>
                </div>
                <div class="modal-body">
                    <div class="row">                
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Thâm niên:</label>
                                <input class="form-control input-sm" name="score_tn" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Trưởng SP/Team Leader:</label>
                                <input class="form-control input-sm" name="score_tsp_tld" type="text" maxlength="4"    onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
    
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Trưởng phòng/Phó TT:</label>
                                <input class="form-control input-sm" name="score_tp_ptt" type="text" maxlength="4"    onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Trưởng TT:</label>
                                <input class="form-control input-sm" name="score_ttt" type="text" maxlength="4"    onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>

                        <?php /* <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Điểm tín nhiệm tối thiểu:</label>
                                <input class="form-control input-sm" name="score_min" type="text" maxlength="4" >
                            </div>
                        </div> */ ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateScoreFaithConfig()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
    <div id="updateOtherConfig" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cấu hình khác</h4>
                </div>
                <div class="modal-body">
                    <div class="row">                
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Nhắc trả hàng tháng trước x (ngày):</label>
                                <input class="form-control input-sm" name="remind_after_x_days" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Nhắc nhắc quá hạn sau y (ngày):</label>
                                <input class="form-control input-sm" name="remind_before_x_days" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Email thủ quỹ:</label>
                                <input class="form-control input-sm" name="email_tq" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateOtherConfig()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
    <div id="editLoanCapital" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cập nhật thông tin</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Họ và tên:</label><br>
                                <span class="fullname"></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Điểm tín nhiệm:</label>
                                <input class="form-control input-sm" name="score" type="text" maxlength="5"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">Số tiền vay tối đa (VNĐ):</label>
                                <input data-type="currency" class="form-control input-sm" name="max_money" type="text" value="">
                            </div>
                        </div>
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">L/s ưu đãi (% năm):</label>
                                <input class="form-control input-sm" name="preferential_interest_rate" type="text" value="" required min="0"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group clearfix">
                                <label class="control-label">T/g ưu đãi (từ lúc bắt đầu trả nợ) (tháng):</label>
                                <input class="form-control input-sm" name="month_time" type="text" maxlength="3" onkeyup="this.value=this.value.replace(/[^\d]/,'')" >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="personnel_id">
                    <button type="button" class="btn btn-primary" onclick="updateLoanCapital()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#editFundLoanCapital').on('hidden.bs.modal', function () {
                $('#editFundLoanCapital [name=fund_money]').val('<?php echo e(BatvHelper::formatPriceSpecial($config_fund_loan_capital->value)); ?>');
            });

            $('#insertInterestRateConfig').on('hidden.bs.modal', function () {
                $('#insertInterestRateConfig input').val('');
            });

            $('#updateOtherConfig').on('show.bs.modal', function () {
                $('#updateOtherConfig input[name=remind_before_x_days]').val(<?php echo e($config_remind_before_x_days); ?>);
                $('#updateOtherConfig input[name=remind_after_x_days]').val(<?php echo e($config_remind_after_x_days); ?>);
                $('#updateOtherConfig input[name=email_tq]').val("<?php echo e($config_email_tq); ?>");
            });

            $('#updateScoreFaithConfig').on('show.bs.modal', function () {
                $('#updateScoreFaithConfig input[name=score_tn]').val(<?php echo e($config_score_tn); ?>);
                $('#updateScoreFaithConfig input[name=score_tsp_tld]').val(<?php echo e($config_score_tsp_tld); ?>);
                $('#updateScoreFaithConfig input[name=score_tp_ptt]').val(<?php echo e($config_score_tp_ptt); ?>);
                $('#updateScoreFaithConfig input[name=score_ttt]').val(<?php echo e($config_score_ttt); ?>);
            });

            $('#updateInterestRateConfig').on('show.bs.modal', function () {
                $('#updateInterestRateConfig input[name=interest_rate]').val(<?php echo e($config_fund_loan_capital->interest_rate); ?>);
            });

            $('#myTab a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
    
            // store the currently selected tab in the hash value
            $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
                var id = $(e.target).attr("href").substr(1);
                window.location.hash = id;
            });
    
            // on load of the page: switch to the currently selected tab
            var hash = window.location.hash;
            $('#myTab a[href="' + hash + '"]').tab('show');
        });

        $(document).on('click','.update-interest-rate-config',function(){
            id = $(this).attr('data-id');
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {
                        id : id,
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/check-interest-rate-config")); ?>',
                data:data, 
                dataType: 'json',
                // beforeSend: function() {
                //     $("#pre_ajax_loading").show();
                // },
                // complete: function() {
                //     $("#pre_ajax_loading").hide();
                // },
                success: function (response) {
                    if(response.status == 404){
                        $("#updateInterestRateConfig input").attr("disabled", true);
                        $("#updateInterestRateConfig input[name=apply_to]").attr("disabled", false);
                    } else {
                        $("#updateInterestRateConfig input").attr("disabled", false);
                    }
                },
                error: function (error) {

                }
            });

            var date = $(this).closest('tr').find('td:eq(0)').text();
            date = date.split("đến");
            var apply_from = date[0].trim();
            var apply_to = date[1].trim();
            var apply_from = date[0].trim();
            apply_from = apply_from.replace("Từ ", "");

            var interest_rate = $(this).closest('tr').find('td:eq(1)').text();
            var preferential_interest_rate = $(this).closest('tr').find('td:eq(2)').text();
            var count_month_preferential = $(this).closest('tr').find('td:eq(3)').text();
            var deferred_interest = $(this).closest('tr').find('td:eq(4)').text();
            var start_month_pay = $(this).closest('tr').find('td:eq(5)').text();
            var month_time_max = $(this).closest('tr').find('td:eq(6)').text();
            var x_salary = $(this).closest('tr').find('td:eq(7)').text();
            var time_complete_file = $(this).closest('tr').find('td:eq(8)').text();
            var interest_file_late = $(this).closest('tr').find('td:eq(9)').text();
            var score_min = $(this).closest('tr').find('td:eq(10)').text();

            $('#updateInterestRateConfig input[name=interest_rate]').val(interest_rate);
            $('#updateInterestRateConfig input[name=preferential_interest_rate]').val(preferential_interest_rate);
            $('#updateInterestRateConfig input[name=count_month_preferential]').val(count_month_preferential);
            $('#updateInterestRateConfig input[name=deferred_interest]').val(deferred_interest);
            $('#updateInterestRateConfig input[name=start_month_pay]').val(start_month_pay);
            $('#updateInterestRateConfig input[name=month_time_max]').val(month_time_max);
            $('#updateInterestRateConfig input[name=x_salary]').val(x_salary);
            $('#updateInterestRateConfig input[name=time_complete_file]').val(time_complete_file);
            $('#updateInterestRateConfig input[name=interest_file_late]').val(interest_file_late);
            $('#updateInterestRateConfig input[name=score_min]').val(score_min);
            $('#updateInterestRateConfig input[name=apply_from]').val(apply_from);
            $('#updateInterestRateConfig input[name=apply_to]').val(apply_to);
        });

        $(document).on('click','.btn-edit.loan-capital',function(){
            var personnel_id = $(this).attr('data-personnel_id');
            var fullname = $(this).attr('data-fullname');
            var score = $(this).attr('data-score');
            var max_money = $(this).attr('data-max_money');
            var preferential_interest_rate = $(this).attr('data-preferential_interest_rate');
            var month_time = $(this).attr('data-month_time');

            $('#editLoanCapital input[name=personnel_id]').val(personnel_id);
            $('#editLoanCapital .fullname').html(fullname);

            if (score || max_money || preferential_interest_rate || month_time) {
                $('#editLoanCapital input[name=score]').val(score);
                $('#editLoanCapital input[name=money]').val(max_money.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
                // $('#editLoanCapital input[name=max_money]').val(max_money);
                $('#editLoanCapital input[name=preferential_interest_rate]').val(preferential_interest_rate);
                $('#editLoanCapital input[name=month_time]').val(month_time);
            } else {
                $('#editLoanCapital input[name=score]').val('');
                $('#editLoanCapital input[name=max_money]').val('');
                $('#editLoanCapital input[name=preferential_interest_rate]').val('');
                $('#editLoanCapital input[name=month_time]').val('');
            }
        });

        function updateFundLoanCapital(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var fund_money = $("#editFundLoanCapital input[name=fund_money]").val().replace(/,/g,'');
            if (fund_money == 0 || fund_money == null) {
                Swal.fire({
                    type: 'warning',
                    html: 'Xin vui lòng nhập số tiền',
                    allowOutsideClick: false
                })
                return;
            }
            var data = {
                        fund_money_real : $("#editFundLoanCapital input[name=fund_money]").val().replace(/,/g,''),
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/update-fund-loan-capital")); ?>',
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
        } 

        function updateLoanCapital(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var data = {
                        personnel_id : $('#editLoanCapital input[name=personnel_id]').val(),
                        score : $('#editLoanCapital input[name=score]').val().replace(/,/g,''),
                        max_money : $('#editLoanCapital input[name=max_money]').val().replace(/,/g,''),
                        preferential_interest_rate : $('#editLoanCapital input[name=preferential_interest_rate]').val().replace(/,/g,''),
                        month_time : $('#editLoanCapital input[name=month_time]').val().replace(/,/g,''),
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/update-loan-capital")); ?>',
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

        function insertInterestRateConfig(){
            var apply_from= $('#insertInterestRateConfig input[name=apply_from]').val();
            var apply_to= $('#insertInterestRateConfig input[name=apply_to]').val();

            if (apply_from == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng từ!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(apply_from)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng từ hợp lệ!",
                        allowOutsideClick: false
                    })

                    return;
                }
            }


            if (apply_to == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng đến!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(apply_to)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng đến hợp lệ!",
                        allowOutsideClick: false
                    })

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

            var data = {
                        month_time_max : $('#updateInterestRateConfig input[name=month_time_max]').val().replace(/,/g,''),
                        x_salary : $('#updateInterestRateConfig input[name=x_salary]').val().replace(/,/g,''),
                        time_complete_file : $('#updateInterestRateConfig input[name=time_complete_file]').val().replace(/,/g,''),
                        interest_file_late : $('#updateInterestRateConfig input[name=interest_file_late]').val().replace(/,/g,''),
                        score_min : $('#insertInterestRateConfig input[name=score_min]').val().replace(/,/g,''),
                        interest_rate : $('#insertInterestRateConfig input[name=interest_rate]').val().replace(/,/g,''),
                        preferential_interest_rate : $('#insertInterestRateConfig input[name=preferential_interest_rate]').val().replace(/,/g,''),
                        start_month_pay : $('#insertInterestRateConfig input[name=start_month_pay]').val().replace(/,/g,''),
                        count_month_preferential : $('#insertInterestRateConfig input[name=count_month_preferential]').val().replace(/,/g,''),
                        deferred_interest : $('#insertInterestRateConfig input[name=deferred_interest]').val().replace(/,/g,''),
                        apply_from : apply_from,
                        apply_to : apply_to,
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/insert-interest-rate-config")); ?>',
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

        function updateInterestRateConfig(){
            var apply_from= $('#updateInterestRateConfig input[name=apply_from]').val();
            var apply_to= $('#updateInterestRateConfig input[name=apply_to]').val();

            if (apply_from == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng từ!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(apply_from)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng từ hợp lệ!",
                        allowOutsideClick: false
                    })

                    return;
                }
            }


            if (apply_to == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng đến!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(apply_to)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập thời gian Áp dụng đến hợp lệ!",
                        allowOutsideClick: false
                    })

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

            var data = {
                        id : id,
                        month_time_max : $('#updateInterestRateConfig input[name=month_time_max]').val().replace(/,/g,''),
                        x_salary : $('#updateInterestRateConfig input[name=x_salary]').val().replace(/,/g,''),
                        time_complete_file : $('#updateInterestRateConfig input[name=time_complete_file]').val().replace(/,/g,''),
                        interest_file_late : $('#updateInterestRateConfig input[name=interest_file_late]').val().replace(/,/g,''),
                        score_min : $('#updateInterestRateConfig input[name=score_min]').val().replace(/,/g,''),
                        interest_rate : $('#updateInterestRateConfig input[name=interest_rate]').val().replace(/,/g,''),
                        preferential_interest_rate : $('#updateInterestRateConfig input[name=preferential_interest_rate]').val().replace(/,/g,''),
                        start_month_pay : $('#updateInterestRateConfig input[name=start_month_pay]').val().replace(/,/g,''),
                        count_month_preferential : $('#updateInterestRateConfig input[name=count_month_preferential]').val().replace(/,/g,''),
                        deferred_interest : $('#updateInterestRateConfig input[name=deferred_interest]').val().replace(/,/g,''),
                        apply_from : apply_from,
                        apply_to : apply_to,
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/update-interest-rate-config")); ?>',
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
        } 

        function deleteInterestRateConfig(id){
            Swal.fire({
                text: "Bạn có chắc chắn muốn xóa!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có',
                cancelButtonText: 'Không'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup(
                        {
                            headers:
                            {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        var data = {
                                    id : id,
                                };

                        $.ajax({
                            method: "POST",
                            url: '<?php echo e(url("toh_hrm/api/delete-interest-rate-config")); ?>',
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
                    }
            })
        }

        function updateScoreFaithConfig(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        score_tn : $('#updateScoreFaithConfig input[name=score_tn]').val().replace(/,/g,''),
                        score_tsp_tld : $('#updateScoreFaithConfig input[name=score_tsp_tld]').val().replace(/,/g,''),
                        score_tp_ptt : $('#updateScoreFaithConfig input[name=score_tp_ptt]').val().replace(/,/g,''),
                        score_ttt : $('#updateScoreFaithConfig input[name=score_ttt]').val().replace(/,/g,''),
                        // score_min : $('#updateScoreFaithConfig input[name=score_min]').val(),
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/update-score-faith-config")); ?>',
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

        function updateOtherConfig(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        remind_before_x_days : $('#updateOtherConfig input[name=remind_before_x_days]').val().replace(/,/g,''),
                        remind_after_x_days : $('#updateOtherConfig input[name=remind_after_x_days]').val().replace(/,/g,''),
                        email_tq : $('#updateOtherConfig input[name=email_tq]').val().trim(),
                    };

            $.ajax({
                method: "POST",
                url: '<?php echo e(url("toh_hrm/api/update-other-config")); ?>',
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

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>