<?php
    include("dbh.php");
    session_start();

    $lopres="";
if (isset($_POST["logsub"]) || isset($_POST["logsub1"])) {
    $email = $_POST["loginEmail"];
    $password = $_POST["loginPassword"];

    // Query the user by email
    $stmt = $con->prepare("SELECT uID, upassword FROM userfood WHERE uemail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user_arr = $result->fetch_assoc();

        // Check if user has reached the maximum failed attempts
        if (isset($_SESSION['failed_attempts'][$email]) && $_SESSION['failed_attempts'][$email] >= 3) {
            $lockout_time = 480; // 8 minutes in seconds
            $last_attempt_time = $_SESSION['last_attempt_time'][$email];

            $current_time = time();
            $time_diff = $current_time - $last_attempt_time;

            if ($time_diff < $lockout_time) {
                $remaining_time = $lockout_time - $time_diff;
                $remaining_minutes = floor($remaining_time / 60);
                $remaining_seconds = $remaining_time % 60;

                // Alert user about remaining time
                echo "<script>
                    alert('You have tried too many times. Please wait " . $remaining_minutes . " minutes and " . $remaining_seconds . " seconds before trying again.');
                    window.location.replace('../index.php');
                </script>";
                exit;
            } else {
                // Reset failed attempts after lockout time has passed
                $_SESSION['failed_attempts'][$email] = 0;
                unset($_SESSION['last_attempt_time'][$email]);
            }
        }

        // Validate the password
        if (password_verify($password, $user_arr['upassword'])) {
            // Password is correct, log the user in
            $_SESSION['user_arr'] = $user_arr; // Store user ID in session

            // Reset failed attempts after successful login
            unset($_SESSION['failed_attempts'][$email]);
            unset($_SESSION['last_attempt_time'][$email]);

              // Set a cookie after successful login (optional, for user tracking)
              setcookie("loggedInUser", $user_arr['uID'], time() + (86400 * 30), "/", "", true, true); // Secure and HttpOnly
              setcookie("loginEmail", $email, time() + (86400 * 30), "/", "", true, true); // Store email for 30 days
              setcookie("loginPassword", $password, time() + (86400 * 30), "/", "", true, true); // Store password for 30 days (not secure for real apps)

              setcookie("email", $email, [
                'expires' => time() + (86400 * 30),  // 30 days
                'path' => '/',
                'secure' => true,  // Only over HTTPS
                'httponly' => true,  // Not accessible to JavaScript
                'samesite' => 'None',  // Required for cross-site cookies
            ]);
            setcookie("password", $password, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'None',
            ]);

                // Handle `cookieAccepted` (set when user accepts cookies on your page)
            if (!isset($_COOKIE['cookieAccepted'])) {
                setcookie("cookieAccepted", "true", time() + (86400 * 365), "/"); // Set 'cookieAccepted' cookie for 1 year
            }


            if (isset($_POST["logsub"])) {
                echo "<script>
                window.history.back();</script>";
            } else {
                header("Location: ../user/usersetting.php");
            }
            exit;
        } else {
            // Password is incorrect, increment failed attempts
            if (!isset($_SESSION['failed_attempts'][$email])) {
                $_SESSION['failed_attempts'][$email] = 0;
            }

            $_SESSION['failed_attempts'][$email]++;
            $_SESSION['last_attempt_time'][$email] = time(); // Update last attempt time

            // Notify user about the number of attempts left
            echo "<script>
                alert('Incorrect password. You have " . (3 - $_SESSION['failed_attempts'][$email]) . " attempts left.');
                window.location.replace('../index.php');
            </script>";
        }
    } else {
        // No user found with this email
        echo "<script>
            alert('No user found with this email.');
            window.location.replace('../index.php');
        </script>";
    }
}


if(isset($_POST["resetSubmit"])){
    $uemail=$_POST['resetEmail'];
    if($uemail){
        echo "<script>
        alert('Code Has Been Sent to (". $uemail.").');
        window.location.replace('../index.php');
        </script>";
    }
}



if (isset($_POST["recipe"])) {
    // Get all the form data
    $recipe_title = mysqli_real_escape_string($con, $_POST["recipeTitle"]);
    $prepare_time = $_POST["prepTime"];
    $user_id = $_SESSION["user_arr"]["uID"];
    $cook_time = $_POST["cookTime"];
    $avaiEater = $_POST["availableEater"];
    $category_id = $_POST["categoryType"];
    $eatertype_id = $_POST["eaterType"];
    $description = mysqli_real_escape_string($con, $_POST["description"]);
    $ingredients = $_POST["ingredient"];  // It's an array, no need to wrap in brackets.

    // Handling the file upload
    $file = $_FILES["recipeImage"];
    $filename = $_FILES["recipeImage"]["name"];
    $filetmpName = $_FILES["recipeImage"]["tmp_name"];
    $madeAt = date("Y-m-d H:i:s");
    
    // Validate file extension
    $fileExt = explode(".", $filename);
    $fileActExt = strtolower(end($fileExt));
    $allowedExtensions = array("png", "jpeg", "jpg", "pdf", "webp");

    if (in_array($fileActExt, $allowedExtensions)) {
        // Create a unique filename and upload the image
        $filenewName = uniqid('', true) . "." . $fileActExt;
        $destination = "../images/" . $filenewName;
        move_uploaded_file($filetmpName, $destination);  

        // Insert recipe into the 'recipe' table (specify the column names)
        $sql = "INSERT INTO recipe (rtitle, prepareTime, cookingTime, availableEater, rimage, rdescription, rmadeAt, uId, cId,etID) 
                VALUES ('$recipe_title', '$prepare_time', '$cook_time', '$avaiEater', '$filenewName', '$description', '$madeAt', $user_id, $category_id,$eatertype_id)";
        
        if (mysqli_query($con, $sql)) {
            // Get the last inserted recipe ID
            $lid = mysqli_insert_id($con);

            // Insert ingredients into the 'ingredient' table
            foreach ($ingredients as $ingredient) {
                $ingredient_sql = "INSERT INTO ingredient (iname, rID) VALUES ('$ingredient', $lid)";
                if (!mysqli_query($con, $ingredient_sql)) {
                    // Debugging ingredient insert error
                    echo "<script>alert('Error inserting ingredient: " . mysqli_error($con) . "');
                          window.location.replace('../user/usersetting.php');</script>";
                    exit;
                }
            }

            // Success message
            echo "<script>alert('Recipe added successfully');
                  window.location.replace('../user/usersetting.php');</script>";
        } else {
            // Debugging main insert error
            echo "<script>alert('Error to add a recipe: " . mysqli_error($con) . "');
                  window.location.replace('../user/usersetting.php');</script>";
        }
    } else {
        echo "<script>alert('Please upload jpg, png, jpeg, pdf, webp formats only');
              window.location.replace('../user/usersetting.php');</script>";
    }
}

// add recipe end


//edit recipe start
if(isset($_POST["reupsubmit"]))
{
    $recipeTitle=$_POST["recipeTitle"];
    $prepareTime=$_POST["prepareTime"];
    $cookingTime=$_POST["cookingTime"];
    $availableEater=$_POST["availableEater"];
    $recid=$_POST["recid"];
    $user_id=$_SESSION["user_arr"]["uID"];
    $description=$_POST["description"];
    $ingredients=$_POST["ingredient"];
    $category_id=$_POST["upcuisineType"];
    $eatertype_id=$_POST["upeaterType"];
    
    $file = $_FILES["reupimage"];
    $filename = $_FILES["reupimage"]["name"];
    $filetmpName = $_FILES["reupimage"]["tmp_name"];
    
    // Validate file extension
    $oldimage=$_POST["oldimg"];
    $oldimagepath="../images/".$oldimage;
    $fileExt = explode(".", $filename);
    $fileActExt = strtolower(end($fileExt));
    $allowedExtensions = array("png", "jpeg", "jpg","webp");

    if($filename!="")
    {
        if (in_array($fileActExt, $allowedExtensions)) {
            // Create a unique filename and upload the image
            $filenewName = uniqid('', true) . "." . $fileActExt;
            $destination = "../images/" . $filenewName;
            move_uploaded_file($filetmpName, $destination);  
        
                $sql="UPDATE recipe SET rtitle='$recipeTitle',prepareTime='$prepareTime',cookingTime='$cookingTime',availableEater='$availableEater',rimage='$filenewName',rdescription='$description',cID='$category_id',etID='$eatertype_id' WHERE rID=$recid";
                if(mysqli_query($con,$sql))
                {
                    unlink($oldimagepath);
                    $uprec_id=$recid;
                    $sql2="DELETE FROM ingredient WHERE rID=$recid";
                    if(mysqli_query($con,$sql2))
                    {

                    }
                    $ingredients=explode(",",$ingredients);
                    foreach($ingredients as $ingredient)
                    {
                        $sql2="INSERT INTO ingredient (iname,rID) VALUES ('$ingredient',$uprec_id)";
                        if(mysqli_query($con,$sql2))
                        {
                            
                        }
                    }
                    echo "<script>alert('Recipe updated successfully');
                    window.location.replace('../user/usersetting.php');</script>";
                }
                else
                {
                    echo "<script>alert('Error to update a recipe');
                    window.history.back();</script>";
                }
            }
        else
        {
            echo "<script>alert('please upload jpg,png,jpeg,webp formats only');
            window.history.back();</script>";
        }
    }
    else
    {
        echo "<script>alert('Please choose image file!');
            window.history.back();</script>";
    }
}

// if(isset($_POST['comratebtn']))
// {
//     $recipeid=$_POST['recipeid'];
//     $recipeRate=$_POST['recipeRate'] ?? '';   
//     $recipeComment=$_POST['recipeComment'] ?? '';
//     if(isset($_SESSION['user_arr']))
//     {
//         $uID=$_SESSION['user_arr']['uID'];
//         $date0=date("Y-m-d H:i:s");
//         if(isset($recipeComment) && $recipeComment!=NULL){
//             $sql0="INSERT INTO comment (content,wroteDate,uID,rID) VALUES ('$recipeComment','$date0',$uID,$recipeid)";
//             $res0=mysqli_query($con,$sql0);
//         }
//         if(isset($recipeRate) &&  $recipeRate!=Null)
//         {
//             $sql1="INSERT INTO rating (rating,uID,rID) VALUES ('$recipeRate',$uID,$recipeid)";
//             $res1=mysqli_query($con,$sql1);
//         }
//         if(empty($recipeComment) && empty($recipeRate)){
//             echo "<script>alert('Please Fill Wrinting Comment And Rating.');
//                     window.history.back();</script>"; 
//         }
//         if(isset($res0) && $res0 && isset($res1) && $res1){
//             echo "<script>alert('Success Writting Comment And You Rated (".$recipeRate.") Stars.');
//                window.history.back();</script>"; 
//         }else if(isset($res0) && $res0){
//             echo "<script>alert('Success Writting Comment.');
//                  window.history.back();</script>"; 
//         }else if(isset($res1) && $res1){
//             echo "<script>alert('You Rated (".$recipeRate.") Stars.');
//                  window.history.back();</script>"; 
//         }
//         else if(isset($res0) && !$res0 && isset($res1) && !$res1){
//             echo "<script>alert('Fail Writting Comment And Rating.');
//                  window.history.back();</script>";
//         } 
//     }
//     else{
//         $ucomname=$_POST['ratname'];
//         $ucomemail=$_POST['ratemail'];
//         if(isset($recipeComment) && $recipeComment!=Null || isset($recipeRate)  && $recipeRate!=Null){
//             $date=$date=date("Y-m-d H:i:s");
//             $sql2="INSERT INTO userfood (uname,uemail,upassword,uimage,ujoinDate) VALUES ('$ucomname','$ucomemail',NULL,NULL,'$date')";
//             $res2=mysqli_query($con,$sql2);
//             if($res2){
//                 $luserid = mysqli_insert_id($con);
//                 if(isset($recipeComment) && $recipeComment!=NULL){
//                     $sql3="INSERT INTO comment (content,wroteDate,uID,rID) VALUES ('$recipeComment','$date',$luserid,$recipeid)";
//                     $res3=mysqli_query($con,$sql3);
//                 if(isset($recipeRate) &&  $recipeRate!=Null){
//                     $sql4="INSERT INTO rating (rating,uID,rID) VALUES ('$recipeRate',$luserid,$recipeid)";
//                     $res4=mysqli_query($con,$sql4);
//                 }
//                 if(empty($recipeComment) && empty($recipeRate)){
//                     echo "<script>alert('Please Fill Wrinting Comment And Rating.');
//                             window.history.back();</script>"; 
//                 }
//                 if(isset($res3) && $res3 && isset($res4) && $res4){
//                     echo "<script>alert('Success Writting Comment And You Rated (".$recipeRate.") Stars.');
//                        window.history.back();</script>"; 
//                 }else if(isset($res3) && $res3){
//                     echo "<script>alert('Success Writting Comment.');
//                          window.history.back();</script>"; 
//                 }else if(isset($res4) && $res4 ){
//                     echo "<script>alert('You Rated (".$recipeRate.") Stars.');
//                          window.history.back();</script>"; 
//                 }
//                 else if(isset($res3) && !$res3 && isset($res4) && !$res4){
//                     echo "<script>alert('Fail Writting Comment And Rating.');
//                          window.history.back();</script>";
//                 } 
//             }
//             }
//         }
//         else{
//             echo "<script>alert('Please Fill Comment Or Rate.');
//              window.history.back();</script>";
//         }
//     }
// }

if (isset($_POST['comratebtn'])) {
    $recipeid = $_POST['recipeid'];
    $recipeRate = $_POST['recipeRate'] ?? '';
    $recipeComment = $_POST['recipeComment'] ?? '';
    $date0 = date("Y-m-d H:i:s");

    // Check if the user is logged in
    if (isset($_SESSION['user_arr'])) {
        $uID = $_SESSION['user_arr']['uID'];

        // Insert comment if provided
        $res0 = null;
        if (!empty($recipeComment)) {
            $sql0 = "INSERT INTO comment (content, wroteDate, uID, rID) VALUES ('$recipeComment', '$date0', $uID, $recipeid)";
            $res0 = mysqli_query($con, $sql0);
        }

        // Insert rating if provided
        $res1 = null;
        if (!empty($recipeRate)) {
            $sql1 = "INSERT INTO rating (rating, uID, rID) VALUES ('$recipeRate', $uID, $recipeid)";
            $res1 = mysqli_query($con, $sql1);
        }

        // Alert handling based on actions taken
        if (!empty($recipeComment) && !empty($recipeRate)) {
            if ($res0 && $res1) {
                echo "<script>alert('Comment and rating submitted successfully with $recipeRate stars.');
                    window.history.back();</script>";
            }
        } elseif ($res0) {
            echo "<script>alert('Comment submitted successfully.');
                window.history.back();</script>";
        } elseif ($res1) {
            echo "<script>alert('Rating of $recipeRate stars submitted successfully.');
                window.history.back();</script>";
        } else {
            echo "<script>alert('Please enter a comment or rating.');
                window.history.back();</script>";
        }
    }
    // Guest user section
    else {
        $ucomname = $_POST['ratname'] ?? '';
        $ucomemail = $_POST['ratemail'] ?? '';

        if (!empty($recipeComment) || !empty($recipeRate)) {
            // Insert guest user if not in session
            $sql2 = "INSERT INTO userfood (uname, uemail, upassword, uimage, ujoinDate) 
                     VALUES ('$ucomname', '$ucomemail', NULL, NULL, '$date0')";
            $res2 = mysqli_query($con, $sql2);

            if ($res2) {
                $luserid = mysqli_insert_id($con);

                // Insert comment for guest user
                if (!empty($recipeComment)) {
                    $sql3 = "INSERT INTO comment (content, wroteDate, uID, rID) VALUES ('$recipeComment', '$date0', $luserid, $recipeid)";
                    $res3 = mysqli_query($con, $sql3);
                }

                // Insert rating for guest user
                if (!empty($recipeRate)) {
                    $sql4 = "INSERT INTO rating (rating, uID, rID) VALUES ('$recipeRate', $luserid, $recipeid)";
                    $res4 = mysqli_query($con, $sql4);
                }

                // Alerts for guest actions
                if (!empty($recipeComment) && !empty($recipeRate)) {
                    if ($res3 && $res4) {
                        echo "<script>alert('Comment and rating submitted successfully with $recipeRate stars.');
                            window.history.back();</script>";
                    }
                } elseif ($res3) {
                    echo "<script>alert('Comment submitted successfully.');
                        window.history.back();</script>";
                } elseif ($res4) {
                    echo "<script>alert('Rating of $recipeRate stars submitted successfully.');
                        window.history.back();</script>";
                } else {
                    echo "<script>alert('Failed to submit guest comment and rating.');
                        window.history.back();</script>";
                }
            }
        } else {
            echo "<script>alert('Please enter a comment or rating.');
                window.history.back();</script>";
        }
    }
}



?>


