@extends('layouts.master')

@section('title', 'Tín dụng')

@section('content')  
    <style>
        #ui-datepicker-div{
            z-index: 9999 !important;
        }
        .table.table-striped a.btn{
            width: 135px;
        }
        .table.table-striped span{
            /* padding: 4.48px 44px !important; */
        }
        .select2-container .select2-selection--single{
            height: 29px;
        }
        label.control-label{
            padding-top: 6px;
        }
        .table-manager tbody tr td{
            padding: 8px 3px !important;
        }

    </style>
    <div class="row">
        <div class="col-lg-2">
            @include('layouts.vay-von.menu')
        </div>
        <div class="col-lg-10">
            <h4 class="title-fuction">
                Danh sách nhân viên vay vốn
                @if ($user_id == 1 && count($list_personnel_loan_capital) > 0)
                    <div class="pull-right" style="position: relative;bottom:5px;right:10px">
                        <button type="button" class="btn btn-sm btn-primary" onclick="remindMonthLoanCapital()">Nhắc trả nợ</button>
                    </div>
                @endif
            </h4>
            @if ($user_id == 1)
                <div class="box_search">
                    <form class="row" action="">
                        <div class="form-group col-lg-4">
                            <label class="col-sm-4 control-label">Nhân viên</label>
                            <div class="col-sm-8">
                                <select name="personnel_id" id="selectPersonnel" class="form-control select2 narrow wrap" >
                                    <option value="0">--Chọn nhân viên--</option>
                                    @if (!empty($list_all_personnel))
                                        @foreach ($list_all_personnel as $value)
                                        <option value="{{ $value->id }}" @if ($value->id == Request::get('personnel_id')) {{ "selected" }} @endif>{{ $value->fullname }}</option>
                                        @endforeach
                                    @endif
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
                                    {!! $department !!}
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
                                <select name="status" class="form-control" style="width: 100%;height: 29px; font-size: 13px !important;">
                                    <option value="-1" @if (Request::get('status') == -1) selected @endif> -- Tất cả -- </option>
                                    <option value="0" @if (Request::get('status') == 0) selected @endif> Xem xét yêu cầu vay</option>
                                    <option value="1" @if (Request::get('status') == 1) selected @endif> Phê duyệt </option>
                                    <option value="2" @if (Request::get('status') == 2) selected @endif> Đã duyệt </option>
                                    <option value="3" @if (Request::get('status') == 3) selected @endif> Đã hủy </option>
                                    <option value="4" @if (Request::get('status') == 4) selected @endif> Đã hoàn tất thanh toán </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
                @if (count($list_personnel_loan_capital) > 0)
                    <table class="table table-bordered table-manager">
                            <thead>
                                <tr>
                                    <th style="width: 20%;" class="text-center">Họ và tên</th>
                                    <th class="text-center">Số tiền vay<br> (VNĐ)</th>
                                    <th class="text-center">Thời gian vay<br>  (tháng)</th>
                                    <th class="text-center">Ngày giải ngân mong muốn của n/v</th>
                                    <th class="text-center">Ngày giải ngân cty duyệt</th>
                                    <th class="text-center">Ngày n/v <br>bắt đầu trả</th>
                                    <th class="text-center">L/s<br> (% năm)</th>
                                    <th class="text-center">L/s ưu đãi <br> (% năm)</th>
                                    <th class="text-center">Số tháng <br> ưu đãi</th>
                                    {{-- <th class="text-center">L/s trả chậm<br> (% năm)</th> --}}
                                    <th class="text-center">Hồ sơ</th>
                                    <th class="text-center">Chi tiết</th>
                                    <th class="text-center">Trả sớm một phần</th>
                                    <th class="text-center">Trả sớm toàn bộ</th>
                                    <th class="text-center" style="width: 22%">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php //dd($list_personnel_loan_capital); ?>
                                @foreach ($list_personnel_loan_capital as $personnel)
                                    <tr>
                                        <td class="text-center">
                                            {{ $personnel->fullname }}
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->max_money != null)
                                                {{ number_format($personnel->max_money) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->month_time != null)
                                                {{ $personnel->month_time }}
                                            @else
                                                -
                                            @endif
                                        </td> 
                                        <td class="text-center">
                                            @if ($personnel->disbursement_date_by_user != '0000-00-00')
                                                {{ BatvHelper::formatDate($personnel->disbursement_date_by_user ,'Y-m-d','d/m/Y','H:i:s',false) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->disbursement_date != '0000-00-00')
                                                {{ BatvHelper::formatDate($personnel->disbursement_date ,'Y-m-d','d/m/Y','H:i:s',false) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->disbursement_date != '0000-00-00')
                                                <?php
                                                    $start_month_pay = date('Y-m-d', strtotime("+". $personnel->start_month_pay ." months", strtotime($personnel->disbursement_date)));
                                                ?>
                                                {{ BatvHelper::formatDate($start_month_pay ,'Y-m-d','d/m/Y','H:i:s',false) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        
                                        <td class="text-center">
                                            @if ($personnel->interest_rate != null)
                                                {{ $personnel->interest_rate }}
                                            @else
                                                {{ $config_loan_capital->interest_rate }}
                                            @endif
                                        </td>
                                        
                                        <td class="text-center">
                                            @if ($personnel->preferential_interest_rate != null)
                                                {{ $personnel->preferential_interest_rate }}
                                            @else
                                                {{ $config_loan_capital->preferential_interest_rate }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->count_month_preferential != null)
                                                {{ $personnel->count_month_preferential }}
                                            @else
                                                {{ $config_loan_capital->count_month_preferential }}
                                            @endif
                                        </td> 
                                        {{-- <td class="text-center">
                                            @if ($personnel->deferred_interest != null)
                                                {{ $personnel->deferred_interest }}
                                            @else
                                                {{ $config_loan_capital->deferred_interest }}
                                            @endif
                                        </td>  --}}
                                        <td class="text-center">
                                            <a href="javascript:void(0)" data-status_file='{{ $personnel->status_file }}' data-loan_capital_id='{{ $personnel->loan_capital_id }}' data-file='{{ $personnel->file }}' data-toggle="modal" data-target="#reviewFile" style="width: 100%"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->status == 2)
                                                <a @if($personnel->loan_capital_id != null && $personnel->status == 2 || $personnel->status == 4) href="{{ route('detailLoanCapital', ['loan_capital_id' => $personnel->loan_capital_id]) }}"  target="_blank" @elseif($personnel->status == 3)  href="javascript:void(0)" data-toggle="modal" data-target="#alertModalCancel"  @else href="javascript:void(0)" data-toggle="modal" data-target="#alertModal"  @endif ><i class="fa fa-info-circle"></i></a>
                                            @else
                                                <a 
                                                data-personnel_id='{{ $personnel->personnel_id }}' 
                                                data-loan_capital_id='{{ $personnel->loan_capital_id }}' 
                                                data-fullname='{{ $personnel->fullname }}' data-score='{{ $personnel->score_faith }}' 
                                                data-max_money='{{ $personnel->max_money }}' 
                                                data-preferential_interest_rate='{{ $personnel->preferential_interest_rate }}' 
                                                data-month_time='{{ $personnel->month_time }}' 
                                                data-note='{{ $personnel->note }}' 
                                                data-loan_purpose='{{ $personnel->loan_purpose }}' 
                                                data-another_purpose='{{ $personnel->another_purpose }}' 
                                                data-disbursement_form='{{ $personnel->disbursement_form }}' 
                                                data-info_receive_disbursement='{!! $personnel->info_receive_disbursement !!}'
                                                data-pay='{{ $personnel->pay }}' 
                                                data-disbursement_date_by_user='{{ BatvHelper::formatDate($personnel->disbursement_date_by_user,'Y-m-d', 'd/m/Y', 'H:i:s', false) }}' 
                                                data-file='{{ $personnel->file }}' 
                                                class="approved-loan-capital-view-only" 
                                                href="javascript:void(0)" 
                                                data-toggle="modal" 
                                                data-target="#approvedLoanCapitalViewOnly" 
                                                style="width: 100%">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->partial_settlement == 1 && $personnel->status == 2)
                                                <i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($personnel->final_settlement == 1 && $personnel->status == 2)
                                                <i class="fa fa-check" aria-hidden="true" style="color:green"></i>
                                            @endif
                                        </td>
                                        <td class="admin-approved">
                                            @if ($personnel->status == 0)
                                                <div class="text-center">
                                                    @if(in_array('xet-diem-tin-nhiem',$arr_route))
                                                     <a 
                                                        data-personnel_id='{{ $personnel->personnel_id }}' 
                                                        data-loan_capital_id='{{ $personnel->loan_capital_id }}' 
                                                        data-fullname='{{ $personnel->fullname }}' data-score='{{ $personnel->score_faith }}' 
                                                        data-max_money='{{ $personnel->max_money }}' 
                                                        data-preferential_interest_rate='{{ $personnel->preferential_interest_rate }}' 
                                                        data-month_time='{{ $personnel->month_time }}' 
                                                        data-note='{{ $personnel->note }}' 
                                                        data-loan_purpose='{{ $personnel->loan_purpose }}' 
                                                        data-another_purpose='{{ $personnel->another_purpose }}' 
                                                        data-disbursement_form='{{ $personnel->disbursement_form }}' 
                                                        data-info_receive_disbursement='{!! $personnel->info_receive_disbursement !!}' 
                                                        data-pay='{{ $personnel->pay }}' 
                                                        data-disbursement_date_by_user='{{ BatvHelper::formatDate($personnel->disbursement_date_by_user,'Y-m-d', 'd/m/Y', 'H:i:s', false) }}' 
                                                        data-file='{{ $personnel->file }}'
                                                        class="btn btn-xs btn-warning review-score" href="javascript:void(0)" data-toggle="modal" data-target="#reviewScore" style="width: 100%">
                                                        Xem xét yêu cầu vay 
                                                    </a>
                                                    @else
                                                        <div class="text-center">
                                                            <span href="#" class="choduyet" style="cursor: inherit;padding: 4.48px 42px;">Chờ duyệt</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div id="reviewScore" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Xem xét yêu cầu vay</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Họ và tên:</label><br>
                                                                            <div class="fullname"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Điểm tín nhiệm:</label> 
                                                                            <span class="score_min"></span>
                                                                            <div class="txt_status"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Số tiền vay (VNĐ):</label>
                                                                            <input class="form-control input-sm" name="money" type="text" disabled>
                                                                            <input class="form-control input-sm hidden" name="max_money" type="number">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Thời gian vay (tháng):</label>
                                                                            <input class="form-control input-sm" name="month_time" type="text" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Thời gian mong muốn GN:</label>
                                                                            <div class="disbursement_date_by_user"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Mục đích vay:</label> <div id="container"></div>
                                                                            <div class="loan_purpose" style="text-align: justify;"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Hình thức GN:</label>
                                                                            <div class="disbursement_form"></div>
                                                                            <label class="control-label">Thông tin tài khoản nhận giải ngân:</label>
                                                                            <textarea class="info_receive_disbursement form-control" disabled rows="5" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group clearfix">
                                                                            <label class="control-label">Hình thức trả:</label>
                                                                            <div class="pay"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label class="control-label">Phê duyệt:</label>
                                                                        <div>
                                                                            <label class="radio-inline"><input type="radio" name="action_ttt" checked value="1">Đồng ý</label>
                                                                            <label class="radio-inline"><input type="radio" name="action_ttt" value="3">Từ chối</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label class="control-label">Hồ sơ:</label>
                                                                        <div class="file">
                                                                           
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group clearfix note">
                                                                            <label class="control-label">Ghi chú gửi TGĐ:</label>
                                                                            <textarea class="form-control" data-autoresize="" rows="5" required="" name="note"></textarea>
                                                                        </div>
                                                                        <div class="reason form-group clearfix hidden">
                                                                            <label class="control-label">Lý do từ chối:</label>
                                                                            <textarea class="form-control" data-autoresize="" rows="5" required="" name="reason"></textarea>
                                                                        </div>
                                                                        {{-- <div class="form-group clearfix">
                                                                          <div style="color:red;font-weight:600;font-style:italic">
                                                                            CHÚ Ý: Nếu điểm tín nhiệm của nhân viên được xét nhỏ hơn {{ $config_loan_capital->score_min }} (điểm tiêu chuẩn mà công ty quy định) thì nhân viên sẽ không được duyệt vay vốn.
                                                                          </div>
                                                                        </div> --}}
                                                                    </div>
                                                                    <div class="col-sm-12 list-loan-capital-history hidden">
                                                                        <div class="clearfix">
                                                                            <label class="control-label">Lịch sử vay:</label>
                                                                            <table class="table table-bordered">
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
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                            
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="loan_capital_id">
                                                                <button type="button" class="btn btn-primary" onclick="reviewScore()"> Phê duyệt </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($personnel->status == 1 && $user_id == 1)
                                                @if (Auth::user()->id == 1)
                                                    <div class="text-center">
                                                        <a 
                                                        data-personnel_id='{{ $personnel->personnel_id }}' 
                                                        data-loan_capital_id='{{ $personnel->loan_capital_id }}' 
                                                        data-fullname='{{ $personnel->fullname }}' data-score='{{ $personnel->score_faith }}' 
                                                        data-max_money='{{ $personnel->max_money }}' 
                                                        data-preferential_interest_rate='{{ $personnel->preferential_interest_rate }}' 
                                                        data-month_time='{{ $personnel->month_time }}' 
                                                        data-note='{{ $personnel->note }}' 
                                                        data-loan_purpose='{{ $personnel->loan_purpose }}' 
                                                        data-another_purpose='{{ $personnel->another_purpose }}' 
                                                        data-disbursement_form='{{ $personnel->disbursement_form }}' 
                                                        data-info_receive_disbursement='{!! $personnel->info_receive_disbursement !!}'
                                                        data-pay='{{ $personnel->pay }}' 
                                                        data-disbursement_date_by_user='{{ BatvHelper::formatDate($personnel->disbursement_date_by_user,'Y-m-d', 'd/m/Y', 'H:i:s', false) }}' 
                                                        data-file='{{ $personnel->file }}' 
                                                        class="btn btn-xs btn-primary approved-loan-capital" 
                                                        href="javascript:void(0)" 
                                                        data-toggle="modal" 
                                                        data-target="#approvedLoanCapital" 
                                                        style="width: 100%">
                                                        Phê duyệt
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <div href="#" class="choduyet" style="cursor: inherit;padding: 4.48px 0px;">Chờ duyệt</div>
                                                    </div>
                                                @endif
                                            @elseif ($personnel->status == 1 && in_array('xet-diem-tin-nhiem',$arr_route))
                                                <div class="text-center">
                                                    <div href="#" class="daduyet" style="cursor: inherit;padding: 4.48px 0px;">Đã duyệt gửi TGĐ</div>
                                                </div>
                                            @endif

                                            <div class="text-center">
                                                {{-- <div href="#" class="choduyet" style="cursor: inherit;padding: 4.48px 42px;">Chờ duyệt</div>
                                                <div href="#" class="daduyet" style="cursor: inherit;padding: 4.48px 21px;">Đã duyệt gửi TGĐ</div>
                                                <div href="#" class="daduyet" style="cursor: inherit;padding: 4.48px 45px;">Đã duyệt</div>
                                                <div href="#" class="dahuy" style="cursor: inherit;padding: 4.48px 50px;">Đã hủy</div>
                                                <div href="#" class="hoantatthanhtoan" style="cursor: inherit;">Đã hoàn tất thanh toán</div> --}}

                                                @if ($personnel->status == 2)
                                                    <div href="#" class="daduyet" style="cursor: inherit;padding: 4.48px 0px;">Đã duyệt</div>
                                                @endif
                                                
                                                @if ($personnel->status == 3)
                                                    <div href="#" class="dahuy" style="cursor: inherit;padding: 4.48px 0px;">Đã hủy</div>
                                                @endif
            
                                                @if ($personnel->status == 4)
                                                    <div href="#" class="hoantatthanhtoan" style="cursor: inherit;">Đã hoàn tất thanh toán</div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                    <div class="col-lg-12 text-right">
                        {{ $list_personnel_loan_capital->appends(Request::all())->links() }} 
                    </div>
                @else
                    <div class="alert alert-warning fade in alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        Không có dữ liệu.
                    </div>
                @endif
        </div>
    </div>

    <div id="reviewFile" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Phê duyệt hồ sơ vay vốn của nhân viên</h4>
                </div>
                <div class="modal-body">
                    <div class="row list-file">

                    </div>
                    @if ($user_id == 1)
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label">Trạng thái:</label>
                            <div class="approved_file"></div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

    <div id="alertModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Đang chờ phê duyệt</h4>
                </div>
                <div class="modal-body">
                    {{-- <p>Some text in the modal.</p> --}}
                </div>
            </div>
        </div>
    </div>

    <div id="alertModalCancel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Đã hủy</h4>
                </div>
                <div class="modal-body">
                    {{-- <p>Some text in the modal.</p> --}}
                </div>
            </div>
        </div>
    </div>

    <div id="approvedLoanCapital" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Xét duyệt thông tin vay vốn</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Họ và tên:</label><br>
                                <div class="fullname"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Điểm tín nhiệm:</label> 
                                <span class="score_min"></span>
                                <div class="txt_status"></div>
                                {{-- ({{ $personnel->score }}/{{ $config_loan_capital->score_min }}) --}}
                                {{-- @if ($personnel->score < $config_loan_capital->score_min)
                                    <div href="#" class="dahuy text-center" style="cursor: inherit">Không đạt tiêu chuẩn</div>
                                @else
                                    <div href="#" class="daduyet text-center" style="cursor: inherit">Đạt tiêu chuẩn</div>
                                @endif --}}
                                {{-- <input class="form-control input-sm" name="score" type="number" value=""  required min="0"  > --}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Số tiền vay (VNĐ):</label>
                                <input data-type="currency" class="form-control input-sm" type="text" name="max_money" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Thời gian vay (tháng):</label>
                                <input class="form-control input-sm" name="month_time" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Thời gian mong muốn GN:</label>
                                <div class="disbursement_date_by_user"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Mục đích vay:</label> <div id="container"></div>
                                <div class="loan_purpose" style="text-align: justify;"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Hình thức GN:</label>
                                <div class="disbursement_form"></div>
                                <label class="control-label">Thông tin tài khoản nhận giải ngân:</label>
                                <textarea class="info_receive_disbursement form-control" disabled rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Hình thức trả:</label>
                                <div class="pay"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Ngày GN:</label>
                                <input type="text" class="form-control input-sm"  id="datepicker" name="disbursement_date"  pattern="\d{1,2}/\d{1,2}/\d{4}" value="" autocomplete="off">
                                <script>
                                    $(function() {
                                        $( "#datepicker" ).datepicker({
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
                        <div class="col-sm-3">
                            <label class="control-label">Phê duyệt:</label>
                            <div>
                                <label class="radio-inline"><input type="radio" name="action" checked value="2">Đồng ý</label>
                                <label class="radio-inline"><input type="radio" name="action" value="3">Từ chối</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Hồ sơ:</label>
                            <div class="file">
                               
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Ghi chú của TTT:</label>
                                <textarea disabled class="form-control" data-autoresize="" rows="5" required="" name="note"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="reason form-group clearfix hidden">
                                <label class="control-label">Lý do từ chối:</label>
                                <textarea class="form-control"  data-autoresize  rows="5" name="reason"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 list-loan-capital-history hidden">
                            <div class="clearfix">
                                <label class="control-label">Lịch sử vay:</label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Số tiền vay</th>
                                            <th class="text-center">Ngày <br>giải ngân</th>
                                            <th class="text-center">Ngày n/v <br>bắt đầu trả</th>
                                            <th class="text-center">Ngày <br>kết thúc</th>
                                            <th class="text-center">L/s<br> (% năm)</th>
                                            <th class="text-center">L/s ưu đãi <br> (% năm)</th>
                                            <th class="text-center">Số tháng ưu đãi (từ lúc bắt đầu trả nợ)</th>
                                            <th class="text-center">L/s trả chậm<br> (% năm)</th>
                                            <th class="text-center">L/s nếu không hoàn thiện hồ sơ (% năm)</th>
                                            <th class="text-center">Tổng <br>tiền lãi</th>
                                            <th class="text-center">Phạt trả chậm</th>
                                            <th class="text-center">Đã trả</th>
                                            <th class="text-center">Còn lại</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->id == 1)
                <div class="modal-footer">
                    <input type="hidden" name="loan_capital_id">
                    <button type="button" class="btn btn-primary" onclick="approvedLoanCapital()">Cập nhật</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div id="approvedLoanCapitalViewOnly" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Xem thông tin vay vốn</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Họ và tên:</label><br>
                                <div class="fullname"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Điểm tín nhiệm:</label> 
                                <span class="score_min"></span>
                                <div class="txt_status"></div>
                                {{-- ({{ $personnel->score }}/{{ $config_loan_capital->score_min }}) --}}
                                {{-- @if ($personnel->score < $config_loan_capital->score_min)
                                    <div href="#" class="dahuy text-center" style="cursor: inherit">Không đạt tiêu chuẩn</div>
                                @else
                                    <div href="#" class="daduyet text-center" style="cursor: inherit">Đạt tiêu chuẩn</div>
                                @endif --}}
                                {{-- <input class="form-control input-sm" name="score" type="number" value=""  required min="0"  > --}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Số tiền vay (VNĐ):</label>
                                <input data-type="currency" class="form-control input-sm" type="text" name="max_money" id="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Thời gian vay (tháng):</label>
                                <input class="form-control input-sm" name="month_time" type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Thời gian mong muốn GN:</label>
                                <div class="disbursement_date_by_user"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Mục đích vay:</label> <div id="container"></div>
                                <div class="loan_purpose" style="text-align: justify;"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Hình thức GN:</label>
                                <div class="disbursement_form"></div>
                                <label class="control-label">Thông tin tài khoản nhận giải ngân:</label>
                                <textarea class="info_receive_disbursement form-control" disabled rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group clearfix">
                                <label class="control-label">Hình thức trả:</label>
                                <div class="pay"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Hồ sơ:</label>
                            <div class="file">
                               
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <label class="control-label">Ghi chú của TTT:</label>
                                <textarea disabled class="form-control" data-autoresize="" rows="5" required="" name="note"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="reason form-group clearfix hidden">
                                <label class="control-label">Lý do từ chối:</label>
                                <textarea class="form-control"  data-autoresize  rows="5" name="reason"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 list-loan-capital-history hidden">
                            <div class="clearfix">
                                <label class="control-label">Lịch sử vay:</label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Số tiền vay</th>
                                            <th class="text-center">Ngày <br>giải ngân</th>
                                            <th class="text-center">Ngày n/v <br>bắt đầu trả</th>
                                            <th class="text-center">Ngày <br>kết thúc</th>
                                            <th class="text-center">L/s<br> (% năm)</th>
                                            <th class="text-center">L/s ưu đãi <br> (% năm)</th>
                                            <th class="text-center">Số tháng ưu đãi (từ lúc bắt đầu trả nợ)</th>
                                            <th class="text-center">L/s trả chậm<br> (% năm)</th>
                                            <th class="text-center">L/s nếu không hoàn thiện hồ sơ (% năm)</th>
                                            <th class="text-center">Tổng <br>tiền lãi</th>
                                            <th class="text-center">Phạt trả chậm</th>
                                            <th class="text-center">Đã trả</th>
                                            <th class="text-center">Còn lại</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>  

        $(document).ready(function(){
            $('body').tooltip({
                selector: '.createdDiv'
            });
            $(".admin-approved a[data-loan_capital_id={{ Request::get('loan_capital_id') }}]").trigger("click");   
        });

        $('#approvedLoanCapital input[type=radio][name=action]').change(function() {
            if (this.value == 2) {
                $('#approvedLoanCapital .reason').addClass('hidden');
            } else {
                $('#approvedLoanCapital .reason').removeClass('hidden');
            }
        });

        $('#reviewScore input[type=radio][name=action_ttt]').change(function() {
            if (this.value == 1) {
                $('#reviewScore .reason').addClass('hidden');
                $('#reviewScore .note').removeClass('hidden');
            } else {
                $('#reviewScore .reason').removeClass('hidden');
                $('#reviewScore .note').addClass('hidden');
            }
        });

 
        $(document).on("click","a[data-target='#reviewFile']",function() {
            var loan_capital_id = $(this).attr('data-loan_capital_id');
            var status_file = $(this).attr('data-status_file');

            
            $('#reviewFile .modal-body .list-file').html('');
            if (status_file == 1) {
                var footer_action = '<button type="button" class="btn" data-dismiss="modal">Đóng</button>';
                var approved_file = '<span href="#" class="daduyet" style="cursor: inherit;padding: 4.48px 45px;">Đã hoàn tất</span>';
            } else if(status_file == 2) {
                var footer_action = '<button type="button" class="btn" data-dismiss="modal">Đóng</button>';
                var approved_file = '<span href="#" class="dahuy" style="cursor: inherit;padding: 4.48px 45px;">Chưa hoàn tất</span>';
            } else {
                var footer_action = '<input type="hidden" name="loan_capital_id"><button type="button" class="btn btn-primary" onclick="reviewFile()">Cập nhật</button><button type="button" class="btn" data-dismiss="modal">Đóng</button>';
                var approved_file = '<label class="radio-inline"><input type="radio" name="approved_file" checked value="1">Đã hoàn tất</label><label class="radio-inline"><input type="radio" name="approved_file" value="2">Chưa hoàn tất</label>';
            }

            @if ($user_id == 1)
                $('#reviewFile .approved_file').html(approved_file);
                $('#reviewFile .modal-footer').html(footer_action);
            @endif

            var file = $(this).attr('data-file');
            file = file.split(",");
            $.each(file, function(key,value){
                if (value) {
                    var ext = value.split('.').pop();
                                
                    if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="{{ asset("images/general/document.png") }}"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    } else {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    }

                    $('#reviewFile .modal-body .list-file').append(image_path);
                }
            });

            $('#reviewFile input[name=loan_capital_id]').val(loan_capital_id);
        });


        $('#reviewScore').on('shown.bs.modal', function (e) {
            $('#reviewScore textarea[name=reason]').val('');
            $('#reviewScore .note').removeClass('hidden');
            $('#reviewScore .reason').addClass('hidden');
            $('#reviewScore input[name=action_ttt]:first').prop('checked', true);
        })
        
        $(document).on('click','.approved-loan-capital',function(e){
            var personnel_id = $(this).attr('data-personnel_id');
            $(".list-loan-capital-history").addClass('hidden');
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        personnel_id : personnel_id,
                    };
                    
            $.ajax({
                method: "POST",
                url: '{{ route("list-loan-capital-history") }}',
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
                        var data = response.data;
                        if (data.length > 0) {
                            $(".list-loan-capital-history").removeClass('hidden');
                            var html = '';

                            $.each(data, function(key,value){
                                var res = value['received_date'].split("-");
                                var received_date = res[2] + '/' + res[1] + '/' + res[0];

                                html += '<tr class="text-center">';
                                    html += '<td>' + formatNumber(value['max_money'], '.', ',') + '</td>';
                                    html += '<td>' + value['disbursement_date']+ '</td>';
                                    html += '<td>' + value['repayment_period']+ '</td>';
                                    html += '<td>' + received_date + '</td>';
                                    html += '<td>' + value['interest_rate']+ '</td>';
                                    html += '<td>' + value['preferential_interest_rate'] + '</td>';
                                    html += '<td>' + value['count_month_preferential'] + '</td>';
                                    html += '<td>' + value['deferred_interest'] + '</td>';
                                    html += '<td>' + value['interest_file_late'] + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_interest']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_interest_incurred']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_paid_money']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round((value['max_money'] + value['total_interest'] + value['total_interest_incurred']) - value['total_paid_money'] ), '.', ',')+ '</td>';
                                html += '</tr>';
                            });

                            $(".list-loan-capital-history tbody").html(html);
                        }

                    }
                },
                error: function (error) {
                    alert('error');
                }
            });
            
            var loan_capital_id = $(this).attr('data-loan_capital_id');
            var fullname = $(this).attr('data-fullname');
            var score = $(this).attr('data-score');
            var max_money = $(this).attr('data-max_money');
            // var preferential_interest_rate = $(this).attr('data-preferential_interest_rate');
            var month_time = $(this).attr('data-month_time');
            var note = $(this).attr('data-note');
            var loan_purpose = $(this).attr('data-loan_purpose');
            var disbursement_form = $(this).attr('data-disbursement_form');
            var info_receive_disbursement = $(this).attr('data-info_receive_disbursement');
            var pay = $(this).attr('data-pay');
            var disbursement_date_by_user = $(this).attr('data-disbursement_date_by_user');

            if (loan_purpose == 1) {
                loan_purpose = 'Vay mua nhà'
            } else if(loan_purpose == 2) {
                loan_purpose = 'Vay mua xe'
            } else {
                loan_purpose =  $(this).attr('data-another_purpose');
            }

            if (disbursement_form == 1) {
                disbursement_form = 'GN trực tiếp tới đơn vị thụ hưởng'
            } else {
                disbursement_form = 'GN tới người vay'
            }

            if (pay == 1) {
                pay = 'Trừ vào lương'
            } else {
                pay = 'Tự trả qua chuyển  khoản'
            }

            $('#approvedLoanCapital input[name=loan_capital_id]').val(loan_capital_id);
            $('#approvedLoanCapital .fullname').html(fullname);

            var file = $(this).attr('data-file');
            file = file.split(",");
            $('#approvedLoanCapital .file').html('');
            
            $.each(file, function(key,value){
                if (value) {
                    var ext = value.split('.').pop();
                                
                    if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="{{ asset("images/general/document.png") }}"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    } else {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    }

                    // var image_path = '<div class="col-sm-3"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"></a></div>';
                    $('#approvedLoanCapital .file').append(image_path);
                }
            });

            if (score || max_money || preferential_interest_rate || month_time) {
                if (score < {{ $config_loan_capital->score_min }}) {
                    txt_status = '<div href="#" class="dahuy text-center" style="cursor: inherit">Không đạt tiêu chuẩn</div>';
                } else {
                    txt_status = '<div href="#" class="daduyet text-center" style="cursor: inherit">Đạt tiêu chuẩn</div>';
                }

                $('#approvedLoanCapital span.score_min').html('('+ score + '/{{ $config_loan_capital->score_min }})');
                $('#approvedLoanCapital div.txt_status').html(txt_status);
                $('#approvedLoanCapital input[name=score]').val(score);
                $('#approvedLoanCapital input[name=max_money]').val(max_money.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
                $('#approvedLoanCapital input[name=month_time]').val(month_time);
                $('#approvedLoanCapital textarea[name=note]').val(note);
                $('#approvedLoanCapital .loan_purpose').html(loan_purpose);
                $('#approvedLoanCapital .disbursement_form').html(disbursement_form);
                $('#approvedLoanCapital .info_receive_disbursement').val(info_receive_disbursement);
                $('#approvedLoanCapital .pay').html(pay);
                $('#approvedLoanCapital .disbursement_date_by_user').html(disbursement_date_by_user);
            } else {
                $('#approvedLoanCapital span.score_min').html('');
                $('#approvedLoanCapital div.txt_status').html('');
                $('#approvedLoanCapital input[name=score]').val('');
                $('#approvedLoanCapital input[name=max_money]').val('');
                $('#approvedLoanCapital input[name=preferential_interest_rate]').val('');
                $('#approvedLoanCapital input[name=month_time]').val('');
                $('#approvedLoanCapital textarea[name=note]').val('');
                $('#approvedLoanCapital .loan_purpose').html('');
                $('#approvedLoanCapital .disbursement_form').html('');
                $('#approvedLoanCapital .info_receive_disbursement').val('');
                $('#approvedLoanCapital .pay').html('');
                $('#approvedLoanCapital .disbursement_date_by_user').html('');
            }

            $('#approvedLoanCapital textarea[name=reason]').val('');
            $('#approvedLoanCapital .reason').addClass('hidden');
            $('#approvedLoanCapital input[name=disbursement_date]').val('');
            $('#approvedLoanCapital input[name=action]:first').prop('checked', true);
        });

        $(document).on('click','.approved-loan-capital-view-only',function(e){
            var personnel_id = $(this).attr('data-personnel_id');
            $(".list-loan-capital-history").addClass('hidden');
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        personnel_id : personnel_id,
                    };
                    
            $.ajax({
                method: "POST",
                url: '{{ route("list-loan-capital-history") }}',
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
                        var data = response.data;
                        if (data.length > 0) {
                            $(".list-loan-capital-history").removeClass('hidden');
                            var html = '';

                            $.each(data, function(key,value){
                                var res = value['received_date'].split("-");
                                var received_date = res[2] + '/' + res[1] + '/' + res[0];

                                html += '<tr class="text-center">';
                                    html += '<td>' + formatNumber(value['max_money'], '.', ',') + '</td>';
                                    html += '<td>' + value['disbursement_date']+ '</td>';
                                    html += '<td>' + value['repayment_period']+ '</td>';
                                    html += '<td>' + received_date + '</td>';
                                    html += '<td>' + value['interest_rate']+ '</td>';
                                    html += '<td>' + value['preferential_interest_rate'] + '</td>';
                                    html += '<td>' + value['count_month_preferential'] + '</td>';
                                    html += '<td>' + value['deferred_interest'] + '</td>';
                                    html += '<td>' + value['interest_file_late'] + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_interest']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_interest_incurred']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_paid_money']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round((value['max_money'] + value['total_interest'] + value['total_interest_incurred']) - value['total_paid_money'] ), '.', ',')+ '</td>';
                                html += '</tr>';
                            });

                            $(".list-loan-capital-history tbody").html(html);
                        }

                    }
                },
                error: function (error) {
                    alert('error');
                }
            });
            
            var loan_capital_id = $(this).attr('data-loan_capital_id');
            var fullname = $(this).attr('data-fullname');
            var score = $(this).attr('data-score');
            var max_money = $(this).attr('data-max_money');
            // var preferential_interest_rate = $(this).attr('data-preferential_interest_rate');
            var month_time = $(this).attr('data-month_time');
            var note = $(this).attr('data-note');
            var loan_purpose = $(this).attr('data-loan_purpose');
            var disbursement_form = $(this).attr('data-disbursement_form');
            var info_receive_disbursement = $(this).attr('data-info_receive_disbursement');
            var pay = $(this).attr('data-pay');
            var disbursement_date_by_user = $(this).attr('data-disbursement_date_by_user');

            if (loan_purpose == 1) {
                loan_purpose = 'Vay mua nhà'
            } else if(loan_purpose == 2) {
                loan_purpose = 'Vay mua xe'
            } else {
                loan_purpose =  $(this).attr('data-another_purpose');
            }

            if (disbursement_form == 1) {
                disbursement_form = 'GN trực tiếp tới đơn vị thụ hưởng'
            } else {
                disbursement_form = 'GN tới người vay'
            }

            if (pay == 1) {
                pay = 'Trừ vào lương'
            } else {
                pay = 'Tự trả qua chuyển  khoản'
            }

            $('#approvedLoanCapitalViewOnly input[name=loan_capital_id]').val(loan_capital_id);
            $('#approvedLoanCapitalViewOnly .fullname').html(fullname);

            var file = $(this).attr('data-file');
            file = file.split(",");
            $('#approvedLoanCapitalViewOnly .file').html('');
            
            $.each(file, function(key,value){
                if (value) {
                    var ext = value.split('.').pop();
                                
                    if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="{{ asset("images/general/document.png") }}"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    } else {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    }

                    // var image_path = '<div class="col-sm-3"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"></a></div>';
                    $('#approvedLoanCapitalViewOnly .file').append(image_path);
                }
            });

            if (score || max_money || preferential_interest_rate || month_time) {
                if (score < {{ $config_loan_capital->score_min }}) {
                    txt_status = '<div href="#" class="dahuy text-center" style="cursor: inherit">Không đạt tiêu chuẩn</div>';
                } else {
                    txt_status = '<div href="#" class="daduyet text-center" style="cursor: inherit">Đạt tiêu chuẩn</div>';
                }

                $('#approvedLoanCapitalViewOnly span.score_min').html('('+ score + '/{{ $config_loan_capital->score_min }})');
                $('#approvedLoanCapitalViewOnly div.txt_status').html(txt_status);
                $('#approvedLoanCapitalViewOnly input[name=score]').val(score);
                $('#approvedLoanCapitalViewOnly input[name=max_money]').val(max_money.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
                $('#approvedLoanCapitalViewOnly input[name=month_time]').val(month_time);
                $('#approvedLoanCapitalViewOnly textarea[name=note]').val(note);
                $('#approvedLoanCapitalViewOnly .loan_purpose').html(loan_purpose);
                $('#approvedLoanCapitalViewOnly .disbursement_form').html(disbursement_form);
                $('#approvedLoanCapitalViewOnly .info_receive_disbursement').val(info_receive_disbursement);
                $('#approvedLoanCapitalViewOnly .pay').html(pay);
                $('#approvedLoanCapitalViewOnly .disbursement_date_by_user').html(disbursement_date_by_user);
            } else {
                $('#approvedLoanCapitalViewOnly span.score_min').html('');
                $('#approvedLoanCapitalViewOnly div.txt_status').html('');
                $('#approvedLoanCapitalViewOnly input[name=score]').val('');
                $('#approvedLoanCapitalViewOnly input[name=max_money]').val('');
                $('#approvedLoanCapitalViewOnly input[name=preferential_interest_rate]').val('');
                $('#approvedLoanCapitalViewOnly input[name=month_time]').val('');
                $('#approvedLoanCapitalViewOnly textarea[name=note]').val('');
                $('#approvedLoanCapitalViewOnly .loan_purpose').html('');
                $('#approvedLoanCapitalViewOnly .disbursement_form').html('');
                $('#approvedLoanCapitalViewOnly .info_receive_disbursement').val('');
                $('#approvedLoanCapitalViewOnly .pay').html('');
                $('#approvedLoanCapitalViewOnly .disbursement_date_by_user').html('');
            }

            $('#approvedLoanCapitalViewOnly textarea[name=reason]').val('');
            $('#approvedLoanCapitalViewOnly .reason').addClass('hidden');
            $('#approvedLoanCapitalViewOnly input[name=disbursement_date]').val('');
            $('#approvedLoanCapitalViewOnly input[name=action]:first').prop('checked', true);
        });
        $(document).on('click','.review-score',function(){
            var personnel_id = $(this).attr('data-personnel_id');
            $(".list-loan-capital-history").addClass('hidden');
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data = {
                        personnel_id : personnel_id,
                    };
                    
            $.ajax({
                method: "POST",
                url: '{{ route("list-loan-capital-history") }}',
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
                        var data = response.data;
                        if (data.length > 0) {
                            $(".list-loan-capital-history").removeClass('hidden');
                            var html = '';

                            $.each(data, function(key,value){
                                var res = value['received_date'].split("-");
                                var received_date = res[2] + '/' + res[1] + '/' + res[0];

                                html += '<tr class="text-center">';
                                    html += '<td>' + formatNumber(value['max_money'], '.', ',') + '</td>';
                                    html += '<td>' + value['disbursement_date']+ '</td>';
                                    html += '<td>' + value['repayment_period']+ '</td>';
                                    html += '<td>' + received_date + '</td>';
                                    html += '<td>' + value['interest_rate']+ '</td>';
                                    html += '<td>' + value['preferential_interest_rate'] + '</td>';
                                    html += '<td>' + value['count_month_preferential'] + '</td>';
                                    html += '<td>' + value['deferred_interest'] + '</td>';
                                    html += '<td>' + value['interest_file_late'] + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_interest']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_interest_incurred']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round(value['total_paid_money']), '.', ',') + '</td>';
                                    html += '<td>' + formatNumber(Math.round((value['max_money'] + value['total_interest'] + value['total_interest_incurred']) - value['total_paid_money'] ), '.', ',')+ '</td>';
                                html += '</tr>';
                            });

                            $(".list-loan-capital-history tbody").html(html);
                        }

                    }
                },
                error: function (error) {
                    alert('error');
                }
            });



            var loan_capital_id = $(this).attr('data-loan_capital_id');
            var fullname = $(this).attr('data-fullname');
            var score = $(this).attr('data-score');
            var max_money = $(this).attr('data-max_money');
            // var preferential_interest_rate = $(this).attr('data-preferential_interest_rate');
            var month_time = $(this).attr('data-month_time');
            var note = $(this).attr('data-note');
            var loan_purpose = $(this).attr('data-loan_purpose');
            var disbursement_form = $(this).attr('data-disbursement_form');
            var info_receive_disbursement = $(this).attr('data-info_receive_disbursement');
            var pay = $(this).attr('data-pay');
            var disbursement_date_by_user = $(this).attr('data-disbursement_date_by_user');

            if (loan_purpose == 1) {
                loan_purpose = 'Vay mua nhà'
            } else if(loan_purpose == 2) {
                loan_purpose = 'Vay mua xe'
            } else {
                loan_purpose =  $(this).attr('data-another_purpose');
            }

            if (disbursement_form == 1) {
                disbursement_form = 'GN trực tiếp tới đơn vị thụ hưởng'
            } else {
                disbursement_form = 'GN tới người vay'
            }

            if (pay == 1) {
                pay = 'Trừ vào lương'
            } else {
                pay = 'Tự trả qua chuyển  khoản'
            }

            $('#reviewScore input[name=loan_capital_id]').val(loan_capital_id);
            $('#reviewScore .fullname').html(fullname);

            var file = $(this).attr('data-file');
            file = file.split(",");
            $('#reviewScore .file').html('');
            
            $.each(file, function(key,value){
                if (value) {
                    var ext = value.split('.').pop();
                                
                    if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="{{ asset("images/general/document.png") }}"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    } else {
                        var image_path = '<div class="col-sm-3 form-group text-center"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"><div class="dz-filename"><span>'+ value +'</span></div></a></div>';
                    }

                    // var image_path = '<div class="col-sm-3"><a href="'+ "{{ url('/images') }}" + "/" +value +'" target="_blank"><img style="max-width: 100%;height:75px" src="'+ "{{ url('/images') }}" + "/" +value +'"></a></div>';
                    $('#reviewScore .file').append(image_path);
                }
            });

            if (score || max_money || preferential_interest_rate || month_time) {
                if (score < {{ $config_loan_capital->score_min }}) {
                    txt_status = '<div href="#" class="dahuy text-center" style="cursor: inherit">Không đạt tiêu chuẩn</div>';
                } else {
                    txt_status = '<div href="#" class="daduyet text-center" style="cursor: inherit">Đạt tiêu chuẩn</div>';
                }

                $('#reviewScore span.score_min').html('('+ score + '/{{ $config_loan_capital->score_min }})');
                $('#reviewScore div.txt_status').html(txt_status);
                $('#reviewScore input[name=score]').val(score);
                $('#reviewScore input[name=money]').val(max_money.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
                $('#reviewScore input[name=max_money]').val(max_money);
                $('#reviewScore input[name=month_time]').val(month_time);
                $('#reviewScore textarea[name=note]').val(note);
                $('#reviewScore .loan_purpose').html(loan_purpose);
                $('#reviewScore .disbursement_form').html(disbursement_form);
                $('#reviewScore .info_receive_disbursement').val(info_receive_disbursement);
                $('#reviewScore .pay').html(pay);
                $('#reviewScore .disbursement_date_by_user').html(disbursement_date_by_user);
            } else {
                $('#reviewScore span.score_min').html('');
                $('#reviewScore div.txt_status').html('');
                $('#reviewScore input[name=score]').val('');
                $('#reviewScore input[name=max_money]').val('');
                $('#reviewScore input[name=preferential_interest_rate]').val('');
                $('#reviewScore input[name=month_time]').val('');
                $('#reviewScore textarea[name=note]').val('');
                $('#reviewScore .loan_purpose').html('');
                $('#reviewScore .disbursement_form').html('');
                $('#reviewScore .info_receive_disbursement').val('');
                $('#reviewScore .pay').html('');
                $('#reviewScore .disbursement_date_by_user').html('');
            }

            $('#reviewScore textarea[name=reason]').val('');
            $('#reviewScore .reason').addClass('hidden');
            $('#reviewScore input[name=disbursement_date]').val('');
            $('#reviewScore input[name=action]:first').prop('checked', true);
        });

        function handling(data){
            $.ajax({
                method: "POST",
                url: '{{ url("toh_hrm/api/approved-loan-capital") }}',
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
                    } else if(response.status == 400){
                        Swal.fire({
                            type: 'warning',
                            html: response.message,
                            allowOutsideClick: false
                        })
                    } else{
                        var obj_errors = response.message;
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
                },
                error: function (error) {
                    Swal.fire({
                        type: 'warning',
                        html: error.responseJSON,
                    })
                }
            });
        }

        function approvedLoanCapital(){
            $.ajaxSetup(
            {
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var status = $('#approvedLoanCapital input[name=action]:checked').val();

            if (status == 2) {
                var max_money = $('#approvedLoanCapital input[name=max_money]').val().replace(/,/g,'');
                var rest_fund_loan_capital = {{ $rest_fund_loan_capital }};
                var data = {
                                loan_capital_id : $('#approvedLoanCapital input[name=loan_capital_id]').val(),
                                score : $('#approvedLoanCapital input[name=score]').val(),
                                max_money : max_money,
                                disbursement_date : $('#approvedLoanCapital input[name=disbursement_date]').val().trim(),
                                month_time : $('#approvedLoanCapital input[name=month_time]').val().replace(/,/g,''),
                                status : status,
                            };
                if (max_money > rest_fund_loan_capital) {
                    Swal.fire({
                        type: 'warning',
                        text: 'Quỹ tín dụng hiện tại không đủ. Bạn vẫn muốn duyệt?',
                        showCancelButton: true,
                        confirmButtonText: 'Có',
                        cancelButtonText: 'Không',
                    }).then(function (result) {
                        if(result.value){
                            handling(data);
                        }
                    })
                } else {
                    handling(data);
                }

            } else {
                var data = {
                        loan_capital_id : $('#approvedLoanCapital input[name=loan_capital_id]').val(),
                        reason : $('#approvedLoanCapital textarea[name=reason]').val(),
                        status : status,
                    };
                handling(data);
            }
        } 

        function reviewScore(){
            Swal.fire({
                   type: 'warning',
                   text: 'Phê duyệt sẽ không được sửa lại, bạn có chắc chắn muốn phê duyệt?',
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
                                loan_capital_id : $('#reviewScore input[name=loan_capital_id]').val(),
                                // score : $('#reviewScore input[name=score]').val(),
                                note : $('#reviewScore textarea[name=note]').val(),
                                reason : $('#reviewScore textarea[name=reason]').val(),
                                status : $('#reviewScore input[name=action_ttt]:checked').val(),
                            };
                            
                    $.ajax({
                        method: "POST",
                        url: '{{ url("toh_hrm/api/review-score") }}',
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
                                    allowOutsideClick: false
                                })
                            }
                        },
                        error: function (error) {
                    
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

        function remindMonthLoanCapital(){
            Swal.fire({
                   type: 'warning',
                   text: 'Sẽ có email thông báo trả nợ đến những người chưa trả của tháng hiện tại, bạn có chắc chắn muốn gửi?',
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
                            };
                            
                    $.ajax({
                        method: "POST",
                        url: '{{ url("toh_hrm/api/remind-month-loan-capital") }}',
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
                            // alert('Errors');
                        }
                    });
                }
            })
        }

        function reviewFile(){  
            Swal.fire({
                   type: 'warning',
                   text: 'Phê duyệt xong sẽ không được chỉnh sửa lại, bạn có chắc chắn muốn phê duyệt?',
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
                                loan_capital_id : $('#reviewFile input[name=loan_capital_id]').val(),
                                status_file : $('#reviewFile input[name=approved_file]:checked').val(),
                            };
                            
                    $.ajax({
                        method: "POST",
                        url: '{{ url("toh_hrm/api/approved-file") }}',
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