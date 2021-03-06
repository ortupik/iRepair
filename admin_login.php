<?php

 require_once dirname(__FILE__) . '/db/core.php';
 require_once dirname(__FILE__) . '/db/db_connect.php';

  $db = new DB_CONNECT();

$response = array();

if (isset($_POST['username']) && isset($_POST['password'])) {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
   
       
    if (!empty($username) && !empty($password)) {
        
        $queryresult = mysqli_query($db->connect(), "SELECT * FROM `admin` WHERE `username`='$username' AND `password`='$password' ");
        
        if ($queryresult) {
            
            if ( mysqli_num_rows($queryresult) <=0) {
                $response['message'] = 'Invalid login credentials';
                $response['success'] = 0;
                
                echo '<script>alert("Invalid login credentials !");</script>';
               // header("location:index.php");           
            } else  {

                $row = mysqli_fetch_array($queryresult);
                echo $_SESSION["admin_id"] = $row['id'];
                header("location:admin_manage_staff.php");
          }
            
        } else {
             $response['message'] = "Database Error in Login";
            $response['success'] = 0;
            echo json_encode($response['message']);
        
        }
    }else{
        $response['success'] = 0;
        $response['message'] = "Some Fields Empty !";
         //  echo json_encode($response['message']);
    }
}else{
     $response['success'] = 0;
      $response['message'] = "Some Fields NOT SET !";
   // echo json_encode($response['message']);
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/semantic.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
          <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/components/icon.min.css'>

    </head>
    
    <body>
        <br><br><br>
        <div class="ui middle aligned center aligned grid">
            <div class="column six wide"><h1>ADMIN LOGIN</h1>
              
                <form class="ui large form" method="POST" action="admin_login.php">
                <div class="ui stacked segment">
                  <div class="field">
                    <div class="ui left icon input">
                      <i class="user icon"></i>
                      <input type="text" name="username" placeholder="Username">
                    </div>
                  </div>
                  <div class="field">
                    <div class="ui left icon input">
                      <i class="lock icon"></i>
                      <input type="password" name="password" placeholder="Password" required="">
                    </div>
                  </div>
                  <button class="ui fluid large black submit button" type="submit">Login</button>
                </div>

                <div class="ui error message"></div>

              </form>
              
            </div>
          </div>

         
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
       
    </body>
</html>
