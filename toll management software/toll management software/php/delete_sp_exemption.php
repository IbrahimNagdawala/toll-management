<center><?php
        session_start();
        if (!isset($_SESSION["loggedin"])) {
            header("location:login_form.php");
            exit;
        }
        if ($_SESSION["type"] !== "administrator") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="mystyle.css">
</head>

<body>
    <center>
        <?php
        if (isset($_POST['delete'])) {
            require_once "config.php";
            $sno = $_POST['delete'];
            $sql = "delete from sp_exemption where SNO='$sno'";
            if ($res = mysqli_query($link, $sql)) {
                echo "<br><br>Deleted Successfuly";
                header("Refresh:2,url=edit_sp_exemption.php");
            } else {
                echo "<br><br>Error : Something went wrong";
            }
        }
        ?></center>
</body>

</html>