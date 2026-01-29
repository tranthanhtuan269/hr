@extends('layouts.master')

@section('title', 'Chấm công')

@section('content')
<?php //echo $check; ?>
<div class="row content-function">
    <div class="col-lg-3">
    	<h4 class="title-fuction">Giải thích ký hiệu</h4>
		   <table class="table">
		   		<thead> 
					<tr> 
						<th>Ký hiệu</th> 
						<th>Chú giải</th>  
					</tr> 
					</thead> 
				<tbody> 
					@if(!empty($info))
						@foreach($info as $val)
							<tr> 
								<td>{{$val->symbol}}</td>
								<td>
									{{$val->title}}
								</td> 
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
    </div>
	<div class="col-lg-9">
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
		<div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
	  @endif
	  @if (session('flash_message_succ') != '')
     	 <div class="alert alert-success" role="alert">{{ session('flash_message_succ') }}</div>
      @endif
		<h4 class="title-fuction">QUẢN TRỊ CHẤM CÔNG</h4>
		<form class="form-horizontal ajaxFormAttendance" method="post" action="" onsubmit="return false">
			<div class="form-group col-lg-6">
				<label for="date" class="col-sm-4 control-label">Ngày</label>
				<div class="col-sm-8">
					<input type="text" id="datetimepicker" style="height:60px;" class="form-control" name="date" id="date" autocomplete="off" placeholder="Ngày" value="{{ Request::get('date') }}">
				</div>
			</div>
			<div class="form-group col-lg-6">
				<label for="selectDepart" class="col-sm-4 control-label">Phòng</label>
				<div class="col-sm-8">	
	               <select name="selectDepart" id="selectDepart" class="form-control">
	               				<option value="0">--All--</option>
		                <?php
		                	foreach ($rooms as $key => $value) {
		                ?>
								<option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>

		                <?php
		                	}
		                ?>
		            </select>
                </div>
			</div>

<!-- 			<div class="form-group col-lg-6">
			   <div class="radio radio-primary">
			   		<input type="radio" name="typeWork" checked="checked" value="1" id="radio1">
			   		<label for="radio1">Chấm công đi làm</label>
			   </div>
				<div class="radio radio-primary">
					<input type="radio" name="typeWork" value="2" id="radio2">
					<label for="radio2">Chấm công đi muộn</label>
				</div>
				
			</div> -->
			<script type="text/javascript"> 
             	jQuery(document).ready(function(){
             		$(".ajaxFormAttendance").submit(function(event){
             			//alert('aaaa');
						$.ajax({
							method: "GET",
							url: "{{ route('postAttendanceListAjax')}}",
							data: $(this).serializeArray(),

							success: function (response) {
								var obj = $.parseJSON(response);
								if(obj.Response=='Error')
								{
									$("#result").removeClass('alert alert-success fade in alert-dismissable').addClass("alert alert-danger fade in alert-dismissable");
									$("#result").html(obj.Error);
									$("#result").show();
								}else{
									$("#result").removeClass('alert alert-danger fade in alert-dismissable').addClass("alert alert-success fade in alert-dismissable");
									$("#result").html(obj.Message);
									$("#result").show();
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {
							   console.log(textStatus, errorThrown);
							}
						})
             		});
             	});
     		</script>
             <script type="text/javascript">
             	
             	jQuery(document).ready(function(){
					$( "#selectDepart" ).change(function() {
             			var selectDepart = $('#selectDepart :selected').val();
             			$("#data_default").remove();
             			//alert(selectDepart);
						$.ajax({
							method: "GET",
							url: "{{ route('searchItemDepartAjax')}}",
							data: "selectDepart=" + selectDepart,

							success: function (response) {
								var obj = $.parseJSON(response);
								$xhtml = '<thead>';
								$xhtml +=	'<tr>';
								$xhtml +=		'<th>#</th><th>Họ và tên</th><th>Email</th><th>Chấm công đi làm</th><th>Chấm công đi muộn</th><th>Thao tác</th>'
								$xhtml +=	'</tr>';
								$xhtml += '</thead>';
								$xhtml += '<tbody>';
									var i=1;
								    $.each(obj.data, function (index, value) {
								    	
							    		$xhtml += '<tr>';
							    			$xhtml += '<th>'+i+'</th>';
											$xhtml +=  '<td>'+value.fullname+'</td>';
											$xhtml +=  '<td>'+value.email+'</td>';
											$xhtml += '<td style="text-align: center;"><select class="select_type" name="typeAttendance_1['+value.id+']">';
											$.each(obj.listType_1, function (index_1, value_1) {
												$xhtml +=  '<option value="'+value_1.id+'">'+value_1.symbol+'</option>';
											});
											$xhtml += '</select></td>';
											$xhtml += '<td style="text-align: center;"><select class="select_type" name="typeAttendance_2['+value.id+']">';
											$.each(obj.listType_3, function (index_3, value_3) {
												$xhtml +=  '<option value="'+value_3.id+'">'+value_3.symbol+'</option>';
											});
											$xhtml += '</select></td>';
											$xhtml += '<td>'+1111111+'</td>';
										i++;
									});
								    $xhtml += '</tbody>';
								$('#data_ajax').html($xhtml);
							},
							error: function(jqXHR, textStatus, errorThrown) {
							   console.log(textStatus, errorThrown);
							}
						})
					});
             	});

             </script>
			<table class="table table-hover" id="data_ajax"> 

			</table>
			<table class="table table-hover" id="data_default"> 
				<thead> 
					<tr> 
						<th>#</th> 
						<th>Họ và tên</th> 
						<th>Email</th>
						<th>Chấm công đi làm</th> 
						<th>Chấm công đi muộn</th> 
						<th>Thao tác</th> 
					</tr> 
				</thead> 
				<tbody> 
			@if(!empty($data))
				<?php $i = 1;?>
				@foreach ($data as $val)
			
					<tr> 
						<th scope="row">{{$i}}</th> 
						<td>
							{{ str_limit( $val->fullname, $limit = 45, $end = '...') }}
						</td> 
						<td>
							{{$val->email}}
						</td> 
						<td style="text-align: center;">
							<select class="select_type" name='typeAttendance_1[{{ $val->id }}]'>	
							<?php
								// echo "<pre>";
								// print_r($listType_1);die;
							?>	
				            @foreach($listType_1 as $v)
				                <option value="{{ $v->id }}" @if( old('typeAttendance_1') && old('typeAttendance_1') == $v->id ) selected='selected' @endif >{{ $v->symbol }}</option>
				            @endforeach
							</select>
						</td> 
						<td style="text-align: center;">
							<select class="select_type" name='typeAttendance_2[{{ $val->id }}]'>		
				            @foreach($listType_3 as $v)
				                <option value="{{ $v->id }}" @if( old('typeAttendance_2') && old('typeAttendance_2') == $v->id ) selected='selected' @endif >{{ $v->symbol }}</option>
				            @endforeach
							</select>
							
						</td> 
						<td><a class="update_item btn btn-sm btn-orange" href="#sign_up" id="{{ $val->id }}">Cập nhật</a></td>
					</tr> 
					<?php $i++ ?>
				@endforeach
			@endif
				</tbody>
			</table>
			<div id="result" class="text-center"></div>
			 <div class="form-group col-lg-12">
	          <div class="text-center">
	            <button type="submit" class="btn btn-sm btn-orange" name="save">Cập nhật</button>
	            <button type="reset" class="btn btn-sm btn-grey">Nhập lại</button>
	          </div>
	        </div>
	        {{ csrf_field()}}
		</form>
		
		<script type="text/javascript">
			$(document).ready(function(){
			   $('a[href="#sign_up"]').click(function(){
         			var personnel_id =$(this).attr('id');
					$.ajax({
						method: "GET",
						url: "{{ route('editItemAttendanceAjax')}}",
						data: "personnel_id=" + personnel_id,
						dataType: "json",
				        success: function (data) {
				        	console.log('response'); 
				        },
				        error: function (data) {
				            console.log('Error:', data);
				        }
					})
			   }); 
			});
		</script>

	</div>
</div>
@endsection