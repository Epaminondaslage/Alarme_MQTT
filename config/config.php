<?php
//***************************************************************************************************
//         Este arquivo deve ser copiado no diretorio /var/www/html/Alarme_MQTT              
//                       VERSAO 11 de Novembro 2024 / março 2025                                             
//             Este PHP é executado automaticamente  pelo /Alarme_MQTT /public/index.php                    
//                  Arquivo de configuração com as credenciais de conexão MQTT
//***************************************************************************************************

$config = [
    'mqtt' => [
        'server' => '10.0.0.141',
        'port' => 1883,
        'username' => 'mqtt',
        'password' => 'planeta',
        'client_id' => 'alarm_set'
    ]
];
return $config;
