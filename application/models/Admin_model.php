<?php
class Admin_model extends CI_Model {
 
	
	public function __construct() {
       parent::__construct();
        $date = new DateTime();
        $this->getTimestamp = $date->getTimestamp();
  
	}
/* 

/*
| [:START:]
| -------------------------------------------------------------------
| 
| -------------------------------------------------------------------
*/	
	
	function master_function_get_data_by_condition($table,$condition,$order=null,$orderby=null){

		$this->db->where($condition);
		$this->db->order_by($order, $orderby);
		$query = $this->db->get($table);
		
		return $result =$query->result_array();
		//print_r($result);
	}

	function master_function_for_update_by_conditions($table,$condition,$data){

					$this->db->where($condition);
					$update = $this->db->update($table, $data);
					if($update){
						return true;
				   	}else{
						return false;
					}
	}

	function check_login($data){
		 		
		$result=$this->db->get_Where('tbl_users', $data)->num_rows();
	    return $result;

	}

	function add_product($data){
		$insert = $this->db->insert('tbl_product', $data);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	
	}

	function update_product($data,$id){
					$this->db->where('id', $id);
					$update = $this->db->update('tbl_product', $data);
					if($update){
						return true;
				   }else{
						return false;
					}
	}

	function delete_product($data,$id){

				$this->db->where('id', $id);
			$update = $this->db->update('tbl_product', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}

	public function get_product_list() {
		
		$result = $this->db->query("select * from tbl_product where is_active=1")->result_array();
		return  $result;
	}

	public function get_package_list() {
		
		$result = $this->db->query("select * from tbl_packages where is_active=1")->result_array();
		return  $result;
	}

	function add_package($data){
		$insert = $this->db->insert('tbl_packages', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function update_package($data,$id){
			$this->db->where('id', $id);
			$update = $this->db->update('tbl_packages', $data);
			if($update){
				return true;
		   }else{
				return false;
			}
	}

	function delete_package($data,$id){

				$this->db->where('id', $id);
			$update = $this->db->update('tbl_packages', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}
	function add_order($data){
		$insert = $this->db->insert('tbl_order', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}
	function add_review($data){
		$insert = $this->db->insert('tbl_review', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}
	public function get_review_list() {
		
		$result = $this->db->query("select * from tbl_review where is_active=1")->result_array();
		return  $result;
	}
	
	public function get_order_list() {
		
		$result = $this->db->query("select o.*,p.package_name from tbl_order o LEFT JOIN tbl_packages p ON p.id=o.package_id")->result_array();
		return  $result;
	}

	


	public function get_source_lang() {
		
		$result = $this->db->query("select * from tbl_source_language")->result_array();
		return  $result;
	}
	public function get_exercise_mode() {
		
		$result = $this->db->query("select * from tbl_exercise_mode order by mode_name asc")->result_array();
		return  $result;
	}

	public function get_exercise_type($mid=null) {
		
		$query ="select * from tbl_exercise_type";
		if($mid!=""){

			$query.=" Where exercise_mode_id='$mid'";
		}
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	
	

	function add_lang($data){
		$insert = $this->db->insert('tbl_source_language', $data);
  		$insert_id = $this->db->insert_id();
  		$code = $data['language_code'];
  		$name = strtolower($data['field_name']);
  		
  		$query = "ALTER TABLE tbl_exercise_mode_categories ADD category_name_in_$code VARCHAR( 255 ) NOT NULL after category_name_in_en";
		$this->db->query($query);

		$query = "ALTER TABLE tbl_exercise_mode_subcategories ADD subcategory_name_in_$code VARCHAR( 255 ) NOT NULL after subcategory_name_in_en";
		$this->db->query($query);

		$query = "ALTER TABLE tbl_word ADD $name VARCHAR( 255 ) NOT NULL after word_english";
		$this->db->query($query);
		
		$query = "ALTER TABLE tbl_phrases ADD phrase_$code VARCHAR( 255 ) NOT NULL after phrase_en";
		$this->db->query($query);

		$query = "ALTER TABLE tbl_exercise_type ADD type_$code VARCHAR( 255 ) NOT NULL after type_en";
		$this->db->query($query);

		return  $insert_id;
		
	}


	function add_words($data){
		$insert = $this->db->insert('tbl_word', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_phrases($data){

		//print_r($data); die();
		$insert = $this->db->insert('tbl_phrases', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}


	function add_grammer($data){
		$insert = $this->db->insert('tbl_grammer_master', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}


	function add_dialogue_master($data){
		$insert = $this->db->insert('tbl_dialogue_master', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_dialogue_list($data){
		$insert = $this->db->insert('tbl_dialogue_list', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}


	function add_culture_master($data){
		$insert = $this->db->insert('tbl_culture_master', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_culture_que($data){
		$insert = $this->db->insert('tbl_culture_question', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	// function delete_dialogue_list($id){

	// 			$this->db->where('dialogue_id', $id);
	// 		$delete = $this->db->delete('tbl_dialogue_list');
	// 		if($delete){
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}

	// }


	function add_category_exercise($data){
		$insert = $this->db->insert('tbl_exercise_mode_categories_exercise', $data);
		 $insert_id = $this->db->insert_id();
		return  $insert_id;
	}


	

	function search_category_by_mode($mid=null){

		$query="select c.*,m.mode_name from tbl_exercise_mode_categories c LEFT JOIN tbl_exercise_mode m ON m.id=c.exercise_mode_id where c.is_active='1' AND c.is_delete='0'";
		if($mid!=""){

			$query.=" AND c.exercise_mode_id='$mid'";
		}
		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_type_list(){
		$result = $this->db->query("select t.*,m.mode_name from tbl_exercise_type t LEFT JOIN tbl_exercise_mode m ON m.id=t.exercise_mode_id")->result_array();
		return  $result;
	}

	function get_words_list($mid=null,$cid=null,$scid=null,$sort=null){
	
	$query="select w.*,c.category_name from tbl_word w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1'";
	
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
		if($mid!=""){

			$query.=" AND w.exercise_mode_id='$mid'";
		}
		if($sort!=""){

			if($sort=="1"){

				$query.=" order by w.word_english asc ";

			}
			if($sort=="2"){

				$query.=" order by w.word_english desc ";
			}
			
		}

		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_words_list_pagination($limit,$page,$mid=null,$cid=null,$scid=null,$sort=null){
	
		$query="select w.*,c.category_name from tbl_word w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1'";
	
		
		if($mid!=""){

			$query.=" AND w.exercise_mode_id='$mid'";
		}
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}

		if($sort!=""){

			if($sort=="1"){

				$query.=" order by w.word_english asc ";

			}
			if($sort=="2"){

				$query.=" order by w.word_english desc ";
			}
			
		}else{

			$query .=" order by word_id desc ";
		}


	    $query .=" limit $page,$limit ";


		$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_grammer_list($lid=null,$cid=null,$scid=null,$sort=null){
	
	$query="select g.*,c.category_name from tbl_grammer_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}
		// if($sort!=""){

		// 	if($sort=="1"){

		// 		$query.=" order by w.word_english asc ";

		// 	}
		// 	if($sort=="2"){

		// 		$query.=" order by w.word_english desc ";
		// 	}
			
		// }

		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_grammer_list_pagination($limit,$page,$lid=null,$cid=null,$scid=null){
	
		$query="select g.*,c.category_name from tbl_grammer_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}


	   $query .=" limit $page,$limit "; 


		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	


	function get_phrases_list($cid=null,$scid=null){
	
	$query="select w.*,c.category_name from tbl_phrases w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1'";
	
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
			
		

		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_phrases_list_pagination($limit,$page,$cid=null,$scid=null){
	
		$query="select w.*,c.category_name from tbl_phrases w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1'";
	
		
		
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}

		

		$query .=" order by phrases_id desc ";
	


	   $query .=" limit $page,$limit ";


		$result = $this->db->query($query)->result_array();
		return  $result;
	}



	function get_dialogue_list($lid=null,$cid=null,$scid=null,$sort=null){
	
	$query="select g.*,c.category_name from tbl_dialogue_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}
		
		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_dialogue_list_pagination($limit,$page,$lid=null,$cid=null,$scid=null){
	
		$query="select g.*,c.category_name from tbl_dialogue_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}


	   $query .=" limit $page,$limit "; 


		$result = $this->db->query($query)->result_array();
		return  $result;
	}



	function get_culture_list($lid=null,$cid=null,$scid=null,$sort=null){
	
	$query="select g.*,c.category_name from tbl_culture_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}
		
		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_culture_list_pagination($limit,$page,$lid=null,$cid=null,$scid=null){
	
		$query="select g.*,c.category_name from tbl_culture_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}


	   $query .=" limit $page,$limit "; 


		$result = $this->db->query($query)->result_array();
		return  $result;
	}





	function get_subcategory_list($cid=null,$mid=null){

	$query="select s.*,c.category_name from tbl_exercise_mode_subcategories s LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=s.category_id   where s.is_active='1' AND s.is_delete='0'";
	if($cid!=""){

			$query.=" AND s.category_id='$cid'";
		}

		if($mid!=""){

			$query.=" AND c.exercise_mode_id='$mid'";
		}
		$query.=" order by s.subcategory_name_in_en asc";
		$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_words_from_id($wid){
	
	$query="select w.*,c.category_name from tbl_word w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.word_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_phrases_from_id($wid){
	
	$query="select w.*,c.category_name from tbl_phrases w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.is_delete='0' AND w.phrases_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_grammar_from_id($wid){
	
		$query="select g.*,c.category_name from tbl_grammer_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.grammer_master_id='$wid'";
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_dialogue_from_id($wid){
	
	$query="select g.*,c.category_name from tbl_dialogue_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.dialogue_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_dialogue_list_from_id($wid){
	
	$query="select l.* from tbl_dialogue_list l LEFT JOIN tbl_dialogue_master g ON l.dialogue_master_id=g.dialogue_master_id where l.dialogue_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_culture_from_id($wid){
	
	$query="select g.*,c.category_name from tbl_culture_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.culture_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_culture_list_from_id($wid){
	
	$query="select l.* from tbl_culture_question l LEFT JOIN tbl_culture_master g ON l.culture_master_id=g.culture_master_id where l.culture_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}



	

	function delete_category_type($id,$type){

			//	$this->db->where('category_id', $id);
				//$this->db->where('exercise_type_id', $type);
				$this->db->where(array('category_id' => $id, 'exercise_type_id' => $type));
			$update = $this->db->delete('tbl_exercise_mode_categories_exercise');
			if($update){
			return true;
		}else{
			return false;
		}

	}


	function delete_subcategory($data,$id){

				$this->db->where('exercise_mode_subcategory_id', $id);
			$update = $this->db->update('tbl_exercise_mode_subcategories', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}
	
	function delete_word($data,$id){

				$this->db->where('word_id', $id);
			$update = $this->db->update('tbl_word', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}



	function delete_row_by_condition($table,$data,$condition){

			$this->db->where($condition);
			$update = $this->db->update($table, $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}





	
	function update_type($data,$id){
					$this->db->where('id', $id);
					$update = $this->db->update('tbl_exercise_type', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_word($data,$id){
					$this->db->where('word_id', $id);
					$update = $this->db->update('tbl_word', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_phrase($data,$id){
					$this->db->where('phrases_id', $id);
					$update = $this->db->update('tbl_phrases', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_grammar($data,$id){
					$this->db->where('grammer_master_id', $id);
					$update = $this->db->update('tbl_grammer_master', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_dialogue($data,$id){
					$this->db->where('dialogue_master_id', $id);
					$update = $this->db->update('tbl_dialogue_master', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_culture($data,$id){
					$this->db->where('culture_master_id', $id);
					$update = $this->db->update('tbl_culture_master', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function delete_culture_question($id){

			$this->db->where('culture_master_id', $id);
			$delete = $this->db->delete('tbl_culture_question');
			if($delete){
			return true;
		}else{
			return false;
		}
	}

	function delete_dialogue_list($id){

			$this->db->where('dialogue_master_id', $id);
			$delete = $this->db->delete('tbl_dialogue_list');
			if($delete){
			return true;
		}else{
			return false;
		}
	}

	function delete_grammar($data,$id){

			$this->db->where('grammer_master_id', $id);
			$update = $this->db->update('tbl_grammer_master', $data);
			if($update){
			return true;
			}else{
				return false;
			}

	}

	function delete_dialogue($data,$id){

			$this->db->where('dialogue_master_id', $id);
			$update = $this->db->update('tbl_dialogue_master', $data);
			if($update){
			return true;
			}else{
				return false;
			}

	}

	function delete_culture($data,$id){

			$this->db->where('culture_master_id', $id);
			$update = $this->db->update('tbl_culture_master', $data);
			if($update){
			return true;
			}else{
				return false;
			}

	}










}