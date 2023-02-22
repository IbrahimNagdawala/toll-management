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
        Special Exemption Registration</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        table{
            color:black;
            background-color: white;
            border:2px solid black;
        }
    </style>
</head>

<body>
    <center><br>
        <h2>Special Exemption Registration</h2>
        <br>
        <form method='POST'>
            <br><br>
            Name of Vehicle Owner : <input type='text' name='name' placeholder="Owner's Name" required=True><br><br>
            Mobile No. : <input type="text" name='mobile' placeholder='Mobile Number' required=True><br><br>
            Address : <input type='text' name='address' placeholder='Address' required=True><br><br>
            Vehicle Type : <select name="v_type">
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
            <br><br>
            Vehicle Number : <input type='text' name='v_number' placeholder="Vehicle Number" required=True><br><br>
            <input type='submit' name='submit' value='submit'>
            <br><br>
        </form>
        <?php
        if (isset($_POST["submit"])) {
            $name = addslashes($_POST['name']);
            $mobile = addslashes($_POST['mobile']);
            $address = addslashes($_POST['address']);
            $v_type = addslashes($_POST['v_type']);
            $v_number = addslashes($_POST['v_number']);

            $sql = "Insert into sp_exemption (NAME,VEHICLE_TYPE,VEHICLE_NUMBER,MOBILE,ADDRESS) VALUES ('$name','$v_type','$v_number','$mobile','$address')";
            if ($res = mysqli_query($link, $sql)) {
                echo "<br><br>Registered This Vehicle as Special Exemption Successfully<br><br>";
                echo "<table border='2px'>";
                echo "<tr>
                <th>Name</th> <th>Mobile</th> <th>Address</th> <th>Vehicle Type</th> <th>Vehicle Number</th>
                </tr>";
                echo "<tr>
                <th>$name</th> <th>$mobile</th> <th>$address</th> <th>$v_type</th> <th>$v_number</th>
                </tr>";

                echo "</table>";
            } else {
                echo "<br><br>Error : Something Went Wrong!!";
            }
            mysqli_close($link);
        }
        ?>

    </center>

</body>

</html>