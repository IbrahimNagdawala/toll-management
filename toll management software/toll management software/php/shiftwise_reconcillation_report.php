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
        Shiftwise reconcillation Report
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
            background-color: white;
            color:black;
            border:2px solid black;
        }
    </style>

</head>

<body>
    <?php
    $date = date('Y-m-d');
    ?>
    <center>
        <h2>Shiftwise reconcillation Report</h2>
        <form method="POST">
            <br>
            Start Date :<?php echo "<input type='date' name='start_date' required value='$date'>"; ?>
            &ensp;
            End Date : <?php echo "<input type='date' name='end_date' required value='$date' >"; ?>

            <br><br>
            Shift : <select name='shift'>
                <option value="ALL">ALL</option>
                <option value="shift 1">Shift 1</option>
                <option value="shift 2">Shift 2</option>
                <option value="shift 3">Shift 3</option>
            </select>
            <br><br>
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
        require_once "config.php";
        if (isset($_POST["submit"])) {
            $start_date = $_POST["start_date"];
            $end_date = $_POST["end_date"];

            $shift = $_POST["shift"];


            echo "<br><br><button onclick='window.print()'>Print Report</button>";
            ?>
            <button onclick="exportTableToExcel('excel_area', filename = 'shiftwise-reconcillation-report')">Excel File</button>
            <?php
            echo "<div class='print_container'>";

            echo "<table border='2px' id='excel_area' >";
            echo "<thead>";
            echo "<tr>";
            echo "<th colspan='10'>Sarlapada, Ratlam(M.P.)<br>Toll Plaza Sarlapada,Sarwan Ratlam -Sailana-Banswada Road SH-39,Ratlam(MP)<br><br>Shiftwise Reconcillation Report</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<th colspan='10'><br>Lane : ALL &ensp; Shift : $shift &ensp; Operator : ALL &ensp; Start date : $start_date &ensp; End date : $end_date<br><br></th>";
            echo "</tr>";

            echo "<tr>";
            echo "<th>Date</th>";
            echo "<th>Shift</th>";
            echo "<th>Lane No</th>";
            echo "<th>Operator</th>";
            echo "<th>System Amt</th>";
            echo "<th>Correct Amt</th>";
            echo "<th>Cash Amt</th>";
            echo "<th>Short(-)</th>";
            echo "<th>Excess(+)</th>";
            echo "<th>Cashup By</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $sql = "select DISTINCT DATE from cash_up where DATE BETWEEN '$start_date' and '$end_date'";
            if ($dates = mysqli_query($link, $sql)) {
                $dates = mysqli_fetch_all($dates);
            }
            $grandtotal = array();
            $grandtotal[0] = 0;
            $grandtotal[1] = 0;
            $grandtotal[2] = 0;
            $grandtotal[3] = 0;
            $grandtotal[4] = 0;

            for ($i = 0; $i < sizeof($dates); $i++) { //dates
                $date = $dates[$i][0];
                for ($j = 1; $j <= 3; $j++) { //shifts
                    if ($shift == "shift $j" || $shift == "ALL") {
                        $sql1 = "Select DATE,SHIFT,BOOTH,NAME,SYS_COLLECTION,CORRECT_TOLL,CASH_COLLECTION,RECOVERY_AMOUNT,CASHUP_BY from cash_up where DATE='$date' and SHIFT='shift $j' ORDER BY BOOTH ";
                        $sql11 = "select SUM(SYS_COLLECTION),SUM(CORRECT_TOLL),SUM(CASH_COLLECTION),SUM(RECOVERY_AMOUNT) from cash_up where DATE='$date' and SHIFT='shift $j' ";
                        $sql12 = "select SUM(RECOVERY_AMOUNT) from cash_up where DATE='$date' and SHIFT='shift $j' and RECOVERY_AMOUNT > 0 ";
                        if ($res1 = mysqli_query($link, $sql1)) {
                            while ($row1 = mysqli_fetch_row($res1)) {
                                echo "<tr>";
                                echo "<td>$row1[0]</td>";
                                echo "<td>$row1[1]</td>";
                                echo "<td>$row1[2]</td>";
                                echo "<td>$row1[3]</td>";
                                echo "<td>$row1[4]</td>";
                                echo "<td>$row1[5]</td>";
                                echo "<td>$row1[6]</td>";
                                if ($row1[7] > 0) {
                                    echo "<td>0</td>";
                                    echo "<td>$row1[7]</td>";
                                } else {
                                    echo "<td>$row1[7]</td>";
                                    echo "<td>0</td>";
                                }
                                echo "<td>$row1[8]</td>";


                                echo "</tr>";
                            }

                            if ($res12 = mysqli_query($link, $sql12)) {

                                $row12 = mysqli_fetch_row($res12);
                                $excess = $row12[0];
                                if ($res11 = mysqli_query($link, $sql11)) {

                                    $row11 = mysqli_fetch_row($res11);
                                    if ($row11[0] !== NULL) {

                                        echo "<tr bgcolor='yellow' style='color:black;'>";
                                        echo "<td colspan='4'>Shift Total</td>";
                                        echo "<td>$row11[0]</td>";

                                        echo "<td>$row11[1]</td>";
                                        echo "<td>$row11[2]</td>";
                                        echo "<td>" . ($row11[3] - $excess) . "</td>";
                                        echo "<td>$excess</td>";
                                        $grandtotal[0] += $row11[0];
                                        $grandtotal[1] += $row11[1];
                                        $grandtotal[2] += $row11[2];
                                        $grandtotal[3] += $row11[3] - $excess;
                                        $grandtotal[4] += $excess;
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td colspan='9'style='border:none;' ><br></td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            echo "<tr bgcolor='orange' style='color:black;'>";
            echo "<td colspan='4'>Grand Total</td>";
            echo "<td>$grandtotal[0]</td>";
            echo "<td>$grandtotal[1]</td>";
            echo "<td>$grandtotal[2]</td>";
            echo "<td>$grandtotal[3]</td>";
            echo "<td>$grandtotal[4]</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
        ?>
    </center>
</body>

</html>