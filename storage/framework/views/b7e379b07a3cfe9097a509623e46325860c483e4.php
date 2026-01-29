

<?php $__env->startSection('title', 'Lương thưởng'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $turns = ( date('m') >= 1 && date('m') <= 6 )? 1 : 2;
    $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
    
    if( isset( $_GET['frequency'] ) ){
        if( date('m') >= 1 && date('m') <= 6 ){
            $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y') : "đợt 2(tháng 12) năm ".date('Y', strtotime(date('Y').' -1 year'));
        }else{
            $turns = (  $_GET['frequency'] == 1 ) ? 1 : 2;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
        }
    }

    if( date('m') >= 1 && date('m')<=6 ){
        $time_before = 'Đợt T12/'.date('Y', strtotime(date('Y').' -1 year'));
        $time_after = 'Đợt T6/'.date('Y');
    }else{
        $time_before = 'Đợt T6/'.date('Y');
        $time_after = 'Đợt T12/'.date('Y');
    }
?>
<div class="row box_salary">
        <!-- Danh muc -->
        <?php echo $__env->make('layouts.luongthuong.server.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div class="col-lg-10">

                <h4 class="title-fuction">
                    Danh sách nhân viên được TL
                </h4>
                <form class="form-horizontal clearfix" method="get" action="">
                    <div class="form-group col-lg-6">
                    <label for="date" class="col-sm-3 control-label">Đợt xét :</label>
                        <div class="col-sm-7">
                            <select name="frequency" class="form-control select2 wrap">
                                <option value="2" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 2)?"selected":""; ?> ><?php echo e($time_after); ?></option>
                                <option value="1" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 1)?"selected":""; ?> ><?php echo e($time_before); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
                        <div class="col-sm-8">  
                           <select name="selectDepart" id="department" class="form-control select2 wrap">
                                <option value="0"> -- Đơn vị -- </option>
                                <?php echo $department; ?>

                            </select>
                            <script type="text/javascript">
                                var $select2 = $('.select2').select2({
                                    containerCssClass: "wrap"
                                })
                            </script>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 text-center">
                        <input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>
                <div class="table-responsive" >
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" width="1%">STT</th>
                                <th class="text-center" width="20%">Họ và tên</th>
                                <th class="text-center">Ngày n/l gần nhất</th>
                                <th class="text-center">H/s lương trước</th>
                                <th class="text-center">H/s lương hiện tại</th>
                                <th class="text-center">Thời gian a/d mức lương mới</th>
                                <th class="text-center" width="25%">Khoảng t/g được xét TL</th>
                                <th class="text-center" width="5%">Tổng hệ số t/g truy lĩnh thực tế(NCTT/NCTC)</th>
                                <th class="text-center" width="16.5%">Số tiền TL</th>
                                <th width="11%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // echo "<pre>";
                                // print_r($data);die;
                            ?>
                            <?php if(!empty($data)): ?>
                                <?php $tmp=1; ?>
                                <?php foreach($data as $key=>$val): ?>
                                 <tr>
                                    <td class="text-center"> <?php echo e($tmp); ?> </td> 
                                    <td style="text-align: left; padding-left: 5px;">
                                        <a href="<?php echo e(route('getPersonnelEdit',['id'=>$val['personnel_id'] ])); ?>"><?php echo e(str_limit( $val['fullname'], $limit = 35, $end = '...')); ?></a>
                                    </td>
                                    <td><?php echo e(BatvHelper::formatDate($val["date_hdct"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
                                    <td><?php echo e($val['hsl_old']); ?></td>
                                    <td><?php echo e($val['hsl_ht']); ?></td>
                                    <td><?php echo e(BatvHelper::formatDate($val["date_nlgn"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?></td>
                                    <td><?php echo e($val["period"]["from"]); ?>-<?php echo e($val["period"]["to"]); ?></td>
                                    <td><?php echo e(round($val["param_hs"],3)); ?></td>
<!--                                     <td><?php echo e($val['number_tt']['month']); ?> tháng <?php if( $val['number_tt']['days'] > 0 ): ?>  <?php echo e($val['number_tt']['days']); ?> ngày <?php endif; ?></td> -->
                                    <td><span class="col-sm-6" style="margin-right: 5px;"><?php echo e(BatvHelper::formatPrice( $val['value_tt'] )); ?> </span>&nbsp
                                        <?php if($val['type']!=1): ?>
                                            <a href="#" class="btn btn-xs btn-orange text-right" data-toggle="modal" data-target="#editSalaryTL<?php echo e($val['personnel_id']); ?>" style="text-decoration: none;">Sửa</a>
                                            <!-- POPUP -->
                                            <div id="editSalaryTL<?php echo e($val['personnel_id']); ?>" class="modal fade" role="dialog">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <div style="font-size: 18px;text-align: center;">Sửa số tiền truy lĩnh</div>
                                                    </div>

                                                    <form class="form-horizontal" id="editSalaryTL<?php echo e($val['personnel_id']); ?>">
                                                        <?php echo csrf_field(); ?>

                                                        <div class="modal-body row">
                                                            <div class="form-group clearfix">
                                                                <label class="col-sm-4" style="text-align: right;">Nhập số tiền: </label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" onkeyup="format_curency_general(this.value,'numFormatResult<?php echo e($val["personnel_id"]); ?>','result<?php echo e($val["personnel_id"]); ?>');" id="numFormatResult<?php echo e($val['personnel_id']); ?>" class="form-control" value="<?php echo e(old('income_value')); ?>" >
                                                                    <input type="hidden" id="result<?php echo e($val['personnel_id']); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                        
                                                            <div id="pre_ajax_loading_updated<?php echo e($val['personnel_id']); ?>" class="hide" style="text-align: center;margin-bottom: 10px;"><img src="<?php echo e(asset('images/general/bx_loader.gif')); ?>"></div>
                                                            <button type="button" class="btn btn-xs btn-orange" onclick="editSalaryTL(<?php echo e($val['personnel_id']); ?>)">Cập nhật</button>
                                                            <div class="ajax_response_updated <?php echo e($val['personnel_id']); ?>" style="display: none;margin: 10px 0px;padding: 6px 0px;font-size: 12px;"></div>

                                                        </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 125px;">
                                        <input type="hidden" name="month<?php echo e($val['personnel_id']); ?>" value='<?php echo e(BatvHelper::formatDate($val["date_nlgn"],"Y-m-d", $formatDate="m",$timeFormat="H:i:s",$time=false)); ?>' >
                                        <input type="hidden" name="year<?php echo e($val['personnel_id']); ?>" value='<?php echo e(BatvHelper::formatDate($val["date_nlgn"],"Y-m-d", $formatDate="Y",$timeFormat="H:i:s",$time=false)); ?>' >
                                        <input type="hidden" name="income_value<?php echo e($val['personnel_id']); ?>" value='<?php echo e($val["value_tt"]); ?>'>
                                        <?php if($val['type']==1): ?>
                                            <span class="daduyet">Đã duyệt</span>
                                        <?php else: ?>
                                            <div id="pre_ajax_loading<?php echo e($val['personnel_id']); ?>" class="hide" style="text-align: center;margin-bottom: 10px;"><img src="<?php echo e(asset('images/general/bx_loader.gif')); ?>"></div>
                                            <button type="button" class="btn btn-xs btn-orange" onclick="updateData(<?php echo e($val['personnel_id']); ?>)">Phê duyệt</button>
                                            <div class="ajax_response <?php echo e($val['personnel_id']); ?>" style="display: none;margin: 10px 0px;padding: 6px 0px;font-size: 12px;"></div>
                                            <input type="hidden" id="income_value<?php echo e($val['personnel_id']); ?>" value="<?php echo e(BatvHelper::formatPrice( $val['value_tt'] )); ?>">
                                            
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $tmp++; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.modal-body input[type=checkbox]').change(function() {
            param = $(this).val();
            if(this.checked){
                $("input[id=numFormatResult"+param+"]").attr("disabled", false);
                $("input[id=numFormatResult"+param+"]").attr("required", true);
                $("input[id=numFormatResult"+param+"]").val('');
            }else{
                $("input[id=numFormatResult"+param+"]").attr("disabled", true);
                $("input[id=numFormatResult"+param+"]").attr("required", false);
                $("input[id=result"+param+"]").val('');
            }
        });

    });

    function updateData(id){
        var tmp = confirm("Bạn có chắc chắn muốn phê duyệt ? ");
        if (tmp == true) {
            var id = id;
            var month = $('input[name=month'+id+']').val();
            var year = $('input[name=year'+id+']').val();
            var income_value = $('input[name=income_value'+id+']').val();
            var frequency = $('select[name="frequency"]').val();

            var param = {
                            id : id,
                            month : month,
                            year:year,
                            income_value:income_value,
                            frequency:frequency,
                        };
            $.ajax({
                method: "GET",
                url: "<?php echo e(route('approvalSalaryTLAjax')); ?>",
                data: param,
                beforeSend: function() {
                    $("div#pre_ajax_loading"+id).removeClass("hide");
                },
                complete: function() {
                    $("div#pre_ajax_loading"+id).addClass("hide");
                    $(".result-alert").show();
                },
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
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    }
                },
            })
        }
    }

   function editSalaryTL(id){
        var id = id;
        var month = $('input[name=month'+id+']').val();
        var year = $('input[name=year'+id+']').val();
        var income_value_handmade = $('input[id=result'+id+']').val();
        var check;
        var x = $('input[id=result'+id+']').val();
        // alert(x);return false;
        if( !isNaN(x) && x >= 0){
            check = 1;
        }else{
            check = 0;
        }

        var param = {
                        id : id,
                        month : month,
                        year:year,
                        income_value_handmade:income_value_handmade,
                        check:check,
                    };
        $.ajax({
            method: "GET",
            url: "<?php echo e(route('editSalaryTLAjax')); ?>",
            data: param,
            beforeSend: function() {
                $("div#pre_ajax_loading_updated"+id).removeClass("hide");
            },
            complete: function() {
                $("div#pre_ajax_loading_updated"+id).addClass("hide");
                $(".result-alert").show();
            },
            success: function (response) {
                var obj = $.parseJSON(response);
                if(obj.Response=='Error')
                {
                    $(".ajax_response_updated."+id).removeClass('alert-success').addClass("alert-error");
                    $(".ajax_response_updated."+id).html(obj.Error);
                    $(".ajax_response_updated."+id).show('slow');
                }else{
                    $(".ajax_response_updated."+id).removeClass('alert-error').addClass("alert-success");
                    $(".ajax_response_updated."+id).html(obj.Message);
                    $(".ajax_response_updated."+id).show('slow');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                }
            },
        })
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>