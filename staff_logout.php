<?php
   require_once dirname(__FILE__) . '/db/core.php';

if(isset($_SESSION['staff_id'])){
    session_unset($_SESSION['staff_id']);
    session_destroy();
}
 header("location:staff_login.php");