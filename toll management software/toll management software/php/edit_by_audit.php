<?php
require_once "config.php";
$validated_by=$_POST['validated_by'];
$file_check=$_POST['file_check'];
$receipt_no=$_POST["receipt_no"];
$v_type=$_POST["edit_v_type"];
$sql="SELECT V_TYPE,JOURNEY_TYPE from vehicle_details where RECEIPT_NO='$receipt_no'";
if($res=mysqli_query($link,$sql)){
    $res=mysqli_fetch_row($res);
    $orig_v_type=$res[0];
    $j_type=$res[1];
    if($orig_v_type==$v_type){
    $sql1 = "UPDATE vehicle_details set VALIDATED='1',VALIDATED_BY='$validated_by' where RECEIPT_NO='$receipt_no'";
    if (!mysqli_query($link, $sql1)) {
        echo "Error : Not able to validate ";
            if ($file_check == "validate_entries") {
                header("location:validate_entries.php");
            } else {
                header("location:view_audited_entries.php");
            }
    }else{
            if ($file_check == "validate_entries") {
                header("location:validate_entries.php");
            } else {
                header("location:view_audited_entries.php");
            }
    }
}else{
        //prepare statement to get price
        if($v_type!="cancel"){
        $sql2 = "SELECT TOLL_AMOUNT FROM vehicle_type WHERE VEHICLE_TYPE=?";
        if ($stmt = mysqli_prepare($link, $sql2)) {
            mysqli_stmt_bind_param($stmt, "s", $param_v_type);
            $param_v_type = $v_type;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $toll);
                mysqli_stmt_fetch($stmt);
            }
            $price = $toll;
            mysqli_stmt_close($stmt);
        }}else{
            $price=0;
        }
        if($j_type=="Monthly Pass" || $j_type=="Exemption[VIP]"|| $j_type == "Exemption[Police]"|| $j_type == "Exemption[Amb]"|| $j_type == "Exemption[Other]" ||  $j_type == "Exemption[Press]"||  $j_type == "Exemption[Defence]" ||  $j_type == "Exemption[Gov]" ||$j_type=='Special Exemption'){
            $price=0;
        }
        $sql1 = "UPDATE vehicle_details set VALIDATED='1', V_TYPE='$v_type',CORRECT_TOLL='$price', VALIDATED_BY='$validated_by' where RECEIPT_NO='$receipt_no'";
    
        if (!mysqli_query($link, $sql1)) {
            echo "Error : Not able to validate ";
            header("Refresh:3,url:validate_entries.php");
        } else {
            if($file_check=="validate_entries"){
                header("location:validate_entries.php");}
                else{
                    header("location:view_audited_entries.php");
                }
        }
}
}
?>
