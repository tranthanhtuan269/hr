
<?php $__env->startSection('title', 'Đánh giá'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-Emonth">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.danhgia.menuleft.danhgianam', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá nâng lương <i class="fa fa-angle-double-right" aria-hidden="true"></i> Đánh giá nhân viên trực thuộc <i class="fa fa-angle-double-right" aria-hidden="true"></i> Chỉnh sửa đánh giá nhân viên trực thuộc</h4>
        <?php if(session('flash_message_err') != ''): ?>
            <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
        <?php endif; ?>
        <?php if(session('flash_message_succ') != ''): ?>
            <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
        <?php endif; ?>
        
        <?php
            if ( isset($error_special) && $error_special != ''){
        ?>
            <div class="alert alert-danger" role="alert"> <?php echo $error_special; ?></div>
        <?php
            }
        ?>
        <div class="detail">
            <div class="">
                <?php if(!empty($tdgn)): ?>
                    <div class="text-center" style="margin: 15px 0px;">
                        <?php if(!empty($data[0]->fullname)){ echo "Nhân viên <b>".$data[0]->fullname."</b> tự đánh giá"; } ?>
                    </div>
                    <div class="table-responsive">
                        <table class="evaluation table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Tiêu chí</th>
                                    <th class="text-center">Điểm đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php $total_tdgn = 0; $tmp = 1;  ?>
                                    <?php foreach($tdgn as $val): ?>
                                        <?php  
                                            $total_tdgn += ( $val->point * $val->criteria_weight*BatvHelper::pointCriteriaGroup($val->criteria_group_id) );
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo e($tmp); ?></td>
                                            <td class="text-left"><?php echo e($val->criteria_content); ?></td>
                                            <td class="text-center"><?php echo e($val->point); ?></td>
                                        </tr>
                                        <?php $tmp++;  ?>
                                    <?php endforeach; ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><i>Tổng điểm</i> : <b><?php echo e($total_tdgn); ?></b></td>
                                        </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-center" style="margin: 15px 0px;">
                    Đánh giá : <b><?php echo e($data[0]->fullname); ?></b>
                </div>
                <form action="" method="post" id="myForm">
                    <div class="table-responsive">
                        <table class="evaluation table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Tiêu chí</th>
                                    <th class="text-center">Điểm đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)): ?>
                                    <?php $total_qldgn = 0; $param = 1; ?>
                                    <?php foreach($data as $val): ?>
                                        <?php $total_qldgn += ( $val->point * $val->criteria_weight*BatvHelper::pointCriteriaGroup($val->criteria_group_id) ) ?>
                                        
                                        <tr>
                                            <td class="text-center"><?php echo e($param); ?></td>
                                            <td class="text-left"><?php echo e($val->criteria_content); ?></td>
                                            <td class="text-center">
                                                <select name="point[<?php echo e($val->criteria_id); ?>]">
                                                    <option value="1" <?php echo ( $val->point == 1 )?"selected":"";  ?> >1</option>
                                                    <option value="2" <?php echo ( $val->point == 2 )?"selected":"";  ?> >2</option>
                                                    <option value="3" <?php echo ( $val->point == 3 )?"selected":"";  ?> >3</option>
                                                    <option value="4" <?php echo ( $val->point == 4 )?"selected":"";  ?> >4</option>
                                                    <option value="5" <?php echo ( $val->point == 5 )?"selected":"";  ?> >5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php $param++; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><i>Tổng điểm</i> : <b><?php echo e($total_qldgn); ?></b></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <p><b>Nhận xét của quản lý gửi cho nhân viên</b>:</p> <textarea class="form-control" rows="8" onkeydown="expandtext(this);" name="comment_manager" onkeyup="textAreaAdjust(this)" ><?php echo e($data[0]->comment_manager); ?></textarea>
                        </div>
                        <div class="form-group">
                            <p><b>Nhận xét về nhân viên gửi BGĐ</b>:</p> <textarea class="form-control" rows="8" onkeydown="expandtext(this);" name="comment" onkeyup="textAreaAdjust(this)" ><?php echo e($data[0]->comment); ?></textarea>
                        </div>
                        <div class="form-group">
                            <?php if( $checkSpecial < 1 ): ?>
                                <p>Hệ số lương trước: <b><?php echo e(BatvHelper::getRatioByTime($personnel_id,$time)); ?></b> (<span style="color: red;font-style: italic;font-weight: bold;"><?php echo e(BatvHelper::formatPrice( BatvHelper::ltt('',$personnel_id,$time,$type=1,'',$option=1,$convert_ratio='')  )); ?></span>)</p> 
                                <input type="hidden" name="ratio_current" value="<?php echo e(BatvHelper::getRatioByTime($personnel_id,$time)); ?>">
                            <?php else: ?>
                                <p>Hệ số lương trước: <b><?php echo e(BatvHelper::getRatioSpecial($personnel_id,$time)); ?></b> (<span style="color: red;font-style: italic;font-weight: bold;"><?php echo e(BatvHelper::formatPrice( BatvHelper::ltt('',$personnel_id,$time,$type=1,'',$option=1,BatvHelper::getRatioSpecial($personnel_id,$time))  )); ?></span>)</p> 
                                <input type="hidden" name="ratio_current" value="<?php echo e(BatvHelper::getRatioSpecial($personnel_id,$time)); ?>">
                            <?php endif; ?>  
                        </div>
                        <div class="form-group">
                            <span style="padding-right: 10px;">Hệ số lương đề xuất: </span>
                            <?php if( $checkSpecial >= 1 ): ?>
                                <b><?php echo e($data[0]->ratio_propose); ?></b>
                                (<span style="color: red;font-style: italic;font-weight: bold;"><?php echo e(BatvHelper::formatPrice( BatvHelper::ltt('',$personnel_id,$time,$type=1,'',$option=1,$data[0]->ratio_propose )  )); ?></span>)
                                <input type="hidden" name="ratio_propose" value="<?php echo e($data[0]->ratio_propose); ?>" style="font-weight: bold;">
                            <?php else: ?>
                                <input type="number" name="ratio_propose" required step="0.01" value="<?php echo e($data[0]->ratio_propose); ?>" style="font-weight: bold;">
                                <span style="color: red;font-style: italic;font-weight: bold;" id="salary_convert_by_ratio">
                                    <input type="hidden" name="salary_convert_by_ratio" value="<?php echo e($data[0]->ratio_propose); ?>">
                                </span>
                            <?php endif; ?>  
                        </div>
                        <div class="form-group">
                            <span style="padding-right: 10px;">Mức tăng hệ số: </span>
                            <b id="increase"></b>  
                        </div>
                         <div class="form-group">
                            <span style="padding-right: 10px;">Mức phụ cấp hiện tại: </span>
                            <b><?php echo e(BatvHelper::formatPrice($data[0]->management_allowance_old)); ?></b>  
                        </div>
                         <div class="form-group"  id="management_allowance">
           
                            <input type="checkbox" <?php if($data[0]->management_allowance > 0): ?> checked <?php endif; ?> value="1" name="change_management_allowance" style="top: 2px;position: relative;"> Mức phụ cấp đề xuất mới
                            <input type="text" name="management_allowance" <?php if($data[0]->management_allowance > 0): ?> value="<?php echo e(BatvHelper::formatPrice($data[0]->management_allowance)); ?>" <?php else: ?> class="hidden" <?php endif; ?>  data-type="currency">            
                        </div>
                    </div>
                    <div class="text-center">
                        
                        <?php if( Auth::user()->id != 1 ): ?>
                            <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
                            <button type="submit" class="btn btn-sm btn-orange" name="send_email">Gửi Email</button>
                        <?php endif; ?>
                        <?php if( Auth::user()->id == 1 ): ?>
                            <?php if( $checkSpecial >= 1 ): ?>
<!--                                 <button type="submit" class="btn btn-sm btn-orange" name="cancel">Hủy</button> -->
                            <?php else: ?>   
                                <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
<!--                                 <button type="submit" class="btn btn-sm btn-orange" name="done">Phê duyệt</button> -->
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if( $checkSpecial >= 1 ): ?>
                            <span class="daduyet">Đã duyệt</span>
                        <?php endif; ?>  
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        $("#myForm").submit(function () {
            $("button[type=submit]").attr("disabled", true);
            return true;
        });
        
        $("#management_allowance input[type=checkbox]").click(function(){
          if ($(this).is(':checked')) {
            $('#management_allowance input[type=text]').removeClass('hidden');
          } else {
            $('#management_allowance input[type=text]').addClass('hidden');
          }
        });

        var param = $('input[name="salary_convert_by_ratio"]').val();
        var personnel_id = <?php echo e($personnel_id); ?>;
        var ratio_propose = parseFloat($('input[name="ratio_propose"]').val());
        var ratio_current = parseFloat( $('input[name="ratio_current"]').val() );

        if (ratio_propose-ratio_current <= 0) {
            $("#increase").html(0)
        } else {
            $("#increase").html((ratio_propose-ratio_current).toFixed(2));
        }

        $.ajax({
            type: "GET",
            url: "<?php echo e(route('getSalaryDefaultAjax')); ?>",
            //contentType: "application/json; charset=utf-8",
            data:{'param' : param,'personnel_id':personnel_id},
            // dataType: "json",
            success: function(data){
                $("#salary_convert_by_ratio").html(data);
            }
        });

       $('input[name="ratio_propose"]').keyup(function(){
            var param = $(this).val();
            var personnel_id = <?php echo e($personnel_id); ?>;
            var ratio_propose = parseFloat($('input[name="ratio_propose"]').val());
            var ratio_current = parseFloat( $('input[name="ratio_current"]').val() );
            $("#increase").html( (ratio_propose-ratio_current).toFixed(2) );
            $.ajax({
                type: "GET",
                url: "<?php echo e(route('getSalaryDefaultAjax')); ?>",
                //contentType: "application/json; charset=utf-8",
                data:{'param' : param,'personnel_id':personnel_id},
                // dataType: "json",
                success: function(data){
                    $("#salary_convert_by_ratio").html(data);
                }
            });

       });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>