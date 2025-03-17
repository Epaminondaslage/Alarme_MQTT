<?php
//****************************************************************************************************
//*         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/public               *
//*                                    VERSAO 13 de Novembro 2024                                    *
//*                  É executado automaticamente  pelo /webhookPdS/public/alarm_status.html          *
//*                                 Arquivo: et_log.php                                              *
//* Este arquivo lê o conteúdo do log de eventos de alarme mqtt_log.txt  e o retorna em formato JSON * 
//****************************************************************************************************

$logFile = '../logs/mqtt_log.txt';

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

    echo json_encode($response);
} else {
    echo json_encode([]);
}
?>