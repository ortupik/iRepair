<?php
   require_once dirname(__FILE__) . '/db/core.php';
   require_once dirname(__FILE__) . '/db/db_connect.php';

  $db = new DB_CONNECT();
   
    if (isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['first_name']) && isset($_POST['last_name'])){
        
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        
        $inject = "INSERT INTO `customers` (`phone`,`password`,`first_name`,`last_name`)VALUES ('$phone', '$password','$first_name','$last_name')";

        if(mysqli_query($db->connect(),$inject)){
            header("location:user_login.php");
        } else{
           echo mysqli_error($db->connect());
        } 
        
    }
?>
<!DOCTYPE html>
<html lang="en">
 <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/semantic.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/components/icon.min.css'>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>

    </head> 
      <body>
        <br><br><br>
        <div class="ui middle aligned center aligned grid">
            <div class="column six wide">
              <h2 class="ui teal image header">
                <div class="content">
                  Register new Account
                </div>
              </h2>
                <form class="ui large form" method="POST" action="register.php">
                <div class="ui stacked segment">
                    <div class="field">

                    <div class="ui left icon input">
                      <i class="user icon"></i>
                      <input type="text" name="first_name" placeholder="First Name">
                    </div>
                  </div>
                    <div class="field">
                    <div class="ui left icon input">
                      <i class="user icon"></i>
                      <input type="text" name="last_name" placeholder="Last Name">
                    </div>
                  </div>
                  <div class="field">
                    <div class="ui left icon input">
                      <i class="phone icon"></i>
                      <input type="text" name="phone" placeholder="Phone Number">
                    </div>
                  </div>
                  <div class="field">
                    <div class="ui left icon input">
                      <i class="lock icon"></i>
                      <input type="password" name="password" placeholder="Password" required="">
                    </div>
                  </div>
                   <div class="field">
                    <div class="ui left icon input">
                      <i class="lock icon"></i>
                      <input type="password" name="confirm_password" placeholder="Confirm Password" required="">
                    </div>
                  </div>
                  <button class="ui fluid large teal submit button" type="submit">Register</button>
                </div>

                <div class="ui error message"></div>

              </form>

              <div class="ui message">
                  Have an account ? <a href="login.php">Sign in</a>
              </div>
            </div>
          </div>

         
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
       
    </body>
</html>