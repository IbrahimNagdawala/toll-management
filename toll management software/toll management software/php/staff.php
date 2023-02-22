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
        Staff Members
    </title>
    <link rel="stylesheet" href="mystyle.css">

</head>

<body>
    <br><br>
    <h2 align="center">Staff Members</h2>
    <br><br><center>
    <table border="2px">
        <tr>
            <th>SNO</th>
            <th>Staff ID</th>
            <th>Staff Name</th>
            <th>Username</th>
            <th>Account Type</th>
        </tr>
        <?php
        require_once "config.php";
        $sql="SELECT ID,NAME,USERNAME,TYPE FROM users ";
        if($result=mysqli_query($link,$sql)){
            $i=1;
            while($row=mysqli_fetch_row($result)){
                echo "<tr><td>".$i++."</td>";
                echo "<td>$row[0]</td>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";
                echo "<td>$row[3]</td>";
                echo "</tr>";
            }
        }
        else{
            echo "<br><br>SomeThing Went Wrong Try Again Later ";
        }
        mysqli_close($link);
        ?>
        


    </table>
    </center>
    </body>
    </html>