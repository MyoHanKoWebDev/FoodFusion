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
    <title>Food Fusion - Recipes</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/recipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="js/recipe.js"></script>
    <script src="js/script.js"></script>
</head>

<body class="bodyback">
    <?php include 'header1.php'; 
    
    function displayStars($avgRating) {
        $fullStars = floor($avgRating); // Number of full stars
        $halfStar = ($avgRating - $fullStars >= 0.5) ? true : false; // Whether there is a half star
        $emptyStars = 5 - ceil($avgRating); // Number of empty stars
        
        // Display full stars
        for ($i = 0; $i < $fullStars; $i++) {
            echo '<i class="fas fa-star" style="color: orange;"></i>'; // Full star
        }
    
        // Display half star (if applicable)
        if ($halfStar) {
            echo '<i class="fas fa-star-half-alt" style="color: orange;"></i>'; // Half star
        }
    
        // Display empty stars
        for ($i = 0; $i < $emptyStars; $i++) {
            echo '<i class="far fa-star" style="color: orange;"></i>'; // Empty star
        }
    }?>
    

    <section class="recipe-detail-section">
    <?php
    if (isset($_GET['rid'])) {
        $rid = $_GET['rid'];
                        
        $rdtsql = "SELECT * FROM recipe INNER JOIN ingredient ON recipe.rID = ingredient.rID WHERE recipe.rID = $rid";
        $result = mysqli_query($con, $rdtsql);

        if (mysqli_num_rows($result) > 0) {
            $rdtrow = mysqli_fetch_assoc($result);
    ?>
    <div class="recipe-detail-container">
        <!-- Recipe Image and Name -->
        <div class="recipe-image-container" >
            <img src="images/<?php echo $rdtrow['rimage']; ?>" alt="<?php echo $rdtrow['rtitle']; ?>" class="recipe-image" id="recipeImage">
            <h2 class="recipe-name"><?php echo $rdtrow['rtitle'] ?></h2>
        </div>

        <!-- Modal for Enlarged Image -->
        <div id="imageModal" class="modal3">
            <span class="close3">&times;</span>
            <img class="modal-content3" id="modalImage">
            
        </div>

         <!-- Recipe Creator and Date -->
         <div class="recipe-meta">
            <?php
                $nsql="SELECT uname,uimage FROM userfood WHERE uID=".$rdtrow['uID'];
                $res=mysqli_query($con,$nsql);
                if(mysqli_num_rows($res)>0){
                $nrow=mysqli_fetch_assoc($res);
            ?>
            <img src="images/<?php echo $nrow['uimage']; ?>" alt="Profile Image" class="profile-img" onclick="myfun3(<?php echo $rdtrow['uID'] ?>)">
            <p><strong>Shared by:</strong> <?php echo $nrow['uname']; ?></p>
            <?php } ?>
            <p><strong>Date:</strong> <?php echo $rdtrow['rmadeAt']; ?></p>
            <?php
           
            $sqlr = "SELECT AVG(rating) as avg_rating FROM rating WHERE rID= $rid";
            $resr = mysqli_query($con, $sqlr);
            if(mysqli_num_rows($resr)>0){
                $rowr = mysqli_fetch_assoc($resr);
                $avgRating = round($rowr['avg_rating'], 1);
            ?>
                 <p><strong>Avreage Rating: </strong><?php echo displayStars($avgRating)." ( ".$avgRating." / 5 )"; ?>  </p>
            <?php } ?>
     
        <!-- Recipe Content -->
        <div class="recipe-content">

            <!-- Left: Recipe Description -->
            <div class="recipe-description">
                <h3>Description</h3>
                <p><?php echo $rdtrow['rdescription']; ?></p>
            </div>

            <!-- Right: Recipe Ingredients and Time -->
            <div class="recipe-info">
                <h3>Ingredients</h3>
                <ul>
                <?php
                    $insql = "SELECT * FROM ingredient WHERE rID = $rid";
                    $res1 = mysqli_query($con, $insql);
                    if (mysqli_num_rows($res1) > 0) {
                        while ($inrow = mysqli_fetch_assoc($res1)) {
                ?>
                    <li><?php echo $inrow['iname'] ?></li>
                <?php
                        }
                    }
                ?>
                </ul>
                <p><strong>Preparation Time: </strong><?php echo $rdtrow['prepareTime'] ?> minutes</p>
                <p><strong>Cooking Time: </strong><?php echo $rdtrow['cookingTime'] ?> minutes</p>
                <p><strong>Available for: </strong><?php echo $rdtrow['availableEater'] ?> People</p>
            </div>
        </div>

      
<?php   }

    }    
?>
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
        <!-- Comment Section -->
        <div class="comment-section">
            <h3>Leave a Comment and Rate Recipe </h3>
            <span>(Account User Don't Need to Fill Name and Email !)</span>
            <form class="comment-form" action="connection/verify.php" method="POST">
            <?php if (isset($_SESSION['user_arr'])):
                        
                $sql="SELECT * FROM userfood WHERE uID=".$_SESSION["user_arr"]["uID"];
                $res=mysqli_query($con,$sql);
                if(mysqli_num_rows($res)>0){
                    while($row=mysqli_fetch_assoc($res))
                    {

                ?>   
                <div class="form-group1">
                    <label for="ratname">Your Name:</label>
                    <input type="text" id="ratname" name="ratname" value="<?php echo $row['uname']; ?>">
                </div>
                <div class="form-group1">
                    <label for="ratemail">Your Email:</label>
                    <input type="text" id="ratemail" name="ratemail" value="<?php echo $row['uemail']; ?>">
                </div>
            <?php   }
                }?>
            <?php else: ?>
                 <div class="form-group1">
                    <label for="ratname">Your Name:</label>
                    <input type="text" id="ratname" name="ratname" required>
                </div>
                <div class="form-group1">
                    <label for="ratemail">Your Email:</label>
                    <input type="text" id="ratemail" name="ratemail" required>
                </div>
            <?php endif; ?>
                <div class="form-group1">
                    <label for="comment">Your Comment:</label>
                    <textarea id="comment" name="recipeComment" rows="5" value="<?php echo $clear ?>"></textarea>
                </div>
                <div class="form-group2">
                    <label for="rate">Rate Recipe: </label>
                <select class="rating-select" id="rate" name="recipeRate">
                    <option value="" disabled selected>Choose Rating</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>
                </div>
                  
        <input type="hidden" name="recipeid" value="<?php echo $rid ?>">
        <div class="conratesubmit">
            <button type="submit" class="submit-comment1" name="comratebtn">Submit</button>
            <a href="recipeDetail.php?rid=<?php echo $rid ?>" class="submit-comment2">Reset</a>
        </div>   
            </form>
        </div>
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
<script>
        function myfun3(uid) {
            window.location.href = "cookbookuser.php?uid=" + uid;
        }

        // Get the modal, image, and close elements
const modal = document.getElementById("imageModal");
const img = document.getElementById("recipeImage");
const modalImg = document.getElementById("modalImage");
const captionText = document.getElementById("caption3");
const closeBtn = document.querySelector(".close3");

// When the user clicks the image, open the modal and display the image
img.onclick = function() {
    modal.style.display = "block";
    modalImg.src = this.src; // Use the same image source
    captionText.innerHTML = this.alt; // Set the recipe title as caption
}

// When the user clicks on the close button, close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
}

function togglePassword(inputId) {
        var inputField = document.getElementById(inputId);
        if (inputField.type === "password") {
            inputField.type = "text";
        } else {
            inputField.type = "password";
        }
    }

        
    </script>

<?php include 'footer.php'; ?>
</body>
</html>