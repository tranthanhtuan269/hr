@extends('layouts.master')
@section('title', 'Thiết bị')
@section('content')
<div class="row content-function">
    <!-- Danh muc -->
    @include('layouts.thietbi.menuleft')
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                @if (session('flash_message_err') != '')
                <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
                @endif
                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
                @endif
                <h4 class="title-fuction">Bàn giao thiết bị</h4>
                <div class="form-group col-lg-6 col-lg-offset-2">
                    <form class="form-horizontal" method="get" action="">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Nhập nội dung</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="text_search" value="{{ Request::get('text_search') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-4 control-label">Danh mục</label>
                                    <div class="col-sm-8">
                                        <select name="c_id" class="form-control">
                                            <option value="0" selected>--Chọn--</option>
                                            @if(!empty($cateDevice))
                                            {!! $cateDevice !!}
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Người quản lý</label>
                                    <div class="col-sm-8">
                                        @if(!empty($listPersonnel))
                                        <select name="personnel_id" id="my-select" required>
                                            <option value="0">--Chọn--</option>
                                            @foreach($listPersonnel as $val)
                                            <option value="{{ $val->id }}" <?php  if(!empty(Request::input('personnel_id')) && Request::input('personnel_id')== $val->id) { echo "selected"; }?> >{{ $val->fullname }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                        <script type="text/javascript">
                                            $(function() {
                                                $('#my-select').searchableOptionList({
                                                    maxHeight: '250px'
                                                });
                                            }); 
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Ngày bàn giao :</label>
                                    <div class="col-sm-8">
                                        <?php
                                            $value = date('d/m/Y');
                                            if ( !empty( Request::get('fillter_date') ) ){
                                                $value = Request::get('fillter_date');
                                            }
                                          ?>
                                            <input type="text"  class="form-control datepicker" name="fillter_date"  required placeholder="Ngày" value="{{$value}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                        {{ csrf_field()}}
                    </form>
                    
                </div>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">Danh sách bàn giao thiết bị 			                        
                    @if(in_array('thietbi-thembangiaothietbi',$arr_route))
                    <a href="{{ route('getTakeDeviceAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
                    @endif
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên thiết bị </th>
                                <th class="text-center">Thuộc danh mục </th>
                                <th class="text-center">Người quản lý</th>
                                <th class="text-center">Ngày bàn giao</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Trạng thái</th>
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
                                <td class="text-left"> {{ $val->title }} </td>
                                <td>{{ $val->c_title }}</td>
                                <td>{{ BatvHelper::getInfoUser($val->personnel_id) }}</td>
                                <td> {{ BatvHelper::formatDate($val->tdp_dateIn,"Y-m-d H:i:s", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}</td>
                                <td>{{ $val->number }}</td>
                                <td>
                                    @if( $val->tdp_options==0 )
                                    {{ 'Ngừng sử dụng' }}
                                    @else
                                    {{ 'Đang sử dụng' }}
                                    @endif
                                </td>
                                <td>
                                    @if(in_array('thietbi-suabangiaothietbi',$arr_route))
                                        <a class="btn-edit" href="{{ route('getTakeDeviceEdit',['id'=>$val->id_device_personnel]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                    @endif

                                    @if(in_array('thietbi-xoabangiaothietbi',$arr_route))
                                        <a class="btn-delete" href="{{ route('getTakeDeviceDel',['id'=>$val->id_device_personnel])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                        <img src="{{ asset('images/general/remove.png') }}"></a>
                                    @endif
                                </td>
                            </tr>
                            <?php $i++ ?>
                            @endforeach
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
@endsection