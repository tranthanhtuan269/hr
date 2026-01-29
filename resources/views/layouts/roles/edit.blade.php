@extends('layouts.master')
@section('title', 'Vai trò')
@section('content')
<div class="row">
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Sửa Role</h4>
		<div class="row">
			<div class="col-lg-offset-4 col-lg-8">
			    @if (session('flash_message_err') != '')
			    <div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
			    @endif
			        @if(count($errors) > 0)
			        <div class="alert alert-danger" role="alert">
			            <ul>
			                @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			                @endforeach
			            </ul>
			        </div>
			        @endif
			        <?php 
			            // echo "<pre>";
			            // print_r($data);
			            function listCate ($data, $arr = array() , $parent = 0, $str="") {
			            	foreach ($data as $val) {
			            		$id = $val["id"];
			            		$name = $val["privilege_name"];
			            		if ($val["parent_id"] == $parent) {
			            			echo '<li class="list_data">';
			            		        if ($str == "") {
			            		        	echo'<b>'.$str.' '.$name.'</b>';
			            		        } else {
			            		        	if (in_array($id,$arr)) {
			            		        		echo $str.'<input type="checkbox" name="check_id[]" checked="checked" value="'.$id.'" >'.$name.'';
			            		        	}else{
			            		        		echo $str.'<input type="checkbox" name="check_id[]" value="'.$id.'" >'.$name.'';
			            		        	}
			            		        }
			            		    echo '</li>';
			            		    listCate ($data,$arr,$id,$str." --- ");
			            		}
			            	}
			            }
			            echo '<form action="" method="post">';
			            echo '<b>Name</b> : <input type="text" class="form_control_special" name="roles_name" style="margin-bottom:10px;" value="'.$role_name.'">';
			            echo '<ul class="list-roles list-group">';
			              listCate($data,$list_privilegs);	
			            echo '<li style="margin-top:10px;"><input type="submit" name="ok" class="btn btn-sm btn-orange" value="Cập nhật"></li>';
			            echo '</ul>';
			            ?>
			        {{ csrf_field()}}
			        </form>
			</div>
		</div>
    </div>
</div>
@endsection