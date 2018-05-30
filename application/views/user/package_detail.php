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
                      <div class="col-md-12 product-title">
                          <h3>Electronic Box : <?PHP echo $package_detail[0]['package_name']; ?></h3>
                          <h4><span class=""><?PHP echo $package_detail[0]['model_no']; ?></span>
                           <span class="t2"></span></h4>
                          <span><i class="fa fa-star" aria-hidden="true"></i></span>
                           <span><i class="fa fa-star" aria-hidden="true"></i></span>
                           <span><i class="fa fa-star" aria-hidden="true"></i></span>
                           <span><i class="fa fa-star" aria-hidden="true"></i></span>
                           <span><i class="fa fa-star" aria-hidden="true"></i></span>
                          <span class="rating_avg" style="background-color: green; color: #fff;">5.0</span>
              </div>
              </div>
            

                <!-- main slider carousel -->
                <div class="row" style="margin-top: 10px;">
                    <div class="col-lg-8 offset-lg-2" id="slider">
                            <div id="myCarousel" class="carousel slide">
                                <!-- main slider carousel items -->
                                <div class="carousel-inner">
                                    <div class="active item carousel-item" data-slide-number="0">
                                        <img src="<?php echo  base_url();?>uploads/<?php echo $package_detail[0]['images']; ?>" class="img-fluid">
                                    </div>
                                    <div class="item carousel-item" data-slide-number="1">
                                        <img src="<?php echo  base_url();?>uploads/<?php echo $package_detail[0]['images']; ?>" class="img-fluid">
                                    </div>
                                    

            <!--
                                    <a class="carousel-control left pt-3" href="#myCarousel" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                                    <a class="carousel-control right pt-3" href="#myCarousel" data-slide="next"><i class="fa fa-chevron-right"></i></a>
            -->

                                </div>
                                <!-- main slider carousel nav controls -->


                                <ul class="carousel-indicators list-inline">
                                    <li class="list-inline-item active">
                                        <a id="carousel-selector-0" class="selected" data-slide-to="0" data-target="#myCarousel">
                                            <img src="<?php echo  base_url();?>uploads/<?php echo $package_detail[0]['images']; ?>" class="img-fluid">
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a id="carousel-selector-1" data-slide-to="1" data-target="#myCarousel">
                                            <img src="<?php echo  base_url();?>uploads/<?php echo $package_detail[0]['images']; ?>" class="img-fluid">
                                        </a>
                                    </li>
                                    
                                </ul>
                        </div>
                    </div>

                </div>
                <!--/main slider carousel-->


              <div class="row">
                  <div class="col-md-12 product-detail">
                  
                      <h5>Product Benefits</h5>
                      <p><?PHP echo $package_detail[0]['description']; ?></p>
                   </div>
                  </div>
                  <div class="row">
                      <form action="../checkout" method="post">
                      <div class="col-md-3 col-sm-12" style="margin-bottom: 10px;">
                      <input type="text" class="btn btn-block btn-default" name="qty" placeholder="Quanity">
                      <input type="hidden" class="btn btn-block btn-default" name="package_id" value="<?php echo $package_detail[0]['id'];?>">
                      </div>
                       <div class="col-md-3 col-sm-12">
                      <input type="submit" class="btn btn-block btn-success" value="Buy Now">
                      </div>
                      </form>
                  </div>
      
    
</div>
      

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>