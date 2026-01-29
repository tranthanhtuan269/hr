

<?php $__env->startSection('title', 'TOH HRMS'); ?>

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
        <h4 class="title-fuction">Quản trị tin tức</h4>
        <form class="form-horizontal" method="get" action="">
          <div class="form-group col-lg-12">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="inputName" class="col-sm-4 control-label">Tiêu đề tin tức</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" value="<?php echo e(Request::get('title')); ?>">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                    <a href="<?php echo e(route('getNewsList')); ?>" class="btn btn-sm btn-grey">Nhập lại</a>
                  </div>
                </div>
              </div>


            </div>
          </div>
              <?php echo e(csrf_field()); ?>

        </form>


      </div>
      <div class="col-lg-12">
        <h4 class="title-fuction">Danh sách tin tức  
            <?php if(in_array('tintuc-themtintuc',$arr_route)): ?>
              <a href="<?php echo e(route('getNewsAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
            <?php endif; ?>
        </h4>
        <?php if( count($data)>0 ): ?>
            <div class="table-responsive"> 
              <table class="table table-hover">
                <tbody>
                    <tr>
                      <th>STT</th>
                      <th>Tiêu đề tin tức </th>
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
                      <td><?php echo e($i); ?></td>
                      <td> <?php echo e($val->title); ?> </td>
                      <td>
                          <?php if(in_array('tintuc-suatintuc',$arr_route)): ?>
                            <a class="btn-edit" href="<?php echo e(route('getNewsEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                          <?php endif; ?>
                          <?php if(in_array('tintuc-xoatintuc',$arr_route)): ?>
                            <a class="btn-delete" href="<?php echo e(route('getNewsDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                          <img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
                          <?php endif; ?>
                      </td>  
                    </tr>
                      <?php $i++ ?>
                    <?php endforeach; ?>
                    
                 
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-danger" role="alert"> Không tìm thấy kết quả tìm kiếm</div>
        <?php endif; ?>
      </div>
      <div class="col-lg-12 text-right">
          <?php echo e($data->appends(Request::all())->links()); ?> 
      </div>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>