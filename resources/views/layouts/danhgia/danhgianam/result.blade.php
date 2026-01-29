@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<?php
    $turns = ( date('m') >= 1 && date('m') <= 6 )? 1 : 2;
    $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y'):"đợt 2(tháng 12) năm ".date('Y');
    $year = date('Y');
   

    if( isset( $_GET['frequency'] ) ){
        if( date('m') >= 1 && date('m') <= 6 ){
            $turns = (  $_GET['frequency'] == 1 ) ? 2 : 1;
            $param = ( $turns == 1 )?"đợt 1(tháng 6) năm ".date('Y') : "đợt 2(tháng 12) năm ".date('Y', strtotime(date('Y').' -1 year'));
            $year = date('Y', strtotime(date('Y').' -1 year') );
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

    $time_calu = ( $turns == 1 ) ? $year.'-06' : $year.'-12';
?>
<style type="text/css">
    textarea{ height: auto !important; }
</style>
<div class="row content-Emonth">
    <!-- Danh muc -->
    @include('layouts.danhgia.menuleft.danhgianam')
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá nâng lương <i class="fa fa-angle-double-right" aria-hidden="true"></i> Tổng hợp kết quả đánh giá của nhân viên được xét nâng lương {{ $param }}</h4>
        @if (session('flash_message_err') != '')
            <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
        @endif
        @if (session('flash_message_succ') != '')
            <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
        @endif
        
        <?php
            if ( isset($error_special) && $error_special != ''){
        ?>
            <div class="alert alert-danger" role="alert"> <?php echo $error_special; ?></div>
        <?php
            }
        ?>
        <form  class="form-horizontal clearfix" method="GET">
            <div class="form-group col-sm-6">
                <label for="date" class="col-sm-4 control-label">Đợt xét :</label>
                <div class="col-sm-8">
                    <select name="frequency" class="form-control select2 wrap">
                        <option value="2" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 2)?"selected":""; ?> >{{ $time_after }}</option>
                        <option value="1" <?php echo ( isset( $_GET['frequency'] ) && $_GET['frequency'] == 1)?"selected":""; ?> >{{ $time_before }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-6">
                <label for="selectDepart" class="col-sm-4 control-label">Đơn vị</label>
                <div class="col-sm-8">  
                   <select name="selectDepart" id="department" class="form-control select2 wrap">
                        <option value="0"> -- Đơn vị -- </option>
                        {!! $department !!}
                    </select>
                    <script type="text/javascript">
                        var $select2 = $('.select2').select2({
                            containerCssClass: "wrap"
                        })
                    </script>
                </div>
            </div>
            <div class="text-center" style="margin-bottom:15px">
                <input type="submit" class="btn btn-sm btn-orange" name="search" value="Tìm kiếm">
            </div>
            {{ csrf_field()}}
        </form>
        <div class="detail">
            <div class="text-center">
                <form action="" method="post">
                    <div class="table-responsive">
                        <table class="evaluation table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 18%;">Tên nhân viên</th>
                                    <th class="text-center">H/s trước</th>
                                    <th class="text-center">H/s đề xuất</th>
                                    <th class="text-center">Mức tăng H/s</th>
                                    <th class="text-center">Điểm NVTĐG</th>
                                    <th class="text-center">Điểm QLĐG</th>
                                    <th class="text-center">Kết quả</th>
                                    <th class="text-center">Thay đổi phụ cấp</th>
                                    <th class="text-center">Số tháng từ t/đ LGN</th>
                                    <th class="text-center">Số tháng TL</th>
                                    <th class="text-center" style="width: 7%;">Loại xét</th>
                                    <th class="text-center" style="width: 165px"></th>
                                </tr>
                            </thead>
                            <tbody> 
                            <?php
                                // echo "<pre>";
                                // print_r($data);die;
                            ?>
                                @if( $data )
                                    @foreach ($data as $val)
                                        <?php
                                            $salary_before = BatvHelper::ltt('',$val[
                                        'personnel_id'],$time_calu,$type=1,'',$option=1,$val['ratio_before'] );

                                            $salary_after = 0;
                                            $salary_sub = 0;
                                            $hs_sub = 0;

                                            if ($val['ratio_propose'] > 0) {
                                                $salary_after = BatvHelper::ltt('',$val[
                                            'personnel_id'],$time_calu,$type=1,'',$option=1,$val['ratio_propose'] );
                                                $salary_sub = $salary_after - $salary_before;
                                                $hs_sub = $val['ratio_propose'] - $val['ratio_before'];
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-left">
                                                <a href="#" data-toggle="modal" data-target="#myModal_view{{ $val['personnel_id'] }}">{{ str_limit( $val['fullname'], $limit = 35, $end = '...') }}</a>
                                                <div id="myModal_view{{ $val['personnel_id'] }}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content clearfix">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title text-center">Xem chi tiết</h4>
                                                                <div class="ajax_response text-center" style="display: none;"></div>
                                                            </div>
                                                            <div style="padding: 20px;">
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Họ và tên : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ $val['first_name'].' '.$val['last_name'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Giới tính : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        @if ($val['gender'] == 1)
                                                                            {{ 'Nam' }}
                                                                        @else
                                                                            {{ 'Nữ' }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if( $val['birthday'] != NULL )
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Ngày sinh : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ BatvHelper::formatDate($val['birthday'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                                                    </div>
                                                                </div>
                                                                @endif  
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Thâm niên : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ BatvHelper::getSeniority($val['personnel_id']) }} 
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Điện thoại : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ $val['phone_number'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Số chứng minh thư : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ $val['indentity_card_id'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Quỹ : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{  BatvHelper::getInfoFundsbyPersonnel( $val['personnel_id'] ) }}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Giờ chấm công đi làm : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ $val['time_attendance_machine'] }}
                                                                    </div>
                                                                </div>
                                                                @if( $val['date_in'] != NULL )
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Ngày vào công ty : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ BatvHelper::formatDate($val['date_in'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                                                    </div>
                                                                </div>
                                                                @endif

                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Chu kỳ xét tăng lương : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{  ( $val['salary_frequency'] > 0 )?$val['salary_frequency'].' năm':' Không được xét ' }}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Loại hợp đồng : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <?php
                                                                            $contracts = BatvHelper::getContracts($val['personnel_id']);
                                                                        ?>
                                                                        @if( $contracts )
                                                                            @foreach( $contracts as $k_contract => $v_contract )
                                                                                {{ $v_contract->title.': '.BatvHelper::formatDate($v_contract->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false).' - '.BatvHelper::formatDate($v_contract->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)}} </br>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Đơn vị : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ $val['title'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Mức lương cơ bản đóng bảo hiểm : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ BatvHelper::formatPriceSpecial($val['insurrance']) }} VNĐ
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-4">
                                                                        <b>Quê quán : </b>  
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        {{ $val['address'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>{{ $val['ratio_before'] }}</td>
                                            <td>{{ $val['ratio_propose'] }}</td>
                                            <td>{{ $hs_sub }}</td>
                                            <td>{{ $val['detail_total_point_personnel'] }}</td>
                                            <td>{{ $val['detail_total_point_manager'] }}</td>
                                            <td>
                                                @if ($val['detail_total_point_personnel'] == $val['detail_total_point_manager'])
                                                    {{ $val['result_final'] }}
                                                @elseif($val['detail_total_point_personnel'] > $val['detail_total_point_manager'])
                                                    <span style="color:red">{{ $val['result_final'] }}</span>
                                                @else
                                                    <span style="color:green">{{ $val['result_final'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($val['management_allowance'] > 0)
                                                    Từ {{ BatvHelper::formatPrice($val['management_allowance_old']) }} => {{ BatvHelper::formatPrice($val['management_allowance']) }}
                                                @endif
                                            </td>
                                            <td>{{ $val['number_month_nlgn'] }}</td>
                                            <td>{{ $val['number_month_TL'] }}</td>
                                            <td>
                                                @if( $val['type'] == 1 )
                                                    <span>Đ.kỳ</span>
                                                @else
                                                    <span style="color: green;">Đ.xuất</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if( in_array('danhgia-danhgiachitiet',$arr_route) && $val['options'] != 1 )
                                                    <a href="{{ route('getEvaluationYearbyManager',[$val['personnel_id'],$year,$turns]) }}" target="_blank" class="btn btn-xs btn-orange" >Đánh giá</i></a>
                                                @endif
                                                
                                                @if( $val['options'] >= 1 )
                                                    <a href="#" class="daduyet" data-toggle="modal" data-target='#myModal{{ $val["personnel_id"] }}'>Đã duyệt</a>
                                                @else
                                                    @if( Auth::user()->id == 1 || Auth::user()->id == 112 )
                                                        <a href="#" class="btn btn-xs btn-orange" data-toggle="modal" data-target='#myModal{{ $val["personnel_id"] }}' style="text-decoration: none;">Phê duyệt</a>
                                                    @endif
                                                @endif
                                                <!-- POPUP nhận xét của TGD-->
                                                <div id="myModal{{ $val['personnel_id'] }}" class="modal fade" role="dialog">
                                                  <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title text-center">Tên nhân viên: {{ BatvHelper::getInfoUser($val['personnel_id']) }}</h4>
                                                            <div class="ajax_response text-center" style="display: none;"></div>
                                                        </div>
                                                        <form class="form-horizontal" id="myModal{{ $val['personnel_id'] }}">
                                                            {!! csrf_field()!!}
                                                            <div class="modal-body">
                                                                <div class="row form-group clearfix">
                                                                    <label class="col-sm-2 control-label" style="text-align: left;">Thông tin nhân viên</label>
                                                                    <div class="col-sm-3 info_management_allowance{{ $val['personnel_id'] }}">
                                                                        <table class="table table-bordered">
                                                                          <tbody>
                                                                            <tr>
                                                                              <th colspan="2" class="text-center">Thông tin chung</th>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Ngày sinh</td>
                                                                              <td>
                                                                                {{ BatvHelper::formatDate($val['birthday'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Chu kỳ xét</td>
                                                                              <td>{{  ( $val['salary_frequency'] > 0 )?$val['salary_frequency'].' năm':' Không được xét ' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Đơn vị</td>
                                                                              <td>{{ $val['title'] }}</td>
                                                                            </tr>
                                                                          </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-sm-4 info_salary{{ $val['personnel_id'] }}">
                                                                        <table class="table table-bordered">
                                                                          <tbody>
                                                                            <tr>
                                                                              <th colspan="3" class="text-center">Thông tin lương</th>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Hệ số cũ</td>
                                                                              <td>{{ $val['ratio_before'] }}</td>
                                                                              <td>{{ BatvHelper::formatPrice($salary_before) }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Hệ số mới</td>
                                                                              <td>{{ $val['ratio_propose'] }}</td>
                                                                              <td>{{ BatvHelper::formatPrice($salary_after) }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Mức tăng</td>
                                                                              <td>{{ $hs_sub }}</td>
                                                                              <td>{{ BatvHelper::formatPrice($salary_sub) }}</td>
                                                                            </tr>
                                                                          </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-sm-3 info_management_allowance{{ $val['personnel_id'] }}">
                                                                        <table class="table table-bordered">
                                                                          <tbody>
                                                                            <tr>
                                                                              <th colspan="2" class="text-center">Thông tin phụ cấp</th>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Mức hiện tại</td>
                                                                              <td>
                                                                              {{ BatvHelper::formatPrice($val['management_allowance_old'])}}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td>Mức đề xuất</td>
                                                                              <td>{{ BatvHelper::formatPrice($val['management_allowance']) }}</td>
                                                                            </tr>
                                                                          </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group clearfix">
                                                                    <label class="col-sm-2 control-label" style="text-align: left;">Nhận xét gửi NV</label>
                                                                    <div class="col-sm-10">
                                                                        <textarea class="form-control"  data-autoresize  rows="6" disabled >{{ $val['comment_send_personnel'] }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group clearfix">
                                                                    <label class="col-sm-2 control-label" style="text-align: left;">Nhận xét thêm hoặc các chú ý gửi BGĐ </label>
                                                                    <div class="col-sm-10">
                                                                        <textarea class="form-control" data-autoresize rows="6" disabled>{{ $val['comment_send_manager'] }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row form-group clearfix">
                                                                    <label class="col-sm-2 control-label" style="text-align: left;">Nhận xét của TGĐ </label>
                                                                    <div class="col-sm-10">
                                                                        <textarea class="form-control" data-autoresize rows="6" name="comment_manager_final{{ $val['personnel_id'] }}">{{ $val['comment_manager_final'] }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div id="pre_ajax_loading" class="hide" style="text-align: center;margin-bottom: 10px;"><img src="{{ asset('images/general/bx_loader.gif') }}"></div>
                                                                @if( $val['ratio_propose'] > 0 )
                                                                    @if( $val['options'] >= 1 )
                                                                        <span class="daduyet">Đã duyệt</span>
                                                                    @else
                                                                        @if( Auth::user()->id == 1 || Auth::user()->id == 112 )
                                                                            <button type="button" class="btn btn-sm btn-orange" onclick="updateData({{ $val['personnel_id'] }})">Phê duyệt</button>
                                                                            <input type="hidden" name="ratio_propose{{ $val['personnel_id'] }}" value="{{ $val['ratio_propose'] }}">
                                                                            <div class="ajax_response {{ $val['personnel_id'] }}" style="display: none;margin: 10px 0px;padding: 6px 0px;"></div>
                                                                        @endif

                                                                    @endif
                                                                @else
                                                                    <span style="color: red;font-weight: 600;font-style: italic;">Chưa có thông tin đề xuất hệ số lương nên bạn không thể phê duyệt được</span>
                                                                @endif  
                                                            </div>
                                                        </form>
                                                    </div>
                                                  </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ csrf_field()}}
                </form>
                @if ((Auth::user()->id == 1 || Auth::user()->id == 112) && isset($_GET['selectDepart']) && $_GET['selectDepart'] > 17 && $button_duyet == true)
                    <div class="form-group col-lg-12 text-center mt-3">
                        <input type="button" class="btn btn-sm btn-orange" onclick="updateDataFilter()" name="search" value="Phê duyệt">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    arr_personnel_id = [<?php echo '"'.implode('","', $list_personnel_id).'"' ?>];

    function updateDataFilter() {
        Swal.fire({
            text: "Bạn có chắc chắn muốn phê duyệt tất cả theo thông tin lọc được chọn?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.value) {
                var flag_status = 0;
        
                for(i = 0; i < arr_personnel_id.length; i++) {
                    var id = arr_personnel_id[i];
                    var ratio_propose = $('input[name=ratio_propose'+id+']').val();
                    var comment_manager_final = $('textarea[name=comment_manager_final'+id+']').val();
                    var year = $("select[name=frequency] option:selected").text();
                    year = year.split("/");
                    year = year[1];
                    var param = {
                                    id : id,
                                    ratio_propose : ratio_propose,
                                    comment_manager_final:comment_manager_final,
                                    year:parseInt(year),
                                    info_salary : $('.info_salary'+id).html(),
                                    info_management_allowance : $('.info_management_allowance'+id).html(),
                                };
                    $.ajaxSetup(
                    {
                        headers:
                        {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
        
                    $.ajax({
                        method: "POST",
                        url: "{{ route('approvalSalaryAjax')}}",
                        data: param,
                        beforeSend: function() {
                            $(".ajax_waiting").addClass("loading");
                        },
                        success: function (response) {
                            flag_status++;
        
                            if (flag_status == arr_personnel_id.length) {
                                $(".ajax_waiting").removeClass("loading");
        
                                Swal.fire({
                                    type: "success",
                                    html: 'Bạn đã phê duyệt thành công',
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if(result.value){
                                        location.reload();
                                    }
                                })
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    })
                }
            }
        })
    }

   function updateData(id){
        var id = id;
        var ratio_propose = $('input[name=ratio_propose'+id+']').val();
        var comment_manager_final = $('textarea[name=comment_manager_final'+id+']').val();
        var year = $("select[name=frequency] option:selected").text();
        year = year.split("/");
        year = year[1];
        var param = {
                        id : id,
                        ratio_propose : ratio_propose,
                        comment_manager_final:comment_manager_final,
                        year:parseInt(year),
                        info_salary : $('.info_salary'+id).html(),
                        info_management_allowance : $('.info_management_allowance'+id).html(),
                    };
         $.ajaxSetup(
        {
            headers:
            {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            method: "POST",
            url: "{{ route('approvalSalaryAjax')}}",
            data: param,
            beforeSend: function() {
                $("div#pre_ajax_loading").removeClass("hide");
            },
            complete: function() {
                $("div#pre_ajax_loading").addClass("hide");
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
                // setTimeout(function() {
                //     $(".ajax_response."+personnel_id).fadeOut( "slow" );
                // }, 3000);

            },
            error: function (data) {
                console.log('Error:', data);
            }
        })
   }
</script>
@endsection