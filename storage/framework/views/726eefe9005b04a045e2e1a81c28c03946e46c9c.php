
<?php $__env->startSection('title', 'Thiết bị'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-function">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.thietbi.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                <?php if(session('flash_message_err') != ''): ?>
                <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
                <?php endif; ?>
                <?php if(session('flash_message_succ') != ''): ?>
                <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
                <?php endif; ?>
                <h4 class="title-fuction">Quản trị thiết bị</h4>
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
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                        <?php echo e(csrf_field()); ?>

                    </form>
                    
                </div>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">Danh sách thiết bị 			                        
                    <?php if(in_array('thietbi-add',$arr_route)): ?>
                    <a href="<?php echo e(route('getDeviceAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
                    <?php endif; ?>
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên thiết bị </th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Phiên bản hệ điều hành</th>
                                <th class="text-center">Thuộc danh mục </th>
                                <th class="text-center">Ngày mua</th>
                                <th class="text-center">&nbsp;&nbsp;</th>
                            </tr>
                            <?php 
                                if( !isset($_GET['page']) || $_GET['page']==1 ){
                                  $i  = 1;
                                }else{
                                  $i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                                }
                                ?>
                            <?php foreach($data as $val): ?>
                            <tr class="text-center">
                                <td><?php echo e($i); ?></td>
                                <td class="text-left"> <?php echo e($val->title); ?> </td>
                                <td><?php echo e($val->number); ?></td>
                                <td><?php echo e($val->system); ?></td>
                                <td><?php echo e($val->c_title); ?></td>
                                <td> <?php echo e(BatvHelper::formatDate($val->date_buy,"Y-m-d H:i:s", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
                                <td>
                                    <?php if(in_array('thietbi-edit',$arr_route)): ?>
                                    <a class="btn-edit" href="<?php echo e(route('getDeviceEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                    <?php endif; ?>
                                    <?php if(in_array('thietbi-del',$arr_route)): ?>
                                    <a class="btn-delete" href="<?php echo e(route('getDeviceDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                    <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
                                    <?php endif; ?>
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