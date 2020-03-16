<?php
$location = "-6.2204".mt_rand(10,99).",106.779".mt_rand(100,999);

function request($url, $token = null, $data = null, $pin = null, $otpsetpin = null, $uuid = null){
    global $location;
    $header[] = "Host: api.gojekapi.com";
    $header[] = "User-Agent: okhttp/3.10.0";
    $header[] = "Accept: application/json";
    $header[] = "Accept-Language: id-ID";
    $header[] = "Content-Type: application/json; charset=UTF-8";
    $header[] = "X-AppVersion: 3.46.1";
    $header[] = "X-UniqueId: ".time()."57".mt_rand(1000,9999);
    $header[] = "Connection: keep-alive";
    $header[] = "X-User-Locale: id_ID";
    $header[] = "X-Location: $location";
    
    if ($pin):
        $header[] = "pin: $pin";
    endif;
    if ($token):
        $header[] = "Authorization: Bearer $token";
    endif;
    if ($otpsetpin):
        $header[] = "otp: $otpsetpin";
    endif;
    if ($uuid):
        $header[] = "User-uuid: $uuid";
    endif;
    $c = curl_init("https://api.gojekapi.com".$url);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    if ($data):
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_POST, true);
    endif;
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($c);
    $httpcode = curl_getinfo($c);
    if (!$httpcode)
        return false;
    else {
        $header = substr($response, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
        $body   = substr($response, curl_getinfo($c, CURLINFO_HEADER_SIZE));
    }
    $json = json_decode($body, true);
    return $body;
}

function save($filename, $content)
{
    $save = fopen($filename, "a");
    fputs($save, "$content\r\n");
    fclose($save);
}

function nama()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $ex = curl_exec($ch);
    // $rand = json_decode($rnd_get, true);
    preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
    return $name[2][mt_rand(0, 14) ];
}

function getStr($a,$b,$c){
    $a = @explode($a,$c)[1];
    return @explode($b,$a)[0];
}

function getStr1($a,$b,$c,$d){
    $a = @explode($a,$c)[$d];
    return @explode($b,$a)[0];
}

function color($color = "default" , $text)
{
    $arrayColor = array(
        'grey'      => '1;30',
        'red'       => '1;31',
        'green'     => '1;32',
        'yellow'    => '1;33',
        'blue'      => '1;34',
        'purple'    => '1;35',
        'nevy'      => '1;36',
        'white'     => '1;0',
    );  
    return "\033[".$arrayColor[$color]."m".$text."\033[0m";
}

function fetch_value($str,$find_start,$find_end) {
    $start = @strpos($str,$find_start);
    if ($start === false) {
        return "";
    }
    $length = strlen($find_start);
    $end    = strpos(substr($str,$start +$length),$find_end);
    return trim(substr($str,$start +$length,$end));
}

function redeem($jenis, $token, $kode){
    if ($jenis == 'promo') {
       return request('/go-promotions/v1/promotions/enrollments', $token, '{"promo_code":"$kode"}');
   }elseif($jenis == 'reff'){
    return request('/customer_referrals/v1/campaign/enrolment', $token, '{"referral_code":"$kode"}');
}
}
function regis($no, $email, $nama){
    if (substr($no, '0', '1') == '0') {
        $no = '62'.substr($no, 1);
    }

    $data = '{"email":"'.$email.'@gmail.com","name":"'.$nama.'","phone":"+'.$no.'","signed_up_country":"ID"}';
    $register = request("/v5/customers", null, $data);

    return objectToArray(json_decode($register));
}

function verifregis($otp, $token)
{
    $data = '{"client_name":"gojek:cons:android","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
    $verif = request("/v5/customers/phone/verify", null, $data);

    return objectToArray(json_decode($verif));
}

function login($no)
{
 if (substr($no, '0', '1') == '0') {
    $no = '62'.substr($no, 1);
}
$data = '{"phone":"+'.$no.'"}';
$login = request("/v4/customers/login_with_phone", "", $data);

return objectToArray(json_decode($login));
}

function veriflogin($otp, $token)
{
    $data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
    $verif = request("/v4/customers/login/verify", "", $data);

    return objectToArray(json_decode($verif));
}

function objectToArray($d) {
    if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }

    ?>