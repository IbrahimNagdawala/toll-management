<!-- this is dashboard of a normal staff who will be doing the entries -->
<!DOCTYPE html>
<html>

<head>
    <title>Main Page</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="mystyle.css">

    <style>
        #results {
            padding: 10px;
            border: 1px solid;
            background: #ccc;
        }

        header {
            padding-top: 5px;
            padding-left: 10px;
            background: #3500D3;
            position: fixed;
            width: 100%;
            font-size: 25px;
            height: 40px;
            color: white;
            top: 0;
            left: 0;

        }

        /*.logo{
            font-size: 20px;
            font-weight: bold;
            padding-left:20px;
            color:white;
            background-color:teal;
            height:30px;
            margin-top:1%
            
        }*/

        #date_clock {
            float: left;
            margin-top: 3%;
            height: 60px;
        }
    </style>

</head>
<header>
    <i>Toll Tax Management Software</i>
</header>


<body onload="show_clock()">
    <!-- show_clock() is a function to display live clock coded by me -->
    <?php
    // date and time 
    $date_time = getdate();
    $day = $date_time["mday"];
    $month = $date_time["mon"];
    $year = $date_time["year"];
    if ($day < 10) {
        $day = "0" . $day;
    }
    if ($month < 10) {
        $month = "0" . $month;
    }
    $date = $day . "/" . $month . "/" . $year;
    date_default_timezone_set("Asia/Kolkata");
    $time = date("h:i:sa");
    session_start();
    // checking if the user is logged in or not 
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login_form.php"); //redirecting the user to login form
        exit;
    }
if($_SESSION['type']!="staff"){
header("Location:logout.php");
}
    /*function timeofout($timen)
    {
        $time1 = strtotime("07:59:59am");
        $time2 = strtotime("03:59:59pm");
        $time3 = strtotime("11:59:59pm");


        $timen = strtotime($timen);
        if ($timen < $time1) {
            $diff = $time1 - $timen;
        } else if ($timen < $time2) {
            $diff = $time2 - $timen;
        } else {
            $diff = $time3 - $timen;
        }
        return $diff;
    }*/

    // Declaring variables 
    $login_time = $_SESSION["login_time"];
    $_SESSION["humancheck"] = 1;
    $name = $_SESSION["name"];
    $type = $_SESSION["type"];
    $id = $_SESSION["id"];
    $booth_no = $_SESSION["booth_no"];

    /*$time_left = timeofout($login_time);
    $login_time = strtotime($login_time);
    $time = strtotime($time);
    $diff = $time - $login_time;

    if ($diff >= $time_left) {
        header("location:logout.php");
    } */

    // displaying the account details in right side of the webpage
    echo "<br><aside align='right'>";
    echo "<br>$name";
    echo "<br>id: $id";
    echo "<br> $type";
    echo "<br>Booth No : $booth_no";
    echo "<br>Journey Direction : ".$_SESSION['direction'];
    echo "<br><br><a href='recent_entries_for_staff.php'>Recent Entries</a>";
    
    echo "<br><br><a href='logout.php'>Logout</a> ";
    echo "</aside>";





    // displaying date and time in left side of webpage
    echo "<aside id='date_clock' style='margin-left:20px;font-size:20px;'>";
    echo "$date<br>";
    echo "<script language='javascript' src='liveclock.js'>";
    echo "</script>";
    echo "<br><br><p style='float:left;' id='demo'></p>";
    echo "</aside>";





    ?>

    <center>
        <br><br>



        <!-- html form to take entries of vehicles to generate receipts -->
        <form method="POST" action="action.php">
            <br>

            Vehicle Type : <select name="v_type" onchange="check_na()">
                <?php
                require_once "config.php";
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
            <br><br>
            Journey Type : <select name="journey_type" id="j_type" onchange="Exemption_Type()">
                <option value="single"> Single Journey</option>
               <!--  &nbsp;<option value="return"> Return Journey</option> */ -->
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

            <div id='price_na' style='display:none;'>
                <br>
                Toll Amt : <input type='float' name='price_na' placeholder='Toll Amount' value='0' >
            </div>




            <!-- coding camera to display it as streaming. 
                    the camera will take picture when submit button of this form is pressed and  vehicle details will also be submitted
                    with it .
                -->
            <div class="row" align="center">
                <div class="col-md-6" align="center">

		<!--	<img src='http://admin:aidpl12345@192.168.1.65:80/ISAPI/Streaming/channels/101/httpPreview' width='400px' height='300px'> -->
                    <div class="col-md-12 text-center" align="center">
                        <br />

                        
                       
                        <input type="submit" name="submit" value="submit" class="btn btn-success">

                        

                    </div>
                </div>
            </div>

        </form>
        <!-- Configure a few settings and attach camera -->
        <script language="JavaScript">
                

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

    </center>


</body>


<!-- <footer class="logo">
    Code the logo here
</footer> -->




</html>
