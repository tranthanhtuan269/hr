

<?php $__env->startSection('title', 'TOH HRMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="row content-function">
	<!-- Danh muc -->
	<?php echo $__env->make('layouts.chucnangkhac.menuleft', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">
					Danh sách vùng hiển thị trang chủ
					<?php if(in_array('chucnangkhac-themvunghienthitrangchu',$arr_route)): ?>
						<a href="<?php echo e(route('addPageHome')); ?>"><img src="<?php echo e(asset('images/general/add.png')); ?>"></a>
					<?php endif; ?>
				</h4>	
				<div class="ajax_response text-center" style="display: none;"></div>
				<div>
					<p style="font-weight: 600;font-style: italic;text-align: center;">
						<span style="color:red">*</span> Bạn có thể thay đổi vị trí hiển thị bằng cách kéo thả
					</p>
					<?php if( count($data) > 0 ): ?>
					<ul id="sortable" class="connectedSortable">
						<?php foreach( $data as $value ): ?>
							<li class="item_<?php echo e($value->id); ?>" data-id="<?php echo e($value->id); ?>"><?php echo e($value->title); ?> <a href="javascript:void(0)" onclick="removeItem(<?php echo e($value->id); ?>)" style="float: right; margin-left: 10px;margin-top: -1px;" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a><a href="<?php echo e(route('postPageHomeEdit', ['id' => $value->id])); ?>"  style="float: right" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
    function removeItem(id) {
		var check = confirm("Bạn có thực sự muốn xóa ?");

		if ( check ) {
			var data = {
			  idPageHome : id,
			};

			$.ajaxSetup(
			{
				headers:
				  {
				  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				method: "POST",
				url: "<?php echo route('delPageHomeAjax'); ?>",
				data: data,
				success: function (response) {
				  var obj = $.parseJSON(response);
				  if(obj.Response=='Error'){
				    //
				  }else{
				  	$('.item_' + id).remove();
	                myFunction(obj.Message);
				  }
				},
				error: function (data) {
				 console.log('Error:', data);
				}
			});
		} else {
			return false;
		}
    }
	$(document).ready(function(){

	    $( "#sortable" ).sortable({
	        update: function(event, ui) {
	        	var dataSort = [];
				$("#sortable li").each(function(index){
				    dataSort.push({"id":$(this).attr('data-id'), "position":index + 1 });
				});
				var data = {
				  dataSort:dataSort,
				};

				$.ajaxSetup(
				{
					headers:
					  {
					  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					method: "POST",
					url: "<?php echo route('changePositionAjax'); ?>",
					data: data,
					success: function (response) {
					  var obj = $.parseJSON(response);
					  if(obj.Response=='Error'){
					    //
					  }else{
                        myFunction(obj.Message);
					  }
					},
					error: function (data) {
					 console.log('Error:', data);
					}
				});
	        }
	    });

	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>