<?php
    include("../connection/dbh.php");  
    session_start();
    if(empty($_SESSION["user_arr"])){
        header("Location:../login.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" >
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/adscript.js"></script>
</head>
<body>

  <!-- Back Icon -->
  <div class="back-button">
        <a href="../index.php"><i class="bi bi-arrow-left"></i></a>
</div>

<?php
    $sql="SELECT * FROM userfood WHERE uID=".$_SESSION["user_arr"]["uID"];
    $res=mysqli_query($con,$sql);
    if(mysqli_num_rows($res)>0)
    {
        while($row=mysqli_fetch_assoc($res))
        {
    

?>
<div class="navcontain">
    <!-- Header with logo/company name and user profile -->
    <header class="dashboard-header">
        <div class="container">
            <div class="logo">
                <span class="logo logo0">Flavor</span>
                <span class="logo logo1">Fusion</span>
                <h1>Manage Your Recipes</h1>
            </div>
            <div class="user-profile" id="profileDropdownToggle">
                <img src="../images/<?php echo $row['uimage']; ?>" alt="Profile Image" class="profile-img">
                <span><?php echo $row['uname']; ?></span>
                <div class="dropdown-content" id="profileDropdown">
                    <a href="usermodipro.php" >Manage Profile</a>
                    <a href="usermodipass.php">Modify Password</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Horizontal navigation with icons -->
    <nav class="dashboard-nav">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="usersetting.php"><i class="bi bi-speedometer2"></i>Main Dashboard</a>
            </li>
            <li class="nav-item dropdown" id="recipeDropdownToggle">
                <a href="#"><i class="bi bi-book"></i> Share Recipe <i class="bi bi-caret-down" id="exi1"></i></a>
                <ul class="dropdown-menu" id="recipeDropdown">
                    <li><a href="#" id="newRecipeLink">Share Your Recipe </a></li>
                    <li><a href="#" id="displayRecipeTable">All Recipes</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" id="displayRating"><i class="bi bi-calendar4-range"></i> Average Rating Per Recipe</a>
            </li>
        </ul>
    </nav>
</div>
    
    <!-- Dashboard Content (hidden initially) -->
    <section class="dashboard-content ">
        <div class="dashboard-stats">
            <?php
                $sqldas="SELECT count(*) as total_recipe
                        FROM recipe
                        WHERE recipe.uID =".$_SESSION['user_arr']['uID']."";
                $resdas=mysqli_query($con,$sqldas);
                $rowdasr=mysqli_fetch_assoc($resdas);
            ?>
            <div class="stat-box box1" id="statBox1">
                <i class="bi bi-file-earmark-text"></i>
                <h3>Total Recipes</h3>
                <p><?php echo $rowdasr['total_recipe']; ?></p>
            </div>
            <?php
                $sqldas="SELECT count(*) as total_comment
                        FROM comment
                        INNER JOIN recipe ON comment.rID = recipe.rID
                        WHERE recipe.uID =".$_SESSION['user_arr']['uID']."";
                $resdas=mysqli_query($con,$sqldas);
                $rowdascm=mysqli_fetch_assoc($resdas);
            ?>
            <div class="stat-box box2" id="statBox2">
                <i class="bi bi-chat"></i>
                <h3>All Comments</h3>
                <p><?php echo $rowdascm['total_comment']; ?></p>
            </div>
            <?php
            $sqldas = "
                SELECT recipe.rtitle,recipe.rID,
                AVG(rating.rating) as avg_rating
                FROM recipe
                LEFT JOIN rating ON recipe.rID = rating.rID
                WHERE recipe.uID = ".$_SESSION["user_arr"]["uID"]."
                GROUP BY recipe.rtitle
                ORDER BY avg_rating DESC
                LIMIT 1";
            
            $resavg = mysqli_query($con, $sqldas);
            $rowdasa=mysqli_fetch_assoc($resavg);
            ?>
            <div class="stat-box box3" onclick="myfun2(<?php echo $rowdasa['rID']?>)">
                <i class="bi bi-card-checklist"></i>
                <h3>Most Rating Recipe</h3>
                <?php if(isset($rowdasa['rtitle']) && $rowdasa['rtitle']!= null){?>
                    <p id="topr"><?php echo $rowdasa['rtitle']; ?></p>
                <?php }else{ ?>
                <p id="topr"><?php echo "No recipes" ?></p>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Recipe Form (hidden initially) -->
    <section id="newRecipeForm" class="recipe-form hidden">
        <div class="form-container">
            <h2>Add New Recipe</h2>
            <form id="recipeForm" enctype="multipart/form-data" method="POST" action="../connection/verify.php">
                <div class="form-group">
                    <label for="recipeTitle">Recipe Title:</label>
                    <input type="text" id="recipeTitle" name="recipeTitle" placeholder="Enter recipe title" required>
                </div>

                <div class="form-group">
                    <label for="prepTime">Preparation Time (mins):</label>
                    <input type="number" id="prepTime" name="prepTime" placeholder="Enter preparation time" required>
                </div>

                <div class="form-group">
                    <label for="cookTime">Cooking Time (mins):</label>
                    <input type="number" id="cookTime" name="cookTime" placeholder="Enter cooking time" required>
                </div>

                <div class="form-group">
                    <label for="availableEater">Available Eaters:</label>
                    <input type="number" id="availableEater" name="availableEater" placeholder="How many people can eat" required>
                </div>

                <div class="form-group">
                <div id="ingredientList">
                    <label for="ingredient">Ingredients:</label>
                    <input type="text" name="ingredient[]" placeholder="Ingredient name" id="ingredient" required>
                    <button type="button" class="add-ingredient-btn">Add</button>
                    <button type="button" class="remove-ingredient-btn">Remove</button>
                </div>
            
                </div>
                

                <div class="form-group">
                    <label for="cuisineType">Cuisine Type:</label>
                    <select id="cuisineType" name="categoryType" required>
                    <?php
                        $sql = "SELECT * FROM category";
                        $res = mysqli_query($con, $sql);
                        if(mysqli_num_rows($res)>0){
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<option value='{$row['cID']}'>{$row['cname']}</option>";
                            }
                        }
                    ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="eaterType">Yields Eaters Type:</label>
                    <select id="eaterType" name="eaterType" required>
                    <?php
                        $sql = "SELECT * FROM eaterType";
                        $res = mysqli_query($con, $sql);
                        if(mysqli_num_rows($res)>0){
                        while ($row1 = mysqli_fetch_assoc($res)) {
                            echo "<option value='{$row1['etID']}'>{$row1['etperson']}</option>";
                            }
                        }
                    ?>
    
                    </select>
                </div>


                <div class="form-group">
                    <label for="recipeImage">Recipe Image:</label>
                    <input type="file" id="recipeImage" name="recipeImage" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" placeholder="Your food description" rows="3" required></textarea>
                    <!-- <input type="textarea" id="description" name="description" placeholder="Your food description" required> -->
                </div>

                <button type="submit" class="submit-btn" name="recipe">Submit Recipe</button>
            </form>
        </div>
    </section>

     <!-- Recipe Table Section (hidden initially) -->
<section id="recipeTableSection" class="recipe-table hidden">
    <div class="table-container">
        <table class="recipe-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Prepare Time</th>
                    <th>Cooking Time</th>
                    <th>Available Eaters</th>
                    <th>Listing Date</th>
                    <th>Action</th> <!-- New Action column -->
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql="SELECT * FROM recipe WHERE uID=".$_SESSION['user_arr']['uID'];
                    $res=mysqli_query($con,$sql);
                    $counter=1;
                    if(mysqli_num_rows($res)>0)
                    {
                        while($row=mysqli_fetch_assoc($res))
                        {
                    
                ?>
                <tr>
                    <td><?php echo $counter++ ?></td>
                    <td><?php echo $row['rtitle']; ?></td>
                    <td><?php echo $row['prepareTime']; ?></td>
                    <td><?php echo $row['cookingTime']; ?></td>
                    <td><?php echo $row['availableEater']; ?></td>
                    <td><?php echo $row['rmadeAt']; ?></td>
                    <td class="recipebtn">
                        <button class="update-btn"><a href="userrecipeup.php?reup=<?php echo $row['rID']; ?>">Update</a></button>
                        <button class="delete-btn"><a href="userrecipede.php?rede=<?php echo $row['rID']; ?>" onclick="return confirm('Do you really want to Delete ?');">Delete</a></button>
                        <button class="combtn"><a href="usercomment.php?recm=<?php echo $row['rID']; ?>">Comment and Rate Count</a></button>
                    </td> <!-- Action buttons added here -->
                </tr>
                <?php
                    }
                } ?>
            </tbody>
        </table>
    </div>
</section>
<?php
        }
}
?>

    <!-- Search Section (hidden initially) -->
    <section id="ratingDisplaySection" class="recipe-rating-table hidden">
    <div class="table-container1">
        <table class="recipe-rating-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Recipe Title</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
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
                $sqlrate = "
                SELECT recipe.rID,recipe.rtitle, 
                AVG(rating.rating) as avg_rating
                FROM recipe
                LEFT JOIN rating ON recipe.rID = rating.rID
                WHERE recipe.uID = ".$_SESSION["user_arr"]["uID"]."
                GROUP BY recipe.rID, recipe.rtitle
                ORDER BY avg_rating DESC";
            
            $resultrate = mysqli_query($con, $sqlrate);
            $counter1=1;
            if(mysqli_num_rows($resultrate)>0){
                while($rowrate=mysqli_fetch_assoc($resultrate))
                {
                ?>
                <tr>
                    <td><?php echo $counter1++ ?></td>
                    <td><img src="../images/halo-halo (1).png" alt="" class="dropimg"> <?php echo $rowrate['rtitle']; ?></td>
                    <td>
<?php
$avgRating = round($rowrate['avg_rating'], 1);

echo displayStars($avgRating)." ( ".$avgRating." /5 )";?>
                    </td>
                </tr>
                <?php }
}?>
            </tbody>
        </table>
    </div>
    </section>
    <script>
        function myfun2(rid) {
            window.location.href = "usercomment.php?recm=" + rid;
        }
    </script>

</body>
</html>