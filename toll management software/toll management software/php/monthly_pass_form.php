<!DOCTYPE html>
<html>

<head>
    <title>
        Monthly Pass Form
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
        table{
            border:1px solid black;
            text-align: justify;
            background:white;
            color:black;
        }
    </style>

</head>

<body>
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
        <br><br><h2 align="center">Monthly Pass Form</h2>
        <form method="POST">
            <br><br><br><br>
            Owner's Name : <input type="text" name="name" placeholder="Enter the name" required=True>
            <br><br>
            Mobile No. : <input type="number" name="mobile" placeholder="Mobile Number" required=True>
            <br><br>
            Owner's Address : <input type="text" name="address" placeholder="Enter the address" size=40 required=True>
            <br><br>
            Vehicle Type : <select name="v_type">
                <?php
                require_once "config.php";
                $sql = "SELECT VEHICLE_TYPE FROM vehicle_type";
                if ($result = mysqli_query($link, $sql)) {

                    while ($array = mysqli_fetch_row($result)) {


                        echo "<option value='$array[0]'>" . $array[0] . "</option>";
                    }
                }
                echo "</select>";
                //date and time

                date_default_timezone_set("Asia/Kolkata");
                $time = date("h:ia"); //time
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
                
            echo "<br><br>
            Vehicle Number: <input type='text' name='v_number' placeholder='Enter the vehicle number' required=True>
            <br><br>
            
            
            Amount : <input type='float' name='amount' placeholder='Amount' required=True readonly value='85'>

            
            <br><br>
            <input type='submit' name='submit' value='submit'>
            <br><br>
        </form>
        <br><br>";

        
        

        if (isset($_POST["submit"])) {

            

            $from_date = $date;

            function lastdateofmonth($tdate){
                        

                        // Converting string to date 
                        $date = strtotime($tdate);

                        // Last date of current month. 
                        $lastdate = date("Y-m-t", $date);
                        return $lastdate;

            }
            
            $to_date = lastdateofmonth($from_date);
            //DECLARE VARIABLES
            $name = $_POST["name"];
            $address = addslashes($_POST["address"]);
            $v_type = $_POST["v_type"];
            $v_number = strtolower($_POST["v_number"]);
            $mobile=$_POST["mobile"];
            $amount=$_POST["amount"];

            $sql = "INSERT INTO monthly_pass (DATE,TIME,NAME,MOBILE_NO,ADDRESS,VEHICLE_TYPE,VEHICLE_NUMBER,FROM_DATE,TO_DATE,AMOUNT) VALUES ('$date','$time','$name','$mobile','$address','$v_type','$v_number','$from_date','$to_date','$amount');";
            if (mysqli_query($link, $sql)) {
                echo "Monthly Pass created successfully<br>";
                $sql = "SELECT PASS_NO FROM monthly_pass WHERE VEHICLE_NUMBER=?  AND TIME=?";

                //prepare the select statement
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $param_v_number, $param_time);

                    $param_v_number = $v_number;

                    $param_time = $time;
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        mysqli_stmt_bind_result($stmt, $pass_no);
                        mysqli_stmt_fetch($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }


                

                echo "<br>";
                echo "<div class='print_container' ><table border='2px'>";
                echo "<tr>
            <th style='text-align:center;' colspan='2'>AGROH RATLAM TOLLS Pvt Ltd<br>RATLAM TOLL<br>MONTHLY PASS</th>
        </tr>
        <tr>
            <td >Date:&nbsp;$date </td>
            <td >Pass No:&nbsp;$pass_no </td>
        </tr>
        <tr>
            <td >Vehicle Type:&nbsp;".strtolower($v_type)."</td>
            <td>Vehicle No:&nbsp;$v_number </td>
        </tr>
        <tr>
            <td>Owner Name:&nbsp;$name </td>
            <td> Mobile:&nbsp;$mobile </td>
        </tr>
        <tr>
            <td  colspan='2'>Owner Address:&nbsp;$address </td>
        </tr>
        <tr>
            <td >Valid From:&nbsp;$from_date </td>
            <td >Valid To:&nbsp;$to_date </td>
        </tr>
        <tr>
            <td  colspan='2'>Amount (in Rs):$amount <br><br>Authorised Sign ------------- Customer's Sign ----------</td>
        </tr>
                </table></div>";


                echo "<br><br><input type='button' name='button' value='Print Pass' onclick='window.print()'>";
            }else{
                echo "Error : Something Went Wrong";
            }

            
        }


        








        ?></center>
</body>

</html>