<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	@if(in_array('thietbi-danhmucthietbi',$arr_route))
    	<p><a href="{{route('getCateDeviceList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh mục thiết bị</a></p>
	@endif
	@if(in_array('thietbi-danhsach',$arr_route))
    	<p><a href="{{route('getDeviceList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Thiết bị</a></p>
	@endif
	@if(in_array('thietbi-danhsachbangiaothietbi',$arr_route))
    	<p><a href="{{route('getTakeDeviceList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Bàn giao thiết bị</a></p>
	@endif
</div>