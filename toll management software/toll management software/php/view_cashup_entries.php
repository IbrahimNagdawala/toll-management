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
        View Cashup Entries</title>
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

        @media print {

            /* Hide every other element*/
            body * {
                visibility: hidden;

            }

            /*then displaying print_container elements*/
            .print_container,
            .print_container * {
                visibility: visible;
            }

            /* adjusting the position to always start from top left */
            .print_container {
                position: absolute;
                left: 0px;
                top: 0px;
            }
        }
    </style>
</head>

<body>
    <header><i>Toll Tax Management Software</i>
        <?php
        require_once "config.php";
        //date and time
        $date_time = getdate();
        $day = $date_time["mday"];
        $month = $date_time["mon"];
        $year = $date_time["year"];
        if($day<10){
            $day="0".$day;
        }
        if ($month < 10) {
            $month = "0" . $month;
        }
        $date = $year . "-" . $month . "-" . $day;

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
    
    <a href='cashup_staff_form.php' style='margin-top:55px;position:fixed;'>Go Back</a>

</body>
<br><br><br><br>
<h2 align="center">View Cashup Entries</h2>
<center>
    <form method="POST">
        <br><br>
        <?php
        echo "FROM DATE : <input type='date' value='$date' required=True name='from_date'>";
        echo "<br><br>";
        echo "TO DATE : <input type='date' value='$date' required=True name='to_date'>";
        ?> <br><br>
        Staff ID : <input type="text" name="id" required=True placeholder="Enter staff id or ALL for whole Staff" size="30"><br><br>
        SHIFT : <input type="text" name="shift" required=True placeholder="Enter Shift Number or ALL for all shifts" size='35'>
        <br><br><input type="submit" name="submit" value="submit">
        <br><br>

    </form>
    <?php
    if (isset($_POST["submit"])) {
        $id = $_POST["id"];
        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];
        $shift = "shift " . $_POST["shift"];
        $sql = "Select * from cash_up where (DATE BETWEEN '$from_date' and '$to_date')";
        if ($id !== "ALL") {
            $sql = $sql . " and ID='$id'";
        }
        if ($shift !== "shift ALL") {
            $sql = $sql . " and SHIFT='$shift'";
        }
        if ($res = mysqli_query($link, $sql)) {
            echo "<br><br><input type='button' name='button' value='Print Report' onclick='window.print()'> <br><br>";
            echo "<div class='print_container'><table border='2px'>";
            echo "<tr>";
            echo "<th>DATE</th>";
            echo "<th>TIME</th>";
            echo "<th>ID</th>";
            echo "<th>NAME</th>";
            echo "<th>SHIFT</th>";
            echo "<th>BOOTH</th>";
            echo "<th>SYSTEM COLLECTION</th>";
            echo "<th>CORRECT TOLL</th>";
            echo "<th>CASH COLLECTION</th>";
            echo "<th>MANUAL COLLECTION</th>";
            echo "<th>RECOVERY AMOUNT</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>";
                echo "<td>" . $row['DATE'] . "</td>";
                echo "<td>" . $row['TIME'] . "</td>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['NAME'] . "</td>";
                echo "<td>" . $row['SHIFT'] . "</td>";
                echo "<td>" . $row['BOOTH'] . "</td>";
                echo "<td>" . $row['SYS_COLLECTION'] . "</td>";
                echo "<td>" . $row['CORRECT_TOLL'] . "</td>";
                echo "<td>" . $row['CASH_COLLECTION'] . "</td>";
                echo "<td>" . $row['MANUAL_COLLECTION'] . "</td>";
                echo "<td>" . $row['RECOVERY_AMOUNT'] . "</td>";
                echo "</tr>";
            }
            echo "</table></div>";
        }
        mysqli_close($link);
    }
    ?>
</center>

</html>