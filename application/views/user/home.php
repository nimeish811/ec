<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/user/mystyle.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <title></title>
  </head>
  <body>
    
      <div class="container">
      
          <div class="row">
          <div class="col-md-12">
              <img src="<?php echo base_url();?>uploads/<?PHP echo $product_list[0]['product_image']; ?>" class="img-fluid">
              </div>
          </div>
          
           
          <div class="row">
              <div class="col-md-4 pg_3_btn">
              <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#exampleModalLong">BUY</button>
              </div>
              </div>
          
      </div>
      <hr>
      <div class="container pg_3_reviews">
      <h5>Reviews</h5>
      <?php foreach($review_list as $key=>$value) { ?>
       <h5><?php echo $value['title']; ?></h5>
      
            
           <?php for($i=0;$i < $value['star'];$i++) { ?>
            <span><i class="fa fa-star" aria-hidden="true"></i></span>
           <?php } ?>
      
      
     
      <p> <?php echo $value['review']; ?></p>
<?php } ?>
      </div>
      
      
   <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header mheader">
                <h5 class="modal-title" id="exampleModalLongTitle">Package</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
          
               <div class="modal-body mbody">
                  <div class="container-fluid">
                    
                    <?php foreach($package_list as $key=>$value) { ?>
                    <div class="row">
                      <div class="col-xs-10">
                        <h5><?php echo $value['package_name'];?></h5>
                         <p><?php echo $value['model_no'];?></p>
                       
                        </div>
                      <div class="col-xs-2 mbtn"><a href="package_details/<?php echo $value['id']; ?>" class="btn btn-success btn-block">BUY NOW</a>
                        <button type="button" class="btn btn-success btn-block">PREBOOK</button>
                        <a href="package_review/<?php echo $value['id']; ?>" class="btn btn-success btn-block">REVIEW</a>
                        </div>
                    </div>
                  <hr>
                       <?php } ?>
                  
                  </div>
                </div>    
            </div>
          </div>
        </div>
      
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>
