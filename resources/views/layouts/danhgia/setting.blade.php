@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<div class="row content-setting">
    <div class="col-lg-2">
        <h4 class="title-fuction">Danh mục</h4>
        <p><a href="{{route('getEvaluationSupport')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hướng dẫn đánh giá</a></p>
        <p><a href="{{route('listDepartmentCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình bộ tiêu chí</a></p>
        <p><a href="{{route('getEvaluationCriteria')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình tiêu chí chi tiết</a></p>

    </div>
    <div class="col-lg-10">
        <h4 class="title-fuction">Cấu hình bộ tiêu chí</h4>
        @if(count($errors) > 0)
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('flash_message_err') != '')
        <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
        @endif
        @if (session('flash_message_succ') != '')
        <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
        @endif

        <form class="form-horizontal" method="post" action="">
            <div class="form-group col-lg-12">
                <label for="date" class="col-sm-2 control-label" style="margin-left: -7px;">Tên bộ tiêu chí : </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title"  value="{{old('title')}}" required>
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label for="date" class="col-sm-4 control-label">Áp dụng từ :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control datepicker" name="startdate"  value="{{old('startdate')}}" required>
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label for="selectDepart" class="col-sm-4 control-label">đến :</label>
                <div class="col-sm-8">	
                    <input type="text" class="form-control datepicker" name="enddate" value="{{old('enddate')}}" required>
                </div>
            </div>
            <div class="form-group col-lg-12">
                <label class="col-sm-2 control-label">Loại bộ tiêu chí : </label>
                <div class="col-sm-10">
                    <label class="radio-inline">
                      <input type="radio" name="type" value="0" {{ old('type')==0 ?"checked":"" }}> Tháng
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="1"  {{ old('type')==1 ?"checked":"" }}> Năm
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="2"  {{ old('type')==2 ?"checked":"" }}> Khảo sát ý kiến nhân viên
                    </label>
                </div>
            </div>
            <div class="col-lg-12">
                <label class="col-sm-2 control-label" style="margin-left: -7px;">Danh sách tiêu chí: </label>
                <div class="row setting_evaluation_criteria clearfix">
                @if(!empty($data))
                    @foreach ($data as $val)
                        <div class="col-sm-10">
                             <input type="checkbox" value="{{ $val->id }}" name="criteria[]"  {{ ( is_array(old('criteria')) && in_array($val->id, old('criteria')) ) ? 'checked ' : '' }} > {{ $val->criteria_content }}
                        </div>              
                    @endforeach
                @endif
                </div>
            </div>
            <div class="col-lg-12 text-center">
                <input type="submit" class="btn btn-sm btn-orange" name="save" value="Cập nhật">
            </div>
            {{ csrf_field()}}
        </form>
    </div>
</div>
@endsection