

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>
<style>
.title-info-taikhoan{
     padding-top: 12px;
}
.title-info-hoso{
  
}
.title-info-chamcong{
     padding-top: 12px;
}
.title-info-luongthuong{
     padding-top: 10px;
}
.title-info-danhgia{
     padding-top: 10px;
}
.title-info-quantri{
     padding-top: 10px;
}
</style>

<div class="row list-items">
			<div class="col-lg-12">
				<div class="row">
					<?php if(!empty($listHilight)): ?>
						<div class="col-sm-6">
				            <h4><a href="<?php echo e(route('getNewsList')); ?>">Tin nổi bật</a></h4>
				            <div class="panel-group" id="accordion">
			                
								<?php $tmp=1; ?>
			                   <?php foreach($listHilight as $key=>$val): ?>
					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                        	<?php if(in_array('danhgia-list',$arr_route)): ?><a href="<?php echo e(route('getNewsEdit',['id'=>$val->id])); ?>" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a><?php endif; ?>
					                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo e($tmp); ?>"><?php echo e($val->title); ?></a>
					                        </h4>
					                    </div>
					                    <div id="collapse<?php echo e($tmp); ?>" class="panel-collapse collapse <?php if( $tmp==1 ){ echo "in"; } ?>">
					                        <div class="panel-body"><?php echo $val->content; ?>

					                        </div>
					                    </div>
					                </div>
				                <?php $tmp++; ?>
			                	<?php endforeach; ?>
			                
				            </div>
				            <div class="pull-right"><a href="<?php echo e(route('getNewsListHighlight')); ?>">Xem thêm <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
						</div>
					<?php endif; ?>
					
					<?php if(!empty($listOther)): ?>
						<div class="col-sm-6">
				            <h4><a href="<?php echo e(route('getNewsList')); ?>">Tin tức khác</a></h4>
				            <div class="panel-group" id="accordion_2">
									<?php $param=100; ?>
				                   <?php foreach($listOther as $key=>$val): ?>
						                <div class="panel panel-default">
						                    <div class="panel-heading">
						                        <h4 class="panel-title">
	         										<?php if(in_array('danhgia-list',$arr_route)): ?><a href="<?php echo e(route('getNewsEdit',['id'=>$val->id])); ?>" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a><?php endif; ?>
						                            <a data-toggle="collapse" data-parent="#accordion_2" href="#collapse<?php echo e($param); ?>"><?php echo e($val->title); ?></a>
						                        </h4>
						                    </div>
						                    <div id="collapse<?php echo e($param); ?>" class="panel-collapse collapse <?php if( $param==100 ){ echo "in"; } ?>">
						                        <div class="panel-body"><?php echo $val->content; ?>

						                        </div>
						                    </div>
						                </div>
					                <?php $param++; ?>
				                	<?php endforeach; ?>
				                
			                </div>
				            <div class="pull-right" style="margin-bottom: 25px;"><a href="<?php echo e(route('getNewsListOther')); ?>">Xem thêm <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
						</div>
		            <?php endif; ?>
				</div>
			</div>
		    <div class="col-lg-12">
		    	<?php if(session('flash_message_err') != ''): ?>
				<div class="alert alert-danger" role="alert"><?php echo e(session('flash_message_err')); ?></div>
				<?php endif; ?>
				 <div class="row row-1">
					<?php if( count($dataGroupPageHome) > 0 ): ?>
						<?php foreach( $dataGroupPageHome as $value ): ?>
						    <div class="col-lg-4 col-md-4 item-hrms">
								<div class="item-hrm" style="background-image: -webkit-linear-gradient( 0deg, <?php echo e($value->background_color); ?> 0%, <?php echo e($value->background_color); ?> 100%); margin-bottom: 20px;box-shadow: inset 0 0 10px #ccc;">
									<div class="item-top row">
										<div class="col-xs-12">
											<?php if( $value->icon != NULL ): ?>
												<div class="item-logo">
													<img src="<?php echo e(asset('uploads/icon-cat-home/'.$value->icon)); ?>">
												</div>
											<?php endif; ?>
											<div class="wrap-title-info title-info-taikhoan">
												<span class="title-info"><?php echo e($value->title); ?></span>
											</div>
										</div>
									</div>
									<?php echo $value->content; ?>

								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<!-- -/.row-1 -->
			</div>
		</div>
		<!-- /.list-items -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>