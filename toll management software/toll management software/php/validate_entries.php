
<center><?php
        session_start();
        if (!isset($_SESSION["loggedin"])) {
            header("location:login_form.php");
            exit;
        }
        if ($_SESSION["type"] !== "audit") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>
<!DOCTYPE html>
<html>

<head>
    <title>
        Validate Entries
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
    </script>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        .selected {
            background-color: #4CAF50;
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

        body {
            margin: 0;
        }

        header {
            padding-top: 5px;
            background: #3500D3;
            position: fixed;
            width: 100%;
            font-size: 25px;
            height: 40px;
            color: white;

        }

        #acc_details {

            float: right;
            padding-top: 8px;
            font-size: 20px;
            display: block;



        }
    </style>
</head>

<body>
    <header><i>Toll Tax Management Software</i>
        <?php
        
        echo "<div id='acc_details'>";
        echo $_SESSION['name'];
        echo "&nbsp;(" . $_SESSION["type"] . ")";
        echo "&nbsp;";
        echo "&nbsp;";
        echo "&nbsp;";
        echo '<a href="logout.php" class="button" style= "padding-top:5px;text-decoration:none;display:block;background:black; float:right;height:27px;text-align:center;border:1px gray;color:white;" >Logout</a>';
        echo "&nbsp;";
        echo "</div>";

        ?>
    </header>
    <br><br><br>
    <a href="validate_shift_form.php">Go Back</a> 

    <br>
    <table border="2px">
        <tr>
            <th>RECEIPT NO</th>
            <th>DATE</th>
            <th>TIME</th>
            <th>STAFF ID</th>
            <th>STAFF NAME</th>
            <th>VEHICLE TYPE</th>
            <th>VEHICLE NO</th>
            <th>TOLL</th>
            <th>IMAGE</th>
            <th>EDIT</th>
            <th>ACTION</th>
        </tr>
        <?php

        if (isset($_POST["shift"])) {
            $_SESSION["target_shift"] = "shift ".$_POST["shift"];
        }
        $shift = $_SESSION["target_shift"];
        require_once "config.php";
        
        $sql0="select DATE from vehicle_details  where SHIFT='$shift' ORDER BY SNO DESC LIMIT 1";
        if($date=mysqli_query($link,$sql0)){
            $date=mysqli_fetch_row($date);
            $date=$date[0];
        }
        $sql = "select RECEIPT_NO,DATE,TIME,EMPLOYEE_ID,NAME,V_TYPE,V_NUMBER,CORRECT_TOLL,VALIDATED,IMAGE from vehicle_details where SHIFT='$shift' and DATE='$date'";


        $sqlidcheck="Select * from cash_up where DATE='$date' and SHIFT='$shift' ";
        if($idcheck=mysqli_query($link,$sqlidcheck)){
            if(mysqli_num_rows($idcheck)!=0){
                $id_array=array();
                while($row=mysqli_fetch_assoc($idcheck)){
                    array_push($id_array,$row['ID']);
                }
                $ids=join(",",$id_array);
                $ids="(".$ids.")";
                
                $sql="select RECEIPT_NO,DATE,TIME,EMPLOYEE_ID,NAME,V_TYPE,V_NUMBER,CORRECT_TOLL,VALIDATED,IMAGE from vehicle_details where SHIFT='$shift' and DATE='$date' and EMPLOYEE_ID NOT IN $ids";
            }
        }





        
        if ($result = mysqli_query($link, $sql)) {
            $receipt_array = array();
            $i = 0;
            while ($row = mysqli_fetch_row($result)) {
                if($row[8]==0) {
                    echo "<tr>";
                    echo "<td>$row[0]</td>";
                    array_push($receipt_array, $row[0]);
                    echo "<td>$row[1]</td>";
                    echo "<td>$row[2]</td>";
                    echo "<td>$row[3]</td>";
                    echo "<td>$row[4]</td>";
                    echo "<td>$row[5]</td>";
                    echo "<td>$row[6]</td>";
                    echo "<td>$row[7]</td>";
                    echo "<td>"; ?><div class="container1">
                     <?php $img_name = "/var/www/html/tolltax/" . $row[9];?> 
                <!-- <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src="data:image/jpg;charset=utf8;base64<?php echo base64_encode($row[9]); ?>" /> -->
                <img style="width:70px;height:30px;cursor:pointer" onclick="onClick(this)" class="modal-hover-opacity" src=<?php echo $img_name ?>>
            </div>
            </td><?php
                    
                    echo "<form method='POST'>";
                    echo "<td><select name='edit_v_type'>";
                    $sql = "SELECT * FROM vehicle_type";
                    if ($res = mysqli_query($link, $sql)) {

                        while ($array = mysqli_fetch_row($res)) {
                            if ($array[0] == "$row[5]") {
                                echo "<option value='$array[0]' selected>$array[0]</option>";
                            } else {
                                echo "<option value='$array[0]' >$array[0]</option>";
                            }
                        }
                        echo "<option value='cancel'>Cancel</option>";
                    }
                    $name=$_SESSION['name'];
                    echo "</select></td>";
                    echo "<input type='hidden' value='$receipt_array[$i]' name='receipt_no' >";
                    echo "<input type='hidden' value='$name' name='validated_by' >";
                    echo "<input type='hidden' value='validate_entries' name='file_check'>";

                    echo "<td><button name='button' formaction='edit_by_audit.php'>Validate</button></td>";


                    echo "</form>";
                    echo "</tr>";
                    $i++;
                }
                
                
                 
                    }
                }
                            ?>

    </table>
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