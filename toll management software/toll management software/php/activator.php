<!DOCTYPE html>
<html>

<head>
    <title>
        License Key Activator
    </title>
    <link rel="stylesheet" href="mystyle.css">
</head>

<body>
    <br><br>
    <center>
        <h2>License Key Activator</h2>
        <br><br> 
        <form method="POST">
            <br><br>

            <?php
            if (!file_exists(".key")) {
                echo "<input type='checkbox' name='trial_activation' id='trial_box' value='20'> Free Trial of 20 Days";
                echo "<br><br>No. of Booths you want  : <input type='number' name='booths' placeholder='No. of Booths' required=True>";
            }
            ?>
            <br>
            <br>
            Enter The License Key : <input type="text" name="license_key" placeholder="License key" id='license_key' required>

            <script>
                document.getElementById('trial_box').onchange = function() {
                    document.getElementById('license_key').disabled = this.checked;
                };
            </script>

            <br><br>
            <input type="submit" name="submit" value="Activate Software">
            <br><br>
        </form>

        <?php
        if (isset($_POST["submit"])) {
            //no. of booths
            if(!file_exists(".key")){
            $booths=$_POST["booths"];
            require_once "config.php";

            $sqlt="CREATE TABLE booth_sno (BOOTH varchar(100) PRIMARY KEY , SNO int(255))";
            if(!$table=mysqli_query($link,$sqlt)){
                exit("<br><br> Error in creating table for booth sno");
            }
            for($i=1;$i<=$booths;$i++){
                $l="L".$i;
            $sql="INSERT INTO booth_sno (BOOTH,SNO) VALUES ('$l',0)";
            if(!mysqli_query($link,$sql)){
                echo "<br><br>Error In creating booths";
            break;
            exit;
            }
            }
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
            if (isset($_POST["trial_activation"])) {
                $file = fopen(".key", "w");
                $EndDateTime = DateTime::createFromFormat('Y-m-d', $date);
                $EndDateTime->modify('+20 days');
                $strdate = $EndDateTime->format('Y-m-d');

                fwrite($file, $strdate);
                echo "<br><br>Trial Activated for 20 Days , redirecting to login page in a few seconds";
                fclose($file);
                header("Refresh:4,url=login_form.php");
            } else {

                $license_key = $_POST["license_key"];
                if ($license_key == "aidpl@manawar") {
                    $file = fopen(".key", "w");
                    fwrite($file, $license_key);


                    fclose($file);
                    echo "<br><br> Software Activated Successfully for lifetime .<br><br>Redirecting to the login page in a few seconds";
                    header("Refresh:4,url=login_form.php");
                } else {
                    echo "<br><br> !! Invalid License Key ";
                }
            }
        }
        ?>
    </center>
</body>

</html>