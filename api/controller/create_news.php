<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/news.php';

$database = new Database();
$db = $database->getConnection();
$news = new News($db);

$data = json_decode(file_get_contents("php://input"));

$news->titulo = $data->titulo;
$news->subtitulo = $data->subtitulo;
$news->contenido = $data->contenido;
$news->imagen = $data->imagen;

$array = [];
if ($news->createNews()) {

    // set response code
    http_response_code(200);

    // display message: user was created
    $array["message"] = "Noticia creada correctamente";
    $array["success"] = true;
    echo json_encode($array);

} else {

    // set response code
    http_response_code(400);

    $array["message"] = "Error al crear noticia";
    $array["success"] = false;

    echo json_encode($array);
}
