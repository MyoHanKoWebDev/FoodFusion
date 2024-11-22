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
    <title>Food Fusion - Recipes</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/recipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/recipe.js"></script>
</head>
<style>
.profile-img {
    border-radius: 50%;
    width: 60px;
    height: 60px;
    z-index: 1;
    border:2px solid orange;
}

</style>
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

    <section class="search-section">
    <form class="search-container" id="searchContainer" method="GET" action="">
    <!-- <span class="close-search" id="closeSearch">&times;</span> Close (Cross) Icon -->
        <select id="category" class="search-category" name="category">
            <option value="" disabled selected>All Categories</option>
        <?php   
        
                $sql = "SELECT * FROM category";
                $res = mysqli_query($con, $sql);
                if(mysqli_num_rows($res)>0){
                while ($row = mysqli_fetch_assoc($res)) {
                    $select = isset($_GET['category']) && $_GET['category'] == $row['cID'] ? 'selected' : '';
                    echo "<option value='{$row['cID']}' {$select}>{$row['cname']}</option>";
                    }
                }
            ?>
        </select>

         <!-- Food Type Selection -->
         <select id="foodType" class="search-foodtype" name="eatertype">
            <option value="" disabled selected>All EaterTypes</option>
         <?php
                $sql = "SELECT * FROM eaterType";   
                $res1 = mysqli_query($con, $sql);
                if(mysqli_num_rows($res1)>0){
                while ($row1 = mysqli_fetch_assoc($res1)) {
                    $select = isset($_GET['eatertype']) && $_GET['eatertype'] == $row1['etID'] ? 'selected' : '';
                    echo "<option value='{$row1['etID']}' {$select}>{$row1['etperson']}</option>";
                    }
                }
            ?>
        </select>

        <!-- Eater Selection -->
        <select id="eater" class="search-eater" name="sorted">
            <option value="" disabled selected>Choose Rating and Latest</option>
            <option value="highestrate" <?= isset($_GET['sorted']) && $_GET['sorted'] == 'highestrate' ? 'selected' : '' ?>>Sort by Rating</option>
            <option value="latestcreat" <?= isset($_GET['sorted']) && $_GET['sorted'] == 'latestcreat' ? 'selected' : '' ?>>Sort by Latest</option>     
        </select>
        
        <input type="text" id="recipeName" class="search-input" placeholder="Search recipes by name..." value="<?= $_GET['keyword'] ?? '' ?>" name="name">
        
        <button id="searchBtn" class="search-button">Search</button>
            </form>
</section>
   
<section class="menu-section">
    <div class="menu-header">
        <h2>Enjoy Our Recipes</h2>
    </div> 
    <div class="menu-container">
    <?php
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
    }
$keyword = $_GET['name'] ?? '';
$cateid = $_GET['category'] ?? '';
$eaterid=$_GET['eatertype'] ?? '';
$sorted = $_GET['sorted'] ?? 'latestcreat';

// Updated SQL query to join recipe_ratings table for rating values
$sql1 = "SELECT recipe.*, category.cname, eatertype.etperson,  
        (SELECT AVG(rating.rating) FROM rating WHERE rating.rID = recipe.rID) as avg_rating 
        FROM recipe 
        LEFT JOIN category ON recipe.cID = category.cID 
        LEFT JOIN eatertype ON recipe.etID = eatertype.etID
        WHERE 1";

if ($keyword) {
    $sql1 .= " AND recipe.rtitle LIKE '%$keyword%'";
}
if ($cateid) {
    $sql1 .= " AND recipe.cID = '$cateid'";
}

if ($eaterid) {
    $sql1 .= " AND recipe.etID = '$eaterid'";
}
if ($sorted == 'highestrate') {
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
                    <p><strong>Category:</strong> <?php echo $row['cname']; ?></p>
                    <p><strong>Eater Type:</strong> <?php echo $row['etperson']; ?></p>
                    <p><strong>Rating:</strong> <?php displayStars($row['avg_rating'])?></p>
                </div> 
                
            </div>
    
<?php  } 
    } 
    else{
        echo "<p class='rec-notfound'>No recipes found.</p>";
    }
    ?>
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

    <div class="reload-recipe">
        <a href="recipes.php">Show All Recipes</a>
    </div>
    <section class="sponsors">
        <h2 class="sponsor-title">Proudly Sponsored By</h2>
    </section>
    <div class="slider2"  style="
  --width: 200px;
  --height: 150px;
  --quantity: 6;
">
  <div class="list2">
    <div class="item2" style="--position: 1"><img src="images/Spon1.jpg" alt=""></div>
    <div class="item2" style="--position: 2"><img src="images/Spon3.jpg" alt=""></div>
    <div class="item2" style="--position: 3"><img src="images/Spon4.png" alt=""></div>
    <div class="item2" style="--position: 4"><img src="images/Spon6.jpg" alt=""></div>
    <div class="item2" style="--position: 5"><img src="images/Spon5.png" alt=""></div>
    <div class="item2" style="--position: 6"><img src="images/Spon2.png" alt=""></div>
  </div>
</div>


    <?php include 'footer.php'; ?>

    <script>
        function myfun2(rid) {
            window.location.href = "recipeDetail.php?rid=" + rid;
        }

    document.addEventListener("DOMContentLoaded", function() {
    const menuItems = document.querySelectorAll('.menu-item');
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