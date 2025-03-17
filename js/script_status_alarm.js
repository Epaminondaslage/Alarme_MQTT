/****************************************************************************************************/
/*         Este arquivo deve ser copiado no diretorio /var/www/html/webhookPdS/js                   */
/*                                    VERSAO 14 de Novembro 2024                                    */
/*          Este js  é executado automaticamente  pelo /webhookPdS/public/status_alarm.html         */
/*         Arquivo de obter o status do alarme ao carregar a página atualizar log e outras coisas   */
/****************************************************************************************************/
$(document).ready(function() {
    // Requisição inicial para obter o status do alarme ao carregar a página
    $.get("toggle_alarm.php", function(data) {
        try {
            var response = JSON.parse(data);
            if (response.status === 'success') {
                updateButtonState(response.currentStatus == 1 ? 'Alarme_Ligado' : 'Alarme_Desligado');
            } else {
                updateFooter("Erro: " + response.message);
            }
        } catch (e) {
            updateFooter("Erro ao processar resposta do servidor.");
        }
    }).fail(function(xhr) {
        updateFooter("Erro ao obter o status inicial: " + xhr.responseText);
    });

    // Ao clicar no botão de alternância do alarme
    $('#toggleAlarm').click(function() {
        var alarmStatus = $(this).text() === 'Alarme Ligado' ? 'Alarme_Desligado' : 'Alarme_Ligado';
        
        // Envia o estado do alarme para o servidor via POST
        $.post("toggle_alarm.php", { status: alarmStatus }, function(data) {
            try {
                var response = JSON.parse(data);
                
                // Atualiza o texto, cor do botão e o rodapé com base na resposta
                if (response.status === 'success') {
                    updateButtonState(response.currentStatus == 1 ? 'Alarme_Ligado' : 'Alarme_Desligado');
                    updateFooter(response.message);
                    updateLog();
                } else {
                    updateFooter("Erro: " + response.message);
                }
            } catch (e) {
                updateFooter("Erro ao processar resposta do servidor.");
            }
        }).fail(function(xhr) {
            // Em caso de falha na requisição, exibe uma mensagem de erro
            alert("Erro ao executar ação: " + xhr.responseText);
            updateFooter("Erro ao executar ação");
        });
    });

    // Função para atualizar o rodapé com uma mensagem
    function updateFooter(message) {
        $('#footer').text(message);
    }

    // Função para atualizar o estado do botão
    function updateButtonState(alarmStatus) {
        if (alarmStatus === 'Alarme_Ligado') {
            $('#toggleAlarm').text('Alarme Ligado').css('background-color', 'green');
        } else {
            $('#toggleAlarm').text('Alarme Desligado').css('background-color', 'red');
        }
    }

    // Função para atualizar o log de eventos periodicamente
    function updateLog() {
        $.get("get_log_alarm.php", function(data) {
            try {
                var logEntries = JSON.parse(data).reverse();
                var logHtml = '';

                // Cria as linhas da tabela de log com os dados recebidos
                logEntries.forEach(function(entry) {
                    logHtml += '<tr><td>' + entry.date + '</td><td>' + entry.topic + '</td><td>' + entry.message + '</td></tr>';
                });

                // Atualiza o conteúdo da tabela de log
                $('#logTable').html(logHtml);
            } catch (e) {
                updateFooter("Erro ao processar log de eventos.");
            }
        }).fail(function(xhr) {
            // Em caso de erro ao atualizar o log, exibe uma mensagem no rodapé
            updateFooter("Erro ao atualizar log: " + xhr.responseText);
        });
    }

    // Atualiza o log a cada 5 segundos
    setInterval(updateLog, 5000);
    updateLog(); // Atualiza o log imediatamente ao carregar a página
});