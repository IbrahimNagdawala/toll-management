<!DOCTYPE html>
<html>

<head>
    <title>
        Reset password
    </title>
    <link rel="stylesheet" href="mystyle.css">

</head>

<body>
    <div id='demo' style='float:left'></div>
    <center>
        <form method="POST">
            <h2>Reset Password</h2>
            <br><br>
            Current Password : <input type="password" name="current_password" required=True>
            <br><br>
            New Password : <input type="password" name="new_password" required=True>
            <br><br>
            Confirm New Password : <input type="password" name="confirm_password" required=True>
            <br><br>
            <input type="submit" name="submit" value="submit">
        </form>
    </center>
</body>





<center>
    <?php

    //initailize the session
    session_start();
    //check if the user is logged in , if not then redirect to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location:login_form.php");
        exit;
    }

    // include config file
    require_once "config.php";

    //define variables and initailize with empty values
    $current_password = $new_password = $confirm_password = "";
    $current_password_err = $new_password_err = $confirm_password_err = "";

    //processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"])) {

            //validate  current password
            $current_password = $_POST["current_password"];

            //prepare a select statement
            $sql = "SELECT PASSWORD FROM users WHERE USERNAME = ? ";

            if ($stmt = mysqli_prepare($link, $sql)) {
                //bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                //set parameters
                $param_username = $_SESSION["username"];

                //attempt to execute the prepared statement

                if (mysqli_stmt_execute($stmt)) {
                    //store result
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (!password_verify($current_password, $hashed_password)) {
                                // current password is  not matched with the database 
                                echo "<br><br>Invalid Current Password";
                            } else {
                                // validate new password
                                if (empty(trim($_POST["new_password"]))) {
                                    $new_password_err = "Please enter the new password.";
                                    echo "<br><br>" . $new_password_err;
                                } elseif (strlen(trim($_POST["new_password"])) < 8) {
                                    $new_password_err = "Password must have atleast 8 characters.";
                                    echo "<br><br>" . $new_password_err;
                                } else {
                                    $new_password = trim($_POST["new_password"]);
                                }

                                //validate confirm password
                                if (empty(trim($_POST["confirm_password"]))) {
                                    $confirm_password_err = "Please confirm the password.";
                                    echo "<br><br>" . $confirm_password_err;
                                } else {
                                    $confirm_password = trim($_POST["confirm_password"]);
                                    if (empty($new_password_err) && ($new_password != $confirm_password)) {
                                        $confirm_password_err = "New Password and Confirm Password did not match.";
                                        echo "<br><br>" . $confirm_password_err;
                                    }
                                }


                                //check if the new password and current password is same
                                if ($new_password == $current_password) {
                                    $matching_current_new_password_err = "present";
                                    echo "<br><br>New Password and Current password cannot be same";
                                }


                                // Check input errors before updating the database
                                if (empty($new_password_err) && empty($confirm_password_err) && empty($matching_current_new_password_err)) {
                                    // Prepare an update statement
                                    $sql = "UPDATE users SET PASSWORD = ? WHERE USERNAME = ?";

                                    if ($stmt = mysqli_prepare($link, $sql)) {
                                        // Bind variables to the prepared statement as parameters
                                        mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                                        //set parameters
                                        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                                        $param_username = $_SESSION["username"];

                                        //attempt to execute the prepared statement
                                        if (mysqli_stmt_execute($stmt)) {
                                            // Password updated successfully. Destroy the session, and redirect to login page
                                            session_destroy();
                                            echo "<br><br>password updated successfully";
                                            echo "<br><br> redirecting to login page in a few seconds";
                                            header("refresh:5;url=login_form.php");
                                            exit();
                                        } else {
                                            echo "Oops! Something went wrong. Please try again later.";
                                        }
                                        //close statement
                                        mysqli_stmt_close($stmt);
                                    }
                                }
                            }
                        }
                    }
                }
                mysqli_stmt_close($stmt);
                //close connection
                mysqli_close($link);
            }
        }
    }
    ?>
    <br><br><a href="dashboard.php">Go Back</a></center>
<script>
    // Set the date we're counting down to
    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }
    today = yyyy + '-' + mm + '-' + dd;
    console.log(today);
    var countDown1 = new Date(`${today} 07:59:59`);
    var countDown2 = new Date(`${today} 15:59:59`);
    var countDown3 = new Date(`${today} 23:59:59`);


    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        if (now < countDown1) {
            distance = countDown1 - now;
        } else if (now > countDown1 && now < countDown2) {
            distance = countDown2 - now;
        } else if (now > countDown2 && now < countDown3) {
            distance = countDown3 - now;
        }

        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        if ((distance / 1000) <= 152 && (distance / 1000) > 151) {
            alert("!! Be Alert ,Your Session will be ending in a few minutes.");
        }
        if ((distance / 1000) <= 150) {
            document.getElementById("demo").innerHTML = "Your Session will end after " + minutes + "m " + seconds + "s ";

        }






        // Output the result in an element with id="demo"


        // If the count down is over, write some text 
        if (distance >= 0 && distance < 500) {

            window.location.replace("logout.php");
        }
    }, 1000);
</script>

</html>