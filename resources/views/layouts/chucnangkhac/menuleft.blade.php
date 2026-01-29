<div class="col-sm-2">

        <p><a href="{{route('settingPageHome')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình trang chủ</a></p>


    @if(in_array('chucnangkhac-cauhinhemail',$arr_route))
		<p><a href="{{route('settingEmail')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình Email</a></p>
    @endif
    @if(in_array('chucnangkhac-cauhinhluongcoban',$arr_route))
    	<p><a href="{{route('settingSalaryBasic')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình mức lương cơ bản + quỹ phúc lợi</a></p>
    @endif
    @if(in_array('chucnangkhac-cauhinhmucchiuthue',$arr_route))
    	<p><a href="{{route('settingTax')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình mức thuế</a></p>
    @endif
    @if(in_array('chucnangkhac-cauhinhkhac',$arr_route))
        <p><a href="{{route('settingOthers')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình khác</a></p>
    @endif
    @if(in_array('chucnangkhac-cauhinhmienchamcong',$arr_route))
        <p><a href="{{route('settingExceptionalAttendance')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình miễn chấm công</a></p>
    @endif
    @if(in_array('chucnangkhac-cauhinhchamcongnghiphep',$arr_route))
        <p><a href="{{route('settingAbsentAttendance')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình chấm công nghỉ phép</a></p>
    @endif
</div>