@extends('layouts.master')
@section('title', 'Lương thưởng')
@section('content')
<?php
    if( !empty(  $_GET['valid_from'] ) && !empty(  $_GET['valid_to']  ) ){
        $valid_from = BatvHelper::formatDate($_GET['valid_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
        $valid_to = BatvHelper::formatDate($_GET['valid_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
    }else{  
        $valid_from = date('Y')."-".date('m')."-"."01";

        $numberDay = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        $valid_to = date('Y')."-".date('m')."-".$numberDay;
    }
?>
<div class="row content-function">
    <!-- Danh muc -->
    @include('layouts.luongthuong.client.menuleft')
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                @if (session('flash_message_err') != '')
                <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
                @endif
                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
                @endif

                <h4 class="title-fuction">Danh sách chi tiêu quỹ phúc lợi từ {{ BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }} đến {{ BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}
                </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p><b>Tổng số tiền đã chi</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px">{{ BatvHelper::formatPrice($infoSpendMoneyWelfareFundsbyMonth) }}</span></p>
                        <p><b>Tổng tiền còn lại</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px">{{ BatvHelper::formatPrice($funds_id_default['value'] + $infoTotalPriceWelfareFunds - $infoSpendMoneyWelfareFunds) }}</span></p>
                    </div>
                    <div class="col-sm-6">
                        <form action="" method="get">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Từ tháng</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="datepicker form-control" name="valid_from" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <div class="row">
                                        <label class="col-sm-3 control-label">Đến tháng</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="datepicker form-control" name="valid_to" required pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{ BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}">
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
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>STT</th>
                                <th>Tiêu đề</th>
                                <th>Giá trị</th>
                                <th>Mô tả</th>
                            </tr>
                            @if(!empty($data))
                                <?php 
                                  if( !isset($_GET['page']) || $_GET['page']==1 ){
                                    $i  = 1;
                                  }else{
                                    $i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                                  }
                                ?>
                                @foreach ($data as $val)
                                <tr>
                                    <td class="text-center">{{$i}}</td>
                                    <td> {{ $val['title'] }} </td>
                                    <td> {{ BatvHelper::formatPrice($val['value']) }}  </td>
                                    <td> {{ $val['description'] }} </td>
                                </tr>
                                <?php $i++ ?>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12 text-right">
                {{ $data->appends(Request::all())->links() }} 
            </div>
        </div>
    </div>
</div>
</div>
@endsection