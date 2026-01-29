@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<?php
    $currentUrl = Request::url();
    $userId = explode("/", $currentUrl);
    $userId = end($userId);
?>
<div class="row content-Emonth">
    <!-- Danh muc -->
    @include('layouts.danhgia.menuleft.tudanhgiathang')
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá KPI tháng <i class="fa fa-angle-double-right" aria-hidden="true"></i> Đánh giá nhân viên trực thuộc</h4>
        @if (session('flash_message_err') != '')
            <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
        @endif
        @if (session('flash_message_succ') != '')
            <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
        @endif
        
        @if ( isset($error_special) && $error_special != '')
            <div class="alert alert-danger" role="alert"> <?php echo $error_special; ?></div>
        @else
            @if(!empty($param))
                <p><a href="{{ route('getEvaluationMonthbyManagerEdit',['id'=>$id ]) }}" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Chỉnh sửa</a></p>
            @endif
            <div class="detail">
                <div class="text-center">
                    <form action="" method="post">
                        <div class="text-center" style="margin: 15px 0px;">
                            Đánh giá : <b>@if( !empty($infoUser->fullname) )  {{ $infoUser->fullname }}  @endif</b>
                        </div>
                        <div class="table-responsive">
                            <table class="evaluation table table-bordered selfEvaluation">
                                <thead>
                                    <tr>
                                        <th class="text-center">Tiêu chí</th>
                                        <th class="text-center">Điểm đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($data))
                                        @foreach ($data as $val)
                                            <tr>
                                                <td>{{ $val->criteria_content }}</td>
                                                <td>
                                                    <select name="point[{{ $val->id }}]">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3" selected>3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <textarea rows="4" onkeydown="expandtext(this);" name="comment" onkeyup="textAreaAdjust(this)"  placeholder="Các đánh giá khác.."></textarea>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-sm btn-orange" name="save" value="Cập nhật">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <button type="reset" class="btn btn-sm btn-grey">Nhập lại</button>
                        </div>
                        {{ csrf_field()}}
                    </form>

                </div>
            </div>
        @endif
    </div>
</div>
@endsection