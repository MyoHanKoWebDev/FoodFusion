<?php
    // session_start();
    include ("connection/dbh.php");
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
<?php include 'header1.php'; ?>
<!-- Register Form Modal -->
  
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegister">&times;</span>
        <h2>Register</h2>
        <form  id="registForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name"  required>
                <?php if(isset($nres) && $nres!=""){ ?>
                    <span class="er_name er"><?php echo $nres; ?></span> 
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <?php if(isset($eres) && $eres!=""){ ?>
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
                <?php if(isset($pres) && $pres!=""){ ?>
                    <span class="er_pass er"><?php echo $pres; ?></span>
                <?php } ?>
                </div>
               
            </div>

            <div class="form-group">
                <label for="conpassword">Confirm Password</label>
                <input type="password" id="conpassword" name="conpassword" placeholder="Enter your Confirm password" required>
                <input type="checkbox" onclick="togglePassword('conpassword')"> Show ConfirmPassword
                <div>
                <?php if(isset($conpres) && $conpres!=""){ ?>
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
<section class="community-cookbook">
    <div class="left-content">
        <section class="recipe-explore">
            <h1>Explore Recipes</h1>
            <div class="filter-bar">
                <a href="community_cookbook.php">All</a>
                <?php
            $etersql = "SELECT * FROM eatertype";
            $etres = mysqli_query($con, $etersql);

            if (mysqli_num_rows($etres) > 0) {
                while ($etrow = mysqli_fetch_assoc($etres)) {
                    $selected = isset($_GET['eatertype']) && $_GET['eatertype'] == $etrow['etID'] ? 'active' : '';
                    echo "<a href='community_cookbook.php?eatertype={$etrow['etID']}'>{$etrow['etperson']}</a>";
                }
            }
            ?>
            </div>

            <div class="recipe-grid">
                <!-- Recipe Cards -->
    <?php
        $eaterid = $_GET['eatertype'] ?? '';

        // Updated SQL query to join recipe_ratings table for rating values
        $sqlcom = "SELECT recipe.*, eatertype.etperson
                FROM recipe 
                LEFT JOIN eatertype ON eatertype.etID= recipe.etID
                WHERE 1";

        if ($eaterid) {
            $sqlcom .= " AND eatertype.etID = '$eaterid'";
        }

        $sqlcom .= " ORDER BY recipe.rmadeAt DESC";  // Order by creation date

        $comres = mysqli_query($con, $sqlcom);

        if (mysqli_num_rows($comres) > 0) {
            while ($comrow = mysqli_fetch_assoc($comres)) {
    ?>
                <div class="recipe-card">
                    <div class="imgcomcon">
                        <img src="images/<?php echo $comrow['rimage'] ?>" alt="<?php echo $comrow['rtitle'] ?>" class="recipe-img">
                    </div>
                    <div class="recipe-details">
                        <h3><?php echo $comrow['rtitle'] ?></h3>
                        <p><strong>Eater Type:</strong> <?php echo $comrow['etperson'] ?></p>
                <?php
                $nsql="SELECT uname,uimage FROM userfood WHERE uID=".$comrow['uID'];
                $res=mysqli_query($con,$nsql);
                if(mysqli_num_rows($res)>0){
                $nrow=mysqli_fetch_assoc($res);
                ?>
                        <div class="user-profilecom" onclick="myfun3(<?php echo $comrow['uID']; ?>)">
                            <div class="overlay1">
                                <div class="text1">View Profile</div>
                            </div>
                            <img src="images/<?php echo $nrow['uimage']; ?>" alt="User Profile">
                            <span><?php echo $nrow['uname']; ?></span>
                        </div>
                        <?php } ?>
                        <div class="stats">
                        <?php
                            $sqltousercm="SELECT count(*) as total_cm
                            FROM comment 
                            WHERE comment.rID=".$comrow['rID'];
                            $restousercm=mysqli_query($con,$sqltousercm);  
                            $rowtousercm=mysqli_fetch_assoc($restousercm);                   
                        ?>
                            <span class="comspan1" onclick="showCommentModal(<?php echo $comrow['rID']; ?>)"><i class="bi bi-chat"></i> <?php echo $rowtousercm['total_cm']; ?></span>
                            <span class="comspan1" onclick="myfun2(<?php echo $comrow['rID'] ?>)">Recipe Details</span>
                        </div>
                    </div>
                    
                </div>
                  <!-- Modal Structure -->
<div id="commentModal<?php echo $comrow['rID']; ?>" class="modal1">
    <div class="modal-content1">
        <span class="close1" onclick="closeCommentModal(<?php echo $comrow['rID'] ?>)">&times;</span>
        <h2>Comments</h2>
        <div class="comment-section1">
        <?php
            $sqlusercm="SELECT userfood.*,comment.* 
            FROM comment 
            LEFT JOIN userfood ON comment.uID = userfood.uID
            WHERE comment.rID=".$comrow['rID'];
            $resusercm=mysqli_query($con,$sqlusercm);
            $countcm=0;
            if(mysqli_num_rows($resusercm)>0){
                while($rusercm=mysqli_fetch_assoc($resusercm)){        
                if($rusercm['upassword']==Null){ 
            ?>
            <div class="comment1">
                <img src="images/visitor.jpg" alt="<?php echo $rusercm['uname']; ?>" class="comment-profile-pic2">
                <div class="comment-content1">
                    <strong><?php echo $rusercm['uname']; ?> <span class="cmvis">(Visistors)</span></strong>
                    <p><?php echo $rusercm['content']; ?></p>
                    <span class="comment-time1"><?php echo $rusercm['wroteDate']; ?></span>
                </div>
            </div>
            <?php   }else { ?>
            <div class="comment1">
                <img src="images/<?php echo $rusercm['uimage']; ?>" alt="<?php echo $rusercm['uname']; ?>" class="comment-profile-pic2">
                <div class="comment-content1">
                    <strong><?php echo $rusercm['uname']; ?> <span class="cmau">(Member)</span></strong>
                    <p><?php echo $rusercm['content']; ?></p>
                    <span class="comment-time1"><?php echo $rusercm['wroteDate']; ?></span>
                </div>
            </div>
            <?php }
            $countcm++;
             }
             ?>
                <p class="cmhead"><?php echo $countcm." comments"?> <i class="bi bi-chat-dots" id="cmhead1"></i></p>
            <?php
            }else{
                ?>
                <p class="cmhead"><?php echo " 0 comments"?> <i class="bi bi-chat-dots" id="cmhead1"></i></p>
            <?php
            }
            ?>
            <!-- Add more comments as needed -->
           
            <!-- Comment Input -->
            <div class="add-comment1">
                <img src="images/<?php echo $comrow['rimage'] ?>" alt="<?php echo $comrow['rtitle'] ?>" class="comment-profile-pic2">
                <!-- <input type="text" placeholder="Write a comment..." class="comment-input1"> -->
                <button class="comment-btn1" onclick="myfun2(<?php echo $comrow['rID'] ?>)">Click To Comment</button>
            </div>
        </div>
    </div>
</div>          
  
    <?php }
        }else {
            echo "<p class='text-center'>No recipes found.</p>";
        }
        ?>
            </div>
        </section>
    </div>

    <div class="right-content">
        <aside class="sidebar">
            <h3>Users with Most Rating Recipe</h3>
            <?php
            $sqlavg="SELECT recipe.*, userfood.*,
                    (SELECT AVG(rating.rating) FROM rating WHERE rating.rID = recipe.rID) as avg_rating 
                    FROM recipe 
                    LEFT JOIN userfood ON recipe.uID = userfood.uID 
                    ORDER BY avg_rating DESC
                    LIMIT 5";
            $resavg=mysqli_query($con,$sqlavg);
            if(mysqli_num_rows($resavg)>0){
                while($rowavg=mysqli_fetch_assoc($resavg)){
            ?>
            <div class="comment-box1" onclick="myfun3(<?php echo $rowavg['uID'] ?>)">
                <img src="images/<?php echo $rowavg['uimage'] ?>" alt="Profile" class="comment-profile-pic1" >
                        <div class="comment-content3">
                            <h4 class="comment-username1"><?php echo $rowavg['uname'] ?></h4>
                            <p class="comment-text1"><?php echo $rowavg['rtitle'] ?></p>
                        </div>
            </div>
            <?php }
            } ?>
            
        </aside>
        <a href="user/usersetting.php"  class="share-recipe-btn">Share Your Recipe</a>
        <p>Got a recipe you love? Share it with the FoodFusion community and inspire others to cook your favorite dish!</p>
    </div>
</section>
    
<?php 
// Check if the "cookieAccepted" cookie is set
if (!isset($_COOKIE['cookieAccepted'])) {
    // If the cookie is not set, display the consent banner
    $showCookieConsent = true;
} else {
    // If the cookie is set, don't display the consent banner
    $showCookieConsent = false;
}
if ($showCookieConsent): ?>
<div id="cookieConsent" class="cookie-consent">
<div class="cookie-content">
        <h3>We Use Cookies</h3>
        <p>
            Our website uses cookies to ensure that you get the best experience. These cookies help us:
            <ul>
                <li>Ensure that the website functions properly and securely.</li>
                <li>Remember your preferences and settings on future visits.</li>
                <li>Provide personalized content and recommendations based on your interests.</li>
                <li>Analyze site traffic and usage to improve performance and features.</li>
            </ul>
            By clicking "Accept", you agree to the use of cookies that enhance your experience and improve our website’s services.
        </p>
        <button id="acceptCookie" class="accept-cookie-btn">Accept Cookies</button>
    </div>
</div>
<?php endif; ?>

    <?php include 'footer.php'; ?>
    <script>
        function myfun2(rid) {
            window.location.href = "recipeDetail.php?rid=" + rid;
        }
        function myfun3(uid) {
            window.location.href = "cookbookuser.php?uid=" + uid;
        }

        // Show the modal
function showCommentModal(rID) {
    document.getElementById("commentModal" + rID).style.display = "block";
}

function closeCommentModal(rID) {
    document.getElementById("commentModal" + rID).style.display = "none";
}
document.addEventListener("DOMContentLoaded", function() {
    const menuItems = document.querySelectorAll('.recipe-card');
    menuItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('appear');
        }, index * 300); // Adjust delay for desired effect
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const menuItems = document.querySelectorAll('.comment-box1');
    menuItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('appear');
        }, index * 300); // Adjust delay for desired effect
    });
});
function togglePassword(inputId) {
        var inputField = document.getElementById(inputId);
        if (inputField.type === "password") {
            inputField.type = "text";
        } else {
            inputField.type = "password";
        }
    }
        
    </script>
</body>
</html>