<?php

 require_once dirname(__FILE__) . '/db/core.php';
 require_once dirname(__FILE__) . '/db/db_connect.php';

  $db = new DB_CONNECT();
  $conn = $db->connect();

  if(!isset($_SESSION["staff_id"])){
     header("location:staff_login.php"); 
  }else{
    
   $finalArray = array();
   
   $staff_id = 3;
   
   if(isset($_POST['add_job']) && isset($_POST['scooter_type']) && isset($_POST['phone'])){
        
       $phone = $_POST['phone'];
       $scooter_type = $_POST['scooter_type'];
       $queryresult = mysqli_query($db->connect(), "SELECT * FROM `customers` WHERE `phone`='$phone'  ");
        
        if ($queryresult) {
            if ( mysqli_num_rows($queryresult) <= 0) {
                
               $password = 'user101';
               $inject = "INSERT INTO `customers` (`customer_id`,`phone`,`password`)VALUES (NULL,'$phone', '$password')";
              
               if(mysqli_query($conn,$inject)){
                    $customer_id = mysqli_insert_id($conn);
                    $addquery = mysqli_query($conn, "INSERT INTO orders(scooter_type,staff_id,customer_id) VALUES('$scooter_type',$staff_id,$customer_id)") or die(mysqli_error($db->connect()));
                    if ($addquery) {
                        echo '<script>alert("Successfully Added JOB !");</script>';
                     } 
                } else{
                   echo mysqli_error($conn);
                } 
            }else{
                 $row = mysqli_fetch_array($queryresult);
                 $customer_id = $row['customer_id'];
                  $addquery = mysqli_query($conn, "INSERT INTO orders(scooter_type,staff_id,customer_id) VALUES('$scooter_type',$staff_id,$customer_id)") or die(mysqli_error($db->connect()));
                    if ($addquery) {
                        echo '<script>alert("Successfully Added JOB !");</script>';
                     } 
            }
            
           
        }
       
   }

   
   $query = mysqli_query($conn, "SELECT order_id,scooter_type,`status`,`time`,(SELECT SUM(`price`) FROM `repairs` WHERE repairs.`order_id` = `orders`.order_id) as price,(SELECT `first_name` FROM `staff` WHERE staff.`staff_id` = `orders`.staff_id) as staff_name,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as customer_name,(SELECT phone FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as phone FROM `orders`; ") or die(mysqli_error($db->connect()));
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
              
               $repairArray = array();
               
               $query2 = mysqli_query($conn, "SELECT description,`status` FROM repairs WHERE order_id = $order_id ORDER BY `status` ASC  ") or die(mysqli_error($db->connect()));
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
      
        <div class="ui   menu">
            <div class="ui container">
                
              <a href="#" class="header item">
                Scooter Repairs
              </a> 
                <a href="index.php" class="item active">Home</a>
                <div class="right menu">
                  <a href="#" class="item active"><i class="grid user icon"></i><?php echo $_SESSION["staff_name"]?></a>
                  <a href="staff_logout.php" class="item right">Logout</a>
                  </div>
                
            </div>
          </div>
          <br>
       <div class="ui container"> 
            <div class="ui container  grid"> 
            <div class=" field column seven wide">
           <div class="ui labeled icon menu ">
                <a class="item" id='new_job_btn'>
                  <i class="briefcase icon"></i>
                  Add New Job
                </a>
               <a class="item right" href="index.php">
                  <i class="list icon"></i>
                   Switch to List View
                </a>
              </div>
            </div>
           <div class=" field column seven wide">
               <label class="ui label">Filter: </label>
               <select class="dropdown" name="filter" >
                   <option>View All</option>
                   <option>In Queue</option>
                   <option>Job Started</option>
                   <option>Waiting For Customer Response</option>
                   <option>Job Completed</option>
               </select>
           </div>
           </div>
           <br><br>
           <div class="ui cards">
              
               <?php
                foreach ($finalArray as $data) {?>
     
                <div class="card">
                  <div class="content" >
                       <span class="ui floated   header">
                         <span class="card_title">JOB #<?php echo $data['order_id'];?></span>
                         <span><h5 class="card_date" >By <?php echo $data['staff_name'];?></h5></span>
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
                           <?php } ?> 
                        <span><h5 class="card_date" ><?php echo $data['time'];?></h5></span> 
                    </span>
                  </div>
                         <div class="content" >
                            <div class="ui relaxed divided list"> 
                              <div class="item">
                                  <strong>Customer:</strong> <?php echo $data['customer_name'];?>
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
                         
                         <div class="header">PRICE: $<?php echo $data['price'];?></div>
                     </div>
                    <form method="GET" action="more_details.php" class=" ui bottom compact  teal icon button compact" >
                        <button class="ui  button compact" type="submit" style="background-color: 80cbc4; " name="order_id" value="<?php echo $data['order_id'];?>"> <i class="add icon"></i> More Details</button>
                    </form>
                    
                 
                </div>
                <?php }?>
           </div>
      
       </div>
           <div class="ui modal" id="addNewJobModal">
                <div class="header"><i class="briefcase icon"></i> Add New Job</div>
                <div class="content">
                    <form method="POST" action="index.php">
                    <div class="ui form">  
                        <div class="ui segments ">
                         
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Scooter Type</label>
                                    <div class="  fields">
                                        <div class="field fifteen wide">
                                            <input name="scooter_type"  placeholder="Scooter Type" type="text" required="">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Client Phone:</label>
                                    <div style="margin-bottom: 5px;"><small style="color: grey;">New Clients will be automatically registered.</small></div>
                                    <div class="  fields">
                                        <div class="field seven wide">
                                            <input name="phone"  placeholder="e.g +123456789" type="text" required="">
                                        </div>

                                    </div>
                                </div>
                                <input class="right  ui button teal compact" name="add_job" type="submit" value="Add Job"/>
                            </div>


                        </div>
                    </div>
                </form>
                </div>
                 <div class="actions">
                    <div class="ui cancel button compact">Cancel</div>
                  </div>
              </div>  
            
         
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/semantic.min.js"></script>
        <script >
           $(document).ready(function () {
             $('.dropdown').dropdown();
          });
        </script>
        <script >
         $(document).ready(function () {
             $("#new_job_btn").on("click",function(){
                 $('#addNewJobModal').modal('show')
             });
         });
        </script>  

    </body>
</html>
