<div class="col-lg-12">
	<ul class="list clearfix">
        <?php if(in_array('luongthuong-cauhinhthamso',$arr_route)): ?>
			<li>
				<a href="<?php echo e(route('getParametersConfig')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tham số</a>
			</li>
        <?php endif; ?>
        <?php if(in_array('luongthuong-cauhinhnhomnguoi',$arr_route)): ?>
			<li>
				<a href="<?php echo e(route('getGroupPersonalConfig')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình nhóm người</a>
			</li>
        <?php endif; ?>
        <?php if(in_array('luongthuong-cauhinhcongthuc',$arr_route)): ?>
			<li>
				<a href="<?php echo e(route('getRecipeConfig')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình công thức tính lương</a>
			</li>
        <?php endif; ?>
        <?php if(in_array('luongthuong-cauhinhngaynghile',$arr_route)): ?>
			<li>
				<a href="<?php echo e(route('getHolidaysConfig')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình ngày nghỉ lễ</a>
			</li>
        <?php endif; ?>
<!--         <?php if(in_array('luongthuong-cauhinhngaynghiphep',$arr_route)): ?>
			<li>
				<a href="<?php echo e(route('getLeaveConfig')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình ngày nghỉ phép</a>
			</li>
		<?php endif; ?> -->
		
        <?php if(in_array('luongthuong-cauhinhthamso',$arr_route)): ?>
			<li>
				<a data-toggle="modal" data-target="#myModalBH" href="#"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tăng số tiền bảo hiểm</a>
			</li>
			<div id="myModalBH" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Cấu hình tăng số tiền bảo hiểm</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-3">Nhập số tiền tăng: </div>
								<div class="col-sm-9">
									<input type="number" name="money" class="form-control" value="0">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="updatedIncreaseInsurrance()">Cập nhật</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				function updatedIncreaseInsurrance() {
					var data = {
							money:$('input[name=money]').val(),
						};
					$.ajax({
						url: "<?php echo e(route('updated-increase-insurrance')); ?>",
						data: data,
						dataType: 'json',
						beforeSend: function() {
							$(".ajax_waiting").addClass("loading");
						},
						complete: function() {
							$(".ajax_waiting").removeClass("loading");
						},
				        success: function (response) {
							if (response.status == 200) {
								Swal.fire({
									type: 'success',
									html: response.message,
								}).then(function(result){
									if(result.value){
										location.reload();
									}
								})
							}
				        },
				        error: function (data) {
				            console.log('Error:', data);
				        }
					});
				}
			</script>
        <?php endif; ?>
	</ul>
</div>