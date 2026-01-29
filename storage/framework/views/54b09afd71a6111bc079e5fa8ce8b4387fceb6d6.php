
<?php $__env->startSection('title', 'Chi phí'); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
        <h4 class="title-fuction">Thêm loại chi phí</h4>
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

        <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-4 control-label">Tên loại chi phí <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo e(old('title')); ?>" required>
                </div>
            </div>
            <div class="form-group curency">
                <label class="col-sm-4 control-label">Chi phí ngoại tệ</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                      <label><input type="checkbox" value="">Chọn</label>
                    </div>
                </div>
            </div>
            <div class="form-group usd hidden">
                <label class="col-sm-4 control-label">Giá trị (USD)<span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" onkeyup="format_curency_general( this.value,'formatResult','result_usd' );" id="formatResult" class="form-control" value="<?php echo e(old('value_usd')); ?>" >
                    <input type="hidden" name="value_usd" id="result_usd">
                    <span style="color: red;font-style: italic;font-weight: 600;font-size: 12px;" id="submittername">(1$ = <?php echo e(BatvHelper::formatPriceSpecial($value_usd)); ?> VNĐ)</span>
                </div>
            </div>
            <div class="form-group vnd">
                <label class="col-sm-4 control-label">Giá trị (VNĐ)<span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" onkeyup="format_curency( this.value );" id="numFormatResult" class="form-control" value="<?php echo e(old('value')); ?>"  required>
                    <input type="hidden" name="value" id="result">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"> Mô tả</label>
                <div class="col-sm-6"><textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control" id="description"><?php echo e(old('description')); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
                <div class="col-sm-6">
                     <select class="form-control" name="type" id="type">
                         <option value="0" <?php echo e((old("type") == 0 ? "selected":"")); ?>>Chi phí phát sinh</option>
                         <option value="1" <?php echo e((old("type") == 1 ? "selected":"")); ?>>Chi phí cố định</option>
                     </select>
                </div>
            </div>
            <div class="form-group funds">
                <label class="col-sm-4 control-label">Quỹ <span class="required">*</span></label>
                <div class="col-sm-6 funds_default">
                    <?php if(!empty($listFunds)): ?>
                        <?php foreach($listFunds as $fund): ?>
                            <div class="checkbox bypersonnel_notselect">
                                <div class="col-sm-3"><input type="checkbox"  id="<?php echo e($fund->id); ?>" name="fund[<?php echo e($fund->id); ?>]" value="<?php echo e($fund->id); ?>" <?php if( array_key_exists( $fund->id, old('fund', []) ) ): ?> checked <?php elseif( !(old('fund')) && $funds_id_default == $fund->id): ?> checked  <?php endif; ?>  ><?php echo e($fund->title); ?></div>
                                <div class="col-sm-6">
                                    <input type='text' name="percent[<?php echo e($fund->id); ?>]"  <?php if(  !(old('fund')) && $funds_id_default == $fund->id): ?> value="100" <?php else: ?> value="<?php echo e(old('percent.'.$fund->id)); ?>"  <?php endif; ?> > %  
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-7 col-sm-offset-5">( <input type="checkbox" value="" id="bypersonnel"> <span style="font-weight: 600;font-style: italic;">Theo phân bổ nhân sự </span> )</div>
            </div>
            <div class="form-group">
                    <label class="col-sm-4 control-label">Người tạo <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php if(!empty($getListManager)): ?>
                        <select name="personnel" class="form-control select2 narrow wrap">
                            <?php foreach($getListManager as $personnel): ?>
                            <option value="<?php echo e($personnel->id); ?>" <?php if( $personnel->id == Auth::user()->id ): ?> selected="selected" <?php endif; ?> ><?php echo e($personnel->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Thời gian hiệu lực <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="valid_from" value='<?php echo ( isset( $_POST["valid_from"] ) )?$_POST["valid_from"]:date("d/m/Y")?>'>
                </div>
            </div>
            <div id="day_boxing" class="day_boxing form-group">
                <label class="col-sm-4 control-label">Thời gian hết hiệu lực <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="valid_to" value='<?php echo e(old("valid_to")?old("valid_to"):"31/12/2099"); ?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Upload hóa đơn </label>
                <div class="col-sm-6">
                    <input type="file"  name="fileImage" id="fileImage">
                </div>
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Thêm mới">
            </div>
            <?php echo e(csrf_field()); ?>

        </form>
    </div>
    <div class="col-lg-1"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var tmp = $( "select option:selected" ).val();
        if( tmp == 1 ){
            $('.day_boxing').css("display", "block");
        }else{
            $('.day_boxing').css("display", "none");
        }

        $('.curency input[type=checkbox]').change(function() {
            $(".curency input:checked").each(function () {
                $(".vnd input[id=numFormatResult]").attr("disabled", true);
                $(".usd input[id=result_usd]").attr("required", true);
                $(".usd").removeClass("hidden");
            });

            $(".curency input:not(:checked)").each(function () {
                $(".vnd input[id=numFormatResult]").removeAttr("disabled");
                $(".usd input[id=result_usd]").removeAttr("required");
                $(".usd input[id=result_usd],.usd input[id=formatResult]").val('');
                $(".usd").addClass("hidden");
            });
        });

        $("input[id=formatResult]").keyup(function(){
            var usd = $('input[id="result_usd"]').val();
            var time = $('input[name="valid_from"]').val();
            $.ajax({

                type: "GET",
                url: "<?php echo e(route('getCurencyAjax')); ?>",
                data:{
                    'usd' : usd,
                    'time':time
                },
                // dataType: "json",
                success: function(response){
                    var obj = $.parseJSON(response);
                    if( obj.usd == null ){
                        alert('Chưa có cấu hình tiền tệ trong khoảng thời gian hiện tại !');
                        $("input[id=numFormatResult],input[id=result]").val('');
                    }else{
                        $("input[id=numFormatResult]").val( formatNumber(obj.value, '.', ',') );
                        $("input[id=result]").val(obj.value);
                    }               
                }
            });
        });

        $("input[name=valid_from]").change(function(){
            $(".curency input:checked").each(function () {
                var usd = $('input[id="result_usd"]').val();
                var time = $('input[name="valid_from"]').val();
                $.ajax({

                    type: "GET",
                    url: "<?php echo e(route('getCurencyAjax')); ?>",
                    data:{
                        'usd' : usd,
                        'time':time
                    },

                    // dataType: "json",
                    success: function(response){
                        var obj = $.parseJSON(response);
                        if( obj.usd == null ){
                            alert('Chưa có cấu hình tiền tệ trong khoảng thời gian hiện tại !');
                            $("input[id=numFormatResult],input[id=result]").val('');
                        }else{
                            $("input[id=numFormatResult]").val( formatNumber(obj.value, '.', ',') );
                            $("input[id=result]").val(obj.value);
                            $("#submittername").html("(1$ = "+formatNumber(obj.usd, '.', ',')+" VNĐ)");
                        }  
                    }
                });
            });
            if(document.getElementById('bypersonnel').checked){
                $(".bypersonnel_select").remove();
                var time = $('input[name="valid_from"]').val();
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('getByPersonnelAjax')); ?>",
                    data:{
                        'time':time
                    },
                    // dataType: "json",
                    success: function(data){
                        $(".funds_default").html(data);
                    }
                });
            }

        });

        $('input[id=bypersonnel]').change(function() {
            if(document.getElementById('bypersonnel').checked){
                $(".bypersonnel_notselect").remove();
                var time = $('input[name="valid_from"]').val();
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('getByPersonnelAjax')); ?>",
                    data:{
                        'time':time
                    },
                    // dataType: "json",
                    success: function(data){
                        $(".funds_default").html(data);
                    }
                });
            }else{
                $(".bypersonnel_select").remove();
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('getDefaultFundsAjax')); ?>",
                    // dataType: "json",
                    success: function(data){
                        $(".funds_default").html(data);
                    }
                });
            }
        });

        $('#type').change(function() {
            if ($(this).val() === '1') {
                $('#day_boxing input').prop('required',true);
                $('.day_boxing').css("display", "block");
            }else{
                $('.day_boxing').css("display", "none");
                $('#day_boxing input').removeAttr('required');
            }
        });

        $('.funds input[type=checkbox]').change(function() {
            var numberOfChecked = $('.funds input:checkbox:checked').length;  
            var item = 100/numberOfChecked; 
            var item = (100/numberOfChecked).toFixed(3);
            $(".funds input:checked").each(function () {
                var id = $(this).attr("id");
                $(".funds input[name='percent["+id+"]']").prop('required',true);
                $(".funds input[name='percent["+id+"]']").val( item );
            });

            $(".funds input:not(:checked)").each(function () {
                var id = $(this).attr("id");
                $(".funds input[name='percent["+id+"]']").removeAttr('required');
                $(".funds input[name='percent["+id+"]']").val('');
            });
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>