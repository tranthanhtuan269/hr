@extends('layouts.master')

@section('title', 'Tài khoản')

@section('content')
<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
      <h4 class="title-fuction">Thêm người dùng</h4>
    
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
      <form class="form-horizontal" method="POST" action="{{ route('postUserAdd') }}">
        <div class="form-group">
          <label class="col-sm-4 control-label">Họ tên <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="inputHoten" id="inputHoten" placeholder="Họ tên" value="{{ old('inputHoten') }}" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Email <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email" value="{{ old('inputEmail')}}" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Mật khẩu <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Nhập lại mật khẩu <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="inputPassword_confirmation" placeholder="Retype Password" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Role <span class="required">*</span></label>
          <div class="col-sm-8">
             @if(!empty($data_roles))
                  <select id="roles_id" name="roles_id[]" multiple="multiple">
                      @foreach($data_roles as $val)
                           <option value="{{ $val->id }}" @if( old('roles_id'))  @if( in_array($val->id, old('roles_id'))  ) selected='selected' @endif @endif >{{ $val->roles_name }}</option>
                      @endforeach
                  </select>
              @endif
              <script type="text/javascript">
                  $(function() {
                      $('#roles_id').searchableOptionList({
                          showSelectAll: true,
                          maxHeight: '350px',
                      });
                  });    
              </script>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Đơn vị <span class="required">*</span></label>
          <div class="col-sm-8">
               <select name="department_id" class="form-control select2 narrow wrap">
                  <option value=""> -- Đơn vị -- </option>
                  {!! $department !!}
              </select>
              <script type="text/javascript">
                var $select2 = $('.select2').select2({
                    containerCssClass: "wrap"
                })
              </script>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-sm btn-orange">Thêm mới</button>
          </div>
        </div>
          {{ csrf_field()}}
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection