@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')
<style>
.title-info-taikhoan{
     padding-top: 12px;
}
.title-info-hoso{
  
}
.title-info-chamcong{
     padding-top: 12px;
}
.title-info-luongthuong{
     padding-top: 10px;
}
.title-info-danhgia{
     padding-top: 10px;
}
.title-info-quantri{
     padding-top: 10px;
}
</style>

<div class="row list-items">
			<div class="col-lg-12">
				<div class="row">
					@if(!empty($listHilight))
						<div class="col-sm-6">
				            <h4><a href="{{ route('getNewsList')}}">Tin nổi bật</a></h4>
				            <div class="panel-group" id="accordion">
			                
								<?php $tmp=1; ?>
			                   @foreach ($listHilight as $key=>$val)
					                <div class="panel panel-default">
					                    <div class="panel-heading">
					                        <h4 class="panel-title">
					                        	@if(in_array('danhgia-list',$arr_route))<a href="{{ route('getNewsEdit',['id'=>$val->id]) }}" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>@endif
					                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $tmp }}">{{ $val->title }}</a>
					                        </h4>
					                    </div>
					                    <div id="collapse{{ $tmp }}" class="panel-collapse collapse <?php if( $tmp==1 ){ echo "in"; } ?>">
					                        <div class="panel-body">{!! $val->content !!}
					                        </div>
					                    </div>
					                </div>
				                <?php $tmp++; ?>
			                	@endforeach
			                
				            </div>
				            <div class="pull-right"><a href="{{ route('getNewsListHighlight') }}">Xem thêm <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
						</div>
					@endif
					
					@if(!empty($listOther))
						<div class="col-sm-6">
				            <h4><a href="{{ route('getNewsList')}}">Tin tức khác</a></h4>
				            <div class="panel-group" id="accordion_2">
									<?php $param=100; ?>
				                   @foreach ($listOther as $key=>$val)
						                <div class="panel panel-default">
						                    <div class="panel-heading">
						                        <h4 class="panel-title">
	         										@if(in_array('danhgia-list',$arr_route))<a href="{{ route('getNewsEdit',['id'=>$val->id]) }}" style="color: #ed7234;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>@endif
						                            <a data-toggle="collapse" data-parent="#accordion_2" href="#collapse{{ $param }}">{{ $val->title }}</a>
						                        </h4>
						                    </div>
						                    <div id="collapse{{ $param }}" class="panel-collapse collapse <?php if( $param==100 ){ echo "in"; } ?>">
						                        <div class="panel-body">{!! $val->content !!}
						                        </div>
						                    </div>
						                </div>
					                <?php $param++; ?>
				                	@endforeach
				                
			                </div>
				            <div class="pull-right" style="margin-bottom: 25px;"><a href="{{ route('getNewsListOther') }}">Xem thêm <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
						</div>
		            @endif
				</div>
			</div>
		    <div class="col-lg-12">
		    	@if (session('flash_message_err') != '')
				<div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
				@endif
				 <div class="row row-1">
					@if ( count($dataGroupPageHome) > 0 )
						@foreach ( $dataGroupPageHome as $value )
						    <div class="col-lg-4 col-md-4 item-hrms">
								<div class="item-hrm" style="background-image: -webkit-linear-gradient( 0deg, {{ $value->background_color }} 0%, {{ $value->background_color }} 100%); margin-bottom: 20px;box-shadow: inset 0 0 10px #ccc;">
									<div class="item-top row">
										<div class="col-xs-12">
											@if ( $value->icon != NULL )
												<div class="item-logo">
													<img src="{{ asset('uploads/icon-cat-home/'.$value->icon)}}">
												</div>
											@endif
											<div class="wrap-title-info title-info-taikhoan">
												<span class="title-info">{{ $value->title }}</span>
											</div>
										</div>
									</div>
									{!! $value->content !!}
								</div>
							</div>
						@endforeach
					@endif
				</div>
				<!-- -/.row-1 -->
			</div>
		</div>
		<!-- /.list-items -->
@endsection
