<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    include_once '../libs/Requests-master/library/Requests.php';
    Requests::register_autoloader();
    include_once '../libs/culqi-php-master/lib/culqi.php';

    // Codigo de Comercio
    $PUBLIC_KEY = "pk_test_M9FUiJ2LrfGqIITo";
    $culqi = new Culqi\Culqi(array('api_key' => $PUBLIC_KEY));

    // Creando Cargo a una tarjeta
    $token = $culqi->Tokens->create(
        array(
            "card_number" => "4111111111111111",
            "cvv" => "123",
            "email" => "wmuro" . uniqid() . "@me.com", //email must not repeated
            "expiration_month" => 9,
            "expiration_year" => 2020,
            "fingerprint" => uniqid()
        )
    );
    // Respuesta
    echo json_encode("Token: " . $token->id);
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
