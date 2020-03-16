<?php
date_default_timezone_set('Asia/Jakarta');
include "func.php";

echo color("nevy","?] Token: ");
$token = trim(fgets(STDIN));

echo color("red","===========(CEK VOUCHER)===========");
$cekvoucher = request('/gopoints/v3/wallet/vouchers?limit=10&page=1', $token);
$total = fetch_value($cekvoucher,'"total_vouchers":',',');
echo "\n".color("yellow","!] Total voucher ".$total." : ");
echo "\n";

for ($i=1; $i <= $total; $i++) { 
    $voc = getStr1('"title":"','",',$cekvoucher,$i);
    $expired = getStr1('"expiry_date":"','"',$cekvoucher,$i);
    echo color("green",$i.". ".$voc." [".$expired."]");
    echo "\n";
}
