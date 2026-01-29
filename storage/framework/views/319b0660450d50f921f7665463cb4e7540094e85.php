
<?php $__env->startSection('title', 'Đánh giá'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $turns = ( date('m') >= 1 && date('m') <= 6 )? 1 : 2;
    $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
    $year = date('Y');
   

    if( isset( $_GET['frequency'] ) ){
        if( date('m') >= 1 && date('m') <= 6 ){
            $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y') : "đợt 2(tháng 12) năm ".date('Y', strtotime(date('Y').' -1 year'));
            $year = date('Y', strtotime(date('Y').' -1 year') );
        }else{
            $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
        }
    }

    if( date('m') >= 1 && date('m')<=6 ){
        $time_before = 'Đợt T12/'.date('Y', strtotime(date('Y').' -1 year'));
        $time_after = 'Đợt T6/'.date('Y');
    }else{
        $time_before = 'Đợt T6/'.date('Y');
        $time_after = 'Đợt T12/'.date('Y');
    }

    $time_calu = ( $turns == 1 ) ? $year.'-06' : $year.'-12';
?>
<div class="row content-Emonth">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.danhgia.menuleft.danhgianam', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá nâng lương <i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách nhân viên được đánh giá nâng lương <?php echo e($param); ?></h4>
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
        <form  class="form-horizontal clearfix" method="GET">
            <div class="form-group col-lg-12">
                <label for="date" class="col-sm-offset-1 col-sm-3 control-label">Đợt xét :</label>
                <div class="col-sm-5">
                    <select name="frequency" class="form-control select2 wrap">
                        <option value="2" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 2)?"selected":""; ?> ><?php echo e($time_after); ?></option>
                        <option value="1" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 1)?"selected":""; ?> ><?php echo e($time_before); ?></option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
                </div>
            </div>
            <?php echo e(csrf_field()); ?>

        </form>
        <div class="detail">
                <form action="" method="post">
                    <div class="table-responsive">
                        <table class="evaluation table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)): ?>
                                    <?php foreach($data as $val): ?>
                                        <tr>
                                            <td><?php echo e($val->fullname); ?></td>
                                            <td><a href="<?php echo e(route('getEvaluationYearbyManager',[$val->id,$year,$turns ])); ?>" target="_blank">Đánh giá</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>
                <?php if(isset($listExpires) && count($listExpires) > 0): ?>
                    <form>
                        <div class="text-center">
                            <div class="table-responsive">
                                <table class="evaluation table table-bordered selfEvaluation">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Danh sách nhân viên quá hạn đánh giá</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                            <?php foreach($listExpires as $val): ?>
                                                <tr>
                                                    <td><?php echo e($val->fullname); ?></td>
                                                    <td><button type="button" class="btn btn-sm btn-orange extend" data-personnel_id="<?php echo e($val->id); ?>">Gia hạn đánh giá</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                 <script type="text/javascript">
                    $(document).on('click','.extend',function(){
                        var personnel_id = $(this).attr('data-personnel_id');
                        var link = "<?php echo route('updateExtendAjax'); ?>";
                        var data = {
                                personnel_id:personnel_id,
                                turns : '<?php echo e($turns); ?>'
                            };
                        $.ajax({
                            url: link,
                            data: data,
                            success: function (response) {
                                var obj = $.parseJSON(response);
                                Swal.fire({
                                        type: 'success',
                                        html: obj.Message,
                                }).then((result) => {

                                })
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                        $(".ajax_response div").remove();
                    });
                 </script>
                 <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>