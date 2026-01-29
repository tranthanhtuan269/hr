@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')
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
            </div>
        
            <div class="col-lg-12">
                <h4 class="title-fuction">Danh sách ký quỹ
                    @if(in_array('chiphi-themkyquy',$arr_route))
                    <a href="{{ route('getSignFundsAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
                    @endif
                </h4>
                <p><b>Tổng tiền</b> : <span style="color: red;font-weight: bold;font-style: italic;font-size:15px">{{ BatvHelper::formatPriceSpecial($total_price) }}</span></p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>STT</th>
                                <th>Người cầm quỹ</th>
                                <th>Ngày nhận</th>
                                <th>Số lượng</th>
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
                                    <td>{{$i}}</td>
                                    <td> {{ BatvHelper::getInfoUser($val['personnel_id']) }} </td>
                                    <td> {{ BatvHelper::formatDate($val['received_date'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }} </td>
                                    <td> {{ BatvHelper::formatPriceSpecial($val['value']) }} </td>
                                    <td>
                                        @if(in_array('chiphi-suakyquy',$arr_route))
                                        <a class="btn-edit" href="{{ route('getSignFundsEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                        @endif
                                        @if(in_array('chiphi-xoakyquy',$arr_route))
                                        <a class="btn-delete" href="{{ route('signFundsDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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