<?php
 $nres="";
 $eres="";
 $pres="";
 $conpres="";
 if(isset($_POST["submit"]))
 {
     $name=$_POST["name"];
     $email=$_POST["email"];
     $pass=$_POST["password"];
     $hash1=password_hash($pass,PASSWORD_DEFAULT);
     $conpass=$_POST["conpassword"];
     $hash2=password_hash($conpass,PASSWORD_DEFAULT);
     $file=$_FILES["file"];
     $filename=$_FILES["file"]["name"];
     $fileerror=$_FILES["file"]["error"];
     $filetype=$_FILES["file"]["type"];
     $filetmpName=$_FILES["file"]["tmp_name"];
     $date=date("Y-m-d H:i:s");
     $valid=true;
     
         //name start
         $namepattern="/^[A-Z][a-z]*( [A-Z][a-z]+)*$/";
         if(strlen($name)==0)
         {
             $nres="Please fill name field";
             $valid=false;
         }
         else if(strlen($name)<5)
         {
             $nres="Name must contain at least 5 character";
             $valid=false;
         }
         else if(!preg_match($namepattern,$name))
         {
             $nres="Field name not valid field name contains alphabets with small and capital letter.";
             $valid=false;
         }
         //name end            

         //email start

         $emailpattern="/^(.+)@(gmail.com)$/";
         if(strlen($email)==0)
         {
             $eres="Please fill email field";
             $valid=false;
         }
         else if(strlen($email)<5)
         {
             $eres="Name must contain at least 5 character";
             $valid=false;
         }
         else if(!preg_match($emailpattern,$email))
         {
             $eres="Field email not valid format that must be end with @gmail.com";
             $valid=false;
         }
         //email end

         //password start
         
         $passpattern="/^((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{6,})$/";
         if(strlen($pass)==0)
         {
             $pres="Please fill password field";
             $valid=false;
         }
         else if(strlen($pass)<6)
         {
             $pres="Password field atleast 6 characters";
             $valid=false;
         }
         else if(!preg_match($passpattern,$pass))
         {
             $pres="Passowrd must include number, capital and small letter";
             $valid=false;
         }
         //password end

         
         //confirm password start
         
         if(strlen($conpass)==0)
         {
             $conpres="Please fill password field";
             $valid=false;
         }
         else if($pass!=$conpass)
         {
             $conpres="Password and Confirm password not match";
             $valid=false;
         }

         //confirm password end
 
         $fileExt=explode(".",$filename);
         $fileActExt=strtolower(end($fileExt));
 
         $arr=array("png","jpeg","jpg","pdf","webp");
        
         if($valid==true)
         {
             if(in_array($fileActExt,$arr))
             {
             if($fileerror == 0)
             {                 
                     $filenewName=uniqid('',true).".".$fileActExt;
                     $destination="images/".$filenewName;
                     move_uploaded_file($filetmpName,$destination);
                     $sqlcheck="SELECT * FROM userfood WHERE uname='$name' AND uemail='$email' ";
                     $res=mysqli_query($con,$sqlcheck);
                     if(mysqli_num_rows($res)>0){
                         echo "<script>alert('Already exists name or email.');</script>";
                     }
                     else{
                         $sql="INSERT INTO userfood (uname,uemail,upassword,uimage,ujoinDate) VALUES ('$name','$email','$hash1','$filenewName','$date')";
                         $check=mysqli_query($con,$sql);
                         if($check){
                             echo "<script>alert('Successfully Register You can login');</script>";
                         }
                         else{
                             echo "<script>alert('Something Wrong');</script>";
                         }
                     
                     }
             }
             else{
                 echo "<script>alert('Therer was an error uploading you file');</script>";
             }   
             }
         else{
             echo "<script>alert('Invalid Format. Only jpg/jpeg/png/webp/pdf format allowed');</script>";
         }
     }else{
         echo "<script>alert('All fields must correct');</script>";
     }   
 }    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Fusion - Community</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/recipe.css">
    <link rel="stylesheet" href="css/cookbook.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="js/recipe.js"></script>
    <script src="js/script.js"></script>
</head>
<body class="bodyback">
<?php include 'nav.php'; ?>
<!-- Register Form Modal -->
  
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegister">&times;</span>
        <h2>Register</h2>
        <form id="registForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name"  required>
                <?php if ($nres) { ?>
                    <span class="er_name er"><?php echo $nres; ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <?php if ($eres) { ?>
                    <span class="er_email er"><?php echo $eres; ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <input type="checkbox" onclick="togglePassword('password')" class="shwps"> Show Password
                <div class="showpassformat">
                    <span>At lest 6 characters with only one capital & small letter and number included</span>
                </div>      
                <div>
                <?php if ($pres) { ?>
                    <span class="er_pass er"><?php echo $pres; ?></span>
                <?php } ?>
                </div>
               
            </div>

            <div class="form-group">
                <label for="conpassword">Confirm Password</label>
                <input type="password" id="conpassword" name="conpassword" placeholder="Enter your Confirm password" required>
                <input type="checkbox" onclick="togglePassword('conpassword')"> Show ConfirmPassword
                <div>
                <?php if ($conpres) { ?>
                    <span class="er_pass er"><?php echo $conpres; ?></span>
                <?php } ?>
                </div>
            </div>

            <div class="form-group">
                <label for="profilepic">Profile Picture</label>
                <input type="file" id="profilepic" name="file" >
            </div>

            <button type="submit" name="submit" value="Upload">Register</button>

            <div class="form-group switch-container">
                <span>Do you have an account?</span>
                <a href="#" id="switchToLogin">Login here</a>
            </div>
        </form>
    </div>
</div>

    <!-- Login Form Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeLogin">&times;</span>
        <h2>Login</h2>
        <form action="connection/verify.php" method="POST">
            <div class="form-group">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="loginEmail" placeholder="Enter your email" required>
                <span></span>
            </div>

            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter your password"  required>
                <input type="checkbox" onclick="togglePassword('loginPassword')" > Show Password
                <?php if(isset($lopres) && ($lopres!="" )){ ?>
                    <span class="er_pass er"><?php echo $lopres; ?></span>
                <?php } ?>
            </div>

            <button type="submit" name="logsub">Login</button>

            <div class="form-group switch-container">
                <span>Don't you have an cccount?</span>
                <a href="#" id="switchToRegister">Register here</a>
            </div>
            <div class="form-group">
                <a href="#" id="forgotPasswordLink" class="forgetPass">Forgot Password?</a>
            </div>
        </form>
        
    </div>
</div>
<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeForgotPassword">&times;</span>
        <h2>Reset Password</h2>
        <form action="connection/verify.php" method="POST">
            <div class="form-group">
                <label for="resetEmail">Enter your email</label>
                <input type="email" id="resetEmail" name="resetEmail" placeholder="Enter your email" required>
            </div>
            <button type="submit" name="resetSubmit">Send Reset Link</button>
        </form>
    </div>
</div>

<div class="profile-card">
        <?php 
            if(isset($_GET['uid'])){
                $uid=$_GET['uid'];
                $sqlun="SELECT * FROM userfood WHERE uID=$uid";
                $resn=mysqli_query($con,$sqlun);
                $rown=mysqli_fetch_assoc($resn);
        ?>
        <div class="profile-header">
            <img src="images/<?php echo $rown['uimage']; ?>" alt="Profile Picture" class="profile-pic">
            <h2><?php echo $rown['uname']; ?></h2>
            <p>Food Fusion - Member</p>
            <!-- <p>Web Producer - Web Specialist<br>Columbia University - New York</p> -->
        </div>
        
        <div class="profile-stats">
            <div class="stat">
            <?php
        
                $sqldas="SELECT count(*) as total_recipe
                        FROM recipe
                        WHERE recipe.uID =$uid";
                $resdas=mysqli_query($con,$sqldas);
                $rowdasr=mysqli_fetch_assoc($resdas);
            ?>
                <h3><?php echo $rowdasr['total_recipe']; ?></h3>
                <p>Recipes</p>
            </div>
            <div class="stat">
            <?php
                $sqltr="SELECT sum(rating.rating) as total_star
                        FROM rating
                        INNER JOIN recipe ON rating.rID = recipe.rID
                        WHERE recipe.uID =$uid";
                $restr=mysqli_query($con,$sqltr);
                $rowtr=mysqli_fetch_assoc($restr);
            ?>
                <h3><?php echo $rowtr['total_star']; ?></h3>
                <p>Stars (Total Recipes)</p>
            </div>
            <div class="stat">
            <?php
                $sqldas="SELECT count(*) as total_comment
                        FROM comment
                        INNER JOIN recipe ON comment.rID = recipe.rID
                        WHERE recipe.uID =$uid";
                $resdas=mysqli_query($con,$sqldas);
                $rowdascm=mysqli_fetch_assoc($resdas);
            ?>
                <h3><?php echo $rowdascm['total_comment']; ?></h3>
                <p>Comments</p>
            </div>
        </div>
       
        <form action="" method="GET">
        <input type="hidden" name="uid" value="<?php echo $uid; ?>"> <!-- Preserving the uid -->
            <label for="filter-select">Sort by:</label>
            <select id="filter-select" class="btn show-more-btn" name="filter">
                <option value="" disabled selected> Rating or Latest</option>
                <option value="highestrate" <?= isset($_GET['filter']) && $_GET['filter'] == 'highestrate' ? 'selected' : '' ?>>Sort by Rating</option>
                <option value="latestcreat" <?= isset($_GET['filter']) && $_GET['filter'] == 'latestcreat' ? 'selected' : '' ?>>Sort by Latest</option>    
            </select>
        <button class="sorbtn1">Filter</button>
        </form>
        
    </div>
    <section class="menu-section">
    <div class="menu-header">
        <h1>Shared Recipes</h1>
    </div> 
    <div class="menu-container"> 
<?php
$filter = $_GET['filter'] ?? 'latestcreat';

// Updated SQL query to join recipe_ratings table for rating values
$sql1 = "SELECT recipe.*, category.cname, eatertype.etperson, 
        (SELECT AVG(rating.rating) FROM rating WHERE rating.rID = recipe.rID) as avg_rating  
        FROM recipe 
        LEFT JOIN category ON recipe.cID = category.cID 
        LEFT JOIN eatertype ON recipe.etID = eatertype.etID
        WHERE recipe.uID=$uid";

if ($filter == 'highestrate') {
    $sql1 .= " ORDER BY avg_rating DESC";  // Order by rating from recipe_ratings table
} else {
    $sql1 .= " ORDER BY recipe.rmadeAt DESC";  // Order by creation date
}

$resultre = mysqli_query($con, $sql1);

 if (mysqli_num_rows($resultre) > 0) {
    while ($row = mysqli_fetch_assoc($resultre)) {
    ?>           
        <div class="menu-item" onclick="myfun2(<?php echo $row['rID']?>)">
                <div class="overlay">
                    <div class="text">Tap To Details</div>
                </div>
                <img src="images/<?php echo $row['rimage']; ?>" alt="<?php echo $row['rtitle']; ?>" class="menu-item-image">
                <div class="menu-item-info">
                    <h4 class="menu-item-name"><?php echo $row['rtitle']; ?></h4>
                    <p><strong>Category:</strong> <?php echo $row['rtitle']; ?></p>
                    <p><strong>Eater Type:</strong> <?php echo $row['etperson']; ?></p>
                    <p><strong>Shared At:</strong> <?php echo $row['rmadeAt']; ?></p>
                </div> 
                
            </div>
            <?php  } 
    } 
    else{
        echo "<p class='rec-notfound'>No recipes shared yet.</p>";
    }
    ?>
            
    </div>   
</section>
<?php } ?>
<?php include 'footer.php'; ?>
    <script>
        function myfun2(rid) {
            window.location.href = "recipeDetail.php?rid=" + rid;
        }
        function myfun3(rid) {
            window.location.href = "cookbookuser.php?rid=" + rid;
        }

        document.addEventListener("DOMContentLoaded", function() {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('appear');
        }, index * 300); // Adjust delay for desired effect
    });
});
        
    </script>
</body>
</html>