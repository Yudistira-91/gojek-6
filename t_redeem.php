<?php
date_default_timezone_set('Asia/Jakarta');
include "func.php";
echo color("nevy","?] Token: ");
$token = trim(fgets(STDIN));
$claim = redeem('promo', $token, 'COBAGOFOOD090320A');
$message = fetch_value($claim,'"message":"','"');
if(strpos($claim, 'Promo kamu sudah bisa dipakai')){
    echo "\n".color("green","+] Message: ".$message);
}else{
    echo "\n".color("red","-] Message: ".$message);
    reff:
    $claim = redeem('reff', $token, 'G-75SR565');
    $message = fetch_value($claim,'"message":"','"');
    if(strpos($claim, 'Promo kamu sudah bisa dipakai')){
        echo "\n".color("green","+] Message: ".$message);
    }else{
        echo "\n".color("red","-] Message: ".$message);
    }
}

