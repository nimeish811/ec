<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
   <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <link href="style.css" rel="stylesheet">
   
  </head>
  <body>
 
      <div class="container pg_2">
      
          
          <div class="container">
          <div class="row">
              <div class="col-md-12">
              
                  <h4>Information</h4>
                  
                     <form action="order" method="post">
                         
                          <input type="hidden" class="form-control" name="qty" value="<?php echo $qty; ?>">
                           <input type="hidden" class="form-control" name="package" value="<?php echo $id; ?>" >
                          <div class="form-group">
                            Name:
                            <input type="text" class="form-control" name="name" >
                          </div>
                          <div class="form-group">
                            Email Address:
                            <input type="email" class="form-control" name="email" id="pwd">
                          </div>
                         <div class="form-group">
                            Contact Number:
                            <input type="text" class="form-control" name="number" id="pwd">
                          </div>
                          
                            <input type="checkbox" id="check1" name="check-1" value="1">
                              <label for="check1">Local Pickup</label>
                         <input type="checkbox" id="check2" name="check-2" value="2">
                              <label for="check1">Delivery</label>
                              
                         
                        
                  
              </div>
              </div>
          </div>
          
          <div class="container description">
          <div class="row">
              <div class="col-md-5 quote">
              
                  <p>"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting"</p>
              </div>
              <div class="col-md-5 address">
              
                  <h4>Store Address:</h4>
                  
                  <p>lorem ipsum lorem isp</p>
                  <p>lorem ipsum lorem ipsum lorem ipsum</p>
                  <p>380015, Kalupur</p>
              </div>
              
              <div class="col-md-2">
              <button type="submit" class="btn btn-success btn-block pg_2_btn">Submit</button>
              </div>
           </form> 
              </div>
          </div>
          
      
      
    
      
      </div>
 
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
 <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>