<?php

 require_once dirname(__FILE__) . '/db/core.php';
 require_once dirname(__FILE__) . '/db/db_connect.php';

  $db = new DB_CONNECT();
  $conn = $db->connect();

  if(!isset($_SESSION["staff_id"])){
     header("location:staff_login.php"); 
  }else{
    
   $finalArray = array();
   
   $staff_id = $_SESSION["staff_id"];
   
    
     
   if(isset($_POST['add_job']) && isset($_POST['scooter_type']) && isset($_POST['phone']) && isset($_POST['first_name']) && isset($_POST['last_name'])
           && isset($_POST['job_description']) && isset($_POST['able_move']) && isset($_POST['able_charge']) && isset($_POST['able_power']) && isset($_POST['items_left'])){
        
       $phone = $_POST['phone'];
       $first_name = $_POST['first_name'];
       $last_name = $_POST['last_name'];
       $scooter_type = $_POST['scooter_type'];
       $job_description = $_POST['job_description'];
       $able_move = $_POST['able_move'];
       $able_charge = $_POST['able_charge'];
       $able_power = $_POST['able_power'];
       $items_left = $_POST['items_left'];
       
        $items = implode(" & ",$items_left);
       
      $queryresult = mysqli_query($db->connect(), "SELECT * FROM `customers` WHERE `phone`='$phone'  ");
        
        if ($queryresult) {
            if ( mysqli_num_rows($queryresult) <= 0) {
                
               $password = 'user101';
               $inject = "INSERT INTO `customers` (`customer_id`,`phone`,`password`,`first_name`,`last_name`)VALUES (NULL,'$phone', '$password','$first_name','$last_name')";
              
               if(mysqli_query($conn,$inject)){
                    $customer_id = mysqli_insert_id($conn);
                  $addquery = mysqli_query($conn, "INSERT INTO orders(scooter_type,job_description,items_left,able_power,able_charge,able_move,staff_id,customer_id) VALUES('$scooter_type','$job_description','$items','$able_power','$able_charge','$able_move',$staff_id,$customer_id)") or die(mysqli_error($db->connect()));
                    if ($addquery) {
                        echo '<script>alert("Successfully Added JOB !");</script>';
                     }else{
                         echo mysqli_error($conn);
                     } 
                } else{
                   echo mysqli_error($conn);
                } 
            }else{
                 $row = mysqli_fetch_array($queryresult);
                 $customer_id = $row['customer_id'];
                  $addquery = mysqli_query($conn, "INSERT INTO orders(scooter_type,job_description,items_left,able_power,able_charge,able_move,staff_id,customer_id) VALUES('$scooter_type','$job_description','$items','$able_power','$able_charge','$able_move',$staff_id,$customer_id)") or die(mysqli_error($db->connect()));
                    if ($addquery) {
                        echo '<script>alert("Successfully Added JOB !");</script>';
                     } else{
                         echo mysqli_error($conn);
                     }
            }
           
        }
       
   }
   if(isset($_GET['filter_status'])){
   $status = $_GET['filter_status'];
   }else{
       $status = 'All';
   }
   if($status != "" && $status != "All"){
      $query = mysqli_query($conn, "SELECT order_id,scooter_type,`status`,`time`,(SELECT SUM(`price`) FROM `repairs` WHERE repairs.`order_id` = `orders`.order_id) as price,(SELECT `first_name` FROM `staff` WHERE staff.`staff_id` = `orders`.staff_id) as staff_name,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as customer_name,(SELECT phone FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as phone FROM `orders` WHERE `status` = '$status' ; ") or die(mysqli_error($db->connect()));
   }else{
          $query = mysqli_query($conn, "SELECT order_id,scooter_type,`status`,`time`,(SELECT SUM(`price`) FROM `repairs` WHERE repairs.`order_id` = `orders`.order_id) as price,(SELECT `first_name` FROM `staff` WHERE staff.`staff_id` = `orders`.staff_id) as staff_name,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as customer_name,(SELECT phone FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as phone FROM `orders` ; ") or die(mysqli_error($db->connect()));
   }
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
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/components/icon.min.css'>

    </head>
    
    <body>
      
        <div class="ui   menu">
            <div class="ui  container">
                
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
          <div class="ui    grid"> 
              <div class=" field column seven wide " >
           <div class="ui labeled icon menu middle centered  " >
                <a class="item" id='new_job_btn'>
                  <i class="briefcase icon"></i>
                  Add New Job
                </a>
                <a class="item" id='new_job_btn'>
               <select class="ui  " id="status_filter2" name="filter" value="InQueue" >  <label class=" ui compact">Filter: </label>
                   <option value=""></option>
                   <option value="All" >All</option>
                   <option value="In Queue">In Queue</option>
                   <option value="Job Started">Job Started</option>
                   <option value="Waiting For Customer Response">Waiting For Customer Response</option>
                   <option value="Job Completed">Job Completed</option>
               </select>
               <span style="margin-top: 10px;">
                   <label class="compact label  ui" disabled="">Filter: </label>  <label class=" label compact teal ui" disabled=""><?php echo $status;?></label>
               </span>
              </a>
              </div>
            </div>
          
           <br><br>
            <div class=" field column sixteen wide">
            <table class="ui compact celled  table" id="dataTable">
                <thead class="full-width">
                    <tr>
                        <th>Job ID</th>
                        <th>By</th>
                        <th>Status</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Scooter Type</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>More</th>
                    </tr>
                </thead>
                <tbody>
               <?php
                foreach ($finalArray as $data) {?>
     
               <tr>
                   <td>#<?php echo $data['order_id'];?></td>
                   <td><?php echo $data['staff_name'];?></td>
                        <?php $status = $data['status']; 
                           if($status == "IN QUEUE"){ ?>
                                 <td style="color: orangered;"><?php echo $status;?></td>
                         <?php }elseif($status == "JOB STARTED"){?>
                                 <td style="color: #4d82cb;"><?php echo $status;?></td>
                             <?php }elseif($status == "WAITING FOR CUSTOMER RESPONSE"){?>
                                 <td style="color: #ffcc00;"><?php echo 'IN WAITING'?></td>
                            <?php }elseif($status == "JOB COMPLETED"){?> 
                                 <td style="color: green;"><?php echo 'COMPLETED';?></td>
                           <?php }elseif($status == "CUSTOMER COLLECTED"){?> 
                                 <td style="color: grey;"><?php echo 'COLLECTED';?></td>
                           <?php } ?>        
                       
                         <td><?php echo $data['customer_name'];?> </td>
                         <td> <?php echo $data['phone'];?></td>
                         <td><?php echo $data['scooter_type'];?></td>
                         <td>$<?php echo $data['price'];?></td>
                          <td class="card_date" ><?php echo $data['time'];?></td> 
                         <td> <form method="GET" action="more_details.php" class=" ui  compact  teal icon  compact" >
                          <button class="ui  button compact" type="submit" style="background-color: 80cbc4; " name="order_id" value="<?php echo $data['order_id'];?>"> <i class="add icon"></i> More Details</button>
                             </form></td>
                    
            </tr>
                <?php }?>
            </tbody>
            
            </table>
            </div>
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
                                    <label>Clients Name</label>
                                    <div class="  fields">
                                        <div class="field five wide">
                                            <input name="first_name" placeholder="First Name"  type="text" required="">
                                        </div>
                                        <div class="field five wide">
                                            <input name="last_name" placeholder="Last Name" type="text" required="">
                                        </div>
                                    </div>
                                </div>
                               <div class="field ">
                                    <label>Client Phone:</label>
                                    <div style="margin-bottom: 5px;"><small style="color: grey;">New Clients will be automatically registered.</small></div>
                                    <div class="  fields">
                                        <div class="field seven wide">
                                            <input name="phone"  placeholder="e.g +123456789" type="text" required="">
                                        </div>

                                    </div>
                                </div>
                            </div>
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
                                    <label>Job Description</label>
                                    <div class="  fields">
                                        <div class="field fifteen wide">
                                            <textarea name="job_description"  rows="2" type="text" required=""></textarea>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="ui segment">
                                <div class="grouped fields">
                                    <label>Items left with us</label>
                                    <div class="field">
                                      <div class="ui  checkbox">
                                          <input name="items_left[]" value="charger"  type="checkbox" >
                                        <label>Charger</label>
                                      </div>
                                    </div>
                                    <div class="field">
                                      <div class="ui  checkbox">
                                          <input name="items_left[]" value="Alarm remote/key" type="checkbox">
                                        <label>Alarm remote/key</label>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="ui segment">
                                <h5 style="color: grey;">Scooter Condition</h5>
                                <div class="inline fields">
                                    <label>Scooter Able to power on</label>
                                    <div class="field">
                                      <div class="ui radio checkbox">
                                          <input name="able_power" value="Y" type="radio" required="">
                                        <label>Yes</label>
                                      </div>
                                    </div>
                                    <div class="field">
                                      <div class="ui radio checkbox">
                                          <input name="able_power" value="N" type="radio">
                                        <label>No</label>
                                      </div>
                                    </div>
                                    
                                  </div>
                                 <div class="inline fields">
                                    <label>Scooter Able to Move</label>
                                    <div class="field">
                                      <div class="ui radio checkbox">
                                          <input name="able_move" value="Y"  type="radio" required="">
                                        <label>Yes</label>
                                      </div>
                                    </div>
                                    <div class="field">
                                      <div class="ui radio checkbox">
                                          <input name="able_move" value="N" type="radio" required="">
                                        <label>No</label>
                                      </div>
                                    </div>
                                    
                                  </div>
                                 <div class="inline fields">
                                    <label>Scooter Able to Charge</label>
                                    <div class="field">
                                      <div class="ui radio checkbox">
                                          <input name="able_charge" value="Y" type="radio" required="">
                                        <label>Yes</label>
                                      </div>
                                    </div>
                                    <div class="field">
                                      <div class="ui radio checkbox">
                                          <input name="able_charge" value="N" type="radio" required="">
                                        <label>No</label>
                                      </div>
                                    </div>
                                    
                                  </div>
                            </div>
                            <div class="ui segment  ">
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
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>

        <script >
           $(document).ready(function () {
             $('#dataTable').DataTable();
             $('.dropdown').dropdown();
          });
        </script>
        <script >
         $(document).ready(function () {
             $("#new_job_btn").on("click",function(){
                 $('#addNewJobModal').modal('show')
             });
                $( "#status_filter2" ).change(function () {
                    var str = "";
                    $( "#status_filter2 option:selected" ).each(function() {
                       str += $( this ).text() + " ";
                    });
                  
                    $status = $.trim(str); 
                    if($status != "" ){
                        location.href="index.php?filter_status="+$status ;
                    }
                   
                     
                    
                  }).change();
            
         });
         
        </script>  

    </body>
</html>
