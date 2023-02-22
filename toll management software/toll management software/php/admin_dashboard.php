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
<!Doctype html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {

            margin: 0;
            padding: 0;
            background: black;
            font-family: sans-serif;
        }

        table {
            color: black;
            background-color: gold;
            border: 2px solid blueviolet;
            margin-left: 15px;
            text-align: center;
        }

        .container {
            width: 1250px;
            height: 30%;
            background: rgba(188, 37, 67, 0.7);
            margin: 20px;
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
        }

        .container .box1 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: yellow;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .box2 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: violet;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .box3 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: orange;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .box4 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: cyan;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .box5 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: pink;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;
        }

        .container .box6 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: white;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .box7 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: red;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .box8 {
            position: relative;
            width: 180px;
            height: 15vh;
            background: blue;
            margin: 5px;
            display: flex;
            box-shadow: 0px 10px 15px;

        }

        .container .content .box1 h1 {
            color: black;
            text-align: center;
        }

        .container .content .box1 .text p {
            color: white;
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
    <title>
        Administrator's Dashboard
    </title>
</head>
<!--  coding for auto refresh  in javascript-->
<script type="text/JavaScript">

    function AutoRefresh( t ) {
               setTimeout("location.reload(true);", t);
            }
        
</script>

<body onload="JavaScript:AutoRefresh(10000);">
    <?php
    require_once "config.php";
    $sql = "select ID from users";
    if ($result = mysqli_query($link, $sql)) {
        $users = mysqli_num_rows($result);
    }
    $sql = "select * from vehicle_type";
    if ($result = mysqli_query($link, $sql)) {
        $vehicle_types = mysqli_num_rows($result);
    }
    $sql = "select * from vehicle_details";
    if ($result = mysqli_query($link, $sql)) {
        $receipts = mysqli_num_rows($result);
    }
    // date and time 
    $date_time = getdate();
    $day = $date_time["mday"];
    $month = $date_time["mon"];
    $year = $date_time["year"];
    if($day<10){
        $day="0".$day;
    }
    if ($month < 10) {
        $month = "0" . $month;
    }
    $date = $year . "-" . $month . "-" . $day;
    $sql = "select * from vehicle_details where DATE='$date'";
    if ($result = mysqli_query($link, $sql)) {
        $todays_vehicles = mysqli_num_rows($result);
    }
    $sql = "select * from vehicle_details where DATE='$date'";
    if ($result = mysqli_query($link, $sql)) {
        $todays_vehicles = mysqli_num_rows($result);
    }
    $sql="Select * from monthly_pass";
    if($result=mysqli_query($link,$sql)){
        $monthly_pass=mysqli_num_rows($result);
    }


    ?>
    <div class=" container">

        <div class="content">

            <div class="box1">
                <h1 class="text"><?php
                                    echo $users;
                                    ?></h1>
                <p>Total staff</p>
            </div>
        </div>

        <div class="box2">
            <h1 class="text"><?php
                                echo $vehicle_types;
                                ?></h1>
            <p class="text">Total vehicle category</p>
        </div>
        <div class="box3">
            <h1 class="text"><?php
                                echo $monthly_pass;
                                ?></h1>
            <p class="text">Total Monthly Pass</p>
        </div>


        <div class="box4">
            <h1 class="text"><?php
                                echo $receipts;
                                ?></h1>
            </h1>
            <p class="text">Total receipt</p>
        </div>
        <div class="box5">
            <h1 class="text"><?php
                                echo $todays_vehicles;
                                ?></h1>
            <p class="text">Today's no. of vehicles</p>
        </div>



    </div>
    <center>
        <table border='2px'>
            <tr>
                <th>RECEIPT_NO</th>
                <th>DATE</th>
                <th>TIME</th>
                <th>SHIFT</th>
                <th>BOOTH NO</th>
                <th>EMPLOYEE ID</th>
                <th>NAME</th>
                <th>VEHICLE TYPE</th>
                <th>VEHICLE NUMBER</th>
                <th>JOURNEY TYPE</th>
                <th>JOURNEY DIRECTION</th>
                <th>TOLL AMOUNT</th>
                <th>IMAGE</th>

            </tr>
            <?php


            // date and time 
            $date_time = getdate();
            $day = $date_time["mday"];
            $month = $date_time["mon"];
            $year = $date_time["year"];
            if($day<10){
                $day="0".$day;
            }
            if ($month < 10) {
                $month = "0" . $month;
            }
            $date = $year . "-" . $month . "-" . $day;
            $sql = "SELECT * FROM vehicle_details WHERE DATE='$date' ORDER BY SNO DESC LIMIT 15";
            if ($result = mysqli_query($link, $sql)) {

                while ($array = mysqli_fetch_row($result)) {
                    echo "<tr><td>$array[12]</td>";
                    echo "<td>$array[1]</td>";
                    echo "<td>$array[2]</td>";
                    echo "<td>$array[10]</td>";
                    echo "<td>$array[11]</td>";
                    echo "<td>$array[3]</td>";
                    echo "<td>$array[4]</td>";
                    echo "<td>$array[5]</td>";
                    echo "<td>$array[6]</td>";
                    echo "<td>$array[7]</td>";
                    echo "<td>$array[15]</td>";
                    echo "<td>$array[8]</td>";
                    echo "<td>"; ?><div class="container1">
                        <!-- <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="data:image/jpg;charset=utf8;base64,<?php // echo base64_encode($array[9]); ?>" /> -->
                        <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="<?php echo "/var/www/html/tolltax/".$array[9]; ?>" />
                    </div>
                    </td>
                    </tr> <?php


                        }
                    }
                    mysqli_close($link);
                            ?>
        </table>

    </center>
    <!-- This is the script to make modal image -->
    <script>
        function onClick(element) {
            document.getElementById("img01").src = element.src;
            document.getElementById("modal01").style.display = "block";
        }
    </script>
    <div id="modal01" class="modal" onclick="this.style.display='none'">
        <span class="close">&times;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <div class="modal-content">
            <img id="img01" style="max-width:100%">
        </div>
    </div>
</body>

</html>
