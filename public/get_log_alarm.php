<?php
//****************************************************************************************************
//*         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/public               *
//*                                    VERSAO 13 de Novembro 2024                                    *
//*                  É executado automaticamente  pelo /Alarme_MQTT/public/alarm_status.html          *
//*                                 Arquivo: get_log_alarm.php                                              *
//* Este arquivo lê o conteúdo do log de eventos de alarme mqtt_log.txt  e o retorna em formato JSON * 
//****************************************************************************************************

$status = 0;

$statusFile = '../logs/status.txt'; // Arquivo onde o status do alarme é salvo

$currentStatus = file_exists($statusFile) ? trim(file_get_contents($statusFile)) : "0";


$logFile = '../logs/mqtt_log.txt';
$logs = [];

if (file_exists($logFile)) {
    $logData = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $response = [];

    foreach ($logData as $line) {
        // Dividindo a linha em data, tópico e mensagem
        list($date, $topic, $message) = explode(" - ", $line, 3);
        $response[] = [
            'date' => htmlspecialchars($date),
            'topic' => htmlspecialchars($topic),
            'message' => htmlspecialchars($message),
        ];
    }

    $logs = $response;
}


echo json_encode(array(
    'currentStatus' => $currentStatus,
    'logs' => $logs
));
?>