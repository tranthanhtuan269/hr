
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
        <h4 class="title-fuction">Cấu hình tiêu chí chi tiết</h4>
        <form class="form-horizontal" method="get" action="">
            <div class="form-group col-lg-6">
                <label for="hoten" class="col-sm-4 control-label">Tên tiêu chí</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="criteria_content" autocomplete="off" placeholder="Nhập tên tiêu chí..." value="<?php echo e(Request::get('criteria_content')); ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                </div>
            </div>
            <?php echo e(csrf_field()); ?>

        </form>

        <h4 class="title-fuction">Danh sách tiêu chí 
            <?php if(in_array('danhgia-themtieuchi',$arr_route)): ?>
                <a href="<?php echo e(route('addEvaluationCriteria')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
            <?php endif; ?>
        </h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Tên tiêu chí</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php if(!empty($data)): ?>
                    <?php
                        if( !isset($_GET['page']) || $_GET['page']==1 ){
                            $count  = 1;
                        }else{
                            $count = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                        }
                        ?>
                        <?php foreach($data as $val): ?>
                        <tr>
                            <td><?php echo e($count); ?></td>
                            <td><?php echo e($val->criteria_content); ?></td>
                            <td>
                                <?php if(in_array('danhgia-suatieuchi',$arr_route)): ?>
                                    <a class="btn-edit" href="<?php echo e(route('editEvaluationCriteria',['id'=>$val->id])); ?>"><img src="<?php echo e(asset('images/general/edit.png')); ?>"></a>
                                <?php endif; ?>
                                <?php if(in_array('danhgia-xoatieuchi',$arr_route)): ?>
                                    <a class="btn-delete" href="#deleteItem" id="<?php echo e($val->id); ?>"><img src="<?php echo e(asset('images/general/remove.png')); ?>"></a>
                                    <div class="ajax_response <?php echo e($val->id); ?>" style="display: none;"></div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            
                           $('a[href="#deleteItem"]').click(function(){
                                var r = confirm("Bạn có chắc chắn muốn xóa !!!");
                                if (r == true) {
                                    var id =$(this).attr('id');
                                    var param = {
                                        id : id,
                                    };
                                    $.ajax({
                                        method: "GET",
                                        url: "<?php echo e(route('deleteEvaluationCriteriaAjax')); ?>",
                                        data: param,
                                        // dataType: "json",
                                        success: function (response) {
                                            var obj = $.parseJSON(response);
                                            if(obj.Response=='Error')
                                            {
                                                $(".ajax_response."+id).removeClass('alert-success').addClass("alert-error");
                                                $(".ajax_response."+id).html(obj.Error);
                                                $(".ajax_response."+id).show('slow');
                                            }else{
                                                $(".ajax_response."+id).removeClass('alert-error').addClass("alert-success");
                                                $(".ajax_response."+id).html(obj.Message);
                                                $(".ajax_response."+id).show('slow');
                                            }
                                            setTimeout(function() {
                                                $(".ajax_response."+id).fadeOut( "slow" );
                                            }, 3000);

                                        },
                                        error: function (data) {
                                            console.log('Error:', data);
                                        }
                                    })
                                } else {
                                    return false;
                                }
                           }); 
                        });
                    </script>
                </tbody>
            </table>
        </div>
    
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>