@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')
<?php
    // echo "<pre>";
    // print_r($data);die;
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
                <h4 class="title-fuction">Quản trị chi phí</h4>
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
                                            <label class="col-sm-4 control-label">Quỹ</label>
                                            <div class="col-sm-8">
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
                                    <div class="form-group  col-lg-12">
                                        <div class="col-sm-offset-3 col-sm-5">
                                            <select name="type" class="form-control">
                                                <option value="" <?php if( isset( $_GET['type'] )  && $_GET['type'] == "" ): ?> selected="selected" <?php endif; ?>>Loại chi phí</option>
                                                <option value="0" <?php if( isset( $_GET['type'] )  && $_GET['type'] ==0 && $_GET['type'] != "" ): ?> selected="selected" <?php endif; ?>>Chi phí phát sinh</option>
                                                <option value="1" <?php if( isset( $_GET['type'] )  && $_GET['type'] ==1 ): ?> selected="selected" <?php endif; ?>>Chi phí cố định</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">Danh sách chi phí  
                    @if(in_array('chiphi-themchiphi',$arr_route))
                    <a href="{{ route('getExpenseAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
                    @endif
                </h4>
                <div class="checkbox">
<!--                     <label class="checkbox-inline funds_timeout"><input type="checkbox" value="" checked>  <i>Ẩn chi phí đã hết thời hạn</i></label> -->
                </div>
                @if( count($data)>0 )
                <div class="table-responsive detailType">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Tiêu đề chi phí </th>
                                <th>Giá trị</th>
                                <th>Quỹ</th>
                                <th>Ngày phát sinh</th>
                                <th>Người tạo</th>
                                <th>Loại</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            <?php $total = 0; ?>
                            @foreach ($data as $val)
                            <?php
                                $price = ($val['value']*$val['param']*$val['percent'])/100;
                                $total += $price;
                                $class = '';
                            ?>
                            <tr class={{ $class }}>
                            <td style="width: 35%;"> {{ $val['title'] }} </td>
                            <td>
                                {{ BatvHelper::formatPriceSpecial( $price ) }}
                                <input type="hidden" name="{{ $class }}" value="{{ $val['value'] }}">
                            </td>
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
                            <td>{{ BatvHelper::formatDate($val['valid_from'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</td>
                            <td> {{ BatvHelper::getInfoUser( $val['created_by'] ) }} </td>
                            <td>
                                @if( $val['type'] ==0 )
                                C/p phát sinh
                                @else
                                C/p cố định
                                @endif
                            </td>
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
                                                        {{ BatvHelper::formatPriceSpecial( $val['value'] ) }} VNĐ 
                                                        @if( $val['value_usd'] >0 )
                                                            ( {{ BatvHelper::formatPriceSpecial( $val['value_usd'] ) }} USD )
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

                                @if( $val['created_by'] == Auth::user()->id || Auth::user()->id == 1 )
                                <a class="btn-edit" href="{{ route('getExpenseEdit',['id'=>$val['expense_id']]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                <a class="btn-delete" href="{{ route('getExpenseDel',['id'=>$val['expense_id'] ])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"><img src="{{ asset('images/general/remove.png') }}"></a>
                                @endif
                            </td>
                            </tr>
                            
                            @endforeach
                            <tr style="background: rgba(255, 0, 0, 0.56);">
                                <td colspan="1" style="text-align: center;"><b>TỔNG HỢP</b></td>
                                <td>
                                    <b>{{ BatvHelper::formatPriceSpecial( $total ) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-danger" role="alert"> Không tìm thấy kết quả tìm kiếm</div>
                @endif
            </div>
            <div class="col-lg-12 text-right">
                {{ $data->appends(Request::query())->render()  }} 
            </div>
        </div>
    </div>
    <style type="text/css">
        .detailType input[type="text"]{ background: none;border: none;font-weight: 600; width: 100px; }
    </style>
    <script type="text/javascript">
        $('select[name="viewfast"],select[name="type"]').change(function(){
            $("#autoClick").click();
        });
    </script>

</div>
@endsection