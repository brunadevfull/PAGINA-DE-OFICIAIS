$(document).ready(function() {
    // Verifica o hash da URL e exibe os modais correspondentes
    if (window.location.hash === '#loginModal') {
        $('#loginModal').modal('show');
    } else if (window.location.hash === '#successModal') {
        $('#successModal').modal('show');
    } else if (window.location.hash === '#passwordChangeSuccessModal') {
        $('#passwordChangeSuccessModal').modal('show');
    } 

    // Função para adicionar um usuário
    $('#addUserForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'views/process_add_user.php', // URL do script PHP
            type: 'POST',
            data: formData,
            success: function(response) {
                try {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        $('#addUserModal').modal('hide');
                        $('#userAddSuccessModal').modal('show');
                    } else if (response.status === 'error') {
                        $('#userAddErrorModal').modal('show');
                      
                    } else {
                        console.error('Status desconhecido:', response.status);
                    }
                } catch (e) {
                    console.error('Erro ao analisar a resposta do servidor:', e);
                    alert('Erro ao processar a resposta do servidor. Verifique o console para mais detalhes.');
                }
            },
            error: function() {
                alert('Erro ao adicionar o usuário.');
            }
        });
    });

    $('#addUserModal').on('hidden.bs.modal', function () {
        $('#addUserForm')[0].reset();
        $('#usernameError').text('');
    });

    // Código para adicionar oficial
    $('#addOfficialForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.post('views/process_add_official.php', formData, function(response) {
            $('#addOfficialModal').modal('hide');
            $('#oficialSuccessModal').modal('show');
            $('#oficialSuccessModal').on('hidden.bs.modal', function () {
                window.location.reload();
            });
        }).fail(function() {
            alert('Erro ao adicionar oficial.');
        });
    });

    $('#addOfficialModal').on('hidden.bs.modal', function () {
        $('#addOfficialForm')[0].reset();
    });

    // Código para editar oficial
    $('#editOfficialForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.post('views/process_edit_official.php', formData, function(response) {
            $('#editOfficialModal').modal('hide');
            $('#editSuccessModal').modal('show');
            $('#editSuccessModal').on('hidden.bs.modal', function () {
                window.location.reload(); // Recarrega a página quando o modal de sucesso for fechado
            });
        }).fail(function() {
            alert('Erro ao editar oficial.');
        });
    });

    $('#editOfficialModal').on('hidden.bs.modal', function () {
        $('#editOfficialForm')[0].reset();
    });

    // Função para redefinir a senha do usuário
    $('#resetPasswordForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'views/process_reset_user_password.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                try {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        $('#resetPasswordSuccessModal').modal('show');
                        // Limpa a variável de sessão no lado do cliente, se necessário
                        window.location.hash = ''; // Opcional: Remove o hash da URL após exibir o modal
                    } else {
                     
                        $('#passwordErrorMessage').text(response.message);
                    }
                } catch (e) {
                    console.error('Erro ao analisar a resposta do servidor:', e);
                    alert('Erro ao processar a resposta do servidor. Verifique o console para mais detalhes.');
                }
            },
            error: function() {
                alert('Erro ao redefinir a senha.');
            }
        });
      
        // Código para exibir modais com base no hash da URL
        if (window.location.hash === '#resetPasswordSuccessModal') {
            $('#passwordChangeSuccessModal').modal('show');
        } else if (window.location.hash === '#passwordErrorModal') {
            $('#passwordErrorModal').modal('show');
        }
    });

    $('#resetUserPasswordModal').on('hidden.bs.modal', function () {
        $('#resetUserPasswordForm')[0].reset();
    });

    // Função para atualizar a numeração na tabela
    function atualizarNumeracao() {
        $('.table tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Função para excluir oficial
    window.excluirOficial = function(id) {
        if (confirm("Tem certeza que deseja excluir este oficial?")) {
            $.ajax({
                url: "views/remove_official.php",
                type: "POST",
                data: { id: id },
                success: function(response) {
                    atualizarNumeracao();
                    location.reload();
                },
                error: function() {
                    alert("Falha ao excluir oficial.");
                }
            });
        }
    }

    // Função para atualizar a data e hora
     function updateDateTime() {
        const now = new Date();
        const days = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
        const months = ["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"];
        const dayName = days[now.getDay()];
        const day = now.getDate().toString().padStart(2, '0');
        const month = months[now.getMonth()];
        const year = now.getFullYear().toString().slice(-2);
        const formattedDate = `${day} ${month} ${year}`;
        const formattedDateWithDay = `${dayName}, ${formattedDate}`;
        const formattedTime = now.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        const dateElement = document.getElementById('datetime-date');
        const timeElement = document.getElementById('datetime-time');

        if (dateElement) {
            dateElement.textContent = formattedDateWithDay;
        }
        if (timeElement) {
            timeElement.textContent = formattedTime;
        }


    }




setInterval(updateDateTime, 1000);
updateDateTime();
   
    // Função para abrir o modal de adicionar oficial
    window.abrirModalAdicionar = function(localizacao) {
        $('#addOfficialModal').modal('show');
        $('#localizacao').val(localizacao + 1);

        var linhaAtual = $('#oficial-' + localizacao); // ÚNICA CORREÇÃO: mudou de #row- para #oficial-
        var linhaProxima = linhaAtual.next('tr');

        var textoAntes = linhaAtual.find('.name-column').text();
        var textoDepois = linhaProxima.length ? linhaProxima.find('.name-column').text() : 'N/A';

        $('#addOfficialMessage').text('Adicionando oficial entre ' + textoAntes + ' e ' + textoDepois);

        $('.highlight').removeClass('highlight');
        linhaAtual.addClass('highlight');
    }

    // Função para abrir o modal de editar oficial
    window.abrirModalEditar = function(id, nome, posto_id, status, localizacao) {
        $('#editOfficialModal').modal('show');
        $('#edit_id').val(id);
        $('#edit_nome').val(nome);
        $('#edit_posto').val(posto_id);
        $('#edit_status').val(status);
        $('#edit_localizacao').val(localizacao);
    }

    // Inicializa o Select2 para o modal de adicionar oficial
    $('#addOfficialModal').on('shown.bs.modal', function () {
        var $posto = $('#posto');

        if (!$posto.length || typeof $.fn.select2 === 'undefined') {
            return;
        }

        if ($posto.data('select2')) {
            return;
        }

        $posto.select2({
            dropdownParent: $('#addOfficialModal'),
            width: '100%',
            templateResult: formatOption,
            templateSelection: formatOption
        });
    });

    $('#resetUserPasswordModal').on('shown.bs.modal', function () {
        $('#reset_username').select2();
    });

    function formatOption(option) {
        if (!option.id) {
            return option.text;
        }
        var imgSrc = $(option.element).data('img');
        return $('<span><img src="' + imgSrc + '" style="width: 40px; height: 20px; margin-right: 10px;" /> ' + option.text + '</span>');
    }
});

// ===============================================
// FUNÇÕES PARA TOGGLE - MANTENDO SEU CÓDIGO ORIGINAL COM CORREÇÕES MÍNIMAS
// ===============================================

// Função para atualizar o status de presença - MANTIDA COM CORREÇÕES
window.togglePresence = function(id) {
    // Get the checkbox
    var checkbox = document.querySelector('input[data-id="' + id + '"]');
    if (!checkbox) {
        console.error('Checkbox with ID ' + id + ' not found.');
        return;
    }

    // Determine status based on checkbox state
    var status = checkbox.checked ? 'bordo' : 'terra';

    // Send AJAX request to update status
    $.ajax({
        url: 'views/update_status_file.php',
        type: 'POST',
        data: { id: id, status: status },
        success: function(response) {
            console.log(response);
            if (response !== 'Status atualizado com sucesso.') {
                alert('Failed to update status: ' + response);
                checkbox.checked = !checkbox.checked; // Revert checkbox if failed
            }
            
            // Update visual elements
            updateToggleColor(checkbox);
            updateStatusBar(id, checkbox.checked);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Only logged-in users can perform this action');
            checkbox.checked = !checkbox.checked; // Revert checkbox if failed
            updateToggleColor(checkbox);
        }
    });
};

// Nova função para atualizar a barra colorida lateral - CORRIGIDA
function updateStatusBar(id, isPresent) {
    console.log('Atualizando linha para oficial ID:', id, 'Presente:', isPresent);
    
    // CORRIGIDO: Procurar especificamente pelo ID do oficial
    var row = document.getElementById('oficial-' + id);
    
    // Fallback: procurar pelo data-oficial-id
    if (!row) {
        row = document.querySelector('tr[data-oficial-id="' + id + '"]');
    }
    
    // Fallback: procurar pelo checkbox e subir
    if (!row) {
        var checkbox = document.querySelector('input[data-id="' + id + '"]');
        if (checkbox) {
            row = checkbox.closest('tr');
        }
    }
    
    if (row) {
        console.log('Linha encontrada:', row);
        // Remove existing classes
        row.classList.remove('present', 'absent');
        
        // Add the appropriate class based on status
        if (isPresent) {
            row.classList.add('present');
            console.log('Classe present adicionada');
        } else {
            row.classList.add('absent');
            console.log('Classe absent adicionada');
        }
    } else {
        console.error('ERRO: Linha não encontrada para oficial ID:', id);
        console.log('Tentativas feitas:');
        console.log('- getElementById: oficial-' + id);
        console.log('- querySelector: tr[data-oficial-id="' + id + '"]');
        console.log('- checkbox closest: input[data-id="' + id + '"]');
    }
}

// Função para atualizar classe da linha - MANTIDA
function updateRowClass(id, isPresent) {
    updateStatusBar(id, isPresent); // Chama a função acima
}

// Atualiza a cor do toggle com base no estado do checkbox - CORRIGIDA AS CORES
function updateToggleColor(checkbox) {
    var slider = checkbox.nextElementSibling;
    if (checkbox.checked) {
        slider.style.backgroundColor = '#28a745'; // Verde
    } else {
        slider.style.backgroundColor = '#dc3545'; // Vermelho  
    }
}

// MANTENDO SUA LÓGICA ORIGINAL DE DOMContentLoaded, APENAS CORRIGINDO CONFLITOS
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando checkboxes...');
    
    var checkboxes = document.querySelectorAll('input[type="checkbox"][data-id]'); // MUDANÇA: adicionei [data-id] para ser mais específico
    console.log('Encontrados', checkboxes.length, 'checkboxes com data-id');
    
    checkboxes.forEach(function(checkbox) {
        var oficialId = checkbox.getAttribute('data-id');
        console.log('Inicializando checkbox para oficial ID:', oficialId);
        
        // Configura a cor inicial do toggle
        updateToggleColor(checkbox);
        
        // Configura a cor inicial da linha
        updateStatusBar(oficialId, checkbox.checked);

        // REMOVIDO O ONCLICK DO HTML E USANDO APENAS addEventListener
        checkbox.addEventListener('change', function() {
            console.log('Checkbox mudou para oficial ID:', oficialId);
            
            // Verifica se o usuário está autenticado antes de chamar a função
            if (typeof userIsAuthenticated !== 'undefined' && !userIsAuthenticated) {
                // Reverte a mudança do checkbox
                checkbox.checked = !checkbox.checked;
                // Mostra um alerta informando que o usuário precisa estar logado
                alert('Você precisa estar logado para realizar essa ação.');
                return;
            }

            // Chama a função para alterar a presença
            togglePresence(oficialId);
        });
    });
    
    console.log('Inicialização dos checkboxes completa');
});

// FUNÇÕES DE DEBUG (OPCIONAL - pode comentar se não quiser)
function debugRowStatus() {
    var rows = document.querySelectorAll('tr[id^="oficial-"]');
    console.log('=== DEBUG: Status das linhas ===');
    rows.forEach(function(row) {
        var id = row.id;
        var classes = row.className;
        var checkbox = row.querySelector('input[data-id]');
        var checkboxStatus = checkbox ? checkbox.checked : 'sem checkbox';
        console.log('Row:', id, 'Classes:', classes, 'Checkbox:', checkboxStatus);
    });
    console.log('=== Fim do debug ===');
}
