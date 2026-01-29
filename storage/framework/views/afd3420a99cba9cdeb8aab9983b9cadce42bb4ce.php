
<?php $__env->startSection('title', 'Đánh giá'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $time = ( date('m') >= 1 && date('m') <= 6 )? date('Y').'-06' : $time = date('Y').'-12';
    $turns = ( date('m') >= 1 && date('m') <= 6 )? 1 : 2;
    
    if( isset( $_GET['frequency'] ) ){
        if( date('m') >= 1 && date('m') <= 6 ){
            $time = (  $_GET['frequency'] == 1 ) ? date('Y', strtotime(date('Y').' -1 year')).'-12' : date('Y').'-06';
            $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
        }else{
            $time = (  $_GET['frequency'] == 1 ) ? date('Y').'-06' : date('Y').'-12';
            $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
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
<div class="row content-Emonth">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.danhgia.menuleft.danhgianam', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá nâng lương <i class="fa fa-angle-double-right" aria-hidden="true"></i> Đánh giá nhân viên trực thuộc</h4>
        <?php if(session('flash_message_err') != ''): ?>
            <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
        <?php endif; ?>
        <?php if(session('flash_message_succ') != ''): ?>
            <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
        <?php endif; ?>
        
        <?php if( isset($error_special) && $error_special != ''): ?>
            <div class="alert alert-danger" role="alert"> <?php echo $error_special; ?></div>
        <?php else: ?>
            <?php if(!empty($param)): ?>
                <p><a href="<?php echo e(route('getEvaluationYearbyManagerEdit',['id'=>$id ])); ?>" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa</a></p>
            <?php endif; ?>
            <div class="detail">
                <div class="">
                    <form action="" method="post" id="myForm">
                    <?php if(!empty($tdgn)): ?>
                        <div class="text-center" style="margin: 15px 0px;">
                            <?php if(!empty($infoUser)){ echo "Nhân viên <b>".$infoUser->fullname."</b> tự đánh giá"; } ?>
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
                                    <?php $total_tdgn = 0; $tmp = 1; ?>
                                    <?php foreach($tdgn as $val): ?>
                                        <?php $total_tdgn += ( $val->point * $val->criteria_weight*BatvHelper::pointCriteriaGroup($val->criteria_group_id) ) ?>
                                        <tr>
                                            <td class="text-center"><?php echo e($tmp); ?></td>
                                            <td class="text-left"><?php echo e($val->criteria_content); ?></td>
                                            <td class="text-center"><?php echo e($val->point); ?></td>
                                        </tr>
                                        <?php  $tmp++; ?>
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
                            <?php if(!empty($infoUser)){ echo "Đánh giá : <b>".$infoUser->fullname."</b>"; } ?>
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
                                    <?php if(!empty($data)): ?>
                                        <?php  $param = 1; ?>
                                        <?php foreach($data as $val): ?>
                                            <tr>
                                                <td class="text-center"><?php echo e($param); ?></td>
                                                <td  class="text-left"><?php echo e($val->criteria_content); ?></td>
                                                <td class="text-center">
                                                    <select name="point[<?php echo e($val->id); ?>]">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3" selected>3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php  $param++; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <p><b>Nhận xét của quản lý gửi cho nhân viên</b>:</p> <textarea class="form-control" rows="8" onkeydown="expandtext(this);" name="comment_manager" onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea>
                        </div>
                        <div class="form-group">
                            <p><b>Nhận xét về nhân viên gửi BGĐ</b>:</p> <textarea class="form-control" rows="8" onkeydown="expandtext(this);" name="comment" onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea>
                        </div>
                        <div class="form-group">
                            <p>Hệ số lương hiện tại: <b><?php echo e(BatvHelper::getRatioByTime($id,$time)); ?></b> (<span style="color: red;font-style: italic;font-weight: bold;"><?php echo e(BatvHelper::formatPrice( BatvHelper::ltt('',$id,$time,$type=1,'',$option=1,$convert_ratio='')  )); ?></span>)</p> 
                        </div>
                        <div class="form-group">
                            <span style="padding-right: 10px;">Hệ số lương đề xuất: </span>
                            <input type="number" name="ratio_propose" required step="0.01">
                            <span style="color: red;font-style: italic;font-weight: bold;" id="salary_convert_by_ratio"></span>
                        </div>
                        <div class="form-group">
                            <span style="padding-right: 10px;">Mức phụ cấp hiện tại: </span>
                            <b><?php echo e(BatvHelper::formatPrice($management_allowance_old)); ?></b> 
                            <input type="hidden" name="management_allowance_old" value="<?php echo e($management_allowance_old); ?>"> 
                        </div>
                         <div class="form-group"  id="management_allowance">
           
                            <input type="checkbox" value="1" name="change_management_allowance" style="top: 2px;position: relative;"> Mức phụ cấp đề xuất mới
                            <input type="text" name="management_allowance" class="hidden" data-type="currency">            
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-orange" name="save" >Cập nhật</button>
                        </div>
                        <?php echo e(csrf_field()); ?>

                    </form>

                </div>
            </div>
        <?php endif; ?>
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
        
       $('input[name="ratio_propose"]').keyup(function(){
             var param = $(this).val();
             var personnel_id = <?php echo e($id); ?>;
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