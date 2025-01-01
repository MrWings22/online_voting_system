<?php
session_start();
header("Location: candidate_login.php");
exit();
session_destroy();
?>
