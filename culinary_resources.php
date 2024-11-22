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
    <title>Food Fusion - Culinary Resources</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/resources.css">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="js/script.js"></script>

</head>
<body>
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
    <section class="culinary-resources">
    <h1>Culinary Resources</h1>
    <div class="resource-categories">
        <button class="category-btn" onclick="scrollToHeading1()">Recipe Cards</button>
        <button class="category-btn" onclick="scrollToHeading2()">Cooking Tutorials</button>
        <button class="category-btn" onclick="scrollToHeading3()">Kitchen Hacks</button>
    </div>

    <div class="resource-grid1">
        <!-- Recipe Cards Section -->
        <div class="resource-card1 recipe-cards" id="recipecard">
            <div class="imgcomcon">
                <img src="images/pasta1.jpg" alt="Recipe Card 1" class="resource-image" onclick="showImageModal4('images/pasta1.jpg')">
            </div>
            <div class="resource-content">
                <h3>Easy Pasta Recipe</h3>
                <p><strong>Description:</strong> Quick and easy pasta recipe that anyone can follow.</p>
                <button class="view-details-btn1" onclick="showModal('Easy Pasta Recipe', 'https://www.youtube.com/embed/bcnPfcJnYr0', 'Learn to cook pasta quickly with this tutorial.')">View Tutorial</button>
                <a href="resources/110195022.pdf"  class="download-btn1" download>Download Recipe Card</a>
            </div>
        </div>

        <div class="resource-card1 recipe-cards">
            <div class="imgcomcon1">
                <img src="images/pizza1.jpg" alt="Recipe Card 2" class="resource-image" onclick="showImageModal4('images/pizza1.jpg')">
            </div>
                <div class="resource-content">
                <h3>Homemade Pizza</h3>
                <p><strong>Description:</strong> Learn how to make delicious pizza from scratch.</p>
                <button class="view-details-btn1" onclick="showModal('Homemade Pizza', 'https://www.youtube.com/embed/OV_vFhPe1Io', 'Make pizza from scratch with simple ingredients.')">View Tutorial</button>
                <a href="resources/5fd05a7e6820b027c21a5d48_persiMon-Chorizo-Pizza.pdf" class="download-btn1" download>Download Recipe Card</a>
            </div>
        </div>

        <div class="resource-card1 recipe-cards">
            <div class="imgcomcon1">
                <img src="images/salad1.jpg" alt="Recipe Card 3" class="resource-image" onclick="showImageModal4('images/salad1.jpg')">
            </div>
                <div class="resource-content">
                <h3>Healthy Salad Recipe</h3>
                <p><strong>Description:</strong> A fresh and healthy salad recipe perfect for a light meal.</p>
                <button class="view-details-btn1" onclick="showModal('Healthy Salad Recipe', 'https://www.youtube.com/embed/SpI3QF_Iauc', 'Learn to make a nutritious salad.')">View Tutorial</button>
                <a href="resources/Salad-Recipe-Pack-AHS.pdf" class="download-btn1" download>Download Recipe Card</a>
            </div>
        </div>

        <!-- Cooking Tutorials Section -->
        <div class="resource-card1 tutorials" id="knifeskill"> 
            <div class="imgcomcon1">
                <img src="images/COOK AT YOUR OWN PACE_ Smart Chopper 101.jpg" alt="Cooking Tutorial 1" class="resource-image" onclick="showImageModal4('images/COOK AT YOUR OWN PACE_ Smart Chopper 101.jpg')">
            </div>
            <div class="resource-content">
                <h3>Knife Skills 101</h3>
                <p><strong>Description:</strong> Master basic knife skills with this tutorial.</p>
                <button class="view-details-btn1" onclick="showModal('Knife Skills 101', 'https://www.youtube.com/embed/G-Fg7l7G1zw', 'Learn essential knife skills in the kitchen.')">Watch Video</button>
            </div>
        </div>

        <div class="resource-card1 tutorials">
            <div class="imgcomcon1">
                <img src="images/baking1.jpg" alt="Cooking Tutorial 2" class="resource-image" onclick="showImageModal4('images/baking1.jpg')">
            </div>
            <div class="resource-content">
                <h3>Baking Tips & Tricks</h3>
                <p><strong>Description:</strong> Get the best tips for baking cakes and cookies like a pro.</p>
                <button class="view-details-btn1" onclick="showModal('Baking Tips & Tricks', 'https://www.youtube.com/embed/320xPox0VF0', 'Enhance your baking skills with this guide.')">Watch Video</button>
            </div>
        </div>

        <div class="resource-card1 tutorials">
            <div class="imgcomcon1">
                <img src="images/Grilling2.jpg" alt="Cooking Tutorial 3" class="resource-image" onclick="showImageModal4('images/Grilling2.jpg')">
            </div>
                <div class="resource-content">
                <h3>Perfect Grilling Techniques</h3>
                <p><strong>Description:</strong> Learn how to grill meats and vegetables perfectly every time.</p>
                <button class="view-details-btn1" onclick="showModal('Perfect Grilling Techniques', 'https://www.youtube.com/embed/yPAeRiKa0h8', 'Master grilling techniques in this video.')">Watch Video</button>
            </div>
        </div>

        <!-- Kitchen Hacks Section -->
        <div class="resource-card1 hacks" id="kitchenhack">
            <div class="imgcomcon1">
                <img src="images/Kitchen1.jpg" alt="Kitchen Hack 1" class="resource-image" onclick="showImageModal4('images/Kitchen1.jpg')">
            </div>
                <div class="resource-content">
                <h3>Kitchen Hacks to Save Time</h3>
                <p><strong>Description:</strong> Learn quick kitchen hacks to save time and effort in cooking.</p>
                <button class="view-details-btn1" onclick="showModal('5 Kitchen Hacks to Save Time', 'https://www.youtube.com/embed/z6DAY0x-J80', 'Learn how to make your time in the kitchen more efficient with these hacks.')">Watch Video</button>
            </div>
        </div>

        <div class="resource-card1 hacks">
            <div class="imgcomcon1">
                <img src="images/Kitchen2.jpg" alt="Kitchen Hack 2" class="resource-image" onclick="showImageModal4('images/Kitchen2.jpg')">
            </div>
                <div class="resource-content">
                <h3>Organize Your Kitchen Like a Pro</h3>
                <p><strong>Description:</strong> Organize your kitchen space efficiently with these tips.</p>
                <button class="view-details-btn1" onclick="showModal('Organize Your Kitchen Like a Pro', 'https://www.youtube.com/embed/Cu3mFkhdxHg', 'Keep your kitchen tidy and functional with these organization tips.')">Watch Video</button>
            </div>
        </div>

        <div class="resource-card1 hacks">
            <div class="imgcomcon1">
                <img src="images/Kitchen3.jpg" alt="Kitchen Hack 3" class="resource-image" onclick="showImageModal4('images/Kitchen3.jpg')">
            </div>
                <div class="resource-content">
                <h3>Extend the Shelf Life of Produce</h3>
                <p><strong>Description:</strong> Simple tips to extend the freshness of fruits and vegetables.</p>
                <button class="view-details-btn1" onclick="showModal('Extend the Shelf Life of Produce', 'https://www.youtube.com/embed/hnYj4uUsLSQ', 'Keep your fruits and vegetables fresh longer with these hacks.')">Watch Video</button>
            </div>
        </div>
    </div>
        <!-- Modal Structure -->
<div id="resourceModal" class="modal5 hidden">
    <div class="modal-content5">
        <span class="close-btn5" onclick="closeModal1()">&times;</span>
        <h2 id="modalTitle"></h2>
        <iframe id="modalVideo" class="video-frame" allowfullscreen></iframe>
        <p id="modalDescription"></p>
    </div>
</div>
<!-- Image Modal Structure -->
<div id="imageModal4" class="modal4 hidden">
    <div class="modal-content4">
        <span class="close-btn4" onclick="closeImageModal()">&times;</span>
        <img id="modalImage4" src="" alt="Modal Image" class="modal-image4">
    </div>
</div>
</section>
<section class="educational-resources1">
    <h1>Popular Chef Videos & Recipes</h1>
    <div class="resource-grid2">
        <!-- Resource Cards -->
        <div class="resource-card2">
            <h3>Chef Gordon Ramsay - Beef Wellington</h3>
            <iframe src="https://www.youtube.com/embed/Cyskqnp1j64" allowfullscreen></iframe>
            <button class="view-details-btn2" onclick="showModal6('Chef Gordon Ramsay - Beef Wellington', 'https://www.youtube.com/embed/Cyskqnp1j64', 'Beef, Puff Pastry, Mushrooms, Mustard, Eggs', 'Preparation Time: 30 min, Cooking Time: 60 min', 'A world-famous dish by Gordon Ramsay.')">View More</button>         
        </div>

        <div class="resource-card2">
            <h3>Chef Jamie Oliver - Pasta Carbonara</h3>
            <iframe src="https://www.youtube.com/embed/3AAdKl1UYZs" allowfullscreen></iframe>
            <button class="view-details-btn2" onclick="showModal6('Chef Jamie Oliver - Pasta Carbonara', 'https://www.youtube.com/embed/3AAdKl1UYZs', 'Pasta, Eggs, Bacon, Parmesan, Garlic', 'Preparation Time: 20 min, Cooking Time: 15 min', 'A classic Carbonara by Jamie Oliver.')">View More</button>
        </div>

        <!-- Added 10 more resource cards with descriptions -->
        <div class="resource-card2">
            <h3>Chef Nigella Lawson - Chocolate Cake</h3>
            <iframe src="https://www.youtube.com/embed/KVsJK9pi8N0" allowfullscreen></iframe>
            <button class="view-details-btn2" onclick="showModal6('Chef Nigella Lawson - Chocolate Cake', 'https://www.youtube.com/embed/KVsJK9pi8N0', 'Chocolate, Butter, Eggs, Flour', 'Preparation Time: 15 min, Cooking Time: 45 min', 'Nigella Lawson\'s indulgent chocolate cake recipe.')">View More</button>
        </div>


    </div>
    <!-- Modal Structure -->
<div id="resourceModal6" class="modal6">
    <div class="modal-content6">
        <span class="close-btn6" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle2"></h2>
        <iframe id="modalVideo2" class="video-frame"></iframe>
        <p id="modalIngredients"></p>
        <p id="modalPrepTime"></p>
        <p id="modalDescription"></p>
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
function filterResources(category) {
    const cards = document.querySelectorAll('.resource-card');
    cards.forEach(card => {
        if (card.classList.contains(category)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function showModal(title, videoUrl, description) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalVideo').src = videoUrl;
    document.getElementById('modalDescription').innerText = description;
    document.getElementById('resourceModal').style.display = 'flex';
}

function closeModal1() {
    document.getElementById('resourceModal').style.display = 'none';
    document.getElementById('modalVideo').src = '';
}


function showImageModal4(imageSrc) {
    document.getElementById('modalImage4').src = imageSrc;
    document.getElementById('imageModal4').style.display = 'flex';
}

function closeImageModal() {
    document.getElementById('imageModal4').style.display = 'none';
}

function showModal6(title, video, ingredients, prepTime, description) {
    document.getElementById('modalTitle2').innerText = title;
    document.getElementById('modalVideo2').src = video;
    document.getElementById('modalIngredients').innerText = 'Ingredients: ' + ingredients;
    document.getElementById('modalPrepTime').innerText = prepTime;
    document.getElementById('modalDescription').innerText = description;

    document.getElementById('resourceModal6').style.display = 'flex';
}

function closeModal() {
    document.getElementById('resourceModal6').style.display = 'none';
    document.getElementById('modalVideo2').src = ''; // Stop video playback
}

function scrollToHeading1() {
    const heading = document.getElementById('recipecard');
    heading.scrollIntoView({
        behavior: 'smooth',  // Smooth scrolling
        block: 'center',     // Scroll to the center of the viewport
        inline: 'nearest'
    });
}

function scrollToHeading2() {
    const heading = document.getElementById('knifeskill');
    heading.scrollIntoView({
        behavior: 'smooth',  // Smooth scrolling
        block: 'center',     // Scroll to the center of the viewport
        inline: 'nearest'
    });
}

function scrollToHeading3() {
    const heading = document.getElementById('kitchenhack');
    heading.scrollIntoView({
        behavior: 'smooth',  // Smooth scrolling
        block: 'center',     // Scroll to the center of the viewport
        inline: 'nearest'
    });
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