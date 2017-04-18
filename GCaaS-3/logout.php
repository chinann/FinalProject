<?php
session_start();
unset($_SESSION['roleID']);
unset($_SESSION["username"]);
unset($_SESSION['userID']);
header("Location:http://".$_SESSION['host']."/GCaaS-3/index.php");
?>