<?php
session_start();
session_destroy();
$_SESSION['email'] = '';
header('Location: ../index.php');
?>