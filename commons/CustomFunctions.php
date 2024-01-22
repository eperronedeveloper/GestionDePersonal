<?php

function debug(...$vars) {
    http_response_code(417);
    
    foreach ($vars as $var) {
        echo("<pre>");
        print_r($var);
        echo("</pre>");
    }
    
    die();
}

function clean($data){
    $cleanData = trim(mb_strtoupper(htmlspecialchars($data)));
    return $cleanData;
}

function cleanSensitive($data){
    $cleanData = trim(htmlspecialchars($data));
    return $cleanData;
}


function emptyValues($data) {
    if (is_array($data)) {
        $trimmedData = array_map('trim', $data);
        $isEmpty = array_reduce($trimmedData, function ($carry, $value) {
            return $carry || ctype_space($value) || $value === '';
        }, false);

        return $isEmpty;
    }

    $trimmedData = trim($data);
    return ctype_space($trimmedData) || $trimmedData === '';
}