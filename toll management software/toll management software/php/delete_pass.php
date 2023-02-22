<center>
    <?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login_form.php"); //redirecting the user to login form
        exit;
    }
    if ($_SESSION["type"] != "pass manager") {
        exit("You are not allowed to access this functionality");
    }
    ?></center>
<br><br><br><br>
<center>
    <!DOCTYPE html>
    <html>

    <head>
        <title> Delete Pass</title>
        <link rel="stylesheet" href="mystyle.css">
    </head>

    <body>
        <?php
        require_once "config.php";
        if (isset($_POST["delete"])) {
            $pass_no = $_POST["delete"];
            $sql = "DELETE FROM monthly_pass WHERE PASS_NO='$pass_no'";
            if (mysqli_query($link, $sql)) {
                echo "<br><br>Pass no. $pass_no Deleted successfully";
            } else {
                echo "<br><br>Error : Something went wrong";
            }
        }
        ?>
        <br><br><a href="view_monthly_pass_record.php">Go Back</a>
</center>

<body>

    </html>