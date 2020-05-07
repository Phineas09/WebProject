<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((isset($_POST["car"]))) {

        $masina = '{
            "model" : "Mazda 3 Sedan",
            "An" : "2020",
            "nivel_echipare" : "PLUS",
            "pret" : "21.600 Euro",
            "galerie" : [
                { "poza1" : "Overview", "url" : "overview.jpg"},
                { "poza2" : "Interior", "url" : "interior.jpg"},
                { "poza3" : "Fata", "url" : "front.jpg"}
            ]
        }';

        echo json_encode(
            array(
                'statusCode' => 200,
                'car' => $masina
            ));

        exit;
    }
}
