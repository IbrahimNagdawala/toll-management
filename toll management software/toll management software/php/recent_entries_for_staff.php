<?php
session_start();
// checking if the user is logged in or not 
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_form.php"); //redirecting the user to login form
    exit;
}

if($_SESSION['type']!="staff"){

    exit("You are not allowed to access this functionality");

}
?>
<!DOCTYPE html>
<html>

<head>
    <title>
        Recent Entries
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
    </style>
</head>

<body>
    <center>
        <form method="POST" action="dashboard.php" style="float:left; border:none; width:133px;">
            <input type="submit" name="submit" value="Back to Dashboard">
            <div id='demo'></div>
        </form>
        <button onclick='window.print()' style="float:right;">Print </button>
        <div class="print_container">
            <h2>Recent Entries <br>(only Today's Entries will be Visible)</h2>


            <table border="2px">
                <tr>
                    <th>RECEIPT NO</th>
                    <th>DATE</th>
                    <th>TIME</th>
                    <th>EMPLOYEE ID</th>
                    <th>NAME</th>
                    <th>VEHICLE TYPE</th>
                    <th>VEHICLE NUMBER</th>
                    <th>JOURNEY TYPE</th>
                    <th>JOURNEY DIRECTION</th>

                </tr>
                <?php

                require_once "config.php";
                // date and time 
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
                $date = $year . "-" . $month . "-" . $day;
                $sql = "SELECT * FROM vehicle_details WHERE   EMPLOYEE_ID =" . $_SESSION["id"] . " and DATE='$date' ORDER BY SNO DESC LIMIT 30";
                if ($result = mysqli_query($link, $sql)) {

                    while ($array = mysqli_fetch_row($result)) {
                        echo "<tr><td>$array[12]</td>";
                        echo "<td>$array[1]</td>";
                        echo "<td>$array[2]</td>";
                        echo "<td>$array[3]</td>";
                        echo "<td>$array[4]</td>";
                        echo "<td>$array[5]</td>";
                        echo "<td>$array[6]</td>";
                        echo "<td>$array[7]</td>";
                        echo "<td>$array[15]</td>";
                        echo "</tr>";
                    }
                }
                mysqli_close($link);
                ?>
            </table>
        </div>

    </center>
    <script>
        // Set the date we're counting down to
        var today = new Date();
        var dd = today.getDate();

        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }
        today = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        var countDown1 = new Date(`${today} 07:59:59`);
        var countDown2 = new Date(`${today} 15:59:59`);
        var countDown3 = new Date(`${today} 23:59:59`);


        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            if (now < countDown1) {
                distance = countDown1 - now;
            } else if (now > countDown1 && now < countDown2) {
                distance = countDown2 - now;
            } else if (now > countDown2 && now < countDown3) {
                distance = countDown3 - now;
            }

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            if ((distance / 1000) <= 152 && (distance / 1000) > 151) {
                alert("!! Be Alert ,Your Session will be ending in a few minutes.");
            }
            if ((distance / 1000) <= 150) {
                document.getElementById("demo").innerHTML = "Your Session will end after " + minutes + "m " + seconds + "s ";

            }






            // Output the result in an element with id="demo"


            // If the count down is over, write some text 
            if (distance >= 0 && distance < 500) {

                window.location.replace("logout.php");
            }
        }, 1000);
    </script>
</body>

</html>