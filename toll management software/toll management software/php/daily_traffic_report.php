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
    <title>Daily Traffic Report</title>
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
            background: white;
            border: 2px solid black;
            color: black;
        }
    </style>
</head>

<body>
    <center>
        <br><br>
        <form method="POST">
            <br><br>
            <?php $date = date("Y-m-d"); ?>
            Start Date:<?php echo "<input type='date' name='start_date' required=True value='$date'>"; ?>
            &ensp;
            End Date:<?php echo "<input type='date' name='end_date' required=True value='$date'>"; ?>
            <br><br>
            Lane No. : <input type='text' name='lane_number' placeholder="Enter Lane No. Separated By Commas OR ALL for all lanes" required=True size='55'>
            <br><br>
            Shift : <select name='shift'>
                <option value='ALL'>ALL</option>
                <option value='shift 1'>shift 1</option>
                <option value='shift 2'>shift 2</option>
                <option value='shift 3'>shift 3</option>
            </select>
            <br><br>
            Journey Type : <select name='j_type'>
                <option value='ALL'>ALL</option>
                <option value='Single Journey'>Single Journey</option>
                <option value='Return Journey'>Return Journey</option>
                <option value='Monthly Pass'>Monthly Pass</option>
                <option value='Exemption%'>Exemption(ALL)</option>
                <option value='Special Exemption'>Sp Exemption</option>

            </select>
            <br><br>
            Journey Direction : <select name='direction'>
                <option value='ALL'>ALL</option>
                <option value='Up'>Up</option>
                <option value='Down'>Down</option>
            </select>





            <BR><BR>
            <input type="submit" name="submit" value="Generate Report">
            <br><br>
        </form>
        <script>
        function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
        </script>
        <?php
        if (isset($_POST["submit"])) {
            require_once "config.php";
            $start_date = $_POST["start_date"];
            $end_date = $_POST["end_date"];
            $shift = $_POST["shift"];
            $j_type = $_POST["j_type"];
            $lane_array = $_POST['lane_number'];
            $lane_array = explode(",", $lane_array);
            $direction=$_POST['direction'];


            $sqlv = "select VEHICLE_TYPE from vehicle_type";
            if ($ve = mysqli_query($link, $sqlv)) {
                $v = mysqli_fetch_all($ve);
            }
            $journey_types = ['Single Journey', 'Return Journey', 'Monthly Pass', 'Exemption%', 'Special Exemption'];

            $sqld = " select distinct DATE from vehicle_details where DATE BETWEEN '$start_date' and '$end_date'";
            if ($dates = mysqli_query($link, $sqld)) {
                $dates = mysqli_fetch_all($dates);
            }

            $sqlb = "select COUNT(BOOTH) from booth_sno";
            if ($booth = mysqli_query($link, $sqlb)) {
                $booth = mysqli_fetch_row($booth);
                $booth = $booth[0];
            }
            $num = 6 + sizeof($v);
            $count = array();
            $shift_total = array();
            $lane_total = array();
            $grand_total = array();

            for ($a1 = 0; $a1 < sizeof($v); $a1++) {
                array_push($grand_total, 0);
            }
            array_push($grand_total, 0);
            echo "<br><br><button onclick='window.print()'>Print Report</button>";
            ?>
            <button onclick="exportTableToExcel('excel_area', filename = 'daily-traffic-report' )">Excel File</button>
            <?php
            echo "<div class='print_container'>";
            echo "<table border='2px' id='excel_area'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th colspan='$num' style='border:none;'>Sarlapada, Ratlam(M.P.)<br>Toll Plaza Sarlapada,Sarwan Ratlam -Sailana-Banswada Road SH-39,Ratlam(MP)<br><br>Daily Traffic Report</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<th colspan='$num'><br>Lane : " . $_POST['lane_number'] . " &ensp; Shift : $shift &ensp; Operator : ALL &ensp; Start date : $start_date &ensp; End date : $end_date<br>Journey Direction : $direction<br></th>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>DATE</th>";
            echo "<th>LANE NO</th>";
            echo "<th>USER</th>";
            echo "<th>SHIFT</th>";
            echo "<th>JOURNEY TYPE</th>";
            for ($a = 0; $a < sizeof($v); $a++) {
                echo "<th>" . $v[$a][0] . "</th>";
            }
            echo "<th>TOTAL</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            for ($i = 0; $i < sizeof($dates); $i++) {  //date
                $date = $dates[$i][0];

                for ($j = 1; $j <= $booth; $j++) {   //lane
                    $sqlcl = "select DIRECTION from vehicle_details where DATE='$date' and BOOTH_NO='$j' ORDER BY SNO DESC LIMIT 1";
                    if ($resultcl = mysqli_query($link, $sqlcl)) {
                        $resultcl = mysqli_fetch_row($resultcl);
                        if ($resultcl){
                        $resdirect = $resultcl[0];}
                    }
                    if ((in_array($j, $lane_array) || in_array("ALL", $lane_array)) && ($resdirect == $direction || $direction == 'ALL')) {
                        $lane = $j;
                        $lane_total = array();
                        for ($abc = 0; $abc < sizeof($v); $abc++) {
                            array_push($lane_total, 0);
                        }
                        array_push($lane_total, 0);

                        for ($k = 1; $k <= 3; $k++) {   //shift
                            if ($shift == "shift $k" || $shift == "ALL") {
                                $shift_total = array();
                                for ($ab = 0; $ab < sizeof($v); $ab++) {
                                    array_push($shift_total, 0);
                                }
                                array_push($shift_total, 0);  //total 

                                for ($m = 0; $m < sizeof($journey_types); $m++) { //journey type
                                    if ($j_type == $journey_types[$m] || $j_type == "ALL") {
                                        $journey_type = $journey_types[$m];
                                        $sql2 = "select NAME from vehicle_details where DATE='$date' and BOOTH_NO='$lane' and SHIFT='shift $k' ORDER BY SNO DESC LIMIT 1";

                                        if ($res2 = mysqli_query($link, $sql2)) {
                                            $row2 = mysqli_fetch_row($res2);
                                        }
                                        $count = array();
                                        for ($l = 0; $l < sizeof($v); $l++) {  //vehicle type
                                            $vehicle = $v[$l][0];
                                            $sql1 = "Select COUNT(SNO) from vehicle_details where DATE='$date' and BOOTH_NO='$lane'and SHIFT='shift $k' and JOURNEY_TYPE LIKE '$journey_type' and V_TYPE='$vehicle' ";

                                            if ($res1 = mysqli_query($link, $sql1)) {
                                                $row1 = mysqli_fetch_row($res1);
                                                array_push($count, $row1[0]);
                                                $shift_total[$l] += $row1[0];
                                            }
                                        }
                                        echo "<tr>";
                                        echo "<td>" . $date . "</td>";
                                        echo "<td>$lane</td>";
                                        echo "<td>$row2[0]</td>";
                                        echo "<td>shift $k</td>";
                                        echo "<td>" . $journey_types[$m] . "</td>";

                                        for ($b = 0; $b < sizeof($count); $b++) {
                                            echo "<td>$count[$b]</td>";
                                        }
                                        echo "<td>" . array_sum($count) . "</td>";
                                        echo "</tr>";
                                        $shift_total[sizeof($shift_total) - 1] += array_sum($count);
                                    }
                                }

                                echo "<tr bgcolor='yellow'>";
                                echo "<th colspan='5' style='border:none;'>USER TOTAL</th>";
                                for ($bc = 0; $bc < sizeof($count); $bc++) {
                                    echo "<td>$shift_total[$bc]</td>";
                                }
                                echo "<th>" . $shift_total[sizeof($shift_total) - 1] . "</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td colspan='$num'><br></td>";
                                echo "</tr>";

                                for ($bb = 0; $bb < sizeof($lane_total); $bb++) {
                                    $lane_total[$bb] += $shift_total[$bb];
                                }
                            }
                        }
                        echo "<tr bgcolor='blueviolet'>";
                        echo "<th colspan='5' >LANE TOTAL</th>";
                        for ($bba = 0; $bba < sizeof($lane_total); $bba++) {
                            echo "<th>$lane_total[$bba]</th>";
                            $grand_total[$bba] += $lane_total[$bba];
                        }
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='$num'><br></td>";
                        echo "</tr>";
                    }
                }
            }

            echo "<tr bgcolor='orange'>";
            echo "<th colspan='5' >GRAND TOTAL</th>";
            for ($bba = 0; $bba < sizeof($grand_total); $bba++) {
                echo "<th>$grand_total[$bba]</th>";
            }
            echo "</tr>";

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
        ?>
    </center>

</body>

</html>