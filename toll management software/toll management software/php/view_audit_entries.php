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
    <title>View Audit Entries</title>
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
            background-color: white;
            color: black;
            border: 2px solid black;
        }
    </style>
</head>

<body>
    <center>
        <h1>View Audit Entries</h1>
        <a href='validate_shift_form.php' style='float:left;'>Go Back</a>
        <form method='POST'>
            <br><br>
            Date : <?php $date = date('Y-m-d');
                    echo "<input type='date' name='date' value='$date' required=True>"; ?>
            <br><br>
            Shift : <select name='shift'>
                <option>ALL</option>
                <option value='shift 1'>shift 1</option>
                <option value='shift 2'>shift 2</option>
                <option value='shift 3'>shift 3</option>
            </select>
            <br><br>
            <input type='submit' name='submit' value='View Entries'>
            <br><br>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            require_once "config.php";
            echo "<button onclick='window.print()'>Print</button>";
            $date_of_ent = $_POST['date'];
            $shift = $_POST['shift'];
            $sql = "Select * from vehicle_details where DATE='$date_of_ent' and VALIDATED=1";
            if ($shift !== "ALL") {
                $sql = $sql . " and SHIFT='$shift'";
            }
            echo "<div class='print_container'>";
            echo "<table border='2px'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Receipt No</th>";
            echo "<th>Date</th>";
            echo "<th>Time</th>";
            echo "<th>Shift</th>";
            echo "<th>Lane No</th>";
            echo "<th>Employee ID</th>";
            echo "<th>Name</th>";
            echo "<th>Vehicle Type</th>";
            echo "<th>Vehicle No</th>";
            echo "<th>Journey Type</th>";
            echo "<th>Amount</th>";

            echo "</tr>";
            echo "</thead>";
            if ($res = mysqli_query($link, $sql)) {
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>";
                    echo "<td>" . $row['RECEIPT_NO'] . "</td>";
                    echo "<td>" . $row['DATE'] . "</td>";
                    echo "<td>" . $row['TIME'] . "</td>";
                    echo "<td>" . $row['SHIFT'] . "</td>";
                    echo "<td>" . $row['BOOTH_NO'] . "</td>";
                    echo "<td>" . $row['EMPLOYEE_ID'] . "</td>";
                    echo "<td>" . $row['NAME'] . "</td>";
                    echo "<td>" . $row['V_TYPE'] . "</td>";
                    echo "<td>" . $row['V_NUMBER'] . "</td>";
                    echo "<td>" . $row['JOURNEY_TYPE'] . "</td>";
                    echo "<td>" . $row['CORRECT_TOLL'] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
            }
            echo "</table>";
            echo "</div>";
        }
        ?>
    </center>
</body>

</html>