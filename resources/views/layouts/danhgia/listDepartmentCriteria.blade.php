@extends('layouts.master')
@section('title', 'TOH HRMS')
@section('content')
<div class="row content-function">
    <div class="col-lg-2">
        <h4 class="title-fuction">Danh mục</h4>
        @if(in_array('danhgia-viethuongdan',$arr_route))
            <p><a href="{{route('getEvaluationSupport')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hướng dẫn đánh giá</a></p>
        @endif
        @if(in_array('danhgia-danhsachbotieuchi',$arr_route))
            <p><a href="{{route('listDepartmentCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tiêu chí</a></p>
        @endif
        @if(in_array('danhgia-danhsachtieuchi',$arr_route))
            <p><a href="{{route('getEvaluationCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tiêu chí chi tiết</a></p>
        @endif
    </div>
    <div class="col-lg-10">
        @if (session('flash_message_succ') != '')
        <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
        @endif
        @if (session('flash_message_err') != '')
        <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
        @endif
        <h4 class="title-fuction">Cấu hình bộ tiêu chí</h4>
        <form class="form-horizontal" method="get" action="">
            <div class="form-group col-lg-6">
                <label for="hoten" class="col-sm-4 control-label">Tên tiêu chí</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" autocomplete="off" placeholder="Nhập tên tiêu chí..." value="{{ Request::get('title') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                </div>
            </div>
            {{ csrf_field()}}
        </form>

        <h4 class="title-fuction">Danh sách bộ tiêu chí 
            @if(in_array('danhgia-caidat',$arr_route))
                <a href="{{ route('settingEvaluationCriteria')}}"><img src="{{ asset('images/general/add.png') }}"></a>
            @endif
        </h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Tên bộ tiêu chí</th>
                        <th>Ngày hiệu lực</th>
                        <th>Ngày hết hiệu lực</th>
                        <th>Danh sách tiêu chí</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php 
                        if( !isset($_GET['page']) || $_GET['page']==1 ){
                            $i  = 1;
                        }else{
                            $i = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                        }
                    ?>
                    @if(!empty($data))
                        @foreach ($data as $val)
                        <tr>
                            <td>{{ $i }}</td>
                            <td> {{ $val['title'] }}</td>
                            <td> {{ BatvHelper::formatDate($val['date_start'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}</td>
                            <td> {{ BatvHelper::formatDate($val['date_end'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) }}</td>
                            <td>
                                @foreach ($val['criteria_content'] as $v)
                                    <div> - {{ $v }} </div>
                                @endforeach
                            </td>
                            <td>
                                @if(in_array('danhgia-suabotieuchi',$arr_route))
                                    <a class="btn-edit" href="{{ route('editDepartmentCriteria',['id'=>$val['id']]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                @endif
                                @if(in_array('danhgia-xoabotieuchi',$arr_route))
                                    <a class="btn-delete" href="{{ route('deleteDepartmentCriteria',['id'=>$val['id']])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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
        <div class="col-lg-12 text-right">
            {{ $data->appends(Request::query())->render()  }} 
        </div>
    </div>
</div>
@endsection