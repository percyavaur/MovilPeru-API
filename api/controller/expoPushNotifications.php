<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../model/down/expoToken.php';
require_once '../vendor/autoload.php';

$database = new Database();
$db = $database->getConnection();
$expoToken = new expoToken($db);

$array = [];
$expoTokens = array();

$expoToken->idExpoToken;
$expoToken->idUsuario;
$expoToken->expoToken;
$getExpoTokens = $expoToken->getExpoTokens();
$expoTokens = $expoToken->expoTokens;

function uuid(){
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

if ($getExpoTokens) {

    $notification = ['title' => "holas", 'body' => "adios"];
    
    foreach ($expoTokens as $key => $expoToken) {
        
    try {
        $interestDetails = [uuid(), $expoToken["expoToken"]];
        $expo = \ExponentPhpSDK\Expo::normalSetup();
        $expo->subscribe($interestDetails[0], $interestDetails[1]);
        $expo->notify($interestDetails[0], $notification);
        echo ("success");
    } catch (\Throwable $th) {
        echo ("error");
        echo ($th);
    }
    }
}
