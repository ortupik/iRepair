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
<?php

 require_once dirname(__FILE__) . '/db/core.php';
 require_once dirname(__FILE__) . '/db/db_connect.php';

  $db = new DB_CONNECT();
    $conn = $db->connect();


   if(!isset($_SESSION["customer_id"])){
     header("location:user_login.php"); 
  }else{
   if(isset($_GET['order_id'])){
   
     $order_id = $_GET['order_id'];  
      $finalArray = array();
   
    $query = mysqli_query($conn, "SELECT order_id,scooter_type,`time`,status,`job_description`,`items_left`,`able_power`,`able_charge`,`able_move`,(SELECT SUM(`price`) FROM `repairs` WHERE repairs.`order_id` = `orders`.order_id) as price,(SELECT `first_name` FROM `staff` WHERE staff.`staff_id` = `orders`.staff_id) as staff_name,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as customer_name,(SELECT phone FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as phone FROM `orders` WHERE `order_id` = $order_id ") or die(mysqli_error($conn));
    if ($query) {
        $rows = mysqli_num_rows($query);
         $count = 0;
        if ($rows > 0) {
           if ($row = mysqli_fetch_array($query)) {
               $dataArray = array();
               $order_id  = $dataArray['order_id'] = $row['order_id'];
               $dataArray['scooter_type'] = $row['scooter_type'];
               $dataArray['time'] = $row['time'];
               $dataArray['phone'] = $row['phone'];
                $dataArray['status'] = $row['status'];
               $dataArray['price'] = $row['price'];
               $dataArray['job_description'] = $row['job_description'];
               $dataArray['items_left'] = $row['items_left'];
               $dataArray['able_power'] = $row['able_power'];
               $dataArray['able_charge'] = $row['able_charge'];
               $dataArray['able_move'] = $row['able_move'];
               $dataArray['customer_name'] = $row['customer_name'];
                $repairArray = array();
               
               $query2 = mysqli_query($db->connect(), "SELECT description,`status` FROM repairs WHERE order_id = $order_id ORDER BY `status` ASC  ") or die(mysqli_error($db->connect()));
                if ($query2) {
                    $rows2 = mysqli_num_rows($query2);
                     $count2 = 0;
                    if ($rows2 > 0) {
                       while ($row2 = mysqli_fetch_array($query2)) {
                           $dataArray2 = array();
                           $dataArray2['description'] = $row2['description'];
                           $dataArray2['status'] = $row2['status'];
                           $repairArray[$count2] = $dataArray2;
                           $count2++;
                       }
                       }
                    }
                
               $dataArray['repair_array'] = $repairArray;
               $finalArray[$count] = $dataArray;
               $count++;
        }
        }
    }
   }
  }
    
?>

    <body>
         <div class="ui   menu">
            <div class="ui container">
              <a href="#" class="header item">
                Scooter Repairs
              </a> 
                <a href="client_home.php" class="item  ">Home</a>
                <a href="client_home.php" class="item  active">Details</a>
               <a href="edit_user.php" class="item ">Edit My Profile</a>
                <div class="right menu">
                  <a href="#" class="item active"><i class="grid user icon"></i> <?php echo $_SESSION['user_name']; ?></a>
                  <a href="client_logout.php" class="item right">Logout</a>
                  </div>
                
            </div>
          </div>
          <br>
        <div class="ui container  grid"> 
            <div class="five wide column">

                <div class="ui cards ">
              <?php
                foreach ($finalArray as $data) {?>
                    <div class="card">
                        <div class="content">
                             <span class="ui floated   header">
                                    <span class="card_progress">JOB ID #<?php echo $data['order_id'];?></span>
                                   <span><h5 class="card_date" ><?php echo $data['time'];?></h5></span>
                                </span>
                        </div>
                         <div class="content">
                                <span class="ui  floated header">
                                    <span class="card_progress"><h4>JOB STATUS:</h4></span>
                                     <?php $status = $data['status']; 
                                    if($status == "IN QUEUE"){ ?>
                                          <span><h4 style="color: orangered;"><?php echo $status;?></h4></span>
                                  <?php }elseif($status == "JOB STARTED"){?>
                                          <span><h4 style="color:purple;"><?php echo $status;?></h4></span>
                                      <?php }elseif($status == "WAITING FOR CUSTOMER RESPONSE"){?>
                                          <span><h4 style="color: orange;"><?php echo 'IN WAITING'?></h4></span>
                                     <?php }elseif($status == "JOB COMPLETED"){?> 
                                          <span><h4 style="color: green;"><?php echo 'COMPLETED';?></h4></span>
                                    <?php }elseif($status == "CUSTOMER COLLECTED"){?> 
                                          <span><h4 style="color: grey;"><?php echo 'CUSTOMER COLLECTED';?></h4></span>
                                    <?php } ?> 
                                </span>
                            </div>
                       
                          <div class="content">
                            <div class="ui relaxed divided list">
                              <div class="item">
                                  <strong>Your Names:</strong> <?php echo $data['customer_name'];?>
                              </div>
                                <div class="item">
                                <div class="content">
                                  <strong>Phone:</strong>  <?php echo $data['phone'];?>
                                </div>
                              </div>
                              <div class="item">
                                <div class="content">
                                  <strong>Scooter Type:</strong>  <?php echo $data['scooter_type'];?>
                                </div>
                              </div>
                              
                            </div>
                    </div>
                    <div class="content">
                    <div class="header">Repairs</div>
                    <div class="description">
                      <div class="ui bulleted list">
                         <?php
                          $repairArray = $data['repair_array'];
                           foreach ($repairArray as $value){ 
                               $status = $value['status'];
                               ?>
                                <div class="item"> <?php echo $value['description'];?></div>
                               <?php  } ?>
                      </div>
                    </div>
                  </div>
                     <div class="content">
                         <div class="header">PRICE: $<?php echo $data['price'];?></div>
                     </div>
                    </div>
                     <?php }?>
                </div>
            </div>
            <div class=" ten wide column center middle">

              <?php $status = $data['status']; 
                    if($status == "IN QUEUE"){ ?>
                        <div class="ui message negative">
                            <div class="header">
                                <h3>JOB STATUS: IN QUEUE</h3>
                            </div>
                            <p>Your repair job has been queued.</p>
                        </div>
                  <?php }elseif($status == "JOB STARTED"){?>
                       <div class="ui message info">
                            <div class="header">
                                <h3>JOB STATUS: JOB HAS STARTED</h3>
                            </div>
                            <p>Your repair job has commenced.</p>
                        </div>
                      <?php }elseif($status == "WAITING FOR CUSTOMER RESPONSE"){?>
                        <div class="ui message orange">
                            <div class="header">
                                <h3>JOB STATUS: WAITING FOR CUSTOMER RESPONSE</h3>
                            </div>
                           <p>Waiting for your response pertaining the Order. Please use the chat box below to communicate.</p>
                        </div>
                     <?php }elseif($status == "JOB COMPLETED"){?>
                         <div class="ui message positive">
                            <div class="header">
                                <h3>JOB STATUS: JOB COMPLETED</h3>
                            </div>
                            <p>You repair Job has been completed.</p>
                        </div>
                 <?php }elseif($status == "CUSTOMER COLLECTED"){?>
                         <div class="ui message ">
                            <div class="header">
                                <h3>JOB STATUS: SCOOTER COLLECTED</h3>
                            </div>
                            <p>You have collected your scooter.</p>
                        </div>
                    <?php } ?> 
                   <div class="ui  ">
              
               <div class="card"> 
               <div class="content">
                <table class="ui compact celled  table " >
                        <thead class="full-width">
                            <tr>
                                <th>Status</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php
                                $query = mysqli_query($conn,"SELECT * FROM `event_table` WHERE `order_id` = '$order_id';") or die(mysqli_error($db->connect()));
                                if ($query) {
                                   $rows = mysqli_num_rows($query);
                                   if ($rows > 0) {
                                      while ($row = mysqli_fetch_array($query)) {
                                          $status_t  = $row['status'];
                                          $time = $row['time'];?>
                                            <tr>
                                              <td><?php echo $status_t;?></td>
                                               <td><?php echo $time;?></td>
                                            </tr>
                                <?php }}} ?>
                        </tbody>
                    </table>
                    </div>
                       </div>
                       <div class="ui card">
                      <div class="content">
                            <div class="ui relaxed divided list">
                              <div class="item">
                                  <div><strong>Job Description</strong> </div>
                                  <small><?php echo $data['job_description'];?></small>
                              </div>
                                <div class="item">
                                <div class="content">
                                  <div><strong>Items Left</strong> </div>
                                  <small><?php echo $data['items_left'];?></small>
                                </div>
                              </div>
                               <div class="item">
                                <div class="content">
                                   <b><small>Scooter able to power on:  <?php echo $data['able_power'];?></small></b>
                                </div>
                              </div>
                               <div class="item">
                               <div class="content">
                                   <b><small>Scooter able to Charge:  <?php echo $data['able_charge'];?></small></b>
                                </div>  
                                </div>
                                <div class="item">
                                <div class="content">
                                   <b><small>Scooter able to Move:  <?php echo $data['able_move'];?></small></b>
                                </div>  
                                </div>
                              
                            </div>
                    </div>
                    </div>
                   </div>
               
            </div>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
        <script >
            $(document).ready(function () {
                $('select.dropdown').dropdown();
            });
        </script>
        <!--Start of Tawk.to Script
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
        </script>-->
        <!--End of Tawk.to Script-->
    </body>
</html>
