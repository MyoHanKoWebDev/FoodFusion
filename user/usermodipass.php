<?php
include('usersetting.php');
//update password start
if(isset($_POST["updatePass"]))
{
    $current_pass=$_POST["currentPassword"];
    $new_pass=$_POST["newPassword"];
    $confirm_pass=$_POST["confirmPassword"];
    $sql="SELECT * FROM userfood WHERE uID=".$_SESSION['user_arr']['uID'];
    $res=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($res);
    $verify=password_verify($current_pass,$row["upassword"]);
    if($verify)
    {
        if($current_pass==$new_pass)
        {
        echo "<script>alert('New password must be a different one');</script>";
        }
        else
        {
            if($new_pass==$confirm_pass)
            {
                $new_pass1=password_hash($new_pass,PASSWORD_DEFAULT);
                $sql2="UPDATE userfood SET upassword='$new_pass1' WHERE uID=".$_SESSION['user_arr']['uID'];
                if(mysqli_query($con,$sql2))
                {
                    echo "<script>alert('Password updated successfully')
                    window.location.replace('../index.php');</script>";
                }
                else
                {
                    echo "<script>alert('Error to update a password');</script>";
                }
            }
            else
            {
              echo "<script>alert('New password and confirm password not match');</script>";
            }
        }
    }
    else
    {
        echo "<script>alert('Current password not match');</script>";
    }
}
//update password end
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Modify Password Form (initially hidden) -->
<section id="modifyPasswordForm" class="modify-password-form ">
        <div class="form-container">
            <h2>Modify Password</h2>
            <form action="usermodipass.php" method="POST">
                <div class="form-group">
                    <label for="currentPassword">Current Password:</label>
                    <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter current password" required>
                </div>

                <div class="form-group">
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>
                </div>

                <button type="submit" class="submit-btn" name="updatePass">Update Password</button>
            </form>
        </div>
    </section>
</body>
</html>
