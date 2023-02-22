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

<!DOCTYPE html>
<html>

<head>
    <title>Renew Monthly Pass</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
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

        table {
            border: 1px solid black;
            text-align: justify;
            background: white;
            color: black;
        }
    </style>
</head>

<body>
    <center>
        <br><br>
        <h2>Renew Monthly Pass</h2>
        <br>
        <form method="POST">
            <br><br>
            Enter Pass No to be Renewed : <input type="number" name="pass_no" placeholder="Pass No" required=True>
            <br><br>
            
                Amount : <input type="float" name="amount" placeholder="Amount" required=True readonly value="85">
                <br><br>
                <input type="submit" name="submit" value="Renew">
                <br><br>

        </form>
        <?php
        require_once "config.php";
        if (isset($_POST["submit"])) {


           /* function to_date($from_date)
            {
                $array = explode("-", $from_date);
                if ($array[1] == 2) {
                    if ($array[0] % 4 == 0) {
                        $array[1] = 3;
                        $array[2] = $array[2] + 1;
                    } else {
                        $array[1] = 3;
                        $array[2] = $array[2] + 2;
                    }
                } else if ($array[1] == 12) {
                    $array[0] = $array[0] + 1;
                    $array[1] = 1;
                } else if ($array[1] == 1 && ($array[2] >= 29 && $array[2] <= 31)) {
                    if ($array[0] % 4 == 0) {
                        switch ($array[2]) {
                            case 29:
                                $array[2] = 29;
                                $array[1] = 2;
                                break;
                            case 30:
                                $array[2] = 1;
                                $array[1] = 3;
                                break;
                            case 31:
                                $array[2] = 2;
                                $array[1] = 3;
                                break;
                        }
                    } else {
                        switch ($array[2]) {
                            case 29:
                                $array[2] = 1;
                                $array[1] = 3;
                                break;
                            case 30:
                                $array[2] = 2;
                                $array[1] = 3;
                                break;
                            case 31:
                                $array[2] = 3;
                                $array[1] = 3;
                                break;
                        }
                    }
                } else {
                    $array[1] = $array[1] + 1;
                }
                $to_date = implode("-", $array);
                return $to_date;
            } */

            $pass_no = $_POST["pass_no"];
            
            $amount = $_POST["amount"];
        
            date_default_timezone_set("Asia/Kolkata");
            $time = date("h:ia"); //time
            $date_time = getdate();
            $day = $date_time["mday"];
            if ($day < 10) {
                $day = "0" . $day;
            }
            
            $month = $date_time["mon"];
            if ($month < 10) {
                $month = "0" . $month;
            }
            $year = $date_time["year"];
            $date = $year . "-" . $month . "-" . $day; //date

            $from_date=$date;
            function lastdateofmonth($tdate)
            {


                // Converting string to date 
                $date = strtotime($tdate);

                // Last date of current month. 
                $lastdate = date("Y-m-t", $date);
                return $lastdate;
            }
            $to_date=lastdateofmonth($from_date);

            $sql = "UPDATE monthly_pass SET TIME='$time',FROM_DATE='$from_date',TO_DATE='$to_date',AMOUNT='$amount' WHERE PASS_NO='$pass_no'";
            if (mysqli_query($link, $sql)) {
                echo "<br><br>Pass Renewed Successfully";
                $sql = "SELECT * FROM monthly_pass WHERE PASS_NO='$pass_no'";
                echo "<br><br><div class='print_container'>";
                echo "<table border='2px'>";

                if ($result = mysqli_query($link, $sql)) {
                    $array = mysqli_fetch_assoc($result);

                    echo "<tr>
            <th style='text-align:center;' colspan='2'>AGROH RATLAM TOLLS Pvt Ltd<br>RATLAM TOLL<br>MONTHLY PASS</th>
        </tr>
        <tr>
            <td >Date:&nbsp;$date </td>
            <td >Pass No:&nbsp;$pass_no </td>
        </tr>
        <tr>
            <td >Vehicle Type:&nbsp;".$array['VEHICLE_TYPE']. "</td>
            <td>Vehicle No:&nbsp;" . $array['VEHICLE_NUMBER'] . "</td>
        </tr>
        <tr>
            <td>Owner Name:&nbsp;" . $array['NAME'] . "</td>
            <td> Mobile:&nbsp;" . $array['MOBILE_NO'] . "</td>
        </tr>
        <tr>
            <td  colspan='2'>Owner Address:&nbsp;" . $array['ADDRESS'] . "</td>
        </tr>
        <tr>
            <td >Valid From:&nbsp;$from_date </td>
            <td >Valid To:&nbsp;$to_date </td>
        </tr>
        <tr>
            <td  colspan='2'>Amount (in Rs):$amount <br><br>Authorised Sign ------------- Customer's Sign ----------</td>
        </tr>";
                }
                echo "</table></div>";
                echo "<br><br><input type='button' name='button' value='Print Pass' onclick='window.print()'><br><br>";
            } else {
                echo "<br><br>Error in Renewing the pass";
            }
        }
        mysqli_close($link);

        ?>
    </center>
</body>

</html>