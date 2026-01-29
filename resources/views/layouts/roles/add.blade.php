@extends('layouts.master')

@section('title', 'Vai trò')

@section('content')

<div class="row">
	<?php 
	  function listPrivilegs($data,$parent=0){
	    if(isset($data[$parent])){
	      echo "<ul class='list-roles list-group special'>";
	      foreach($data[$parent] as $k=>$value){
	        echo "<li class='list_data'>";
	        $id=$value['id'];
	        $name = $value["privilege_name"];
	        if(isset($data[$id])){
	            echo '<b>'.$name.'</b>';
	        }else{
	            echo '<input type="checkbox" name="check_id[]" value="'.$id.'" >'. $name.'';
	        }
	        unset($data[$k]);
	        listPrivilegs($data,$id);
	        echo "</li>";
	      }
	      echo "</ul>";
	    }
	  }
	?>
	<div class="col-lg-offset-2 col-lg-8">
		<h4 class="title-fuction">Thêm Role</h4>
		  @if (session('flash_message_succ') != '')
	     	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
	      @endif
		  @if (session('flash_message_err') != '')
	     	 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
	      @endif
		<div class="row">
			<div class="col-lg-offset-3 col-lg-6">
		        <form  class="form-horizontal" method="POST" action="{{ route('postRoleAdd')}}" name="frmRoles">
		          <div class="form-group error">
		          <label class="col-sm-2 control-label" for="inputRolename"> Name</label>
		            <div class="col-sm-10">
		              <input type="text" placeholder="Role name" class="form-control has-error" name="role_name" id="name" required="required">
		               @if($errors->has('role_name'))
		                  <p style="color:red"> {{$errors->first('role_name')}}</p>
		               @endif
		            </div>
		          </div>
		          <div class="form-group">
		            <label class="col-sm-2 control-label" for="inputPrivilegs">Quyền</label>
		            <div class="col-sm-10">
		              <?php 
		              foreach ($arr_privilegs as $value) {
		                  $keyParent = $value['parent_id'];
		                  $data[$keyParent][] = $value;
		                }
		                 echo listPrivilegs($data);  
		             ?>                 
		            </div>
		          </div>
		          <div class="text-center">
		            <input type="submit" class="btn btn-sm btn-orange"  name="ok" value="Thêm mới">
		          </div>
		            {{ csrf_field()}}
		        </form>
			</div>
		</div>
	</div>
		
</div>



@endsection