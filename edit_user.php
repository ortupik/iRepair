<?php
  
 require_once dirname(__FILE__) . '/db/core.php';
 require_once dirname(__FILE__) . '/db/db_connect.php';
 
   $db = new DB_CONNECT();

 if(!isset($_SESSION["customer_id"])){
     header("location:user_login.php"); 
  }
  
    $customer_id = $_SESSION["customer_id"];
    
       if (isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['first_name']) && isset($_POST['last_name'])){
        
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        
        if($password != $confirm_password){
              echo '<script>alert("Passwords Don\'t Match !");</script>';
        }else{
             if($password == ""){
                  $inject = "UPDATE `customers` SET `phone` = '$phone', `first_name` = '$first_name',`last_name` = '$last_name' WHERE `customer_id` = $customer_id;";
             }else{
                $inject = "UPDATE `customers` SET `phone` = '$phone',`password` = '$password', `first_name` = '$first_name',`last_name` = '$last_name' WHERE `customer_id` = $customer_id;";
             }
             $_SESSION["user_name"] = $first_name.' '.$last_name;
            if(mysqli_query($db->connect(),$inject)){
                 echo '<script>alert("Successfully Updated your Details !");</script>';
            } else{
               echo mysqli_error($db->connect());

            }
        }
        } 
        
     $query = mysqli_query($db->connect(), "SELECT * FROM `customers` WHERE `customer_id` = $customer_id ") or die(mysqli_error($db->connect()));
    if ($query) {
        $rows = mysqli_num_rows($query);
         $count = 0;
        if ($rows > 0) {
           if ($row = mysqli_fetch_array($query)) {
               $dataArray = array();
               $dataArray['first_name'] = $row['first_name'];
               $dataArray['phone'] = $row['phone'];
               $dataArray['last_name'] = $row['last_name']; 
               $finalArray[$count] = $dataArray;
               $count++;
          }
        }
    }
 ?>
<html>
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
         <div class="ui   menu">
            <div class="ui container">
              <a href="#" class="header item">
                Scooter Repairs
              </a> 
                <a href="client_home.php" class="item ">Home</a>
                <a href="edit_user.php" class="item active">Edit My Profile</a>
                <div class="right menu">
                  <a href="#" class="item active"><i class="grid user icon"></i><?php echo $_SESSION['user_name']; ?></a>
                  <a href="client_logout.php" class="item right">Logout</a>
                  </div>
                
            </div>
          </div>
          <br>
        <div class="ui container  grid"> 
            <div class="three wide column">

            </div>
            <div class=" ten wide column">

              
                <form method="POST" action="edit_user.php">
                    <div class="ui form">  
                        <div class="ui segments ">

                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Change Name</label>
                                    <div class="  fields">
                                        <div class="field five wide">
                                            <input name="first_name" placeholder="First Name" value="<?php echo $finalArray[0]['first_name'];?>" type="text" required="">
                                        </div>
                                        <div class="field five wide">
                                            <input name="last_name" placeholder="Last Name" value="<?php echo $finalArray[0]['last_name'];?>" type="text" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Change Phone No.</label>
                                    <div class="  fields">
                                        <div class="field seven wide">
                                            <input name="phone" placeholder="Phone" value="<?php echo $finalArray[0]['phone'];?>" type="text" required="">
                                        </div>

                                    </div>
                                </div>
                                
                            </div>
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Change Passwords</label>
                                    <div class="ui left icon input field seven wide">
                                      <i class="lock icon"></i>
                                      <input type="password" name="password" placeholder="New Password" >
                                    </div>
                                    <div class="ui left icon input field seven wide">
                                      <i class="lock icon"></i>
                                      <input type="password" name="confirm_password" placeholder="Confirm Password" >
                                    </div>
                                </div>
                                 <input class="right  ui button compact green" name="save" type="submit" value="Save Changes"/>

                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
        <script >
            $(document).ready(function () {
                $('select.dropdown').dropdown();
            });
        </script>
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/5a7731ab4b401e45400ca862/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    </body>
</html>
