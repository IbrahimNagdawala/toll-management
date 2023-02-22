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
    <title>
        Transaction Detail Report
    </title>
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
            color: black;
            background-color: white;
            border: 2px solid black;
        }
    </style>

</head>
<body>
<?php

            require_once "config.php";
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
            $today_date = $year . "-" . $month . "-" . $day;

?>
<center><h2>Monthly Pass Report</h2></center>
<br><br>
<center>
<form method="POST">
<br>
From Date : <?php echo "<input type='date' name='from_date' value='$today_date'>";?>
&nbsp;&nbsp;To Date : <?php echo "<input type='date' name='to_date' value='$today_date'>";?>

<br><br>
<input type="submit" name="submit" value="submit">
<br>

</form>

<?php 

if(isset($_POST['submit'])){
    $from_date=$_POST['from_date'];
    $to_date=$_POST['to_date'];

    $sql="select COUNT(PASS_NO),SUM(AMOUNT) from monthly_pass where FROM_DATE BETWEEN '$from_date' AND '$to_date' ";
    if($res=mysqli_query($link,$sql)){
        $arr=mysqli_fetch_all($res);
        echo "<br><br><button onclick='window.print()'>Print Report</button>";
        echo "<br><br><div class='print_container'><table border='2px' id='excel-area'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th colspan='2'>Sarlapada, Ratlam(M.P.)<br>Toll Plaza Sarlapada,Sarwan Ratlam -Sailana-Banswada Road SH-39,Ratlam(MP)<br><br>Monthly Pass Report<br></th></tr><tr><td colspan='2'>From Date:$from_date &emsp;  To Date:$to_date</td>";
        echo "</tr>";
        echo "<tr>";
        echo "</tr>";
        echo "<th>Total No. of Pass</th>";
        echo "<th>Total Amount(in Rs)</th>";

        echo "</thead>";

        echo "<tr>";
        echo "<td>".$arr[0][0]."</td>";
        echo "<td>".$arr[0][1]."</td>";
        echo "</tr>";
        
    }else{
        echo "Error Occurred In Fetching Details";
    }
}

?>


</center>

</body>

</html>