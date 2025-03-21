<?php

require_once(__DIR__ . "/phpMQTT.php");

use Bluerhinos\phpMQTT;

$server    = "10.0.0.141";
$port      = 1883;
$username  = "mqtt";
$password  = "planeta";
$client_id = "phpMQTT-" . rand();

// Instanciando corretamente com namespace:
$mqtt = new phpMQTT($server, $port, $client_id);

if (!$mqtt->connect(true, NULL, $username, $password)) {
    exit("Falha na conexão com MQTT.\n");
}

$topic = "KC868_A16/F024F9F4F430/STATE";

$topics[$topic] = [
    "qos" => 0,
    "function" => "procMsg"
];

$mqtt->subscribe($topics, 0);

while ($mqtt->proc()) {
    // mantém rodando...
}

$mqtt->close();

function procMsg($topic, $msg)
{
    file_put_contents(__DIR__ . "/data.json", $msg);
    echo "Mensagem recebida e salva com sucesso!\n";
}
