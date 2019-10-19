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

if ($getExpoTokens) {
    $expoTokens = $expoToken->expoTokens;
    $notification = ['title' => "hola", 'body' => "adios"];

    foreach ($expoTokens as $key => $expoToken); {
        echo($expoToken["expoToken"]);
        $key = $expoToken["expoToken"];
        $userId = $expoToken["idUsuario"];
        try {
            $expo = \ExponentPhpSDK\Expo::normalSetup();
            $expo->notify($userId, $notification); //$userId from database
        } catch (Exception $e) {
            $expo->subscribe($userId, $key); //$userId from database
            $expo->notify($userId, $notification);
        }
    }
}
