<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	
	@if(in_array('chiphi-tonghopchiphi',$arr_route))
		<p><a href="{{route('getExpenseGeneral')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tổng hợp</a></p>
	@endif

	@if(in_array('chiphi-danhsachquy',$arr_route))
		<p><a href="{{route('getFundsList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quỹ</a></p>
	@endif

	@if(in_array('chiphi-danhsachchiphi',$arr_route))
		<p><a href="{{route('getExpenseList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Chi phí</a></p>
	@endif

	@if(in_array('chiphi-danhsachkyquy',$arr_route))
		<p><a href="{{route('getSignFundsList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Ký quỹ</a></p>
	@endif

    @if(in_array('chiphi-danhsachchitieuquyphucloi',$arr_route))
        <p><a href="{{route('getWelfareFundsList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quỹ phúc lợi</a></p>
    @endif

    @if(in_array('chiphi-danhsachcauhinhngoaite',$arr_route))
        <p><a href="{{route('getSettingCurrency')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cài đặt ngoại tệ</a></p>
    @endif
</div>