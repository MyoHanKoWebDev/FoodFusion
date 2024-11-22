<?php
include("connection/dbh.php");
session_start();
// require ("../index.php");
?>
<header class="header3">
<nav>
            <div class="nav-left">
                <img src="images/fusion.png" alt="" class="logomain">
                <span class="logo logo0">Flavor</span>
                <span class="logo logo1">Fusion</span>

            </div>
            <div class="iconlist" >
                    <i class="bi bi-list-ul" onclick="shownav();"></i>
                </div>              
            <div id="navigation">
                <div class="nav-right">
                <ul class="nav-links" >
                    <li><a href="index.php"><i class="bi bi-house" id="exei1"></i>Home</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="bi bi-list-check" id="exei2"></i>Explore<i class="bi bi-caret-down" id="exi"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="recipes.php" class="dropa recipe"><img src="images/halo-halo (1).png" alt="" class="dropimg">Recipes</a></li>
                            <li><a href="community_cookbook.php" class="dropa"><img src="images/group-chat.png" alt="" class="dropimg">Community Cookbook</a></li>
                            <li><a href="culinary_resources.php"><img src="images/salad.png" alt="" class="dropimg">Culinary Resources</a></li>
                            <li><a href="educational_resources.php"><img src="images/cookbook.png" alt="" class="dropimg">Educational Resources</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="contact.php"><i class="bi bi-send-plus" id="exei3"></i>Contact</a></li>
                    <li><a href="about.php"><i class="bi bi-book" id="exei4"></i>About us</a></li>
                    <?php if (isset($_SESSION['user_arr'])):
                        
                        $sql="SELECT * FROM userfood WHERE uID=".$_SESSION["user_arr"]["uID"];
                        $res=mysqli_query($con,$sql);
                        if(mysqli_num_rows($res)>0)
                        {
                        while($row=mysqli_fetch_assoc($res))
                            {

?>
                        <div class="user-profile reg" id="profileDropdownToggle">
                            <img src="images/<?php echo $row['uimage']; ?>" alt="Profile Image" class="profile-img">
                            <span><?php echo $row['uname']; ?></span>
                            <div class="dropdown-content" id="profileDropdown">
                                <a href="user/usersetting.php" >Your Dashboard</a>
                                <a href="logout.php">Logout</a>
                            </div>
                        </div>    
                    <?php } 
                        }  ?>
                        <?php else: ?>
                        <!-- Show Register and Login when not logged in -->
                        <li class="reg"><a href="#"  class="navlo" id="registerBtn" ><i class="bi bi-box-arrow-in-right"></i>Join Now</a></li>
                        <li class="reg"><a href="#"  class="navlo" id="loginBtn" ><i class="bi bi-box-arrow-in-right"></i>Login</a></li>
                         <!-- Show Dashboard and Logout when logged in -->
                        <li class="reg"><a href="user/usersetting.php" class="navlo"><i class="bi bi-kanban"></i>Dashboard</a></li>
                    <?php endif; ?>                  
                    
                </ul>
                </div>
                
            </div>
        </nav>    
       
</header>