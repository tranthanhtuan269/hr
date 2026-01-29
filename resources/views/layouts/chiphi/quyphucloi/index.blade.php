@extends('layouts.master')
@section('title', 'Chi phí')
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
    @include('layouts.chiphi.menuleft')
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                @if (session('flash_message_err') != '')
                <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
                @endif
                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
                @endif
				<h4 class="title-fuction">
					Cài đặt quỹ phúc lợi
				</h4>
				<form  class="form-horizontal" method="POST">
					<div class="form-group">
						<label class="col-sm-4 control-label">Nhập tổng số tiền quỹ phúc lợi còn lại <span class="required">*</span></label>
						<div class="col-sm-5">	
		                    <input type="text" onkeyup="format_curency( this.value );" id="numFormatResult" class="form-control" value="{{ BatvHelper::formatPriceSpecial($funds_id_default['value']) }}"  required>
		                    <input type="hidden" name="value" id="result" value="{{ $funds_id_default['value'] }}">
					    </div>
					</div>
		            <div class="form-group">
		                <label class="col-sm-4 control-label">Thời gian hiệu lực <span class="required">*</span></label>
		                <div class="col-sm-5">
		                    <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="valid_date" value="{{  BatvHelper::formatDate($funds_id_default['valid_date'],'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}">
		                </div>
		            </div>
					<div class="text-center">
						<input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
					</div>
		            {{ csrf_field()}}
				</form>

                <h4 class="title-fuction">Danh sách chi tiêu quỹ phúc lợi từ {{ BatvHelper::formatDate($valid_from,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }} đến {{ BatvHelper::formatDate($valid_to,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}
                    @if(in_array('chiphi-themchitieuquyphucloi',$arr_route))
                    	<a href="{{ route('getWelfareFundsAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
                    @endif
                </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p><b>Tổng số tiền đã chi</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px">{{ BatvHelper::formatPriceSpecial($infoSpendMoneyWelfareFundsbyMonth) }}</span></p>
                        <p><b>Tổng tiền còn lại</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px">{{ BatvHelper::formatPriceSpecial($funds_id_default['value'] + $infoTotalPriceWelfareFunds - $infoSpendMoneyWelfareFunds) }}</span></p>
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
                                <th>&nbsp;&nbsp;</th>
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
                                    <td> {{ BatvHelper::formatPriceSpecial($val['value']) }}  </td>
                                    <td>{{ $val['description'] }}</td>
                                    <td>
                                        @if(in_array('chiphi-suachitieuquyphucloi',$arr_route))
                                        <a class="btn-edit" href="{{ route('getWelfareFundsEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                        @endif
                                        @if(in_array('chiphi-xoachitieuquyphucloi',$arr_route))
                                        <a class="btn-delete" href="{{ route('welfareFundsDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                        <img src="{{ asset('images/general/remove.png') }}"></a>
                                        @endif
                                    </td>
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