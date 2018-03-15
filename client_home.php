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

if(!isset($_SESSION["customer_id"])){
     header("location:user_login.php"); 
  }else{
     
   $finalArray = array();
   
    $customer_id = $_SESSION['customer_id'];
    $user_name = $_SESSION['user_name'];
   
    $query = mysqli_query($db->connect(), "SELECT order_id,scooter_type,`status`,`time`,(SELECT SUM(`price`) FROM `repairs` WHERE repairs.`order_id` = `orders`.order_id) as price,(SELECT `first_name` FROM `staff` WHERE staff.`staff_id` = `orders`.staff_id) as staff_name,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as customer_name,(SELECT phone FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as phone FROM `orders` WHERE `customer_id` = $customer_id; ") or die(mysqli_error($db->connect()));
    if ($query) {
        $rows = mysqli_num_rows($query);
         $count = 0;
        if ($rows > 0) {
           while ($row = mysqli_fetch_array($query)) {
               $dataArray = array();
               $order_id  = $dataArray['order_id'] = $row['order_id'];
               $dataArray['scooter_type'] = $row['scooter_type'];
               $dataArray['time'] = $row['time'];
               $dataArray['staff_name'] = $row['staff_name'];
               $dataArray['phone'] = $row['phone'];
               $dataArray['price'] = $row['price'];
                $dataArray['status'] = $row['status'];
               $dataArray['customer_name'] = $row['customer_name'];
              
               $finalArray[$count] = $dataArray;
               $count++;
               
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
                <a href="client_home.php" class="item active">Home</a>
                 <a href="edit_user.php" class="item ">Edit My Profile</a>

                <div class="right menu">
                  <a href="#" class="item active"><i class="grid user icon"></i> <?php echo $_SESSION['user_name']; ?></a>
                  <a href="client_logout.php" class="item right">Logout</a>
                  </div>
                
            </div>
          </div>
          <br>
       <div class="ui container"> 
           <?php  if($count <= 0 ){ ?>
          <div class="ui message ">
                    <div class="header">
                        <h3>Welcome Dear Client</h3>
                    </div>
                    <p>You have no order yet, please visit our shop for repair of your scooter!</p>
                </div>
           <?php }?>
           <br>
           <div class="ui cards">
              
               <?php
                foreach ($finalArray as $data) {?>
     
                <div class="card">
                  <div class="content" >
                       <span class="ui floated   header">
                         <span class="card_title">JOB ID #<?php echo $data['order_id'];?></span>
                         </span>
                    <span class="ui right floated header" >
                        <?php $status = $data['status']; 
                           if($status == "IN QUEUE"){ ?>
                                 <span><h3 style="color: orangered;"><?php echo $status;?></h3></span>
                         <?php }elseif($status == "JOB STARTED"){?>
                                 <span><h3 style="color: #4d82cb;"><?php echo $status;?></h3></span>
                             <?php }elseif($status == "WAITING FOR CUSTOMER RESPONSE"){?>
                                 <span><h3 style="color: #ffcc00;"><?php echo 'IN WAITING'?></h3></span>
                            <?php }elseif($status == "JOB COMPLETED"){?> 
                                 <span><h3 style="color: green;"><?php echo 'COMPLETED';?></h3></span>
                             <?php }elseif($status == "CUSTOMER COLLECTED"){?> 
                                 <span><h3 style="color: grey;"><?php echo 'COLLECTED';?></h3></span>     
                           <?php } ?> 
                    </span>
                      <br> <div><?php echo $data['time'];?></div>

                  </div>
                    <div class="content" >
                            <div class="ui relaxed divided list"> 
                             
                              <div class="item">
                                <div class="content">
                                  <strong>Scooter Type:</strong>  <?php echo $data['scooter_type'];?>
                                </div>
                              </div>
                              
                            </div>
                    </div>
                    
                     <div class="content">
                         
                         <div class="header">PRICE: $<?php echo $data['price'];?></div>
                     </div>
                    
                    <form method="GET" action="client.php" class=" ui bottom compact   icon button compact" >
                        <button class="ui  button compact" type="submit" style="background-color: 80cbc4; " name="order_id" value="<?php echo $data['order_id'];?>"> <i class="add icon"></i> More Details</button>
                    </form>
                    
                 
                </div>
                <?php }?>
           </div>
      
       </div>
    
         
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
        <!-- <script type="text/javascript">
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

    </body>
</html>
