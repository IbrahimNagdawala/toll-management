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
        Generate Report
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
    <h2>Summary Report</h2>
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


            echo "From Date : <input type='date' name='from_date' value='$today_date'  >";
            echo "&nbsp; &nbsp; To Date : <input type='date' name='to_date' value='$today_date'>";

            echo "<br><br>";







            ?>
            <br><br>
            Shift : <select name="shift">
                <option value="ALL">ALL</option>
                <option value="shift 1">Shift 1</option>
                <option value="shift 2">Shift 2</option>
                <option value="shift 3">Shift 3</option>
            </select>
            <br><br>
            <?php 
            $sqlbo="select COUNT(BOOTH) from booth_sno";
            if($la=mysqli_query($link,$sqlbo)){
                $la=mysqli_fetch_row($la);
                $la=$la[0];
            }
        
            ?>
            Lane No. : <select name='lane'> 
            <?php
            echo "<option value='ALL'>ALL</option>";
            for($i=1;$i<=$la;$i++){
                echo "<option value='$i'>$i</option>";
            } 
            ?></select> 
            <br><br>

            <input type="submit" name="submit" value="Generate Report">
            <br><br>
        </form>
        <br><br>
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
                    $shift = $_POST["shift"];
                    $from_date = $_POST["from_date"];
                    $to_date = $_POST["to_date"];
                    $lane=$_POST['lane'];
                    $sqlshift="select COUNT(SNO) , SUM(VALIDATED) from vehicle_details where (DATE BETWEEN '$from_date' and '$to_date')";
                    if($shift!=="ALL"){
                        $sqlshift=$sqlshift." and SHIFT='$shift'";
                    }
                    if($shiftcheck=mysqli_query($link,$sqlshift)){
                        
                        $shiftcheck = mysqli_fetch_row($shiftcheck);
                        if($shiftcheck[0]==0){
                            exit("<br><br> No entries found in shift entered by you");
                        }
                        if($shiftcheck[0]!==$shiftcheck[1]){
                            exit("<br><br> Shift selected by you is not validated completely");
                        }
                    }

                    echo "<br><br><button onclick='window.print()'>Print Report</button>";
                    ?>
                    <button onclick="exportTableToExcel('excel_area', filename = 'summary_report')">Excel File</button>
                    <?php
                    $file=fopen('summary_report_no.txt',"r");
                    $report_no=(int)file_get_contents("summary_report_no.txt");
                     $report_no++;
                    fclose($file);
                    $file=fopen("summary_report_no.txt","w");
                    fwrite($file,$report_no);
                    fclose($file);

                    
                    
                    $sql = "Select VEHICLE_TYPE from vehicle_type";
                    

                   echo "<div class='print_container'><table border='2px' id='excel_area'>
            <thead>

                <tr>
                    <th colspan='4' style='border:none;'>Sarlapada, Ratlam(M.P.)<br>Toll Plaza Sarlapada,Sarwan Ratlam -Sailana-Banswada Road SH-39,Ratlam(MP)<br>Summary Report</th>
                </tr>
                <tr>
                    <td colspan='1' style='border:none;'>$from_date to $to_date  </td>
                    <td style='border:none;' >Lane : $lane </td>
                    <td style='border:none;'>Report No. $report_no</td>
                    <td style='border:none;'>Shift:$shift </td>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <th>Vehicle Type</th>
                    <th>Journey Type</th>
                    <th>No. of Entries</th>
                    <th>Amount(in Rs)</th>

                </tr>";
                    
                    if ($result = mysqli_query($link, $sql)) {
                        while ($row = mysqli_fetch_row($result)) {
                            if ($shift == "ALL") {
                                $sql = "select COUNT(SNO),SUM(CORRECT_TOLL) from vehicle_details where (DATE BETWEEN '$from_date' and '$to_date') and V_TYPE='$row[0]'";
                            } else {
                                $sql = "select COUNT(SNO),SUM(CORRECT_TOLL) from vehicle_details where (DATE BETWEEN '$from_date' and '$to_date') and V_TYPE='$row[0]' and SHIFT='$shift'";
                            }
                            if($lane!="ALL"){
                                $sql=$sql." and BOOTH_NO='$lane' ";
                            }
                            $sql1 = $sql . " and JOURNEY_TYPE='Single Journey'";
                            $sql2 = $sql . " and JOURNEY_TYPE='Return Journey'";
                            $sql3 = $sql . " and JOURNEY_TYPE='Monthly Pass'";
                            $sql4 = $sql . " and JOURNEY_TYPE LIKE 'Exemption%'";
                            $sql5 = $sql . " and JOURNEY_TYPE='Special Exemption'";
                            if ($res1 = mysqli_query($link, $sql1)) {
                                $fetch1 = mysqli_fetch_row($res1);
                            }
                            if ($res2 = mysqli_query($link, $sql2)) {
                                $fetch2 = mysqli_fetch_row($res2);
                            }
                            if ($res3 = mysqli_query($link, $sql3)) {
                                $fetch3 = mysqli_fetch_row($res3);
                            }
                            if ($res4 = mysqli_query($link, $sql4)) {
                                $fetch4 = mysqli_fetch_row($res4);
                            }
                            if ($res5 = mysqli_query($link, $sql5)) {
                                $fetch5 = mysqli_fetch_row($res5);
                            }
                            echo "<tr>";
                            echo "<td>$row[0]</td>";
                            echo "<td>Single<br>Return<br>Monthly Pass<br>Exemption<br>Special Exemption </td>";
                            echo "<td>$fetch1[0]<br>$fetch2[0]<br>$fetch3[0]<br>$fetch4[0]<br>$fetch5[0]</td>";
                            echo "<td>";
                            if($fetch1[1]==NULL){
                                echo "0<br>";
                            }else{
                                echo $fetch1[1]."<br>";
                            }
                            if ($fetch2[1] == NULL) {
                                echo "0<br>";
                            } else {
                                echo $fetch2[1] . "<br>";
                            }
                            if ($fetch3[1] == NULL) {
                                echo "0<br>";
                            } else {
                                echo $fetch3[1] . "<br>";
                            }
                            if ($fetch4[1] == NULL) {
                                echo "0<br>";
                            } else {
                                echo $fetch4[1] . "<br>";
                            }
                            if ($fetch5[1] == NULL) {
                                echo "0<br>";
                            } else {
                                echo $fetch5[1] . "<br>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    if ($shift == "ALL") {
                        $sql = "select SUM(CORRECT_TOLL),COUNT(SNO) from vehicle_details where (DATE BETWEEN '$from_date' and '$to_date') and V_TYPE!='cancel'";
                    } else {
                        $sql = "select SUM(CORRECT_TOLL),COUNT(SNO) from vehicle_details where (DATE BETWEEN '$from_date' and '$to_date')  and SHIFT='$shift' and V_TYPE!='cancel'";
                    }
                    if($lane!="ALL"){
                        $sql=$sql." and BOOTH_NO='$lane' ";
                    }
                    if ($outcome = mysqli_query($link, $sql)) {
                        $total = mysqli_fetch_row($outcome);
                    }
                    $sqlr="select SUM(RECOVERY_AMOUNT) from cash_up where (DATE BETWEEN '$from_date' and '$to_date')";
                    if($shift!=="ALL"){
                        $sqlr=$sqlr." and SHIFT='$shift'";
                    }
                    if($lane!="ALL"){
                        $sqlr=$sqlr." and BOOTH='$lane' ";
                    }
                    if($rec_amt=mysqli_query($link,$sqlr)){
                        $rec_amt=mysqli_fetch_row($rec_amt);
                        $rec_amt=$rec_amt[0];
                    }
                    echo "<tr>
                <td colspan='4'>Total Traffic Count : $total[1] &ensp;Total Amount : $total[0] &emsp; Recovery Amount : $rec_amt </td>
            </tr>";
                }
                

                ?>
            </tbody>

        </table></div>
    </center>

</body>

</html>