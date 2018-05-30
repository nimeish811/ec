<?php
class Api_model extends CI_Model {
 
	
	public function __construct() {
       parent::__construct();
        $date = new DateTime();
        $this->getTimestamp = $date->getTimestamp();
 
	}

	public function get_source_lang($data){

		$result = $this->db->query("select * from tbl_source_language")->result_array();
		
		if($result) {
			http_response(200, 1, 'No Record Found', $result,'');	
		} 
		else {
			http_response(404, 0, 'RECORD NoT Found', $result,'');
			return false;
		}
	}

	public function get_target_lang(){
		
		$result = $this->db->query("select * from tbl_target_language")->result_array();
		
		if($result) {
			http_response(200, 1, 'No Record Found', $result,'');	
		} 
		else {
			http_response(404, 0, 'RECORD NoT Found', $result,'');
			return false;
		}
	}

	public function get_exercise_mode($data){
		
		$result = $this->db->query("select * from tbl_exercise_mode")->result_array();
		if($result) {
			http_response(200, 1, 'No Record Found', $result,'');	
		} 
		else {
			http_response(404, 0, 'RECORD NoT Found', $result,'');
			return false;
		}
	}

	public function get_category_list($data){
		

		 $lang = $data['lang'];
		 $exercise_mode = $data['exercise_mode_id'];
		 if($lang=="" || $exercise_mode=="" ){

			 	http_response(404, 0, 'Parameters not passed', array(),'');
			 	return false;
			 	
		 }else{
		 		
		 		$get_field_name = $this->db->query("SELECT * FROM tbl_source_language WHERE source_language_id='$lang'")->result_array();
				$language_code = $get_field_name[0]['language_code'];


				$result = $this->db->query("select exercise_mode_category_id,image,category_name_in_$language_code as category_name from tbl_exercise_mode_categories where exercise_mode_id='$exercise_mode' AND is_active='1' AND is_delete='0' order by category_name_in_en asc")->result_array();
				
				foreach ($result as $key => $value){

					if($value['image']==""){

						$result[$key]['image_path'] = "";
					
					}else{

						$result[$key]['image_path'] = base_url().'uploads/'.$value['image'];
					}
					
				}
				if($result){

					http_response(200, 1, 'No Record Found', $result,'');

				} 
				else{
					
					http_response(404, 0, 'RECORD NoT Found', $result,'');
					return false;
				}
		}
	}


	public function get_subcategory_list($data) {
	
		 $lang = $data['lang'];
		 $category_id = $data['category_id'];

		 if($lang=="" || $category_id==""){
		        http_response(404, 0, 'Parameters not passed', array(),'');
			 	return false;
		 }else{

		 		$get_field_name = $this->db->query("SELECT * FROM tbl_source_language WHERE source_language_id='$lang'")->result_array();
				$language_code = $get_field_name[0]['language_code'];
				$result = $this->db->query("select exercise_mode_subcategory_id,category_id,difficulty_level_id,image,subcategory_name_in_$language_code as subcategory_name from tbl_exercise_mode_subcategories where category_id='$category_id' AND is_active='1' AND is_delete='0' order by subcategory_name_in_en asc")->result_array();
		
					foreach ($result as $key => $value){
							if($value['image']==""){

								$result[$key]['image_path'] = "";

							}else{
							
								$result[$key]['image_path'] = base_url().'uploads/'.$value['image'];
						
							}
					}
				
				if($result){
					
					http_response(200, 1, 'No Record Found', $result,'');

				}else{
					
					http_response(404, 1, 'No Record Found', $result,'');
					return false;
				}
		}
	}

	public function get_exercise_type_list($data){
		
			$lang = $data['lang'];
			$category_id = $data['subcategory_id'];
			if($lang=="" || $category_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

		 		$get_field_name = $this->db->query("SELECT * FROM tbl_source_language WHERE source_language_id='$lang'")->result_array();
				$language_code = $get_field_name[0]['language_code'];
				
					$result = $this->db->query("select type.id, type.type_$language_code as type_name,type.image  from tbl_exercise_type type LEFT JOIN tbl_exercise_mode_categories_exercise ex ON type.id=ex.exercise_type_id where ex.category_id='$category_id' AND ex.is_active='1'")->result_array();

					foreach ($result as $key => $value){
						
						if($value['image']==""){

							$result[$key]['image_path'] = base_url().'assets/thumb_image_not_available.png';

						}else{

							$result[$key]['image_path'] = base_url().'uploads/'.$value['image'];
						}

					}
					if($result){

						http_response(200, 1, 'No Record Found',$result,'');	
					} 
					else{

						http_response(404, 1, 'No Record Found', $result,'');
						return false;
		 			}
		    }
	}
	
	public function get_sorce_lan_word_type_1($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');
			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					
		 			$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT $field_name as word,word_id,image_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1'")->result_array();
					foreach ($result as $key => $value) {
					  

					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
						
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$queid = $value['word_id'];
					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 

					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {

								        $result[$key]['option'][$key1]['is_correct'] = 0;
					}
//==================================================For GETTING  currect option================================================================
					 	 	$wid = $result[$key]['word_id'];

					 		$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'")->result_array();

					 		$arr  = array_push($result[$key]['option'], $option1[0]);
					 		
					 		foreach ($option1 as $key2 => $value2) {
					 		 	$result[$key]['option'][3]['is_correct'] = 1;
					 		}
					 			// for random index of options
					 		shuffle($result[$key]['option']);
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					  shuffle($result);
					     $result = array_slice($result, 0, $limit); 

					       
						   http_response(200, 1, 'No Record Found',$result,'');	
		}
	}



	public function get_sorce_lan_word_type_2($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					
		 			$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					//print_r($get_field_name); 
					$field_name = $get_field_name[0]['field_name'];
					


					$result = $this->db->query("SELECT $field_name as word,word_id,image_file,word_english,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

					foreach ($result as $key => $value) {
					
					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
					
					$language_code = $get_field_name[0]['language_code']; 
					 $aname = str_replace(" ","_",$value['audio_file']); 
					
					$root_path  = $this->config->item('root_path');


					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname.'_'.$language_code.'.m4a';

					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}
					 
						
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$queid = $value['word_id'];
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 

					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {

								        $result[$key]['option'][$key1]['is_correct'] = 0;
							 }
//==================================================For GETTING  currect option================================================================
					 	 	$wid = $result[$key]['word_id'];
					 		$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'")->result_array();

					 		 $arr  = array_push($result[$key]['option'], $option1[0]);
					 		
					 		 foreach ($option1 as $key2 => $value2) {

					 		 	$result[$key]['option'][3]['is_correct'] = 1;
					 		 }
					 			
					 			// for random index of options
					 			shuffle($result[$key]['option']);
							
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					        shuffle($result);
							$result = array_slice($result, 0, $limit); 
					      

					
							http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}


	public function get_sorce_lan_word_type_3($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();

					foreach ($result as $key => $value) {
					  

//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$queid = $value['word_id'];
					$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 


					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}		

					
					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {
								        $result[$key]['option'][$key1]['is_correct'] = 0;
							 }
//==================================================For GETTING  currect option================================================================
					 	 	$wid = $result[$key]['word_id'];
					 		$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'")->result_array();

					 		 $arr  = array_push($result[$key]['option'], $option1[0]);
					 		
					 		 foreach ($option1 as $key2 => $value2) {
					 		 	$result[$key]['option'][3]['is_correct'] = 1;
					 		 }
					 			
					 			// for random index of options
					 			shuffle($result[$key]['option']);
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS ==================================================
					   shuffle($result);
					      $result = array_slice($result, 0, $limit); 
					       
						   http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}

	public function get_sorce_lan_word_type_4($data){
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

					foreach ($result as $key => $value) {
					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];

					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}	
					 $result[$key]['option']=$value['word'];
						
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					       shuffle($result);
					       $result = array_slice($result, 0, $limit); 
					       http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}

	public function get_sorce_lan_word_type_5($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{
//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();
					
					
					foreach ($result as $key => $value) {
					  
					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
					$language_code = $get_field_name[0]['language_code'];
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					      shuffle($result);
					      $result = array_slice($result, 0, $limit); 
					      
						http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}

public function get_sorce_lan_word_type_9($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

		//	print_r($data); die();
			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{
//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();
					
					
					foreach ($result as $key => $value) {
					
					$language_code = $get_field_name[0]['language_code'];
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					      shuffle($result);
					      $result = array_slice($result, 0, $limit); 
						  http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}


	public function get_sorce_lan_word_type_6($data){
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					
		 			$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

					foreach ($result as $key => $value) {
					
					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					 $result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
							
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================
;
					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}
					$queid = $value['word_id'];
					$option = $this->db->query("SELECT image_file
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1' AND is_image_available='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 1); 

					$result[$key]['option'] = $option;

					$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id' AND is_active='1'")->result_array();
					$category_folder = $get_cate_name[0]['category_name'];

					foreach ($option as $key1 => $value1) {
						
						$result[$key]['option'][$key1]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value1['image_file'];

									
								        $result[$key]['option'][$key1]['is_correct'] = 0;
							 }
//==================================================For GETTING  currect option================================================================
					 	 	$wid = $result[$key]['word_id'];
					 		$option1 = $this->db->query("SELECT image_file
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

					 		 $arr  = array_push($result[$key]['option'], $option1[0]);
					 		
					 		foreach ($option1 as $key2 => $value2) {

					 		 			  $result[$key]['option'][1]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value2['image_file'];
					 		 		

					 		 	$result[$key]['option'][1]['is_correct'] = 1;
					 		}
					 			
					 			// for random index of options
					 			shuffle($result[$key]['option']);
							
					 	

					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					     shuffle($result);
					     $result = array_slice($result, 0, $limit); 
					       

					
							http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}



	public function get_sorce_lan_word_type_7($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

		//	print_r($data); die();
			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================

					$result = $this->db->query("SELECT word_id
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();

					foreach ($result as $key => $value) {
					  
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name1 = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT word_id,$field_name as word_s,$field_name1 as word_t
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 6); 

					$result[$key]['option'] = $option;
					$result[$key]['option1'] = $option;
					foreach ($option as $key1 => $value1) {

						$result[$key]['option'][$key1]['word']=$value1['word_s'];
						$result[$key]['option1'][$key1]['word']=$value1['word_t'];
					}
					 		
					 			
					 			// for random index of options
					 			shuffle($result[$key]['option']);
					 			shuffle($result[$key]['option1']);
							
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					     shuffle($result);
					      $result = array_slice($result, 0, $limit); 
					       

							http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}

	public function get_sorce_lan_word_type_8($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

		//	print_r($data); die();
			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed' , array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];
					//print_r($get_field_name); die();

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

					$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id'")->result_array();
					$category_folder = $get_cate_name[0]['category_name'];

					foreach($result as $key => $value) {
					  

					  $result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$value['image_file'];
					
					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}					  $result[$key]['word']=$value['word'];
						

//==================================================For GETTING  currect option================================================================
					 	 	$wid = $result[$key]['word_id'];
					 	 	$get_field_name1 = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
							$field_name = $get_field_name1[0]['field_name'];

					 		$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'" )->result_array();

					 		 foreach ($option1 as $key2 => $value2) {
					 		 			$result[$key]['option']=$value2['word'];		
					 		
					 		 }
					 			
					 			
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					       shuffle($result);
					       $result = array_slice($result, 0, $limit); 
					       

					
							http_response(200, 1, 'No Record Found',$result,'');	
				
		}
	}

// --------------------------------------------------------  GRAMMER MODE------------------------------------------------------------

	public function get_grammer_type_1($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');
			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{

					$result = $this->db->query("SELECT question As word,options
								FROM tbl_grammer_master where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND question_type='1' AND is_active='1' AND is_delete='0'")->result_array();
					$optionArr = [];
					foreach ($result as $key => $value) {

					 	 	$option = explode("#",$value['options']);
					 	 	$objOpt = [];
					 	 	$ocount=1;
					 	 	foreach  ($option as $k => $v){
					 	 		$objOpt['word'] = $v;
					 	 		if($ocount=="1"){
										$objOpt['is_correct'] = 1;
					 	 		}else{
					 	 			$objOpt['is_correct'] = 0;
					 	 		}
					 	 		
					 	 		$optionArr[$k] = $objOpt;
					 	 		$ocount++;
					 	 	}
					 	 	$result[$key]['option'] = $optionArr;
					 	 	shuffle($result[$key]['option']);
					 			
					} // END FOR LOOP OF RESULT ARRAY
					  
					shuffle($result);
					$result = array_slice($result, 0, $limit); 
					http_response(200, 1, 'No Record Found',$result,'');	
		}
	}

	public function get_grammer_type_2($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');

			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{


		 				// $str = "Robert på ####### fabriken idag.";
		 				// $count = substr_count($str, '#');
		 		 	// 	$str1 = str_repeat("#",$count);
		 			 //  	echo $final_string = str_replace($str1,"###",$str);

		 		  //       die();

				$result = $this->db->query("SELECT question,options
								FROM tbl_grammer_master where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND question_type='2' AND is_active='1' AND is_delete='0'")->result_array();
					 
				$ctn=0;
				foreach ($result as $key => $value) {
				 	
				 	$count = substr_count($value['question'], '#');
	 		 		$str1 = str_repeat("#",$count);
	 			    $final_string = str_replace($str1,"...",$value['question']);
	 			    $result[$ctn]['question']= $final_string;
	 			    $ctn++;

				}
						
					shuffle($result);
					$result = array_slice($result, 0, $limit); 
				    http_response(200, 1, 'No Record Found',$result,'');	
			}
	}
//========================================================= Grammer Mode END ===========================================================

//========================================================= Phrases Mode Start ===========================================================

public function get_phrases_type_1($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$limit = $this->config->item('api_record_limit');

			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id==""){

				 	http_response(404, 0, 'Parameters not passed' , array(),'');
				 	return false;
			
		 	}else{

//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];
					$language_code = $get_field_name[0]['language_code'];
					//print_r($get_field_name); die();

					$result = $this->db->query("SELECT phrases_id,phrase_$language_code as word,phrase_en,audio_file
								FROM tbl_phrases where category_id='$category_id' AND subcategory_id='$subcategory_id' AND is_active='1'")->result_array();

					$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id'")->result_array();
					$category_folder = $get_cate_name[0]['category_name'];

					foreach($result as $key => $value) {
					 
					 $language_code = $get_field_name[0]['language_code'];
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
				
					if(file_exists($aufile)){

					 	$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

					 	$result[$key]['audio_file']="";

					}
						$result[$key]['word']=$value['word'];
						

//==================================================For GETTING  currect option================================================================
					 	 	$wid = $result[$key]['phrases_id'];
					 	 	$get_field_name1 = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
							$field_name = $get_field_name1[0]['field_name'];
							$language_code = $get_field_name1[0]['language_code'];

					 		$option1 = $this->db->query("SELECT phrase_$language_code as word
								FROM tbl_phrases where category_id='$category_id'  AND phrases_id=$wid AND is_active='1'" )->result_array();
					 		
					 		 foreach ($option1 as $key2 => $value2) {
					 		 			$result[$key]['option']=$value2['word'];		
					 		 }
					 			
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
					       shuffle($result);
					       $result = array_slice($result, 0, $limit); 
					       http_response(200, 1, 'No Record Found',$result,'');	
				

		}
	}

//========================================================= Phrases Mode END ===========================================================


//========================================================= Dialogue Mode START ===========================================================

	public function get_dialogue_type_1($data){

				$slang = $data['slang'];
				$tlang = $data['tlang'];
				$category_id = $data['category_id'];
				$subcategory_id = $data['subcategory_id'];
				$limit = $this->config->item('api_record_limit');

				if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id==""){

					 	http_response(404, 0, 'Parameters not passed' , array(),'');
					 	return false;
				
			 	}else{

			 			$result = $this->db->query("SELECT dialogue_master_id,title,full_audio
									FROM tbl_dialogue_master  where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
						  

					foreach($result as $key => $value) {
						
						$root_path  = $this->config->item('root_path');
						$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['full_audio'];
						
						if(file_exists($aufile)){

						 	$result[$key]['full_audio']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['full_audio'];

						}else{

						 	$result[$key]['full_audio']="";

						}




						  	$mid = $value['dialogue_master_id'];
						  	$result1 = $this->db->query("SELECT phrase,audio_name,speaker,sequence_no
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' order by sequence_no asc")->result_array();
						 

					 		foreach ($result1 as $k=> $v) {
					 			
								$root_path  = $this->config->item('root_path');
								$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$v['audio_name'];
							
								if(file_exists($aufile)){

								 	$result1[$k]['audio_name']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$v['audio_name'];

								}else{

								 	$result1[$k]['audio_name']="";

								}
					 		}

						  	$result[$key]['list']=$result1;

					}
						  
						  shuffle($result);
						  $result = array_slice($result, 0, $limit); 
					      http_response(200, 1, 'No Record Found',$result,'');	

			 	}


	}


	public function get_dialogue_type_2($data){

				$slang = $data['slang'];
				$tlang = $data['tlang'];
				$category_id = $data['category_id'];
				$subcategory_id = $data['subcategory_id'];
				$limit = $this->config->item('api_record_limit');

				if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id==""){

					 	http_response(404, 0, 'Parameters not passed' , array(),'');
					 	return false;
				
			 	}else{

			 		$result = $this->db->query("SELECT dialogue_master_id,title,full_audio
									FROM tbl_dialogue_master  where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
						  
						foreach($result as $key => $value) {

						  		$mid = $value['dialogue_master_id'];
						  		$result1 = $this->db->query("SELECT phrase,audio_name,speaker
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' order by sequence_no asc limit 2")->result_array();
						  		
						  		$result2 = $this->db->query("SELECT phrase,sequence_no,speaker
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' limit 2,10")->result_array();
						  			$result[$key]['list']=$result1;
						  			$optionArr = [];
						  		foreach($result2 as $k => $v){
						  			
						  			$objOpt = [];
						 	 		$objOpt['word'] = $v['phrase'];
						 	 		$objOpt['sequence'] = $v['sequence_no'];
						 	 		$objOpt['speaker'] = $v['speaker'];
						 	 	// 	if($v['sequence_no']=="3"){
										// $objOpt['is_correct'] = 1;
						 	 	// 	}else{
						 	 	// 		$objOpt['is_correct'] = 0;
						 	 	// 	}
						 	 		
						 	 		$optionArr[$k] = $objOpt;
						 	 		
						 	 	}

						 	 	shuffle($optionArr);
						 	 	$result[$key]['option']=$optionArr;
						}
						  
						  shuffle($result);
						  $result = array_slice($result, 0, $limit); 
					      http_response(200, 1, 'No Record Found',$result,'');	

			 	}


	}
//========================================================= Dialogue Mode END ===========================================================

//=========================================================Culture Mode Start ==========================================================

		public function get_culture_type_1($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');
			
			if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				 	http_response(404, 0, 'Parameters not passed', array(),'');
				 	return false;
			
		 	}else{


		 			$result1 = $this->db->query("SELECT culture_master_id,title_text,external_link,paragraph,image_name
								FROM tbl_culture_master where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
			
		 			$questionarray =[];
		 			foreach ($result1 as $key1 => $value1) {
		 				
		 				$result1[$key1]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$value1['image_name'];
		 				$mid= $value1['culture_master_id'];
			 			$result = $this->db->query("SELECT question As word,options
									FROM tbl_culture_question where  culture_master_id='$mid'")->result_array();
						$optionArr = [];
						foreach ($result as $key => $value) {

						 	 	$option = explode("#",$value['options']);
						 	 	$objOpt = [];
						 	 	$ocount=1;
						 	 	foreach  ($option as $k => $v){
						 	 		$objOpt['word'] = $v;
						 	 		if($ocount=="1"){
											$objOpt['is_correct'] = 1;
						 	 		}else{
						 	 			$objOpt['is_correct'] = 0;
						 	 		}
						 	 		
						 	 		$optionArr[$k] = $objOpt;
						 	 		$ocount++;
						 	 	}
						 	 	$result[$key]['option'] = $optionArr;
						 	 	shuffle($result[$key]['option']);

						 	 	
						 			
						} // END FOR LOOP OF RESULT ARRAY
						$result1[$key1]['questions']=$result;
		 			}
					
					  
					shuffle($result1);
					$result = array_slice($result1, 0, $limit); 
					http_response(200, 1, 'No Record Found',$result,'');	
		}
	}


//==========================================================Culture Mode End=============================================================

}
