<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<!-- SECTION-HEADER -->
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Add Package</li>
							<a href="<?php echo base_url(); ?>admin_master/subcategory_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
						</ol>
					</div>
					<!-- END SECTION-HEADER -->
					
					<!-- SECTION-BODY -->
					<div class="section-body">
						<!-- CARD -->
						<div class="card">
							<!-- CARD-BODY -->
							<div class="card-body">
								<?php //print_r($exercise_mode); 
$attributes = array('action' => base_url().'admin_master/add_package','onsubmit'=>'return checkvalid()');
echo form_open_multipart('admin_master/add_package', $attributes);
?>
<?php 
if(isset($success_msg)){ ?>
<div class="success-message"><?php echo $success_msg; ?></div>
<?php } ?>
<?php 
if(isset($error_msg)){ ?>
<div class="error-message"><?php echo $error_msg; ?></div>
<?php } ?>
									<div class="form-group">
										<div class="row">
											<div class="col-md-3 col-sm-6">


												<div class="col-md-12 mrgt10">
													<label>Product</label>
													<select id="product" name="product" class="form-control">
														<option value="">Product</option>
														<?php foreach($category as $key){ ?>
															<option <?php echo set_select('category', $key['id']) ?>  value="<?php echo $key['id'];?>"><?php echo $key['product_name'];?> </option>
														<?php } ?>
													</select>
													<?php echo form_error('product', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
												<div class="col-md-12 mrgt10">
													<label>Package Name</label>
													<input type="text" name="package" class="form-control" placeholder="Package Name" >
													<?php echo form_error('package', '<div class="errormsg">', '</div>'); ?>
												</div>
                                                
                                                <div class="col-md-12 mrgt10">
													<label>Model No </label>
													<input type="text" name="model" class="form-control" placeholder="model no" >
													<?php echo form_error('model', '<div class="errormsg">', '</div>'); ?>
												</div>
												
												
												<div class="col-md-12 mrgt10">
													<label>Price</label>
													<input type="text" name="price" class="form-control" placeholder="price" >
													<?php echo form_error('price', '<div class="errormsg">', '</div>'); ?>
												</div>
												
												<div class="col-md-12 mrgt10">
													<label>Batch No </label>
													<input type="text" name="batch" class="form-control" placeholder="batch no" >
													<?php echo form_error('batch', '<div class="errormsg">', '</div>'); ?>
												</div>
												
												<div class="col-md-12 mrgt10">
													<label>SKU</label>
													<input type="text" name="sku" class="form-control" placeholder="SKU" >
													<?php echo form_error('sku', '<div class="errormsg">', '</div>'); ?>
												</div>
												

												<div class="col-md-12 mrgt10">
													<label>Description</label>
													<textarea name="desc" class="form-control" placeholder="price" > </textarea>
													<?php echo form_error('desc', '<div class="errormsg">', '</div>'); ?>
												</div>

												
												<div class="clearfix"></div>
												<label class="col-md-12 mrgt10">Image</label>
												<div class="col-md-12 mrgt5">
													<div class="btn btn-primary btn-raised">
														<span>Choose File</span>
														<input type="file" name="userfile" class="fileUpload" id="image_id" />
													</div>
												</div>
												<span id="file_error" class="errormsg"> </span>
												<div class="clearfix"></div>
												<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
													
													<input class="btn btn-primary btn-raised" type="submit" name="save" value="save"> 
												</div>
											</div>
											
											
										</div>
									</div>
								</form>
							</div>
							<!-- END .CARD-BODY -->
						</div>
						<!-- END .CARD -->
					</div>
					<!-- END .SECTION-BODY -->
				</section>
				<!-- END SECTION -->
			</div>
			<!-- END CONTENT -->
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
						
						//$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': modeid,'sort':sort,'per_page':per_page});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
		});

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_type_from_mode',
					type:'POST',
					data:{mode_id:modeid},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						$('#exe_type').find('option').remove().end().append(data);
						$('.selectpicker').selectpicker('refresh');
						
						//$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': modeid,'sort':sort,'per_page':per_page});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
		});


	});



	function checkvalid(){


		var Validat=1;
		var oFile = document.getElementById('image_id').files[0];
		//console.log(oFile);
		//return false;
		$("#file_error").text("");

			//if(oFile==undefined){
					//alert('file not fafdfd');
			//	Validat=0;
			//	$("#file_error").text("Please Choose File");
			//}else{

				var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
			if (! rFilter.test(oFile.type)) {
				//alert('unspoerted');
				//alert('file not supported');
				$("#file_error").text("file type not supported");
				Validat=0;
			}

		//}


	// filter for image files
			

	

		if(Validat==1){
					return true;
				}else{
					return false;
				}



}



			</script>