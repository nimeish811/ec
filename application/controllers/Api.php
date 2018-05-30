<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	private $method_type=''; 
	private $getTimestamp;
	public function __construct() {
         parent::__construct();

	}
	
	public function index(){
		_dx(__CLASS__ ."/". __function__);	 
	}

	public function get_source_language(){

		 $lang = $this->input->get_post('lang');
			$data = array('lang'=>$lang);

		 $this->api_model->get_source_lang($data);	
		
	}	

	public function get_target_language() {
		
		 $this->api_model->get_target_lang();
	}	

	public function get_exercise_mode() {

		 $lang = $this->input->get_post('lang');
		 $data = array('lang'=>$lang);
		 $this->api_model->get_exercise_mode($data);	
	}

	public function get_category_list() {
		
		$lang = $this->input->get_post('lang');
		$exercise_mode_id = $this->input->get_post('exercise_mode_id');
		$data = array('lang'=>$lang,'exercise_mode_id'=>$exercise_mode_id);
		$this->api_model->get_category_list($data);	
		
	}
	public function get_subcategory_list() {
		
		$lang = $this->input->get_post('lang');
		$category_id = $this->input->get_post('category_id');
		$data = array('lang'=>$lang,'category_id'=>$category_id);
		$this->api_model->get_subcategory_list($data);	
		
	}
	public function get_exercise_type(){
		
		$lang = $this->input->get_post('lang');
		$category_id = $this->input->get_post('subcategory_id');
		$data = array('lang'=>$lang,'subcategory_id'=>$category_id);
		$this->api_model->get_exercise_type_list($data);	
		
	}

	function vocabulary_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$type = $this->input->get_post('type');

			$data = array(
						
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id
					);

			if($type=="1"){
				$this->api_model->get_sorce_lan_word_type_1($data);	

			}else if($type=="2"){
				$this->api_model->get_sorce_lan_word_type_2($data);	

			}else if($type=="3"){
				$this->api_model->get_sorce_lan_word_type_3($data);	
				
			}else if($type=="4"){
				$this->api_model->get_sorce_lan_word_type_4($data);	
				
			}else if($type=="5"){
				$this->api_model->get_sorce_lan_word_type_5($data);	
				
			}else if($type=="6"){
					$this->api_model->get_sorce_lan_word_type_6($data);	
				
			}else if($type=="7"){
				$this->api_model->get_sorce_lan_word_type_7($data);	
				
			}else if($type=="8"){
				$this->api_model->get_sorce_lan_word_type_8($data);	
				
			}else if($type=="9"){
				$this->api_model->get_sorce_lan_word_type_9($data);	
			}else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
			}

	}

	function grammar_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$type = $this->input->get_post('type');

			$data = array(
						
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id
					);
				
				if($type=="10"){

					$this->api_model->get_grammer_type_1($data);

				}else if($type=="11"){

					 $this->api_model->get_grammer_type_2($data);
				}else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
				}
				
	}

	function phrases_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$type = $this->input->get_post('type');

			$data = array(
						
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id
					);
				
			if($type=="12"){

				$this->api_model->get_phrases_type_1($data);

			}else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
				}
				
	}

	function dialogues_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$type = $this->input->get_post('type');

			$data = array(
						
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id
					);
				
				if($type=="13"){

					$this->api_model->get_dialogue_type_1($data);

				}else if($type=="14"){

					$this->api_model->get_dialogue_type_1($data);
				}else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
				}
				
	}

	function culture_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$type = $this->input->get_post('type');

			$data = array(
				
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id
					);
				
				if($type=="15"){

					$this->api_model->get_culture_type_1($data);

				}else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
				}
				
	}






	
/*[:END:]*/	
}
