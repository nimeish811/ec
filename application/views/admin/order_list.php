

		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Order List</li>
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
							
								
								<!-- BEGIN DATATABLE 2 -->
								<div class="row">
									<div class="col-md-12 mrgt5">
										<h4>Order List</h4>
									</div><!--end .col -->
									<div class="col-lg-12 category mrgt15">
										<div class="table-responsive no-margin">
											<table id="datatable1" class="table hover">
												<thead>
													<tr>
														<th>Id</th>
														
														<th>Package Name</th>
														<th>Quantity</th>
														<th>Name</th>
														<th>Email</th>
														<th>Contact Numbet</th>
													</tr>
												</thead>
												<tbody>
													
													<?php $ctn=1;
													foreach ($order_list as $key) { ?>
													<tr>
														<td><?php echo $ctn; ?></td>
														<td><?php echo $key['package_name']; ?></td>
														<td><?php echo $key['qty']; ?></td>
														<td><?php echo $key['name']; ?></td>
														<td><?php echo $key['email']; ?></td>
														<td><?php echo $key['mobile']; ?></td>
														
													</tr>
													<?php $ctn++; } ?>
												</tbody>
											</table>
										</div><!--end .col -->
									</div><!--end .col -->
								</div><!--end .row -->
								<!-- END DATATABLE 2 -->
								
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

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_mode_category',
					type:'POST',
					data:{mode_id:modeid},
					sucess:function(data){


		var results = JSON.parse(data);
		var arrayReturn = [], results = returnData;
            for (var i = 0, len = results.length; i < len; i++){
                var result = results[i];
                arrayReturn.push([ result.Age, result.Name]);
        }

            console.log('here');

		}

       	 

					
		});

	});

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
	}

	if(Validat==1){
		return true;
	}else{
		return false;
	}
}

</script>
