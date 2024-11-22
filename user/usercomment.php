<?php 
    include("usersetting.php") 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- <div class="recipe-container">
    <div class="recipe-left">
        <img src="../images/banner6.jpg" alt="Recipe Image" class="recipe-image">
        <h2 class="recipe-name">Recipe Name</h2>
    </div>

    <div class="rating-box">
        <div class="rating-item">
            <span>5★</span>
            <div class="rating-bar" style="background-color: green; width: 60%;"></div>
            <span>2.0k</span>
        </div>
        <div class="rating-item">
            <span>4★</span>
            <div class="rating-bar" style="background-color: purple; width: 40%;"></div>
            <span>1.0k</span>
        </div>
        <div class="rating-item">
            <span>3★</span>
            <div class="rating-bar" style="background-color: orange; width: 20%;"></div>
            <span>500</span>
        </div>
        <div class="rating-item">
            <span>2★</span>
            <div class="rating-bar" style="background-color: cyan; width: 10%;"></div>
            <span>200</span>
        </div>
        <div class="rating-item">
            <span>1★</span>
            <div class="rating-bar" style="background-color: red; width: 5%;"></div>
            <span>0k</span>
        </div>
    </div>
</div> -->

<!-- Comment Section -->
<!-- <div class="comment-section">
    <div class="comment">
        <img src="../images/banner6.jpg" alt="User Image" class="profile-pic">
        <div class="comment-details">
            <span class="user-name">User Name</span>
            <p class="user-comment">This is a sample comment about the recipe.</p>
        </div>
    </div> -->
    <!-- Add more comments below as needed -->
<!-- </div> -->
<?php
    if(isset($_GET['recm']))
    {
        $rid=$_GET['recm'];
        $sqlcm="SELECT * FROM recipe WHERE rID=$rid";
        $rescm=mysqli_query($con,$sqlcm);
        if(mysqli_num_rows($rescm)>0){
            while($rowcm=mysqli_fetch_assoc($rescm)){
?>
<div class="recipe-container">
    <div class="recipe-left">
        <img src="../images/<?php echo $rowcm['rimage']; ?>" class="recipe-image">
        <h2 class="recipe-name"><?php echo $rowcm['rtitle']; ?></h2>
    </div>
    <div class="rating-right">
        <?php
            // Fetch the count of ratings from the database
        $query = "SELECT rating, COUNT(*) as count 
        FROM rating 
        WHERE rID=$rid
        GROUP BY rating 
        ORDER BY rating DESC";
        $result = $con->query($query);

// Initialize an array to store the rating counts
        $ratings = [
                    5 => 0,
                    4 => 0,
                    3 => 0,
                    2 => 0,
                    1 => 0
                    ];
// Variable to store total number of ratings
        $totalRatings = 0;

// Loop through the result and store it in the ratings array
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ratings[$row['rating']] = $row['count'];
                $totalRatings += $row['count']; // Calculate total number of ratings
            }
        }
        ?>
        <!-- HTML with dynamic data from PHP -->
        <ul class="rating-list">
    <?php foreach ($ratings as $rating => $count): ?>
        <?php
            // Calculate the percentage width for each rating bar
            $percentage = ($totalRatings > 0) ? ($count / $totalRatings) * 100 : 0;
        ?>
        <li>
            <span class="rating-star"><i class="bi bi-star-fill"></i> <?php echo $rating; ?></span> 
            <div class="rating-bar-container">
                <!-- Dynamic width applied to the rating bar -->
                <div class="rating-bar rating-<?php echo $rating; ?>" style="width: <?php echo $percentage; ?>%;"></div>
            </div>
            <span class="rating-number"><?php echo number_format($count); ?></span>
        </li>
    <?php endforeach; ?>
</ul>
        <!-- <ul class="rating-list">
            <span class="rating-bar rating-<?php echo $rating; ?>" style="width: <?php echo $percentage; ?>%;"></span> 
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 5</span> <span class="rating-bar rating-5"></span> <span class="rating-number">2.0k</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 4</span> <span class="rating-bar rating-4"></span> <span class="rating-number">1.0k</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 3</span> <span class="rating-bar rating-3"></span> <span class="rating-number">500</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 2</span> <span class="rating-bar rating-2"></span> <span class="rating-number">200</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 1</span> <span class="rating-bar rating-1"></span> <span class="rating-number">0</span></li>
        </ul> -->
        <h2 class="recipe-namerate">User Ratings</h2>
    </div> 
    <!-- <div class="rating-right">
        <ul class="rating-list">
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 5</span> <span class="rating-bar rating-5"></span> <span class="rating-number">2.0k</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 4</span> <span class="rating-bar rating-4"></span> <span class="rating-number">1.0k</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 3</span> <span class="rating-bar rating-3"></span> <span class="rating-number">500</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 2</span> <span class="rating-bar rating-2"></span> <span class="rating-number">200</span></li>
            <li><span class="rating-star"><i class="bi bi-star-fill"></i> 1</span> <span class="rating-bar rating-1"></span> <span class="rating-number">0</span></li>
        </ul>
        <h2 class="recipe-namerate">User Ratings</h2>
    </div>  -->
</div>

<div class="comment-section">
            <?php
            $sqlusercm="SELECT userfood.*,comment.* 
            FROM comment 
            LEFT JOIN userfood ON comment.uID = userfood.uID
            WHERE comment.rID=$rid";
            $resusercm=mysqli_query($con,$sqlusercm);
            $countcm=0;
            if(mysqli_num_rows($resusercm)>0){
                while($rusercm=mysqli_fetch_assoc($resusercm)){        
                if($rusercm['upassword']==Null){ 
            ?>
                    <div class="comment-box">
                        <img src="../images/visitor.jpg" alt="Profile" class="comment-profile-pic">
                        <div class="comment-content">
                            <h4 class="comment-username"><?php echo $rusercm['uname']; ?> <span class="cmvis">(Visistor)</span></h4>
                            <p class="comment-text"><?php echo $rusercm['content']; ?></p>
                            <p class="datecm"><?php echo $rusercm['wroteDate']; ?></p>
                        </div>
                    </div>
            <?php    }else { ?>
                    <div class="comment-box">
                        <img src="../images/<?php echo $rusercm['uimage']; ?>" alt="Profile" class="comment-profile-pic">
                        <div class="comment-content">
                            <h4 class="comment-username"><?php echo $rusercm['uname']; ?> <span class="cmau">(Member)</span></h4>
                            <p class="comment-text"><?php echo $rusercm['content']; ?></p>
                            <p class="datecm"><?php echo $rusercm['wroteDate']; ?></p>
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

    <!-- Add more comments similarly -->
</div>
<?php       }
        }
    }
?>
</body>
</html>