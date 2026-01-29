@extends('layouts.master')

@section('title', 'Phòng ban')

@section('content')

<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Sửa phòng ban</h4>
		<div class="row">
			<div class="col-lg-12">
			   @if (count($errors) > 0)
			    <div class="alert alert-danger">
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
			</div>
			<div class="col-lg-offset-2 col-lg-8">
		        <form  class="form-horizontal" method="POST">
		          <div class="form-group">
		            <label class="col-sm-4 control-label"> Tên phòng ban</label>
		            <div class="col-sm-8">
		              <input type="text" class="form-control" name="title"  value="{{old('title',isset($data[$id]['title']) ? $data[$id]['title'] : null )}}" required="required">
		            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label"> Thuộc phòng ban</label>
			            <div class="col-sm-8">
	                        @if(!empty($department))
	                            <select name="parent_id" class="form-control">
	                                <option value="0" selected>--Trống--</option>
									{!! $department !!}
	                            </select>
	                        @endif
			            </div>
		          </div>
		          <div class="form-group">
			          <label class="col-sm-4 control-label">Người quản lý</label>
			            <div class="col-sm-8">
	                        @if(!empty($listPersonnel))
	                            <select name="manager_id" id="my-select" >
	                                @foreach($listPersonnel as $val)
	                                     <option value="{{ $val->id }}" <?php echo ( $val->id==$data[$id]['manager_id'])?"selected":""; ?> >{{ $val->fullname }}</option>
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
						<label class="col-sm-4 control-label">Người chấm công</label>
						<div class="col-sm-8">	
	                        @if(!empty($listPersonnel))
	                            <select id="my-select-2" name="personnel_attendance[]" multiple="multiple">
	                                @foreach($listPersonnel as $val)
	                                     <option value="{{ $val->id }}" <?php echo ( in_array($val->id,$data[$id]['manage_id_attendance']) )?"selected":""; ?>>{{ $val->fullname }}</option>data
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