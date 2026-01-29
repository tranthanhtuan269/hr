
<?php $__env->startSection('title', 'Vai trò'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-function">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.users.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="title-fuction">Quản trị phân quyền</h4>
                <form class="form-horizontal" method="get" action="">
                    <div class="form-group col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-4 control-label">Quyền</label>
                                    <div class="col-sm-8">
                                        <?php if(!empty($listRoles)): ?>
                                            <select name="roles_name" class="form-control">
                                                <option value="0">--Tất cả--</option>
                                                <?php foreach($listRoles as $val): ?>
                                                     <option value="<?php echo e($val['roles_name']); ?>" <?php echo ($val['roles_name']==Request::get("roles_name"))?"selected":""; ?>><?php echo e($val['roles_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">
                    Phân quyền người dùng 
                    <?php if(in_array('roles-add',$arr_route)): ?>
                        <a href="<?php echo e(route('getRoleAdd')); ?>"> <img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
                    <?php endif; ?>
                </h4>
                <?php if(session('flash_message_err') != ''): ?>
                <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
                <?php endif; ?>
                <?php if(session('flash_message_succ') != ''): ?>
                <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
                <?php endif; ?>
                <style type="text/css">
                    ul.menu{list-style: none;padding: 0}
                    .menu > li{float: left; margin-right: 5px;}
                </style>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Role</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            <?php foreach($roles as $role): ?>
                            <tr>
                                <td><?php echo e($role['roles_name']); ?></td>
                                <td>
                                    <?php if(in_array('roles-edit',$arr_route)): ?>
                                        <a class="btn-edit" href="<?php echo e(route('getRoleEdit',['id'=>$role['id']])); ?>"> <img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                    <?php endif; ?>
                                    <?php if(in_array('roles-del',$arr_route)): ?>
                                        <a class="btn-delete" href="<?php echo e(route('getRoleDel',['id'=>$role['id']])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                        <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="col-lg-12 text-right">
                        <?php echo e($roles->appends(Request::query())->render()); ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>