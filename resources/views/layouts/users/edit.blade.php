@extends('layouts.master')

@section('title', 'Tài khoản')

@section('content')
<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
	    <h4 class="title-fuction">Sửa người dùng</h4>
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
		<form class="form-horizontal" method="POST" action="">
			<div class="form-group">
				<label class="col-sm-4 control-label">Họ tên</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputHoten" id="inputHoten" placeholder="Họ tên" value="{{ old('inputHoten',isset($data->name) ? $data->name : null) }}" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Email</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email" value="{{ old('inputEmail',isset($data->email) ? $data->email : null) }}" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Mật khẩu</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Nhập lại mật khẩu</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" placeholder="Retype Password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Role</label>
				<div class="col-sm-8">
                    @if(!empty($data_roles))
                        <select id="roles_id" name="roles_id[]" multiple="multiple">
                            @foreach($data_roles as $role)
                                 <option value="{{ $role->id }}"  @if( old("roles_id"))  @if( in_array($role->id, old("roles_id"))  ) selected='selected' @endif @else @if(in_array($role->id, $roles_user) )selected='selected' @endif @endif >
                                    {{ $role->roles_name }}
                                </option>
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
				<div class="col-sm-offset-4 col-sm-8">
					<button type="submit" class="btn btn-sm btn-orange">Lưu</button>
				</div>
			</div>
			{{ csrf_field()}}
		</form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection