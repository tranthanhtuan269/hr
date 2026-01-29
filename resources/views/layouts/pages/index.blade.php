@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row">
  @include('layouts.pages.menuleft')
  <div class="col-lg-10">
      <h4 class="title-fuction">Danh sách page
            @if(in_array('page-add',$arr_route))
              <a href="{{ route('getPageAdd')}}"><img src="{{ asset('images/general/add.png') }}"></a>
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
      
      <div class="table-responsive"> 
        <table class="table table-hover">
          <tbody>
            <tr>
              <th>Tiêu đề</th>
              <th>Thao tác</th>
            </tr>
            @foreach($data as $val)
            <tr>
              <td><a href="{{ route('getCategories',['cat'=> $val->slug ]) }}">{{ $val->title}}</a></td> 
              <td>
                @if(in_array('page-edit',$arr_route))
                  <a class="btn-edit" href="{{ route('getPageEdit',['id'=> $val->id ]) }}"><img src="{{ asset('images/general/edit.png') }}"></a>
                @endif
                @if(in_array('page-del',$arr_route))
                  <form action="{{ url('toh_hrm/page/del/'.$val->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa ?');">
                    </button>
                  </form>
                @endif
              </td>  
            </tr>
            @endforeach
           
          </tbody>
        </table>
        </div>
      </div>
      <div class="col-lg-12 text-right">
        
      </div>
  </div>
</div>
@endsection