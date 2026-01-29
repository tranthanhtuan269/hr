
<?php $__env->startSection('title', 'TOH HRMS'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-function">
    <div class="col-lg-2">
        <h4 class="title-fuction">Danh mục</h4>
        <?php if(in_array('danhgia-viethuongdan',$arr_route)): ?>
            <p><a href="<?php echo e(route('getEvaluationSupport')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hướng dẫn đánh giá</a></p>
        <?php endif; ?>
        <?php if(in_array('danhgia-danhsachbotieuchi',$arr_route)): ?>
            <p><a href="<?php echo e(route('listDepartmentCriteria')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tiêu chí</a></p>
        <?php endif; ?>
        <?php if(in_array('danhgia-danhsachtieuchi',$arr_route)): ?>
            <p><a href="<?php echo e(route('getEvaluationCriteria')); ?>" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tiêu chí chi tiết</a></p>
        <?php endif; ?>
    </div>
    <div class="col-lg-10">
        <?php if(session('flash_message_succ') != ''): ?>
        <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
        <?php endif; ?>
        <?php if(session('flash_message_err') != ''): ?>
        <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
        <?php endif; ?>
        <h4 class="title-fuction">Cấu hình bộ tiêu chí</h4>
        <form class="form-horizontal" method="get" action="">
            <div class="form-group col-lg-6">
                <label for="hoten" class="col-sm-4 control-label">Tên tiêu chí</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" autocomplete="off" placeholder="Nhập tên tiêu chí..." value="<?php echo e(Request::get('title')); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                </div>
            </div>
            <?php echo e(csrf_field()); ?>

        </form>

        <h4 class="title-fuction">Danh sách bộ tiêu chí 
            <?php if(in_array('danhgia-caidat',$arr_route)): ?>
                <a href="<?php echo e(route('settingEvaluationCriteria')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
            <?php endif; ?>
        </h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Tên bộ tiêu chí</th>
                        <th>Ngày hiệu lực</th>
                        <th>Ngày hết hiệu lực</th>
                        <th>Danh sách tiêu chí</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php 
                        if( !isset($_GET['page']) || $_GET['page']==1 ){
                            $i  = 1;
                        }else{
                            $i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                        }
                    ?>
                    <?php if(!empty($data)): ?>
                        <?php foreach($data as $val): ?>
                        <tr>
                            <td><?php echo e($i); ?></td>
                            <td> <?php echo e($val['title']); ?></td>
                            <td> <?php echo e(BatvHelper::formatDate($val['date_start'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?></td>
                            <td> <?php echo e(BatvHelper::formatDate($val['date_end'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?></td>
                            <td>
                                <?php foreach($val['criteria_content'] as $v): ?>
                                    <div> - <?php echo e($v); ?> </div>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php if(in_array('danhgia-suabotieuchi',$arr_route)): ?>
                                    <a class="btn-edit" href="<?php echo e(route('editDepartmentCriteria',['id'=>$val['id']])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                <?php endif; ?>
                                <?php if(in_array('danhgia-xoabotieuchi',$arr_route)): ?>
                                    <a class="btn-delete" href="<?php echo e(route('deleteDepartmentCriteria',['id'=>$val['id']])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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
        <div class="col-lg-12 text-right">
            <?php echo e($data->appends(Request::query())->render()); ?> 
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>