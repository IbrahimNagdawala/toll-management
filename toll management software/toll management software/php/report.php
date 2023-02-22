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

        /* adding css to image */
        .modal {
            z-index: 1;
            display: none;
            padding-top: 10px;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.8)
        }

        .modal-content {
            margin: auto;
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }


        .modal-hover-opacity {
            opacity: 1;
            filter: alpha(opacity=100);
            -webkit-backface-visibility: hidden
        }

        .modal-hover-opacity:hover {
            opacity: 0.60;
            filter: alpha(opacity=60);
            -webkit-backface-visibility: hidden
        }


        .close {
            text-decoration: none;
            float: right;
            font-size: 24px;
            font-weight: bold;
            color: white
        }

        .container1 {
            width: 80px;
            display: inline-block;
        }

        .modal-content,
        #caption {

            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }


        @-webkit-keyframes zoom {
            from {
                -webkit-transform: scale(0)
            }

            to {
                -webkit-transform: scale(1)
            }
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
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
    <h2 align="center">Transaction Detail Report</h2>
    <center>
        <form method="POST">
            <br><br>
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
            function dateoflastmonth($tdate)
            {
                $array = explode("-", $tdate);
                if ($array[1] == 01) {
                    $array[0] = $array[0] - 1;
                    $array[1] = 12;
                } else {
                    $array[1] = $array[1] - 1;
                }
                $date_bef_a_month = implode("-", $array);
                return $date_bef_a_month;
            }

            echo "From Date : <input type='date' name='from_date' value='$today_date' max='$today_date' >";
            echo "&nbsp; &nbsp; To Date : <input type='date' name='to_date' value='$today_date' max='$today_date'>";
            echo "<br><br><input type='checkbox' name='last30days' value='last30days'> Last 30 days ";
            echo "<br><br>

            Staff Id : <input type='text' name='staff_id' required='True' placeholder='Enter Staff id or ALL for whole staff' size=30>";
            echo "<br><br> Select Vehicle Type : <select name='v_type'>";
            $sql = "SELECT VEHICLE_TYPE  FROM vehicle_type";
            if ($result = mysqli_query($link, $sql)) {
                echo "<option value='ALL'>ALL</option>";
                while ($array = mysqli_fetch_row($result)) {
                    echo "<option value='$array[0]'>$array[0]</option>";
                }
            }
            echo "</select>";




            ?>
            <br><br>
            Journey Type : <select name="journey_type">
                <option value="ALL">ALL</option>
                <option value="Single Journey"> Single Journey</option>
                <option value="Return Journey"> Return Journey</option>
                <option value="Monthly Pass"> Monthly Pass</option>
                <option value="Exemption"> Exemption</option>
                <option value="Special Exemption"> Sp Exemption</option>
            </select>
            &nbsp;&nbsp;
            Shift : <select name="shift">
                <option value="ALL">ALL</option>
                <option value="shift 1">Shift 1</option>
                <option value="shift 2">Shift 2</option>
                <option value="shift 3">Shift 3</option>
            </select>
            <br><br>
            Booth No : <input type="text" name="booth_no" size=48 placeholder="Enter Booth Numbers separated by commas or ALL" required=True>
            <br><br>
            Journey Direction : <select name='direction'>
                <option value='ALL'>ALL</option>
                <option value='Up'>Up</option>
                <option value='Down'>Down</option>
            </select>
            <br><br>
            <input type="submit" name="submit" value="Generate Report">
            <br><br>
        </form>
        <!-- This is the script to make modal image -->
        <script>
            function onClick(element) {
                document.getElementById("img01").src = element.src;
                document.getElementById("modal01").style.display = "block";
            }
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
            if (isset($_POST['last30days'])) {
                $param_from_date = dateoflastmonth($today_date);
                $param_to_date = $today_date;
            } else {
                $param_from_date = $_POST["from_date"];
                $param_to_date = $_POST["to_date"];
            }

            $param_id = addslashes($_POST["staff_id"]);
            $param_v_type = $_POST["v_type"];
            $str_v_type = (string)$param_v_type;
            $str_id = (string)$param_id;
            $direction=$_POST['direction'];


            if ($str_id == "ALL" && $str_v_type == "ALL") {


                $sql = "SELECT * FROM vehicle_details WHERE (DATE BETWEEN  '$param_from_date' and '$param_to_date')";
            } else if ($str_id == 'ALL') {
                $sql = "SELECT * FROM vehicle_details WHERE (DATE BETWEEN  '$param_from_date' and '$param_to_date')  and V_TYPE='$param_v_type'";
            } else if ($str_v_type == 'ALL') {
                $sql = "SELECT * FROM vehicle_details WHERE (DATE BETWEEN  '$param_from_date' and '$param_to_date') and EMPLOYEE_ID='$param_id'";
            } else {

                $sql = "SELECT * FROM vehicle_details WHERE (DATE BETWEEN  '$param_from_date' and '$param_to_date') and EMPLOYEE_ID='$param_id' and V_TYPE='$param_v_type'";
            }
            $shift = $_POST["shift"];
            switch ($shift) {
                case "ALL":
                    $sql = $sql;
                    break;
                default:
                    $sql = $sql . " and SHIFT='$shift'";
                    break;
            }
            $j_type = $_POST["journey_type"];
            switch ($j_type) {
                case "ALL":
                    $sql = $sql;
                    break;
                default:
                    $sql = $sql . " and JOURNEY_TYPE LIKE '$j_type%'";
                    break;
            }

            //booth no
            $booth_no = addslashes($_POST["booth_no"]);
            $booth_array = explode(",", $booth_no);
            if ($booth_no == "ALL") {
                $sql = $sql;
            } else {
                foreach ($booth_array as $key => $value) {
                    $booth_array[$key] = "'" . $booth_array[$key] . "'";
                }
                $booth_array[0] = "(" . $booth_array[0];
                $booth_array[sizeof($booth_array) - 1] = $booth_array[sizeof($booth_array) - 1] . ")";
                $booth_seq = implode(",", $booth_array);
                $sql = $sql . "and  BOOTH_NO IN $booth_seq";
            }

            if($direction!=="ALL"){
                $sql=$sql." and DIRECTION='$direction'";
            }


            $data = mysqli_query($link, $sql);
            if (!$data) {
                echo ("Error description: " . mysqli_error($link));
            } else {
                // caclculation of total toll amount no. of entries 
                $total_toll = 0;



                $copy = array();
                while ($array = mysqli_fetch_array($data)) {
                    array_push($copy, $array);
                    $total_toll = $total_toll + (float)$array['CORRECT_TOLL'];
                }
                $total_no_of_trans = sizeof($copy);


                echo "<br><br><button onclick='window.print()'>Print Report</button>";
                ?>
                <button onclick="exportTableToExcel('excel-area', filename = 'transaction-report.php')">Excel File</button>
                <?php
                echo "<br><br><div class='print_container'><table border='2px' id='excel-area'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th colspan='14'>Sarlapada, Ratlam(M.P.)<br>Toll Plaza Sarlapada,Sarwan Ratlam -Sailana-Banswada Road SH-39,Ratlam(MP)<br><br>Transaction Detail Report</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='14'><br>Lane : $booth_no &ensp; Shift : $shift &ensp; Operator : ALL &ensp; Start date : $param_from_date &ensp; End date : $param_to_date &ensp; Journey Direction : $direction <br><br> Vehicle Type :$param_v_type &ensp; Journey Type : $j_type &ensp; User : $str_id &ensp; Total Amount : $total_toll &ensp; Total No. of Trans. : $total_no_of_trans</th>";
                echo "</tr>";
                echo "<tr><th>RECEIPT_NO</th>";
                echo "<th>Date</th>";
                echo "<th>Time</th>";
                echo "<th>SHIFT</th>";
                echo "<th>BOOTH NO</th>";
                echo "<th>Employee ID</th>";
                echo "<th>Name</th>";
                echo "<th>Vehicle Type</th>";
                echo "<th>Vehicle Number</th>";
                echo "<th>Journey Type</th>";
                echo "<th>Journey Direction</th>";
                echo "<th>Toll Amount(in Rs)</th>";
                echo "<th>Image</th>";
                echo "<th>Validated By</th>";
                echo "</tr>";
                echo "</thead>";


                echo "<tbody>";
                foreach ($copy as $row) {
                    echo "<tr><td>" . $row['RECEIPT_NO'] . "</td>";
                    echo "<td>" . $row['DATE'] . "</td>";
                    echo "<td>" . $row['TIME'] . "</td>";
                    echo "<td>" . $row['SHIFT'] . "</td>";
                    echo "<td>" . $row['BOOTH_NO'] . "</td>";
                    echo "<td>" . $row['EMPLOYEE_ID'] . "</td>";
                    echo "<td>" . $row['NAME'] . "</td>";
                    echo "<td>" . $row['V_TYPE'] . "</td>";
                    echo "<td>" . $row['V_NUMBER'] . "</td>";
                    echo "<td>" . $row['JOURNEY_TYPE'] . "</td>";
                    echo "<td>" . $row['DIRECTION'] . "</td>";
                    echo "<td>" . $row['CORRECT_TOLL'] . "</td>";
                    echo "<td>"; ?><div class="container1">
                        <!-- <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="data:image/jpg;charset=utf8;base64,<?php //echo base64_encode($row['IMAGE']); ?>" /> -->
                        <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="<?php echo "/var/www/html/tolltax/".$row['IMAGE']; ?>" />
                    </div>
                    </td>
                    <?php echo "<td>" . $row['VALIDATED_BY'] . "</td>";  ?>
                    </tr> <?php
                        }
                        echo "<tr bgcolor='orange'>";
                        echo "<th colspan='14' style='color:black;'>Total Toll Amount of this Report is " . $total_toll . " Rupees</th>";

                        echo "</tr>";
                        echo "</tbody>";
                        echo "</table></div>";
                    }
                }
                            ?>
        <div id="modal01" class="modal" onclick="this.style.display='none'">
            <span class="close">&times;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <div class="modal-content">
                <img id="img01" style="max-width:100%">
            </div>
        </div>


    </center>

</body>

</html>