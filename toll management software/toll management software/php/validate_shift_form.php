<center><?php
        session_start();
        if (!isset($_SESSION["loggedin"])) {
            header("location:login_form.php");
            exit;
        }
        if ($_SESSION["type"] !== "audit") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>
<!DOCTYPE html>
<html>

<head>
    <title>
        Validate Staff Form</title>
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
        <a href='view_audit_entries.php' style='float:left;margin-top:100px;'>View Past Entries</a>
        
        <a href='view_audited_entries_form.php' style='float:left;margin-top:100px;margin-left:0px;'>View Audited Entries</a>

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
        <h2>Validation Form</h2>


        <form method="POST" action="validate_entries.php">
            SHIFT NO: <input type="text" name="shift" placeholder="Enter Shift No" required=True>
            <br><br>
            <input type="submit" name="sub1" value="submit">
            <br><br>
        </form>
    </center>
</body>

</html>