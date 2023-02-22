<!DOCTYPE html>
<html>

<head>
    <title>
        Reset Password of Other Users
    </title>
    <link rel="stylesheet" href="mystyle.css">


</head>

<body>
    <center>
        <form method="POST">
            <br><br>
            <br><br>
            Username :<input type="text" name="username">
            <br>
            <br>
            New Password : <input type="password" name="new_password">
            <br><br>
            Administrator's Password : <input type="password" name="administrator_password">
            <br><br>
            <input type="submit" name="submit" value="Update Password">
        </form>
    </center>
</body>
<center>
    <?php
    require_once "config.php";
    session_start();
    // checking if the user is logged in or not 
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login_form.php"); //redirecting the user to login form
        exit;
    }
    if ($_SESSION["type"] !== "administrator") {
        exit("You are not allowed to access this functionality");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"])) {
            $username = $_POST["username"];
            $new_password = $_POST["new_password"];
            $administrator_password = $_POST["administrator_password"];
            $username_err = $new_password_err = $administrator_password_err = "";
            //prepare statement to select password of admin
            $sql = "SELECT PASSWORD FROM users WHERE USERNAME = ? AND TYPE='administrator'";
            if ($stmt = mysqli_prepare($link, $sql)) {
                //bind variables to the prepared statement
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                //set parameters
                $param_username = $_SESSION["username"];
                if (mysqli_stmt_execute($stmt)) {
                    //store result
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $hashed_password);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (hash("sha256",$administrator_password)!=$hashed_password) {
                                // administrator's  password is  not matched with the database 
                                echo "<br><br>Invalid Administrator's Password";
                                // closing the statement
                                mysqli_stmt_close($stmt);
                            } else {

                                // checking if the username is available in the database or not

                                //preparing statement
                                $sql = "SELECT ID FROM users WHERE USERNAME = ?";
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    //bind variables to the statement
                                    mysqli_stmt_bind_param($stmt, "s", $param_username);
                                    //set parameters
                                    $param_username = $username;

                                    if (mysqli_stmt_execute($stmt)) {
                                        //store result
                                        mysqli_stmt_store_result($stmt);
                                        if (mysqli_stmt_num_rows($stmt) == 0) {
                                            echo "<br><br>Invalid Username";
                                            //close statement
                                            mysqli_stmt_close($stmt);
                                        } else {

                                            //prepare the statement to update password
                                            $sql = "UPDATE users SET PASSWORD = ? WHERE USERNAME = ?";
                                            if ($stmt = mysqli_prepare($link, $sql)) {
                                                // Bind variables to the prepared statement as parameters
                                                mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                                                //set parameters
                                                $param_password = hash("sha256",$new_password);
                                                $param_username = $username;
                                                //attempt to execute the prepared statement
                                                if (mysqli_stmt_execute($stmt)) {
                                                    // Password updated successfully.
                                                    echo "<br><br>Password Updated successfuly";
                                                    
                                                    exit();
                                                } else {
                                                    echo "Oops! Something went wrong. Please try again later.";
                                                }
                                                // close statement
                                                mysqli_stmt_close($stmt);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    //close connection
    mysqli_close($link);
    ?>
</center>

</html>