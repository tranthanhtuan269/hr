<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	@if(in_array('user-list',$arr_route))
		<p><a href="{{route('getUserList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quản trị tài khoản</a></p>
	@endif
	@if(in_array('roles-list',$arr_route))
	    <p><a href="{{route('getRoleList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Phân quyền người dùng</a></p>
	@endif
	@if(in_array('tintuc-danhsach',$arr_route))
		<p><a href="{{route('getNewsList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quản lý tin tức</a></p>
	@endif
</div>