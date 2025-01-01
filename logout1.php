<?php
session_start();
header("Location: login.php");
exit();
session_destroy();
?>