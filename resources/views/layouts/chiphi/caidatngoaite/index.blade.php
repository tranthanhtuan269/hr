@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')

<div class="row content-function">
    <!-- Danh muc -->
    @include('layouts.chiphi.menuleft')
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="title-fuction">Cài đặt ngoại tệ
                    @if(in_array('chiphi-themcauhinhngoaite',$arr_route))
                    <a href="{{ route('getSettingCurrencyAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
                    @endif
                </h4>

                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
                @endif
                @if(count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if( count($data)>0 )
                <div class="table-responsive detailType">
                    <table class="table table-hover">
                        <tbody>
                            <tr> 
                                <th class="text-center">STT</th>
                                <th class="text-center">Tiêu đề</th>
                                <th class="text-center">Giá trị</th>
                                <th class="text-center">Thời gian hiệu lực</th>
                                <th class="text-center">Thời gian hết hiệu lực</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            <?php 
                              if( !isset($_GET['page']) || $_GET['page']==1 ){
                                $i  = 1;
                              }else{
                                $i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                              }
                            ?>
                              @foreach ($data as $val)
                             <tr class="text-center">
                              <td>{{$i}}</td>
                              <td> {{ $val['title'] }} </td>
                              <td> {{ BatvHelper::formatPriceSpecial($val['value']) }} </td>
                              <td> {{ BatvHelper::formatDate($val['apply_from'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }} </td>
                              <td> {{ BatvHelper::formatDate($val['apply_to'],"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }} </td>
                              <td>
                                  @if(in_array('chiphi-suacauhinhngoaite',$arr_route))
                                    <a class="btn-edit" href="{{ route('getSettingCurrencyEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                  @endif
                                  @if(in_array('chiphi-xoacauhinhngoaite',$arr_route))
                                    <a class="btn-delete" href="{{ route('getSettingCurrencyDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                  <img src="{{ asset('images/general/remove.png') }}"></a>
                                  @endif
                              </td>  
                            </tr>
                              <?php $i++ ?>
                            @endforeach
                    
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-danger" role="alert"> Không tìm thấy kết quả tìm kiếm</div>
                @endif
            </div>
            <div class="col-lg-12 text-right">
                {{ $data->appends(Request::all())->links() }}
            </div>
        </div>
    </div>

</div>
@endsection