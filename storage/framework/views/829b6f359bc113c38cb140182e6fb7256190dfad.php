
<?php $__env->startSection('title', 'Chi phí'); ?>
<?php $__env->startSection('content'); ?>

<div class="row content-function">
    <!-- Danh muc -->
    <?php echo $__env->make('layouts.chiphi.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="title-fuction">Cài đặt ngoại tệ
                    <?php if(in_array('chiphi-themcauhinhngoaite',$arr_route)): ?>
                    <a href="<?php echo e(route('getSettingCurrencyAdd')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
                    <?php endif; ?>
                </h4>

                <?php if(session('flash_message_succ') != ''): ?>
                <div class="alert alert-success" role="alert"> <?php echo e(session('flash_message_succ')); ?></div>
                <?php endif; ?>
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach($errors->all() as $error): ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <?php if( count($data)>0 ): ?>
                <div class="table-responsive detailType">
                    <table class="table table-hover">
                        <tbody>
                            <tr> 
                                <th class="text-center">STT</th>
                                <th class="text-center">Tiêu đề</th>
                                <th class="text-center">Giá trị</th>
                                <th class="text-center">Thời gian hiệu lực</th>
                                <th class="text-center">Thời gian hết hiệu lực</th>
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
                             <tr class="text-center">
                              <td><?php echo e($i); ?></td>
                              <td> <?php echo e($val['title']); ?> </td>
                              <td> <?php echo e(BatvHelper::formatPriceSpecial($val['value'])); ?> </td>
                              <td> <?php echo e(BatvHelper::formatDate($val['apply_from'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> </td>
                              <td> <?php echo e(BatvHelper::formatDate($val['apply_to'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> </td>
                              <td>
                                  <?php if(in_array('chiphi-suacauhinhngoaite',$arr_route)): ?>
                                    <a class="btn-edit" href="<?php echo e(route('getSettingCurrencyEdit',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                  <?php endif; ?>
                                  <?php if(in_array('chiphi-xoacauhinhngoaite',$arr_route)): ?>
                                    <a class="btn-delete" href="<?php echo e(route('getSettingCurrencyDel',['id'=>$val->id])); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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