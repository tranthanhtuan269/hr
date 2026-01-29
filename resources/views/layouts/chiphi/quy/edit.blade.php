@extends('layouts.master')

@section('title', 'Chi phí')

@section('content')
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
      <h4 class="title-fuction">Sửa loại quỹ</h4>
    
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
          <label class="col-sm-4 control-label">Tên loại quỹ <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="title" value="{{old('title',isset($data->title) ? $data->title : null )}}" required>
          </div>
        </div>
          <div class="text-center">
            <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
          </div>
          {{ csrf_field()}}
      </form>
	</div>
	<div class="col-lg-1"></div>
</div>
@endsection

