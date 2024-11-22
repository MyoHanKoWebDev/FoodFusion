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
    <title>Food Fusion - Contact Us</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="js/script.js"></script>
    <style>
    .header2 {
    background-image: url('images/banner10.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    /* background-attachment: fixed; */
    color: white;
    /* padding: 1rem 0; */
    min-height: 70vh;  
}
</style>
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
                <div class="showpassformat">
                    <span>At lest 6 characters with only one capital & small letter and number included</span>
                </div>  
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
    
<section class="contact-section">
    <div class="contact-info">
    
        <h1>We are here to help!</h1>
        <p>
            Let us know how we can best serve you. Use the contact form to email us or select from the topics below that best fit your needs. It's an honor to support you in your journey towards culinary creativity and better food experiences.
        </p>
       
    </div>

    <div class="contact-form">
        <form action="" method="POST" id="contactForm">
            <input type="text" name="name" placeholder="Name" required >
            <input type="email" name="email" placeholder="Email" required >
            <input type="tel" name="phone" placeholder="Phone number">
            <textarea name="message" placeholder="Comment" required></textarea>
            <button type="submit" name="contactbtn" class="send-btn">Send Message</button>
        </form>
        <div class="recaptcha-info">
            This site is protected by reCAPTCHA and the Google <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> apply.
        </div>
    </div>
</section>
<iframe 
            class="map"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434508615!2d144.95373531586658!3d-37.8172097797517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf5778407747c6675!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1615003892244!5m2!1sen!2sau" 
            width="100%" 
            height="350" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
        <a href="https://goo.gl/maps/examplelink" class="map-link" target="_blank">View on Google Maps</a>  
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
    <?php include 'footer.php'; 
    if(isset($_POST['contactbtn'])){
        $name=$_POST['name'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $message=$_POST['message'];
        $valid=true;
        //email start
        $emailpattern="/^(.+)@(gmail.com)$/";
        if(!preg_match($emailpattern,$email))
        {
            $eres="Field email not valid format that must be end with @gmail.com";
            $valid=false;
        }
        //email end
        if($valid==true)
        {
        $sqlcontact="INSERT INTO contact (ctname,ctemail,ctph,ctcomment) VALUES ('$name','$email','$phone','$message')";
        $rescontact=mysqli_query($con,$sqlcontact);
        if($sqlcontact){
            echo "<script>alert('You contact message successfully sent.');
                clearForm(); // Call JavaScript to clear the form</script>";
        }
        }else{
        echo "<script>alert('Email Format is Wrong (----@gmail.com)');
                </script>";
        }
    }
    
    ?>
    <script>
    // Clear the form after submission
    function clearForm() {
        document.getElementById('contactForm').reset(); // This will clear all input fields
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
</body>
</html>