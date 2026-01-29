<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $__env->yieldContent('title'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/normalize.css')); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/fontawesome.css')); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/style.css')); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/combobox.css')); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/jquery-ui.css')); ?>">
	<link rel="shortcut icon" href="<?php echo e(asset('images/favicon.ico')); ?>" /> 

	<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/jquery.combobox.js')); ?>"></script>
	<script src="<?php echo e(asset('js/plugin/ckeditor/ckeditor.js')); ?>"></script>
	<script src="<?php echo e(asset('js/checkbox.js')); ?>"></script>
	<script src="<?php echo e(asset('js/tableHeadFixer.js')); ?>"></script>
	<script src="<?php echo e(asset('js/demo.js')); ?>"></script>
	<script src="<?php echo e(asset('js/function.js')); ?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
	<?php /* <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet"> */ ?>
</head>
<body>
	<div class="wrapper">
		<header class="main-header">
			<div class="container">
				<div class="row">
					<div class="col-md-3 main-logo text-center logo">
						<a href="<?php echo e(url('toh_hrm')); ?>"><img src="<?php echo e(asset('images/dashboard/logo.png')); ?> "></a>
					</div>
					<div class="col-md-9 main-info">
						<div class="row">
							<div class="col-md-8">
								<p class="name-company text-center">Công ty phần mềm Tower Hà Nội</p>
								<p class="name-website text-center">HỆ THỐNG QUẢN LÝ NHÂN SỰ</p>
							</div>
							<div class="col-md-4">
								<a href="#" class="dropdown-toggle navbar-right" data-toggle="dropdown" id="dropdownMenu1" aria-expanded="true">
									<?php if(empty(Auth::user()->avatar)): ?>
										<img src="<?php echo e(asset('images/dashboard/avatar.png')); ?>" class="user-image" style="width:64px;height:64px;border-radius:100%;float: left;margin-right:5px;"  alt="User Image">
									<?php else: ?>
										<img style="width:64px;height:64px;border-radius:100%;float: left;margin-right:5px;" src="<?php echo e(asset('uploads/users/'. Auth::user()->avatar )); ?> " class="user-image" alt="User Image">
									<?php endif; ?> &nbsp;
									<span style="position: relative; top:20px">
										<span class="hidden-xs" style="word-wrap: break-word;"> <?php echo e(str_limit(Auth::user()->name, $limit = 30, $end = '...')); ?></span> <span class="add-caret">&nbsp;</span>
									</span>
					            </a>
					            <ul class="dropdown-menu fix-dropdown" aria-labelledby="dropdownMenu1">
								    <li><a href="<?php echo e(route('getTaikhoanInfo')); ?>">Thông tin tài khoản</a></li>
								    <li role="separator" class="divider"></li>
								    <li><a href="<?php echo e(url('logout')); ?>">Đăng xuất</a></li>
							  	</ul>
							</div>
						</div>

					</div>
				</div>
		<div class="row" style="margin-top:15px; margin-left: 0px;margin-right: 0px;">
			<div class="navbar navbar-default navbar-static-top">
			       <div class="col-md-3">
			        <div class="navbar-header">
			          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			          </button>
			          <a class="navbar-brand" href="#">&nbsp;</a>
			        </div>
			        </div>
			        <div class="col-md-9">
			        <div class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
						    <li class="dropdown menu-large <?php echo e(Request::is('toh_hrm/hoso/thongtin*') || Request::is('toh_hrm/hoso/nhanvientructhuoc*') || Request::is('toh_hrm/hoso/congtac*')? 'current-menu' : ''); ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hồ sơ <b class="caret"></b></a>				
								<ul class="dropdown-menu megamenu row">
									<li class="sub-menu"><a href="<?php echo e(route('getHosoInfo')); ?>">Hồ sơ nhân sự</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('getHosoCongtac')); ?>">Quá trình công tác</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('getFilePersonnelByManger')); ?>?type=hidden">Nhân viên trực thuộc quản lý</a></li>
								</ul>
							</li>
							<li class="dropdown menu-large <?php echo e(Request::is('toh_hrm/lam-them-gio/*') || Request::is('toh_hrm/chamcong/tonghop')||Request::is('toh_hrm/chamcong/dilam')||Request::is('toh_hrm/chamcong/dimuon')||Request::is('toh_hrm/chamcong/ngayphep') ? 'current-menu' : ''); ?>">
				                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Chấm công <b class="caret"></b></a>				
								<ul class="dropdown-menu megamenu row">
									<?php /* <li class="sub-menu"><a href="<?php echo e(route('getAttendanceTotal')); ?>">Tổng hợp</a></li> */ ?>
									<li class="sub-menu"><a href="<?php echo e(route('getAttendanceWork')); ?>">Đi làm</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('getAttendanceWorkLate')); ?>">Đi muộn</a></li>
									<li class="sub-menu">
										<a href="<?php echo e(url('toh_hrm/lam-them-gio/index')); ?>">Làm thêm giờ</a>				
								 	</li>
								</ul>
							</li>

							<li class="dropdown menu-large <?php echo e(Request::is('toh_hrm/luongthuong/tonghopchitiet')||Request::is('toh_hrm/luongthuong/thongtinluong')||Request::is('toh_hrm/luongthuong/thongtinthuongphucap')||Request::is('toh_hrm/luongthuong/thongtinthuebaohiem')||Request::is('toh_hrm/luongthuong/thongtincackhoankhac') ? 'current-menu' : ''); ?>">
								<a href="<?php echo e(route('getAllClient')); ?>" class="dropdown-toggle" data-toggle="dropdown">Lương thưởng <b class="caret"></b></a>				
								<ul class="dropdown-menu megamenu row">
									<li class="sub-menu"><a href="<?php echo e(route('getAllClient')); ?>">Tổng hợp</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('getSalaryClient')); ?>">Thông tin lương</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('getAllowanceClient')); ?>">Thưởng và phụ cấp</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('getTaxInsurranceClient')); ?>">Thuế và bảo hiểm</a></li>
								</ul>
							</li>
							<li class="dropdown menu-large <?php echo e(Request::is('toh_hrm/danhgia/diem-tin-nhiem') || Request::is('toh_hrm/danhgia/xemhuongdan')||Request::is('toh_hrm/danhgia/tudanhgiathang*')||Request::is('toh_hrm/danhgia/suatudanhgiathang*')||Request::is('toh_hrm/danhgia/quanlydanhgiathang*')||Request::is('toh_hrm/danhgia/suaquanlydanhgiathang*')  ||Request::is('toh_hrm/danhgia/danhsachnhanvientructhuoctheothang*')||Request::is('toh_hrm/danhgia/danhgiaquanlytheothang*')||Request::is('toh_hrm/danhgia/suadanhgiaquanlytheothang*')||Request::is('toh_hrm/danhgia/tudanhgianam*')||Request::is('toh_hrm/danhgia/suatudanhgianam*')||Request::is('toh_hrm/danhgia/danhsachnhanvientructhuoctheonam*')||Request::is('toh_hrm/danhgia/suaquanlydanhgianam*')||Request::is('toh_hrm/danhgia/danhgiaquanlytheonam*')||Request::is('toh_hrm/danhgia/suadanhgiaquanlytheonam*')? 'current-menu' : ''); ?>">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Đánh giá <b class="caret"></b></a>				
								<ul class="dropdown-menu megamenu row">
									<li class="sub-menu"><a href="<?php echo e(route('viewEvaluationSupport')); ?>">Hướng dẫn</a></li>
<!-- 									<li class="sub-menu"><a href="<?php echo e(route('getEvaluationMonthbyUser')); ?>">KPI tháng</a></li> -->
									<li class="sub-menu"><a href="<?php echo e(route('getEvaluationYearbyUser')); ?>">Đánh giá nâng lương</a></li>
									<li class="sub-menu"><a href="<?php echo e(route('danhgia.diem-tin-nhiem')); ?>">Điểm tín nhiệm</a></li>
								</ul>

							</li>
							<li class="dropdown menu-large <?php echo e(Request::is('toh_hrm/vay-von/*') ? 'current-menu' : ''); ?>">
								<a href="<?php echo e(url('toh_hrm/vay-von/index')); ?>">Tín dụng</a>		
							</li>
							<li class="dropdown menu-large <?php echo e(Request::is('toh_hrm/thietbi/danhsachphianguoidung') ? 'current-menu' : ''); ?>">
								<a href="<?php echo e(route('getDeviceClientList')); ?>">Thiết bị</a>		
							</li>
							<?php if( in_array('hoso-list',$arr_route) || in_array('chamcong-list',$arr_route) || in_array('luongthuong-list',$arr_route) || in_array('quydoiheso-congthuc',$arr_route) || in_array('danhgia-viethuongdan',$arr_route) || in_array('user-list',$arr_route) ||in_array('chucnangkhac-cauhinhemail',$arr_route)|| in_array('roles-list',$arr_route) ): ?>
								<li class="dropdown menu-large <?php echo e(!Request::is('toh_hrm/hoso/nhanvientructhuoc') && !Request::is('toh_hrm/vay-von/*') && !Request::is('toh_hrm/lam-them-gio/*') && !Request::is('toh_hrm/hoso/thongtin*') && !Request::is('toh_hrm/hoso/congtac*') && !Request::is('toh_hrm/chamcong/tonghop')&& !Request::is('toh_hrm/chamcong/dilam')&& !Request::is('toh_hrm/chamcong/dimuon')&& !Request::is('toh_hrm/chamcong/ngayphep')&& !Request::is('toh_hrm/luongthuong/tonghopchitiet')&& !Request::is('toh_hrm/luongthuong/thongtinluong')&& !Request::is('toh_hrm/luongthuong/thongtinthuongphucap')&& !Request::is('toh_hrm/luongthuong/thongtinthuebaohiem')&& !Request::is('toh_hrm/luongthuong/thongtincackhoankhac')&& !Request::is('toh_hrm/danhgia/xemhuongdan')&& !Request::is('toh_hrm/danhgia/tudanhgiathang*')&& !Request::is('toh_hrm/danhgia/suatudanhgiathang*')&& !Request::is('toh_hrm/danhgia/quanlydanhgiathang*')&& !Request::is('toh_hrm/danhgia/suaquanlydanhgiathang*')  && !Request::is('toh_hrm/danhgia/danhsachnhanvientructhuoctheothang*')&& !Request::is('toh_hrm/danhgia/danhgiaquanlytheothang*')&& !Request::is('toh_hrm/danhgia/suadanhgiaquanlytheothang*')&& !Request::is('toh_hrm/danhgia/tudanhgianam*')&& !Request::is('toh_hrm/danhgia/suatudanhgianam*')&& !Request::is('toh_hrm/danhgia/danhsachnhanvientructhuoctheonam*')&& !Request::is('toh_hrm/danhgia/suaquanlydanhgianam*')&& !Request::is('toh_hrm/danhgia/danhgiaquanlytheonam*')&& !Request::is('toh_hrm/danhgia/suadanhgiaquanlytheonam*') && !Request::is('toh_hrm/thietbi/danhsachphianguoidung') && !Request::is('toh_hrm/danhgia/diem-tin-nhiem') ? 'current-menu' : ''); ?>">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Quản trị <b class="caret"></b></a>				
									<ul class="dropdown-menu megamenu row">
										<?php if(in_array('hoso-list',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getPersonnelList')); ?>">Hồ sơ nhân sự</a></li>
										<?php endif; ?>
										<?php if(in_array('chamcong-list',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getAttendanceList')); ?>">Chấm công</a></li>
										<?php endif; ?>
										<?php if(in_array('luongthuong-tonghop',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getAllSalary')); ?>">Lương thưởng</a></li>
										<?php endif; ?>
										<?php if(in_array('quydoiheso-congthuc',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getConvert')); ?>">Quy đổi HS</a></li>
										<?php endif; ?>
										<?php if(in_array('danhgia-viethuongdan',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getEvaluationSupport')); ?>">Đánh giá</a></li>
										<?php endif; ?>
										<?php if( in_array('user-list',$arr_route) ): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getUserList')); ?>">Tài khoản </a></li>
										<?php endif; ?>
										<?php if(in_array('tintuc-danhsach',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getNewsList')); ?>">Tin tức</a></li>
										<?php endif; ?>
										<?php if(in_array('page-list',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getPageList')); ?>">Page</a></li>
										<?php endif; ?>
										<?php if(in_array('chiphi-tonghopchiphi',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getExpenseGeneral')); ?>">Chi phí</a></li>
										<?php endif; ?>
										<?php if(in_array('thietbi-danhsach',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('getDeviceList')); ?>">Thiết bị</a></li>
										<?php endif; ?>
										<?php if(in_array('chucnangkhac-cauhinhemail',$arr_route)): ?>
											<li class="sub-menu"><a href="<?php echo e(route('settingEmail')); ?>">Khác </a></li>
										<?php endif; ?>
									</ul>
								</li>
							<?php endif; ?>
							</ul>
						</div>
			      	</div>
			    	</div> 
				</div><!-- /.navbar-->
			</div>
		<!-- /.container -->
	</header>
	<!-- /.header-->
	<div class="container">
		  <?php echo $__env->yieldContent('content'); ?>
	</div>
	<!--  /.content-wrapper -->
	</div>
<!-- /.wrapper-->

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="footer">
						<p>© CÔNG TY TNHH PHẦN MỀM TOWER HÀ NỘI</p>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<div class="ajax_waiting"></div>
	<div id="snackbar"></div>
	<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(asset('js/jquery-ui.js')); ?>"></script>
	<script src="<?php echo e(asset('js/general.js')); ?>"></script>

	<script>
		// <input type="button" class="btn btn-sm btn-grey" value="Nhập lại" id="btnsubmit" onclick="submitFormReset()">
		function submitFormReset() {
		  $('input[type="number"],input[type="text"],textarea').val('');
		  $('input:checkbox').removeAttr('checked');
		  $("select").val("0");
		}
		$(document).ready(function(){
			if ($(".alert")[0]){
				setTimeout(function() {
					$(".alert-success").fadeOut("slow" );
				}, 2000);
			}
		});

    	var baseURL="<?php echo URL::to('/'); ?>";

	</script>
</body>
</html>