@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row">
  <!-- Danh muc -->
  @include('layouts.pages.menuleft')
	<div class="col-lg-10">
      <h4 class="title-fuction">Thêm Page</h4>
      @if (session('flash_message_err') != '')
       <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
      @endif
      @if (session('flash_message_succ') != '')
       <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
      @endif

      <form class="form-horizontal" method="POST">
        <div class="form-group">
          <label class="col-sm-2 control-label">Tiêu đề <span class="required">*</span></label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
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
            <button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
          </div>
        </div>
          {{ csrf_field()}}
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection