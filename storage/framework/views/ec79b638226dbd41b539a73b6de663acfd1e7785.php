

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $turns = ( date('m') >= 1 && date('m') <= 6 )? 1 : 2;
    $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
    
    if( isset( $_GET['frequency'] ) ){
        if( date('m') >= 1 && date('m') <= 6 ){
        	$turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y') : "đợt 2(tháng 12) năm ".date('Y', strtotime(date('Y').' -1 year'));
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
?>
<div class="row box_salary">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<div class="col-lg-10">

			<h4 class="title-fuction">
				Đề xuất tăng lương đột xuất <?php echo e($param); ?>

			</h4>
			<div class="col-lg-12">
				<?php if(session('flash_message_succ') != ''): ?>
					 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
				<?php endif; ?>
			</div>
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
            <form  class="form-horizontal clearfix" method="POST">
				<div class="form-group col-lg-12">
					<label class="col-sm-offset-1 col-sm-3 control-label">Danh sách nhân viên</label>
					<div class="col-sm-5">	
                        <?php if(!empty($listPersonnel)): ?>
                            <select id="my-select-2" name="personnel_id[]" multiple="multiple">
                                <?php foreach($listPersonnel as $val): ?>
                                     <option value="<?php echo e($val->id); ?>"><?php echo e($val->fullname); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
						<script type="text/javascript">
							$(function() {
							    $('#my-select-2').searchableOptionList({
							        showSelectAll: true,
							        maxHeight: '250px',
							    });
							});    
						</script>
				    </div>
					<div class="col-sm-2">
						<input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
					</div>
				</div>
	            <?php echo e(csrf_field()); ?>

			</form>

			<h4 class="title-fuction">Danh sách nhân viên được tăng lương đột xuất năm <?php echo e($param); ?></h4>
			<div class="table-responsive" >
			    <table class="table table-bordered table-striped">
			        <thead>
			            <tr>
			                <th class="text-center">STT</th>
			                <th class="text-center" width="25%">Họ và tên</th>
			                <th class="text-center" width="15%">Hệ số lương hiện tại</th>
			            <!--     <th class="text-center" width="15%">Số tháng từ ngày thay đổi lương gần nhất</th> -->
			                <th class="text-center" width="20%">Ngày đủ tiêu chuẩn xét</th>
			                <th class="text-center">Chu kỳ xét</th>
			                <th class="text-center"></th>
			            </tr>
			        </thead>
			        <tbody>
						    <?php if(!empty($data)): ?>
						    	<?php $tmp=1; ?>
						     	<?php foreach($data as $key=>$val): ?>
							     <tr>
							      	<td class="text-center"> <?php echo e($tmp); ?> </td> 
							      	<td style="text-align: left; padding-left: 5px;">
							      		<a href="<?php echo e(route('getPersonnelEdit',['id'=>$val['personnel_id'] ])); ?>"><?php echo e(str_limit( $val['fullname'], $limit = 35, $end = '...')); ?></a>
								    </td>
								    <td><?php echo e($val['hsl_ht']); ?></td>
		<!-- 						    <td><?php echo e($val["number_month_nlgn"]); ?></td> -->
								    <td><?php echo e(BatvHelper::formatDate($val["date_dxnl"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
								    <td>
								    	<?php echo e($val["salary_frequency"]); ?> năm
								    </td>
								    <td>
				                        <?php if(in_array('luongthuong-xoanhanvientangluongdotxuat',$arr_route) && in_array($val['personnel_id'],$arrPermission)): ?>
								       		<a class="btn-delete" href="<?php echo e(route('deleteSalaryPropose',['id'=>$val['personnel_id']])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
								       		<img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
				                        <?php endif; ?>
								    </td>
							    </tr>
							    <?php $tmp++; ?>
							    <?php endforeach; ?>
						    <?php endif; ?>
			        </tbody>
			    </table>
			</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>