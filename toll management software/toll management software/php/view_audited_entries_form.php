<center><?php
        session_start();
        if (!isset($_SESSION["loggedin"])) {
            header("location:login_form.php");
            exit;
        }
        if ($_SESSION["type"] !== "audit") {
            exit("You are not allowed to access this functionality");
        }
        ?></center>

<center>

    <head>
        <link rel="stylesheet" href="mystyle.css">
    </head>
    <a href="validate_shift_form.php" style='float:left;'>Go Back</a>
    <form method='POST' action='view_audited_entries.php'>
        Shift No : <select name='shift_no'>
            <option value='shift 1'>shift 1</option>
            <option value='shift 2'>shift 2</option>
            <option value='shift 3'>shift 3</option>

        </select>
        <br><br>
        <input type='submit' name='submitshift' value='submit'>
        <br><br>

    </form>
</center>