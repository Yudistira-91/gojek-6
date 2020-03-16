<?php
date_default_timezone_set('Asia/Jakarta');
include "func.php";

$nama = nama();
$email = str_replace(" ", "", $nama) . mt_rand(100, 999);

echo color("green","# # # # # # # # # # # # # # # # # # # # # # # \n");
echo color("yellow","[•]  Time  : ".date('[d-m-Y] [H:i:s]')."   \n");
echo color("yellow","[•] \n");
echo color("yellow","[•] Nama: ".$nama."\n");
echo color("yellow","[•] Email: ".$email."\n");
echo color("yellow","[•] Lokasi: ".$location."\n");
echo color("green","# # # # # # # # # # # # # # # # # # # # # # # \n");

ulang:

echo color("nevy","(•) Nomor : ");

$no = trim(fgets(STDIN));
if (substr($no, '0', '1') == '0') {
    $no = '62'.substr($no, 1);
}

$otp_data = regis($no, $email, $nama);

if($otp_data['data']['otp_token']){
    $otptoken = $otp_data['data']['otp_token'];
    echo color("green","+] Kode verifikasi sudah di kirim")."\n";
    otp:
    echo color("nevy","?] Otp: ");
    $otp = trim(fgets(STDIN));
    
    $user_data = verifregis($otp, $otptoken);

    $token = $user_data['data']['access_token'];
    $uuid = $user_data['data']['resource_owner_id'];
    $customer = $user_data['data']['customer'];

    if($token){
        echo color("green","+] Berhasil mendaftar");
        echo "\n".color("yellow","+] Your access token : ".$token."\n\n");
        save("token.txt",$token);
        echo "\n".color("nevy","?] Mau Redeem Voucher?: y/n ");
        $pilihan = trim(fgets(STDIN));  
        if($pilihan == "y" || $pilihan == "Y"){
            echo color("red","===========(REDEEM VOUCHER)===========");
            echo "\n".color("yellow","!] Claim voc GOFOOD 15+10+5");
            echo "\n".color("yellow","!] Please wait");
            for($a=1;$a<=3;$a++){
                echo color("yellow",".");
                sleep(10);
            }
            $code1 = request('/go-promotions/v1/promotions/enrollments', $token, '{"promo_code":"COBAGOFOOD090320A"}');
            $message = fetch_value($code1,'"message":"','"');
            if(strpos($code1, 'Promo kamu sudah bisa dipakai')){
                echo "\n".color("green","+] Message: ".$message);
            }else{
                echo "\n".color("red","-] Message: ".$message);
                reff:
                $data = '{"referral_code":"G-75SR565"}';    
                $claim = request("/customer_referrals/v1/campaign/enrolment", $token, $data);
                $message = fetch_value($claim,'"message":"','"');
                if(strpos($claim, 'Promo kamu sudah bisa dipakai')){
                    echo "\n".color("green","+] Message: ".$message);
                }else{
                    echo "\n".color("red","-] Message: ".$message);
                }
            }
        }else{
            echo 'Sukses daftar';
        }
    }else{
     echo color("red","-] Otp yang anda input salah");
     echo"\n==================================\n\n";
     echo color("yellow","!] Silahkan input kembali\n");
     goto otp;
 }
}else{
 echo color("red","NOMOR SUDAH TERDAFTAR/SALAH !!!");
 echo "\nMau ulang? (y/n): ";
 $pilih = trim(fgets(STDIN));
 if($pilih == "y" || $pilih == "Y"){
     echo "\n==============Register==============\n";
     goto ulang;
 }else{
     echo "\n==============Register==============\n";
     goto ulang;
 }
}
