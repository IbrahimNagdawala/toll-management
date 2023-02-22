<center><?php
        session_start();
        if (!isset($_SESSION["loggedin"])) {
            header("location:login_form.php");
            exit;
        }
        if ($_SESSION["type"] !== "accountant") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>
<!DOCTYPE html>
<html>

<head>
    <title>
        Cashup Staff Form</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        body {
            margin: 0;
        }

        header {
            padding-top: 5px;
            background: #3500D3;
            position: fixed;
            width: 100%;
            font-size: 25px;
            height: 40px;
            color: white;

        }

        #acc_details {

            float: right;
            padding-top: 8px;
            font-size: 20px;
            display: block;



        }
    </style>
</head>

<body>
    <header><i>Toll Tax Management Software</i>
        <?php

        echo "<div id='acc_details'>";
        echo $_SESSION['name'];
        echo "&nbsp;(" . $_SESSION["type"] . ")";
        echo "&nbsp;";
        echo "&nbsp;";
        echo "&nbsp;";
        echo '<a href="logout.php" class="button" style= "padding-top:5px;text-decoration:none;display:block;background:black; float:right;height:27px;text-align:center;border:1px gray;color:white;" >Logout</a>';
        echo "&nbsp;";
        echo "</div>";

        ?>
    </header>

    <center>
        <br><br>
        <h2>Cashup Form</h2>
        <a style="margin-top:45px;float:left;font-size:25px;width:250px;" href="view_cashup_entries.php">View Cashup Entries</a>

        <br>
        <form method="POST">
            Staff ID : <input type="text" name="id" placeholder="Enter Staff ID" required=True>
            <br><br>
            Shift No : <input type="number" name="shift" placeholder="Enter Shift No of Staff" required=True>
            <br><br>
            <input type="submit" name="sub1" value="submit">
            <br><br>
        </form>
    </center>
    <?php
    if (isset($_POST["sub1"])) {

        $_SESSION["target_id"] = addslashes($_POST["id"]);
        $_SESSION["target_shift"] = "shift " . $_POST["shift"];

        header("location:fill_cashup.php");
    }
    ?>
</body>

</html>