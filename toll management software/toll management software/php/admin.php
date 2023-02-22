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
        Administrator's Cabin
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #acc_details {

            float: right;
            padding-top: 8px;
            font-size: 20px;
            display: block;



        }


        iframe {
            margin-top: 3.1%;
            margin-left: 9%;
            float: right;

        }


        ul {
            list-style-type: none;

            padding: 0;

            height: 100%;
            width: 18%;

            background: rgba(40, 30, 30, 0.8);
            position: absolute;
            margin-top: 3.3%;



        }

        li {
            float: top;
            text-align: center;

        }



        li a:link {

            display: block;
            height: 15px;

            padding: 15px;
            color: white;
            text-decoration: none;
            font-size: 17px;
            text-align: center;





        }

        .button {
            color: white;
        }



        li a:hover {
            display: block;
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid yellowgreen;
            font-size: 23px;
            color: yellow;

        }

        .active {
            background: #4CAF50;
            color: white;
            display: block;
            border-right: 2px solid gray;


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

        body {
            margin: 0;
            background: black;
        }

        /* Dropdown Button */
        .dropbtn {
            background-color: rgba(40, 30, 30, 0.8);
            width: 100%;
            height: 35px;
            text-align: center;



            color: white;
            padding: 16px;
            font-size: 17px;
            border: none;
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
            position: relative;
            display: inline-block;
            width: 100%;
            align-items: center;

        }


        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {

            display: none;
            position: absolute;
            background: rgba(0, 30, 30, 1);

            min-width: 18%;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: white;

            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: gray;
            color: white;
            font-size: 20px;

        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
            width: 220px;

        }

        /* Change the background color of the dropdown button when the dropdown content is shown */
        .dropdown:hover .dropbtn {
            display: block;
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid yellowgreen;
            font-size: 15px;
            color: yellow;
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
        echo '<a href="logout.php" class="button" style= "padding-top:5px;text-decoration:none;display:block;background:black; float:right;height:27px;text-align:center;border:1px gray;" >Logout</a>';
        echo "&nbsp;";
        echo "</div>";

        ?>
    </header>


    <ul id="navbar">
        <li><a href="admin_dashboard.php" target="iframe" class="button active">Dashboard</a></li>
        <li><a href="daily_traffic_report.php" target="iframe" class="button">Daily Traffic Report</a></li>
        <li><a href="lane_wise_report.php" target="iframe" class="button">LaneWise Traffic Report</a></li>
        <li><a href="search_receipt.php" target="iframe" class="button">Search Receipt</a></li>
        <li><a href="traffic_revenue_report.php" target="iframe" class="button">Traffic Revenue Report</a></li>
        <li><a href="summary_report.php" target="iframe" class="button">Summary Report</a></li>
        <li><a href="shiftwise_reconcillation_report.php" target="iframe" class="button">Reconcillation Report</a></li>
        <li><a href="report.php" target="iframe" class="button">Transaction Report</a></li>
        <li><a href="altered_entries_report.php" target="iframe" class="button">Edited Entries Report</a></li>
        <li><a href="monthly_pass_report.php" target="iframe" class="button">Monthly Pass Report</a></li>

        <li><a href="manage_vehicle.php" target="iframe" class="button">Manage Vehicles</a></li>


        <div class="dropdown">
            <li><button class="dropbtn">Sp. Exemption<i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a style='height:8px;' href="add_sp_exemption.php" target="iframe" class="button">Add Sp. Exemption</a>
                    <a style='height:8px;' href="edit_sp_exemption.php" target="iframe" class="button">Edit Sp. Exemption</a>


                </div>
        </div>

        <!--<div class="dropdown">
            <li><button class="dropbtn">Monthly Pass <i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a style='height:8px;' href="monthly_pass_form.php" target="iframe" class="button">Add New Pass</a>
                    <a style='height:8px;' href="search_or_delete_pass.php" target="iframe" class="button">Search or Delete Pass</a>
                    <a style='height:8px;' href="view_monthly_pass_record.php" target="iframe" class="button">Monthly Pass Records</a>
                    <a style='height:8px;' href="Renew_monthly_pass.php" target="iframe" class="button">Renew Pass</a>

                </div>
        </div>-->

        <div class="dropdown">
            <li><button class="dropbtn">Manage Staff <i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a style='height:8px;' href="register_form.php" target="iframe" class="button">Add New Staff</a>
                    <a style='height:8px;' href="remove_staff.php" target="iframe" class="button">Remove Staff</a>
                    <a style='height:8px;' href="reset_other_users_password.php" target="iframe" class="button">Change Password of Staff</a>
                    <a style='height:8px;' href="staff.php" target="iframe" class="button">Staff</a>

                </div>
        </div>




    </ul>

    <div> <iframe src="admin_dashboard.php" height="100%" width="82%" name="iframe"></iframe></div>

    <script>
        var header = document.getElementById("navbar");
        var buttons = header.getElementsByClassName("button");
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("active");
                current[0].className = current[0].className.replace(" active", "");
                this.className += " active";

            });
        }
    </script>

</body>


</html>