@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')
<style type="text/css">
	.setting_salary .repice_reference{ display: none; }
</style>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

		<div class="col-lg-10"> 
			<div class="row">
				<div class="col-sm-12">
					<h4 class="title-fuction">Danh sách nhân viên tham gia các phong trào của công ty năm {{ $year }}</h4>
						@if(count($errors) > 0)
							<div class="alert alert-danger" role="alert">
							<ul>
							    @foreach ($errors->all() as $error)
							        <li>{{ $error }}</li>
							    @endforeach
							</ul>
							</div>
						@endif
						@if (session('flash_message_succ') != '')
							 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
						@endif
						@if (session('flash_message_err') != '')
							 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
						@endif
		                <form class="row form-horizontal clearfix" method="get" action="">
		                	<div class="col-lg-offset-2 col-lg-7">
			                    <div class="form-group row">
			                        <label for="date" class="col-sm-3 control-label">Năm</label>
			                        <div class="col-sm-3">
										<select name="selectYear" class="form-control">
											<?php
												for($i=date("Y")-3;$i<=date("Y");$i++) {
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

			                    <div class="form-group col-lg-2 hidden">
			                        <input type="submit" class="btn btn-sm btn-orange" id="search" value="Tìm kiếm">
			                    </div>
								<script type="text/javascript">
								    $(document).ready(function(){
								        $('select[name="selectYear"]').change(function(){
								            $("#search").click();
								        });
								    });
								</script>
			                    {{ csrf_field() }}
	                		</div>
		                </form>


						<form class="row" action="{{ route('postSettingConfigKiRules') }}" method="post">
							<div class="col-lg-offset-2 col-lg-7">
								<div class="row">							
									<label class="col-sm-3 text-right" style="padding-left: 20px;">Chọn nhân viên</label>
									<div class="col-sm-7">
										<div class="formAddConfigKiPerformance">
											<div class="form-group">
						                        @if(!empty($listPersonnel))
						                            <select id="my-select-2" name="personnel_ki_rules[]" multiple="multiple">
						                                @foreach($listPersonnel as $key => $val)
						                                     <option value="{{ $val->id }}" <?php if(  isset($listPersonnel[$key]->ticket) && $listPersonnel[$key]->ticket==1 ){ echo "selected"; } ?>>{{ $val->fullname }}</option>
						                                @endforeach
						                            </select>
						                        @endif
												<script type="text/javascript">
													$(function() {
													    $('#my-select-2').searchableOptionList({
													        showSelectAll: true,
													        maxHeight: '250px',
													    });
													});    
												</script>
											</div>
										</div>
									</div>
									<div class="col-sm-12 text-center">
										<input type="submit" class="btn btn-sm btn-orange" value="Cập nhật">
										<input type="hidden" name="year" value="{{ $year }}">
									</div>
									{{ csrf_field() }}
								</div>
							</div>
						</form>

				</div>
			</div>


	</div>
</div>

@endsection