<?php session_start();
 if (!isset($_SESSION['email'])) { 
    header('Location: ../index.php');
    exit;
    }
 header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>