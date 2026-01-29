@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')
<?php
    if( !isset( $_GET['viewfast'] ) || $_GET['viewfast'] == '' ){
        if( !empty(  $_GET['valid_from'] ) && !empty(  $_GET['valid_to']  ) ){
            $valid_from = BatvHelper::formatDate($_GET['valid_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
            $valid_to = BatvHelper::formatDate($_GET['valid_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        }else{  
            $valid_from = date('Y')."-".date('m')."-"."01";
    
            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $valid_to = date('Y')."-".date('m')."-".$numberDay;
        }
    }else{
    if( $_GET['viewfast'] == 0 ){
            $valid_from = date('Y')."-".date('m')."-"."01";
    
            $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $valid_to = date('Y')."-".date('m')."-".$numberDay;
    }elseif( $_GET['viewfast'] == 1 ){
            $date_from = date('Y')."-".date('m')."-"."01";
            $date_from = strtotime($date_from.'-1 month');
            $valid_from = date('Y-m-d', $date_from);
    
            $convert_to = explode("-",$valid_from);
            $numberDay = cal_days_in_month(CAL_GREGORIAN,$convert_to[1], $convert_to[0]);
            $valid_to = $convert_to[0]."-".$convert_to[1]."-".$numberDay;
        }elseif ( $_GET['viewfast'] == 2 ) {
            if(  (int)date('m') >=1 && (int)date('m')<=3 ){
                $valid_from  = date('Y').'-01-01';
    
                $numberDay = cal_days_in_month(CAL_GREGORIAN,3, date('Y'));
                $valid_to  = date('Y').'-03-'.$numberDay;
            }elseif ( (int)date('m') >3 && (int)date('m')<=6 ) {
                $valid_from  = date('Y').'-04-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,6, date('Y'));
                $valid_to  = date('Y').'-06-'.$numberDay;
            }elseif ( (int)date('m') >6 && (int)date('m')<=9 ) {
                $valid_from  = date('Y').'-07-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,9, date('Y'));
                $valid_to  = date('Y').'-09-'.$numberDay;
            }else{
                $valid_from  = date('Y').'-10-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y'));
                $valid_to  = date('Y').'-12-'.$numberDay;
            }
        }elseif ( $_GET['viewfast'] == 3 ) {
            if(  (int)date('m') >=1 && (int)date('m')<=3 ){
                $valid_from  = (date('Y')-1).'-09-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y')-1);
                $valid_to  = ( date('Y')-1 ).'-12-'.$numberDay;
            }elseif ( (int)date('m') >3 && (int)date('m')<=6 ) {
                $valid_from  = date('Y').'-01-01';
    
                $numberDay = cal_days_in_month(CAL_GREGORIAN,3, date('Y'));
                $valid_to  = date('Y').'-03-'.$numberDay;
            }elseif ( (int)date('m') >6 && (int)date('m')<=9 ) {
                $valid_from  = date('Y').'-04-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,6, date('Y'));
                $valid_to  = date('Y').'-06-'.$numberDay;
            }else{
                $valid_from  = date('Y').'-07-01';
                $numberDay = cal_days_in_month(CAL_GREGORIAN,9, date('Y'));
                $valid_to  = date('Y').'-09-'.$numberDay;
            }
        }elseif ( $_GET['viewfast'] == 4 ) {
            $valid_from = date('Y')."-01-"."01";
            $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y'));
            $valid_to  = date('Y').'-12-'.$numberDay;
        }elseif ( $_GET['viewfast'] == 5 ) {
            $valid_from = (date('Y')-1)."-01-"."01";
    
            $numberDay = cal_days_in_month(CAL_GREGORIAN,12, date('Y')-1);
            $valid_to  = ( date('Y')-1 ).'-12-'.$numberDay;
        }
    }
    // echo $valid_to;die;
    ?>
<div class="row">
    <!-- Danh muc -->
    @include('layouts.chiphi.menuleft')
    <div class="col-lg-10">
        <h4 class="title-fuction">Thông tin tổng hợp</h4>
                <div class="box_search">
                    <div class="row">
                        <form action="" method="get">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <label class="col-sm-4 control-label">Từ tháng</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="datepicker form-control" name="valid_from" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <label class="col-sm-4 control-label">Đến tháng</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="datepicker form-control" name="valid_to" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <label class="col-sm-4 control-label">Người tạo</label>
                                            <div class="col-sm-8">
                                                @if(!empty($getListManager))
                                                <select name="personnel" class="form-control select2 narrow wrap">
                                                    <option value="">Tất cả</option>
                                                    @foreach($getListManager as $personnel)
                                                    <option value="{{ $personnel->id }}" <?php if( isset( $_GET['personnel'] )  && $personnel->id == $_GET['personnel']): ?> selected="selected" <?php endif; ?>>{{ $personnel->name }}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <div class="row">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-sm btn-orange" id="autoClick">Tìm kiếm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label class="col-sm-3 control-label">Quỹ</label>
                                        <div class="col-sm-5">
                                            @if(!empty($listFunds))
                                            <select name="funds" class="form-control select2 narrow wrap">
                                                <option value="">Tất cả</option>
                                                @foreach($listFunds as $fund)
                                                <option value="{{ $fund->id }}" <?php if( isset( $_GET['funds'] )  && $fund->id == $_GET['funds']): ?> selected="selected" <?php endif; ?>>{{ $fund->title }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group  col-lg-12">
                                        <label class="col-sm-3 control-label">Xem nhanh</label>
                                        <div class="col-sm-5">
                                            <select name="viewfast" class="form-control">
                                                <option value="" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] == "" ): ?> selected="selected" <?php endif; ?>>Chọn thời gian</option>
                                                <option value="0" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] ==0 && $_GET['viewfast'] != "" ): ?> selected="selected" <?php endif; ?>>Tháng này</option>
                                                <option value="1" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] ==1 ): ?> selected="selected" <?php endif; ?>>Tháng trước</option>
                                                <option value="2" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] ==2 ): ?> selected="selected" <?php endif; ?>>Quý này</option>
                                                <option value="3" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] ==3 ): ?> selected="selected" <?php endif; ?>>Quý trước</option>
                                                <option value="4" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] ==4 ): ?> selected="selected" <?php endif; ?>>Năm này</option>
                                                <option value="5" <?php if( isset( $_GET['viewfast'] )  && $_GET['viewfast'] ==5 ): ?> selected="selected" <?php endif; ?>>Năm trước</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        <form action="" method="post">
            <h4 class="title-fuction">
                Thông tin tổng hợp từ {{ BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }} đến {{ BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}
            </h4>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home"><b>Tổng hợp</b></a></li>
                <li><a data-toggle="tab" href="#menu1"><b>Chi phí chung</b></a></li>
                <li><a data-toggle="tab" href="#menu2"><b>Chi phí nhân sự</b></a></li>
            </ul>

            <?php  $total_price = 0; ?>
                @if(!empty($expenseGeneral))
                    @foreach ($expenseGeneral as $val)
                    <?php $total_price += ($val['value']*$val['param']*$val['percent'])/100; ?>
                    @endforeach
                @endif
                <?php
                    $total = $column_all = $column_loan = 0; 
                ?>
                @foreach ($data as $val)
                    <?php 
                        $total_item = $loan = 0; 

                        if( !empty( $others[$val->personnel_id] ) ) {
                            foreach ( $others[$val->personnel_id]['income_value'] as $k=>$v ) {
                                if( !empty($v) ) {
                                    $total = $total_item += $v;
                                }
                            }
                        }


                        if (isset($arr_loan_capital[$val->personnel_id])) {
                            $loan += round($arr_loan_capital[$val->personnel_id]);
                        }

             
                        $flag_tmp_1 =  $val->salary_overtime + $val->salary_official_work+$val->salary_trial_work+$val->salary_trainee_work+$val->salary_trainee_parttime_work+$val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus-$val->party_fee;   

                        if ($flag_tmp_1 > 0) {
                            $all_company = round($val->salary_overtime + $val->salary_official_work+$val->salary_trial_work+$val->salary_trainee_work+$val->salary_trainee_parttime_work+$val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee +$val->insurance_by_company);
                        } else {
                            $all_company = round($val->insurance_by_company);
                        }
                            
                        $column_all += $all_company;
                        $column_loan += round($loan);
                    ?>
                @endforeach


            <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
                    <div class="table-responsive" id="parent">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Chi phí chung</th>
                                    <th class="text-center">Chi phí nhân sự</th>
                                    {{-- <th class="text-center">Khấu trừ khoản vay</th> --}}
                                    <th class="text-center">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{ BatvHelper::formatPrice( $total_price ) }}</td>
                                    <td class="text-center">{{ BatvHelper::formatPrice($column_all) }}</td>
                                    {{-- <td class="text-center">{{ BatvHelper::formatPrice($column_loan) }}</td> --}}
                                    <td class="text-center">{{ BatvHelper::formatPrice( $total_price +$column_all) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="menu1" class="tab-pane fade">
                    <div class="table-responsive" id="parent">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Chi phí</th>
                                    <th class="text-center">Ngày phát sinh</th>
                                    <th class="text-center">Quỹ</th>
                                    <th class="text-center">Giá trị</th>
                                    <th class="text-center">Người tạo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($expenseGeneral))
                                <?php  $total_price = 0; $stt = 1; ?>
                                @foreach ($expenseGeneral as $val)
                                <?php
                                    $price = ($val['value']*$val['param']*$val['percent'])/100;
                                    ?>
                                <tr>
                                    <td class="text-nowrap" scope="row" style="text-align: center;"> {{ $stt }} </td>
                                    <td>{{ $val['title'] }}</td>
                                    <td style="text-align: center;">{{ BatvHelper::formatDate($val['valid_from'],'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}</td>
                                    <td>
                                        <?php $tmp = 1; ?>
                                        @foreach ( $val['funds_title'] as $item )
                                            {{ $item }}
                                            @if( $tmp < count($val['funds_title']) )
                                                <?php echo ","; ?>
                                            @endif
                                            <?php $tmp++; ?>
                                        @endforeach
                                    </td>
                                    <td style="text-align: center;">{{ BatvHelper::formatPrice( $price ) }}</td>
                                    <td style="text-align: center;">{{ BatvHelper::getInfoUser( $val['created_by'] ) }}</td>
                                    <td>
                                      @if(in_array('chiphi-xemchitietchiphi',$arr_route))
                                        <a href="#" data-toggle="modal" data-target="#myModal_view{{ $val['expense_id'] }}"><img src="{{ asset('images/general/eye.png') }}"></a>
                                        <!--  DETAIL POPUP FUNDS -->
                                        <div id="myModal_view{{ $val['expense_id'] }}" class="modal fade" role="dialog">
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
                                                                <b>Tên loại chi phí : </b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ $val['title'] }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group  row">
                                                            <div class="col-sm-4">
                                                                <b>Giá trị :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ BatvHelper::formatPrice( $val['value'] ) }} VNĐ 
                                                                @if( $val['value_usd'] >0 )
                                                                    ( {{ BatvHelper::formatPrice( $val['value_usd'] ) }} USD )
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="form-group  row">
                                                            <div class="col-sm-4">
                                                                <b>Người tạo :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ BatvHelper::getInfoUser( $val['created_by'] ) }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Mô tả :</b>  
                                                            </div>
                                                            <div class="col-sm-8" style="word-wrap: break-word;">
                                                                {!! nl2br($val['description']) !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Loại :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                @if( $val['type'] ==0 ) Chi phí phát sinh @else Chi phí cố định @endif
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Quỹ :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <?php $param = 1; ?>
                                                                @foreach($val['funds_title'] as $k_fund=> $fund)
                                                                    {{ $fund }} ({{ $val['percent_arr'][$k_fund]}} %)
                                                                    @if( $param < count($val['funds_title']) )
                                                                        <?php echo "-"; ?>
                                                                    @endif
                                                                    <?php $param++; ?>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Thời gian hiệu lực :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ BatvHelper::formatDate($val["valid_from"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Thời gian hết hiệu lực :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ BatvHelper::formatDate($val["valid_to"],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Ngày tạo :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ BatvHelper::formatDate($val["created_at"],"Y-m-d H:i:s", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=true) }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                                <b>Link file đính kèm :</b>  
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <a href="{{ $val['link_dropbox'] }}" target="_blank" style="word-wrap: break-word;">{{ $val['link_dropbox'] }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                      @endif

                                    </td>
                                </tr>
                                <?php $total_price += $price; $stt++; ?>
                                @endforeach
                                <tr style="background: rgba(255, 0, 0, 0.56);text-align: center;">
                                    <td colspan="4"><b>TỔNG HỢP</b></td>
                                    <td><b>{{ BatvHelper::formatPrice($total_price) }}</b></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="menu2" class="tab-pane fade">
                    <div class="table-responsive" id="parent">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="checkAllType" checked></th>
                                    <th class="text-center">Họ và tên</th>
                                    <th class="text-center">Tổng</th>
                                </tr>
                            </thead>
                            <tbody class="detailType">

                                @if(!empty($data))
                                <?php
                                    $total = $column_all = $column_salary = $column_allowance = $column_bonus = $column_insurance = $column_others = $column_loan = 0; 
                                ?>
                                @foreach ($data as $val)
                                    <?php $total_item = $loan = 0; ?>
                                        @if( !empty( $others[$val->personnel_id] ) )
                                            @foreach ( $others[$val->personnel_id]['income_value'] as $k=>$v )
                                                @if( !empty($v) )
                                                    <?php $total = $total_item += $v; ?> 
                                                @endif
                                            @endforeach
                                        @endif
                                    <?php
                                        if (isset($arr_loan_capital[$val->personnel_id])) {
                                            $loan += round($arr_loan_capital[$val->personnel_id]);
                                        }
                                    ?>


                                    <tr style="text-align: center;">
                                        <td><input type="checkbox" name="personnel_id[{{ $val->personnel_id }}]" value="{{ $val->personnel_id }}"  checked></td>
                                        <td class="text-nowrap" scope="row"><a class="btn-edit" href="{{ route('getPersonnelEdit',['id'=>$val->personnel_id]) }}">{{ str_limit( $val->fullname, $limit = 35, $end = '...') }}</a></td>
                                        <td> 
                                            <?php   
                                                $flag_tmp_1 =  $val->salary_overtime + $val->salary_official_work+$val->salary_trial_work+$val->salary_trainee_work+$val->salary_trainee_parttime_work+$val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->insurance-$val->money_work_late-$val->welfare_fund+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus-$val->party_fee;   

                                                if ($flag_tmp_1 > 0) {
                                                    $all_company = round($val->salary_overtime + $val->salary_official_work+$val->salary_trial_work+$val->salary_trainee_work+$val->salary_trainee_parttime_work+$val->salary_parttime_work+$val->management_allowance+ $val->lunch_allowance+ $val->travel_allowance+$val->phone_allowance+$val->movement_allowance+$val->work_bonus-$val->money_work_late+$val->parking_fee_allowance+$total_item+$val->other_tax_allowance+$val->laptop_allowance+$val->mulct_money_awol + $val->holiday_bonus - $val->party_fee +$val->insurance_by_company);
                                                } else {
                                                    $all_company = round($val->insurance_by_company);
                                                }
                                                    
                                                $column_all += $all_company;
                                            ?>

                                            {{ BatvHelper::formatPrice($all_company) }}
                                            <input type="hidden" name="item[{{ $val->personnel_id }}]" value="{{ $all_company }}">
                                        </td>
                                        <!-- Tổng nhận -->
                                    </tr>
                                @endforeach
                                <tr style="background: rgba(255, 0, 0, 0.56);text-align: center;">
                                    <td></td>
                                    <td><b>TỔNG HỢP</b></td>
                                    <td><b class="total">{{ BatvHelper::formatPrice($column_all) }}</b>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{ csrf_field()}}
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('select[name="viewfast"]').change(function(){
            $("#autoClick").click();
        });
        
        $("#checkAllType").click(function(){
          $('.detailType input:checkbox').not(this).prop('checked', this.checked);
        });
        
        $('input[type=checkbox]').change(function(){
            var total_salary = total_allowance = total_bonus = total_insurance = total_welfare_fund = total_party_fee = total_insurance_personnel = total_loan = total_item = 0;
            $('.detailType input[type=checkbox]:checked').each(function(index) {
                var round = Math.round;
                var id;
                id = $(this).val();

                total_item += round( $("input[name='item["+id+"]']").val() );
            });

            $("b.total").html( formatNumber(total_item, '.', ',') )
        });
    
    });
</script>
@endsection