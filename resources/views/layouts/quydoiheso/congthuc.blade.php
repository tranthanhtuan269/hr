@extends('layouts.master')
@section('title', 'Quy đổi hệ số lương')
@section('content')
<div class="row box_convert">
    <div class="col-sm-offset-2 col-sm-8">
        <h4 class="title-fuction">Quy đổi hệ số chức danh</h4>
        @if (session('flash_message_err') != '')
          <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
         @endif
        <div class="detail">
            <div class="">
                <form action="" method="post">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label>Chọn cơ chế chuyển đổi : </label>
                            <label class="radio-inline">
                              <input type="radio" name="convert" value="1" id="convert1"> Hệ số => VNĐ
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="convert" value="2" id="convert2"> VNĐ => Hệ số
                            </label>
                        </div>
                        <div class="form-group col-lg-offset-2 col-lg-4">
                            <input type="text" name="heso" class="form-control" > <span>Hệ số</span>
                        </div>
                        <div class="form-group col-lg-1"><i class="fa fa-exchange" aria-hidden="true"></i></div>
                        <div class="form-group col-lg-4">
                            <input type="text" onkeyup="format_curency( this.value );" id="numFormatResult" class="form-control" value="" ><span>VNĐ</span>
                            <input type="hidden" name="result" id="result">
                            <!-- <input type="text" name="result" class="form-control"><span>VNĐ</span> -->
                        </div>
<!--                         <div class="form-group col-lg-12 text-center">
                            <input type="checkbox" value="1" name="phucap" id="phucap"> Bao gồm phụ cấp
                        </div> -->
                        <div class="form-group col-lg-12 text-center">
                            <button type="button" class="btn btn-sm btn-orange" name="save">Chuyển đổi</button>
                            <button type="reset" class="btn btn-sm btn-grey" id="reset">Nhập lại</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="evaluation table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th>Chi tiết tính toán</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td>Mô tả cách tính toán chi tiết ở đây......</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    {{ csrf_field()}}
                </form>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function numberFormat(nStr){
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
      return x1 + x2;
    }

    // This function removes non-numeric characters
    function stripNonNumeric( str ){
        str += '';
        var rgx = /^\d|\.|-$/;
        var out = '';
        for( var i = 0; i < str.length; i++ ){
            if( rgx.test( str.charAt(i) ) ){
              if( !( ( str.charAt(i) == '.' && out.indexOf( '.' ) != -1 ) ||
                     ( str.charAt(i) == '-' && out.length != 0 ) ) ){
                out += str.charAt(i);
              }
            }
        }
        return out;
    }
   $('button[name="save"]').on("click",function(){
        var convert = $('input[name=convert]:checked').val();
        var heso = $('input[name=heso]').val();
        var result = $('input[name=result]').val();
        var phucap = $('input[name=phucap]:checked ').val();
        // alert(phucap);
        var param = {
                        convert : convert,
                        heso : heso,
                        result : result,
                        phucap : phucap,
                    };
        $.ajax({
            method: "GET",
            url: "{{ route('getConvertAjax')}}",
            data: param,
            // dataType: "json",
            success: function (response) {
                var obj = $.parseJSON(response);
                if(obj.Response=='Success_ltn')
                {
                    $('input[id=numFormatResult]').val(numberFormat(obj.ltn));
                    $('input[name=result]').val(obj.ltn);
                }else if(obj.Response=='Success_param'){
                    $('input[name=heso]').val(obj.param);
                }else{
                    alert(obj.Err);
                }

            },
            error: function (data) {
                console.log('Error:', data);
            }
        })
    $(document).ready(function(){
        $('input[name="convert"]').click(function(){
           // $('input[name="result"]').val('');
           $('input[name="heso"]').val('');
           $('#phucap').removeAttr('checked');
        });

        $("#reset").click(function(){
           $('input[name="result"]').attr('value','');
           $('input[name="heso"]').attr('value','');
           $('#phucap').removeAttr('checked');
           $('#convert').removeAttr('checked');
        });
    });
   }); 
        
</script>
@endsection

