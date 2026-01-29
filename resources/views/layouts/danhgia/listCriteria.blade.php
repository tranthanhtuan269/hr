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
        <h4 class="title-fuction">Cấu hình tiêu chí chi tiết</h4>
        <form class="form-horizontal" method="get" action="">
            <div class="form-group col-lg-6">
                <label for="hoten" class="col-sm-4 control-label">Tên tiêu chí</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="criteria_content" autocomplete="off" placeholder="Nhập tên tiêu chí..." value="{{ Request::get('criteria_content') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                </div>
            </div>
            {{ csrf_field()}}
        </form>

        <h4 class="title-fuction">Danh sách tiêu chí 
            @if(in_array('danhgia-themtieuchi',$arr_route))
                <a href="{{ route('addEvaluationCriteria')}}"><img src="{{ asset('images/general/add.png') }}"></a>
            @endif
        </h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Tên tiêu chí</th>
                        <th>Thao tác</th>
                    </tr>
                    @if(!empty($data))
                    <?php
                        if( !isset($_GET['page']) || $_GET['page']==1 ){
                            $count  = 1;
                        }else{
                            $count = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                        }
                        ?>
                        @foreach ($data as $val)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ $val->criteria_content }}</td>
                            <td>
                                @if(in_array('danhgia-suatieuchi',$arr_route))
                                    <a class="btn-edit" href="{{ route('editEvaluationCriteria',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                                @endif
                                @if(in_array('danhgia-xoatieuchi',$arr_route))
                                    <a class="btn-delete" href="#deleteItem" id="{{ $val->id }}"><img src="{{ asset('images/general/remove.png') }}"></a>
                                    <div class="ajax_response {{ $val->id }}" style="display: none;"></div>
                                @endif
                            </td>
                        </tr>
                        <?php $count++; ?>
                        @endforeach
                    @endif
                    <script type="text/javascript">
                        $(document).ready(function(){
                            
                           $('a[href="#deleteItem"]').click(function(){
                                var r = confirm("Bạn có chắc chắn muốn xóa !!!");
                                if (r == true) {
                                    var id =$(this).attr('id');
                                    var param = {
                                        id : id,
                                    };
                                    $.ajax({
                                        method: "GET",
                                        url: "{{ route('deleteEvaluationCriteriaAjax')}}",
                                        data: param,
                                        // dataType: "json",
                                        success: function (response) {
                                            var obj = $.parseJSON(response);
                                            if(obj.Response=='Error')
                                            {
                                                $(".ajax_response."+id).removeClass('alert-success').addClass("alert-error");
                                                $(".ajax_response."+id).html(obj.Error);
                                                $(".ajax_response."+id).show('slow');
                                            }else{
                                                $(".ajax_response."+id).removeClass('alert-error').addClass("alert-success");
                                                $(".ajax_response."+id).html(obj.Message);
                                                $(".ajax_response."+id).show('slow');
                                            }
                                            setTimeout(function() {
                                                $(".ajax_response."+id).fadeOut( "slow" );
                                            }, 3000);

                                        },
                                        error: function (data) {
                                            console.log('Error:', data);
                                        }
                                    })
                                } else {
                                    return false;
                                }
                           }); 
                        });
                    </script>
                </tbody>
            </table>
        </div>
    
    </div>
</div>
@endsection

