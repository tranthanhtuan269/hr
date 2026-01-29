
<?php $__env->startSection('title', 'Chi phí'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-function">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.chiphi.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                <?php if(session('flash_message_err') != ''): ?>
                <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
                <?php endif; ?>
                <?php if(session('flash_message_succ') != ''): ?>
                <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
                <?php endif; ?>
            </div>
        
            <div class="col-lg-12">
                <h4 class="title-fuction">Danh sách ký quỹ
                    <?php if(in_array('chiphi-themkyquy',$arr_route)): ?>
                    <a href="<?php echo e(route('getSignFundsAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
                    <?php endif; ?>
                </h4>
                <p><b>Tổng tiền</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px"><?php echo e(BatvHelper::formatPriceSpecial($total_price)); ?></span></p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>STT</th>
                                <th>Người cầm quỹ</th>
                                <th>Ngày nhận</th>
                                <th>Số lượng</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            <?php if(!empty($data)): ?>
                                <?php 
                                  if( !isset($_GET['page']) || $_GET['page']==1 ){
                                    $i  = 1;
                                  }else{
                                    $i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                                  }
                                ?>
                                <?php foreach($data as $val): ?>
                                <tr>
                                    <td><?php echo e($i); ?></td>
                                    <td> <?php echo e(BatvHelper::getInfoUser($val['personnel_id'])); ?> </td>
                                    <td> <?php echo e(BatvHelper::formatDate($val['received_date'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> </td>
                                    <td> <?php echo e(BatvHelper::formatPriceSpecial($val['value'])); ?> </td>
                                    <td>
                                        <?php if(in_array('chiphi-suakyquy',$arr_route)): ?>
                                        <a class="btn-edit" href="<?php echo e(route('getSignFundsEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                        <?php endif; ?>
                                        <?php if(in_array('chiphi-xoakyquy',$arr_route)): ?>
                                        <a class="btn-delete" href="<?php echo e(route('signFundsDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                        <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12 text-right">
                <?php echo e($data->appends(Request::all())->links()); ?> 
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>