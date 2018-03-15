<?php
   require_once dirname(__FILE__) . '/db/core.php';
   require_once dirname(__FILE__) . '/db/db_connect.php';

  $db = new DB_CONNECT();
  if(!isset($_SESSION["admin_id"])){
     header("location:admin_login.php"); 
  }else{
    if (isset($_POST['phone'])  && isset($_POST['first_name']) && isset($_POST['last_name'])){
        
        $phone = $_POST['phone'];
        $password = 'staff101';
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        
        $inject = "INSERT INTO `staff` (`phone`,`password`,`first_name`,`last_name`)VALUES ('$phone', '$password','$first_name','$last_name')";

        if(mysqli_query($db->connect(),$inject)){
             echo '<script>alert("Successfully Added !");</script>';
        } else{
           echo mysqli_error($db->connect());
        } 
        
    }
     if(isset($_POST['delete'])){
         $staff_id = $_POST['delete'];
         $deletequery = mysqli_query($db->connect(), " DELETE FROM `staff` WHERE `staff_id` = $staff_id ") or die(mysqli_error($db->connect()));
         if ($deletequery) {
             echo '<script>alert("Successfully Deleted !");</script>';
          }
     }
    
     $query = mysqli_query($db->connect(), "SELECT * FROM `staff` ") or die(mysqli_error($db->connect()));
    if ($query) {
        $rows = mysqli_num_rows($query);
         $count = 0;
        if ($rows > 0) {
           while ($row = mysqli_fetch_array($query)) {
               $dataArray = array();
              
                $dataArray['staff_id'] = $row['staff_id'];
               $dataArray['first_name'] = $row['first_name'];
               $dataArray['phone'] = $row['phone'];
               $dataArray['last_name'] = $row['last_name'];              
               $finalArray[$count] = $dataArray;
               $count++;
               
        }
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
         <div class="ui   menu">
            <div class="ui container">
              <a href="#" class="header item">
                Scooter Repairs
              </a> 
                <a href="admin_manage_staff.php" class="item ">Home</a>
                <a href="admin_manage_staff.php" class="item active">Manage Staff</a>
                 <a href="admin_manage_clients.php" class="item ">Manage Clients</a>
                <div class="right menu">
                  <a href="#" class="item active"><i class="grid user icon"></i> ADMIN</a>
                   <a href="admin_logout.php" class="item right">Logout</a>
                  </div>
                
            </div>
          </div>
        <br><br><br>
        <div class="ui middle  center aligned grid">
            <div class="column five wide">
              <h5 class="ui  image header">
                <div class="content">
                  ADD STAFF MEMBER
                </div>
              </h5>
                <form class="ui large form" method="POST" action="admin_manage_staff.php">
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
                 
                  </div>
                  <button class="ui fluid large teal submit button" type="submit">Add Staff Member</button>
              </form> 
            </div>

            <div class="column ten wide">
                 <h5 class="ui   header">
                    <div class="content">
                      ALL STAFF MEMBERS
                    </div>
              </h5>
                  <table class="ui compact celled  table " >
                        <thead class="full-width">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Delete User</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                          foreach ($finalArray as $data) { ?>
                            
                            <tr class="">
                                <td><?php echo $data['first_name'];?></td>
                                  <td><?php echo $data['last_name'];?></td>
                                  <td><?php echo $data['phone'];?></td>
                                  <td ><form method="POST" action="admin_manage_staff.php"><button class="ui button red compact" type="submit" name="delete" value="<?php echo $data['staff_id'];?>"><i class="icon delete"></i> Delete</button> </form></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                    </table>
            </div>
          </div>
         
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
       
    </body>
</html>
  <?php } ?>