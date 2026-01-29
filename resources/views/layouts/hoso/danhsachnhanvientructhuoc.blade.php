@extends('layouts.master')

@section('title', 'Hồ sơ')

@section('content')
<div class="row content-function">
	{{-- @include('layouts.hoso.menuleft') --}}
	<div class="col-xs-12 col-lg-offset-2 col-lg-10">
		<div class="row">
			<div class="col-lg-12">
			  @if (session('flash_message_succ') != '')
		     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		      @endif
				<h4 class="title-fuction">Quản trị nhân viên trực thuộc</h4>
			 <div class="form-horizontal">
				<div class="form-group col-lg-6">
					<label for="hoten" class="col-sm-4 control-label">Họ tên</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="hoten" id="hoten" autocomplete="off" placeholder="Họ tên" value="{{ Request::get('hoten') }}">
					</div>
				</div>
				<div class="form-group col-lg-6">
					<label for="email" class="col-sm-4 control-label">Email</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Email" value="{{ Request::get('email') }}">
					</div>
				</div>
				<div class="form-group col-lg-12">
		          <div class="text-center">
		            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
					<input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
		          </div>
				</div>
             </div>  
			</div>
			<div class="col-lg-12">
				<h4 class="title-fuction">Danh sách nhân viên trực thuộc <a href="#" style="color: #333; text-decoration: none; cursor: initial; font-size:13px"><input type="checkbox" value="1" @if (Request::get('type')== 'hidden') checked @endif> Ẩn NV đã nghỉ</a></h4>
				@if( count($data)>0 )
				<div class="table-responsive">
					<table class="table table-hover">
					    <tbody>
						    <tr>
						      <th class="text-center">STT</th>
						      <th>Họ và tên</th>
						      <th>Email </th>
						      <th>Ngày sinh </th>
						      <th>Điện thoại</th>
						      <th>Giới tính</th>
						      <th>Đơn vị</th>
						      <th>&nbsp;&nbsp;</th>
                            </tr>
                                <?php 
                                    if( !isset($_GET['page']) || $_GET['page']==1 ){
                                        $stt  = 1;
                                    }else{
                                        $stt = ($_GET['page']*BatvHelper::getPagePaging() -BatvHelper::getPagePaging() ) +1;
                                    }
                                ?>
                            @foreach ($data as $val)
                                <?php 
                                    $fullname = $val->first_name.' '.$val->last_name;
                                    $gender = ($val->gender == 1) ? 'Nam': 'Nữ';
                                    $birthday =  !empty($val->birthday) ? BatvHelper::formatDate($val->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false): '';
                                    $phone_number = $val->phone_number;
                                    $indentity_card_id = $val->indentity_card_id;
                                    $indentity_card_date = !empty($val->indentity_card_date) ? BatvHelper::formatDate($val->indentity_card_date,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false): '';
                                    $indentity_card_address = $val->indentity_card_address;
                                    $jobs = $val->jobs;
                                    $quy = BatvHelper::getInfoFundsbyPersonnel( $val->id );
                                    $time_attendance_machine = $val->time_attendance_machine;
                                    $date_in =  !empty($val->date_in) ? BatvHelper::formatDate($val->date_in,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false): '';
                                    $salary_frequency = $val->salary_frequency > 0 ? $val->salary_frequency.' năm':' Không được xét ';
                                    $contracts = BatvHelper::getContracts($val->id);
                                    $str_contracts = '';
                                    if( $contracts ) {
                                        foreach( $contracts as $k_contract => $v_contract ) {
                                            $str_contracts .= $v_contract->title .': '.BatvHelper::formatDate($v_contract->apply_from,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false).' - '.BatvHelper::formatDate($v_contract->apply_to,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) . '</br>';
                                        }
                                    }
                                    $title_department = $val->title;
                                    $insurrance = BatvHelper::formatPriceSpecial($val->insurrance);
                                    $home_town = $val->home_town;
                                ?>
                                <tr>
                                    <td class="text-center">{{ $stt }}</td>
                                    <td>{{ str_limit( $val->fullname, $limit = 45, $end = '...') }}</td>
                                    <td> {{ $val->email }} </td>
                                    <td> @if ( !empty($val->birthday) ) {{ BatvHelper::formatDate($val->birthday,"Y-m-d", $formatDate="d/m/Y",$timeFormat="H:i:s",$time=false) }} @endif </td>
                                    <td> {{ $val->phone_number }}</td>
                                    @if($val->gender == 1)
                                        <td> Nam </td>
                                    @else
                                        <td> Nữ </td>
                                    @endif
                                    <td>{{ $val->title }}</td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#myModalViewFile" 
                                            data-id="{{ $val->id }}"
                                            data-fullname="{{ $fullname }}"
                                            data-gender="{{ $gender }}"
                                            data-birthday="{{ $birthday }}"
                                            data-phone_number="{{ $phone_number }}"
                                            data-indentity_card_id="{{ $indentity_card_id }}"
                                            data-indentity_card_date="{{ $indentity_card_date }}"
                                            data-indentity_card_address="{{ $indentity_card_address }}"
                                            data-jobs="{{ $jobs }}"
                                            data-quy="{{ $quy }}"
                                            data-time_attendance_machine="{{ $time_attendance_machine }}"
                                            data-date_in="{{ $date_in }}"
                                            data-salary_frequency="{{ $salary_frequency }}"
                                            data-str_contracts="{{ $str_contracts }}"
                                            data-title_department="{{ $title_department }}"
                                            data-insurrance="{{ $insurrance }}"
                                            data-home_town="{{ $home_town }}"
                                            title="Xem chi tiết">
                                            <img src="{{ asset('images/general/eye.png') }}">
                                        </a>
                                    </td>  
                                </tr>
                                <?php $stt++; ?>
						    @endforeach
						    
					    </tbody>
					</table>
				</div>
				@else
					<div class="alert alert-danger" role="alert"> Không tìm thấy kết quả tìm kiếm</div>
				@endif
			</div>
			<div class="col-lg-12 text-right">
				{{ $data->appends(Request::all())->links() }} 
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
        $('#myModalViewFile').on('shown.bs.modal', function (e) {
            var personnel_id = $(e.relatedTarget).data('id');

            $.ajax({
                type: "GET",
                url: "{{ route('getFilePersonnelByMangerAjax') }}", 
                data: { personnel_id: personnel_id },
                success: function (response) {
                    $('.fullname').html($(e.relatedTarget).data('fullname'))
                    $('.gender').html($(e.relatedTarget).data('gender'))
                    $('.birthday').html($(e.relatedTarget).data('birthday'))
                    $('.phone_number').html($(e.relatedTarget).data('phone_number'))
                    $('.indentity_card_id').html($(e.relatedTarget).data('indentity_card_id'))
                    $('.indentity_card_date').html($(e.relatedTarget).data('indentity_card_date'))
                    $('.indentity_card_address').html($(e.relatedTarget).data('indentity_card_address'))
                    $('.jobs').html($(e.relatedTarget).data('jobs'))
                    $('.quy').html($(e.relatedTarget).data('quy'))
                    $('.time_attendance_machine').html($(e.relatedTarget).data('time_attendance_machine'))
                    $('.date_in').html($(e.relatedTarget).data('date_in'))
                    $('.salary_frequency').html($(e.relatedTarget).data('salary_frequency'))
                    $('.str_contracts').html($(e.relatedTarget).data('str_contracts'))
                    $('.title_department').html($(e.relatedTarget).data('title_department'))
                    $('.insurrance').html($(e.relatedTarget).data('insurrance'))
                    $('.home_town').html($(e.relatedTarget).data('home_town'))
                    var response = $.parseJSON(response);
                    console.log(response.data)
                    var html_data = '';

                    $.each(response.data.getHistoryAddRatio, function (index, value) {
                        html_data += '<tr class="text-center">'
                            html_data += '<td>' + value.apply_from  + '-' +  value.apply_to + '</td>'
                            html_data += '<td>' + value.ratio + '</td>'
                        html_data += '<tr>'
                    });

                    $('.tham-nien').html(response.data.getSeniority);
                    $('#data tbody').html(html_data);


                    $('#salary tbody').html('<tr class="text-center"><td>' + response.data.salary + '</td><td>' + response.data.management_allowance_old + '</td></tr>');
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        })
    });
</script>
<!-- Modal -->
<div id="myModalViewFile" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Hồ sơ chi tiết</h4>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Hồ sơ</a></li>
                <li><a data-toggle="tab" href="#data">Quá trình công tác</a></li>
                <li><a data-toggle="tab" href="#salary">Thông tin lương</a></li>
            </ul>
            
            <div class="tab-content" style="padding-top:20px">
                <div id="home" class="tab-pane fade in active">
                    <table class="table table-bordered table-responsive">
                        <tbody>
                          <tr>
                            <td style="width: 40%;"><b>Họ và tên: </b></td>
                            <td class="fullname"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Giới tính:</b></td>
                            <td class="gender"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Ngày sinh:</b></td>
                            <td class="birthday"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Điện thoại:</b></td>
                            <td  class="phone_number"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Số CMTND:</b></td>
                            <td class="indentity_card_id"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Ngày cấp CMTND:</b></td>
                            <td class="indentity_card_date"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Nơi cấp CMTND:</b></td>
                            <td class="indentity_card_address"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Chức danh:</b></td>
                            <td class="jobs"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Quỹ:</b></td>
                            <td class="quy"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Giờ chấm công đi làm:</b></td>
                            <td class="time_attendance_machine"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Ngày vào công ty:</b></td>
                            <td class="date_in"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Chu kỳ xét tăng lương:</b></td>
                            <td class="salary_frequency"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Loại hợp đồng:</b></td>
                            <td class="str_contracts"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Đơn vị:</b></td>
                            <td class="title_department"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Mức lương cơ bản đóng bảo hiểm:</b></td>
                            <td class="insurrance"></td>
                          </tr>
                          <tr>
                            <td style="width: 40%;"><b>Quê quán:</b></td>
                            <td class="home_town"></td>
                          </tr>
                        </tbody>
                    </table>
                </div>
                <div id="data" class="tab-pane fade">
                    <p>
                        <b>Thâm niên</b>: <span class="tham-nien"></span>
                    </p>
                    <p>
                        <b>Hệ số chức danh</b>:
                    </p>
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr class="text-center">
                              <th class="text-center">Thời gian</th>
                              <th class="text-center">Hệ số chức danh</th>
                            </tr>
                          </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="salary" class="tab-pane fade">
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">Mức lương hiện tại</th>
                                <th class="text-center">Phụ cấp hiện tại</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"> Đóng </button>
        </div>
      </div>
  
    </div>
  </div>

  <script>
    $(document).ready(function() {
      var url = "{{ route('getFilePersonnelByManger') }}"

      $('input[type=checkbox]').change(function() {
          if(this.checked) {
            window.location.replace(url + '?hoten='+ $('#hoten').val().trim() +'&email='+ $('#email').val().trim() +'&type=hidden'); 
          } else {
            window.location.replace(url + '?hoten='+ $('#hoten').val().trim() +'&email='+ $('#email').val().trim())
          }
      });


      $('button[type=submit]').click(function() {
          if($('input[type=checkbox]:checked').val() == 1) {
            window.location.replace(url + '?hoten='+ $('#hoten').val().trim() +'&email='+ $('#email').val().trim() +'&type=hidden'); 
          } else {
            window.location.replace(url + '?hoten='+ $('#hoten').val().trim() +'&email='+ $('#email').val().trim())
          }
      });
  });
  </script>
@endsection