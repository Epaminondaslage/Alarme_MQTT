<?php
//***************************************************************************************************
//         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/src                  *
//                                    VERSAO 11 de Novembro 2024                                    *
//                  Este PHP é executado automaticamente  pelo /webhookPdS/public/index.php         *
//                Arquivo que  Inclui o autoloader do Composer                                      *
//***************************************************************************************************

require_once __DIR__ . '/../vendor/autoload.php';

// Inclui diretamente o arquivo da classe phpMQTT com o namespace correto
require_once __DIR__ . '/../vendor/bluerhinos/phpmqtt/phpMQTT.php';

class MqttClient {
    private $mqtt;
    private $config;

    public function __construct($config) {
        $this->config = $config;
        // Utilize o namespace completo para instanciar a classe
        $this->mqtt = new \Bluerhinos\phpMQTT($config['server'], $config['port'], $config['client_id']);
    }

    public function connect() {
        return $this->mqtt->connect(true, NULL, $this->config['username'], $this->config['password']);
    }

    public function publish($topic, $message) {
        $this->mqtt->publish($topic, $message, 0);
    }

    public function close() {
        $this->mqtt->close();
    }
}
