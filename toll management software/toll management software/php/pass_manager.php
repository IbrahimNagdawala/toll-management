<center><?php
        session_start();
        if (!isset($_SESSION["loggedin"])) {
            header("location:login_form.php");
            exit;
        }
        if ($_SESSION["type"] !== "pass manager") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>
<!DOCTYPE html>
<html>

<head>
    <title>
        Pass Manager
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #acc_details {

            float: right;
            padding-top: 8px;
            font-size: 20px;
            display: block;



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

        
        


        
        iframe {
            margin-top: 3.1%;
            margin-left: 9%;
            float: right;

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
        


        

        
            
                
                   <li> <a style='height:8px;' href="monthly_pass_form.php" target="iframe" class="button">Add New Pass</a></li>
                    <li><a style='height:8px;' href="search_or_delete_pass.php" target="iframe" class="button">Search or Delete Pass</a></li>
                    <li><a style='height:8px;' href="view_monthly_pass_record.php" target="iframe" class="button">Monthly Pass Records</a></li>
                    <li><a style='height:8px;' href="Renew_monthly_pass.php" target="iframe" class="button">Renew Pass</a></li>

                
        

        




    </ul>

    <div> <iframe src="monthly_pass_form.php" height="100%" width="82%" name="iframe"></iframe></div>

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