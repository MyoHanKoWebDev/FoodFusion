<?php
    $dbname="localhost";
    $username="root";
    $password="";
    $database="project_ddd"; 

    $con=mysqli_connect($dbname,$username,$password,$database);
    if(!$con){
        die("Can't Connect To Database :". mysqli_connect_error($con));
    }

?>