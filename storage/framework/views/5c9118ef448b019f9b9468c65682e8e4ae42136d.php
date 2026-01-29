
<?php $__env->startSection('title', 'Đánh giá'); ?>
<?php $__env->startSection('content'); ?>
<div class="row content-Emonth">
    <div class="col-lg-2">
        <?php echo $__env->make('layouts.vay-von.menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá điểm tín nhiệm</h4>
        <div class="box_search">
            <form class="row" action="">
                <div class="form-group col-lg-4">
                    <label class="col-sm-4 control-label">Nhân viên</label>
                    <div class="col-sm-8">
                        <select name="personnel_id" id="selectPersonnel" class="form-control select2 narrow wrap" >
                            <option value="0">--Chọn nhân viên--</option>
                            <?php if(!empty($list_all_personnel)): ?>
                                <?php foreach($list_all_personnel as $personnelue): ?>
                                <option value="<?php echo e($personnelue->id); ?>" <?php if($personnelue->id == Request::get('personnel_id')): ?> <?php echo e("selected"); ?> <?php endif; ?>><?php echo e($personnelue->fullname); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <script type="text/javascript">
                            $('#selectPersonnel').select2({
                                containerCssClass: "wrap"
                            })
                        </script>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <label class="col-sm-3 control-label">Đơn vị</label>
                    <div class="col-sm-9">	
                        <select name="selectDepart" id="department" class="form-control select2 narrow wrap" style="width: 100%">
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
                <div class="form-group col-lg-4">
                    <label class="col-sm-4 control-label">Trạng thái</label>
                    <div class="col-sm-8">	
                        <select name="status" class="form-control select2 narrow wrap" style="width: 100%">
                            <option value="0"> -- Tất cả -- </option>
                            <option value="1" <?php if(1 == Request::get('status')): ?> <?php echo e("selected"); ?> <?php endif; ?>> Đủ tiêu chuẩn </option>
                            <option value="2" <?php if(2 == Request::get('status')): ?> <?php echo e("selected"); ?> <?php endif; ?>> Không đủ tiêu chuẩn </option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-lg-12 text-center">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
        <?php if(count($data) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Họ và tên</th>
                        <th class="text-center">Điểm tín nhiệm</th>
                        <th class="text-center" style="width:16%">Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $personnel): ?>
                        <tr>
                            <td class="text-center name"><?php echo e($personnel->fullname); ?></td>
                            <td class="text-center score_faith"><?php echo e($personnel->score_faith); ?></td>
                            <td class="text-center">
                                <?php if($personnel->score_faith >= $score_min): ?>
                                    <div href="#" class="daduyet" style="cursor: inherit;">Đủ tiêu chuẩn</div>
                                <?php else: ?>
                                    <div href="#" class="dahuy" style="cursor: inherit;">Không đủ tiêu chuẩn</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php /* <?php if($personnel->score > 0 && Auth::user()->id == 1): ?>
                                    <button data-pesonnel_id="<?php echo e($personnel->id); ?>" data-score="<?php echo e($personnel->score); ?>" data-note="<?php echo e($personnel->note); ?>" type="button" class="btn btn-xs btn-primary approvedEvaluateFaith" data-toggle="modal" data-target="#approvedEvaluateFaith" > Phê duyệt </button>
                                <?php else: ?>
                                    <button data-pesonnel_id="<?php echo e($personnel->id); ?>" type="button" class="btn btn-xs btn-warning evaluateFaith" data-toggle="modal" data-target="#evaluateFaith" > Đề xuất </button>
                                <?php endif; ?> */ ?>
                                <button data-pesonnel_id="<?php echo e($personnel->id); ?>" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal_view<?php echo e($personnel->id); ?>" > Xem hồ sơ </button>
                            </td>
                        </tr>
                        <!--  DETAIL POPUP FUNDS -->
                        <div id="myModal_view<?php echo e($personnel->id); ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content clearfix">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Xem hồ sơ</h4>
                                        <div class="ajax_response text-center" style="display: none;"></div>
                                    </div>
                                    <div style="padding: 20px;">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Họ và tên : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e($personnel->first_name.' '.$personnel->last_name); ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Giới tính : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php if($personnel->gender == 1): ?>
                                                    <?php echo e('Nam'); ?>

                                                <?php else: ?>
                                                    <?php echo e('Nữ'); ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if( $personnel->birthday != NULL ): ?>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Ngày sinh : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e(BatvHelper::formatDate($personnel->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?>

                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Điện thoại : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e($personnel->phone_number); ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Số chứng minh thư : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e($personnel->indentity_card_id); ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Chức danh : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo $personnel->jobs; ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Quỹ : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e(BatvHelper::getInfoFundsbyPersonnel( $personnel->id )); ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Giờ chấm công đi làm : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e($personnel->time_attendance_machine); ?>

                                            </div>
                                        </div>
                                        <?php if( $personnel->date_in != NULL ): ?>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Ngày vào công ty : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e(BatvHelper::formatDate($personnel->date_in,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?>

                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Chu kỳ xét tăng lương : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e(( $personnel->salary_frequency > 0 )?$personnel->salary_frequency.' năm':' Không được xét '); ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Loại hợp đồng : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php
                                                    $contracts = BatvHelper::getContracts($personnel->id);
                                                ?>
                                                <?php if( $contracts ): ?>
                                                    <?php foreach( $contracts as $k_contract => $v_contract ): ?>
                                                        <?php echo e($v_contract->title .': '.BatvHelper::formatDate($v_contract->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false).' - '.BatvHelper::formatDate($v_contract->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)); ?> </br>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Đơn vị : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e($personnel->title); ?>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Mức lương cơ bản đóng bảo hiểm : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e(BatvHelper::formatPriceSpecial($personnel->insurrance)); ?> VNĐ
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Quê quán : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo e($personnel->home_town); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="col-lg-12 text-right">
                <?php echo e($data->appends(Request::all())->links()); ?> 
            </div>
            <div id="evaluateFaith" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Đề xuất điểm tín nhiệm</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Họ và tên:</label><br>
                                        <div class="fullname"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Điểm tín nhiệm:</label>
                                        <input style="width:55%;" class="form-control input-sm" name="score" type="text" maxlength="5"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group clearfix note">
                                        <label class="control-label">Ghi chú gửi TGĐ:</label>
                                        <textarea class="form-control" data-autoresize="" rows="5" required="" name="note"></textarea>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="evaluateFaith()"> Gửi </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="approvedEvaluateFaith" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Phê duyệt đánh giá tín nhiệm từ TTT</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Họ và tên:</label><br>
                                        <div class="fullname"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Điểm tín nhiệm:</label>
                                        <input style="width:55%;" class="form-control input-sm" name="score" type="text" maxlength="5"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Phê duyệt:</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="action" checked value="1">Đồng ý</label>
                                            <label class="radio-inline"><input type="radio" name="action" value="2">Từ chối</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group clearfix note">
                                        <label class="control-label">Ghi chú của TTT:</label>
                                        <textarea class="form-control" data-autoresize="" rows="5" required="" name="note"></textarea>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="approvedEvaluateFaith()"> Cập nhật </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).on("click","button.evaluateFaith[data-toggle=modal]",function() {
                    personnel_id = $(this).attr('data-pesonnel_id');
                    fullname = $(this).closest('tr').find('td.name').text();
                    score_min = $(this).closest('tr').find('td.score_faith').text();
                    $('#evaluateFaith div.fullname').html(fullname);
                    $('#evaluateFaith input[name=score]').val(score_min);
                });
                
                $(document).on("click","button.approvedEvaluateFaith[data-toggle=modal]",function() {
                    personnel_id = $(this).attr('data-pesonnel_id');
                    fullname = $(this).closest('tr').find('td.name').text();
                    score = $(this).attr('data-score');
                    note = $(this).attr('data-note');
                    $('#approvedEvaluateFaith div.fullname').html(fullname);
                    $('#approvedEvaluateFaith input[name=score]').val(score);
                    $('#approvedEvaluateFaith textarea[name=note]').val(note);
                });

                function evaluateFaith(){
                    Swal.fire({
                        type: 'warning',
                        text: 'Sẽ có thông báo trực tiếp gửi đến TGĐ, bạn có chắc chắn muốn gửi?',
                        showCancelButton: true,
                        confirmButtonText: 'Có',
                        cancelButtonText: 'Không',
                    }).then(function (result) {
                        if(result.value){   
                            $.ajaxSetup(
                            {
                                headers:
                                {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            var data = {
                                        'personnel_id' : personnel_id,
                                        'score' : $('#evaluateFaith input[name=score]').val(),
                                        'note' : $('#evaluateFaith textarea[name=note]').val(),
                                    };
                                    
                            $.ajax({
                                method: "POST",
                                url: '<?php echo e(url("toh_hrm/api/evaluate-faith")); ?>',
                                data:data, 
                                dataType: 'json',
                                beforeSend: function() {
                                    $(".ajax_waiting").addClass("loading");
                                },
                                complete: function() {
                                    $(".ajax_waiting").removeClass("loading");
                                },
                                success: function (response) {
                                    if(response.status == 200){
                                        Swal.fire({
                                            type: "success",
                                            html: response.message,
                                            allowOutsideClick: false
                                        }).then(function(result){
                                            if(result.value){
                                                location.reload();
                                            }
                                        })
                                    }else{
                                        Swal.fire({
                                            type: 'warning',
                                            html: response.message,
                                            allowOutsideClick: false
                                        })
                                    }
                                },
                                error: function (error) {
                                    alert('Errors');
                                }
                            });
                        }
                    })

                }

                function approvedEvaluateFaith(){  
                    $.ajaxSetup(
                    {
                        headers:
                        {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var data = {
                                'personnel_id' : personnel_id,
                                'score' : score,
                                'note' : note,
                                'status' : $('#approvedEvaluateFaith input[name=action]').val(),
                            };
                            
                    $.ajax({
                        method: "POST",
                        url: '<?php echo e(url("toh_hrm/api/approved-evaluate-faith")); ?>',
                        data:data, 
                        dataType: 'json',
                        beforeSend: function() {
                            $(".ajax_waiting").addClass("loading");
                        },
                        complete: function() {
                            $(".ajax_waiting").removeClass("loading");
                        },
                        success: function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    type: "success",
                                    html: response.message,
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if(result.value){
                                        location.reload();
                                    }
                                })
                            }else{
                                Swal.fire({
                                    type: 'warning',
                                    html: response.message,
                                    allowOutsideClick: false
                                })
                            }
                        },
                        error: function (error) {
                            alert('Errors');
                        }
                    });
                }
            </script>
        <?php endif; ?>
    </div>
</div>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>