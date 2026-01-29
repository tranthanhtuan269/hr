@extends('layouts.master')

@section('title', 'Lương thưởng')

@section('content')

<?php
	$data = array( 'job_title_id' ,'department_id');
?>
<div class="row setting_salary">
		<!-- Danh muc -->
		@include('layouts.luongthuong.server.menuleft')

	<div class="col-lg-10">
		<h4 class="title-fuction">Cấu hình Ki </h4> 
		<div class="row">
            <div class="col-12">
                <ul class="list clearfix">
                    @if(in_array('luongthuong-xemcauhinhkihieusuatnam',$arr_route))
                        <li><a href="{{route('addConfigKiPerformance')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình KI(hiệu suất) trong năm</a></li>
                    @endif
                    @if(in_array('luongthuong-cauhinhkinoiquynam',$arr_route))
                        <li><a href="{{route('settingConfigKiRules')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình KI(phong trào) trong năm</a></li>
                    @endif
                
                    @if(in_array('luongthuong-danhsachkinoiquynam',$arr_route))
                        <li><a href="{{route('getKiRules')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> KI(nội quy) trong năm</a></li>
                    @endif
                </ul>
            </div>
		</div>
	</div>
</div>
@endsection