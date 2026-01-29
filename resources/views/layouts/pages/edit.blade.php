@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row">
  <!-- Danh muc -->
  @include('layouts.pages.menuleft')
  <div class="col-lg-10">
      <h4 class="title-fuction">Sửa Page</h4>
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
      <form class="form-horizontal" method="POST" action="{{ url('/' ) }}/toh_hrm/page/edit/{{ $data->id }}">
        {{ csrf_field()}}
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
          <label class="col-sm-2 control-label">Tiêu đề <span class="required">*</span></label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="title"  required="required" value="{{old('title',isset($data->title) ? $data->title : null )}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Nội dung <span class="required">*</span></label>
          <div class="col-sm-10">
            <textarea rows="4" onkeydown="expandtext(this);" name="content" requried>{{ old('content',isset($data->content) ? $data->content : null ) }}</textarea>
            <script type="text/javascript">
              CKEDITOR.replace( 'content',
              {
                height: '500px',
                on: {
                      change: function() {
                          unsaved = true;
                      }
                }
              });

            </script>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10 text-center">
            <button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
          </div>
        </div>
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection