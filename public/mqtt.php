<?php

//****************************************************************************************************
//*         Este arquivo deve ser copiado no diretorio /var/www/html/Alarme_MQTT/public               *
//*                                    VERSAO 11 de Novembro 2024                                    *
//*                  Este HTML é executado automaticamente  pelo /Alarme_MQTT/public/index.php        *
//*                     Arquivo de visualização de log  do sistema de Alarme               *
//****************************************************************************************************

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../config/config.php");
require_once("../src/MqttClient.php");


// O arquivo/logs/status.txt contem o "0"para o alarme setado para desligado ou "1" setado para alarme ligado
// somente escreve no tópico do MQTT para ativar relé se o alarme estive ligado 
// este if interrompe o acionamento do relé caso o alarme esteja desligado.

$alarmFile = '../logs/status.txt';

if (!file_exists($alarmFile) || file_get_contents($alarmFile) == '0') {
    echo json_encode(['error' => 'Alarme desativado.']);
    exit();
}


$config = require('../config/config.php');

$mqttClient = new MqttClient($config['mqtt']);
if ($mqttClient->connect()) {

    // *************************************************************************
    // regra de negocio para acionamento de cameras aqui.
    // basta programar em ifs no objeto $data (que vem as infos da camera) e enviar mensagem para o mqtt usando a biblioteca no formato abaixo
    // data['camera_name'] === 'Rua Principal Ouro Verde'
    // **************************************************************************


    // exemplo de ler e Atuar no mqtt
    //if($data['camera_name'] === 'Rua Principal Ouro Verde'){
    //    $topic = "O1";
    //    $mqttClient->publish($topic, 'A');
    //}

    $mqttClient->close();

?>
