
<?php $__env->startSection('title', 'Đánh giá'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-Emonth">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.danhgia.menuleft.danhgianam', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá nâng lương <i class="fa fa-angle-double-right" aria-hidden="true"></i> Tự đánh giá <i class="fa fa-angle-double-right" aria-hidden="true"></i> Chỉnh sửa đánh giá nâng lương</h4>
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
            <div class="text-center">
                <form action="" method="post">
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
                                    <?php $total = 0;$tmp = 1; ?>
                                    <?php foreach($data as $val): ?>
                                        <?php $total += ( $val->point * $val->criteria_weight*BatvHelper::pointCriteriaGroup($val->criteria_group_id) ); ?>
                                        <tr>
                                            <td><?php echo e($tmp); ?></td>
                                            <td class="text-left"><?php echo e($val->criteria_content); ?></td>
                                            <td>
                                                <select name="point[<?php echo e($val->criteria_id); ?>]">
                                                    <option value="1" <?php echo ( $val->point == 1 )?"selected":"";  ?> >1</option>
                                                    <option value="2" <?php echo ( $val->point == 2 )?"selected":"";  ?> >2</option>
                                                    <option value="3" <?php echo ( $val->point == 3 )?"selected":"";  ?> >3</option>
                                                    <option value="4" <?php echo ( $val->point == 4 )?"selected":"";  ?> >4</option>
                                                    <option value="5" <?php echo ( $val->point == 5 )?"selected":"";  ?> >5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php $tmp++; ?>
                                    <?php endforeach; ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><i>Tổng điểm</i> : <b><?php echo e($total); ?></b></td>
                                        </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
                        <button type="reset" class="btn btn-sm btn-grey">Nhập lại</button>
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>

            </div>
        </div>
    </div>
</div>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>