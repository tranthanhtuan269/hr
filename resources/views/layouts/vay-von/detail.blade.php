@extends('layouts.master')

@section('title', 'Tín dụng')

@section('content')
    <style>
        #ui-datepicker-div{
            z-index: 9999 !important;
        }
    </style>
    <div class="row overtime" style="min-height:500px;">
        <div class="col-lg-2">
            @include('layouts.vay-von.menu')
        </div>
        <div class="col-lg-10">
            <h4 class="title-fuction">
                Thông tin nhân viên
            </h4>
            <table class="table table-bordered table-responsive">
                <thead>
                  <tr>
                    <th class="text-center">Họ và tên</th>
                    <th class="text-center">Hình thức giải ngân</th>
                    <th class="text-center">Thông tin tài khoản nhận giải ngân</th>
                    <th class="text-center">Hình thức trả</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center">{{ $detail_loan_capital[0]->fullname }}</td>
                    <td class="text-center">
                        @if ($detail_loan_capital[0]->disbursement_form == 1)
                            Giải ngân trực tiếp tới đơn vị thụ hưởng
                        @else
                            Giải ngân tới người vay
                        @endif
                    </td>
                    <td>
                        <?php $info_receive_disbursement = $detail_loan_capital[0]->info_receive_disbursement; ?>

                        @if ($info_receive_disbursement  != '')
                            <textarea style="border:none">{!! $info_receive_disbursement !!}</textarea>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($detail_loan_capital[0]->pay == 1)
                            Trừ vào lương
                        @else
                            Tự trả qua chuyển khoản
                        @endif
                    </td>
                  </tr>
                </tbody>
              </table>

            <h4 class="title-fuction">
                Thông tin khoản vay
                <div class="pull-right" style="position: relative;bottom:5px;right:10px">
                    {{-- @if ($detail_loan_capital[count($detail_loan_capital) - 1]->status == 1 && $detail_loan_capital[0]->loan_capital_status != 4)
                        <button type="button" class="btn btn-sm btn-warning" onclick="doneMonthLoanCapital()">Hoàn tất</button>
                    @endif --}}

                    @if ($detail_loan_capital[0]->loan_capital_status == 4 )
                        <button type="button" class="btn btn-sm btn-success" style="background-color: #449d44; border:none;cursor: inherit">Đã hoàn tất thanh toán</button>
                    @endif
                </div>
            </h4>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th class="text-center">Số tiền vay</th>
                        <th class="text-center">Ngày <br>giải ngân</th>
                        <th class="text-center">Ngày n/v <br>bắt đầu trả</th>
                        <th class="text-center">Ngày <br>kết thúc</th>
                        <th class="text-center">L/s<br> (% năm)</th>
                        <th class="text-center">L/s ưu đãi <br> (% năm)</th>
                        <th class="text-center">Số tháng <br> ưu đãi</th>
                        <th class="text-center">L/s trả chậm<br> (% năm)</th>
                        <th class="text-center">L/s nếu không hoàn thiện hồ sơ (% năm)</th>
                        <th class="text-center">Tổng <br>tiền lãi</th>
                        <th class="text-center">Phạt trả chậm</th>
                        <th class="text-center">Đã trả</th>
                        <th class="text-center">Còn lại</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_paid_money_1 = $total_interest_1 = $total_interest_incurred_1 = 0; 
                        $loan_capital_status = $detail_loan_capital[0]->loan_capital_status;
                    ?>

                    @foreach ($detail_loan_capital as $value)
                        <?php 
                            $total_paid_money_1 += $value->paid_money; 
                            $total_interest_1 += $value->interest;
                            $total_interest_incurred_1 += $value->interest_incurred;
                        ?>
                    @endforeach
                    <td class="text-center">{{ number_format($detail_loan_capital[0]->remaining_principal) }}</td>
                    <td class="text-center">{{ BatvHelper::formatDate($detail_loan_capital[0]->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false) }}</td>
                    <td class="text-center">{{ BatvHelper::formatDate($detail_loan_capital[1]->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false) }}</td>
                    <td class="text-center">{{ BatvHelper::formatDate($detail_loan_capital[count($detail_loan_capital) - 1]->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false) }}</td>
                    <td class="text-center">{{ $config_loan_capital->interest_rate }}</td>
                    <td class="text-center">{{ $config_loan_capital->preferential_interest_rate }}</td>
                    <td class="text-center">{{ $config_loan_capital->count_month_preferential }}</td>
                    <td class="text-center">{{ $config_loan_capital->deferred_interest }}</td>
                    <td class="text-center">{{ $config_loan_capital->interest_file_late }}</td>
                    <td class="text-center">{{ number_format($total_interest_1) }}</td>
                    <td class="text-center">{{ number_format($total_interest_incurred_1) }}</td>
                    <td class="text-center">{{ number_format($total_paid_money_1) }}</td>
                    <td class="text-center">
                        <?php
                            $all_rest_1 = $detail_loan_capital[0]->remaining_principal + $total_interest_1 + $total_interest_incurred_1 - $total_paid_money_1;
                            $all_rest_1 = ($all_rest_1 <= 0 && $all_rest_1 >= -0.99) ? 0 : $all_rest_1;
                        ?>
                        {{ number_format($all_rest_1 ) }}
                    </td>
                </tbody>
            </table>
            <h4 class="title-fuction">
                Lịch trả nợ
                <?php
                    $final_settlement = $detail_loan_capital[0]->final_settlement;
                    $partial_settlement = $detail_loan_capital[0]->partial_settlement;
                ?>
                @if ($loan_capital_status != 4 && $user_id == 1)
                    <div class="pull-right" style="position: relative;bottom:5px;right:10px">
                        @if ($final_settlement == 1)
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#approvedFinalSettlement">Phê duyệt yêu cầu trả sớm toàn bộ của nhân viên</button>
                        @elseif($partial_settlement == 1)
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#approvedPartialSettlement">Phê duyệt yêu cầu trả sớm một phần của nhân viên</button>
                        @endif
                    </div>
                @endif

            </h4>
            <?php
                $total_remaining_principal = 0;
                $total_principal = 0;
                $total_wanting_month_prev_money = 0;
                $total_redundancy_month_prev_money = 0;
                $total_interest = 0;
                $total_interest_incurred = 0;
                $total_tmp_all = 0;
                $total_tmp = 0;
                $total_real = 0;
                $total_real_all  = 0;
                $total_paid_money = 0;
                $flag = true;
                $pay = $detail_loan_capital[0]->pay;
                $received_date_user = '';
            ?>
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" colspan="2">Kỳ trả nợ</th>
                    <th class="text-center">Số gốc còn lại</th>
                    <th class="text-center">Gốc</th>
                    <th class="text-center">Tiền thiếu tháng trước</th>
                    <th class="text-center">Tiền dư tháng trước</th>
                    <th class="text-center">Lãi</th>
                    <th class="text-center">Phạt trả chậm</th>
                    <th class="text-center">Tổng gốc + Lãi + Phạt trả chậm</th>
                    <th class="text-center">Số tiền thực n/v phải trả trong tháng</th>
                    <th class="text-center">Số tiền n/v đã thanh toán</th>
                    <th class="text-center">Ngày trả</th>
                    @if ($loan_capital_status != 4 && $pay == 2)
                        <th class="text-center">Trả nợ</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                    <?php $price_final_settlement = 0; ?>
                    @foreach ($detail_loan_capital as $key => $item)
                        <tr @if ($item->status == 1) style="background:#449d44;color:#fff" @endif>
                            <?php
                                $total_principal += $item->principal;
                                // $total_wanting_month_prev_money += $item->wanting_month_prev_money;
                                // $total_redundancy_month_prev_money += $item->redundancy_month_prev_money;
                                $total_interest += $item->interest;
                                $total_interest_incurred += $item->interest_incurred;
                                $total_real = $item->principal + $item->interest + $item->interest_incurred;
                                $total_real_all += $total_real;
                                $total_tmp = $item->principal + $item->interest + $item->interest_incurred + $item->wanting_month_prev_money - $item->redundancy_month_prev_money;
                                $total_tmp = ($total_tmp > 0) ? $total_tmp : 0;
                                $total_tmp_all += $total_tmp;
                                $total_paid_money += $item->paid_money;
                                $repayment_period = BatvHelper::formatDate($item->repayment_period,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',false);
                            ?>
                            <td class="text-center">{{ $repayment_period }}</td>
                            <td class="text-center">{{ $item->month }}</td>
                            <td class="text-center">
                                {{ number_format(round($item->remaining_principal)) }}
                            </td>
                            <td class="text-center">{{ number_format($item->principal) }}</td>
                            <td class="text-center">{{ number_format($item->wanting_month_prev_money) }}</td>
                            <td class="text-center">{{ number_format($item->redundancy_month_prev_money) }}</td>
                            <td class="text-center">{{ number_format($item->interest) }}</td>
                            <td class="text-center">{{ number_format($item->interest_incurred) }}</td>
                            <td class="text-center">{{ number_format($total_real) }}</td>
                            <td class="text-center">{{ number_format($total_tmp) }}</td>
                            <td class="text-center">{{ number_format($item->paid_money) }}</td>
                            <td class="text-center">{{ $item->received_date }}</td>
                            @if ($loan_capital_status != 4 && $pay == 2)
                                <td class="text-center">
                                    @if ($item->month > 0 && $item->status == 0 && $flag)
                                        @if(in_array('quan-ly-tra-no-dinh-ky',$arr_route))
                                            @if (($final_settlement == 0 || $final_settlement == 2 ) && ($partial_settlement == 0 || $partial_settlement == 2))
                                                <?php
                                                    $received_date_user = $item->received_date_user;
                                                ?>
                                                <button data-month='{{ $item->month }}' data-repayment_period='{{ $repayment_period }}' data-paid_money='{{ $total_tmp }}' type="button" class="btn-xs btn btn-primary btn-approvedMonthLoanCapital" data-toggle="modal" data-target="#approvedMonthLoanCapital">Cập nhật</button>
                                            @endif
                                            <?php 
                                                $flag = false;
                                                $price_final_settlement = $item->remaining_principal +  $item->principal  -  $item->redundancy_month_prev_money + $item->wanting_month_prev_money + $item->interest + $item->interest_incurred - $item->paid_money;
                                            ?>
                                        @endif
                                    @endif
                                </td>
                            @else
                                @if ($key > 0 && $item->status == 0 && $flag)
                                    <?php 
                                        $flag = false;
                                        $price_final_settlement = $item->remaining_principal +  $item->principal  -  $item->redundancy_month_prev_money + $item->wanting_month_prev_money + $item->interest + $item->interest_incurred - $item->paid_money;
                                    ?>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                        <tr>
                            <td class="text-center"><b>TỔNG</b></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"><b></b></td>
                            <td class="text-center"><b></b></td>
                            <td class="text-center"><b></b></td>
                            <td class="text-center"><b>{{ number_format($total_interest) }}</b></td>
                            <td class="text-center"><b>{{ number_format($total_interest_incurred) }}</b></td>
                            <td class="text-center"><b></b></td>
                            <td></td>
                            {{-- <td class="text-center"><b>{{ number_format($total_tmp_all) }}</b></td> --}}
                            <td class="text-center"><b>{{ number_format($total_paid_money) }}</b></td>
                            <td class="text-center"></td>
                            @if ($loan_capital_status != 4 && $pay == 2)
                                <td class="text-center"></td>
                            @endif
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="approvedMonthLoanCapital" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Nhân viên chuyển khoản:</label>
                            <input data-type="currency" class="form-control input-sm" name="paid_money" type="text" value="">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Ngày nhận tiền:</label>
                            <input type="text" class="form-control input-sm"  name="received_date"  pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ $received_date_user }}" autocomplete="off">
                            <script>
                                $(function() {
                                    $( "#approvedMonthLoanCapital input[name=received_date]" ).datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: "2019:2050",
                                            dateFormat: 'dd/mm/yy',
                                        }   
                                    );
                                });
                            </script>
                        </div>
                        {{-- <div class="col-sm-12 form-group">
                            <label>Hình thức trả:</label><br>
                            <label class="radio-inline"><input type="radio" name="type" checked value="0">Trả tiền định kỳ</label>
                            <label class="radio-inline"><input type="radio" name="type" value="1">Trả tiền định kỳ +  tiền gốc</label>
                            <div style="color:red;margin-top:10px">
                                <span class="type_0">Trả tiền định kỳ hàng tháng, bao gồm tiền gốc tháng + lãi tháng + tiến thiếu tháng trước (nếu có) + phạt tiền chậm (nếu có).</span>
                                <span class="type_1 hidden">Trả tiền định kỳ hàng tháng + trả tiền gốc (1 phần hoặc tất cả).</span>
                                <script>
                                    $('#approvedMonthLoanCapital input[type=radio][name=type]').change(function() {
                                        if (this.value == 0) {
                                            $('.type_0').removeClass('hidden');
                                            $('.type_1').addClass('hidden');
                                        } else {
                                            $('.type_0').addClass('hidden');
                                            $('.type_1').removeClass('hidden');
                                        }
                                    });
                                </script>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="month">
                    <button type="button" class="btn btn-primary" onclick="approvedMonthLoanCapital()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
    <div id="approvedFinalSettlement" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Phê duyệt yêu cầu trả sớm toàn bộ của nhân viên</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>Số tiền còn lại n/v phải trả:</label>
                            <div><span style="color:red;font-size:17px;font-weight:600" id="price_final_settlement"></span> VNĐ</div>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Nhân viên chuyển khoản:</label>
                            <input data-type="currency" class="form-control input-sm" name="paid_money" type="text" value="">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Ngày nhận tiền:</label>
                            <input type="text" class="form-control input-sm"  name="received_date"  pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ $detail_loan_capital[0]->final_settlement_date_user }}" autocomplete="off">
                            <script>
                                $(function() {
                                    $( "#approvedFinalSettlement input[name=received_date]" ).datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: "2019:2050",
                                            dateFormat: 'dd/mm/yy',
                                        }   
                                    );
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="approvedFinalSettlement()">Phê duyệt</button>
                </div>
            </div>
        </div>
    </div>
    <div id="approvedPartialSettlement" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Phê duyệt yêu cầu trả sớm một phần của nhân viên</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>Nhân viên chuyển khoản:</label>
                            <input data-type="currency" class="form-control input-sm" name="paid_money" type="text" value="">
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Ngày nhận tiền:</label>
                            <input type="text" class="form-control input-sm"  name="received_date"  pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ $detail_loan_capital[0]->partial_settlement_date_user }}" autocomplete="off">
                            <script>
                                $(function() {
                                    $( "#approvedPartialSettlement input[name=received_date]" ).datepicker({
                                            changeMonth: true,
                                            changeYear: true,
                                            yearRange: "2019:2050",
                                            dateFormat: 'dd/mm/yy',
                                        }   
                                    );
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="approvedPartialSettlement()">Phê duyệt</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click','.btn-approvedMonthLoanCapital',function(){
            $('#approvedMonthLoanCapital input[name=type]').filter('[value=0]').prop('checked', true);
            $('#approvedMonthLoanCapital .type_0').removeClass('hidden');
            $('#approvedMonthLoanCapital .type_1').addClass('hidden');
            var paid_money = $(this).attr('data-paid_money');
            var repayment_period = $(this).attr('data-repayment_period');
            var month = $(this).attr('data-month');
            $('#approvedMonthLoanCapital .modal-title').html("Kỳ trả nợ: " + repayment_period);
            paid_money = formatNumber(parseFloat(paid_money).toFixed(), '.', ',');
            $('#approvedMonthLoanCapital input[name=paid_money]').val(paid_money.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
            $('#approvedMonthLoanCapital input[name=month]').val(month);
            $('#approvedMonthLoanCapital input[name=received_date]').val('{{ $received_date_user }}');
        });



        $('#approvedFinalSettlement').on('shown.bs.modal', function (e) {
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        loan_capital_id : "{{ request()->route('loan_capital_id') }}",
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/money-final-settlement") }}',
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
                        $('#approvedFinalSettlement input[name=paid_money]').val(formatNumber(response.total_price_real, '.', ','));
                        $('#approvedFinalSettlement input[name=received_date]').val('{{ $detail_loan_capital[0]->final_settlement_date_user }}');
                        $('#price_final_settlement').html(formatNumber(response.total_price_real, '.', ','));
                    }
                },
                error: function (error) {
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                    })
                }
            });
        })

        $('#approvedPartialSettlement').on('hidden.bs.modal', function (e) {
            $('#approvedPartialSettlement input[name=paid_money]').val('')
            $('#approvedPartialSettlement input[name=received_date]').val('{{ $detail_loan_capital[0]->partial_settlement_date_user }}')
        })

        function approvedMonthLoanCapital(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var paid_money = $("#approvedMonthLoanCapital input[name=paid_money]").val().replace(/,/g,'');
            // var type = $("#approvedMonthLoanCapital input[name=type]:checked").val();
            var month = $("#approvedMonthLoanCapital input[name=month]").val();
            var received_date = $("#approvedMonthLoanCapital input[name=received_date]").val();

            if (paid_money == '') {
                Swal.fire({
                    type: 'warning',
                    html: 'Xin vui lòng nhập số tiền',
                    allowOutsideClick: false
                })
                return;
            }

            if (received_date == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày nhân tiền!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(received_date)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày nhân tiền hợp lệ!",
                        allowOutsideClick: false
                    })

                    return;
                }
            }

            var data = {
                        received_date : $.datepicker.formatDate('yy-mm-dd', $("#approvedMonthLoanCapital input[name=received_date]").datepicker('getDate')),
                        paid_money : paid_money,
                        loan_capital_id : "{{ request()->route('loan_capital_id') }}",
                        // type : type,
                        month : month,
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/approved-pay-month-loan-capital") }}',
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
                        var obj_errors = response.message;
                        var txt_errors = '';
                        for (k of Object.keys(obj_errors)) {
                            txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                        }
                        Swal.fire({
                            type: 'warning',
                            html: txt_errors,
                        })
                    }
                },
                error: function (error) {
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                    })
                }
            });

            return false;
        } 

        function approvedPartialSettlement(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var paid_money = $("#approvedPartialSettlement input[name=paid_money]").val().replace(/,/g,'');
            var received_date = $("#approvedPartialSettlement input[name=received_date]").val();

            if (paid_money == '') {
                Swal.fire({
                    type: 'warning',
                    html: 'Xin vui lòng nhập số tiền nhân viên chuyển khoản',
                    allowOutsideClick: false
                })
                return;
            }

            if (received_date == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày nhân tiền!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(received_date)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày nhân tiền hợp lệ!",
                        allowOutsideClick: false
                    })

                    return;
                }
            }

            var data = {
                        received_date : $.datepicker.formatDate('yy-mm-dd', $("#approvedPartialSettlement input[name=received_date]").datepicker('getDate')),
                        paid_money : paid_money,
                        loan_capital_id : "{{ request()->route('loan_capital_id') }}",
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/approved-partial-settlement") }}',
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
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                        allowOutsideClick: false
                    })
                }
            });
        }

        function approvedFinalSettlement(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var paid_money = $("#approvedFinalSettlement input[name=paid_money]").val().replace(/,/g,'');
            var received_date = $("#approvedFinalSettlement input[name=received_date]").val();

            if (paid_money == '') {
                Swal.fire({
                    type: 'warning',
                    html: 'Xin vui lòng nhập số tiền nhân viên chuyển khoản',
                    allowOutsideClick: false
                })
                return;
            }

            if (received_date == '') {
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày nhân tiền!",
                        allowOutsideClick: false
                    })

                    return;
            } else {
                if (!validationDate(received_date)) {
                    
                    Swal.fire({
                        type: 'warning',
                        html: "Xin vui lòng nhập ngày nhân tiền hợp lệ!",
                        allowOutsideClick: false
                    })

                    return;
                }
            }

            var data = {
                        received_date : $.datepicker.formatDate('yy-mm-dd', $("#approvedFinalSettlement input[name=received_date]").datepicker('getDate')),
                        paid_money : paid_money,
                        loan_capital_id : "{{ request()->route('loan_capital_id') }}",
                    };

            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/approved-final-settlement") }}',
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
                
                    console.log(error)
                    var obj_errors = error.responseJSON;
                    var txt_errors = '';
                    for (k of Object.keys(obj_errors)) {
                        txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                    }
                    Swal.fire({
                        type: 'warning',
                        html: txt_errors,
                        allowOutsideClick: false
                    })
                }
            });
        }

        function doneMonthLoanCapital(){
            Swal.fire({
                type: 'warning',
                text: 'Bạn có chắc chắn muốn hoàn tất!',
                showCancelButton: true,
            })
            .then(function (result) {
                if(result.value){   
                    $.ajaxSetup(
                    {
                        headers:
                        {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var data = {
                                loan_capital_id : "{{ request()->route('loan_capital_id') }}",
                            };

                    $.ajax({
                        method: "POST",
                        url: '{{ url("toh_hrm/api/done-pay-month-loan-capital") }}',
                        data:data, 
                        dataType: 'json',
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
                        
                            console.log(error)
                            var obj_errors = error.responseJSON;
                            var txt_errors = '';
                            for (k of Object.keys(obj_errors)) {
                                txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                            }
                            Swal.fire({
                                type: 'warning',
                                html: txt_errors,
                            })
                        }
                    });
                }
            })
        } 
    </script>
@endsection