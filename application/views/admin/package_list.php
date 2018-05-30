
		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Package List</li>
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
								
								
								<div class="clearfix"></div>
								
								<!-- BEGIN DATATABLE 2 -->
								<form action="<?php echo base_url(); ?>admin_master/delete_all_category" method="post" onsubmit="return confirm_delete();">
									<div class="row">
										<h4 class="col-md-2 col-sm-3 mrgt15">Package List</h4>
										<div class="col-md-10 col-sm-9 mrgt10">
											<a href="<?php echo base_url(); ?>admin_master/add_package" class="btn btn-primary btn-sm btn-raised pull-right ">Add New</a>
											
										</div><!--end .col -->
										
										<div class="clearfix"></div>
										
										<!-- BEGIN DATATABLE -->
										<div class="col-lg-12 category mrgt15">
											<div class="table-responsive no-margin">
												<table id="example" class="table order-column hover dataTable no-footer">
													<thead>
														<tr>
															<th>Id</th>
															<th>Image</th>
															<th>Package</th>
															<th>Description</th>
															<th> Action </th>
															
															
														</tr>
													</thead>
													<tbody id="tbody">
														
														<?php $ctn=1;
														foreach ($package_list as $key) { ?>
														<tr>
															<td><?php echo $ctn; ?></td>
															<td><img src="<?php echo base_url(); ?>uploads/<?php echo $key['images']; ?>" class="img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png';"/></td>
															<td><?php echo ucfirst($key['package_name']); ?></td>
															<td><?php echo ucfirst($key['description']); ?></td>
															<td><a href="<?php echo base_url();?>admin_master/edit_package/<?php echo $key['id']; ?>"><i class="fa fa-pencil action"></i> </a>  <a onclick="return confirm('Are you sure?');" href="<?php echo base_url();?>admin_master/delete_package/<?php echo $key['id']; ?>"> <i class="fa fa-trash-o action"></i> </a></td>
														</tr>
														<?php $ctn++; } ?>
													</tbody>
												</table>
											</div><!--end .col -->
										</div>
										<!-- END DATATABLE 2 -->
									</div><!--end .row -->
								</form>
							</div><!--end .card-body -->
						</div><!--end .card -->
						<!-- END HORIZONTAL FORM - SIZES -->
					
					</div><!--end .section-body -->
				</section>

				<!-- BEGIN BLANK SECTION -->
			</div><!--end #content-->
			<!-- END CONTENT -->

<script type="text/javascript">

	$('#mode').change(function(){
	var modeid = this.value;

		$("#tbody").html("");
		$.ajax({
					url:'<?php echo base_url();?>admin_master/category_list/'+modeid,
					//type:'POST',
					
					//data:{mode_id:modeid},
					success:function(data){
						
						window.location.href = '<?php echo base_url();?>admin_master/category_list/'+modeid;
						
       		 }

            

		});


	});


// $(document).ready(function(){
//         var oTable = $('#myTable').dataTable({
//             "aoColumns": [
//               { "bSortable": false },
//               null, null, null, null
//             ]
//         });
// });

function checkvalid(){
	var mode = $("#mode").val();
	var file = $("#file").val();
	var Validat=1;
	$("#mode_error").text("");
	$("#file_error").text("");
	if(mode==""){
		Validat=0;
		$("#mode_error").text("Select Exercise Mode");
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
 // return false;

	

		//var oFile = document.getElementById('file').files[0];
		//console.log(oFile);
		// var rFilter = /^(image\/png)$/i;
		// 	if (! rFilter.test(oFile.type)) {
		// 		//alert('unspoerted');
		// 		//alert('file not supported');
		// 		$("#file_error").text("file type not supported");
		// 		Validat=0;
		// 	}



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


// $("#ckbCheckAll").click(function () {
//     $(".checkBoxClass").prop('checked', $(this).prop('checked'));
// });


$(document).ready(function (){
   var table = $('#example').DataTable({
      'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
         'className': 'dt-body-center',
         
      }],
      "aLengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]], 
      'order': [[1, 'asc']]
   });

   $('#ckbCheckAll').on('click', function(){
      // Get all rows with search applied
      var rows = table.rows({ 'search': 'applied' }).nodes();
      // Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#ckbCheckAll').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
});






// var tbl = $('#datatable1').DataTable();
// $("input:checked", tbl.fnGetNodes()).each(function(){
// console.log($(this).val());
// });

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
