@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<div class="row content-function">
	<!-- Danh muc -->
	@include('layouts.chucnangkhac.menuleft')
	<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="title-fuction">
					Danh sách vùng hiển thị trang chủ
					@if(in_array('chucnangkhac-themvunghienthitrangchu',$arr_route))
						<a href="{{ route('addPageHome')}}"><img src="{{ asset('images/general/add.png') }}"></a>
					@endif
				</h4>	
				<div class="ajax_response text-center" style="display: none;"></div>
				<div>
					<p style="font-weight: 600;font-style: italic;text-align: center;">
						<span style="color:red">*</span> Bạn có thể thay đổi vị trí hiển thị bằng cách kéo thả
					</p>
					@if ( count($data) > 0 )
					<ul id="sortable" class="connectedSortable">
						@foreach ( $data as $value )
							<li class="item_{{ $value->id }}" data-id="{{ $value->id }}">{{ $value->title }} <a href="javascript:void(0)" onclick="removeItem({{ $value->id }})" style="float: right; margin-left: 10px;margin-top: -1px;" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a><a href="{{ route('postPageHomeEdit', ['id' => $value->id]) }}"  style="float: right" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li>
						@endforeach
					</ul>
					@endif
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
				url: "{!! route('delPageHomeAjax') !!}",
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
					url: "{!! route('changePositionAjax') !!}",
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
@endsection