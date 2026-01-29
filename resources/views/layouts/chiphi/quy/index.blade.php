@extends('layouts.master')

@section('title', 'Chi phí')

@section('content')
<div class="row content-function">
<?php
        // echo "<pre>";
        // print_r($data);die;
?>
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
        <h4 class="title-fuction">Quản trị quỹ</h4>
        <form class="form-horizontal" method="get" action="">
          <div class="form-group col-lg-12">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="inputName" class="col-sm-4 control-label">Tiêu đề quỹ</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" value="{{ Request::get('title') }}">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                    <a href="{{ route('getFundsList') }}" class="btn btn-sm btn-grey">Nhập lại</a>
                  </div>
                </div>
              </div>


            </div>
          </div>
              {{ csrf_field()}}
        </form>


      </div>
      <div class="col-lg-12">
        <h4 class="title-fuction">Danh sách quỹ  
            @if(in_array('tintuc-themtintuc',$arr_route))
              <a href="{{ route('getFundsAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
            @endif
        </h4>
        @if( count($data)>0 )
            <div class="table-responsive"> 
              <table class="table table-hover">
                <tbody>
                    <tr>
                      <th>STT</th>
                      <th>Tiêu đề quỹ </th>
                      <th>Chọn mặc định</th>
                      <th>Thao tác</th>
                    </tr>
                    
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
                      <td> {{ $val->title }} </td>
                      <td>
                          @if( $val->type == 0 )
                            <button type="button" class="btn btn-xs btn-orange" onclick="setDefaultFunds({{ $val->id }})">Chọn</button>
                            <span class="ajax_response {!! $val->id !!} text-center" style="display: none;"></span>
                          @endif

                          @if( $val->type == 1 )
                            <i class="fa fa-check-square-o" aria-hidden="true" style="margin-left: 15px;"></i>
                          @endif
                      </td>
                      <td>
                          @if(in_array('tintuc-suatintuc',$arr_route))
                            <a class="btn-edit" href="{{ route('getFundsEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                          @endif
                          @if(in_array('tintuc-xoatintuc',$arr_route))
                            <a class="btn-delete" href="{{ route('getFundsDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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

<script type="text/javascript">
    function setDefaultFunds(id){
      var link = "{!! route('setDefaultFundsAjax') !!}";
      var data = {
              id:id,
           };
      $.ajax({
          url: link,
          data: data,
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
              setTimeout(function() {
                  window.location.reload();
              }, 2000);
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });
      return false;
    }
</script>
@endsection