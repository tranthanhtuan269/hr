

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
					Danh sách nhân viên đủ tiêu chuẩn tăng lương <?php echo e($param); ?>

				</h4>
				<div class="col-lg-12">
					<?php if(session('flash_message_succ') != ''): ?>
						 <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
					<?php endif; ?>
				</div>
                <form class="form-horizontal clearfix" method="get" action="">
                    <div class="form-group col-lg-6">
                        <label for="date" class="col-sm-4 control-label">Đợt xét :</label>
                        <div class="col-sm-8">
                            <select name="frequency" class="form-control select2 wrap">
                                <option value="2" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 2)?"selected":""; ?> ><?php echo e($time_after); ?></option>
                                <option value="1" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 1)?"selected":""; ?> ><?php echo e($time_before); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-lg-6">
                        <label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
                        <div class="col-sm-8">  
                           <select name="selectDepart" id="department" class="form-control select2 wrap">
                                <option value="0"> -- Đơn vị -- </option>
                                <?php echo $department; ?>

                            </select>
                            <script type="text/javascript">
                                var $select2 = $('.select2').select2({
                                    containerCssClass: "wrap"
                                })
                            </script>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 text-center">
                        <input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
                        <input type="submit" class="btn btn-sm btn-orange" name="sendemail" onclick="return confirm('Bạn có chắc chắn muốn gửi Email ?')" value="Gửi Email" >
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>
				<div class="table-responsive" >
				    <table class="table table-bordered table-striped">
				        <thead>
				            <tr>
				                <th class="text-center">STT</th>
				                <th class="text-center" width="20%">Họ và tên</th>
				                <th class="text-center">Ngày ký hợp đồng chính thức</th>
				                <th class="text-center">Hệ số lương hiện tại</th>
				                <th class="text-center">Ngày thay đổi lương gần nhất</th>
				                <th class="text-center">Số tháng từ ngày thay đổi lương gần nhất</th>
				                <th class="text-center">Ngày đủ tiêu chuẩn xét</th>
				                <th class="text-center" width="10%">Chu kỳ xét</th>
				                <th class="text-center" width="6%">Loại xét</th>
								<?php /* <th class="text-center" width="15%">Trạng thái</th> */ ?>
								<th class="text-center" width="15%"></th>
				            </tr>
				        </thead>
				        <tbody>
						    <?php if(!empty($data)): ?>
						    	<?php $tmp=1; ?>
						     	<?php foreach($data as $key=>$val): ?>
							     <tr>
							      	<td class="text-center"> <?php echo e($tmp); ?> </td> 
							      	<td style="text-align: left;">
							      		<a href="<?php echo e(route('getPersonnelEdit',['id'=>$val['personnel_id'] ])); ?>"><?php echo e(str_limit( $val['fullname'], $limit = 35, $end = '...')); ?></a>
								    </td>
								    <td><?php echo e(BatvHelper::formatDate($val["date_hdct"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
								    <td><?php echo e($val['hsl_ht']); ?></td>
								    <td>
										<?php if( $val["date_nlgn"] ): ?>
											<?php echo e(BatvHelper::formatDate($val["date_nlgn"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?>

										<?php endif; ?>
								    </td>
								    <td><?php echo e($val["number_month_nlgn"]); ?></td>
								    <td><?php echo e(BatvHelper::formatDate($val["date_dxnl"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
								    <td>
								    	<?php echo e($val["salary_frequency"]); ?> năm
								    </td>
								    <td>
                                        <?php if( $val['type'] == 1 ): ?>
                                            <span>Đ.kỳ</span>
                                        <?php else: ?>
                                            <span style="color: green;">Đ.xuất</span>
                                        <?php endif; ?>								    
								    </td>
								    <?php /* <td>
								    	<?php if( $val["status"] == 1 ): ?>
											<span class="daduyet" >Đã gửi email</span>
								    	<?php else: ?>
											<span class="chuaguimail" >Chưa gửi email</span>
								    	<?php endif; ?>
									</td> */ ?>
									<td>
										
								    	<?php if( $val["status"] == 1 ): ?>
											<span class="daduyet" >Đã gửi email</span>
								    	<?php else: ?>
											<button type="button" class="btn btn-primary btn-xs send-email-only" data-personel-id="<?php echo e($val['personnel_id']); ?>">Gửi email</button>
											<button type="button" class="btn btn-danger btn-xs delete-only" data-personel-id="<?php echo e($val['personnel_id']); ?>"> Xóa </button>
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
<script>
	<?php if(!isset($_GET['frequency'])): ?>
		$("#department").select2("val", '<?php echo e($ids[0]); ?>');
	<?php endif; ?>


	$('.send-email-only').click(function(){
		var personnel_id = $(this).attr('data-personel-id')
		var data    = {
			personnel_id           : personnel_id,
		};

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: "POST",
			url: "<?php echo e(route('send-email-only-salary')); ?>",
			data: data,
			dataType:'json',
			beforeSend: function(r, a){
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
				}
			},
			error: function (data) {
			}
		});
	});

    $('.delete-only').click(function(){
		Swal.fire({
			title: 'Bạn có chắc chắn muốn xóa?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				var personnel_id = $(this).attr('data-personel-id')
				var data    = {
					personnel_id           : personnel_id,
				};

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					type: "POST",
					url: "<?php echo e(route('delete-only-salary')); ?>",
					data: data,
					dataType:'json',
					beforeSend: function(r, a){
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
						}
					},
					error: function (data) {
					}
				});
			}
		})
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>