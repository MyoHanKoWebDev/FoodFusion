<?php
    include("usersetting.php");

    if(isset($_POST["updateProfile"]) )
    {
        $upname=$_POST["name"];
        $upemail=$_POST["email"];
        $sqli="SELECT uimage FROM userfood WHERE uID=".$_SESSION["user_arr"]["uID"];
        $resi=mysqli_query($con,$sqli);
        if(mysqli_num_rows($resi)>0)
        {
        while($rowi=mysqli_fetch_assoc($resi))
        {
            $oldimage=$rowi["uimage"];
        }
        $oldimagepath="../images/".$oldimage;
        $file=$_FILES["profilepic"];
        $newpic=$_FILES["profilepic"]["name"];
        $filetmpName=$_FILES["profilepic"]["tmp_name"];
        $fileExt=explode(".",$newpic);
        $fileActExt=strtolower(end($fileExt));
        $arr=array("png","jpeg","jpg","pdf","webp","avif");
        if(in_array($fileActExt,$arr))
        {
            $filenewName=uniqid('',true).".".$fileActExt;
            $destination="../images/".$filenewName;
            move_uploaded_file($filetmpName,$destination);

            $sql="UPDATE userfood SET uname='$upname',uemail='$upemail',uimage='$filenewName' WHERE uID=".$_SESSION['user_arr']['uID'];
            $check=mysqli_query($con,$sql);
            if ($check) {
                //Old pic
                unlink($oldimagepath);
                echo "<script>alert('Profile updated successfully');</script>";
                // header("Location: ../index.php");
            }
            else
            {
                mysqli_error($con);
                echo "<script>alert('Something Went Wrong. Please try again');</script>";
            }
        }
    }
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    $sql="SELECT * FROM userfood WHERE uID=".$_SESSION["user_arr"]["uID"];
    $res=mysqli_query($con,$sql);
    if(mysqli_num_rows($res)>0)
    {
        while($row=mysqli_fetch_assoc($res))
        {
    
?>
    <!-- Manage Profile Form (hidden by default) -->
<section id="manageProfileForm" class="manage-profile-form "    >
        <div class="form-container">
            <h2>Manage Profile</h2>
            <form action="usermodipro.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo $row['uname']; ?>" required >
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo $row['uemail']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="profilePic">Upload Profile Image:</label>
                    <input type="file" id="profilePic" name="profilepic" >
                </div>

                <button type="submit" class="submit-btn" name="updateProfile">Update Profile</button>
            </form>
        </div>
    </section>
    <?php
    }
}   
?>
</body>
</html>

    