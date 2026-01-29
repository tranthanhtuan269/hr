

<?php $__env->startSection('title', 'Tài khoản'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
<?php
        // echo "<pre>";
        // print_r($data);die;
?>
  <!-- Danh muc -->
  <?php echo $__env->make('layouts.users.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-10">
    <div class="row">
      <div class="col-lg-12">
      <?php if(session('flash_message_err') != ''): ?>
       <div class="alert alert-danger" role="alert"> <?php echo e(session('flash_message_err')); ?></div>
      <?php endif; ?>
      <?php if(session('flash_message_succ') != ''): ?>
       <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
      <?php endif; ?>
        <h4 class="title-fuction">Quản trị người dùng</h4>
        <form class="form-horizontal" method="get" action="">
          <div class="form-group col-lg-12">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="inputName" class="col-sm-4 control-label">Tên</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" placeholder="Họ tên" value="<?php echo e(Request::get('name')); ?>">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="email" class="col-sm-4 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="email"  placeholder="Email" value="<?php echo e(Request::get('email')); ?>">
                  </div>
                </div>
              </div>


            </div>
          </div>
           <div class="form-group col-lg-12">
                <div class="text-center">
                  <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                </div>
              </div>
              <?php echo e(csrf_field()); ?>

        </form>


      </div>
      <div class="col-lg-12">
        <h4 class="title-fuction">Danh sách người dùng 
            <?php if(in_array('user-add',$arr_route)): ?>
              <a href="<?php echo e(route('getUserAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
            <?php endif; ?>
        </h4>
        <div class="table-responsive"> 
          <table class="table table-hover">
            <tbody>
              <tr>
                <th>User Name</th>
                <th>Email </th>
                <th>Role group</th>
                <th></th>
              </tr>
              <?php foreach($data as $val): ?>
              <tr>
                <td><?php echo e(str_limit($val->name, $limit = 30, $end = '...')); ?></td> 
                <td><?php echo e($val->email); ?></td>
                <td><?php echo nl2br($val->roles_name); ?></td>
                <td>
                  <?php if(in_array('user-edit',$arr_route)): ?>
                    <a class="btn-edit" href="<?php echo e(route('getUserEdit',['id'=>$val->id ])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                  <?php endif; ?>
                  <?php if(in_array('user-del',$arr_route)): ?>
                    <a class="btn-delete" href="<?php echo e(route('getUserDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
                  <?php endif; ?>
                </td>  
              </tr>
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