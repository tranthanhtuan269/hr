
<?php $__env->startSection('title', 'Chi phí'); ?>
<?php $__env->startSection('content'); ?>
<?php
    if( !empty(  $_GET['valid_from'] ) && !empty(  $_GET['valid_to']  ) ){
        $valid_from = BatvHelper::formatDate($_GET['valid_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        $valid_to = BatvHelper::formatDate($_GET['valid_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
    }else{  
        $valid_from = date('Y')."-".date('m')."-"."01";

        $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        $valid_to = date('Y')."-".date('m')."-".$numberDay;
    }
?>
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
				<h4 class="title-fuction">
					Cài đặt quỹ phúc lợi
				</h4>
				<form  class="form-horizontal" method="POST">
					<div class="form-group">
						<label class="col-sm-4 control-label">Nhập tổng số tiền quỹ phúc lợi còn lại <span class="required">*</span></label>
						<div class="col-sm-5">	
		                    <input type="text" onkeyup="format_curency( this.value );" id="numFormatResult" class="form-control" value="<?php echo e(BatvHelper::formatPriceSpecial($funds_id_default['value'])); ?>"  required>
		                    <input type="hidden" name="value" id="result" value="<?php echo e($funds_id_default['value']); ?>">
					    </div>
					</div>
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Thời gian hiệu lực <span class="required">*</span></label>
		                <div class="col-sm-5">
		                    <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="valid_date" value="<?php echo e(BatvHelper::formatDate($funds_id_default['valid_date'],'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?>">
		                </div>
		            </div>
					<div class="text-center">
						<input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
					</div>
		            <?php echo e(csrf_field()); ?>

				</form>

                <h4 class="title-fuction">Danh sách chi tiêu quỹ phúc lợi từ <?php echo e(BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> đến <?php echo e(BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?>

                    <?php if(in_array('chiphi-themchitieuquyphucloi',$arr_route)): ?>
                    	<a href="<?php echo e(route('getWelfareFundsAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
                    <?php endif; ?>
                </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p><b>Tổng số tiền đã chi</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px"><?php echo e(BatvHelper::formatPriceSpecial($infoSpendMoneyWelfareFundsbyMonth)); ?></span></p>
                        <p><b>Tổng tiền còn lại</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px"><?php echo e(BatvHelper::formatPriceSpecial($funds_id_default['value'] + $infoTotalPriceWelfareFunds - $infoSpendMoneyWelfareFunds)); ?></span></p>
                    </div>
                    <div class="col-sm-6">
                        <form action="" method="get">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Từ tháng</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="datepicker form-control" name="valid_from" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo e(BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Đến tháng</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="datepicker form-control" name="valid_to" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="<?php echo e(BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <div class="row">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-sm btn-orange" id="autoClick">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>STT</th>
                                <th>Tiêu đề</th>
                                <th>Giá trị</th>
                                <th>Mô tả</th>
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
                                    <td class="text-center"><?php echo e($i); ?></td>
                                    <td> <?php echo e($val['title']); ?> </td>
                                    <td> <?php echo e(BatvHelper::formatPriceSpecial($val['value'])); ?>  </td>
                                    <td><?php echo e($val['description']); ?></td>
                                    <td>
                                        <?php if(in_array('chiphi-suachitieuquyphucloi',$arr_route)): ?>
                                        <a class="btn-edit" href="<?php echo e(route('getWelfareFundsEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                        <?php endif; ?>
                                        <?php if(in_array('chiphi-xoachitieuquyphucloi',$arr_route)): ?>
                                        <a class="btn-delete" href="<?php echo e(route('welfareFundsDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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