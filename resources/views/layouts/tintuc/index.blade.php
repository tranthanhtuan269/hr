@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row content-function">
<?php
        // echo "<pre>";
        // print_r($data);die;
?>
  <!-- Danh muc -->
  @include('layouts.users.menuleft')
  <div class="col-lg-10">
    <div class="row">
      <div class="col-lg-12">
      @if (session('flash_message_err') != '')
       <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
      @endif
      @if (session('flash_message_succ') != '')
       <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
      @endif
        <h4 class="title-fuction">Quản trị tin tức</h4>
        <form class="form-horizontal" method="get" action="">
          <div class="form-group col-lg-12">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="inputName" class="col-sm-4 control-label">Tiêu đề tin tức</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" value="{{ Request::get('title') }}">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <div class="text-center">
                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                    <a href="{{ route('getNewsList') }}" class="btn btn-sm btn-grey">Nhập lại</a>
                  </div>
                </div>
              </div>


            </div>
          </div>
              {{ csrf_field()}}
        </form>


      </div>
      <div class="col-lg-12">
        <h4 class="title-fuction">Danh sách tin tức  
            @if(in_array('tintuc-themtintuc',$arr_route))
              <a href="{{ route('getNewsAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
            @endif
        </h4>
        @if( count($data)>0 )
            <div class="table-responsive"> 
              <table class="table table-hover">
                <tbody>
                    <tr>
                      <th>STT</th>
                      <th>Tiêu đề tin tức </th>
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
                     <tr>
                      <td>{{$i}}</td>
                      <td> {{ $val->title }} </td>
                      <td>
                          @if(in_array('tintuc-suatintuc',$arr_route))
                            <a class="btn-edit" href="{{ route('getNewsEdit',['id'=>$val->id]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                          @endif
                          @if(in_array('tintuc-xoatintuc',$arr_route))
                            <a class="btn-delete" href="{{ route('getNewsDel',['id'=>$val->id])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
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