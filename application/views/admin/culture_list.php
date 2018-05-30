<script href="https://raw.githubusercontent.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>

		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Culture List</li>
						</ol>
					</div><!--end .section-header -->
					<div class="section-body">
						
						<!-- BEGIN HORIZONTAL FORM - SIZES -->
						<div class="card">
							<div class="card-body">
								<?php if(isset($success_msg)){ ?>
								<div class="success-message"><?php echo $success_msg; ?></div>
								<?php } ?>
								<?php 
								if(isset($error_msg)){ ?>
								<div class="error-message"><?php echo $error_msg; ?></div>
								<?php } ?>
								
								<form action="<?php echo base_url(); ?>admin_master/culture_import" method="post" name="upload_excel" enctype="multipart/form-data" onsubmit="return checkvalid();" >
									<div class="form-group row">
										 <div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select id="lang" name="lang" class="form-control">
												<option value="">Select Language</option>
												<?php foreach($source_lang as $key){ ?>
												<option <?php if(isset($lang)) if($key['source_language_id']==$lang){ echo "selected";} ?> value="<?php echo $key['source_language_id'];?>"><?php echo $key['language_name'];?> </option>
												<?php } ?>
											</select>
											<span id="mode_error" class="errormsg"> </span>
										</div> 
										<div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select id="category" name="category" class="form-control">
												<option value="">Select Category</option>
												<?php foreach($category as $key){ ?>
												<option <?php if(isset($category)) if($key['exercise_mode_category_id']==$category_select){ echo "selected";} ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
												<?php } ?> 
											</select>
											<span id="category_error" class="errormsg"> </span>
										</div>		
										<div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select id="subcate" name="subcategory" class="form-control">
												<option value="">Select SubCategory</option>
												<?php foreach($subcategory as $key){ ?>
												<option <?php if(isset($subcategory)) if($key['exercise_mode_subcategory_id']==$subcategory_select){ echo "selected";} ?> value="<?php echo $key['exercise_mode_subcategory_id'];?>"><?php echo $key['subcategory_name_in_en'];?> </option>
												<?php } ?>
											</select>
											<span id="subcat_error" class="errormsg"> </span>
										</div>
										<div class="col-md-2 col-sm-4 col-xs-7 mrgt5">
											<div class="btn btn-primary btn-raised">
												<span>Choose Excel File</span>
												<input type="file" class="fileUpload" name="file" id="file" />
											</div>
											<span id="file_error" class="errormsg"> </span>
											<span id="selecte_file_name" class=""> </span>
										</div>

										<div class="col-md-1 col-sm-3 col-xs-5 mrgt5">
											
											<button type="submit" class="btn btn-primary btn-raised" id="submit" name="import">Import</button>
										</div>

										<!-- <div class="col-md-1 col-sm-4 col-xs-12 mrgt5">
											
											<select id="sort" name="sort" class="form-control">
												<option value="" selected>Sort By</option>
												<option <?php  if($sort_select=="1"){ echo "selected";} ?>  value="1">A-Z </option>
												<option  <?php  if($sort_select=="2"){ echo "selected";} ?>  value="2">Z-A </option>
											</select>
	
										</div> -->
										<div class="col-md-1 col-sm-3 col-xs-5 text-right mrgt5 pdr0">
											<a href="<?php echo base_url();?>admin_master/download_culture_sample" class="btn btn-primary">Sample Excel File</a>
										</div>
									</div>
								</form>
								
								<div class="clearfix"></div>
								
								<!-- BEGIN DATATABLE 2 -->
								<form action="<?php echo base_url(); ?>admin_master/delete_all_culture" method="post" onsubmit="return confirm_delete();">
									<div class="row">
										<h4 class="col-md-2 col-sm-3 mrgt15">Culture List</h4>
										<div class="col-md-10 col-sm-9 mrgt10">
											<a href="<?php echo base_url(); ?>admin_master/add_culture" class="btn btn-primary btn-sm btn-raised pull-right">Add New</a>
											<input type="submit" class="btn btn-primary btn-sm btn-raised pull-right mrgr10" value="Delete Selected" />
										</div><!--end .col -->
										
										<div class="clearfix"></div>
										
										<div class="col-lg-12 category mrgt15">
											<div class="table-responsive no-margin">
												<div id="tbody">
												<table id="example" class="table order-column hover">
													<thead>
														<tr>
															<th> <label class="checkbox-inline checkbox-styled"><input type="checkbox" class="dt-body-center" name="delete[]" id="ckbCheckAll"/><span></span></label></th>
															<th> Id</th>
															<th>Image </th>
															<th> Title</th>
															<th> Action</th>
														</tr>
													</thead>
													<tbody >
														
														<?php $ctn=1;


														$root_path  = $this->config->item('root_path');
														
														foreach ($grammer_list as $key) {  //http://blue.alphademo.in/sfiapp/uploads/audio/katt_sw.m4a ?>
														<tr>
																<td><label class="checkbox-inline checkbox-styled"><input type="checkbox" name="delete[]" class="checkBoxClass" value="<?php echo $key['culture_master_id']; ?>"/><span></span></label></td>
																<td><?php echo $ctn; ?></td>

																<?php  $file = $root_path.'uploads/words/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$key['image_name'] ;?>
																<td><img src="<?php echo base_url(); ?>uploads/words/<?php echo $key['category_id']; ?>/<?php echo $key['subcategory_id']; ?>/<?php echo $key['image_name']; ?>"  width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png'"; class='<?php if(!file_exists($file)){ echo "img-responsive missing"; } else{ echo 'img-responsive';} ?>'/></td>
														
																<td><?php echo ucfirst($key['title_text']); ?></td>
																
																
																<td><a href="<?php echo base_url();?>admin_master/edit_culture/<?php echo $key['culture_master_id']; ?>"><i class="fa fa-pencil action"></i> </a>  <a onclick="return confirm('Are you sure?');" href="<?php echo base_url();?>admin_master/delete_culture/<?php echo $key['culture_master_id']; ?>"> <i class="fa fa-trash-o action"></i> </a></td>
															
														</tr>
														<?php $ctn++; } ?>
													</tbody>

												</table>
													<span style="float:right;"> <?php echo $links; ?> </span>  <!--  <span style="float:left;"> <?php// echo $page_info; ?> -->		</span>
											</div>
											
											</div><!--end .col -->
										</div><!--end .col -->
									</div><!--end .row -->
						<!-- END DATATABLE 2 -->
									
							</div><!--end .card-body -->
						</div><!--end .card -->
						<!-- END HORIZONTAL FORM - SIZES -->
					</form>
					</div><!--end .section-body -->
				</section>

				<!-- BEGIN BLANK SECTION -->
			</div><!--end #content-->
			<!-- END CONTENT -->

<script type="text/javascript">
	(function(d){d.fn.redirect=function(a,b,c){void 0!==c?(c=c.toUpperCase(),"GET"!=c&&(c="POST")):c="POST";if(void 0===b||!1==b)b=d().parse_url(a),a=b.url,b=b.params;var e=d("<form></form");e.attr("method",c);e.attr("action",a);for(var f in b)a=d("<input />"),a.attr("type","hidden"),a.attr("name",f),a.attr("value",b[f]),a.appendTo(e);d("body").append(e);e.submit()};d.fn.parse_url=function(a){if(-1==a.indexOf("?"))return{url:a,params:{}};var b=a.split("?"),a=b[0],c={},b=b[1].split("&"),e={},d;for(d in b){var g= b[d].split("=");e[g[0]]=g[1]}c.url=a;c.params=e;return c}})(jQuery);
	
	$('#lang').change(function(){
	var lang = this.value;
	var sort= $('#sort').val();
	var category= $('#category').val();
	var subcate= $('#subcate').val();

		$.ajax({
					url:'<?php echo base_url();?>admin_master/culture_list/'+lang,
					type:'POST',
					data:{mode_id:lang},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						//$('#category').find('option').remove().end().append(data);

						$().redirect('<?php echo base_url();?>admin_master/culture_list', {'lang': lang,'cate_id':category,'subcate_id':subcate});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
		});

	});

	$('#subcate').change(function(){
	var subcate = this.value;
	var category= $('#category').val();
	var lang= $('#lang').val();
	var sort= $('#sort').val();

		$.ajax({

					url:'<?php echo base_url();?>admin_master/culture_list/'+lang+'/'+category+'/'+subcate,
					success:function(data){
						
						//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+category+'/'+subcate;
						$().redirect('<?php echo base_url();?>admin_master/culture_list', {'lang': lang,'cate_id':category,'subcate_id':subcate});
						
	   				}		
		});

	});

	$('#category').change(function(){
	var cate_id = this.value;
	//var subcate = this.value;
	var subcate= "";
	var lang= $('#lang').val();
	var sort= $('#sort').val();


			$.ajax({
					url:'<?php echo base_url();?>admin_master/get_subcat_from_cate',
					type:'POST',
					data:{cate_id:cate_id},
					success:function(data){
						//$('#category').html("");
						//alert('here');
						$('#subcate').find('option').remove().end().append(data);
						//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+cate_id+'/'+subcate;
						$().redirect('<?php echo base_url();?>admin_master/culture_list', {'lang': lang,'cate_id':cate_id,'subcate_id':subcate});
					},			
			}); 

	});


	// $('#sort').change(function(){
	// var sort = this.value;
	// //var subcate = this.value;
	// var subcate= $('#subcate').val();
	// var mode= $('#mode').val();
	// var cate_id= $('#category').val();

	// 		$.ajax({
	// 				url:'<?php echo base_url();?>admin_master/words_list/',
					
	// 				success:function(data){
	// 					//$('#category').html("");
	// 					//alert('here');
	// 				//	$('#subcate').find('option').remove().end().append(data);
	// 					//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+cate_id+'/'+subcate;
	// 					$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': mode,'cate_id':cate_id,'subcate_id':subcate,'sort':sort});
	// 				},			
	// 		}); 

	// });

	function checkvalid(){

			var lang = $("#lang").val();
			var file = $("#file").val();
			var category = $("#category").val();
			var subcate = $("#subcate").val();
			var Validat=1;
				$("#mode_error").text("");
				$("#file_error").text("");
				$("#category_error").text("");
				$("#subcat_error").text("");
		
			if(lang==""){
				Validat=0;
				$("#mode_error").text("Select Language");
			}
			if(file==""){
				Validat=0;
				$("#file_error").text("Choose Excel File");
			}else{

				var ext = file.split('.').pop();
				//console.log(ext);
				if(ext!="xlsx"){
					Validat=0;
				$("#file_error").text("file type not supported");
				}
			}
			if(category==""){
				Validat=0;
				$("#category_error").text("Select Category");
			}
			if(subcate==""){
				Validat=0;
				$("#subcat_error").text("Select Subcategory");
			}

				if(Validat==1){
					return true;
				}else{
					return false;
				}
	}


	function confirm_delete(){

				var cm =  confirm("Are you sure to delete all selected?");
				var Validat=1;
						
						if(cm==false){
							Validat=0;
						}
						if(Validat==1){
						return true;
						}else{
							return false;
						}

	}


$("#ckbCheckAll").click(function () {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});

$(document).ready(function (){
   var table = $('#example').DataTable({
      'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
      
        // 'className': 'dt-body-center',
         
      }],
      "aLengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]], 
      'order': [[1, 'asc']],
      "paging":   false,
      //"ordering": false,
      "info":     false,
      "searching": false

   });

 //var p = table.rows({ page: 'current' }).nodes();
   // $('#ckbCheckAll').on('click', function(){
   //    // Get all rows with search applied
   //    var rows = table.rows({ 'search': 'applied',page: 'current' }).nodes();
   //    // Check/uncheck checkboxes for all rows in the table
   //    $('input[type="checkbox"]', rows).prop('checked', this.checked);
   // });
   // $('#example tbody').on('change', 'input[type="checkbox"]', function(){
   //    // If checkbox is not checked
   //    if(!this.checked){
   //       var el = $('#ckbCheckAll').get(0);
   //       // If "Select all" control is checked and has 'indeterminate' property
   //       if(el && el.checked && ('indeterminate' in el)){
   //          // Set visual state of "Select all" control 
   //          // as 'indeterminate'
   //          el.indeterminate = true;
   //       }
   //    }
   // });
});

$('input[type=file]').change(function(e){
   //alert($('#file').val());
   var filePath= $('#file').val();
   if(filePath.match(/fakepath/)) {
                        // update the file-path text using case-insensitive regex
                        filePath = filePath.replace(/C:\\fakepath\\/i, '');
                    }

   $("#selecte_file_name").text(filePath);
});


</script>
