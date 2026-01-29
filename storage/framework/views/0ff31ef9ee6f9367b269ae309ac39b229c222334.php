
<?php $__env->startSection('title', 'Thiết bị'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-function">
    <!-- Danh muc -->

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <?php if(session('flash_message_err') != ''): ?>
                <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
                <?php endif; ?>
                <?php if(session('flash_message_succ') != ''): ?>
                <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
                <?php endif; ?>
                <h4 class="title-fuction">Tìm kiếm thiết bị</h4>
                <div class="form-group col-lg-6 col-lg-offset-2">
                    <form class="form-horizontal" method="get" action="">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Nhập nội dung</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="text_search" value="<?php echo e(Request::get('text_search')); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-4 control-label">Danh mục</label>
                                    <div class="col-sm-8">
                                        <select name="c_id" class="form-control">
                                            <option value="0" selected>--Chọn--</option>
                                            <?php if(!empty($cateDevice)): ?>
                                            <?php echo $cateDevice; ?>

                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Người quản lý</label>
                                    <div class="col-sm-8">
                                        <?php if(!empty($listPersonnel)): ?>
                                        <select name="personnel_id" id="my-select" required>
                                            <option value="0">--Chọn--</option>
                                            <?php foreach($listPersonnel as $val): ?>
                                            <option value="<?php echo e($val->id); ?>" <?php  if(!empty(Request::input('personnel_id')) && Request::input('personnel_id')== $val->id) { echo "selected"; }?> ><?php echo e($val->fullname); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php endif; ?>
                                        <script type="text/javascript">
                                            $(function() {
                                                $('#my-select').searchableOptionList({
                                                    maxHeight: '250px'
                                                });
                                            }); 
                                        </script>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-orange" name="oke">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                        <?php echo e(csrf_field()); ?>

                    </form>
                    
                </div>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">Danh sách thiết bị <?php if( !isset($_GET['oke']) ){ echo 'đang quản lý'; } ?>	                        
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên thiết bị </th>
                                <th class="text-center">Phiên bản hệ điều hành </th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Thuộc danh mục </th>
                                <th class="text-center">Người quản lý</th>
                                <th class="text-center">Ngày bàn giao</th>
                                <th class="text-center">Trạng thái</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
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
                                <td class="text-left"> <?php echo e($val->title); ?> </td>
                                <td class="text-center"> <?php echo e($val->system); ?> </td>
                                <td class="text-center"> <?php echo e($val->number); ?> </td>
                                <td class="text-center"><?php echo e($val->c_title); ?></td>
                                <td class="text-left"><?php echo e(BatvHelper::getInfoUser($val->personnel_id)); ?></td>
                                <td class="text-center"> <?php echo e(BatvHelper::formatDate($val->tdp_dateIn,"Y-m-d H:i:s", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
                                <td class="text-center">
                                    <?php if( $val->tdp_options==0 ): ?>
                                    <?php echo e('Ngừng sử dụng'); ?>

                                    <?php else: ?>
                                    <?php echo e('Đang sử dụng'); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
	                                <a href="#" data-toggle="modal" data-target="#myModal_view<?php echo e($val->id); ?>"><img src="<?php echo e(asset('images/general/eye.png')); ?>"></a>
	                                <!--  DETAIL POPUP FUNDS -->
	                                <div id="myModal_view<?php echo e($val->id); ?>" class="modal fade" role="dialog">
	                                    <div class="modal-dialog">
	                                        <div class="modal-content clearfix">
	                                            <div class="modal-header">
	                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                                                <h4 class="modal-title text-center">Xem chi tiết</h4>
	                                                <div class="ajax_response text-center" style="display: none;"></div>
	                                            </div>
	                                            <div style="padding: 20px;">
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Tên thiết bị : </b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->title); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group  row">
	                                                    <div class="col-sm-4">
	                                                        <b>Thuộc danh mục :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->c_title); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group  row">
	                                                    <div class="col-sm-4">
	                                                        <b>Số lượng :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->number); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Hãng sản xuất :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->maker); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Phiên bản hệ điều hành :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->system); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Cỡ màn hình :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->screen_size); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Cấu hình :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e($val->config); ?>

	                                                    </div>
	                                                </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <b>Thông tin khác :</b>  
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <?php echo e($val->others); ?>

                                                        </div>
                                                    </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Người quản lý :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
	                                                        <?php echo e(BatvHelper::getInfoUser( $val->personnel_id )); ?>

	                                                    </div>
	                                                </div>
	                                                <div class="form-group row">
	                                                    <div class="col-sm-4">
	                                                        <b>Trạng thái :</b>  
	                                                    </div>
	                                                    <div class="col-sm-8">
						                                    <?php if( $val->options==0 ): ?>
						                                    <?php echo e('Ngừng sử dụng'); ?>

						                                    <?php else: ?>
						                                    <?php echo e('Đang sử dụng'); ?>

						                                    <?php endif; ?>
	                                                    </div>
	                                                </div>

	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
                                </td>
                            </tr>
                            <?php $i++ ?>
                            <?php endforeach; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>