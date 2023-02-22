<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="mystyle.css">
</head>

<body>

    <?php
    require_once "config.php";
    session_start();
    $id = $_SESSION["target_id"];
    $shift = $_SESSION["target_shift"];

    date_default_timezone_set("Asia/Kolkata");
    $time = date("h:i:sa");

    $sql0 = "select DATE,BOOTH_NO,NAME from vehicle_details where EMPLOYEE_ID='$id' and SHIFT='$shift' ORDER BY SNO DESC LIMIT 1";
    if ($sol = mysqli_query($link, $sql0)) {
        $sol = mysqli_fetch_row($sol);
        $date = $sol[0];
    }

    $sql = "select SUM(TOLL),SUM(CORRECT_TOLL) from vehicle_details where EMPLOYEE_ID='$id' and SHIFT='$shift' and DATE='$date'";
    if ($out = mysqli_query($link, $sql)) {
        $out = mysqli_fetch_row($out);
        $diff = $out[0] - $out[1];
        $sys_collection = $out[0];
        $correct_toll = $out[1];



        $rs1 = $_POST["rs1"];
        $rs2 = $_POST["rs2"] * 2;
        $rs5 = $_POST["rs5"] * 5;
        $rs10 = $_POST["rs10"] * 10;
        $rs20 = $_POST["rs20"] * 20;
        $rs50 = $_POST["rs50"] * 50;
        $rs100 = $_POST["rs100"] * 100;
        $rs200 = $_POST["rs200"] * 200;
        $rs500 = $_POST["rs500"] * 500;
        $rs2000 = $_POST["rs2000"] * 2000;




        $cash_collection = $rs1 + $rs2 + $rs5 + $rs10 + $rs20 + $rs50 + $rs100 + $rs200 + $rs500 + $rs2000;

        //denomination of coins/notes in variables
        $rs1 = $_POST["rs1"];
        $rs2 = $_POST["rs2"];
        $rs5 = $_POST["rs5"];
        $rs10 = $_POST["rs10"];
        $rs20 = $_POST["rs20"];
        $rs50 = $_POST["rs50"];
        $rs100 = $_POST["rs100"];
        $rs200 = $_POST["rs200"];
        $rs500 = $_POST["rs500"];
        $rs2000 = $_POST["rs2000"];

        //manual cash

        $mrs1 = $_POST["mrs1"];
        $mrs2 = $_POST["mrs2"] * 2;
        $mrs5 = $_POST["mrs5"] * 5;
        $mrs10 = $_POST["mrs10"] * 10;
        $mrs20 = $_POST["mrs20"] * 20;
        $mrs50 = $_POST["mrs50"] * 50;
        $mrs100 = $_POST["mrs100"] * 100;
        $mrs200 = $_POST["mrs200"] * 200;
        $mrs500 = $_POST["mrs500"] * 500;
        $mrs2000 = $_POST["mrs2000"] * 2000;

        $manual_collection_cash = $mrs1 + $mrs2 + $mrs5 + $mrs10 + $mrs20 + $mrs50 + $mrs100 + $mrs200 + $mrs500 + $mrs2000;
        $recovery_amount = $cash_collection - $sys_collection + $diff + $manual_collection_cash;
        $name = $_SESSION['name'];


        $sql3 = "UPDATE cash_up set SYS_COLLECTION='$sys_collection',CORRECT_TOLL='$correct_toll',RECOVERY_AMOUNT='$recovery_amount',TIME='$time',CASH_COLLECTION='$cash_collection',RS1='$rs1',RS2='$rs2',RS5='$rs5',RS10='$rs10',RS20='$rs20',RS50='$rs50',RS100='$rs100',RS200='$rs200',RS500='$rs500',RS2000='$rs2000', MANUAL_COLLECTION='$manual_collection_cash' , CASHUP_BY='$name'  where ID='$id' and SHIFT='$shift' and DATE='$date'";
        if ($update = mysqli_query($link, $sql3)) {

            echo "Cashup filled successfully";

            header("Refresh:1,url=cashup_staff_form.php");
        } else {
            echo "<br><br><center>error in updating records</center>";
        }
    }

    ?>
</body>

</html>