<?php
   require_once dirname(__FILE__) . '/db/core.php';

if(isset($_SESSION['customer_id'])){
    session_unset($_SESSION['customer_id']);
    session_destroy();
    header("location:user_login.php");
}
