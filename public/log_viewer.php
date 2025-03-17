<!DOCTYPE html>
<!--
****************************************************************************************************
*         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/public               *
*                                    VERSAO 11 de Novembro 2024                                    *
*                  Este HTML é executado automaticamente  pelo /webhookPdS/public/index.php        *
*                     Arquivo de visualização de log de webhook do sistema de Alarme               *
****************************************************************************************************
-->
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Logs do Webhook</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <header style="text-align: center; padding: 10px;">
        <img src="../img/log.jpg" alt="log webhook" style="max-width: 10%; height: auto;">
        <h1>Logs de Eventos do Webhook</h1>
        <button id="btn-voltar" onclick="history.back()">Voltar</button>
        <h2>Esta tabela exibe os eventos recebidos pelo webhook e atualiza automaticamente a cada 5 segundos.</h2>
        <table>
            <thead>
                <tr>
                    <th>Data e Hora</th>
                    <th>Mensagem</th>
                </tr>
            </thead>
            <tbody id="log-table-body"></tbody>
        </table>
    </div>
    <script src="../js/scripts.js"></script>
</body>
</html>
