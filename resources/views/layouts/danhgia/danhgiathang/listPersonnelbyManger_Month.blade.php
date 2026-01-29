@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<div class="row content-Emonth">
    <!-- Danh muc -->
    @include('layouts.danhgia.menuleft.tudanhgiathang')
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá KPI tháng <i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách nhân viên trực thuộc</h4>
        @if (session('flash_message_err') != '')
            <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
        @endif
        @if (session('flash_message_succ') != '')
            <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
        @endif
        
        <?php
            if ( isset($error_special) && $error_special != ''){
        ?>
            <div class="alert alert-danger" role="alert"> <?php echo $error_special; ?></div>
        <?php
            }
        ?>
        @if(!empty($param))
            <p><a href="{{ route('getEvaluationManagerbyMonthEdit',['id'=>$manager_id ]) }}" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa</a></p>
        @endif
        <div class="detail">
            <div class="text-center">
                <form action="" method="post">
                    <div class="table-responsive">
                        <table class="evaluation table table-bordered selfEvaluation">
                            <thead>
                                <tr>
                                    <th class="text-center">Danh sách nhân viên trực thuộc</th>
                                    <th class="text-center">Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data))
                                    @foreach ($data as $val)
                                        <tr>
                                            <td>{{ $val->fullname }}</td>
                                            <td><a href="{{ route('getEvaluationMonthbyManager',['id'=>$val->id ]) }}">Đánh giá</a></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ csrf_field()}}
                </form>

            </div>
        </div>
    </div>
</div>

@endsection