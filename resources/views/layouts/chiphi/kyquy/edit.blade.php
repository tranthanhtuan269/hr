@extends('layouts.master')
@section('title', 'Chi phí')
@section('content')

<div class="row content-function">
    <!-- Danh muc -->
    @include('layouts.chiphi.menuleft')
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                @if (session('flash_message_err') != '')
                <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
                @endif
                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
                @endif
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">Sửa nhân sự ký quỹ</h4>
               @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
                <form class="form-horizontal" method="POST" action="">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Người cầm quỹ <span class="required">*</span></label>
                        <div class="col-sm-6">

                            @if(!empty($listPersonnel))
                                <select name="personnel_id" id="my-select" required>
                                    @foreach($listPersonnel as $val)
                                         <option value="{{ $val->id }}" <?php echo ( $val->id==$data['personnel_id'] )?"selected":""; ?>>{{ $val->fullname }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <script type="text/javascript">
                                $(function() {
                                    $('#my-select').searchableOptionList({
                                        maxHeight: '250px'
                                    });
                                }); 
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Giá trị <span class="required">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" onkeyup="format_curency( this.value );" id="numFormatResult" class="form-control" value="{{ old('value',isset($data['value']) ? BatvHelper::formatPriceSpecial($data['value']) : null ) }}"  required>
                            <input type="hidden" name="value" id="result" value="{{ old('value',isset($data['value']) ? $data['value'] : null ) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Ngày nhận <span class="required">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" pattern="\d{1,2}/\d{1,2}/\d{4}" class="datepicker form-control" name="received_date" value="{{ old('value',isset($data['received_date']) ? BatvHelper::formatDate($data['received_date'],'Y-m-d H:i:s',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false) : null ) }}">
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Cập nhật">
                    </div>
                    {{ csrf_field()}}
                </form>

            </div>
        </div>
    </div>

</div>
@endsection