@extends('layouts.master')

@section('title', 'Chấm công đi muộn')

@section('content')

<div class="row content-function">
	<div class="col-lg-12">
	  @if (session('flash_message_succ') != '')
     	 <div class="alert alert-success" role="alert">{{ session('flash_message_succ') }}</div>
      @endif
		<h4 class="title-fuction">Chấm công đi muộn</h4>
		<div class="col-sm-6 col-sm-offset-2">
			<form class="form-horizontal" method="get" action="">
				<div class="form-group col-lg-12">
					<label for="selectMonth" class="col-sm-3">Tháng</label>
					<div class="col-sm-9">
						 <select name="selectMonth" class="form-control">
						 <?php 
			                for ($i = 1; $i <= 12; $i++){
							    $month = ($i < 10) ? '0'.$i : $i ;
							    echo '<option value="'.$month.'"';
							    if (!empty(Request::input('selectMonth'))) {
							    	if ($i == Request::input('selectMonth')) echo ' selected="selected"';
							    }else{
							    	if ($i == date("n")) echo ' selected="selected"';
							    }						    
							    echo '>'.$month.'</option>';
							}
							?>
			             </select>
					</div>
				</div>
				<div class="form-group col-lg-12">
					<label for="enddate" class="col-sm-3">Năm</label>
					<div class="col-sm-9">
						<select name="selectYear" class="form-control">
							<?php
							for($i=date("Y")-5;$i<=date("Y");$i++) {
								 if (!empty(Request::input('selectYear'))) {
							    	$sel = ($i == Request::input('selectYear')) ? 'selected' : '';
							    }else{
							    	$sel = ($i == date('Y')) ? 'selected' : '';
							    }	   
							    echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group col-lg-12">
					<label for="inputBirthday" class="col-sm-3">Đơn vị</label>
					<div class="col-sm-9">	
		               <select name="selectDepart" id="selectDepart" class="form-control select2 narrow wrap">
			                {!! $department !!}
			            </select>
			            <script type="text/javascript">
							var $select2 = $('.select2').select2({
							    containerCssClass: "wrap"
							})
			            </script>
	                </div>
				</div>
				<div class="form-group col-lg-12">
					<label for="selectPersonnel" class="col-sm-3">Nhân sự</label>
					<div class="col-sm-9">
						<select name="selectPersonnel" id="selectPersonnel" class="form-control select2 narrow wrap" >
							<option value="0">--Chọn nhân sự--</option>
		                @if (!empty($listPersonal))
		                	@foreach ($listPersonal as $key => $value)
		                	<option value="{{ $key }}" @if ($key == $_GET['selectPersonnel']) {{ "selected" }} @endif>{{ $value }}</option>
		                	@endforeach
		                @endif
						</select>
						<script type="text/javascript">
							var personnel = $('.select2').select2({
							    containerCssClass: "wrap"
							})
						</script>
					</div>
				</div>
				<div class="form-group col-lg-12">
					<label for="selectPersonnel" class="col-sm-3">Tìm kiếm khác</label>
					<div class="col-sm-9">
						<select name="filter" class="form-control">
			                <option value="0" @if (Input::get('filter') == '0') selected="selected" @endif> Chọn </option>
			                <option value="1" @if (Input::get('filter') == '1') selected="selected" @endif> Danh sách không đi muộn </option>
			                <option value="2" @if (Input::get('filter') == '2') selected="selected" @endif> Danh sách đi muộn </option>
			                <option value="3" @if (Input::get('filter') == '3') selected="selected" @endif> Danh sách đi muộn vi phạm</option>
						</select>
					</div>
				</div>
				<div class="form-group col-lg-12">
		          <div class="text-center">
		            <button type="submit" class="btn btn-sm btn-orange">Tìm kiếm</button>
		            <input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
		          </div>
				</div>
		        {{ csrf_field()}}
			</form>
		</div>
		<div class="form-group col-lg-4">
			<p style="font-weight: bold;">Mô tả : </p>
			<div class="col-sm-12">
				<svg width="50" height="20">
				  <rect width="300" height="100" style="fill:rgba(0, 53, 255, 0.61);"></rect>
				</svg>
				<b style="font-style: italic;">Số phút đi muộn</b>
			</div>
			<div class="col-sm-12">
				<svg width="50" height="20">
				  <rect width="300" height="100" style="fill:rgba(255, 0, 0, 0.84);"></rect>
				</svg>
				<b style="font-style: italic;">Nhân viên đi muộn quá 3 lần</b>
			</div>
        </div>

	</div>
	<div class="col-lg-12">
		<h4 class="title-fuction">Thông tin ngày công</h4>
		<?php


		     function sw_get_current_weekday($weekday) {
			   // date_default_timezone_set('Asia/Ho_Chi_Minh');
			    //$weekday = date("l");
			    $weekday = strtolower($weekday);
			    switch($weekday) {
			        case 'monday':
			            $weekday = 'T. Hai';
			            break;
			        case 'tuesday':
			            $weekday = 'T. Ba';
			            break;
			        case 'wednesday':
			            $weekday = 'T. Tư';
			            break;
			        case 'thursday':
			            $weekday = 'T. Năm';
			            break;
			        case 'friday':
			            $weekday = 'T. Sáu';
			            break;
			        case 'saturday':
			            $weekday = 'T. Bảy';
			            break;
			        default:
			            $weekday = 'CN';
			            break;
			    }
			    return $weekday;
			}
			
			// Function to get an array of days
			function getDays($month, $year){
			   // Start of Month
			   $start = new DateTime("{$year}-{$month}-01");
			   $month = $start->format('F');
			   // Prepare results array
			   $results = array();
			   // While same month
			   while($start->format('F') == $month){
			      // Add to array
			      $day              = $start->format('l');
			   	  $day = sw_get_current_weekday($day);
			      $date             = $start->format('j');
			      $results[$date]   = $day;
			      // Next Day
			      $start->add(new DateInterval("P1D"));
			   }
			   // Return results
			   return $results;
			}

			if (!empty(Request::input('selectYear')) && !empty(Request::input('selectMonth'))) {
				// Get an array of days
				$arrayDays = getDays(Request::input('selectMonth'), Request::input('selectYear'));
				$number = cal_days_in_month(CAL_GREGORIAN, Request::input('selectMonth'), Request::input('selectYear'));
			}else{
				$arrayDays = getDays(date('m'), date('Y'));
				$number = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
			}	
		?>

<div id="myTable">
    <div class="wrapper">
        <table>
            <tr>
                <th class="largerFont">Họ và tên</th>
                  <?php foreach (array_keys($arrayDays) as $someDate): ?>
				       <td><?php echo $someDate; ?></td>
				   <?php endforeach; ?>
   				  <td>Số buổi đi muộn</td>
   				  <td>Tổng số phút đi muộn</td>
            </tr>
             <tr>
                <th class="largerFont">&nbsp;</th>
                  <?php foreach (array_values($arrayDays) as $someDate): ?>
				      <td><?php echo $someDate; ?></td>
				  <?php endforeach; ?>
				  <td></td>
				  <td></td>
            </tr>
            @if(!empty($data))
				@foreach($data as $val)
					<?php $css_timelate_warning = count( $val->time_late ) >3?'background:rgba(255, 0, 0, 0.84);color:white;':''; ?>
				   <tr>
					<th style="{{ $css_timelate_warning }}">{{ str_limit( $val->fullname, $limit = 30, $end = '...') }}</th>
					<?php $total_time_late = 0; ?>
					@for($i = 1 ; $i <= $number ; $i++)
					    @if(!empty($val->time_late[$i]))
					    	<?php $total_time_late += $val->time_late[$i]; ?>
					        <td style="color:white;background:rgba(0, 53, 255, 0.61);font-weight: bold;">{{ $val->time_late[$i] }}</td>
						@else
								<td>&nbsp</td>
						@endif
					@endfor
						<td style="background:#f5f5f5;font-weight: bold;"><?php echo count( $val->time_late ); ?></td>
						<td style="background:#f5f5f5;font-weight: bold;">{{ $total_time_late }}</td>
					</tr>
				@endforeach
            @endif

        </table>
    </div>
    
</div>
	
</div>
 
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
   		 var val = $('#selectDepart').val();
   		 $.ajax({
			type: "GET",
			url: "{{route('getAttendancePersonalAjax')}}",
			//contentType: "application/json; charset=utf-8",
			data:{
					'department_id' : val,
					'selectPersonnel'  : $('#selectPersonnel').val(),
				},
			//dataType: "json",
			success: function(data){
				console.log(data);
				$("#selectPersonnel").html(data);
			}
		});
       $('#selectDepart').on('change',function(){
       		//alert($(this).val());
       		 //alert( this.value );
       		 var val = $(this).val();
       		/* $.ajaxSetup({
			        headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        }
			 });*/
       		 $.ajax({
				type: "GET",
				url: "{{route('getAttendancePersonalAjax')}}",
				//contentType: "application/json; charset=utf-8",
				data:{'department_id' : val},
				//dataType: "json",
				success: function(data){
					console.log(data);
					$("#selectPersonnel").html(data);
					$('#selectPersonnel').trigger('change.select2');
				}
			});

       });
		
	});

</script>
@endsection