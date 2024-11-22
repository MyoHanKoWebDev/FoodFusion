<?php
    require('connection/dbh.php');
    include("auth.php");
    session_start();
    session_destroy();
    session_unset();
    header("Location:index.php");
?>