<!DOCTYPE html>
<html>

<head>
    <title>
        Save Or Edit </title>
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
<div id='demo' style='float:left;'></div>
    <center><?php


            require_once "config.php";
            session_start();
            if(!isset($_SESSION['loggedin'])){
                header("Location:login_form.php");
            }

            if($_SESSION['type']!="staff"){

        exit("You are not allowed to access this functionality");

    }
            // date and time 
            date_default_timezone_set("Asia/Kolkata");
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
            if (isset($_POST["submit"])) {

                $v_number = addslashes($_POST["v_number"]);
                if($v_number==$_SESSION["prev_v_number"] && $_SESSION['humancheck']==0){
                    
                    header("location:dashboard.php");
                }
                else{
                if ($_POST["journey_type"] == "monthly_pass") {
                    $v_number_small=strtolower(($v_number));
                    $sql = "Select * from monthly_pass where VEHICLE_NUMBER='$v_number_small' ORDER BY PASS_NO DESC LIMIT 1";
                    if ($passcheck = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($passcheck) == 0) {
                            echo "<br><br><a href='dashboard.php'>Go Back</a>";
                            exit("<br><br>There is no Pass registered with this vehicle.");
                        }
                        $passcheck = mysqli_fetch_assoc($passcheck);
                        if ($passcheck['TO_DATE'] < $date) {
                            echo "<br><br><a href='dashboard.php'>Go Back</a>";
                            exit("<br><br>Pass registered with this vehicle is expired. ");
                        }
                    }
                }
                if ($_POST['journey_type'] == 'sp_exemption') {
                    $sqlab = "select * from sp_exemption where VEHICLE_NUMBER='$v_number'";
                    if ($sp = mysqli_query($link, $sqlab)) {
                        if (mysqli_num_rows($sp) == 0) {
                            echo "<br><br><a href='dashboard.php'>Go Back</a>";
                            exit("<br><br>This Vehicle is not registered for Special Exemption");
                        }
                    }
                }


                $id = $_SESSION["id"];
                $name = $_SESSION["name"];
                $booth_no = $_SESSION["booth_no"];
                $v_type = $_POST["v_type"];

                $booth_no_l = "L" . $booth_no;
                $direction = $_SESSION['direction'];

                if(!$id || !$name || !$booth_no || !$v_type || !$v_number ||!$direction || !$_POST['journey_type']){
                    header("Location:logout.php");
                }

                $sql = "SELECT SNO FROM booth_sno WHERE BOOTH='$booth_no_l'";
                if ($result = mysqli_query($link, $sql)) {
                    $row = mysqli_fetch_row($result);
                    $booth_sno = $row[0] + 1;
                    $sql = "UPDATE booth_sno SET SNO='$booth_sno' WHERE BOOTH='$booth_no_l'";
                    if (!mysqli_query($link, $sql)) {
                        echo "<br>Error in updating booth sno";
                    }
                } else {
                    echo "<br>Error in retrieving booth sno";
                }

                // Camera image holding in a php variable


                // If file upload form is submitted 

                // if (!empty($_POST["image"])) {


                //     $image = $_POST["image"];
                //     $imgContent = addslashes(file_get_contents($image));
                // }



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

                if ($_POST["journey_type"] == "return") {
                    $journey_type = "Return Journey";
                    $price = $price / 2;
                } else if ($_POST['journey_type'] == "single") {
                    $journey_type = "Single Journey";
                } else if ($_POST['journey_type'] == "monthly_pass") {
                    $journey_type = "Monthly Pass";
                    $price = 0;
                } else if ($_POST['journey_type'] == "exemption") {
                    $exemption_type = $_POST["exemption_type"];

                    $journey_type = "Exemption[" . $exemption_type . "]";
                    $price = 0;
                } else if ($_POST['journey_type'] == "sp_exemption") {
                    $journey_type = "Special Exemption";
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
                //$time = $hours.":".$minutes;

                date_default_timezone_set("Asia/Kolkata");
                $time = date("h:i:sa");
                $stime = date("h:i:s:a");

                function shift($stime)
                {
                    $array = explode(":", $stime);
                    if ($array[3] == "am" && (($array[0] >= 1 && $array[0] <= 7) || $array[0] == 12) && ($array[1] >= 0 && $array[1] <= 59)) {
                        $shift = "shift 1";
                    } else if ($array[3] == "am" && ($array[0] >= 8 && $array[0] <= 11) && ($array[1] >= 0 && $array[1] <= 59)) {
                        $shift = "shift 2";
                    } else if ($array[3] == "pm" && (($array[0] >= 1 && $array[0] <= 3) || $array[0] == 12) && ($array[1] >= 0 && $array[1] <= 59)) {
                        $shift = "shift 2";
                    } else if ($array[3] == "pm" && ($array[0] >= 4 && $array[0] <= 11) && ($array[1] >= 0 && $array[1] <= 59)) {
                        $shift = "shift 3";
                    }
                    return $shift;
                }
                $shift = shift($stime);


                

                //employee id

                $emp_id = $id;

                $booth_sno = (string)$booth_no_l . "-" . $booth_sno;
                $imgContent = "vehicle_images/$booth_sno.jpg";
                $sql = "INSERT INTO vehicle_details (DATE,TIME,EMPLOYEE_ID,NAME,V_TYPE,V_NUMBER,JOURNEY_TYPE,TOLL,IMAGE,SHIFT,BOOTH_NO,RECEIPT_NO,CORRECT_TOLL,VALIDATED,DIRECTION,OLD_V_TYPE) VALUES ('$date','$time','$emp_id','$name','$v_type','$v_number','$journey_type','$price','$imgContent','$shift','$booth_no','$booth_sno','$price','0','$direction','$v_type')";
                if (mysqli_query(
                    $link,
                    $sql
                )) {
                    echo "New record created successfully !";
                    $_SESSION["time_of_record"] = $time;
                    $_SESSION["date_of_record"] = $date;
                    $_SESSION["prev_v_number"]=$v_number;
                } else {
                    echo "<br><br>Error: " . $sql;
                }



                mysqli_close($link);

                // save image through exec function...
            if($_SESSION["booth_no"]=='1'){
                shell_exec("/usr/bin/wget http://admin:aidpl12345@192.168.1.65/ISAPI/Streaming/channels/1/picture -O /var/www/html/TOLLTAX/vehicle_images/$booth_sno.jpeg");
            }
            else if($_SESSION["booth_no"]=='2'){
                shell_exec("/usr/bin/wget http://admin:aidpl12345@192.168.1.66/ISAPI/Streaming/channels/1/picture -O /var/www/html/TOLLTAX/vehicle_images/$booth_sno.jpeg");
            }else if($_SESSION["booth_no"]=='3'){
                shell_exec("/usr/bin/wget http://admin:aidpl12345@192.168.1.67/ISAPI/Streaming/channels/1/picture -O /var/www/html/TOLLTAX/vehicle_images/$booth_sno.jpeg");
            }
            else if($_SESSION["booth_no"]=='4'){
                shell_exec("/usr/bin/wget http://admin:aidpl12345@192.168.1.68/ISAPI/Streaming/channels/1/picture -O /var/www/html/TOLLTAX/vehicle_images/$booth_sno.jpeg");
            }
            
            else{
                exit("camera ip not matched with booth no.");
            }
                
            


            echo "<br><br>";
            echo "<div class='print_container' ><table style='text-align:left; background:white;color:black;border:none;'>";
            echo "<tr>";
            echo "<td style='font-size:15px;text-align:center;' colspan='2'><b>MADHYA PRADESH ROAD DEVELOPMENT CORP. LTD.</b><br>Toll Collection by Manawar Kukshi Tollways Pvt.Ltd.<br>For Construction of Manawar Singhana Kukshi<br>MDR Road Under BOT Scheme<br></td>";
            echo "</tr>";
            echo "<tr><td colspan='2' align='center'><b><br>Toll Receipt</b></td>";
            echo "</tr>";
            echo "<tr><td>RECEIPT NO : </td>";
            echo "<td> $booth_sno</td>";
            echo "</tr>";
            echo "<tr><td>Transaction Date :</td>";
            echo "<td>&nbsp; $date &ensp; $time</td>";



            echo "<tr><td>Booth No : </td>";
            echo "<td> $booth_no</td>";
            echo "</tr>";
            echo "<tr><td>Operator ID : </td>";
            echo "<td> $id</td>";
            echo "</tr>";
            echo "<tr><td>Vehicle Type : </td>";
            echo "<td> $v_type</td>";
            echo "</tr>";
            echo "<tr><td>Vehicle No. : </td>";
            echo "<td> $v_number</td>";
            echo "</tr>";
            echo "<tr><td>Journey Type : </td>";
            echo "<td> $journey_type</td>";
            echo "</tr>";
            echo "<tr><td>Journey Direction: </td>";
            echo "<td> $direction</td>";
            echo "</tr>";
            echo "<tr><td>Trip  Amt. : </td>";
            echo "<td><b> $price</b></td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td style='text-align:center;font-weight:bold;font-style:italic;' colspan='2'><br>Happy Journey</td>";
            echo "</tr>";
            echo "</table></div>";

            echo "<form method='POST' action='edit.php' style='border:none;background:none;'>";
            echo "<br><a href='dashboard.php'  onclick='window.print()'>Print Receipt</a>";
            echo "<br><input type='submit' name='edit_current_entry' value='Edit Current Entry'> ";
            echo "</form>";
            

            
            /*shell_exec("/usr/bin/wget http://192.168.1.3:8080/photo.jpg -O /opt/lampp/htdocs/dashboard/tolltax/$booth_sno.jpeg");*/
            $_SESSION['humancheck']=0;
        }}
            ?> 
        <a href="dashboard.php">Go back</a>


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
