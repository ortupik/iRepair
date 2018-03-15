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

   if(!isset($_SESSION["staff_id"])){
     header("location:staff_login.php"); 
  }else{
    
   $finalArray = array();
        if(isset($_POST['status']) && isset($_POST['repair_id']) && isset($_POST['order_id']) ){
           $order_id = $_POST['order_id'];
           $status = $_POST['status'];
            $repair_id = $_POST['repair_id'];
          
           
           $editquery = mysqli_query($conn, "UPDATE repairs SET `status` = '$status' WHERE `repair_id` = $repair_id") or die(mysqli_error($db->connect()));
            if ($editquery) {
          
                if($status == 'Y'){
                   $query = mysqli_query($db->connect(), "call get_status_complete($order_id); ") or die(mysqli_error($db->connect()));
                    if ($query) {
                        $rows = mysqli_num_rows($query);
                        if ($rows > 0) {
                           if ($row = mysqli_fetch_array($query)) {
                               $completed =  $row['completed'];
                               if($completed == 1){
                                    mysqli_query($conn, "UPDATE orders SET `status` = 'JOB COMPLETED' WHERE `order_id` = $order_id") or die(mysqli_error($db->connect()));
                               }else{
                                  mysqli_query($conn, "UPDATE orders SET `status` = 'JOB STARTED' WHERE `order_id` = $order_id") or die(mysqli_error($db->connect())); 
                               }
                           }
                        }
                    }

               }else{
                  mysqli_query($conn, "UPDATE orders SET `status` = 'JOB STARTED' WHERE `order_id` = $order_id") or die(mysqli_error($db->connect())); 
               }
            }
         
         
     }
     
     if(isset($_POST['job_collected']) && isset($_GET['order_id'])){
           $order_id = $_GET['order_id'];
           mysqli_query($conn, "UPDATE orders SET `status` = 'CUSTOMER COLLECTED' WHERE `order_id` = $order_id") or die(mysqli_error($db->connect())); 
     }
     
     if(isset($_GET['order_id'])){
      $order_id = $_GET['order_id'];
     if(isset($_POST['start_job'])){
          $editquery = mysqli_query($conn, "UPDATE orders SET `status` = 'JOB STARTED' WHERE `order_id` = $order_id") or die(mysqli_error($db->connect()));
          if ($editquery) {
          }
     }
     
     if(isset($_POST['add_task']) && isset($_POST['description']) & isset($_POST['price']) && isset($_POST['handle_staff'])){
         $description = $_POST['description'];
         $price = $_POST['price'];
         $staff = $_POST['handle_staff'];
         
         $addquery = mysqli_query($conn, "INSERT INTO repairs(order_id,description,handled_by,price) VALUES($order_id,'$description','$staff',$price)") or die(mysqli_error($conn));
         if ($addquery) {
             echo '<script>alert("Successfully Added Task !");</script>';
          }
     }
     
     if(isset($_POST['delete'])){
         $repair_id = $_POST['delete'];
         $deletequery = mysqli_query($conn, " DELETE FROM `repairs` WHERE `repair_id` = $repair_id ") or die(mysqli_error($db->connect()));
         if ($deletequery) {
             echo '<script>alert("Successfully Deleted !");</script>';
          }
     }
     if(isset($_POST['edit']) && isset($_POST['edit_values'])){
         $dataArr = explode(",", $_POST['edit_values']);
         $editArray = $dataArr;
         echo "<script>$(document).ready(function () {\$('#editmodal').modal('show');});</script>";
     }
     if(isset($_POST['editComment'])){
         $repair_id_comm = $_POST['editComment'];
         echo "<script>$(document).ready(function () {\$('#editCommentsModal').modal('show');});</script>";
     }
     if(isset($_POST['edit_task']) && isset($_POST['edit_description']) && isset($_POST['edit_price']) && isset($_POST['repair_id'])){
          $description = $_POST['edit_description'];
          $price = $_POST['edit_price'];
          $repair_id = $_POST['repair_id'];
          
          $editquery = mysqli_query($conn, "UPDATE repairs SET `description` = '$description' , `price` = $price WHERE `repair_id` = $repair_id") or die(mysqli_error($db->connect()));
          if ($editquery) {
             echo '<script>alert("Successfully Edited Task !");</script>';
          }
     }
     if(isset($_POST['edit_comment']) && isset($_POST['comments']) && isset($_POST['repair_id'])){
          $comments = $_POST['comments'];
          $repair_id = $_POST['repair_id'];
          
          $editquery = mysqli_query($conn, "UPDATE repairs SET `comments` = CONCAT(`comments`,'  ', '$comments','.') WHERE `repair_id` = $repair_id") or die(mysqli_error($conn));
          if ($editquery) {
             echo '<script>alert("Successfully added comments !");</script>';
          }
     }
     
   
   $query = mysqli_query($conn, "SELECT order_id,scooter_type,`time`,status,`job_description`,`items_left`,`able_power`,`able_charge`,`able_move`,(SELECT SUM(`price`) FROM `repairs` WHERE repairs.`order_id` = `orders`.order_id) as price,(SELECT `first_name` FROM `staff` WHERE staff.`staff_id` = `orders`.staff_id) as staff_name,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as customer_name,(SELECT phone FROM `customers` WHERE customers.`customer_id` = `orders`.customer_id) as phone FROM `orders` WHERE `order_id` = $order_id ") or die(mysqli_error($db->connect()));
    if ($query) {
        $rows = mysqli_num_rows($query);
         $count = 0;
        if ($rows > 0) {
           if ($row = mysqli_fetch_array($query)) {
               $dataArray = array();
               $order_id  = $dataArray['order_id'] = $row['order_id'];
               $dataArray['scooter_type'] = $row['scooter_type'];
               $dataArray['time'] = $row['time'];
                $dataArray['status'] = $row['status'];
               $dataArray['staff_name'] = $row['staff_name'];
               $dataArray['phone'] = $row['phone'];
               $dataArray['price'] = $row['price'];
                $dataArray['job_description'] = $row['job_description'];
               $dataArray['items_left'] = $row['items_left'];
               $dataArray['able_power'] = $row['able_power'];
               $dataArray['able_charge'] = $row['able_charge'];
               $dataArray['able_move'] = $row['able_move'];
               $dataArray['customer_name'] = $row['customer_name'];
              
               $repairArray = array();
               
               $query2 = mysqli_query($conn, "SELECT `repair_id`,`description`,`status`,`price`,`handled_by`,`comments` FROM repairs WHERE order_id = $order_id ORDER BY `status` ASC  ") or die(mysqli_error($db->connect()));
                if ($query2) {
                    $rows2 = mysqli_num_rows($query2);
                     $count2 = 0;
                    if ($rows2 > 0) {
                       while ($row2 = mysqli_fetch_array($query2)) {
                           $dataArray2 = array();
                           $dataArray2['repair_id'] = $row2['repair_id'];
                           $dataArray2['price'] = $row2['price'];
                           $dataArray2['description'] = $row2['description'];
                           $dataArray2['status'] = $row2['status'];
                           $dataArray2['handled_by'] = $row2['handled_by'];
                           $dataArray2['comments'] = $row2['comments'];
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
                <a href="index.php" class="item ">Home</a>
                 <a href="index.php" class="item active">Repairs</a>
                <div class="right menu">
                  <a href="#" class="item active"><i class="grid user icon"></i> <?php echo $_SESSION["staff_name"]?></a>
                  <a href="staff_logout.php" class="item right">Logout</a>
                  </div>
                
            </div>
          </div>
        <br><br>
        <div class="ui container  grid"> 
          <div class="four wide column">

                 <div class="ui cards">
              
               <?php
                foreach ($finalArray as $data) {?>
     
                <div class="card">
                  <div class="content">
                    <div class="">
                       <span class="ui floated   header">
                         <span class="card_title">JOB #<?php echo $data['order_id'];?></span>
                         <span><h5 class="card_date" >By <?php echo $data['staff_name'];?></h5></span>
                       </span>
                    <span class="ui right floated header">
                       <?php $status = $data['status']; 
                           if($status == "IN QUEUE"){ ?>
                                 <span><h3 style="color: orangered;"><?php echo $status;?></h3></span>
                         <?php }elseif($status == "JOB STARTED"){?>
                                 <span><h3 style="color:teal;"><?php echo $status;?></h3></span>
                             <?php }elseif($status == "WAITING FOR CUSTOMER RESPONSE"){?>
                                 <span><h3 style="color: orange;"><?php echo 'IN WAITING'?></h3></span>
                            <?php }elseif($status == "JOB COMPLETED"){?> 
                                 <span><h3 style="color: green;"><?php echo 'COMPLETED';?></h3></span>
                           <?php }elseif($status == "CUSTOMER COLLECTED"){?> 
                                 <span><h3 style="color: grey;"><?php echo 'COLLECTED';?></h3></span>
                           <?php } ?>        
                        <span><h5 class="card_date" ><?php echo $data['time'];?></h5></span>
                    </span>
                    </div>
                  </div>
                    
                 
                    <div class="content">
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
                    <div class="header">Repairs</div>
                    <div class="description">
                      <div class="ui  list">
                         <?php
                          $repairArray = $data['repair_array'];
                           foreach ($repairArray as $value){ 
                               $status = $value['status'];
                               if($status == "Y"){?>
                                <div class="item"> <i class="check green icon"></i><?php echo $value['description'];?></div>
                               <?php }else{?>
                                 <div class="item"> <i class="cancel red icon"></i><?php echo $value['description'];?></div>
                               <?php } } ?>
                      </div>
                    </div>
                  </div>
                     <div class="content">
                         <div class="header">PRICE: $<?php echo $data['price'];?></div>
                     </div>
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
                <?php }?>
           </div>
            </div>
                <div class=" twelve wide column">
                   <?php $status = $data['status']; 
                    if($status == "IN QUEUE"){ ?>
                        <div class="ui message negative">
                            <div class="header">
                                <h3>JOB STATUS: JOB HAS NOT STARTED</h3>
                            </div>
                            <p>Click button below to Start the JOB.</p>
                            <form method="POST" action="more_details.php?order_id=<?php echo $order_id;?>"><input class="right  ui button green compact" name="start_job" type="submit" value="START JOB"/></form>
                        </div>
                  <?php }elseif($status == "JOB STARTED"){?>
                       <div class="ui message info">
                            <div class="header">
                                <h3>JOB STATUS: JOB HAS STARTED</h3>
                            </div>
                            <p>Mark each task as complete to finish job.</p>
                        </div>
                      <?php }elseif($status == "WAITING FOR CUSTOMER RESPONSE"){?>
                        <div class="ui message orange">
                            <div class="header">
                                <h3>JOB STATUS: WAITING FOR CUSTOMER RESPONSE</h3>
                            </div>
                           <p>Waiting for customer response pertaining the Order.</p>
                           <button class="right  ui button orange compact" name="save" type="submit" ><i class="icon chat white"></i>GO TO MESSAGES</button>
                        </div>
                     <?php }elseif($status == "JOB COMPLETED"){?>
                         <div class="ui message positive">
                            <div class="header">
                                <h3>JOB STATUS: JOB COMPLETED</h3>
                            </div>
                            <p>Job has been completed.</p>
                             <form method="POST" action="more_details.php?order_id=<?php echo $order_id;?>"><input class="right  ui button teal compact" name="job_collected" type="submit" value="MARK CUSTOMER COLLECTED"/></form>
                        </div>
                    <?php }elseif($status == "CUSTOMER COLLECTED"){?>
                         <div class="ui message ">
                            <div class="header">
                                <h3>CUSTOMER COLLECTED</h3>
                            </div>
                            <p>Customer has collected the scooter</p>
                        </div>
                    <?php } ?>
                    <?php if($status == "CUSTOMER COLLECTED"){?>
                           <fieldset disabled class="field_set">
                      <?php }else{?>
                         <fieldset  class="field_set">
                       <?php } ?>
                    <table class="ui compact celled  table " >
                        <thead class="full-width">
                            <tr>
                                <th>Task</th>
                                <th>Handled By</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Comments</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                          foreach ($finalArray as $data) {
                             $repairArray = $data['repair_array'];
                             
                             foreach ($repairArray as $value){ 
                                  
                               $repair_id = $value['repair_id'];
                               $status_t = $value['status'];
                               $price = $value['price'];
                               $description = $value['description'];
                               $handled_by = $value['handled_by'];
                               $comments = $value['comments'];
                               ?>
                            <?php if($status_t == 'N'){?>
                                    <tr class="negative">
                                  <?php }else{?>
                                      <tr class="positive">
                                   <?php }?>  
                                <td><?php echo $description;?></td>
                                <td><b><?php echo $handled_by;?></b></td>
                                <?php if($status_t == 'N'){?>
                                        <td ><div><i class="icon close red"></i> Pending </div>
                                            <br>
                                          <div class="ui fitted slider checkbox">
                                              <input type="checkbox" class="status_check" id="<?php echo $repair_id;?>"> <label></label>
                                         </div>
                                <?php }else{?>
                                      <td ><div><i class="icon check green"></i> Completed </div>
                                          <br>
                                          <div class="ui fitted slider checkbox">
                                               <input type="checkbox" checked="" class="status_check" id="<?php echo $repair_id;?>"> <label></label>
                                         </div>
                                <?php }?>     
                                   
                                  </td>
                                  <td><b>$<?php echo $price;?></b></td>
                            <form method="POST" action="?order_id=<?php echo $order_id;?>">
                               <?php $editVal = implode(",", $value);?>
                                <input name="edit_values" type="text" value="<?php echo $editVal;?>" hidden=""/>
                                <td><small><?php echo $comments;?></small><button class="ui  teal compact "type="submit" name="editComment" value="<?php echo $repair_id;?>" >Edit</button></td>
                                <td ><button class="ui button teal compact "type="submit" name="edit" ><i class="icon edit"></i></button> </td>
                                <td ><button class="ui button red compact" type="submit" name="delete" value="<?php echo $repair_id;?>"><i class="icon delete"></i></button> </td>
                            </form>
                            </tr>
                          <?php }}?>
                            
                        </tbody>
                         <?php if($status != "CUSTOMER COLLECTED"){?>
                            <tfoot class="full-width">
                                <tr>
                                    <th colspan="2">
                                    <div class="ui right floated small primary labeled icon button" id="addTaskBtn">
                                        <i class="add icon"></i> Add Task
                                    </div>
                                  </th>
                                  <th colspan="2"><p>Slide the Slider to Change Task Status</p></th>
                                  <th colspan="3">
                                  
                                  </th>
                                 </th>
                            </tr>
                            </tfoot>
                            <?php } ?>
                    </table>
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
                                          $status  = $row['status'];
                                          $time = $row['time'];?>
                                            <tr>
                                              <td><?php echo $status;?></td>
                                               <td><?php echo $time;?></td>
                                            </tr>
                                <?php }}} ?>
                        </tbody>
                    </table>
                    </fieldset>
                   
                   
                </div>
              <div class="ui modal" id="editCommentsModal">
                <div class="header"><i class="add icon"></i> Add Comments</div>
                <div class="content">
                    <form method="POST" action="more_details.php?order_id=<?php echo $order_id;?>">
                    <div class="ui form">  
                        <div class="ui segments ">
                            <input name="repair_id" id="repair_id" value="<?php echo $repair_id_comm?>"  type="text" required="" hidden="">
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Add Comments</label>
                                    <div class="  fields">
                                        <div class="field fifteen wide">
                                            <textarea name="comments" type="text" required="" rows="2"></textarea>
                                        </div>
                                    </div>
                                   <input class="right  ui button teal compact" name="edit_comment" type="submit" value="Add Comments"/>
                                </div>
                            </div>
                         
                        </div>
                    </div>
                </form>
                </div>
                 <div class="actions">
                    <div class="ui cancel button compact">Cancel</div>
                  </div>
              </div>
             <div class="ui modal" id="addmodal">
                <div class="header"><i class="add icon"></i> Add New Task</div>
                <div class="content">
                    <form method="POST" action="more_details.php?order_id=<?php echo $order_id;?>">
                    <div class="ui form">  
                        <div class="ui segments ">
                         
                            <div class="ui segment">
                                <div class="field seven wide">
                                 <label>Select Staff to handle task</label>
                                  <select class="ui " name="handle_staff" >  
                                     <?php
                                     $query = mysqli_query($conn,"SELECT `first_name`,`last_name` FROM `staff`;") or die(mysqli_error($db->connect()));
                                     if ($query) {
                                        $rows = mysqli_num_rows($query);
                                        if ($rows > 0) {
                                           while ($row = mysqli_fetch_array($query)) {
                                               $name  = $row['first_name'].'  '.$row['last_name'];?>
                                                <option value="<?php echo $name;?>"><?php echo $name;?></option>
                                     <?php }}} ?>
                                </select>
                                </div>
                            </div>
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Task Description</label>
                                    <div class="  fields">
                                        <div class="field fifteen wide">
                                            <input name="description"  placeholder="Description" type="text" required="">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ui segment  ">
                                <div class="field ">
                                    <label>Price:</label>
                                    <div class="  fields">
                                        <div class="field seven wide">
                                            <input name="price"  placeholder="e.g $50" type="text" required="">
                                        </div>

                                    </div>
                                </div>
                                <input class="right  ui button teal compact" name="add_task" type="submit" value="Add Task"/>
                            </div>
                            


                        </div>
                    </div>
                </form>
                </div>
                 <div class="actions">
                    <div class="ui cancel button compact">Cancel</div>
                  </div>
              </div>
        </div>

         <script >
           $(document).ready(function () {
             $('select.dropdown').dropdown();
             $("#addTaskBtn").on("click",function(){
                 $('#addmodal').modal('show')
             });
          $('.status_check').change(function() {
                $isChecked = $(this).is(':checked'); 
                var status = "N";
                var id = $(this).prop("id");
                if($isChecked){
                    status = "Y";
                }else{
                    status = "N";
                }
               
                var order_id  = <?php echo $_GET['order_id'];?>; 
                $.post("more_details.php",{status:status,repair_id:id,order_id:order_id}, function(data, status){
                   location.href = "more_details.php?order_id=<?php echo $order_id;?>";
                });
               
              //  location.href = "more_details.php?update_status=&order_id=<?php echo $order_id;?>&status='"+status+"'&repair_id='"+id+"'";
            });
          });
        </script>
    </body>
</html>
