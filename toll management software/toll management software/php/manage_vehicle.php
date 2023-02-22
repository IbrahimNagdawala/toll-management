<center><?php
        session_start();
        // checking if the user is logged in or not 
        if (!isset($_SESSION["loggedin"])) {
            header("location: login_form.php"); //redirecting the user to login form
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
        Manage Vehicles
    </title>
    <link rel="stylesheet" href="mystyle.css">

    <style>
        ul {
            list-style-type: none;

            padding: 0;




            background: rgba(40, 30, 30, 0.8);
            position: fixed;
            margin-top: 3.3%;


            overflow: hidden;
        }

        li {
            float: top;
            text-align: center;
            border:1px solid black;
            
        }

        li a:link {

            display: block;
            height: 20px;

            padding:20px;
            color: white;
            text-decoration: none;
            
            font-size: 17px;
            text-align: center;
        }

        li a:hover {
            display: block;
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid yellowgreen;
            
            color: yellow;

        }
    </style>

</head>

<body>
    <center>
        <br><br>
        <table border="2px">
            <tr>
                <td>SNO</td>
                <td>Vehicle Type</td>
                <td>Toll Amount</td>



            </tr>
            <?php
            require_once "config.php";
            $sql = "SELECT * FROM vehicle_type";
            if ($result = mysqli_query($link, $sql)) {
                $i = 1;
                while ($array = mysqli_fetch_row($result)) {
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . $array[0] . "</td>";
                    echo "<td>" . $array[1] . "</td>";

                    echo "</tr>";
                }
            }
            ?>

        </table>
        
    </center>
    <center>
        <?php

        require_once "config.php";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["submit"])) {
                //prepare sql statement
                $sql = "INSERT INTO vehicle_type (VEHICLE_TYPE,TOLL_AMOUNT) VALUES(?,?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    //bind variables to parameters
                    mysqli_stmt_bind_param($stmt, "sd", $param_vehicle, $param_amount);
                    //set variables
                    $param_vehicle = $_POST["vehicle_type"];
                    $param_amount = $_POST["toll_amount"];
                    if (mysqli_stmt_execute($stmt)) {
                        // inserted record successfully
                        echo "<br><br>Vehicle added successfully";
                    }
                    //close statement
                    mysqli_stmt_close($stmt);
                }
            }
            mysqli_close($link);
        }
        ?>

    </center>
</body>

</html>