<?php
namespace App\Mylibs;

/*

Now, you can access this class using the namespace within your classes:
use App\Mylibs\Myfunction

use App\Mylibs\Myfunction
Note: If you need to access models or any helper classes in your custom class make sure you access them using \ before them ex:: \Auth::id() , \URL::to(‘/’) , \MyModel::all(). If you don’t want to add \ then you can call your classes normally but you will have to use the specific class namespace. (ex: in order to call Auth::id(), add namespace use Auth.)

*/

class Myfunction{
    protected $_result ='';
    protected $categories ='';
   /*public static function getPendingOrders() {

        $orders = \Orders::where('status','=','pending')
        		->count();
            
        return $orders;
    }*/



    public function listCate ($data, $arr = array() , $parent = 0, $str="") {
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
	 /*public function callProcessSelect($data, $parent = 0,$text = '',$select=0){
  
   	    foreach ($data as $k => $value){
			if ($value['parent_id'] == $parent) {
				$id = $value['id'];
				if ($select != 0 && $value['id'] == $select) {
					$this->_result .= '<option value="'.$value['id'].'" selected="selected">' .$text.$value['title'].'</option>';
				}else{
				 	$this->_result .= '<option value="'.$value['id'].'">' .$text.$value['title'].'</option>';
				}				
				//unset($data[$k]);
				$this->callProcessSelect($data,$id,$text.'--',$select);	
			}
   		}
   		return $this->_result;

	}*/

	public function categoryChild($id=0,$table) {
	    $results = array();
	    $data = \DB::table($table)->where('parent_id', $id)->where('status', 1)->get();
    	if( count($data) >0 ){
	        foreach( $data as $category)
	        {
	        	$results[$category->id] =  $this->categoryChild( $category->id,$table );
	        	
	        }
    	}

	    return $results;
	}

	public function categoryParent($parent_id) {
	    $results = array();
	    $data = \DB::table('departments')->where('id', $parent_id)->get();
    	if( count($data) >0 ){
	        foreach( $data as $category)
	        {
	        	$results[$category->id] =  $this->categoryParent( $category->parent_id );
	        	
	        }
    	}

	    return $results;
	}

	public function callProcessSelect($data, $parent = 0,$text = '',$select=0){
   	    foreach ($data as $k => $value){
			if ($value->parent_id == $parent) {
				$id = $value->id;
				if ($select != 0 && $value->id == $select) {
					$this->_result .= '<option value="'.$value->id.'" selected="selected">' .$text.$value->title.'</option>';
				}else{
				 	$this->_result .= '<option value="'.$value->id.'">' .$text.$value->title.'</option>';
				}				
				//unset($data[$k]);
				$this->callProcessSelect($data,$id,$text.'--',$select);	
			}
   		}
   		return $this->_result;

	}

	public function callCateDevice($data, $parent = 0,$text = ''){

   	    foreach ($data as $k => $value){
			if ($value->parent_id == $parent) {
				$id = $value->id;
				$style = ( $value->parent_id == 0 )?'font-weight:600':'';
				$aaa = 'onclick="return confirm('."'Bạn có chắc chắn muốn xóa ?'".")";
				$this->_result .= '<p style="'.$style.'">' .$text.$value->title.'<a class="btn-edit" href="'.route('getCateDeviceEdit', ['id' => $value->id]).'"> <img src="'.asset('images/general/edit.png').'"></a><a class="btn-delete" href="'.route('getCateDeviceDel', ['id' => $value->id]).'" '.$aaa.'"> <img src="'.asset('images/general/remove.png').'"></a></p>';	

				//unset($data[$k]);
				$this->callCateDevice($data,$id,$text.'--');	
			}
   		}
   		return $this->_result;

	}

	public function callProcessSelectSpecial($data, $parent = 0,$text = '',$select=0){
		// echo count($children);die;
   	    foreach ($data as $k => $value){
			if ($value->parent_id == $parent) {
				$id = $value->id;
				if ($select != 0 && $value->id == $select) {
					$this->_result .= '<option value="'.$value->id.'" selected="selected">' .$text.$value->title.'</option>';

				}			
				//unset($data[$k]);
				$this->callProcessSelectSpecial($data,$id,$text.'--',$select);	
			}
   		}
   		return $this->_result;

	}

	public function callProcessSelectAttendance($data, $parent = 0,$text = '',$select=0,$arr_departments_id){
   	    foreach ($data as $k => $value){
			if ($value->parent_id == $parent) {
				$id = $value->id;
				if (in_array($value->id, $arr_departments_id)) {
					if ($select != 0 && $value->id == $select) {
						$this->_result .= '<option value="'.$value->id.'" selected="selected">' .$text.$value->title.'</option>';
					}else{
					 	$this->_result .= '<option value="'.$value->id.'">' .$text.$value->title.'</option>';
					}				
				}
				//unset($data[$k]);
				$this->callProcessSelectAttendance($data,$id,$text.'--',$select,$arr_departments_id);	
			}
   		}
   		return $this->_result;

	}

		/**
	* Resize an image and keep the proportions
	* @author Allison Beckwith <allison@planetargon.com>
	* @param string $filename
	* @param integer $max_width
	* @param integer $max_height
	* @return image
	*/

	/**
 	* dd($request->file('fileImage')->getPathName());
    * $filepath = $_FILES['fileImage']['tmp_name'];
    * //dd($filepath);
    * $da = Myfunction::resizeImage($request->file('fileImage')->getPathName(), '100', '100');
    * dd($da);
	**/
	public static function resizeImage($filepath, $max_width, $max_height,$path_upload,$name_image)
	{
	    list($orig_width, $orig_height) = getimagesize($filepath);
	    $width = $orig_width;
	    $height = $orig_height;

	    # taller
	    if ($height > $max_height) {
	        $width = ($max_height / $height) * $width;
	        $height = $max_height;
	    }

	    # wider
	    if ($width > $max_width) {
	        $height = ($max_width / $width) * $height;
	        $width = $max_width;
	    }

	    $image_p = imagecreatetruecolor($width, $height);

	    $image = imagecreatefromjpeg($filepath);

	    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
        
        imagejpeg($image_p,$path_upload.$name_image);
        
        imagedestroy($image_p);
	    //return $image_p;
	}


}