<!DOCTYPE html>
<html>

<head>
    <title>
        Registration Form
    </title>
    <link rel="stylesheet" href="mystyle.css">

</head>

<body>
    <center>
        <?php
        session_start();
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("location: login_form.php"); //redirecting the user to login form
            exit;
        }
        if ($_SESSION["type"] != "administrator") {
            exit("You are not allowed to access this functionality");
        }
        ?>
        <form method="POST">
            <br><br><br><br>
            ID : <input type="text" name="id" placeholder="Enter the id" min=6 max=10 required=True>
            <br><br>
            Account Type : <select name="type">
                <option value="staff">Staff Member</option>
                <option value="administrator">Administrator</option>
                <option value="accountant">Accountant</option>
                <option value="audit">Audit</option>
                <option value="pass manager">Pass Manager</option>
            </select>
            <br><br>
            Name : <input type="text" name="name" placeholder="Enter the name" min=4 max=50 required=True>
            <br><br>
            Username (to be used for login): <input type="text" name="username" placeholder="Enter the username" min=4 max=15 required=True>
            <br><br>
            Password : <input type="password" name="password" placeholder="Password" min=8 max=16 required=True>
            <br><br>
            Confirm Password : <input type="password" name="confirm_password" placeholder="Confirm Password" min=8 max=16 required=True>
            <br><br>
            <input type="submit" name="submit" value="submit">
        </form>
        <br><br>

        <?php
        // include config file
        require_once "config.php";

        //define variables and initialize with empty values
        $username = $password = $confirm_password = "";
        $username_err = $password_err = $confirm_password_err = "";

        //Processing form data when form is submitted

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["submit"])) {
                //defining variables 
                $id = addslashes($_POST["id"]);
                $name = addslashes($_POST["name"]);
                $username = trim(addslashes($_POST["username"]));
                $password = $_POST["password"];
                $type = $_POST["type"];

                //date and time

                date_default_timezone_set("Asia/Kolkata");
                $time = date("h:i:sa"); //time
                $date_time = getdate();
                $day = $date_time["mday"];
                $month = $date_time["mon"];
                if ($day < 10) {
                    $day = "0" . $day;
                }
                if ($month < 10) {
                    $month = "0" . $month;
                }
                
                $year = $date_time["year"];
                $date = $year . "-" . $month . "-" . $day; //date

                $sql = "SELECT ID FROM users WHERE USERNAME = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    //bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);

                    //set parameters
                    $param_username = $username;

                    //attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        //store result
                        mysqli_stmt_store_result($stmt);

                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            $username_err = "This username is already taken.";
                            echo $username_err;
                        } else {
                            $username = trim(addslashes($_POST["username"]));
                        }
                    } else {
                        echo "Oops!  Something went wrong . Please try again later";
                    }
                    //close statement
                    mysqli_stmt_close($stmt);
                }
            }
            //validate password
            if (empty(trim($_POST["confirm_password"]))) {
                $confirm_password_err = "Please confirm password.";
                echo $confirm_password_err;
            } else {
                $confirm_password = trim($_POST["confirm_password"]);
                if (empty($password_err) && ($password != $confirm_password)) {
                    $confirm_password_err = "Password and Confirm Password did not match.";
                    echo $confirm_password_err;
                }
            }

            //check input errors before inserting in database
            if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

                //prepare an insert statement

                $sql = "INSERT INTO users (ID,TYPE,NAME,USERNAME,PASSWORD,DATE,TIME) VALUES (?,?,?,?,?,?,?)";


                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sssssss", $param_id, $param_type, $param_name, $param_username, $param_password, $param_date, $param_time);


                    // Set parameters
                    $param_id = $id;
                    $param_type = $type;
                    $param_name = $name;
                    $param_username = $username;
                    $param_password = hash("sha256",$password); // Creates a password hash
                    $param_date = $date;
                    $param_time = $time;
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        echo "Account created successfully";

                        // Redirect to login page
                        //header("location: login.php");
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
            // close connection
            mysqli_close($link);
        }
        ?></center>
</body>

</html>