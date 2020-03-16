<?php
date_default_timezone_set('Asia/Jakarta');
include "func.php";
echo color("green","# # # # # # # # # # # # # # # # # # # # # # # \n");
echo color("yellow","[•] Time  : ".date('[d-m-Y] [H:i:s]')."   \n");
echo color("yellow","[•] \n");
echo color("yellow","[•] Lokasi: ".$location."\n");
echo color("green","# # # # # # # # # # # # # # # # # # # # # # # \n");

ulang:

echo color("nevy","(•) Nomor : ");
$no = trim(fgets(STDIN));

$otp_data = login($no);

$m_login = $otp_data['data']['message'];
$l_token = $otp_data['data']['login_token'];
$l_status = $otp_data['success'];

if($l_status == TRUE){
    echo color("green","+] Kode verifikasi sudah di kirim")."\n";
    otp:
    echo color("nevy","?] Otp: ");
    $otp = trim(fgets(STDIN));
    
    $user_data = veriflogin($otp, $l_token);

    $token = $user_data['data']['access_token'];
    $uuid = $user_data['data']['resource_owner_id'];
    $customer = $user_data['data']['customer'];

    if($token){
        echo color("green","+] Berhasil login");

        echo "\n".color("yellow","+] Your access token : ".$l_token."\n\n");
        save("token.txt",$l_token);
        echo "\n".color("nevy","?] Mau Redeem Voucher?: y/n ");
        $pilihan = trim(fgets(STDIN));

        if($pilihan == "y" || $pilihan == "Y"){
            echo color("red","===========(REDEEM VOUCHER)===========");
            echo "\n".color("yellow","!] Claim voc GOFOOD 15+10+5");
            echo "\n".color("yellow","!] Please wait");

            $claim = redeem('promo', $token, 'COBAGOFOOD090320A');
            $message = fetch_value($claim,'"message":"','"');
            if(strpos($claim, 'Promo kamu sudah bisa dipakai')){
                echo "\n".color("green","+] Message: ".$message);
            }else{
                echo "\n".color("red","-] Message: ".$message);    
            }
        }else{
           echo color("red","-] Otp yang anda input salah");
           echo"\n==================================\n\n";
           echo color("yellow","!] Silahkan input kembali\n");
           goto otp;
       }
   }