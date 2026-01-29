@extends('layouts.master')
@section('title', 'Đánh giá')
@section('content')
<div class="row content-Emonth">
    <!-- Danh muc -->
    {{-- @include('layouts.danhgia.menuleft.danhgianam') --}}
    <div class="col-sm-2">
    </div>
    <div class="col-sm-10">
        <h4 class="title-fuction">
            Đánh giá điểm tín nhiệm
            <div class="pull-right" style="position: relative;bottom:5px;right:10px">
                @if (Auth::user()->id == 1)
                    <button type="button" class="btn btn-sm btn-primary" onclick="updatedAllFaith()">Cập nhật</button>
                    <script>
                        function updatedAllFaith(){  
                            $.ajaxSetup(
                            {
                                headers:
                                {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            var data = {
                                    };
                                    
                            $.ajax({
                                method: "POST",
                                url: '{{ url("toh_hrm/api/update-all-evaluate-faith") }}',
                                data:data, 
                                dataType: 'json',
                                beforeSend: function() {
                                    $(".ajax_waiting").addClass("loading");
                                },
                                complete: function() {
                                    $(".ajax_waiting").removeClass("loading");
                                },
                                success: function (response) {
                                    if(response.status == 200){
                                        Swal.fire({
                                            type: "success",
                                            html: response.message,
                                            allowOutsideClick: false
                                        }).then(function(result){
                                            if(result.value){
                                                location.reload();
                                            }
                                        })
                                    }
                                },
                                error: function (error) {
                                    alert('Errors');
                                }
                            });
                        }
                    </script>
                @endif
            </div>
        </h4>
        <div class="box_search">
            <form class="row" action="">
                <div class="form-group col-lg-4">
                    <label class="col-sm-4 control-label">Nhân viên</label>
                    <div class="col-sm-8">
                        <select name="personnel_id" id="selectPersonnel" class="form-control select2 narrow wrap" >
                            <option value="0">--Chọn nhân viên--</option>
                            @if (!empty($list_all_personnel))
                                @foreach ($list_all_personnel as $personnelue)
                                <option value="{{ $personnelue->id }}" @if ($personnelue->id == Request::get('personnel_id')) {{ "selected" }} @endif>{{ $personnelue->fullname }}</option>
                                @endforeach
                            @endif
                        </select>
                        <script type="text/javascript">
                            $('#selectPersonnel').select2({
                                containerCssClass: "wrap"
                            })
                        </script>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <label class="col-sm-3 control-label">Đơn vị</label>
                    <div class="col-sm-9">	
                        <select name="selectDepart" id="department" class="form-control select2 narrow wrap" style="width: 100%">
                            <option value="0"> -- Đơn vị -- </option>
                            {!! $department !!}
                        </select>
                        <script type="text/javascript">
                            var $select2 = $('.select2').select2({
                                containerCssClass: "wrap"
                            })
                        </script>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
        @if (count($data) > 0)
            {{-- <div class="alert alert-warning fade in alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <strong>Warning!</strong> This alert box indicates a warning that might need attention.
            </div> --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Họ và tên</th>
                        <th class="text-center">Điểm thâm niên</th>
                        <th class="text-center">Điểm chức danh</th>
                        <th class="text-center">Khác</th>
                        <th class="text-center">Điểm tín nhiệm</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $personnel)
                        <tr>
                            <td class="text-center name">{{ $personnel->fullname }}</td>
                            <td class="text-center">{{ $personnel->score_seniority }}</td>
                            <td class="text-center">{{ $personnel->score_position }}</td>
                            <td class="text-center">{{ $personnel->score_faith - ($personnel->score_seniority +  $personnel->score_position) }}</td>
                            <td class="text-center score_faith">{{ $personnel->score_faith }}</td>
                            <td class="text-center">
                                @if (Auth::user()->id == 1)
                                    <button data-pesonnel_id="{{ $personnel->id }}" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#evaluateFaithByCEO" > GĐ sửa trực tiếp </button>
                                    <div id="evaluateFaithByCEO" class="modal fade" role="dialog" style="text-align: left">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Sửa điểm tín nhiệm</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group clearfix">
                                                                <label class="control-label">Họ và tên:</label><br>
                                                                <div class="fullname"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group clearfix">
                                                                <label class="control-label">Điểm tín nhiệm:</label>
                                                                <input style="width:55%;" class="form-control input-sm" name="score" type="text" maxlength="5"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-info" onclick="evaluateFaithByCEO()"> Cập nhật </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($personnel->score > 0)
                                    @if (Auth::user()->id == 1)
                                        <button data-pesonnel_id="{{ $personnel->id }}" data-score="{{ $personnel->score }}" data-note="{{ $personnel->note }}" type="button" class="btn btn-xs btn-success approvedEvaluateFaith" data-toggle="modal" data-target="#approvedEvaluateFaith" > Phê duyệt đề xuất từ TTT</button>
                                    @else
                                        <button type="button" class="btn btn-xs btn-success remove-btn"> Đã gửi đề xuất </button>
                                    @endif
                                @else
                                    @if (Auth::user()->id != 1)
                                        <button data-pesonnel_id="{{ $personnel->id }}" type="button" class="btn btn-xs btn-warning evaluateFaith" data-toggle="modal" data-target="#evaluateFaith" > Đề xuất </button>
                                    @endif
                                @endif
                                <button data-pesonnel_id="{{ $personnel->id }}" type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#myModal_view{{ $personnel->id }}" > Xem hồ sơ </button>
                            </td>
                        </tr>
                        <!--  DETAIL POPUP FUNDS -->
                        <div id="myModal_view{{ $personnel->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content clearfix">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Xem hồ sơ</h4>
                                        <div class="ajax_response text-center" style="display: none;"></div>
                                    </div>
                                    <div style="padding: 20px;">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Họ và tên : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $personnel->first_name.' '.$personnel->last_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Giới tính : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                @if ($personnel->gender == 1)
                                                    {{ 'Nam' }}
                                                @else
                                                    {{ 'Nữ' }}
                                                @endif
                                            </div>
                                        </div>
                                        @if( $personnel->birthday != NULL )
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Ngày sinh : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ BatvHelper::formatDate($personnel->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Điện thoại : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $personnel->phone_number }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Số chứng minh thư : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $personnel->indentity_card_id }}
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Chức danh : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {!! $personnel->jobs !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Thâm niên : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {!! BatvHelper::getSeniority($personnel->id) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Quỹ : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{  BatvHelper::getInfoFundsbyPersonnel( $personnel->id ) }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Giờ chấm công đi làm : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $personnel->time_attendance_machine }}
                                            </div>
                                        </div>
                                        @if( $personnel->date_in != NULL )
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Ngày vào công ty : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ BatvHelper::formatDate($personnel->date_in,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Chu kỳ xét tăng lương : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{  ( $personnel->salary_frequency > 0 )?$personnel->salary_frequency.' năm':' Không được xét ' }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Loại hợp đồng : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                <?php
                                                    $contracts = BatvHelper::getContracts($personnel->id);
                                                ?>
                                                @if( $contracts )
                                                    @foreach( $contracts as $k_contract => $v_contract )
                                                        {{ $v_contract->title .': '.BatvHelper::formatDate($v_contract->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false).' - '.BatvHelper::formatDate($v_contract->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false)}} </br>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Đơn vị : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $personnel->title }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Mức lương cơ bản đóng bảo hiểm : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ BatvHelper::formatPriceSpecial($personnel->insurrance) }} VNĐ
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <b>Quê quán : </b>  
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $personnel->home_town }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="col-lg-12 text-right">
                {{ $data->appends(Request::all())->links() }} 
            </div>
            <div id="evaluateFaith" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Đề xuất điểm tín nhiệm</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Họ và tên:</label><br>
                                        <div class="fullname"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Điểm tín nhiệm:</label>
                                        <input style="width:55%;" class="form-control input-sm" name="score" type="text" maxlength="5"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group clearfix note">
                                        <label class="control-label">Ghi chú gửi TGĐ:</label>
                                        <textarea class="form-control" data-autoresize="" rows="6" required="" name="note"></textarea>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" onclick="evaluateFaith()"> Gửi </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="approvedEvaluateFaith" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Phê duyệt đánh giá tín nhiệm từ TTT</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Họ và tên:</label><br>
                                        <div class="fullname"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Điểm tín nhiệm đề xuất:</label>
                                        <input style="width:55%;" class="form-control input-sm" name="score" type="text" maxlength="4"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group clearfix">
                                        <label class="control-label">Phê duyệt:</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="action" checked value="1">Đồng ý</label>
                                            <label class="radio-inline"><input type="radio" name="action" value="2">Từ chối</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group clearfix note">
                                        <label class="control-label">Ghi chú của TTT:</label>
                                        <textarea class="form-control" data-autoresize="" rows="6" required="" name="note" disabled></textarea>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" onclick="approvedEvaluateFaith()"> Cập nhật </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).on("click","button.evaluateFaith[data-toggle=modal]",function() {
                    personnel_id = $(this).attr('data-pesonnel_id');
                    fullname = $(this).closest('tr').find('td.name').text();
                    score_min = $(this).closest('tr').find('td.score_faith').text();
                    $('#evaluateFaith div.fullname').html(fullname);
                    $('#evaluateFaith input[name=score]').val(score_min);
                    $('#evaluateFaith textarea[name=note]').val('');
                });

                $(document).on("click","[data-target='#evaluateFaithByCEO']",function() {
                    personnel_id = $(this).attr('data-pesonnel_id');
                    fullname = $(this).closest('tr').find('td.name').text();
                    score_min = $(this).closest('tr').find('td.score_faith').text();
                    $('#evaluateFaithByCEO div.fullname').html(fullname);
                    $('#evaluateFaithByCEO input[name=score]').val(score_min);
                });

                $(document).on("click","button.approvedEvaluateFaith[data-toggle=modal]",function() {
                    personnel_id = $(this).attr('data-pesonnel_id');
                    fullname = $(this).closest('tr').find('td.name').text();
                    score = $(this).attr('data-score');
                    note = $(this).attr('data-note');
                    $('#approvedEvaluateFaith div.fullname').html(fullname);
                    $('#approvedEvaluateFaith input[name=score]').val(score);
                    $('#approvedEvaluateFaith textarea[name=note]').val(note);
                });

                function evaluateFaith(){
                    var score = $('#evaluateFaith input[name=score]').val().trim();

                    if (score == '') {
                        Swal.fire({
                            type: 'warning',
                            html: 'Điểm tín nhiệm không được để trống!',
                            allowOutsideClick: false
                        })
                        
                        return;
                    }

                    if (score <= 0) {
                        Swal.fire({
                            type: 'warning',
                            html: 'Xin vui lòng nhập điểm tín nhiệm hợp lệ!',
                            allowOutsideClick: false
                        })
                        
                        return;
                    }

                    Swal.fire({
                        type: 'warning',
                        text: 'Sẽ có thông báo trực tiếp gửi đến TGĐ, bạn có chắc chắn muốn gửi?',
                        showCancelButton: true,
                        confirmButtonText: 'Có',
                        cancelButtonText: 'Không',
                    }).then(function (result) {
                        if(result.value){   
                            $.ajaxSetup(
                            {
                                headers:
                                {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
   
                            var data = {
                                        'personnel_id' : personnel_id,
                                        'score' : score,
                                        'note' : $('#evaluateFaith textarea[name=note]').val(),
                                    };

                            $.ajax({
                                method: "POST",
                                url: '{{ url("toh_hrm/api/evaluate-faith") }}',
                                data:data, 
                                dataType: 'json',
                                beforeSend: function() {
                                    $(".ajax_waiting").addClass("loading");
                                },
                                complete: function() {
                                    $(".ajax_waiting").removeClass("loading");
                                },
                                success: function (response) {
                                    if(response.status == 200){
                                        Swal.fire({
                                            type: "success",
                                            html: response.message,
                                            allowOutsideClick: false
                                        }).then(function(result){
                                            if(result.value){
                                                location.reload();
                                            }
                                        })
                                    }else{
                                        Swal.fire({
                                            type: 'warning',
                                            html: response.message,
                                            allowOutsideClick: false
                                        })
                                    }
                                },
                                error: function (error) {
                                    alert('Errors');
                                }
                            });
                        }
                    })

                }

                function evaluateFaithByCEO(){
                    var score = $('#evaluateFaithByCEO input[name=score]').val().trim();

                    if (score == '') {
                        Swal.fire({
                            type: 'warning',
                            html: 'Điểm tín nhiệm không được để trống!',
                            allowOutsideClick: false
                        })
                        
                        return;
                    }

                    if (score <= 0) {
                        Swal.fire({
                            type: 'warning',
                            html: 'Xin vui lòng nhập điểm tín nhiệm hợp lệ!',
                            allowOutsideClick: false
                        })
                        
                        return;
                    }

                    $.ajaxSetup(
                    {
                        headers:
                        {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var data = {
                                'personnel_id' : personnel_id,
                                'score' : score,
                            };
                            
                    $.ajax({
                        method: "POST",
                        url: '{{ url("toh_hrm/api/evaluate-faith-by-ceo") }}',
                        data:data, 
                        dataType: 'json',
                        beforeSend: function() {
                            $(".ajax_waiting").addClass("loading");
                        },
                        complete: function() {
                            $(".ajax_waiting").removeClass("loading");
                        },
                        success: function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    type: "success",
                                    html: response.message,
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if(result.value){
                                        location.reload();
                                    }
                                })
                            }else{
                                Swal.fire({
                                    type: 'warning',
                                    html: response.message,
                                    allowOutsideClick: false
                                })
                            }
                        },
                        error: function (error) {
                            alert('Errors');
                        }
                    });
                }

                function approvedEvaluateFaith(){  
                    $.ajaxSetup(
                    {
                        headers:
                        {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var data = {
                                'personnel_id' : personnel_id,
                                'score' : $('#approvedEvaluateFaith input[name=score]').val(),
                                'note' : note,
                                'status' : $('#approvedEvaluateFaith input[name=action]:checked').val(),
                            };
                            
                    $.ajax({
                        method: "POST",
                        url: '{{ url("toh_hrm/api/approved-evaluate-faith") }}',
                        data:data, 
                        dataType: 'json',
                        beforeSend: function() {
                            $(".ajax_waiting").addClass("loading");
                        },
                        complete: function() {
                            $(".ajax_waiting").removeClass("loading");
                        },
                        success: function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    type: "success",
                                    html: response.message,
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if(result.value){
                                        location.reload();
                                    }
                                })
                            }else{
                                Swal.fire({
                                    type: 'warning',
                                    html: response.message,
                                    allowOutsideClick: false
                                })
                            }
                        },
                        error: function (error) {
                            alert('Errors');
                        }
                    });
                }
            </script>
        @endif
    </div>
</div>
 
@endsection