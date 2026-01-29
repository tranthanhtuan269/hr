
<?php $__env->startSection('title', 'Đánh giá'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-Emonth">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.danhgia.menuleft.danhgianam', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá nâng lương <i class="fa fa-angle-double-right" aria-hidden="true"></i> Tự đánh giá</h4>
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

        <?php if( BatvHelper::listPesonnelAssessment( Auth::user()->id ) == 1 ): ?> 
            <div class="detail">
                <div class="text-center">
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
                                        <?php $tmp = 1; ?>
                                        <?php foreach($data as $val): ?>
                                            <tr>
                                                <td><?php echo e($tmp); ?></td>
                                                <td class="text-left"><?php echo e($val->criteria_content); ?></td>
                                                <td>
                                                    <select name="point[<?php echo e($val->id); ?>]">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3" selected>3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php $tmp++; ?>
                                        <?php endforeach; ?>
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
        <?php else: ?>
            <div class="alert alert-danger" role="alert"> Bạn chưa được xét tăng lương trong đợt này hoặc đã quá thời gian đánh giá cho phép.</div>
        <?php endif; ?>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myForm").submit(function () {
            $("button[type=submit]").attr("disabled", true);
            return true;
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>