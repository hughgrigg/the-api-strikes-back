<?php

// Super basic test server that is very forgiving with client requests!

header("Content-Type: application/json");

if (strstr($_SERVER['REQUEST_URI'], 'token') !== false) {
    http_response_code(201);
    print(
        <<<JSON
{
    "access_token": "e31a726c4b90462ccb7619e1b51f3d0068bf8006",
    "expires_in": 99999999999,
    "token_type": "Bearer",
    "scope": "TheForce"
}
JSON
    );
    exit;
}

if (strstr($_SERVER['REQUEST_URI'], 'reactor/exhaust') !== false) {
    http_response_code(204);
    exit;
}

if (strstr($_SERVER['REQUEST_URI'], 'prisoner') !== false) {
    http_response_code(200);
    print(
    <<<JSON
{
    "cell": "01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111",
    "block": "01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100"
}
JSON
    );
    exit;
}
