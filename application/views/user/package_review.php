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
              
                  <h4>Review</h4>
                  
                     <form action="../submit_review" method="post">
                          <div class="form-group">
                              <input type="hidden" maxvalue="5" class="form-control" name="package" value="<?php echo $this->uri->segment('3');?>" id="email">
                            Star:
                            <input type="number" maxvalue="5" class="form-control" name="star" id="email">
                          </div>
                          <div class="form-group">
                              
                            Title:
                            <input type="text" class="form-control" name="title" id="email">
                          </div>
                          <div class="form-group">
                            Review
                            <textarea class="form-control" name="review"> </textarea>
                          </div>
                                       <button type="submit" class="btn btn-success btn-block pg_2_btn">Submit</button>
                        </form> 
                  
              </div>
              </div>
          </div>
          
        
    
      
      </div>
 
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
 <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>