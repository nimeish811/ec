	
	<!-- BEGIN BASE -->
	<div id="base">
		<!-- BEGIN CONTENT-->
		<div id="content">
			<!-- BEGIN SECTION -->
			<section>
				<div class="section-header">
					<ol class="breadcrumb">
						<li><a href="index.html">Home</a></li>
						<li class="active">Add Grammer</li>
						<a href="<?php echo base_url(); ?>admin_master/grammar_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
					</ol>
				</div><!--end .section-header -->
				<div class="section-body">
					<!-- BEGIN HORIZONTAL FORM - SIZES -->
					<div class="card">

						<div class="card-body">
							<?php //print_r($exercise_mode); 
							$attributes = array('action' => base_url().'admin_master/add_grammar' );
							echo form_open_multipart('admin_master/add_grammar', $attributes);
							?>
							<?php 
							if(isset($success_msg)){ ?>
							<div class="success-message"><?php echo $success_msg; ?></div>
							<?php } ?>
							<?php 
							if(isset($error_msg)){ ?>
							<div class="error-message"><?php echo $error_msg; ?></div>
							<?php } ?>
								
							<form>
								<div class="form-group">
									<div class="row">
										<div class="col-md-5 col-sm-6">
											<div class="col-md-12 mrgt10">
												<label>Language</label>
												<select id="lang" name="lang" class="form-control">
												<option value="">Select Language</option>
												<?php foreach($source_lang as $key){ ?>
												<option <?php if(isset($lang)) if($key['source_language_id']==$lang){ echo "selected";} ?> value="<?php echo $key['source_language_id'];?>"><?php echo ucfirst($key['language_name']);?> </option>
												<?php } ?>
											</select>
												<?php echo form_error('lang', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
												<label>Category</label>
												<select name="category" id="category" class="form-control">
													<option value="">Category</option>
													<?php foreach($category as $key){ ?>
													<option  <?php echo set_select('category', $key['exercise_mode_category_id']); ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo ucfirst($key['category_name_in_en']);?> </option>
													<?php } ?>
												</select>
												<?php echo form_error('category', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
												<label>Sub Category</label>
												<select id="subcate" name="subcategory" class="form-control">
													<option value="">SubCategory</option>
													
												</select>
												<?php echo form_error('subcategory', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
												<label>Question Type</label>
												<select id="type" name="type" class="form-control">
												<option value="">Select Question Type</option>
												<option value="1">Multi Choice</option>
												<option value="2">Fill the gap</option>
												
											</select>
												<?php echo form_error('type', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
													<label>Questions</label>
													<input type="text" name="question" class="form-control" placeholder="Question">
													<?php echo form_error('question', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
												<div class="col-md-12 mrgt10 correct_ans hide">
													<label>Correct Answer</label>
													<input type="text" name="correct_ans" class="form-control" placeholder="Correct Answer">
													<?php echo form_error('correct_ans', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>


												<div class="col-md-12 mrgt10 options hide">
													<label>Option 1(Correct Answer)</label>
													<input type="text" name="option[]" class="form-control" placeholder="Correct Answer">
													<?php echo form_error('option[]', '<div class="errormsg">', '</div>'); ?>
												</div>
								

												<div class="col-md-12 mrgt10 options hide">
													<label>Option 2</label>
													<input type="text" name="option[]" class="form-control" placeholder="option 2">
													<?php echo form_error('option[]', '<div class="errormsg">', '</div>'); ?>
												</div>
											
												<div class="col-md-12 mrgt10 options hide">
													<label>Option 3</label>
													<input type="text" name="option[]" class="form-control" placeholder="Option 3">
													<?php echo form_error('option[]', '<div class="errormsg">', '</div>'); ?>
												</div>
										
												<div class="col-md-12 mrgt10 options hide">
													<label>Option 4</label>
													<input type="text" name="option[]" class="form-control" placeholder="Option 4">
													<?php echo form_error('option[]', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
											
										</div>
										
										<div class="col-md-12 col-sm-12">
											<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
												<input class="btn btn-primary btn-raised" type="submit" name="save" value="save"> 
											</div>
										</div>
									</div>
								</div>
							</form>
						</div><!--end .card-body -->
					</div><!--end .card -->
					<!-- END HORIZONTAL FORM - SIZES -->
				</div><!--end .section-body -->
			</section>
			<!-- BEGIN SECTION -->
		</div>
		<!-- END CONTENT -->
	</div>
	<!-- END BASE -->

<script type="text/javascript">

	$('#mode').change(function(){
	var modeid = this.value;

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_cat_from_mode',
					type:'POST',
					data:{mode_id:modeid},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						$('#category').find('option').remove().end().append(data);


					},

       	 

					
		});

	});

	$("#type").change(function(){
		var type = this.value;
		if(type=="1"){
				$(".options").removeClass("hide");
				$(".correct_ans").addClass("hide");

		}else if(type=="2"){

				$(".options").addClass("hide");
				$(".correct_ans").removeClass("hide");
		}else{

				$(".options").addClass("hide");
				$(".correct_ans").addClass("hide");
		}


	});


		$('#category').change(function(){
	var modeid = this.value;

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_subcat_from_cate',
					type:'POST',
					data:{cate_id:modeid},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						$('#subcate').find('option').remove().end().append(data);


					},

       	 

					
		});

	});



		function checkvalid(){


		var Validat=1;
		var oFile = document.getElementById('image_id').files[0];
		//console.log(oFile);
		//return false;
		$("#file_error").text("");

			if(oFile==undefined){
					//alert('file not fafdfd');
				Validat=0;
				$("#file_error").text("Please Choose File");
			}else{

				var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
			if (! rFilter.test(oFile.type)) {
				//alert('unspoerted');
				//alert('file not supported');
				$("#file_error").text("file type not supported");
				Validat=0;
			}

		}


	// filter for image files
			

	

		if(Validat==1){
					return true;
				}else{
					return false;
				}



}
</script>	