<?php

function url(string $path = null) {
    if ($path) {
        return URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return URL_BASE;
}

function assets(string $path = null) {
    if ($path) {
        return URL_ASSETS . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return URL_ASSETS;
}

function verifyConnection() {
    $dbData = [DB_HOST, DB_NAME, DB_USER];

    $response = [];

    if (in_array('', $dbData)) {
        $response['response_status']['status'] = 0;
        $response['response_status']['msg'] = 'Faltam dados para a conexão com o banco de dados.';
        $response['response_status']['path'] = 'source/Support/Config.php';

        return $response;
    }

    $response['response_status']['status'] = 1;
    $response['response_status']['msg'] = 'Dados completos';

    return $response;
}
