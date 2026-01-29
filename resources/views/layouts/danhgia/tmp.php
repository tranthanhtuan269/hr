@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<div class="row content-Emonth">
    <div class="col-sm-offset-2 col-sm-8">
        <h4 class="title-fuction">Đánh giá KPI tháng</h4>
        <div class="detail">
            <div class="text-center">
                <form>
                    <div class="row">
                        <div class="col-sm-6 radio radio-primary">
                            <input type="radio" name="point" checked="checked" value="1" id="radio1">
                            <label for="radio1">Tự đánh giá</label>
                        </div>
                        <div class="col-sm-6 radio radio-primary">
                            <input type="radio" name="point" value="2" id="radio2">
                            <label for="radio2">Đánh giá nhân viên trực thuộc</label>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center">Tiêu chí</th>
                                    <th class="text-center">Điểm đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiêu chí 1</td>
                                    <td>
                                        <select>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tiêu chí 2</td>
                                    <td>
                                        <select>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tiêu chí 3</td>
                                    <td>
                                        <select>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered managerEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center">Danh sách nhân viên trực thuộc</th>
                                    <th class="text-center">Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nhân viên 1</td>
                                    <td>
                                        <a href="">Đánh giá</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nhân viên 2</td>
                                    <td>
                                        <a href="">Đánh giá</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nhân viên 3</td>
                                    <td>
                                        <a href="">Đánh giá</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
                        <button type="reset" class="btn btn-sm btn-grey">Hủy bỏ</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
 <script type="text/javascript">
    
    jQuery(document).ready(function(){
        //$('input[type="radio"]').click(function(){
        $('input[name="typeWork"]').on("click",function(){
            var val = $("input[type='radio']:checked").val();
            $.ajax({
              method: "GET",
              url: "{{ route('getAttendanceTypeAjax')}}",
              data: "typeRadio=" + val,
              dataType: "json",
            }).done(function( msg ) {
                $xhtml = '';
                $.each(msg, function (index, value) {
                      $xhtml +=  '<option value="'+value.id+'">'+value.symbol+'</option>';
                      // Will stop running after "three"
                });
                $('.select_type').html($xhtml);
            });
        });
    });

 </script>
<script type="text/javascript">

jQuery(document).ready(function(){
    //$('input[type="radio"]').click(function(){
    $('.content-Emonth #radio2').on("click",function(){
        $('.selfEvaluation').hide();
        $('.managerEvaluation').show();
    });
    $('.content-Emonth #radio1').on("click",function(){
        $('.selfEvaluation').show();
        $('.managerEvaluation').hide();
    });

});

</script>
@endsection