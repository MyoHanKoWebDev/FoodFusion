<?php
 if(isset($_GET["rede"]))
 {
    $rede=$_GET["rede"];
    echo $rede;
require("../connection/dbh.php");
$sql="DELETE FROM ingredient WHERE rID=$rede";
if(mysqli_query($con,$sql))
{
    $sql2="DELETE FROM recipe WHERE rID=$rede";
    if(mysqli_query($con,$sql2))
    {
        echo "<script>('Successfully Deleted')
        window.location.replace('../user/usersetting.php');</script>";
    }
    else
    {
        echo "<script>('Can't Delete')
        window.history.back();</script>";
    }
}
 }
?>