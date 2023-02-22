<center><?php
        session_start();
        // checking if the user is logged in or not 
        if (!isset($_SESSION["loggedin"])) {
            header("location: login_form.php"); //redirecting the user to login form
            exit;
        }
        if ($_SESSION["type"] !== "administrator") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>

<!DOCTYPE html>
<html>

<head>
    <title>
        Remove Staff
    </title>
    <link rel="stylesheet" href="mystyle.css">

</head>

<body>
    <br><br>
    <center>
        <form method="POST">
            <br><br>
            Staff's Username: <select name="username">
                <?php
                require_once "config.php";
                $sql = "SELECT USERNAME FROM users";
                if($_SESSION["username"]!=="mktpl"){
                    $sql=$sql." where TYPE='staff'";
                }
                if ($result = mysqli_query($link, $sql)) {

                    while ($array = mysqli_fetch_row($result)) {
                        if($array[0]!=="mktpl"){
                        echo "<option value='$array[0]'>$array[0]</option>";
                        }
                    }
                }
                ?>
            </select>
            <br><br>

            Administrator's Password : <input type="password" name="password" placeholder="Administrator's Password">
            <br><br>
            <input type="submit" name="submit" value="Delete Staff">
        </form>
        <?php
        if (isset($_POST["submit"])) {
            $sql = "SELECT PASSWORD FROM users WHERE USERNAME=? AND TYPE='administrator'";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $admin_password);
                    mysqli_stmt_fetch($stmt);
                }
                mysqli_stmt_close($stmt);
            }
            $password=$_POST["password"];
            if (hash("sha256",$password)==$admin_password) {




                $sql = "DELETE FROM users WHERE USERNAME=?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);
                    if (mysqli_stmt_execute($stmt)) {
                        //Deleted successfully
                        echo "<br><br> Account Deleted successfully";
                    }
                    mysqli_stmt_close($stmt);
                }
                mysqli_close($link);
            } else {
                exit("<br><br>You are not authorise to access this functionality");
            }
        }
        ?>

    </center>

</body>
<html>
