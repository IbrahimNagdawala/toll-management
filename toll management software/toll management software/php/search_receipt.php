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
        Search Receipt by SNO
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
    </style>

</head>

<body>
    <center>
        <br>
        <form method="POST">
            <br><br>
            Enter Receipt No : <input type="text" name="receipt_no" placeholder="RECEIPT_NO">
            <br><br>
            OR 
            <br><br>
            Vehicle Number : <input type='text' name='v_number' placeholder='Vehicle Number'>
            <br><br>
            From Date : <input type='date' name='from_date'>
            To Date : <input type='date' name='to_date'>
            <br><br>
            <input type="submit" name="submit" value="Search Receipt">
            <br><br>
        </form>
        <br>
        <?php
        require_once "config.php";
        if (isset($_POST["submit"])) {
            if($_POST['receipt_no']!=NULL){
            $receipt_no = $_POST["receipt_no"];
            $sql = "SELECT * FROM vehicle_details WHERE RECEIPT_NO = '$receipt_no' ;";
            if ($result = mysqli_query($link, $sql)) {
                echo "<table  class='print_container' style='text-align:left;background:white;color:black;border:2px solid black;'>";
                echo "<tr>";
                echo "<td style='font-size:15px;text-align:center;' colspan='2'><b>Agroh Ratlam Tollway Pvt Ltd<br>MDR ( 00.00 - 43.85)km<br>Sarlapada Toll Plaza Ratlam (at 26.50)<br>Govt Notification No S.O. 1610 (E)<br></b></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='2' style='text-align:center;'><b><br>Toll Receipt</b></th>";
                echo "</tr>";
                $row = mysqli_fetch_array($result);
                echo "<tr>";
                echo "<td>RECEIPT NO : </td>";
                echo "<td>$row[12]</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>DATE : </td>";
                echo "<td>$row[1] &ensp; $row[2]</td>";
                echo "</tr>";
                
                
                echo "<tr>";

                echo "<td>BOOTH NO : </td>";
                echo "<td>$row[11]</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>Operator ID : </td>";
                echo "<td>$row[3]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Vehicle Type : </td>";
                echo "<td>$row[5]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Vehicle No. : </td>";
                echo "<td>$row[6]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Journey Type : </td>";
                echo "<td>$row[7]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Journey Direction : </td>";
                echo "<td>$row[15]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Trip Amt : </td>";
                echo "<td><b>$row[13]</b></td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Image</td>";
                echo "<td>"; ?><div class="container1">
                    <!-- <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="data:image/jpg;charset=utf8;base64,<?php // echo base64_encode($row[9]); ?>" /> -->
                    <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="<?php echo "/var/www/html/tolltax/".$row[9]; ?>" />
                </div> <?php
                        echo "</tr>";
                            echo "<tr>";
                            echo "<td style='text-align:center;font-weight:bold;font-style:italic;' colspan='2'><br>Happy Journey</td>";
                            echo "</tr>";
                        echo "</table>";
                        echo "<br><button onclick='window.print()'>Print Receipt</button>";
                    }
                }
            else{
                $v_number=$_POST['v_number'];
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $sql1="select * from vehicle_details where V_NUMBER='$v_number' and DATE BETWEEN '$from_date' AND '$to_date' ";
                if($res=mysqli_query($link,$sql1)){
                    echo "<br><button onclick='window.print()'>Print Receipt</button>";
                    echo "<table  class='print_container' style='text-align:left;background:white;color:black;border:2px solid black;'>";
                    while($row=mysqli_fetch_row($res)){
                        
                echo "<tr>";
                echo "<td style='font-size:15px;text-align:center;' colspan='2'><b>MADHYA PRADESH ROAD DEVELOPMENT CORP. LTD.</b><br>Toll Collection by Manawar Kukshi Tollways Pvt.Ltd.<br>For Construction of Manawar Singhana Kukshi<br>MDR Road Under BOT Scheme<br></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='2' style='text-align:center;'><b><br>Toll Receipt</b></th>";
                echo "</tr>";
                
                echo "<tr>";
                echo "<td>RECEIPT NO : </td>";
                echo "<td>$row[12]</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>DATE : </td>";
                echo "<td>$row[1] &ensp; $row[2]</td>";
                echo "</tr>";
                
                
                echo "<tr>";

                echo "<td>BOOTH NO : </td>";
                echo "<td>$row[11]</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>Operator ID : </td>";
                echo "<td>$row[3]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Vehicle Type : </td>";
                echo "<td>$row[5]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Vehicle No. : </td>";
                echo "<td>$row[6]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Journey Type : </td>";
                echo "<td>$row[7]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Journey Direction : </td>";
                echo "<td>$row[15]</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Trip Amt : </td>";
                echo "<td><b>$row[13]</b></td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Image</td>";
                echo "<td>"; ?><div class="container1">
                    <!-- <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="data:image/jpg;charset=utf8;base64,<?php // echo base64_encode($row[9]); ?>" /> -->
                    <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="<?php echo "http://localhost/dashboard/".$row[9]; ?>" />
                </div> <?php
                        echo "</tr>";
                            echo "<tr >";
                            echo "<td style='text-align:center;font-weight:bold;font-style:italic;' colspan='2'><br>Happy Journey</td>";
                            echo "</tr>";
                        
                        echo "<tr><br><br><td colspan='2'>------------------------------------------------------------------------</td></tr>";

                    }
                    echo "</table>";
                }
            }
            }
                        ?>
        <div id="modal01" class="modal" onclick="this.style.display='none'">
            <span class="close">&times;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <div class="modal-content">
                <img id="img01" style="max-width:100%">
            </div>
        </div>
        <!-- This is the script to make modal image -->
        <script>
            function onClick(element) {
                document.getElementById("img01").src = element.src;
                document.getElementById("modal01").style.display = "block";
            }
        </script>
    </center>
</body>

</html>