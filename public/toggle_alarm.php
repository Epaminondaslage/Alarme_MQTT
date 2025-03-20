<?php
//****************************************************************************************************
//*         Este arquivo deve ser copiado no diretorio /var/www/html/Alarme_MQTT/public               *
//*                                    VERSAO 13 de Novembro 2024                                    *
//*                  É executado automaticamente  pelo /Alarme_MQTT/public/alarm_status.html          *
//*                                 Arquivo: toggle_alarm.php                                        *
//*   Este arquivo lida com a publicacao do estado do alarme no broker MQTT e a criacao do log       * 
//****************************************************************************************************

// Importa a biblioteca MQTT necessária
require('../vendor/autoload.php');
use Bluerhinos\phpMQTT;

// Configuração do broker MQTT
$server = "10.0.0.141"; // Endereço IP do servidor MQTT
$port = 1883; // Porta padrão do MQTT
$username = "mqtt"; // Nome de usuário MQTT
$password = "planeta"; // Senha MQTT
$topic = "alarm/status_alarm"; // Tópico onde o status do alarme será publicado

// Caminhos para os arquivos de log
$logFile = '../logs/mqtt_log.txt'; // Arquivo de log de eventos MQTT
$alarmFile = '../logs/status.txt'; // Arquivo onde o status do alarme é salvo

try {
    // Garante que o diretório de logs existe
    if (!is_dir('../logs')) {
        mkdir('../logs', 0777, true);
    }
    
    // Obtém o estado atual do alarme (0 ou 1)
    $currentStatus = file_exists($alarmFile) ? trim(file_get_contents($alarmFile)) : "0";
    
    // Alterna o status do alarme
    $newStatus = ($currentStatus == "0") ? "1" : "0";
    
    // Salva o novo status no arquivo
    file_put_contents($alarmFile, $newStatus);
    
    // Registra o evento no arquivo de log
    $logMessage = date("Y-m-d H:i:s") . " - Status do alarme alterado para: " . $newStatus . "\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    
    // Publica o novo status no broker MQTT
    $mqtt = new phpMQTT($server, $port, "PHP_MQTT_Client");
    if ($mqtt->connect(true, NULL, $username, $password)) {
        $mqtt->publish($topic, $newStatus, 0, true);
        $mqtt->close();
        echo json_encode(["status" => "success", "message" => "Alarme atualizado para $newStatus", "mqtt_topic" => "$topic"]);
    } else {
        throw new Exception("Falha na conexão com o broker MQTT");
    }
} catch (Exception $e) {
    // Captura e registra erros
    $errorMessage = date("Y-m-d H:i:s") . " - Erro: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $errorMessage, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
