@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')
<?php
    // echo "<pre>";
    // print_r($data);die;
?>
<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
        <h4 class="title-fuction">Sửa loại chi phí</h4>
        @if (session('flash_message_succ') != '')
        <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
        @endif
        @if(count($errors) > 0)
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-4 control-label">Tên loại chi phí <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="{{old('title',isset($data->title) ? $data->title : null )}}" required>
                </div>
            </div>
            <div class="form-group curency">
                <label class="col-sm-4 control-label">Chi phí ngoại tệ</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                      <label><input type="checkbox" value=""  @if($data->value_usd > 0) checked @endif>Chọn</label>
                    </div>
                </div>
            </div>
            <div class="form-group usd  @if($data->value_usd == 0) hidden @endif ">
                <label class="col-sm-4 control-label">Giá trị (USD)<span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" onkeyup="format_curency_general( this.value,'formatResult','result_usd' );" id="formatResult" class="form-control" value="{{old('value_usd',isset($data->value_usd) ? BatvHelper::formatPriceSpecial($data->value_usd) : null )}}" >
                    <input type="hidden" name="value_usd" id="result_usd" value="{{old('value_usd',isset($data->value_usd) ? $data->value_usd : null )}}">
                    <span style="color: red;font-style: italic;font-weight: 600;font-size: 12px;" id="submittername">(1$ = {{ BatvHelper::formatPriceSpecial($value_usd) }} VNĐ)</span>
                </div>
            </div>
            <div class="form-group vnd">
                <label class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input type="text" onkeyup="format_curency( this.value );" class="form-control" id="numFormatResult" value="{{old('value',isset($data->value) ? BatvHelper::formatPriceSpecial($data->value) : null )}}" required @if($data->value_usd > 0) disabled @endif>
                    <input type="hidden" name="value" id="result" value="{{old('value',isset($data->value) ? $data->value : null )}}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"> Mô tả</label>
                <div class="col-sm-6">
                    <textarea rows="4" onkeydown="expandtext(this);" name="description" class="form-control">{{old('description',isset($data->description) ? $data->description : null )}}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Loại <span class="required">*</span></label>
                <div class="col-sm-6">
                     <select class="form-control" name="type" id="type">
                         <option value="0" <?php echo (isset($data->type) && $data->type == 0 )?"selected":""; ?> >Chi phí phát sinh</option>
                         <option value="1" <?php echo (isset($data->type) && $data->type == 1 )?"selected":""; ?>>Chi phí cố định</option>
                     </select>
                </div>
            </div>
            <div class="form-group funds">
                <label class="col-sm-4 control-label">Quỹ <span class="required">*</span></label>
                <div class="col-sm-6 funds_default">
                    @if(!empty($listFunds))
                        @foreach($listFunds as $fund)
                            <div class="checkbox bypersonnel_notselect">
                                <div class="col-sm-3"><input type="checkbox"  id="{{ $fund->id }}" name="fund[{{ $fund->id }}]" value="{{ $fund->id }}" <?php echo ( isset($fund->selected) && $fund->selected==1 )?"checked":""; ?> >{{ $fund->title }}</div> 
                                <div class="col-sm-6">
                                    <input type='text' name="percent[{{ $fund->id }}]" value="{{ isset($fund->percent) ? $fund->percent  : null }}" > %  
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-7 col-sm-offset-5">( <input type="checkbox" value="" id="bypersonnel"> <span style="font-weight: 600;font-style: italic;">Theo phân bổ nhân sự </span> )</div>
            </div>
            <div class="form-group">
                    <label class="col-sm-4 control-label">Người tạo <span class="required">*</span></label>
                    <div class="col-sm-6">
                        @if(!empty($getListManager))
                        <select name="personnel" class="form-control select2 narrow wrap">
                            @foreach($getListManager as $personnel)
                            <option value="{{ $personnel->id }}" <?php if( $personnel->id == $data->created_by ): ?> selected="selected" <?php endif; ?> >{{ $personnel->name }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Thời gian hiệu lực <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input id="from" type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="valid_from" value='{{ BatvHelper::formatDate($data->valid_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}'  required>
                </div>
            </div>
            <div id="day_boxing" class="day_boxing form-group">
                <label class="col-sm-4 control-label">Thời gian hết hiệu lực <span class="required">*</span></label>
                <div class="col-sm-6">
                    <input id="to" type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="valid_to" value='{{ BatvHelper::formatDate($data->valid_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)  }}' required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Link file đính kèm</label>
                <div class="col-sm-6">
                    <a href="{{ $data->link_dropbox }}" style="position: relative;top: 8px;" target="_blank">{{ $data->link_dropbox }}</a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Upload hóa đơn </label>
                <div class="col-sm-6">
                    <input type="file"  name="fileImage" id="fileImage">
                </div>
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
            </div>
            {{ csrf_field()}}
        </form>
    </div>
    <div class="col-lg-1"></div>
</div>
    <script type="text/javascript">
        $(document).ready(function(){
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

            $('.curency input[type=checkbox]').change(function() {
                $(".curency input:checked").each(function () {
                    $(".vnd input[id=numFormatResult]").attr("disabled", true);
                    $(".usd input[id=result_usd],.usd input[id=formatResult]").attr("required", true);
                    $(".usd").removeClass("hidden");
                });

                $(".curency input:not(:checked)").each(function () {
                    $(".vnd input[id=numFormatResult]").removeAttr("disabled");
                    $(".usd input[id=result_usd]").removeAttr("required");
                    $(".usd input[id=result_usd],.usd input[id=formatResult]").val('');
                    //$(".usd input[id=formatResult]").val('');
                    $(".usd").addClass("hidden");
                });
            });

            $('input[id=bypersonnel]').change(function() {
                if(document.getElementById('bypersonnel').checked){
                    $(".bypersonnel_notselect").remove();
                    var time = $('input[name="valid_from"]').val();
                    $.ajax({
                        type: "GET",
                        url: "{{route('getByPersonnelAjax')}}",
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
                        url: "{{route('getDefaultFundsAjax')}}",
                        // dataType: "json",
                        success: function(data){
                            $(".funds_default").html(data);
                        }
                    });
                }
            });
            $("input[id=formatResult]").keyup(function(){
                var usd = $('input[id="result_usd"]').val();
                var time = $('input[name="valid_from"]').val();
                $.ajax({

                    type: "GET",
                    url: "{{route('getCurencyAjax')}}",
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

                    var date = time.split("/");
                    var lastDay = new Date(date[2], date[1], 0);
                    $('input[name="valid_to"]').val( lastDay.getDate() + '/' + (lastDay.getMonth() + 1) + '/' +  lastDay.getFullYear() );
                    $.ajax({

                        type: "GET",
                        url: "{{route('getCurencyAjax')}}",
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
                        url: "{{route('getByPersonnelAjax')}}",
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
        });
    </script>
<?php
    if( $data->type==1 ){
?>
    <style type="text/css">
        .day_boxing{ display: block; }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#type').change(function() {
                if ($(this).val() === '1') {
                    $('#day_boxing input').prop('required',true);
                    $('.day_boxing').css("display", "block");
                }else{
                    $('.day_boxing').css("display", "none");
                    $('#day_boxing input').removeAttr('required');
                }
            });
        });
    </script>
<?php
    }else{
?>
    <style type="text/css">
        .day_boxing{ display: none; }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#type').change(function() {
                if ($(this).val() === '1') {
                    $('#day_boxing input').prop('required',true);
                    $('.day_boxing').css("display", "block");
                }else{
                    $('.day_boxing').css("display", "none");
                    $('#day_boxing input').removeAttr('required');
                }
            });
        });
    </script>
<?php
    }
?>

@endsection