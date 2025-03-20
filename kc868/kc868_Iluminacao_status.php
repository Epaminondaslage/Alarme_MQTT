<?php

// Execute esse script permanentemente no servidor PHP:

//php mqtt_listener.php

// Use ferramentas como screen ou nohup para mantê-lo rodando em background.)

require_once("../config/config.php");
require_once("../src/MqttClient.php");

// require("phpMQTT.php");

$server   = "10.0.0.141";
$port     = 1883;
$username = "mqtt";
$password = "planeta";
$client_id = "phpMQTT-" . rand();

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
    // Salva o JSON diretamente em um arquivo data.json
    file_put_contents("data.json", $msg);
    echo "Mensagem recebida e salva com sucesso!\n";
}
