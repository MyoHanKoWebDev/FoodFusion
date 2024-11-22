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
<?php
    $reup=$_GET["reup"];
    $sql="SELECT * FROM recipe WHERE rID='$reup'";
    $res=mysqli_query($con,$sql);
    $row0=mysqli_fetch_assoc($res);

    $sql2="SELECT * FROM category WHERE cID=$row0[cID]";
    $res2=mysqli_query($con,$sql2);
    $row4=mysqli_fetch_assoc($res2);

    $sql3="SELECT * FROM eaterType WHERE etID=$row0[etID]";
    $res3=mysqli_query($con,$sql3);
    $row5=mysqli_fetch_assoc($res3);

    $sql1="SELECT * FROM ingredient WHERE rID='$reup'";
    $res1=mysqli_query($con,$sql1);
    
    if(mysqli_num_rows($res1)>0){
        $ingre=[];
        while($row1=mysqli_fetch_assoc($res1))
        {
            array_push($ingre,$row1["iname"]);
        }
        $ingre=implode(",",$ingre);
    }
    
?>
    <!-- Update Recipe Form (initially hidden) -->
<section id="updateRecipeSection" class="update-recipe-form ">
    <div class="form-container">
        <h2>Update Recipe</h2>
        <form id="updateRecipeForm" enctype="multipart/form-data" method="POST" action="../connection/verify.php">
            <input type="hidden" id="recid" name="recid" value="<?php echo $row0["rID"]; ?>" required>
            <div class="form-group"> 
                <label for="updateRecipeTitle">Recipe Title:</label>
                <input type="text" id="updateRecipeTitle" name="recipeTitle" placeholder="Enter recipe title" value="<?php echo $row0["rtitle"]; ?>" required>
            </div>

            <div class="form-group">
                <label for="updatePrepareTime">Prepare Time:</label>
                <input type="number" id="updatePrepareTime" name="prepareTime" placeholder="Enter preparation time" value="<?php echo $row0["prepareTime"]; ?>"required>
            </div>

            <div class="form-group">
                <label for="updateCookingTime">Cooking Time:</label>
                <input type="number" id="updateCookingTime" name="cookingTime" placeholder="Enter cooking time" value="<?php echo $row0["cookingTime"]; ?>" required>
            </div>

            <div class="form-group">
                <label for="updateAvailableEater">Available Eaters:</label>
                <input type="number" id="updateAvailableEater" name="availableEater" placeholder="Enter number of eaters" value="<?php echo $row0["availableEater"]; ?>" required>
            </div>

           
            <div class="form-group">
                <label for="ingredient">Ingredient:</label>
                <textarea id="ingredient" name="ingredient" rows="3" required><?= $ingre; ?></textarea>
            </div>
          

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="3" required><?php echo $row0["rdescription"]; ?></textarea>
                <!-- <input type="text" id="updateRecipeTitle" name="recipeTitle" placeholder="Enter recipe title" value="<?php echo $row["rdescription"]; ?>" required> -->
            </div>

            <div class="form-group">
                <label for="updateCuisineType">Cuisine Type:</label>
                <select id="updateCuisineType" name="upcuisineType" required>
                <?php   
                        $sql = "SELECT * FROM category";
                        $res = mysqli_query($con, $sql);
                        if(mysqli_num_rows($res)>0){
                            echo "<option disabled selected>{$row4['cname']}</option>";
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<option value='{$row['cID']}'>{$row['cname']}</option>";
                            }
                          
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="updateEaterType">Eater Type:</label>
                <select id="updateEaterype" name="upeaterType" required>
                <?php
                        $sql = "SELECT * FROM eaterType";
                        $res = mysqli_query($con, $sql);
                        if(mysqli_num_rows($res)>0){
                            echo "<option disabled selected>{$row5['etperson']}</option>";
                        while ($row2 = mysqli_fetch_assoc($res)) {
                            echo "<option value='{$row2['etID']}'>{$row2['etperson']}</option>";
                            }
                           
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="updateImage">Current Image:</label>
                <input type="hidden" name="oldimg" value="<?php echo $row0['rimage']; ?>">
                <img src="../images/<?php echo $row0["rimage"]; ?>" alt="Current Recipe Image" id="currentRecipeImage" class="current-image" name="curimg">
                <label for="updateImage">Upload New Image:</label>
                <input type="file" id="updateImage" name="reupimage" required>
            </div>

            <button type="submit" class="submit-btn" name="reupsubmit">Update Recipe</button>
        </form>
    </div>
</section>

</body>
</html>