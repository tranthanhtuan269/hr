@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<div class="row content-Emonth">
    <!-- Danh muc -->
    @include('layouts.danhgia.menuleft.tudanhgiathang')
    <div class="col-sm-10">
        <h4 class="title-fuction">Đánh giá KPI tháng >> Chỉnh sửa KPI tháng</h4>
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
        <div class="detail">
            <div class="text-center">
                <form action="" method="post">
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
                                                <select name="point[{{ $val->criteria_id }}]">
                                                    <option value="1" <?php echo ( $val->point == 1 )?"selected":"";  ?> >1</option>
                                                    <option value="2" <?php echo ( $val->point == 2 )?"selected":"";  ?> >2</option>
                                                    <option value="3" <?php echo ( $val->point == 3 )?"selected":"";  ?> >3</option>
                                                    <option value="4" <?php echo ( $val->point == 4 )?"selected":"";  ?> >4</option>
                                                    <option value="5" <?php echo ( $val->point == 5 )?"selected":"";  ?> >5</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
                        <button type="reset" class="btn btn-sm btn-grey">Nhập lại</button>
                    </div>
                    {{ csrf_field()}}
                </form>

            </div>
        </div>
    </div>
</div>
 
@endsection