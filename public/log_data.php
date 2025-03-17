<?php
/***************************************************************************************************
/         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/public               *
/                                    VERSAO 11 de Novembro 2024                                    *
/                  Este PHP é executado automaticamente  pelo /webhookPdS/public/index.php         *
/                Arquivo que mostra os logs dd webhook enviado pelo visicomp                       *
/***************************************************************************************************/
error_reporting(E_ALL);
ini_set('display_errors', '1');

$logFile = '../logs/webhook_log.txt';
$response = ['logs' => []];

if (file_exists($logFile) && is_readable($logFile)) {
    $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($logs as $logEntry) {
        list($dateTime, $message) = explode(" - ", $logEntry, 2);
        $response['logs'][] = [
            'date' => $dateTime,
            'message' => $message
        ];
    }
}

header("Content-Type: application/json");
echo json_encode($response);
