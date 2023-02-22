<?php 
session_start();

if(!isset($_SESSION['loggedin'])){
    header("Location:login_form.php");
}
if($_SESSION['type']!="staff"){

    exit("You are not allowed to access this functionality");

}
?>
<!DOCTYPE html>
<html>

<head>
    <title>
        Edit Current Entry
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
    <a href='dashboard.php' style="margin-top:35px;float:left;">Go Back</a>
    <div id='demo' style="float:left;"></div>
    <center>
        <form method="POST" action="">
            <br>
            <br>
            Vehicle Type : <select name="v_type" onchange="check_na()">
                <?php
                require_once "config.php";
                session_start();
                $sql = "SELECT * FROM vehicle_type";
                if ($result = mysqli_query($link, $sql)) {

                    while ($array = mysqli_fetch_row($result)) {
                        echo "<option value='$array[0]'>$array[0]</option>";
                    }
                }
                ?>
            </select>
            <br>
            <br>
            Vehicle Number : <input type="text" name="v_number" placeholder="Enter the vehicle number" required=True max=8>
            <br>
            <br>
            Journey Type : <select name="journey_type" id="j_type" onclick="Exemption_Type()">
                <option value="single"> Single Journey</option>
                <!-- &nbsp;<option value="return"> Return Journey</option> -->
                &nbsp;<option value="monthly_pass"> Monthly Pass</option>

                &nbsp;<option value="exemption"> Exemption</option>
                <option value="sp_exemption"> Sp. Exemption</option>

            </select>

            <div id="exemption_type" style='display:none;'>
                <br>
                Exemption Type : <select name="exemption_type">
                    <option value="Amb">Ambulance</option>
		    <option value="Gov">Government</option>
                    <option value="Police">Police</option>
                    <option value="VIP">VIP</option>
		    <option value="Press">Press</option>
		    <option value="Defence">Defence</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            </div>
            <div id='price_na' style='display:none;'>
                <br>
                Toll Amt : <input type='float' name='price_na' placeholder='Toll Amount' value='0'>
            </div>

            <br>
            <br>
            <input type="submit" name="submit" value="submit">
        </form>
        <script>
            function Exemption_Type() {
                var other = document.getElementById("exemption_type");
                if (document.forms[0].journey_type.options[document.forms[0].journey_type.selectedIndex].value == "exemption") {
                    other.style.display = "block";
                } else {
                    other.style.display = "none";
                }
            }

            function check_na() {
                var input = document.getElementById("price_na");
                if (document.forms[0].v_type.options[document.forms[0].v_type.selectedIndex].value == "NA") {
                    input.style.display = "block";
                } else {
                    input.style.display = "none";
                }
            }
        </script>

        <?php
        if (isset($_POST["submit"])) {
            // date and time 
            $date_time = getdate();
            $day = $date_time["mday"];
            $month = $date_time["mon"];
            $year = $date_time["year"];
            // $hours = $date_time["hours"];
            //$minutes = $date_time["minutes"];
            /*if ($hours > 12 && $hours != 24) {
                    $hours = ($hours - 12) . ":" . $minutes . " PM";
                } else if ($hours == 24) {
                    $hours = "00:$minutes" . " AM";
                } else {
                    $hours = $hours . ":" . " AM";
                }*/
            if ($day < 10) {
                $day = "0" . $day;
            }
            if ($month < 10) {
                $month = "0" . $month;
            }
            $date = $year . "-" . $month . "-" . $day;
            $edit_v_number = addslashes($_POST["v_number"]);
            $direction = $_SESSION['direction'];
            if ($_POST["journey_type"] == "monthly_pass") {
                $v_number_small= strtolower($edit_v_number);
                $sql = "Select * from monthly_pass where VEHICLE_NUMBER='$v_number_small' ORDER BY PASS_NO DESC LIMIT 1";
                if ($passcheck = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($passcheck) == 0) {

                        exit("<br><br>There is no Pass registered with this vehicle.");
                    }
                    $passcheck = mysqli_fetch_assoc($passcheck);
                    if ($passcheck['TO_DATE'] < $date) {

                        exit("<br><br>Pass registered with this vehicle is expired. ");
                    }
                }
            }
            if ($_POST['journey_type'] == 'sp_exemption') {
                $sqlab = "select * from sp_exemption where VEHICLE_NUMBER='$edit_v_number'";
                if ($sp = mysqli_query($link, $sqlab)) {
                    if (mysqli_num_rows($sp) == 0) {
                        echo "<br><br><a href='dashboard.php'>Go Back</a>";
                        exit("<br><br>This Vehicle is not registered for Special Exemption");
                    }
                }
            }
            $edit_v_type = $_POST["v_type"];

            //prepare statement to get price

            $sql = "SELECT TOLL_AMOUNT FROM vehicle_type WHERE VEHICLE_TYPE=?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_v_type);
                $param_v_type = $_POST["v_type"];
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $toll);
                    mysqli_stmt_fetch($stmt);
                }
                $price = $toll;
                mysqli_stmt_close($stmt);
            }

            // date and time 
            $date_time = getdate();
            $day = $date_time["mday"];
            if ($day < 10) {
                $day = "0" . $day;
            }
            if ($month < 10) {
                $month = "0" . $month;
            }
            $month = $date_time["mon"];
            $year = $date_time["year"];
            /*$hours = $date_time["hours"];
            $minutes = $date_time["minutes"];
            if ($hours > 12 && $hours != 24) {
                $hours = ($hours - 12) . ":" . $minutes . " PM";
            } else if ($hours == 24) {
                $hours = "00:$minutes" . " AM";
            } else {
                $hours = $hours . ":" . " AM";
            }
            $date = $day . "/" . $month . "/" . $year;
            $time = $hours;*/
            $date = $year . "-" . $month . "-" . $day;
            //$time = $hours.":".$minutes;

            date_default_timezone_set("Asia/Kolkata");
            $time = date("h:i:sa");





            $edit_time = $_SESSION["time_of_record"];
            if ($_POST["journey_type"] == "return") {
                $edit_j_type = "Return Journey";
                $price = $price / 2;
            } else if ($_POST["journey_type"] == "single") {
                $edit_j_type = "Single Journey";
            } else if ($_POST["journey_type"] == "monthly_pass") {
                $edit_j_type = "Monthly Pass";
                $price = 0;
            } else if ($_POST["journey_type"] == "exemption") {
                $exemption_type = $_POST["exemption_type"];
                $edit_j_type = "Exemption[" . $exemption_type . "]";
                $price = 0;
            } else if ($_POST["journey_type"] == "sp_exemption") {
                $edit_j_type = "Special Exemption";
                $price = 0;
            }
            //// price for NA
            if ($_POST['v_type'] == 'NA') {
                if (($_POST['price_na']) == NULL) {
                    echo "<br><br><a href='dashboard.php'>Go Back</a>";
                    exit("<br><br>Please enter toll amount for NA vehicle Class");
                }
                $price = $_POST['price_na'];
            }

            $sql = "UPDATE vehicle_details SET TIME='$time' , V_TYPE='$edit_v_type',V_NUMBER='$edit_v_number',TOLL='$price',JOURNEY_TYPE='$edit_j_type' ,CORRECT_TOLL='$price', OLD_V_TYPE='$edit_v_type'
                    WHERE  EMPLOYEE_ID = " . $_SESSION['id'] . " and DATE='$date' ORDER BY SNO DESC LIMIT 1";

            if (mysqli_query($link, $sql)) {


                echo "<br> Record Edited successfully !";
                //prepare the select statement
                $sql1 = "SELECT RECEIPT_NO FROM vehicle_details WHERE EMPLOYEE_ID= " . $_SESSION["id"] . " and V_NUMBER='$edit_v_number' AND DATE='$date' ORDER BY SNO DESC LIMIT 1";
                if ($res = mysqli_query($link, $sql1)) {
                    $row = mysqli_fetch_row($res);
                    $booth_sno = $row[0];
                } else {
                    echo "nahi chala";
                }


                mysqli_close($link);



                echo "<br><br>";
                echo "<div class='print_container'><table style='text-align:left; background:white;color:black;border:none;'>";
                echo "<tr>";
                echo "<td style='font-size:15px;text-align:center;' colspan='2'><b>MADHYA PRADESH ROAD DEVELOPMENT CORP. LTD.</b><br>Toll Collection by Manawar Kukshi Tollways Pvt.Ltd.<br>For Construction of Manawar Singhana Kukshi<br>MDR Road Under BOT Scheme<br></td>";
                echo "</tr>";
                echo "<tr><td colspan='2' align='center'><b><br>Toll Receipt</b></td>";
                echo "</tr>";
                echo "<tr><td>RECEIPT NO : </td>";
                echo "<td>$booth_sno</td>";
                echo "</tr>";
                echo "<tr><td>Transaction Date : </td>";
                echo "<td>&nbsp;$date  &ensp; $time</td>";
                echo "</tr>";

                echo "<tr><td>Booth No : </td>";
                echo "<td>" . $_SESSION['booth_no'] . "</td>";
                echo "</tr>";
                echo "<tr><td>Operator ID : </td>";
                echo "<td>" . $_SESSION["id"] . "</td>";
                echo "</tr>";
                echo "<tr><td>Vehicle Type : </td>";
                echo "<td>$edit_v_type</td>";
                echo "</tr>";
                echo "<tr><td>Vehicle No. : </td>";
                echo "<td>$edit_v_number</td>";
                echo "</tr>";
                echo "<tr><td>Journey Type : </td>";
                echo "<td>$edit_j_type</td>";
                echo "</tr>";
                echo "<tr><td>Journey Direction : </td>";
                echo "<td>$direction</td>";
                echo "</tr>";
                echo "<tr><td>Trip Amount : </td>";
                echo "<td><b>$price</b></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td style='text-align:center;font-weight:bold;font-style:italic;' colspan='2'><b><br>Happy Journey</b></td>";
                echo "</tr>";
                echo "</table></div>";
                echo "<br><a href='dashboard.php' onclick='window.print()' >Print receipt</a>";
            } else {
                echo "Something went wrong !";
            }
        }





        ?>

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
