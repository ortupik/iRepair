<?php
   require_once dirname(__FILE__) . '/db/core.php';

if(isset($_SESSION['admin_id'])){
    session_unset($_SESSION['admin_id']);
    session_destroy();
    header("location:admin_login.php");
}
