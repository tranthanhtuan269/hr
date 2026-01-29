@extends('layouts.master')

@section('title', 'TOH HRMS')

@section('content')

<div class="row list-items">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12">
			            <h4><a href="{{ route('getNewsList')}}">Tin tức khác</a></h4>
			            <div class="panel-group" id="accordion">
		                @if(!empty($data))
							<?php $tmp=1; ?>
		                   @foreach ($data as $key=>$val)
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
		                @endif
			            </div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 text-right">
				{{ $data->appends(Request::all())->links() }} 
			</div>
		</div>
		<!-- /.list-items -->
@endsection
