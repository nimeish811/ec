<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_master extends CI_Controller {
		public function __construct() {
	        
	        parent::__construct();
			$this->data = $this->session->userdata('logged_in');
	   			
		}
	
		public function index() {
			_dx(__CLASS__ ."/". __function__);	 
		}

	/*
	* 
	*  @@ START Login  Developer: Nimesh Patel 22-march-17 @@
	*/

	function test(){


		$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];

						$data['type_list'] = $this->admin_model->get_type_list();
						//print_r($data['category_list']);
						$data['success_msg']=$this->session->flashdata('sucess_msg');
						// $data['success_msg']=$this->session->flashdata('insert_cat');
						$data['source_lang']=$this->admin_model->get_source_lang();
						$data['active_class']="type";

						$this->load->view('admin/header',$data);
						$this->load->view('admin/test',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
	}

	function login(){

				$this->form_validation->set_rules('email','Email','required');
				$this->form_validation->set_rules('password','Password','required');
				if($this->form_validation->run()==true){


					$email = $this->input->post('email');
					$pass = md5($this->input->post('password'));
					$data = array('email'=>$email,'password'=>$pass);

					$check_login = $this->admin_model->check_login($data);
						if($check_login=="1"){

							$sessiondata = $this->admin_model->master_function_get_data_by_condition('tbl_users',$data);
							$this->session->set_userdata('logged_in',$sessiondata);
							redirect('admin_master/product_list','refresh');

						}else{

								$this->session->set_flashdata('error','Authentication Error');
								redirect('admin_master/login', 'refresh');

						}



				}else{
					
					$data['error'] = $this->session->flashdata('error');
					$this->load->view('admin/login',$data);
				}
	}


	function logout(){

			$this->session->unset_userdata('logged_in');
			$this->load->view('admin/login');

	}

// ----------------------------------------------PRODUCT CRUD START ----------------------------------------------------------------------------------
	public function product_list(){
		
		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
					
					$mid = $this->uri->segment(3);
					$data['mid']=$mid;
					$data['category_list'] = $this->admin_model->master_function_get_data_by_condition('tbl_product',array('is_active'=>1),"id","asc");
					$data['success_msg'] = $this->session->flashdata('sucess_msg');
					$data['error_msg'] =   $this->session->flashdata('error_msg');
					$data['active_class']="product";

					$this->load->view('admin/header',$data);
					$this->load->view('admin/product_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	public function add_product() {


		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			
			$this->form_validation->set_rules('cate_name','Product Name','required');

			if($this->form_validation->run() == FALSE)
	       	{

		        $data['success_msg']=$this->session->flashdata('insert_cat');
		        $data['error_msg']=$this->session->flashdata('error_upload');
		        $data['active_class']="product";
				$this->load->view('admin/header',$data);
				$this->load->view('admin/add_product',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');
				
	     	}else{

				$cate_name = $this->input->post('cate_name');
				$config =  array(
                  'upload_path'     => "./uploads/",
                  'allowed_types'   => "gif|jpg|png|jpeg",
                );

                 $this->load->library('upload', $config);
				 $this->upload->initialize($config);

				$imagename = "";
		 		if(!empty($_FILES['userfile']['name'])){
		 					$this->upload->do_upload('userfile');
							$upload_data = $this->upload->data();
							$imagename = $upload_data['file_name'];
		 		}				
				$data = array(
						"product_name"=>$cate_name,
						"product_image"=>$imagename
				);

				$insert = $this->admin_model->add_product($data);
				//-------- Create Folder For images-----------
				//$name = $this->input->post('cate_name_en');
				if (!file_exists('./uploads/product/'.$insert)) {
						 mkdir('./uploads/product/'.$insert, 0777, true);
				}

				if($insert){
					$this->session->set_flashdata('sucess_msg','Product Inserted Successfully');
					redirect('admin_master/product_list', 'refresh');
				}

			}

		}else{

			redirect('admin_master/login', 'refresh');
		}
	
	}


	public function edit_product($id){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
		

					$this->form_validation->set_rules('cate_name', 'Category Name', 'required');
				
					if($this->form_validation->run() == FALSE)
			       	{
				       	$data['edit_data'] = $this->admin_model->master_function_get_data_by_condition('tbl_product',array('id'=>$id));						$data['active_class']="category";
				$data['active_class']="product";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/edit_product',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');

						
			     	}else{

						$cate_name = $this->input->post('cate_name');
						if(empty($_FILES['userfile']['name'])){

								$data = array(
										"product_name"=>$cate_name,
									);
							
								$insert = $this->admin_model->update_product($data,$id);
							

								if($insert){
									$this->session->set_flashdata('sucess_msg','Product Updated Successfully');
									redirect('admin_master/product_list', 'refresh');
								}

						}else{

							$config =  array(
			                  'upload_path'     => "./uploads/",
			                  'allowed_types'   => "gif|jpg|png|jpeg",                
			                );

			                 $this->load->library('upload', $config);
							 $this->upload->initialize($config);

			                if ( ! $this->upload->do_upload('userfile'))
			                {
		                        $error = array('error' => $this->upload->display_errors());
		                        $this->session->set_flashdata('error_upload', $error['error']);
		                        redirect('admin_master/product_list', 'refresh');
			                }else{

			                	$upload_data = $this->upload->data();
			                	$data = array(
										"product_name"=>$cate_name,
										"product_image"=>$upload_data['file_name']
									);

								$insert = $this->admin_model->update_product($data,$id);

								if($insert){
									
									$this->session->set_flashdata('sucess_msg','Product Updated Successfully');
									redirect('admin_master/product_list', 'refresh');
								}
							}
						
		                }
			
					}
		}else{

			redirect('admin_master/login', 'refresh');
		}

	}



	public function delete_product(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0');
				$delete = $this->admin_model->delete_product($data,$id);

			
				
				if($delete){
					$this->session->set_flashdata('sucess_msg','Product Deleted Successfully');
					redirect('admin_master/product_list', 'refresh');	
				}
	}

// --------------------------------------------- END ---------------------------------------------------

//------------------------------Package START ----------------------------


public function add_package(){

			if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

			$this->form_validation->set_rules('package', 'Package', 'required');
			$this->form_validation->set_rules('product', 'Product', 'required');
			$this->form_validation->set_rules('price', 'price', 'required');
			$this->form_validation->set_rules('desc', 'Description', 'required');
			$this->form_validation->set_rules('model', 'Model No', 'required');
			$this->form_validation->set_rules('batch', 'Batch no', 'required');
			$this->form_validation->set_rules('sku', 'SKU', 'required');
		

			
				if($this->form_validation->run() == FALSE)
		       	{


			        $data['category']=$this->admin_model->get_product_list();
			        $data['success_msg']=$this->session->flashdata('insert_cat');
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="package";
					
						$this->load->view('admin/header',$data);
						$this->load->view('admin/add_package',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
					
		     	}else{
					$category = $this->input->post('product');
					$package = $this->input->post('package');
					$price = $this->input->post('price');
					$desc = $this->input->post('desc');
					$model = $this->input->post('model');
					$batch = $this->input->post('batch');
					$sku = $this->input->post('sku');
					
					$config =  array(
	                  'upload_path'     => "./uploads/",
	                  'allowed_types'   => "gif|jpg|png|jpeg",
	                );

	                 $this->load->library('upload', $config);
					 $this->upload->initialize($config);
					$imagename = "";

				 		if(!empty($_FILES['userfile']['name'])){
				 					$this->upload->do_upload('userfile');

									$upload_data = $this->upload->data();
									$imagename = $upload_data['file_name'];
				 		}
						
						$upload_data = $this->upload->data();
							$data = array(
									"product_id"=>$category,
									"images"=>$imagename,
									"package_name"=>$package,
									"price"=>$price,
									"description"=>$desc,
									"model_no"=>$model,
									"batch"=>$batch,
									"sku"=>$sku
							);

							$insert = $this->admin_model->add_package($data);
							
						
							if($insert){
								$this->session->set_flashdata('sucess_msg','Package Inserted Successfully');
								redirect('admin_master/package_list', 'refresh');
							}


	              //  }
		
				}

			}else{

						redirect('admin_master/login', 'refresh');
			}
		
	}

	public function package_list(){

			if ($this->session->userdata('logged_in')){

				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
						
					
						$data['package_list'] = $this->admin_model->get_package_list();
						$data['success_msg']=$this->session->flashdata('sucess_msg');
						$data['error_msg']=$this->session->flashdata('error_msg');

						$data['category_list']=$this->admin_model->get_product_list();
						$data['active_class']="package";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/package_list',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');

			}else{

				redirect('admin_master/login', 'refresh');

			}
	}

	public function delete_package(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0');
				$delete = $this->admin_model->delete_package($data,$id);

			
				
				if($delete){
					$this->session->set_flashdata('sucess_msg','Package Deleted Successfully');
					redirect('admin_master/package_list', 'refresh');	
				}
	}

	public function edit_package($id){
		
		if($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

			$this->form_validation->set_rules('package', 'Package', 'required');
			$this->form_validation->set_rules('product', 'Product', 'required');
			$this->form_validation->set_rules('price', 'price', 'required');
			$this->form_validation->set_rules('desc', 'Description', 'required');

			if($this->form_validation->run() == FALSE)
	       	{
		       	$data['edit_data'] = $this->admin_model->master_function_get_data_by_condition('tbl_packages',array('id'=>$id));
				$data['category']=$this->admin_model->get_product_list();
				$data['active_class']="subcategory";
				$this->load->view('admin/header',$data);
				$this->load->view('admin/edit_package',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');

				
	     	}else{

				$category = $this->input->post('product');
					$package = $this->input->post('package');
					$price = $this->input->post('price');
					$desc = $this->input->post('desc');
					
						$model = $this->input->post('model');
					$batch = $this->input->post('batch');
					$sku = $this->input->post('sku');

				if(empty($_FILES['userfile']['name'])){

						$data = array(
									"product_id"=>$category,
									"package_name"=>$package,
									"price"=>$price,
									"description"=>$desc,
										"model_no"=>$model,
									"batch"=>$batch,
									"sku"=>$sku
						);

						$insert = $this->admin_model->update_package($data,$id);

						if($insert){
							$this->session->set_flashdata('sucess_msg','Package Updated Successfully');
							redirect('admin_master/package_list', 'refresh');
						}

				}else{


					$config =  array(
	                  'upload_path'     => "./uploads/",
	                  'allowed_types'   => "gif|jpg|png|jpeg",
	                );

	                 $this->load->library('upload', $config);
					 $this->upload->initialize($config);

	                if ( ! $this->upload->do_upload('userfile'))
	                {
	                	//echo "here"; die();
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('error_upload', $error['error']);
                        redirect('admin_master/package_list', 'refresh');
	                }else{
	                	 
	                	 $upload_data = $this->upload->data();
	                	 $data = array(
									"product_id"=>$category,
									"package_name"=>$package,
									"price"=>$price,
									"description"=>$desc,
									"images"=>$upload_data['file_name'],
										"model_no"=>$model,
									"batch"=>$batch,
									"sku"=>$sku
							);
		               
						$insert = $this->admin_model->update_package($data,$id);

						if($insert){
							$this->session->set_flashdata('sucess_msg','Package Updated Successfully');
							redirect('admin_master/package_list', 'refresh');
						}
					}
				
                }
	
			}
		}else{

			redirect('admin_master/login', 'refresh');
		}
	}
	

//-----------------------------Package END ----------------------------- 
	
	
	public function order_list(){

			if ($this->session->userdata('logged_in')){

				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
						
					
						$data['order_list'] = $this->admin_model->get_order_list();
						$data['success_msg']=$this->session->flashdata('sucess_msg');
						$data['error_msg']=$this->session->flashdata('error_msg');

				
						$data['active_class']="order";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/order_list',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');

			}else{

				redirect('admin_master/login', 'refresh');

			}
	}
	
//---- FRONTEND START ------------


    public function home(){

				$data['product_list'] = $this->admin_model->get_product_list();
				$data['package_list'] = $this->admin_model->get_package_list();
				$data['review_list'] = $this->admin_model->get_review_list();
				$this->load->view('user/home',$data);
	}
	
	public function package_details(){
	            
	            $id = $this->uri->segment('3');
	            $data['package_detail'] = $this->admin_model->master_function_get_data_by_condition('tbl_packages',array('id'=>$id));
			//	print_r($data['package_detail']);die();
				$this->load->view('user/package_detail',$data);
	    
	}
	
	public function checkout(){
	    
	  //  print_r($this->input->post()); die();
	    if($this->input->post()){
	        $data['qty']=$this->input->post('qty');
	         $data['id']=$this->input->post('package_id');
	         $this->load->view('user/checkout',$data);
	    }else{
	        
	        redirect('admin_master/home');
	    }
	   
	    
	}
	
	public function order(){
	    
	    	        $qty = $this->input->post('qty');
					$package = $this->input->post('package');
					$name = $this->input->post('name');
					$email = $this->input->post('email');
					$number = $this->input->post('number');
					
						$data = array(
									"package_id"=>$package,
									"qty"=>$qty,
									"name"=>$name,
									"email"=>$email,
									"mobile"=>$number
							);

							$insert = $this->admin_model->add_order($data);
							if($insert){
							    
							    redirect('admin_master/thankyou');
							}
							
	    
	}
	
	public function thankyou(){
	    
	    echo "<h1> Thank You </h1>";
	    
	     echo "<a href='home'> Back </h1>";
	}
	
	
		public function package_review(){
	            
	            $id = $this->uri->segment('3');
	           // $data['package_detail'] = $this->admin_model->master_function_get_data_by_condition('tbl_packages',array('id'=>$id));
			//	print_r($data['package_detail']);die();
				$this->load->view('user/package_review');
	    
	}
	
	public function submit_review(){
            $title = $this->input->post('title');
			$review = $this->input->post('review');
			$package = $this->input->post('package');
			$star = $this->input->post('star');
			
				$data = array(
							"title"=>$title,
							"review"=>$review,
							"package_id"=>$package,
							"star"=>$star
							
					);

					$insert = $this->admin_model->add_review($data);
					if($insert){
					    
					    redirect('admin_master/thankyou');
					}
	}



// ----FRONTEND END

	public function type_list(){


			if ($this->session->userdata('logged_in')){

				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];

						$data['type_list'] = $this->admin_model->get_type_list();
						//print_r($data['category_list']);
						$data['success_msg']=$this->session->flashdata('sucess_msg');
						// $data['success_msg']=$this->session->flashdata('insert_cat');
						$data['source_lang']=$this->admin_model->get_source_lang();
						$data['active_class']="type";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/type_list',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');

			}else{

				  redirect('admin_master/login', 'refresh');

			}
	}

	public function edit_type($id){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

			
		//	$this->form_validation->set_rules('type_name','Type Name','required');
			$source_lang=$this->admin_model->get_source_lang();
			foreach($source_lang as $key){

				$this->form_validation->set_rules('type_name_'.$key['language_code'],'Type Name in '.$key['language_name'],'required');
			}
			if($this->form_validation->run() == FALSE)
	       	{
		       	$data['edit_data'] = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_type',array('id'=>$id));
				  $data['source_lang']=$this->admin_model->get_source_lang();
			$data['active_class']="type";
			$this->load->view('admin/header',$data);
			$this->load->view('admin/edit_type',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');

				
	     	}else{

				//$name = $this->input->post('type_name');
				if(empty($_FILES['userfile']['name'])){
						 $data = array(	
								
							);

						 foreach($source_lang as $langkey){

						$name = $this->input->post('type_name_'.$langkey['language_code']);
						$data["type_".$langkey['language_code']] = $name;
						$data["type_name"] = $name = $this->input->post('type_name_en');

					}
				
						$insert = $this->admin_model->update_type($data,$id);

						if($insert){
							$this->session->set_flashdata('sucess_msg','Type Updated Successfully');
							redirect('admin_master/type_list', 'refresh');
						}

				}else{
						 $path = FCPATH.'uploads/'; 
					//$config =  array(
	                 // 'upload_path'     => $path,
	                 // 'allowed_types'   => "gif|jpg|png|jpeg",  
	               // );
					$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/sfiapp/uploads/'; 
					$config['allowed_types'] = "gif|jpg|png|jpeg";

			        $this->load->library('upload', $config);
					$this->upload->initialize($config);

	                if ( ! $this->upload->do_upload('userfile'))
	                {
	                	
                        $error = array('error' => $this->upload->display_errors());
						print_r($error); die();
                        $this->session->set_flashdata('error_upload', $error['error']);
                        redirect('admin_master/type_list', 'refresh');

	 				 }else{

	                	 $upload_data = $this->upload->data();
	       				 $data = array(	
								//"type_name"=>$name,
								"image"=>$upload_data['file_name']
							);

	       				 foreach($source_lang as $langkey){

						$name = $this->input->post('type_name_'.$langkey['language_code']);
						$data["type_".$langkey['language_code']] = $name;
						$data["type_name"] = $name = $this->input->post('type_name_en');

					}

						$insert = $this->admin_model->update_type($data,$id);
						if($insert){
							$this->session->set_flashdata('sucess_msg','Type Updated Successfully');
							redirect('admin_master/type_list', 'refresh');
						}
					}
				


                }
	
			}
		}else{

				redirect('admin_master/login', 'refresh');
			}

	}

	
	

	function get_mode_category(){
		$modeid = $this->input->post('mode_id');
		$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_id'=>$modeid));
		$result = $this->admin_model->search_category_by_mode($modeid);
		echo json_encode($result);
	
	}


	

	
	public function words_list(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

								// $this->session->unset_userdata('modeid');
 							// 	$this->session->unset_userdata('cateid');
 							// 	$this->session->unset_userdata('subcateid');

					 if($this->input->post()){

 								$this->session->set_userdata('modeid',$this->input->post('mode_id'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
					 }

				// print_r($this->input->post());
					
					 $mode =  $this->session->userdata('modeid');
					 $data['mode']=$mode; 

					
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;

					
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;

					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;

					$per_page = $this->session->userdata('per_page');
					$data['per_page_select']=$per_page;
		 
					if(!isset($per_page)){

						$per_page=100;
					}
					/// for pagination

					$config = array();
			       
			        $res = $this->admin_model->get_words_list($mode,$category,$subcategory,$sort);
			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;
			     

        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/words_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			

		        			//print_r($config);
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";

			        $this->pagination->initialize($config);
			        // var_dump($this->pagination);
			        // die();
			        //$data["results"] = $this->Countries->
			           // fetch_countries($config["per_page"], $page);
			        $data['words_list'] = $this->admin_model->get_words_list_pagination($config["per_page"], $page,$mode,$category,$subcategory,$sort);
			     //print_r($data['words_list']); die();
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination

					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list($mode);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $data['source_lang']=$this->admin_model->get_source_lang();
					$data['active_class']="word";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/words_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	public function add_words(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('audio_name','Audio Name','required');
			
					foreach($source_lang as $key){
						$this->form_validation->set_rules('word_'.$key['language_code'],'Word Name in '.$key['language_name'],'required');
					}
					$this->form_validation->set_rules('mode','Mode','required');

						if($this->form_validation->run() == FALSE)
				       	{


						        $data['category']=$this->admin_model->get_category_list();
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						     
						        $data['success_msg']=$this->session->flashdata('insert_cat');

						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="word";
								$data['source_lang']=$this->admin_model->get_source_lang();
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_words',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     	}else{

								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$mode = $this->input->post('mode');
								// $english = $this->input->post('english');
								// $swedish = $this->input->post('swedish');
								// $russian = $this->input->post('russian');
								// $arabic = $this->input->post('arabic');
								$is_audio = $this->input->post('is_audio');
								$is_image = $this->input->post('is_image');
								$audio_name = $this->input->post('audio_name');
								$image = "";
								if(!isset($is_audio)){
										$is_audio=0;
								}else{
									$is_audio=1;
								}
								if(!isset($is_image)){
										$is_image=0;
								}else{
									$is_image=1;
								}

								$config =  array(
				                  'upload_path'     => "./uploads/words/$category/$subcategoty/",
				                  'allowed_types'   => "gif|jpg|png|jpeg",
				            
				                );

							
				                 $this->load->library('upload', $config);
								 $this->upload->initialize($config);
								 if($this->upload->do_upload('userfile')){
								 	$upload_data = $this->upload->data();
								 	$image= $upload_data['file_name'];
								 }
										
										$data = array(
												"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"exercise_mode_id"=>$mode,
												"image_file"=>$image,
												"audio_file"=>$audio_name,
												"is_image_available"=>$is_audio,
												"is_audio_available"=>$is_image
										);

									foreach($source_lang as $langkey){

										$name = $this->input->post('word_'.$langkey['language_code']);
										$data[$langkey['field_name']] = $name;

									}
										//print_r($data); die();
									$insert = $this->admin_model->add_words($data);
									//	echo "here"; die();
										if($insert){
											$this->session->set_flashdata('sucess_msg','Word Inserted Successfully');
											redirect('admin_master/words_list', 'refresh');
										}

						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}

	public function delete_word(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0');
				$delete = $this->admin_model->delete_word($data,$id);
				if($delete){
					$this->session->set_flashdata('sucess_msg','Word Deleted Successfully');
					redirect('admin_master/words_list', 'refresh');	
				}
	}


	public function edit_words($id){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					foreach($source_lang as $key){
						$this->form_validation->set_rules('word_'.$key['language_code'],'Word Name in '.$key['language_name'],'required');
					}
					$this->form_validation->set_rules('mode','Mode','required');

						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_words_from_id($id);
							$data['category']=$this->admin_model->get_category_list();
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						$data['source_lang']=$this->admin_model->get_source_lang();
							$data['active_class']="word";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_words',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$mode = $this->input->post('mode');
								$arabic = $this->input->post('arabic');
								$is_audio = $this->input->post('is_audio');
								$is_image = $this->input->post('is_image');
								
								if(!isset($is_audio)){
										$is_audio=0;
								}else{
									$is_audio=1;
								}
								if(!isset($is_image)){
										$is_image=0;
								}else{
									$is_image=1;
								}

									if(empty($_FILES['userfile']['name'])){

										 $data = array(
													"category_id"=>$category,
													"subcategory_id"=>$subcategoty,
													"exercise_mode_id"=>$mode,
													"is_image_available"=>$is_image,
													"is_audio_available"=>$is_audio
												);
										 	
									foreach($source_lang as $langkey){

											$name = $this->input->post('word_'.$langkey['language_code']);
											$data[$langkey['field_name']] = $name;
									}
									$insert = $this->admin_model->update_word($data,$id);

											if($insert){
												$this->session->set_flashdata('sucess_msg','Word Updated Successfully');
												redirect('admin_master/words_list', 'refresh');
											}

									}else{
											$config =  array(
							                  'upload_path'     => "./uploads/words/".$category.'/'.$subcategoty.'/',
							                  'allowed_types'   => "gif|jpg|png|jpeg", 
							                );

							             $this->load->library('upload', $config);
										 $this->upload->initialize($config);
										if (!$this->upload->do_upload('userfile'))
						                {
						                	//echo "here"; die();
					                        $error = array('error' => $this->upload->display_errors());
					                        $this->session->set_flashdata('error_upload', $error['error']);
					                        redirect('admin_master/words_list', 'refresh');
						                }else{

						                		  $upload_data = $this->upload->data();
						                	 $data = array(
						                	 		"category_id"=>$category,
													"subcategory_id"=>$subcategoty,
													"exercise_mode_id"=>$mode,
													"image_file"=>$upload_data['file_name'],
													"is_image_available"=>$is_image,
													"is_audio_available"=>$is_audio
												);
									        foreach($source_lang as $langkey){

												$name = $this->input->post('word_'.$langkey['language_code']);
												$data[$langkey['field_name']] = $name;
											}

											$insert = $this->admin_model->update_word($data,$id);
											if($insert){
												$this->session->set_flashdata('sucess_msg','Word Updated Successfully');
												redirect('admin_master/words_list', 'refresh');
											}

						                }



									}	
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}
	
	public function category_import(){

		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		//echo "<br> ".$path.$_FILES["file"]["name"];
		//	echo "<br>";
		// var_dump($varname);

		 $this->load->library('excel');//load PHPExcel library 
		
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        // $this->load->library('upload', $configUpload);
         //$this->upload->do_upload('file');	
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		//var_dump(extension_loaded ('zip'));
		//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);      

		//echo $objWorksheet->getCellByColumnAndRow(0,1);die();
	        if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){

	        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
	            redirect('admin_master/category_list', 'refresh');
	        }	          
          //loop from first data untill last data
	        for($i=3;$i<=$totalrows;$i++)
	        {


	     
					$data = array(
									"exercise_mode_id"=>$this->input->post('exercise_mode'),
									// "category_name_in_en"=>$objWorksheet->getCellByColumnAndRow(2,$i),
									// "category_name_in_sv"=>$objWorksheet->getCellByColumnAndRow(3,$i),
									// "category_name_in_ru"=>$objWorksheet->getCellByColumnAndRow(4,$i),
									// "category_name_in_ar"=>$objWorksheet->getCellByColumnAndRow(5,$i),
									"image"=>$objWorksheet->getCellByColumnAndRow(0,$i)
							);

					$source_lang=$this->admin_model->get_source_lang();
					$ctn=0;
					foreach($source_lang as $langkey){

							if($ctn==0){
								$j=1;

							}else{

								$j=$j+1;
							}
							$name=$objWorksheet->getCellByColumnAndRow($j,$i);
							$data["category_name_in_".$langkey['language_code']] = $name;
							$ctn++;
					}
							
					$res = $this->admin_model->master_function_get_data_by_condition("tbl_exercise_mode_categories",array("category_name_in_en"=>$objWorksheet->getCellByColumnAndRow(2,$i),"is_active"=>"1","is_delete"=>"0"));
					//echo count($res); die();
					if(count($res) >= "1"){

						$update = $this->admin_model->master_function_for_update_by_conditions("tbl_exercise_mode_categories",array("category_name_in_en"=>$objWorksheet->getCellByColumnAndRow(2,$i)),$data);


					}else{

							$insert = $this->admin_model->add_category($data);
							if (!file_exists('./uploads/words/'.$insert)) {
			   							 mkdir('./uploads/words/'.$insert, 0777, true);
							}

							if (!file_exists('./uploads/audio/'.$insert)) {
			   							 mkdir('./uploads/audio/'.$insert, 0777, true);
							}

							// $types = $objWorksheet->getCellByColumnAndRow(1,$i);
							// if($types==""){
							// 	$types="1,2,3,4,5,6,7,8";
							// }
							// $types = explode(',', $types);
							// foreach($types as $key){

							//  		$data = array(
							// 			"exercise_type_id"=>$key,
							// 			"category_id"=>$insert,
							// 		);
									
							//  		$insert_exercise = $this->admin_model->add_category_exercise($data);
							// }

					}
							

					
	              
							  
	        }
            unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
            $this->session->set_flashdata('sucess_msg','Category imported Successfully');	 
            redirect('admin_master/category_list', 'refresh');
	           
       
    }


    public function subcategory_import(){


				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

				$this->load->library('excel');//load PHPExcel library 
				
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';
		        // $this->load->library('upload', $configUpload);
		         //$this->upload->do_upload('file');	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0); 

			          if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){

				        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
				            redirect('admin_master/subcategory_list', 'refresh');
	        		}      

		          //loop from first data untill last data
				        for($i=3;$i<=$totalrows;$i++)
				        {
				        		$data = array(
				        				"category_id"=>$this->input->post('category'),
				      //   				"subcategory_name_in_en"=>$objWorksheet->getCellByColumnAndRow(2,$i),
										// "subcategory_name_in_sv"=>$objWorksheet->getCellByColumnAndRow(3,$i),
										// "subcategory_name_in_ru"=>$objWorksheet->getCellByColumnAndRow(4,$i),
										// "subcategory_name_in_ar"=>$objWorksheet->getCellByColumnAndRow(5,$i),
										"image"=>$objWorksheet->getCellByColumnAndRow(0,$i),
				        		
				        				"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i));
								

							$source_lang=$this->admin_model->get_source_lang();
							$ctn=0;
							foreach($source_lang as $langkey){

									if($ctn==0){
										$j=3;

									}else{

										$j=$j+1;
									}
									$name=$objWorksheet->getCellByColumnAndRow($j,$i);
									$data["subcategory_name_in_".$langkey['language_code']] = $name;
									$ctn++;
							}

					        $res = $this->admin_model->master_function_get_data_by_condition("tbl_exercise_mode_subcategories",array("subcategory_name_in_en"=>$objWorksheet->getCellByColumnAndRow(3,$i),"is_active"=>"1","is_delete"=>"0","category_id"=>$this->input->post('category')));
							//echo count($res); die();
							if(count($res) >= "1"){

								$update = $this->admin_model->master_function_for_update_by_conditions("tbl_exercise_mode_subcategories",array("subcategory_name_in_en"=>$objWorksheet->getCellByColumnAndRow(3,$i)),$data);

							}else{

								
									$insert = $this->admin_model->add_subcategory($data);


									//------ create subcategory folder in category folder ------ 
								//$re = $this->admin_model->master_function_get_data_by_condition("tbl_exercise_mode_categories",array("exercise_mode_category_id"=>$this->input->post('category')));
								$catid = $this->input->post('category');
								
								if (!file_exists('./uploads/words/'.$catid.'/'.$insert)) {
		   							 mkdir('./uploads/words/'.$catid.'/'.$insert, 0777, true);
								}

								if (!file_exists('./uploads/audio/'.$catid.'/'.$insert)) {
		   							 mkdir('./uploads/audio/'.$catid.'/'.$insert, 0777, true);
								}

								$types = $objWorksheet->getCellByColumnAndRow(1,$i);
							
								if($types=="" || $types==null || $types==" "){

										//echo "here";
										//echo $this->input->post('mode');
									if($this->input->post('mode')=="4"){

												$types="10,11";

									}else if($this->input->post('mode')=="1"){

										$types="1,2,3,4,5,6,7,8,9";

									}
									else if($this->input->post('mode')=="2"){

										$types="13,14";

									}else if($this->input->post('mode')=="3"){

										$types="12";

									}else if($this->input->post('mode')=="5"){

										$types="15";

									}

									$types = explode(',', $types);
									foreach($types as $key){

							 		$data = array(
										"exercise_type_id"=>$key,
										"category_id"=>$insert,
									);
									
							 		$insert_exercise = $this->admin_model->add_category_exercise($data);
							}
									
								}else{


									if($this->input->post('mode')=="4"){
												
												$types="10,11";
												
									}else if($this->input->post('mode')=="2"){

										$types="13,14";

									}else if($this->input->post('mode')=="3"){

										$types="12";

									}else if($this->input->post('mode')=="5"){

										$types="15";

									}

									$types = explode(',', $types);
							foreach($types as $key){

							 		$data = array(
										"exercise_type_id"=>$key,
										"category_id"=>$insert,
									);
									
							 		$insert_exercise = $this->admin_model->add_category_exercise($data);
							}

								}

								//echo $type;
								//die();
							

							}
								  
				        }
			        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
			        $this->session->set_flashdata('sucess_msg','SubCategory imported Successfully');	 
			        redirect('admin_master/subcategory_list', 'refresh');
	           
    }


    public function words_import(){


				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

				$this->load->library('excel');//load PHPExcel library 
				
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';
		        // $this->load->library('upload', $configUpload);
		         //$this->upload->do_upload('file');	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  


		         if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){

			        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			            redirect('admin_master/subcategory_list', 'refresh');
        		}      
                      
		          //loop from first data untill last data
		        for($i=3;$i<=$totalrows;$i++)
		        {
		        		//$data = array("category_id"=>$this->input->post('category'),"subcategory_name"=>$objWorksheet->getCellByColumnAndRow(0,$i),"image"=>$objWorksheet->getCellByColumnAndRow(1,$i),"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i));
				$category = $this->input->post('category');
				$subcategoty = $this->input->post('subcategory');
				$mode = $this->input->post('mode');
				$data = array(
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty,
								"exercise_mode_id"=>$mode,
								"image_file"=>$objWorksheet->getCellByColumnAndRow(0,$i),
								"audio_file"=>$objWorksheet->getCellByColumnAndRow(1,$i),
								"is_image_available"=>$objWorksheet->getCellByColumnAndRow(3,$i),
								"is_audio_available"=>$objWorksheet->getCellByColumnAndRow(2,$i)
						);	
				//print_r($data); die();
				
						$source_lang=$this->admin_model->get_source_lang();
							$ctn=0;
							foreach($source_lang as $langkey){

									if($ctn==0){
										$j=4;

									}else{

										$j=$j+1;
									}
									$name=$objWorksheet->getCellByColumnAndRow($j,$i);
									$data[$langkey['field_name']] = $name;
									$ctn++;
							}

						$res = $this->admin_model->master_function_get_data_by_condition("tbl_word",array("word_english"=>$objWorksheet->getCellByColumnAndRow(4,$i),"is_active"=>"1"));
						//echo count($res); die();
						if(count($res) >= "1"){

							$update = $this->admin_model->master_function_for_update_by_conditions("tbl_word",array("word_english"=>$objWorksheet->getCellByColumnAndRow(4,$i)),$data);

						}else{

							$insert = $this->admin_model->add_words($data);
						}
						  
		        }
			        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
			        $this->session->set_flashdata('sucess_msg','Words imported Successfully');	 
			        redirect('admin_master/words_list', 'refresh');
	           
       
    }



	public function download_category_sample() {
			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/category_sample.xlsx");
			$name = 'category_sample.xlsx';
			force_download($name, $data);
	}
	public function download_subcategory_sample() {

			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/subcategory_sample.xlsx");
			$name = 'subcategory_sample.xlsx';
			force_download($name, $data);
	}
	public function download_words_sample() {

			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/word_sample.xlsx");
			$name = 'words_sample.xlsx';
			force_download($name, $data);
	}

	public function download_phrase_sample() {

			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/phrase_mode.xlsx");
			$name = 'phrase_mode.xlsx';
			force_download($name, $data);
	}

	public function download_dialogue_sample() {

			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/dialogue_mode_new.xlsx");
			$name = 'dialogue_mode_new.xlsx';
			force_download($name, $data);
	}

	public function download_culture_sample(){

			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/culture_mode_paragraph.xlsx");
			$name = 'culture_mode_paragraph.xlsx';
			force_download($name, $data);
	}



	public function get_cat_from_mode(){
		$mid = $this->input->post('mode_id');
			$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_id'=>$mid,'is_active'=>'1','is_delete'=>'0'),"category_name_in_en","asc");
			//print_r($result);
			echo "<option value=''>Select Category</option>";
			foreach ($result as $key) {

				echo "<option value=".$key['exercise_mode_category_id'].">".$key['category_name_in_en']."</option>";
			}
	}

	public function get_type_from_mode(){
		$mid = $this->input->post('mode_id');
			$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_type',array('exercise_mode_id'=>$mid),"mode_name","asc");
			//print_r($result);
			
			foreach ($result as $key) {

				echo "<option value=".$key['id'].">".$key['type_en']."</option>";
			}
	}
	
	public function get_subcat_from_cate(){
		$cid = $this->input->post('cate_id');
			$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('category_id'=>$cid,'is_active'=>'1','is_delete'=>'0'),"subcategory_name_in_en","asc");
		//	print_r($result);
			echo "<option value=''>Select SubCategory</option>";
			foreach ($result as $key) {

				echo "<option value=".$key['exercise_mode_subcategory_id']." >  ".$key['subcategory_name_in_en']."</option>";
			}
	}

	public function get_subcat_from_cate_image_upload(){
		$cid = $this->input->post('cate_id');
			$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('category_id'=>$cid,'is_active'=>'1','is_delete'=>'0'));
			//print_r($result);
			echo "<option value=''>Select SubCategory</option>";
			foreach ($result as $key) {

				echo "<option value=".$key['subcategory_name_in_en'].">".$key['subcategory_name_in_en']."</option>";
			}
	}

	public function upload_word_images(){

			if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

			$this->form_validation->set_rules('category', 'Category', 'required');
			$this->form_validation->set_rules('subcategory', 'SubCategory', 'required');
			
			

			if($this->input->post()){

				$this->session->set_userdata('selected_mode',$this->input->post('mode'));
				$this->session->set_userdata('selected_cateid',$this->input->post('category'));
				$this->session->set_userdata('selected_subcateid',$this->input->post('subcategory'));
			
			}

			 $mode =  $this->session->userdata('selected_mode');
			 $data['mode']=$mode; 
			 $cateid =  $this->session->userdata('selected_cateid');
			 $data['cateid']=$cateid; 
			 $subcateid =  $this->session->userdata('selected_subcateid');
			 $data['subcateid']=$subcateid; 



				if($this->form_validation->run() == FALSE)
		       	{


			        $data['category']=$this->admin_model->get_category_list($mode);
			        $data['subcategory']=$this->admin_model->get_subcategory_list($cateid);
			        $data['success_msg']=$this->session->flashdata('sucess_msg');
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="image";
					$data['exercise_mode']=$this->admin_model->get_exercise_mode();
					$this->load->view('admin/header',$data);
					$this->load->view('admin/upload_words_images',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
					
		     	}else{

		     		//print_r($_FILES['userfile']['name']); die();
					$category = $this->input->post('category');
					$subcate = $this->input->post('subcategory');
					
					$count = count($_FILES['userfile']['size']);
			        foreach($_FILES as $key=>$value)
			        for($s=0; $s<=$count-1; $s++) {
			        $_FILES['userfile']['name']=$value['name'][$s];
			        $_FILES['userfile']['type']    = $value['type'][$s];
			        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
			        $_FILES['userfile']['error']       = $value['error'][$s];
			        $_FILES['userfile']['size']    = $value['size'][$s];  
			            $config['upload_path'] = "./uploads/words/$category/$subcate";
			            $config['allowed_types'] = 'gif|jpg|png|jpeg';
			                    $config['overwrite'] = TRUE;

			                  $new_name = $value['name'][$s];
			                   $new_name =  str_replace(" ","_",$new_name);
								$config['file_name'] = $new_name;

			           //	$config['max_size']    = '100';
			            //$config['max_width']  = '1024';
			            //$config['max_height']  = '768';
							
			        $this->load->library('upload', $config);
			         $this->upload->initialize($config);
			        $this->upload->do_upload();
			        $data = $this->upload->data();
			       // print_r($data);
			     
			            }
			         //   die();
			           //  print_r($config); 

			         //  $error = array('error' => $this->upload->display_errors());
			         //  $this->session->set_flashdata('error_upload', $error['error']);

			                        //	if($error) echo $error['error']; die();
	                
	            
					$this->session->set_flashdata('sucess_msg','Images uploaded Successfully');
					redirect('admin_master/upload_word_images', 'refresh');
				
		
				}

			}else{

					redirect('admin_master/login', 'refresh');
			}
		
	}

	public function upload_word_audio(){

			if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

			$this->form_validation->set_rules('category', 'Category', 'required');
			$this->form_validation->set_rules('subcategory', 'SubCategory', 'required');
			
				if($this->input->post()){

	 								$this->session->set_userdata('selected_mode',$this->input->post('mode'));
	 								$this->session->set_userdata('selected_cateid',$this->input->post('category'));
	 								$this->session->set_userdata('selected_subcateid',$this->input->post('subcategory'));
	 							
				}		 
				 $mode =  $this->session->userdata('selected_mode');
				 $data['mode']=$mode; 
				 $cateid =  $this->session->userdata('selected_cateid');
				 $data['cateid']=$cateid; 
				 $subcateid =  $this->session->userdata('selected_subcateid');
				 $data['subcateid']=$subcateid; 


			
				if($this->form_validation->run() == FALSE)
		       	{


			        $data['category']=$this->admin_model->get_category_list($mode);
			         $data['subcategory']=$this->admin_model->get_subcategory_list($cateid);


			 
			        $data['success_msg']=$this->session->flashdata('sucess_msg');

			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="audio";
					$data['exercise_mode']=$this->admin_model->get_exercise_mode();
					$this->load->view('admin/header',$data);
					$this->load->view('admin/upload_words_audio',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
					
		     	}else{

		     		//print_r($_FILES['userfile']['name']); die();
					$category = $this->input->post('category'); 
					$subcate = $this->input->post('subcategory');
					
					$count = count($_FILES['userfile']['size']);
			        foreach($_FILES as $key=>$value)
			        for($s=0; $s<=$count-1; $s++) {
			        $_FILES['userfile']['name']=$value['name'][$s];
			        $_FILES['userfile']['type']    = $value['type'][$s];
			        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
			        $_FILES['userfile']['error']       = $value['error'][$s];
			        $_FILES['userfile']['size']    = $value['size'][$s];  
			          $config['upload_path'] = "./uploads/audio/$category/$subcate";
			            $config['allowed_types'] = '*';
			                    $config['overwrite'] = TRUE;

			                  $new_name = $value['name'][$s];
			                   $new_name =  str_replace(" ","_",$new_name);
								$config['file_name'] = $new_name;

			           //	$config['max_size']    = '100';
			            //$config['max_width']  = '1024';
			            //$config['max_height']  = '768';
							
			        $this->load->library('upload', $config);
			         $this->upload->initialize($config);
			        $this->upload->do_upload();
			        $data = $this->upload->data();
			       // print_r($data);
			     
			            }
			         //   die();
			           //  print_r($config); 

			         //  $error = array('error' => $this->upload->display_errors());
			         //  $this->session->set_flashdata('error_upload', $error['error']);

			                        //	if($error) echo $error['error']; die();
	                
	            
					$this->session->set_flashdata('sucess_msg','Audio uploaded Successfully');
					redirect('admin_master/upload_word_audio', 'refresh');
				
		
				}

			}else{

					redirect('admin_master/login', 'refresh');
			}
		
	}

	public function upload_word_audio_old() {

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$data['success_msg']=$this->session->flashdata('sucess_msg');

	        $data['error_msg']=$this->session->flashdata('error_upload');
	        $data['active_class']="audio";
				// echo phpinfo();
				 //die();
	         $data['category']=$this->admin_model->get_category_list();
			 $data['subcategory']=$this->admin_model->get_subcategory_list();

				$this->load->view('admin/header',$data);
				$this->load->view('admin/upload_words_audio',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');

		}else{
				redirect('admin_master/login', 'refresh');

		}	

	}
	     	
	public function upload_audio(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];


				$count = count($_FILES['userfile']['size']);
		        foreach($_FILES as $key=>$value)
		        for($s=0; $s<=$count-1; $s++) {
				       
				        $_FILES['userfile']['name']=$value['name'][$s];
				        $_FILES['userfile']['type']    = $value['type'][$s];
				        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
				        $_FILES['userfile']['error']       = $value['error'][$s];
				        $_FILES['userfile']['size']    = $value['size'][$s];  
			            $config['upload_path'] = "./uploads/audio/";
			            $config['allowed_types'] = '*';
	                    $config['overwrite'] = TRUE;

                  		$new_name = $value['name'][$s];
	                    $new_name =  str_replace(" ","_",$new_name);
						$config['file_name'] = $new_name;

				           //	$config['max_size']    = '100';
				            //$config['max_width']  = '1024';
				            //$config['max_height']  = '768';
								
				        $this->load->library('upload', $config);


				        $this->upload->initialize($config);
				        $this->upload->do_upload();
				        $data = $this->upload->data();
				       // print_r($data);
				     
		        }
		        
		         // $error = array('error' => $this->upload->display_errors());
		         //  $this->session->set_flashdata('error_upload', $error['error']);

		         //                	if($error) echo $error['error']; die();
                
            
				$this->session->set_flashdata('sucess_msg','Audio uploaded Successfully');
				redirect('admin_master/upload_word_audio', 'refresh');
			
	
		

			}else{

						redirect('admin_master/login', 'refresh');
				}
		
	}

	function delete_all_category(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

							$this->session->set_flashdata('error_msg','Please selecte at least one');
							redirect('admin_master/category_list', 'refresh');	

		}else{


				foreach ($ids as $key){
				//	echo $key; 
				//	die();
					$data = array('is_active'=>'0','is_delete'=>'1');
					$delete = $this->admin_model->delete_category($data,$key);


					$data1 = array('is_active'=>'0');
					$delete = $this->admin_model->delete_row_by_condition('tbl_word',$data1,array('category_id'=>$key));

					//$data = array('is_active'=>'0');
					$delete = $this->admin_model->delete_row_by_condition('tbl_grammer_master',$data,array('category_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_culture_master',$data,array('category_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('category_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_dialogue_master',$data,array('category_id'=>$key));
						
				}
				if($delete){

							$this->session->set_flashdata('sucess_msg','Category Deleted Successfully');
							redirect('admin_master/category_list', 'refresh');	
				}


		}
		
			
	}


	function delete_all_subcategory(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

			$this->session->set_flashdata('error_msg','Please selecte at least one');
			redirect('admin_master/subcategory_list', 'refresh');	

		}else{

			foreach ($ids as $key){

				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_subcategory($data,$key);


					$data1 = array('is_active'=>'0');

					$delete = $this->admin_model->delete_row_by_condition('tbl_word',$data1,array('subcategory_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_grammer_master',$data,array('subcategory_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_culture_master',$data,array('subcategory_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('subcategory_id'=>$key));
					$delete = $this->admin_model->delete_row_by_condition('tbl_dialogue_master',$data,array('subcategory_id'=>$key));


			}

			if($delete){
				
				$this->session->set_flashdata('sucess_msg','SubCategory Deleted Successfully');
				redirect('admin_master/subcategory_list', 'refresh');	
			}


		}
		
			
	}


	function delete_all_words(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

							$this->session->set_flashdata('error_msg','Please selecte at least one');
							redirect('admin_master/words_list', 'refresh');	

		}else{


						foreach ($ids as $key) {
							
								$data = array('is_active'=>'0');
								$delete = $this->admin_model->delete_word($data,$key);
								
						
				}

				if($delete){
					$this->session->set_flashdata('sucess_msg','Words Deleted Successfully');
					redirect('admin_master/words_list', 'refresh');	
				}


		}
		
			
	}

// Add languages 
	public function add_dyamic_lang(){

			if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

			$this->form_validation->set_rules('name', 'Name', 'required|is_unique[tbl_source_language.language_name]');
			//$this->form_validation->set_rules('subcate_name','SubCategory Name','required');
			$this->form_validation->set_rules('code','Code','required|is_unique[tbl_source_language.language_code]');

			
				if($this->form_validation->run() == FALSE)
		       	{

			        $data['success_msg']=$this->session->flashdata('insert_cat');
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="subcategory";
					
						$this->load->view('admin/header',$data);
						$this->load->view('admin/add_dyamic_lang',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
					
		     	}else{
					
					$name = strtolower($this->input->post('name'));
					$code = $this->input->post('code');

							$data = array(
									"language_name"=>$name,
									"language_code"=>$code,
									"field_name"=>'word_'.$name
							);

							$insert = $this->admin_model->add_lang($data);

							if($insert){
								$this->session->set_flashdata('sucess_msg','Language Inserted Successfully');
								redirect('admin_master/add_dyamic_lang', 'refresh');
							}

				}

			}else{

						redirect('admin_master/login', 'refresh');
			}
		
	}


	// Dialouge List Start 

public function dialogue_list(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

					 if($this->input->post()){

 								$this->session->set_userdata('lang',$this->input->post('lang'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
					 }

					// print_r($this->input->post());
					
					 $lang =  $this->session->userdata('lang');
					 $data['lang']=$lang; 

					
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;

					
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;

					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;
		 

					/// for pagination
					 $per_page = $this->session->userdata('per_page');
					 $data['per_page_select']=$per_page;
		 

					/// for pagination

					if(!isset($per_page)){

						$per_page=100;
					}

					$config = array();
			       
			        $res = $this->admin_model->get_dialogue_list($lang,$category,$subcategory);
			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;
			     

        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/grammar_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";

			        $this->pagination->initialize($config);
			
			        $data['grammer_list'] = $this->admin_model->get_dialogue_list_pagination($config["per_page"], $page,$lang,$category,$subcategory);
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination
                    $mode = "2";
					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list($mode);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $data['source_lang']=$this->admin_model->get_source_lang();
					$data['active_class']="dialogue";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/dialogue_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	// Dialouge LIST END

 	// Add Dialogue START

	public function add_dialogue(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					//$this->form_validation->set_rules('question','Question','required');
		
					$this->form_validation->set_rules('lang','Language','required');

						if($this->form_validation->run() == FALSE)
				       	{


						        $data['category']=$this->admin_model->get_category_list('2');
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="dialogue";
								$data['source_lang']=$this->admin_model->get_source_lang();
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_dialogue',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     	}else{

								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$title = $this->input->post('title');
							  	$full_audio = $this->input->post('full_audio');

							  	$phrase = $this->input->post('phrase[]');
							  	$type = $this->input->post('type[]');
							  	$audio = $this->input->post('audio[]');

							// echo   $audio[0]; die();
								$data = array(
												
											"title"=>$title,
											"full_audio"=>$full_audio,
											"target_language_id"=>$lang,
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty	
										);
									//print_r($data); die();
									$insert = $this->admin_model->add_dialogue_master($data);
									//	echo "here"; die();
									if($insert){

										for($i=0;$i < count($phrase);$i++){

											$data1 = array(

													"dialogue_master_id"=>$insert,
													"phrase"=>$phrase[$i],
													"audio_name"=>$audio[$i],
													"speaker"=>$type[$i],
													"sequence_no"=>$i+1,
												);	

												$insert_list = $this->admin_model->add_dialogue_list($data1);
											}
											
											//print_r($data1); die();
											$this->session->set_flashdata('sucess_msg','Dialogue Inserted Successfully');
											redirect('admin_master/dialogue_list', 'refresh');
									}

						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}
	 // Add Dialogue END

	// EDIT DIALOGUE START

	public function edit_dialogue($id){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
			
						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_dialogue_from_id($id);
						    $data['edit_data_list'] = $this->admin_model->get_dialogue_list_from_id($id);

						//   print_r($data['edit_data_list']); die();
							$data['category']=$this->admin_model->get_category_list('2');
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						$data['source_lang']=$this->admin_model->get_source_lang();
							$data['active_class']="dialogue";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_dialogue',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$title = $this->input->post('title');
							  	$full_audio = $this->input->post('full_audio');
							  	$phrase = $this->input->post('phrase[]');
							  	$type = $this->input->post('type[]');
							  	$audio = $this->input->post('audio[]');
									

									//print_r($this->input->post()); die();
							$data = array(
												
											"title"=>$title,
											"full_audio"=>$full_audio,
											"target_language_id"=>$lang,
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty	
										);
									//print_r($data); die();
									//$insert = $this->admin_model->add_dialogue_master($data);
							$insert = $this->admin_model->update_dialogue($data,$id);

									if($insert){


											//echo "here"; die();
												$delete_list = $this->admin_model->delete_dialogue_list($id);


												for($i=0;$i < count($phrase);$i++){

												$data1 = array(

														"dialogue_master_id"=>$id,
														"phrase"=>$phrase[$i],
														"audio_name"=>$audio[$i],
														"speaker"=>$type[$i],
														"sequence_no"=>$i+1,
													);	

												//print_r($data1); die();

													$insert_list = $this->admin_model->add_dialogue_list($data1);
												}
												$this->session->set_flashdata('sucess_msg','Dialouge Updated Successfully');
												redirect('admin_master/dialogue_list', 'refresh');
									}

										
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}


	// EDIT DIALOGUE END



	public function delete_dialogue(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_dialogue($data,$id);
				$delete_list = $this->admin_model->delete_dialogue_list($id);

				if($delete){
					$this->session->set_flashdata('sucess_msg','Dialouge Deleted Successfully');
					redirect('admin_master/dialogue_list', 'refresh');	
				}
	}


	function delete_all_dialogue(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

							$this->session->set_flashdata('error_msg','Please selecte at least one');
							redirect('admin_master/dialogue_list', 'refresh');	

		}else{


						foreach ($ids as $key) {
							
								$data = array('is_active'=>'0','is_delete'=>'1');
								$delete = $this->admin_model->delete_dialogue($data,$key);
								$delete_list = $this->admin_model->delete_dialogue_list($key);
						}

				if($delete){
					$this->session->set_flashdata('sucess_msg','Dialouge Deleted Successfully');
					redirect('admin_master/dialogue_list', 'refresh');	
				}


		}
		
			
	}

	// Dialouge IMPORT START

		 public function dialogue_import(){


				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

				$this->load->library('excel');//load PHPExcel library 
				
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';
		        // $this->load->library('upload', $configUpload);
		         //$this->upload->do_upload('file');	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  


		         if(($objWorksheet->getCellByColumnAndRow(0,1))!="Title" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){

			        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			            redirect('admin_master/dialogue_list', 'refresh');
        		}      
                      
		          //loop from first data untill last data
        		$audio_list_array = array();
        		$insert= false;

		        for($i=3;$i<=$totalrows;$i++)
		        {
		        	$au = $objWorksheet->getCellByColumnAndRow(0,$i);
		        		//$data = array("category_id"=>$this->input->post('category'),"subcategory_name"=>$objWorksheet->getCellByColumnAndRow(0,$i),"image"=>$objWorksheet->getCellByColumnAndRow(1,$i),"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i));
					$category = $this->input->post('category');
					$subcategoty = $this->input->post('subcategory');
					$lang = $this->input->post('lang');
					//$audio_list_array[]=$objWorksheet->getCellByColumnAndRow(0,$i);
					array_push($audio_list_array, $au);
					
					if($objWorksheet->getCellByColumnAndRow(0,$i)!=" " && $objWorksheet->getCellByColumnAndRow(0,$i)!=""){

						$data = array(
								
									"title"=>$objWorksheet->getCellByColumnAndRow(0,$i),
									"full_audio"=>$objWorksheet->getCellByColumnAndRow(1,$i),
									"target_language_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty		
							);	
				
						$res = $this->admin_model->master_function_get_data_by_condition("tbl_dialogue_master",array("title"=>$objWorksheet->getCellByColumnAndRow(0,$i),"is_active"=>"1"));
						//echo count($res); die();
						
						if(count($res)== "0"){

							$insert = $this->admin_model->add_dialogue_master($data);
						
						}else{

							$update = $this->admin_model->master_function_for_update_by_conditions("tbl_dialogue_master",array("title"=>$objWorksheet->getCellByColumnAndRow(0,$i)),$data);
						}


					}else{


							//UPDATE CODE HERE
					}

						if(count($res)== "0"){
							
							$data = array(
											"dialogue_master_id"=>$insert,
											"phrase"=>$objWorksheet->getCellByColumnAndRow(2,$i),
											"audio_name"=>$objWorksheet->getCellByColumnAndRow(3,$i),
											"speaker"=>$objWorksheet->getCellByColumnAndRow(4,$i),
											"sequence_no"=>$objWorksheet->getCellByColumnAndRow(5,$i),
									);	

								$insert_list = $this->admin_model->add_dialogue_list($data);
						
						}else{

								//UPDATE CODE HERE
						}	
				}
					//print_r($audio_list_array); die();

			        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
			        $this->session->set_flashdata('sucess_msg','dialogue imported Successfully');	 
			        redirect('admin_master/dialogue_list', 'refresh');
	           
       
    }

	// Dialouge IMPORT END
	


// 	// *** GRAMMER List END 

public function grammar_list(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

					 if($this->input->post()){

 								$this->session->set_userdata('lang',$this->input->post('lang'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
					 }

					//print_r($this->input->post());
					
					 $lang =  $this->session->userdata('lang');
					 $data['lang']=$lang; 

					
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;

					
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;

					// $sort = $this->session->userdata('sort');
					// $data['sort_select']=$sort;

					 $per_page = $this->session->userdata('per_page');
					 $data['per_page_select']=$per_page;
		 

					/// for pagination

					if(!isset($per_page)){

						$per_page=100;
					}

					$config = array();
			       
			        $res = $this->admin_model->get_grammer_list($lang,$category,$subcategory);
			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;
			     

        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/grammar_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";

			        $this->pagination->initialize($config);
			
			        $data['grammer_list'] = $this->admin_model->get_grammer_list_pagination($config["per_page"], $page,$lang,$category,$subcategory);
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination
                    $mode = "4";
					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list($mode);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $data['source_lang']=$this->admin_model->get_source_lang();
					$data['active_class']="grammar";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/grammer_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	//*** GRAMMER LIST END


// ******************************************************** GRAMMER ADD START*******************************************************

	public function add_grammar(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('question','Question','required');
		
					$this->form_validation->set_rules('lang','Language','required');

						if($this->form_validation->run() == FALSE)
				       	{


						        $data['category']=$this->admin_model->get_category_list('4');
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="grammar";
								$data['source_lang']=$this->admin_model->get_source_lang();
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_grammer',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     	}else{

								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$type = $this->input->post('type');
								$question = $this->input->post('question');
							  	$option = $this->input->post('option[]');
							  	$option_ans = $this->input->post('correct_ans');
									
							  	 if($type=="1"){

							   		$option_ans = implode("#", $option);
							  	 }
							   
									$data = array(
												
												"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"target_language_id"=>$lang,
												"question_type"=>$type,
												"question"=>$question,
												"options"=>$option_ans,
									
											);
									//print_r($data); die();
									$insert = $this->admin_model->add_grammer($data);
									//	echo "here"; die();
									if($insert){
											
											$this->session->set_flashdata('sucess_msg','Question Inserted Successfully');
											redirect('admin_master/grammar_list', 'refresh');
									}

						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}


// ******************************************************** GRAMMER ADD END  **********************************************************

// ******************************************************** GRAMMER EDIT START*********************************************************

			public function edit_grammar($id){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('lang','lang','required');
					$this->form_validation->set_rules('question','Question','required');

						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_grammar_from_id($id);

						    //print_r($data['edit_data']); die();
							$data['category']=$this->admin_model->get_category_list('4');
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						$data['source_lang']=$this->admin_model->get_source_lang();
							$data['active_class']="grammar";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_grammar',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$type = $this->input->post('type');
								$question = $this->input->post('question');
							  	$option = $this->input->post('option[]');
							  	$option_ans = $this->input->post('correct_ans');
									
							   if($type=="1"){

							   		$option_ans = implode("#", $option);
							   }
							   
									$data = array(
												
												"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"target_language_id"=>$lang,
												"question_type"=>$type,
												"question"=>$question,
												"options"=>$option_ans,
									
											);
										 	
							
								$insert = $this->admin_model->update_grammar($data,$id);

									if($insert){
												
												$this->session->set_flashdata('sucess_msg','Question Updated Successfully');
												redirect('admin_master/grammar_list', 'refresh');
									}

										
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}

// ******************************************************** GRAMMER EDIT EDN***************************************************************
	   public function delete_grammar(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_grammar($data,$id);
				if($delete){
					$this->session->set_flashdata('sucess_msg','Question Deleted Successfully');
					redirect('admin_master/grammar_list', 'refresh');	
				}
	}


	function delete_all_grammar(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

							$this->session->set_flashdata('error_msg','Please selecte at least one');
							redirect('admin_master/grammar_list', 'refresh');	

		}else{


						foreach ($ids as $key) {
							
								$data = array('is_active'=>'0','is_delete'=>'1');
								$delete = $this->admin_model->delete_grammar($data,$key);
								
						
				}

				if($delete){
					$this->session->set_flashdata('sucess_msg','Question Deleted Successfully');
					redirect('admin_master/grammar_list', 'refresh');	
				}


		}
		
			
	}


	public function download_grammar_sample() {

			$this->load->helper('download');
			$data = file_get_contents("./assets/sample_xls_file/grammer_mode.xlsx");
			$name = 'grammer_mode.xlsx';
			force_download($name, $data);
	}

//**  GRAMMER IMPORT START **

	public function grammar_import(){


				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

				$this->load->library('excel');//load PHPExcel library 
				
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';
		        // $this->load->library('upload', $configUpload);
		         //$this->upload->do_upload('file');	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  

		        if(($objWorksheet->getCellByColumnAndRow(1,1))!="Question"){

			        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			            redirect('admin_master/grammar_list', 'refresh');
        		}      
                      
		          //loop from first data untill last data
		        for($i=3;$i<=$totalrows;$i++)
		        {
		        		//$data = array("category_id"=>$this->input->post('category'),"subcategory_name"=>$objWorksheet->getCellByColumnAndRow(0,$i),"image"=>$objWorksheet->getCellByColumnAndRow(1,$i),"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i));
				$category = $this->input->post('category');
				$subcategoty = $this->input->post('subcategory');
				$lang = $this->input->post('lang');
				
				$data = array(
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty,
								"target_language_id"=>$lang,
								"question_type"=>$objWorksheet->getCellByColumnAndRow(0,$i),
								"question"=>$objWorksheet->getCellByColumnAndRow(1,$i),
								"options"=>$objWorksheet->getCellByColumnAndRow(2,$i)
						);	
				//print_r($data); die();

						// $res = $this->admin_model->master_function_get_data_by_condition("tbl_word",array("word_english"=>$objWorksheet->getCellByColumnAndRow(3,$i),"is_active"=>"1"));
						// //echo count($res); die();
						// if(count($res) >= "1"){

						// 	$update = $this->admin_model->master_function_for_update_by_conditions("tbl_word",array("word_english"=>$objWorksheet->getCellByColumnAndRow(3,$i)),$data);

						// }else{

							$insert = $this->admin_model->add_grammer($data);
					//	}
						  
		        }
			        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
			        $this->session->set_flashdata('sucess_msg','Questions imported Successfully');	 
			        redirect('admin_master/grammar_list', 'refresh');
	           
       
    }

	// //**  GRAMMER IMPORT END **

/* ************************************************************* IMPORT PHRASES **********************************************
@@ START
*/
	public function phrases_import(){


				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

				$this->load->library('excel');//load PHPExcel library 
				
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';
		        // $this->load->library('upload', $configUpload);
		         //$this->upload->do_upload('file');	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  


		         if(($objWorksheet->getCellByColumnAndRow(1,1))!="English" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){

			        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			            redirect('admin_master/subcategory_list', 'refresh');
        		}      
                      
		          //loop from first data untill last data
		        for($i=3;$i<=$totalrows;$i++)
		        {
		        		//$data = array("category_id"=>$this->input->post('category'),"subcategory_name"=>$objWorksheet->getCellByColumnAndRow(0,$i),"image"=>$objWorksheet->getCellByColumnAndRow(1,$i),"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i));
				$category = $this->input->post('category');
				$subcategoty = $this->input->post('subcategory');
				$mode = $this->input->post('mode');
				$data = array(
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty,
								"audio_file"=>$objWorksheet->getCellByColumnAndRow(0,$i),
						);	
				//print_r($data); die();
				
						$source_lang=$this->admin_model->get_source_lang();
							$ctn=0;
							foreach($source_lang as $langkey){

									if($ctn==0){
										$j=1;

									}else{

										$j=$j+1;
									}
									$name=$objWorksheet->getCellByColumnAndRow($j,$i);
									$data['phrase_'.$langkey['language_code']] = $name;
									$ctn++;
							}

							//print_r($data); die();

						 $res = $this->admin_model->master_function_get_data_by_condition("tbl_phrases",array("phrase_en"=>$objWorksheet->getCellByColumnAndRow(1,$i),"is_active"=>"1","is_delete"=>"0"));
						// //echo count($res); die();
						if(count($res) >= "1"){

							$update = $this->admin_model->master_function_for_update_by_conditions("tbl_phrases",array("phrase_en"=>$objWorksheet->getCellByColumnAndRow(1,$i)),$data);

					 }else{

							$insert = $this->admin_model->add_phrases($data);
					}
						  
		        }
			        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
			        $this->session->set_flashdata('sucess_msg','Words imported Successfully');	 
			        redirect('admin_master/phrases_list', 'refresh');
	           
       
    }

/* ************************************************************* IMPORT PHRASES **********************************************
@@ END
*/

	public function phrases_list(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

								// $this->session->unset_userdata('modeid');
 							// 	$this->session->unset_userdata('cateid');
 							// 	$this->session->unset_userdata('subcateid');

					 if($this->input->post()){

 								//$this->session->set_userdata('modeid',$this->input->post('mode_id'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
					 }

					// print_r($this->input->post());
					
					 //$mode =  $this->session->userdata('modeid');
					// $data['mode']=3; 

					
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;

					
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;

					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;
		 

					/// for pagination
					$per_page = $this->session->userdata('per_page');
					$data['per_page_select']=$per_page;
		 

					/// for pagination
					if(!isset($per_page)){

						$per_page=100;
					}

					$config = array();
			       
			        $res = $this->admin_model->get_phrases_list($category,$subcategory);
			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;
			     

        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/phrases_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";

			        $this->pagination->initialize($config);
			        // var_dump($this->pagination);
			        // die();
			        //$data["results"] = $this->Countries->
			           // fetch_countries($config["per_page"], $page);
			        $data['words_list'] = $this->admin_model->get_phrases_list_pagination($config["per_page"], $page,$category,$subcategory);
			     //print_r($data['words_list']); die();
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination

					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list(3);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $data['source_lang']=$this->admin_model->get_source_lang();
					$data['active_class']="phrase";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/phrases_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}
	public function add_phrases(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');

					foreach($source_lang as $key){
						$this->form_validation->set_rules('phrase_'.$key['language_code'],'Phrase in '.$key['language_name'],'required');
					}

						if($this->form_validation->run() == FALSE)
				       	{

						        $data['category']=$this->admin_model->get_category_list(3);
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        
						        $data['success_msg']=$this->session->flashdata('insert_cat');

						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="phrase";
								$data['source_lang']=$this->admin_model->get_source_lang();
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_phrases',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     	}else{

								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$audio_name = $this->input->post('audio_name');
								
										$data = array(
												"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"audio_file"=>$audio_name,
											
										);

									foreach($source_lang as $langkey){

										$name = $this->input->post('phrase_'.$langkey['language_code']);
										$data['phrase_'.$langkey['language_code']] = $name;

									}
										//print_r($data); die();
									$insert = $this->admin_model->add_phrases($data);
									//	echo "here"; die();
										if($insert){
											$this->session->set_flashdata('sucess_msg','Phrases Inserted Successfully');
											redirect('admin_master/phrases_list', 'refresh');
										}

						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}


	public function edit_phrases($id){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					foreach($source_lang as $key){
						$this->form_validation->set_rules('phrase_'.$key['language_code'],'Phrase in '.$key['language_name'],'required');
					}

						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_phrases_from_id($id);
							$data['category']=$this->admin_model->get_category_list(3);
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					       
	  						$data['source_lang']=$this->admin_model->get_source_lang();
							$data['active_class']="phrase";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_phrases',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								

										 $data = array(
													"category_id"=>$category,
													"subcategory_id"=>$subcategoty,
												);
										 	
									foreach($source_lang as $langkey){

											$name = $this->input->post('phrase_'.$langkey['language_code']);
											$data['phrase_'.$langkey['language_code']] = $name;
									}
									$insert = $this->admin_model->update_phrase($data,$id);

											if($insert){
												$this->session->set_flashdata('sucess_msg','Phrase Updated Successfully');
												redirect('admin_master/phrases_list', 'refresh');
											}

										
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}


	public function delete_phrases(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				
				$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('phrases_id'=>$id));

				
				if($delete){
					$this->session->set_flashdata('sucess_msg','Phrase Deleted Successfully');
					redirect('admin_master/phrases_list', 'refresh');	
				}
	}


	function delete_all_phrases(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

							$this->session->set_flashdata('error_msg','Please selecte at least one');
							redirect('admin_master/phrases_list', 'refresh');	

		}else{

					foreach ($ids as $key) {
							
								$data = array('is_active'=>'0','is_delete'=>'1');
								$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('phrases_id'=>$key));

					}

				if($delete){
					$this->session->set_flashdata('sucess_msg','Phrases Deleted Successfully');
					redirect('admin_master/phrases_list', 'refresh');	
				}


		}
		
			
	}




	// Dialouge IMPORT START

		 public function culture_import(){


				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

				$this->load->library('excel');//load PHPExcel library 
				
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';
		        // $this->load->library('upload', $configUpload);
		         //$this->upload->do_upload('file');	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  


		         if(($objWorksheet->getCellByColumnAndRow(1,1))!="Title" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){

			        	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			            redirect('admin_master/culture_list', 'refresh');
        		}      
                      
		          //loop from first data untill last data
        		$audio_list_array = array();
        		$insert= false;

		        for($i=3;$i<=$totalrows;$i++)
		        {
		        	
		        	$category = $this->input->post('category');
					$subcategoty = $this->input->post('subcategory');
					$lang = $this->input->post('lang');
					
					if($objWorksheet->getCellByColumnAndRow(0,$i)!=" " && $objWorksheet->getCellByColumnAndRow(0,$i)!=""){

						$data = array(
								
									"title_text"=>$objWorksheet->getCellByColumnAndRow(1,$i),
									"external_link"=>$objWorksheet->getCellByColumnAndRow(2,$i),
									"paragraph"=>$objWorksheet->getCellByColumnAndRow(3,$i),
									"image_name"=>$objWorksheet->getCellByColumnAndRow(4,$i),
									"target_language_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty		
							);	
				
						$res = $this->admin_model->master_function_get_data_by_condition("tbl_culture_master",array("title_text"=>$objWorksheet->getCellByColumnAndRow(1,$i),"is_active"=>"1"));
						//echo count($res); die();
						
						if(count($res)== "0"){

							$insert = $this->admin_model->add_culture_master($data);
						
						}else{

							$update = $this->admin_model->master_function_for_update_by_conditions("tbl_culture_master",array("title_text"=>$objWorksheet->getCellByColumnAndRow(1,$i)),$data);
						}


					}else{


							//UPDATE CODE HERE
					}

						if(count($res)== "0"){
							
							$data = array(
											"culture_master_id"=>$insert,
											"question"=>$objWorksheet->getCellByColumnAndRow(5,$i),
											"options"=>$objWorksheet->getCellByColumnAndRow(6,$i),
											
										);	

							$insert_list = $this->admin_model->add_culture_que($data);
						
						}else{

								//UPDATE CODE HERE
						}	
				}
					//print_r($audio_list_array); die();

			        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
			        $this->session->set_flashdata('sucess_msg','Culture imported Successfully');	 
			        redirect('admin_master/culture_list', 'refresh');
	           
       
    }

	// Dialouge IMPORT END


	/*  CULTURE MODE LIST */



	public function culture_list(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

					 if($this->input->post()){

 								$this->session->set_userdata('lang',$this->input->post('lang'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
					 }

					// print_r($this->input->post());
					
					 $lang =  $this->session->userdata('lang');
					 $data['lang']=$lang; 

					
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;

					
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;

					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;
		 

					/// for pagination

					$config = array();
			       
			        $res = $this->admin_model->get_culture_list($lang,$category,$subcategory);
			        $config["total_rows"] = count($res);
			        $config["per_page"] = 10;
			     

        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/grammar_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";

			        $this->pagination->initialize($config);
			
			        $data['grammer_list'] = $this->admin_model->get_culture_list_pagination($config["per_page"], $page,$lang,$category,$subcategory);
			    //   print_r($data['grammer_list']); die();
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination
                    $mode = "5";
					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list($mode);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $data['source_lang']=$this->admin_model->get_source_lang();
					$data['active_class']="culture";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/culture_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}



	public function add_culture(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('title','Title','required');
					$this->form_validation->set_rules('para','paragraph','required');
					$this->form_validation->set_rules('link','Link','required');
					$this->form_validation->set_rules('title','Title','required');
					$this->form_validation->set_rules('image_name','Image Name','required');

					$this->form_validation->set_rules('question[]','Question','required');
		
					$this->form_validation->set_rules('lang','Language','required');

						if($this->form_validation->run() == FALSE)
				       	{


						        $data['category']=$this->admin_model->get_category_list('5');
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="culture";
								$data['source_lang']=$this->admin_model->get_source_lang();
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_culture',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     	}else{


				     			//print_r( $this->input->post()); die();
								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$title = $this->input->post('title');
							  	$image_name = $this->input->post('image_name');
							  	$para = $this->input->post('para');
							  	$link = $this->input->post('link');

							  	$question = $this->input->post('question[]');
							  	$option1 = $this->input->post('option1[]');
							  	$option2 = $this->input->post('option2[]');
							  	$option3 = $this->input->post('option3[]');
							  	$option4 = $this->input->post('option4[]');
							  	

							// echo   $audio[0]; die();
								$data = array(
												
									"title_text"=>$title,
									"external_link"=>$link,
									"paragraph"=>$para,
									"image_name"=>$image_name,
									"target_language_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty	
										);
									//print_r($data); die();
									$insert = $this->admin_model->add_culture_master($data);
									//	echo "here"; die();
									if($insert){

										for($i=0;$i < count($question);$i++){

											$data1 = array(

												"culture_master_id"=>$insert,
												"question"=>$question[$i],
												"options"=>$option1[$i].'#'.$option2[$i].'#'.$option3[$i].'#'.$option4[$i],
												
												);	

												$insert_list = $this->admin_model->add_culture_que($data1);
											}
											
											//print_r($data1); die();
											$this->session->set_flashdata('sucess_msg','Culture Inserted Successfully');
											redirect('admin_master/culture_list', 'refresh');
									}

						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}



	public function edit_culture($id){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
			
						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_culture_from_id($id);
						    $data['edit_data_list'] = $this->admin_model->get_culture_list_from_id($id);

						//   print_r($data['edit_data_list']); die();
							$data['category']=$this->admin_model->get_category_list('5');
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						$data['source_lang']=$this->admin_model->get_source_lang();
							$data['active_class']="culture";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_culture',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$title = $this->input->post('title');
							  	$image_name = $this->input->post('image_name');
							  	$para = $this->input->post('para');
							  	$link = $this->input->post('link');

							  	$question = $this->input->post('question[]');
							  	$option1 = $this->input->post('option1[]');
							  	$option2 = $this->input->post('option2[]');
							  	$option3 = $this->input->post('option3[]');
							  	$option4 = $this->input->post('option4[]');
									

									//print_r($this->input->post()); die();
							  $data = array(
												
											"title_text"=>$title,
											"external_link"=>$link,
											"paragraph"=>$para,
											"image_name"=>$image_name,
											"target_language_id"=>$lang,
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty	
										);
									//print_r($data); die();
									//$insert = $this->admin_model->add_dialogue_master($data);
								$insert = $this->admin_model->update_culture($data,$id);

									if($insert){


											//echo "here"; die();
												$delete_list = $this->admin_model->delete_culture_question($id);


												for($i=0;$i < count($question);$i++){

												$data1 = array(

												"culture_master_id"=>$id,
												"question"=>$question[$i],
												"options"=>$option1[$i].'#'.$option2[$i].'#'.$option3[$i].'#'.$option4[$i],
													);	

												//print_r($data1); die();

													$insert_list = $this->admin_model->add_culture_que($data1);
												}
												$this->session->set_flashdata('sucess_msg','Culture Updated Successfully');
												redirect('admin_master/culture_list', 'refresh');
									}

										
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}


public function delete_culture(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_culture($data,$id);
				$delete_list = $this->admin_model->delete_culture_question($id);

				if($delete){
					$this->session->set_flashdata('sucess_msg','culture Deleted Successfully');
					redirect('admin_master/culture_list', 'refresh');	
				}
	}

	function delete_all_culture(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){

							$this->session->set_flashdata('error_msg','Please selecte at least one');
							redirect('admin_master/culture_list', 'refresh');	

		}else{


						foreach ($ids as $key) {
							
								$data = array('is_active'=>'0','is_delete'=>'1');
								$delete = $this->admin_model->delete_culture($data,$key);
								$delete_list = $this->admin_model->delete_culture_question($key);
						}

				if($delete){
					$this->session->set_flashdata('sucess_msg','culture Deleted Successfully');
					redirect('admin_master/culture_list', 'refresh');	
				}


		}
					
	}




	public function edit_profile(){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					//print_r($sessiondata); die();
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$data['userpass']=$sessiondata[0]['password'];

					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
			
						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_dialogue_from_id($id);
						    $data['edit_data_list'] = $this->admin_model->get_dialogue_list_from_id($id);

						//   print_r($data['edit_data_list']); die();
							$data['category']=$this->admin_model->get_category_list('2');
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						$data['source_lang']=$this->admin_model->get_source_lang();
							$data['active_class']="dialogue";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_profile',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$title = $this->input->post('title');
							  	$full_audio = $this->input->post('full_audio');
							  	$phrase = $this->input->post('phrase[]');
							  	$type = $this->input->post('type[]');
							  	$audio = $this->input->post('audio[]');
									

									//print_r($this->input->post()); die();
							$data = array(
												
											"title"=>$title,
											"full_audio"=>$full_audio,
											"target_language_id"=>$lang,
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty	
										);
									//print_r($data); die();
									//$insert = $this->admin_model->add_dialogue_master($data);
							$insert = $this->admin_model->update_dialogue($data,$id);

									if($insert){


											//echo "here"; die();
												$delete_list = $this->admin_model->delete_dialogue_list($id);


												for($i=0;$i < count($phrase);$i++){

												$data1 = array(

														"dialogue_master_id"=>$id,
														"phrase"=>$phrase[$i],
														"audio_name"=>$audio[$i],
														"speaker"=>$type[$i],
														"sequence_no"=>$i+1,
													);	

												//print_r($data1); die();

													$insert_list = $this->admin_model->add_dialogue_list($data1);
												}
												$this->session->set_flashdata('sucess_msg','Dialouge Updated Successfully');
												redirect('admin_master/dialogue_list', 'refresh');
									}

										
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}



/*[:END:]*/	


// 	function get_filter_words(){

// 		  $mid =$this->input->post('mode_id');
// 		  $category =$this->input->post('cat_id');
// 		  $subcategory =$this->input->post('subcate_id');


// 		$words_list = $this->admin_model->get_words_list($mid,$category,$subcategory);
// 	/// for pagination

// 					$config = array();
			       
// 			        $res = $this->admin_model->get_words_list();
// 			        $config["total_rows"] = count($res);
// 			        $config["per_page"] = 10;
			     

// 		        			$config["uri_segment"] = 3;
// 		        			$config["base_url"] = base_url() . "admin_master/words_list";
// 		        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			
// 		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
// 					$config['full_tag_close'] ="</ul>";
// 					$config['num_tag_open'] = '<li>';
// 					$config['num_tag_close'] = '</li>';
// 					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
// 					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
// 					$config['next_tag_open'] = "<li>";
// 					$config['next_tagl_close'] = "</li>";
// 					$config['prev_tag_open'] = "<li>";
// 					$config['prev_tagl_close'] = "</li>";
// 					$config['first_tag_open'] = "<li>";
// 					$config['first_tagl_close'] = "</li>";
// 					$config['last_tag_open'] = "<li>";
// 					$config['last_tagl_close'] = "</li>";

// 			         $this->pagination->initialize($config);
// 			        //$data["results"] = $this->Countries->
// 			           // fetch_countries($config["per_page"], $page);
// 			        $words_list = $this->admin_model->get_words_list_pagination($config["per_page"], $page,$category,$subcategory);
// 			     //   print_r($data['words_list']); die();
// 			        $data["links"] = $this->pagination->create_links();				
// $table = '';
// echo $table1 = '<table id="example" class="table order-column hover"><thead><tr><th><label class="checkbox-inline checkbox-styled"><input type="checkbox" class="dt-body-center" name="delete[]" id="ckbCheckAll"/><span></span></label></th><th>Id</th><th>Image</th><th> Audio </th><th>English</th><th>Swedish</th><th>Russian</th><th>Arabic</th><th>Action</th></tr></thead><tbody>';

//     $ctn=1;
// 	foreach ($words_list as $key) {  

// 	$file = $_SERVER['DOCUMENT_ROOT'] .'/SFI/uploads/words/'.$key['category_name'].'/'.$key['image_file'];
	
// 	if(!file_exists($file) && $key["is_image_available"]){ $cls =  "img-responsive missing";} else{  $cls =  "img-responsive" ;} 
   
//     $file1 = base_url().'/uploads/audio/'.$key['word_swedish'].'_sw.m4a';
	 
// 	$fileaudio = $_SERVER['DOCUMENT_ROOT'] .'/SFI/uploads/audio/'.$key['word_swedish'].'_sw.m4a';
// 	if(file_exists($fileaudio) && $key['is_audio_available']) {

// 		  $tdaud = '<td><a href="#" class="" onClick="window.open('.$file1.',"pagename","resizable,height=260,width=370"); return false;"><i class="fa fa-volume-up"> </i></a></td>';
	
// 	}else if($key["is_audio_available"]=="0"){
	   
// 	     $tdaud =  '<td><a href="#" class=""></a><i class="fa  fa-volume-off"></i></td>';
	
// 	}else{
	      
// 	       $tdaud =  '<td><a href="#" class=""></a><i class="fa red fa-volume-up"></i></td>';
// 	}

//  $onerr = "this.onerror=null;this.src='".base_url()."assets/thumb_image_not_available.png'";

// $table .= '<tr> ';
// $table .='<td><label class="checkbox-inline checkbox-styled"><input type="checkbox" name="delete[]" class="checkBoxClass" value="'.$key['word_id'].'"/><span></span></label></td>';
// $table .='<td>'.$ctn.'</td>'.
// $table .='<td><img src="'.base_url().'uploads/words/'.$key['category_name'].'/'.$key['image_file'].'"  width="60px"  class="'.$cls.'" onerror="'.$onerr.'"/></td>';
// $table .= "'.$tdaud.'";
// $table .= '<td>'.$key['word_english'].'</td><td>'.$key['word_swedish'].'</td><td>'.$key['word_russian'].'</td><td>'.$key['word_arabic'].'</td><td><a href="'.base_url().'admin_master/edit_words/'.$key['word_id'].'"><i class="fa fa-pencil action"></i> </a>  <a onclick="return confirm("Are you sure?");" href="'.base_url().'admin_master/delete_word/'.$key['word_id'].'"> <i class="fa fa-trash-o action"></i> </a></td></tr>';
// $ctn++; }
// //echo '<tr><td><label class="checkbox-inline checkbox-styled"><input type="checkbox" name="delete[]" class="checkBoxClass" value="'.$key['word_id'].'"/><span></span></label></td><td>'.$ctn.'</td>'.$file = $_SERVER['DOCUMENT_ROOT'] .'/SFI/uploads/words/'.$key['category_name'].'/'.$key['image_file']'<td><img src='.base_url()'uploads/words/'.$key['category_name'].'/'.$key['image_file'].'"  width="60px" onerror="this.onerror=null;this.src='.base_url().'assets/thumb_image_not_available.png'"; class='".if(!file_exists($file) && $key['is_image_available']){"img-responsive missing"} else{'img-responsive'}'/></td>'.$file1 = base_url().'/uploads/audio/'.$key['word_swedish'].'_sw.m4a'; $fileaudio = $_SERVER['DOCUMENT_ROOT'] .'/SFI/uploads/audio/'.$key['word_swedish'].'_sw.m4a'; if(file_exists($fileaudio) && $key['is_audio_available']) {'<td><a href="#" class="" onClick="window.open('.$file1.',"pagename","resizable,height=260,width=370"); return false;"><i class="fa fa-volume-up"> </i></a></td>"'.}else if($key['is_audio_available']=="0"){.'<td><a href="#" class=""></a><i class="fa  fa-volume-off"></i></td>'.}else{.'<td><a href="#" class=""></a><i class="fa red fa-volume-up"></i></td>'.}.'<td>'.$key['word_english'].'</td><td>'.$key['word_swedish'].'</td><td>'.$key['word_russian'].'</td><td>'.$key['word_arabic'].'</td><td><a href="'.base_url().'admin_master/edit_words/'.$key['word_id'].'"><i class="fa fa-pencil action"></i> </a>  <a onclick="return confirm('Are you sure?');" href="'.base_url().'admin_master/delete_word/'.$key['word_id'].'"> <i class="fa fa-trash-o action"></i> </a></td></tr>'.$ctn++.' }'; 
// $table .= '</tbody></table> <span style="float:right;"> '.$data["links"].' </span> ';
// 	 echo $table;
// 	}


}
