<center><br><br><?php
                // checking for activation of software
                if (file_exists(".key")) {
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
                    $date = $year . "-" . $month . "-" . $day;
                    $file = fopen(".key", "r");
                    $data = fgets($file);
                    $array = explode("-", $data);
                    if (sizeof($array) == 3) {
                        if ($data < $date) {
                            echo "<br><br> Free Trial is Over . Please Activate The Software With a License Key";
                            fclose($file);
                            header("Refresh:3,url=activator.php");
                        } else {
                            echo "<br><br>Trial will end after the date $data";
                            echo "<br><br>Fully Activate Now with a License Key <a href='activator.php'>Click here</a>";
                        }
                    } else {
                        if ($data !== "aidpl@manawar") {
                            echo "<br><br>Invalid Activation Key Found , Please Activate The Software With The Right One";
                            echo "<br><br> Redirecting to Activation page in a few seconds";
                            fclose($file);
                            header("Refresh:3,url=activator.php");
                        }
                    }
                } else {
                    echo "<br><br> Activation Needed, wait for a few seconds";
                    header("Refresh:2,url=activator.php");
                }

                // checking over
                ?></center>
<!DOCTYPE html>
<html>

<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
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
    </style>

</head>



<body>
    <center>
        <br>

    

        <br>
        <form method="POST">
            <br><br>
            Username : <input type="text" name="username" placeholder="Enter username">
            <br><br>
            Password : <input type="password" name="password" placeholder="Password">

            <br><br><input type="checkbox" name="admin_check" value="admin_check" id="admin_check">Admin,Accountant,Audit,check this box
            <br><br>
            
            Journey : <select name='direction' id='direction' required>
            <option name='Up'>Up</option>
            <option name='Down'>Down</option>
            </select>
            <br><br>
            <input type="submit" name="login" value="Login">
            <br><br>
        </form>
        <script>
            document.getElementById('admin_check').onchange = function() {
                document.getElementById('booth_no').disabled = this.checked;
                document.getElementById('direction').disabled=this.checked;
            };
        </script>
        <?php
        //initialize the session
        //session_start();

        // Check if the user is already logged in, if yes then redirect him to welcome page
        /*if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            if($_SESSION["type"]=="administrator"){
             header("location: admin.php");
                exit;
            }
            else{
                header("location: dashboard.php");
                exit;
            }
            
}*/

        // include config file
        require_once "config.php";

        //define variables and initialize with empty values
        $username = $password = "";
        $username_err = $password_err = "";

        //processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if username is empty
            if (empty(trim($_POST["username"]))) {
                $username_err = "Please enter username.";
                echo "<br><br>" . htmlspecialchars($username_err);
            } else {
                $username = trim($_POST["username"]);
            }

            // Check if password is empty
            if (empty(trim($_POST["password"]))) {
                $password_err = "Please enter your password.";
                echo "<br><br>" . htmlspecialchars($password_err);
            } else {
                $password = trim($_POST["password"]);
            }

            //validate credentials
            if (empty($username_err) && empty($password_err)) {

                //prepare a select statement
                $sql = "SELECT ID,TYPE,NAME,USERNAME,PASSWORD FROM users WHERE USERNAME=?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    //bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);

                    //set parameters
                    $param_username = $username;

                    //attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        //store result
                        mysqli_stmt_store_result($stmt);

                        // check if the username exists, if yes then verify password

                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // bind result variables i.e getting result of 
                            // sql statement into variables defined by us
                            mysqli_stmt_bind_result($stmt, $id, $type, $name, $username, $hashed_password);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (hash("sha256",$password)==$hashed_password) {
                                    //password is correct , so start a new session
                                    if (isset($_POST["admin_check"])) {
                                        if ($type == "staff") {
                                            exit("<br>please do not check the checkbox if you are staff");
                                        }
                                    }
                                    if(!isset($_POST['admin_check'])){
                                       /*$ip=getenv("REMOTE_ADDR");
                                        if($ip=="192.168.1.110"){
                                            $booth="1";
                                        }
					else if($ip=="192.168.1.111"){
						$booth='2';
					}else if($ip=="192.168.1.112"){
						$booth='3';
					}else if($ip=="192.168.1.113"){
						$booth='4';
					}

					else{
                                            exit("ip not matching");
                                        }*/
                                        $booth="3";
                                        
                                        
					
                                }
                                    session_start();
                                    date_default_timezone_set("Asia/Kolkata");
                                    $time = date("h:i:sa");

                                    //store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["login_time"] = $time;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["type"] = $type;
                                    $_SESSION["name"] = $name;
                                    $_SESSION["username"] = $username;
                                    $_SESSION["goldcoin"] = $hashed_password;
                                    $_SESSION["booth_no"] = $booth;
                                    $_SESSION['direction']=$_POST['direction'];
                                    $_SESSION['prev_v_number']="prev_v_number";
                                    // redirect user to the welcome page
                                    if ($_SESSION["type"] == "administrator") {
                                        header("location:admin.php");
                                        exit;
                                    } else if ($_SESSION["type"] == "audit") {
                                        header("location:validate_shift_form.php");
                                    } else if ($_SESSION["type"] == "accountant") {
                                        header("location:cashup_staff_form.php");
                                    } else if ($_SESSION["type"] == "pass manager") {
                                        header("location:pass_manager.php");
                                    } 
                                    
                                    else {
                                        header("location:dashboard.php");
                                    }
                                    exit;
                                } else {
                                    // Display an error message if password is not valid
                                    $password_err = "[!!] Invalid Password or Username";
                                    echo "<br><br>" . htmlspecialchars($password_err);
                                }
                            }
                        } else {
                            // Display an error message if username doesn't exist
                            $username_err = "[!!] Invalid Password or Username";
                            echo "<br><br> " . htmlspecialchars($username_err);
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    //close statement
                    mysqli_stmt_close($stmt);
                }
            }
            // close connection
            mysqli_close($link);
        }


        ?>
    </center>
</body>

</html>
