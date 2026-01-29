
@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row">
    @if (session('flash_message_err') != '')
      <div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
    @endif
    <div class="col-lg-3"></div>
    <div class="col-lg-7">
      <h4 class="title-fuction">Thêm tài khoản và gán cho hồ sơ {{ $p_name }} </h4>
    
      @if (session('flash_message_succ') != '')
      <div class="alert alert-success" role="alert">{{ session('flash_message_succ') }}</div>
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
      <form class="form-horizontal" method="POST" action="">
        <div class="form-group">
          <label for="inputHoten" class="col-sm-4 control-label">Họ tên <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="inputHoten" id="inputHoten" placeholder="Họ tên" value="{{ old('inputHoten') }}">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail" class="col-sm-4 control-label">Email <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email" value="{{ old('inputEmail')}}">
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword" class="col-sm-4 control-label">Mật khẩu <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword_confirmation" class="col-sm-4 control-label">Nhập lại mật khẩu <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" placeholder="Retype Password">
          </div>
        </div>
        <div class="form-group">
          <label for="selectRole" class="col-sm-4 control-label">Role <span class="required">*</span></label>
          <div class="col-sm-8">
            <select name="selectRole" class="form-control">
             <option value=""> -- Select Role -- </option>
          @foreach($data_roles as $role)
              <option value="{{ $role->id }}" @if( old('selectRole') && old('selectRole') == $role->id ) selected='selected' @endif >{{ $role->roles_name }}</option>
          @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-sm btn-orange">Thêm người dùng</button>
          </div>
        </div>
          {{ csrf_field()}}
      </form>
    </div>
    <div class="col-lg-2"></div>
</div>
@endsection