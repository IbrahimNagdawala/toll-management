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
    <title>Monthly Pass Records</title>
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
    </style>
</head>

<body>
    <center>
        <br><br>
        <h2>Monthly Pass Records</h2>
        <br>
        <?php
        require_once "config.php";
        echo "<br><input type='button' name='button' value='Print Pass Records' onclick='window.print()'>";
        $sql = "SELECT * FROM monthly_pass";
        echo "<br><br><div class='print_container'>";
        echo "<table border='2px'>";
        echo "<tr>";
        echo "<th>PASS NO</th>";
        echo "<th>Time</th>";
        echo "<th>Date</th>";
        echo "<th>Name</th>";
        echo "<th>Mobile No</th>";
        echo "<th>Address</th>";
        echo "<th>Vehicle Type</th>";
        echo "<th>Vehicle Number</th>";
        echo "<th>From Date</th>";
        echo "<th>To Date</th>";
        echo "<th>Amount</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        $pass_no_array=array();
        if ($result = mysqli_query($link, $sql)) {
            $i=0;
            while ($array = mysqli_fetch_row($result)) {
                echo "<tr>";
                array_push($pass_no_array,$array[0]);
                echo "<td>$array[0]</td>";
                echo "<td>$array[1]</td>";
                echo "<td>$array[2]</td>";
                echo "<td>$array[3]</td>";
                echo "<td>$array[4]</td>";
                echo "<td>$array[5]</td>";
                echo "<td>$array[6]</td>";
                echo "<td>$array[7]</td>";
                echo "<td>$array[8]</td>";
                echo "<td>$array[9]</td>";
                echo "<td>$array[10]</td>";
                echo "<form method='POST'>";
                echo "<td><button name='delete'value='$pass_no_array[$i]' formaction='delete_pass.php' >Delete</td>";
                echo "</form>";
                echo "</tr>";
                $i++;
            }
        }
        echo "</table></div>";
        ?>
    </center>
</body>

</html>