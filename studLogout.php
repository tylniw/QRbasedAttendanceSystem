<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["pw"]);
header("Location:studlogin.php");
?>
