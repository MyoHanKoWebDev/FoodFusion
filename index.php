<?php
    include("connection/dbh.php");
    session_start();
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
    <title>Food Fusion - Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">  
    <script src="js/script.js"></script>
    
</head>
<body class="bodyback">
<?php include 'header.php'; ?>

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


<div class="special-dishes-info">
    <h2>Our Most Popular Recipes</h2>
    <p>Explore a variety of unique recipes shared by food enthusiasts in our community. We prioritize freshness, flavor, and quality in each recipe.</p>
  </div>
<section class="menu-itemspo">
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
    // Updated SQL query to join recipe_ratings table for rating values
$sql1po = "SELECT recipe.*, category.cname,  
          (SELECT AVG(rating.rating) FROM rating WHERE rating.rID = recipe.rID) as avg_rating 
          FROM recipe 
          LEFT JOIN category ON recipe.cID = category.cID 
          ORDER BY avg_rating DESC
          LIMIT 4";
$resultrepo = mysqli_query($con, $sql1po);
 if (mysqli_num_rows($resultrepo) > 0) {
    while ($rowpo = mysqli_fetch_assoc($resultrepo)) {
    ?>
    <div class="menu-item5">
    <div class="item-image5">
      <img src="images/<?php echo $rowpo['rimage']; ?>" alt="<?php echo $rowpo['rtitle']; ?>">
    </div>
    <h3><?php echo $rowpo['rtitle']; ?></h3>
    <p><strong>Category:</strong> <?php echo $rowpo['cname']; ?></p>
    <p><strong>Rating:</strong> <?php displayStars($rowpo['avg_rating'])?></p>
    <div class="viewsec">
      <button class="view-now" onclick="myfun2(<?php echo $rowpo['rID']?>)">View Detail</button>
    </div>
  </div>
    
<?php  } 
    } 
    ?>
  
</section>

<section class="ftco-counter" id="section-counter">
  <div class="overlay"></div>
  <div class="container">
    <div class="counter-row">
      <div class="counter-item">
        <div class="icon">
          <img src="images/chef1.png" alt="Coffee Branches Icon">
        </div>
        <?php
                $sqltre="SELECT count(*) as total_recipe
                        FROM recipe";
                $restre=mysqli_query($con,$sqltre);
                $rowtre=mysqli_fetch_assoc($restre);
            ?>
        <strong class="number"><?php echo $rowtre['total_recipe']; ?></strong>
        <span>Recipes</span>
      </div>
      <div class="counter-item">
        <div class="icon">
          <img src="images/chef1.png" alt="Awards Icon">
        </div>
        <?php
                $sqltme="SELECT count(*) as total_member
                        FROM userfood
                        WHERE userfood.upassword IS NOT NULL";
                $restme=mysqli_query($con,$sqltme);
                $rowtme=mysqli_fetch_assoc($restme);
            ?>
        <strong class="number"><?php echo $rowtme['total_member']; ?></strong>
        <span>Members</span>
      </div>
      <div class="counter-item">
        <div class="icon">
          <img src="images/chef1.png" alt="Happy Customer Icon">
        </div>
        <strong class="number">2,100 +</strong>
        <span>Happy Visitors</span>
      </div>
      <div class="counter-item">
        <div class="icon">
          <img src="images/chef1.png" alt="Staff Icon">
        </div>
        <?php
          $sqltrfe = "SELECT COUNT(*) AS total_recipe_five
                    FROM (
                    SELECT recipe.rID
                    FROM recipe
                    JOIN rating ON recipe.rID = rating.rID
                    GROUP BY recipe.rID
                    HAVING AVG(rating.rating) = 5
                    ) AS five_star_recipes;
                    ";
                $restrfe=mysqli_query($con,$sqltrfe);
                $rowtrfe=mysqli_fetch_assoc($restrfe);
            ?>
        <strong class="number"><?php echo $rowtrfe['total_recipe_five']; ?></strong>
        <span>Fully Rated Recipes</span>
      </div>
    </div>
  </div>
</section>

<section class="special-dishes">
<div class="section-header">
    <h2>Why Choose Our Community</h2>
    <p>Join our passionate community of food lovers and discover recipes, tips, and high-quality cooking insights shared by culinary enthusiasts.</p>
  </div>
  <div class="features">
    <div class="feature">
      <img src="images/social6.png" alt="Community-Driven Recipes Icon">
      <h3>Community-Driven Recipes</h3>
      <p>Explore a rich collection of recipes crafted and shared by passionate home chefs within our community.</p>
    </div>
    <div class="feature">
    <img src="images/book.png" alt="Community-Driven Recipes Icon">
      <h3>Expert Cooking Tips</h3>
      <p>Gain insights and practical tips from culinary enthusiasts to enhance your cooking skills.</p>
    </div>
    <div class="feature">
      <img src="images/shield.png" alt="Quality and Health Icon">
      <h3>Quality and Health Focus</h3>
      <p>Our community prioritizes healthy, high-quality ingredients for recipes that balance taste and nutrition.</p>
    </div>
  </div>
</section>
<div class="sliderigla" style="
  --width: 190px;
  --height: 30px;
  --quantity: 10;
">
  <div class="list">
    <div class="item" style="--position: 1"><span><img src="images/halo-halo (2).png" alt="" class="img5"> PizzaBurgerPie</span></div>
    <div class="item" style="--position: 2"><span><img src="images/pizza1.png" alt="" class="img5"> UltimateBurger</span></div>
    <div class="item" style="--position: 3"><span><img src="images/mixing.png" alt="" class="img5"> Gluten-FreeBao Buns</span></div>
    <div class="item" style="--position: 4"><span><img src="images/taco.png" alt="" class="img5"> ChickenChowMein</span></div>
    <div class="item" style="--position: 5"><span><img src="images/culinary.png" alt="" class="img5"> GarlicShrimpPasta</span></div>
    <div class="item" style="--position: 6"><span><img src="images/potato-chips.png" alt="" class="img5"> ShrimpPakora</span></div>
    <div class="item" style="--position: 7"><span><img src="images/bunny-chow.png" alt="" class="img5"> ChanaMasalaRecipe</span></div>
    <div class="item" style="--position: 8"><span><img src="images/salad.png" alt="" class="img5"> Best Air Fryer Ribs</span></div>
    <div class="item" style="--position: 9"><span><img src="images/flour.png" alt="" class="img5"> CoconutNoodleSoup</span></div>
    <div class="item" style="--position: 10"><span><img src="images/pizza 2.png" alt="" class="img5"> ChickenBiryani</span></div>
  </div>
</div>

<section class="video-background-section">
  <div class="video-containerindex">
    <video autoplay muted loop class="background-video">
      <source src="images/cooking2.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
  <div class="overlay-content">
    <h2>Explore New Flavors and Techniques</h2>
    <p>Join our culinary community to discover and create amazing recipes.</p>
  </div>
</section>


<div class="sliderigla sliderbt" reverse="true" style="
  --width: 200px;
  --height: 170px;
  --quantity: 10;
">
  <div class="list">
  <?php
    $sqlimg="SELECT * FROM recipe
            ORDER BY rmadeAt DESC
            LIMIT 10";
    $resimg=mysqli_query($con,$sqlimg);
    $imgcount=1;
    if(mysqli_num_rows($resimg)>0){
      while($rowimg=mysqli_fetch_assoc($resimg)){
  ?>
    <div class="item" style="--position: <?php  echo $imgcount; ?>"  onclick="myfun2(<?php echo $rowimg['rID']?>)"><img src="images/<?php echo $rowimg['rimage']; ?>" alt=""></div>
  <?php  $imgcount++;}
          } ?>
 
  </div>
</div>
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

 <!-- JavaScript to toggle password visibility -->
<script>
  function myfun2(rid) {
            window.location.href = "recipeDetail.php?rid=" + rid;
        }
        document.addEventListener("DOMContentLoaded", function() {
    const menuItems = document.querySelectorAll('.menu-item5');
    menuItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('appear');
        }, index * 400); // Adjust delay for desired effect
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
 <?php include 'footer.php'; ?>

</html>