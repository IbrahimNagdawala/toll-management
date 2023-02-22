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
        Edit Sp Exemption
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

        table {
            color: black;
            background-color: white;
            border: 2px solid black;
        }
    </style>
</head>

<body>
    <center>
        <br>
        <h2>Special Exemption Entries</h2>
        <br><button onclick='window.print()'>Print</button>
        <div class='print_container'>
            <table border='2px'>
                <thead>
                    <tr>

                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Number</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once "config.php";
                    $sql = 'select * from sp_exemption';
                    if ($res = mysqli_query($link, $sql)) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>";

                            echo "<td>" . $row['NAME'] . "</td>";
                            echo "<td>" . $row['MOBILE'] . "</td>";
                            echo "<td>" . $row['ADDRESS'] . "</td>";
                            echo "<td>" . $row['VEHICLE_TYPE'] . "</td>";
                            echo "<td>" . $row['VEHICLE_NUMBER'] . "</td>";
                            echo "<form method='POST'>";
                            echo "<td><button name='delete' value=" . $row['SNO'] . " formaction='delete_sp_exemption.php'>Delete</button></td>";
                            echo "</form>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </center>
</body>

</html>