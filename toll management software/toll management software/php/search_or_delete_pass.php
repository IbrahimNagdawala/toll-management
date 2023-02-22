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
    ?>
</center>
<!DOCTYPE html>

<html>

<head>
    <title>
        Search or Delete a Pass
    </title>

    <link rel="stylesheet" href="mystyle.css">
    <style>
        table {
            border: 1px solid black;
            text-align: justify;
            background: white;
            color: black;
        }
    </style>
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
        <h2>Search Or Delete Pass </h2>
        <form method="POST">
            <br><br>
            Enter The Pass no : <input type="number" name="pass_no" placeholder="Pass No" required=True>
            <br><br>
            <input type="submit" name="submit" value="Search Pass">
            <input type="submit" name="delete" value="Delete Pass">
            <br><br>
        </form>
        <br>
        <?php
        require_once "config.php";

        if (isset($_POST["submit"]) && $_POST["submit"] == "Search Pass") {
            echo "<input type='button' name='button' value='Print Pass' onclick='window.print()'>";

            $pass_no = $_POST["pass_no"];
            $sql = "SELECT * FROM monthly_pass WHERE PASS_NO =$pass_no";
            if ($result = mysqli_query($link, $sql)) {
                $row = mysqli_fetch_assoc($result);
                echo "<div class='print_container' ><table border='2px'>";
                echo "<tr>
            <th style='text-align:center;' colspan='2'>AGROH RATLAM TOLLS Pvt Ltd<br>RATLAM TOLL<br>MONTHLY PASS</th>
        </tr>
        <tr>
            <td >Date:" . $row['DATE'] . "</td>
            <td >Pass No: " . $row['PASS_NO'] . "</td>
        </tr>
        <tr>
            <td >Vehicle Type:" . $row['VEHICLE_TYPE'] . "</td>
            <td >Vehicle No:" . $row['VEHICLE_NUMBER'] . " </td>
        </tr>
        <tr>
            <td >Owner Name:" . $row['NAME'] . "</td>
            <td > Mobile:" . $row['MOBILE_NO'] . " </td>
        </tr>
        <tr>
            <td colspan='2'>Owner Address:" . $row['ADDRESS'] . " </td>
        </tr>
        <tr>
            <td>Valid From:" . $row['FROM_DATE'] . " </td>
            <td>Valid To:" . $row['TO_DATE'] . " </td>
        </tr>
        <tr>
            <td colspan='2'>Amount (in Rs):" . $row['AMOUNT'] . " <br><br><br>Authorised Sign ------------- Customer's Sign ----------</td>
        </tr>
                </table></div>";
            } else {
                echo "<br><br> Error : Something went wrong";
            }
            mysqli_close($link);
        }
        if (isset($_POST["delete"])) {
            $pass_no = $_POST["pass_no"];
            $sql = "DELETE FROM monthly_pass WHERE PASS_NO=$pass_no";
            if (mysqli_query($link, $sql)) {
                echo "<br><br>Pass no. $pass_no deleted successfully";
            } else {
                echo "<br><br>Error : Something went wrong";
            }
            mysqli_close($link);
        }
        ?>
    </center>
</body>

</html>