

<?php $__env->startSection('title', 'Hồ sơ'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="col-lg-2"></div>
   <div class="col-lg-8">
   <?php if(session('flash_message_err') != ''): ?>
	<div class="alert alert-danger" role="alert"><?php echo e(session('flash_message_err')); ?></div>
	<?php endif; ?>
	<?php if(session('flash_message_succ') != ''): ?>
	<div class="alert alert-success" role="alert"><?php echo e(session('flash_message_succ')); ?></div>
	<?php endif; ?>
   <?php if(!empty($data->id)): ?>
	   <div class="col-lg-4">
	   	  <?php if(empty($data->avatar)): ?>
	       <div class="avatar text-center">
	       		<img src="<?php echo e(asset('images/dashboard/avatar.png')); ?> " class="user-image" alt="User Image">
	       </div>
	       <?php else: ?>
	        <div class="avatar text-center">
	       		<img style="width:150px;height:150px" src="<?php echo e(asset('uploads/personnels/'.$data->avatar)); ?> " class="user-image" alt="User Image">
	       </div>
	       <?php endif; ?>
	       <br/>
	       <div class="text-center">
	       		<a href="<?php echo e(route('getHosoEditInfo',['id'=>Auth::user()->id])); ?>" class="btn btn-sm btn-orange">Cập nhật</a>
	       </div>
	   </div>
	    <div class="col-lg-8">
	      <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Họ và tên</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->fullname )?$data->fullname:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Giới tính </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<?php if($data->gender == 1): ?>
	   			<p>Nam </p>
	   			<?php else: ?>
				<p>Nữ </p>
	   			<?php endif; ?>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Ngày sinh </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->birthday )?$data->birthday:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Email </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->email )?$data->email:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Điện thoại</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->phone_number )?$data->phone_number:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Số CMTND</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->indentity_card_id )?$data->indentity_card_id:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Ngày cấp CMTND </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->indentity_card_date )? DateTime::createFromFormat('Y-m-d', $data->indentity_card_date)->format('d/m/Y') :'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Nơi cấp CMTND </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->indentity_card_address )?$data->indentity_card_address:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Quê quán </b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->home_town )?$data->home_town:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Nơi ở hiện nay</b></div>
	   		<div class="col-lg-8 col-xs-8">

	   			<p> <?php echo e(!empty( $data->address )?$data->address:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Chức danh</b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p> <?php echo !empty( $data->jobs )?$data->jobs:'...'; ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Ngày vào công ty</b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p><?php if( $data->date_in ): ?> <?php echo e(BatvHelper::formatDate($data->date_in,'Y-m-d',$formatDate='d/m/Y',$timeFormat='H:i:s',$time=false)); ?> <?php endif; ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Chu kỳ xét tăng lương</b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p><?php echo e($data->salary_frequency); ?> <?php echo ( $data->salary_frequency==6 )?"tháng":"năm"; ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Hệ số chức danh </b></div>
	   		<div class="col-lg-8 col-xs-8">
				<p> <?php echo e(!empty( $data->ratio )?$data->ratio:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   	 <div class="row">
	        <div class="col-lg-4 col-xs-4 text-right"><b>Đơn vị</b></div>
	   		<div class="col-lg-8 col-xs-8">
	   			<p> <?php echo e(!empty( $data->title )?$data->title:'...'); ?></p>
	   	  	</div>
	   	 </div>
	   		
	   </div>
	<?php else: ?>
	   <?php if(count($errors) > 0): ?>
      <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach($errors->all() as $error): ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
		<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			<?php echo e(csrf_field()); ?>

			<div class="form-group">
				<label for="hotenDem" class="col-sm-4 control-label">Họ và tên đệm</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="hotenDem" id="hotenDem" placeholder="Họ và tên đệm"  required <?php if($errors->has('hotenDem')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('hotenDem',isset($data->first_name) ? $data->first_name : null)); ?> <?php endif; ?> ">
				</div>
			</div>
			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label">Tên</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Họ tên" required <?php if($errors->has('inputName')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('inputName',isset($data->last_name) ? $data->last_name : null)); ?> <?php endif; ?> ">
				</div>
			</div>
			<div class="form-group">
				<label for="inputGender" class="col-sm-4 control-label">Giới tính</label>
				<div class="col-sm-8">
					<input type="radio" name="gender" value="1"
					<?php if(old('gender',isset($data->gender) ? $data->gender : null) == 1): ?>
						checked="checked"
					<?php endif; ?>> Nam
						<input type="radio" name="gender" value="0" <?php if(old('gender',isset($data->gender) ? $data->gender : null) != null && old('gender',isset($data->gender) ? $data->gender : null) == 0): ?> checked="checked" <?php endif; ?>> Nữ
				</div>
			</div>
			<div class="form-group">
				<label for="inputBirthday" class="col-sm-4 control-label">Ngày sinh</label>
				<div class="col-sm-8">	

		          <input type="text" name="inputBirthday" class="form-control" id="datepicker" <?php if($errors->has('inputBirthday')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('inputBirthday',isset($data->birthday) ? $data->birthday : null)); ?>" <?php endif; ?>>
		        </div>
			</div>
			<script>
			  $(function() {
			    $( "#datepicker" ).datepicker({
			    		changeMonth: true,
							changeYear: true,
							yearRange: "1970:2020",
							dateFormat: 'dd/mm/yy'

			    	}	
			    );
			  });
			  </script>
			<div class="form-group">
				<label for="inputPhone" class="col-sm-4 control-label">Điện thoại</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputPhone" id="inputPhone" placeholder="Điện thoại" required <?php if($errors->has('inputPhone')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('inputPhone',isset($data->phone_number) ? $data->phone_number : null)); ?>" <?php endif; ?>>
				</div>
			</div>
			<div class="form-group">
				<label for="inputId" class="col-sm-4 control-label">Số chứng minh thư</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="indentity_card_id" id="indentity_card_id" placeholder="Số chứng minh" required <?php if($errors->has('indentity_card_id')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('indentity_card_id',isset($data->indentity_card_id) ? $data->indentity_card_id : null)); ?>" <?php endif; ?>>
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="col-sm-4 control-label">Quê quán</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="home_town" id="home_town" placeholder="Quê quán" required <?php if($errors->has('home_town')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('home_town',isset($data->home_town) ? $data->home_town : null)); ?>" <?php endif; ?>>
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="col-sm-4 control-label">Nơi ở hiện nay</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address" id="address" placeholder="Nơi ở hiện tại" required <?php if($errors->has('address')): ?> autofocus value="" <?php else: ?>  value="<?php echo e(old('address',isset($data->address) ? $data->address : null)); ?>" <?php endif; ?>>
				</div>
			</div>
			<div class="form-group">
				<label for="fileImage" class="col-sm-4 control-label">Ảnh hồ sơ</label>
				<div class="col-sm-8">
					<img id="blah" src="#" style="width:150px;height:150px;display: none;" />
				    <?php if(!empty($data->avatar)): ?>
				    	<img class="avatar_first" style="width:150px;height:150px" src="<?php echo e(asset('uploads/personnels/'.$data->avatar)); ?>" alt="avatar">
				    <?php endif; ?>
					<input type="file"  name="fileImage" id="fileImage" accept="image/*">
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Cập nhật</button>
					<a href="<?php echo e(route('getHosoInfo',['id'=>Auth::user()->id])); ?>" class="btn btn-sm btn-grey">Nhập lại</a>
				</div>
			</div>
			<script type="text/javascript">
				function readURL(input) {
				    if (input.files && input.files[0]) {
				        var reader = new FileReader();
				        reader.onload = function (e) {
				            $('#blah').attr('src', e.target.result);
				        }

				        reader.readAsDataURL(input.files[0]);
				         $('#blah').show();
				         $('.avatar_first').hide();
				    }else{
				    	$('#blah').hide();
				         $('.avatar_first').show();
				    }
				}

				$("#fileImage").change(function(){
				    readURL(this);
				});
			</script>
		</form>
		
	<?php endif; ?>
	</div>
   <div class="col-lg-2"></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>