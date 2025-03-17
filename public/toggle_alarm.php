<?php
//****************************************************************************************************
//*         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/public               *
//*                                    VERSAO 13 de Novembro 2024                                    *
//*                  É executado automaticamente  pelo /webhookPdS/public/alarm_status.html          *
//*                                 Arquivo: toggle_alarm.php                                        *
//*   Este arquivo lida com a publicacao do estado do alarme no broker MQTT e a criacao do log       * 
//****************************************************************************************************

require('../vendor/autoload.php');
use Bluerhinos\phpMQTT;

$server = "10.0.0.32";
$port = 1883;
$username = "mqtt";
$password = "planeta";
$topic = "status_alarm";
$logFile = '../logs/mqtt_log.txt';
$alarmFile = '../logs/status.txt';

try {
    // Cria o diretório de logs, caso não exista
    if (!file_exists('../logs')) {
        mkdir('../logs', 0777, true);
    }

    // Verifica o status atual do tópico
    $initialStatus = "Alarme Desligado";
    
    // Verifica se o arquivo de status do alarme existe, se não, cria com o valor "0"
    if (!file_exists($alarmFile)) {
        file_put_contents($alarmFile, "0");
    }

    // Lê o conteúdo do arquivo de status do alarme
    $currentStatus = file_get_contents($alarmFile);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Inverte o valor atual do status do alarme
        $newStatus = ($currentStatus == "0") ? "1" : "0";
        file_put_contents($alarmFile, $newStatus);

        // Publica o novo status no broker MQTT
        $mqtt = new phpMQTT($server, $port, "ClientID" . rand());
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($topic, $newStatus, 0);
            $mqtt->close();
        }

        // Registra a mudança no arquivo de log
        $logMessage = date('Y-m-d H:i:s') . " - Status do alarme alterado para: " . ($newStatus == '1' ? 'Ligado' : 'Desligado') . "\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

        // Retorna uma resposta JSON indicando sucesso
        echo json_encode(array('status' => 'success', 'currentStatus' => $newStatus));
    } else {
        // Retorna uma resposta JSON indicando que o método não é permitido
        echo json_encode(['status' => 'success', 'currentStatus' => $currentStatus]);
    }

} catch (Exception $e) {
    // Em caso de erro, registra o erro no arquivo de log e retorna uma resposta JSON indicando falha
    $logMessage = date('Y-m-d H:i:s') . " - Erro: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>