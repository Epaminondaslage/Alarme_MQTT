## Sistema de Alarme por Vídeo Analítico

## Arquitetura do Sistema

<img src="/img/arquitetura.png" width="40%" />

## Soluções empregadas

* Visicomp
* Webhook
* MQTT
* Acionamentos por Esp32
# Visicomp



# Webhook Sítio Pé de Serra

<img src="/img/webhooker.jpg" width="10%" />

## O que é Webhook

Um webhook é um método de comunicação entre sistemas, onde uma aplicação envia dados para outra de maneira automática e em tempo real quando um evento específico ocorre. Em vez de a aplicação de destino precisar solicitar informações repetidamente (o que é conhecido como "polling"), o webhook notifica assim que há uma atualização ou evento, proporcionando uma comunicação mais rápida e eficiente.

## Como Funciona um Webhook?

Um webhook funciona como um "ponto de escuta" que recebe dados automaticamente. Ele é configurado como uma URL específica onde a aplicação de origem envia uma requisição HTTP (geralmente do tipo POST) assim que o evento ocorre. Essa requisição contém informações do evento no formato de um payload JSON (ou outro formato), que a aplicação de destino processa.

## Usos Comuns de Webhooks

* Notificações em tempo real: Receber atualizações instantâneas sobre novos pedidos, pagamentos ou eventos de sistema.
* Integrações entre serviços: Conectar diferentes serviços, como receber notificações de novas issues do GitHub em um canal do Slack.
* Automatização de tarefas: Criar workflows automáticos, como enviar um email ou atualizar registros em uma planilha.

## Estrutura de uma Requisição de Webhook

* URL do Webhook: A URL que a aplicação de origem envia os dados do evento.
* Método HTTP: Normalmente POST, mas pode variar.
* Headers HTTP: Contêm metadados sobre a requisição.
* Payload: Dados enviados (normalmente em JSON), que descrevem o evento e suas informações.

## Vantagens de Usar Webhooks

* Eficiência: Diminui o número de chamadas desnecessárias entre sistemas.
* Tempo Real: Notifica assim que um evento ocorre, ao contrário do polling, que pode ter atrasos.
* Escalabilidade: Suporta uma comunicação mais rápida e simplificada entre vários sistemas.

##  WebhookPdS

Projeto para rodar em servidor Linux 10.0.0.5 , com os arquivos e diretórios organizados conforme a árvore abaixo. O projeto envolve criação de um webhook em PHP que recebe um JSON via POST, processa-o para subscrever em um broker MQTT e exibe o log em uma interface web. o sistema pose ser desativado através de um botao na página html.  

##  JSON 

**URL do JSON**:

```
http://10.0.0.5/webhookPdS/public/webhook.php
```

**JSON do Visicomp**:

``` 
{
  "event_type": "object_detection_event",
  "event": {
    "camera_id": 0,
    "analytics_id": 0,
    "event_timestamp": "yyyy-mm-ddTHH:ii:ss",
    "video_filename": "<path_to_video>",
    "thumbnail_filename": "<path_to_image_thumbnail>"
  },
  "camera_name": "cam name",
  "analytics_name": "analytic name"
}
```

**Site para formatar o JSON**

```
https://jsonviewer.stack.hu/ 
```

<img src="/img/jsonviewer.png" width="40%" />

**Postman**
O Postman é uma ferramenta gratuita e multiplataforma que ajuda desenvolvedores a criar, testar, compartilhar e documentar APIs: Permite criar e salvar solicitações HTTP e HTTPs, Ler respostas de solicitações, Organizar e agrupar solicitações de API relacionadas, Automatizar testes de API.

<img src="/img/postman.png" width="40%" />

**Servidor do aplicativo** 
```
http://10.0.0.5/var/www/html/webhookPdS
```
## Estrurura do Projeto

```
/var/www/html/webhookPdS/
├── public/                      
│   ├── index.php                # Página inicial ou ponto de entrada (opcional)
│   ├── webhook.php              # Endpoint para o servidor de webhook
│   ├── log_viewer.php           # Página HTML para visualizar os logs
│   └── log_data.php             # API que fornece os logs em JSON para o frontend
│
├── css/
│   └── styles.css               # CSS para estilos e responsividade
│
├── js/
│   └── scripts.js               # JavaScript para atualização dinâmica dos logs
│
├── src/                         
│   ├── MqttClient.php           # Classe para gerenciar a conexão e publicação MQTT
│   └── WebhookHandler.php       # Classe para manipular os dados do webhook
│
├── config/                      
│   └── config.php               # Configurações gerais do projeto (MQTT, etc.)
│
├── logs/                        
│   └── webhook_log.txt          # Log dos eventos de webhook recebidos
│
├── scripts/
│   └── monitor_connections.sh   # Script para verificar e manter ativa a conexão MQTT
│
├── vendor/                      # Diretório para dependências externas (composer)
│
└── README.md                    # Documentação do projeto

```

### Diretorio config (config/config.php)

Arquivo de Configuração. Define as configurações do broker MQTT, como servidor, porta, usuário, senha e ID do cliente.

### Classe MQTT (src/MqttClient.php)

Essa classe gerencia a conexão e publicação no broker MQTT.

### Classe Webhook Handler (src/WebhookHandler.php)

Classe para tratar e registrar logs do webhook.

### Servidor de Webhook (public/webhook.php)

Arquivo principal que recebe o webhook, registra o log e publica a mensagem no broker MQTT.

### Página HTML para Visualizar Logs (public/log_viewer.php)

```
Cabeçalho HTML (<head>):
        Define o título e a codificação da página.
        Inclui a referência ao arquivo CSS (styles.css) localizado no diretório css/ para aplicar estilos e tornar a página responsiva.

Conteúdo da Página (<body>):
        Um div com a classe container que centraliza o conteúdo e aplica o estilo definido no CSS.
        Um título (<h1>) e uma descrição (<p>) que explicam o propósito da tabela.
        Uma tabela com thead e tbody, onde:
            O cabeçalho da tabela (<thead>) define duas colunas: "Data e Hora" e "Mensagem".
            O corpo da tabela (<tbody id="log-table-body">) é onde os dados de logs serão carregados dinamicamente.

Referência ao JavaScript (<script>):
        A linha <script src="../js/scripts.js"></script> carrega o JavaScript que atualiza a tabela de logs.
        O arquivo scripts.js executa a função loadLogs a cada 5 segundos para atualizar o conteúdo da tabela com os logs mais recentes obtidos de log_data.php.

Como Funciona

    CSS (styles.css): Aplica estilos para melhorar a aparência da tabela e tornar a página responsiva.
    JavaScript (scripts.js): Faz uma solicitação periódica a log_data.php para obter novos logs e atualiza o conteúdo da tabela automaticamente.
    Tabela Dinâmica: O conteúdo da tabela é preenchido e atualizado com dados JSON de log_data.php, o que permite a visualização em tempo real dos eventos de webhook recebidos.
```
 Este log_viewer.php fornece uma interface prática e responsiva para visualizar logs do webhook em tempo real.

### Arquivo para Carregar os Logs em JSON (public/log_data.php)

API para fornecer logs em JSON.

### Script de Monitoramento de Conexão (scripts/monitor_connections.sh)

Script para monitorar e manter ativa a conexão com o broker MQTT.

### Configurar Permissões e Cron

* Criar o diretório de logs e configure as permissões:

```
sudo mkdir -p /var/www/html/webhookPdS/logs
sudo touch /var/www/html/webhookPdS/logs/webhook_log.txt
sudo chown -R www-data:www-data /var/www/html/webhookPdS/logs
sudo chmod -R 775 /var/www/html/webhookPdS/logs
```

* Adicione o script de monitoramento ao cron para rodar a cada minuto:

```
sudo crontab -e
```
* Adicione a linha:

```
/var/www/html/webhookPdS/scripts/monitor_connections.sh >> /var/www/html/webhookPdS/logs/monitor.log 2>&1
```

 ### index.php (/public/index.php)

Este arquivo serve como a página inicial do sistema, fornecendo links para acessar a página de visualização de logs e qualquer outra funcionalidade adicional que você queira adicionar no futuro.

* Cabeçalho HTML:
  
Define o título e a codificação da página.
Inclui um pequeno estilo embutido para ajustar a aparência dos links de navegação.

* Conteúdo Principal:

Uma seção container centralizada que contém o título, uma breve descrição, e botões de navegação.

* Botões de Navegação:

Visualizar Logs de Webhook: Um link para acessar a página log_viewer.php, onde é possível visualizar os logs registrados.
Endpoint Webhook: Um link para webhook.php, que pode ser útil para testes manuais de requisições (embora normalmente esse endpoint seja acionado por sistemas externos).

* O index.php oferece uma interface básica e funcional para navegar pelas principais partes do sistema. Esse diretório é geralmente onde ficam os arquivos acessíveis publicamente na web, permitindo que index.php sirva como a página inicial do projeto, acessível pelo navegador no endereço:
  
```
http://seu_dominio_ou_ip/webhookPdS/public/index.php
```

### Arquivo CSS (css/styles.css)

* Explicação dos Estilos

    Estilos Gerais:
        Define uma aparência limpa e centralizada, com background-color suave e fonte legível.
        display: flex e align-items: center centralizam o conteúdo verticalmente.

    Container:
        Configuração do container .container com uma largura máxima, fundo branco, e uma borda suave.
        Aplica um box-shadow para um leve efeito de elevação.

    Título Principal (h1):
        Define a cor, o tamanho e a margem para o título principal das páginas.

    Botões de Link (.link-button):
        Estiliza os botões de navegação como links destacados.
        Inclui background-color verde e uma transição suave ao passar o mouse.

    Tabela de Logs:
        Estilos para table, th, e td usados na página de visualização de logs (log_viewer.php).
        Bordas e cores para melhorar a legibilidade, além de um efeito de hover para as linhas.

    Estilos Responsivos:
        Ajusta o tamanho da fonte e o espaçamento em dispositivos com largura de até 600px para garantir que o layout fique adequado em telas menores.

* Salve esse CSS como styles.css dentro de /var/www/html/webhookPdS/css/, e ele será aplicado automaticamente ao index.php e outras páginas que o referenciem.

### JavaScript (js/scripts.js)

O arquivo scripts.js faz a atualização dinâmica dos logs exibidos na página log_viewer.php. Este JavaScript utiliza fetch para obter os dados dos logs periodicamente e atualizá-los na tabela sem a necessidade de recarregar a página.

* Explicação do Código JavaScript

    loadLogs: Esta função faz uma solicitação ao log_data.php para obter os logs em formato JSON.
        Limpa a tabela de logs antes de adicionar novos dados.
        Cria uma nova linha (tr) para cada log, com duas células (td): uma para a data e outra para a mensagem.
        Adiciona a linha à tabela.

    Atualização Automática: Após o carregamento inicial dos logs, a função loadLogs é chamada a cada 5 segundos para manter a tabela atualizada em tempo real.

 ### O diretório vendor/ 
 
É onde ficam as dependências externas do projeto, que são gerenciadas pelo Composer, uma ferramenta popular de gerenciamento de dependências para PHP. No caso deste projeto, **vendor/** armazenaria a biblioteca phpMQTT para conexão com o broker MQTT, bem como outras dependências que o projeto possa utilizar. 

O diretório **vendor/** é essencial para armazenar dependências externas instaladas via Composer, permitindo que o projeto utilize bibliotecas externas, como phpMQTT. Este diretório não deve ser editado manualmente, pois o Composer é responsável por gerenciar seu conteúdo com base no composer.json. 


* Passos para Configurar o Diretório vendor/ com Composer

    Instalar o Composer (se ainda não tiver)

    Se o Composer ainda não está instalado no servidor, você pode instalar com os comandos abaixo:

```
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

* Adicionar um Arquivo composer.json

No diretório raiz do projeto (/var/www/html/webhookPdS), crie um arquivo composer.json para especificar as dependências, como phpMQTT.

Exemplo de composer.json:

```
{
    "require": {
        "bluerhinos/phpmqtt": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/bluerhinos/phpMQTT"
        }
    ]
}
```

Esse arquivo diz ao Composer para instalar a biblioteca phpMQTT diretamente do repositório GitHub.

* Instalar as Dependências com Composer

No diretório raiz do projeto, execute o comando:

```
composer install
```

Isso criará o diretório vendor/ e instalará o phpMQTT, além de gerar os arquivos vendor/autoload.php e composer.lock, que são usados pelo Composer para gerenciar automaticamente as classes.

* Incluir o Autoloader no Projeto

Em todos os arquivos PHP que precisam de uma dependência de vendor/, inclua o autoloader do Composer para carregar as classes automaticamente. Exemplo:

 ```
   require_once __DIR__ . '/../vendor/autoload.php';
```

* Estrutura do Diretório **vendor/** Após Instalação

Após rodar o **composer install**, o diretório **vendor/** terá a seguinte estrutura:

```
/var/www/html/webhookPdS/vendor/
├── autoload.php             # Arquivo de autoload gerado pelo Composer
├── composer/                # Arquivos de gerenciamento do Composer
├── bluerhinos/              # Diretório com a biblioteca phpMQTT
│   └── phpmqtt/             # Código da biblioteca phpMQTT
│       └── phpMQTT.php      # Arquivo principal da biblioteca
└── other-dependencies/      # Outras dependências, se forem adicionadas
```

Certifique-se de que o *vendor/* contém as dependências (como phpMQTT), instaladas com o Composer.

## MQTT

sdsdmwevfgfygcfygefcyfctyrfgcyfygfy

##  Controle de Alarme no menu Index.html

Regra de disparo de alarme e envio de acionamento por MQTT

```
webhookPdS/
├── public/
│   ├── status_alarm.html            # Página de controle do alarme e exibição de logs
│   ├── toggle_alarm.php             # Script PHP para alternar o estado do alarme no MQTT
│   ├── get_log.php                  # Script PHP que exibe logs (antes de ajustes adicionais)
│   └── img/
│       ├── alarm.jpg                # Imagem para cabeçalho do alarme
│       ├── status_alarm.jpg         # Imagem de status do alarme
├── css/
│   └── styles.css                   # Arquivo de estilo CSS para as páginas HTML
├── js/
│   └── script_status_alarm.js       # Script JavaScript para controle de status do alarme
└── vendor/
    └── autoload.php                 # Carregador automático para dependências do MQTT

```
* Pasta **public/**: Contém os arquivos de frontend acessíveis pelo navegador e os scripts principais PHP que interagem com o MQTT.
* Pasta **css/**: Armazena o arquivo styles.css, que define a aparência visual do projeto.
* Pasta **js/**: Inclui o script_status_alarm.js, responsável pelo controle e atualização do alarme no frontend.
* Pasta **vendor/**: Contém o autoload.php e outras dependências necessárias, como a biblioteca MQTT.

