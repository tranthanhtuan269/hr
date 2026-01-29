@extends('layouts.master')
@section('title', 'Vai trò')
@section('content')
<div class="row content-function">
    <!-- Danh muc -->
    @include('layouts.users.menuleft')
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="title-fuction">Quản trị phân quyền</h4>
                <form class="form-horizontal" method="get" action="">
                    <div class="form-group col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-4 control-label">Quyền</label>
                                    <div class="col-sm-8">
                                        @if(!empty($listRoles))
                                            <select name="roles_name" class="form-control">
                                                <option value="0">--Tất cả--</option>
                                                @foreach($listRoles as $val)
                                                     <option value="{{ $val['roles_name'] }}" <?php echo ($val['roles_name']==Request::get("roles_name"))?"selected":""; ?>>{{ $val['roles_name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ csrf_field()}}
                </form>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">
                    Phân quyền người dùng 
                    @if(in_array('roles-add',$arr_route))
                        <a href="{{ route('getRoleAdd')}}"> <img src="{{ asset('images/general/add.png') }}"></a>
                    @endif
                </h4>
                @if (session('flash_message_err') != '')
                <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
                @endif
                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ')}}</div>
                @endif
                <style type="text/css">
                    ul.menu{list-style: none;padding: 0}
                    .menu > li{float: left; margin-right: 5px;}
                </style>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Role</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role['roles_name'] }}</td>
                                <td>
                                    @if(in_array('roles-edit',$arr_route))
                                        <a class="btn-edit" href="{{ route('getRoleEdit',['id'=>$role['id']]) }}"> <img src="{{ asset('images/general/edit.png') }}"></a>
                                    @endif
                                    @if(in_array('roles-del',$arr_route))
                                        <a class="btn-delete" href="{{ route('getRoleDel',['id'=>$role['id']]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')"> 
                                        <img src="{{ asset('images/general/remove.png') }}"></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-lg-12 text-right">
                        {{ $roles->appends(Request::query())->render()  }} 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection