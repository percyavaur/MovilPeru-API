<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/news.php';
include_once '../config/core.php';

$database = new Database();
$db = $database->getConnection();
$news = new News($db);

$array = [];

if ($news->getExpoTokens()) {

    http_response_code(200);

    $array["success"] = true;
    $array["message"] = "Acceso Garantizado";
    $array["tokens"] = $news->tokens;

    echo json_encode($array);
} else {

    $array["success"] = false;
    $array["message"] = "Acceso Denegado";

    echo json_encode($array);
}
