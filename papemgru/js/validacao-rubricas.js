function validarRubricaAutomatica() {
    
    // Exclusões completas para militar/pensionista
    var exclusoesCompletas = [
        "1200001 - AC CONTAS", "1208000 - COMCOMREC", "1209000 - AC Contabil",
        "2200001 - AC CONTAS", "2300001 - AC CONTAS", "4980200 - IR RESTIT",
        "2208000 - COMCOMREC", "2308000 - COMCOMREC", "1234003 - AUX FUN ATIV",
        "1234006 - AUX FUN ATIV", "1287003 - DESC ADIAN F", "1287004 - AD FER AUTO",
        "1287600 - AD NATALINO", "2234004 - AUX FUN INAT", "2235000 - AUX FUN INAT",
        "2306000 - AUX FUN PENS", "3250201 - RESPAREG", "4811701 - FUSMA RESTIT",
        "5090132 - PENSAO ALIME", "5090232 - PENSAO ALIME", "5090332 - PENSAO ALIME",
        "5090432 - PENSAO ALIME", "5090532 - PENSAO ALIME", "5090632 - PENSAO ALIME",
        "5090732 - PENSAO ALIME", "5100132 - PENSAO ALIME", "5100232 - PENSAO ALIME",
        "5110133 - PENSAO ALIME", "5110233 - PENSAO ALIME", "5110333 - PENSAO ALIME",
        "5950100 - PEN ADIAN 13"
    ];
    
 
    function validarCampoRubrica(campoId, exclusoes) {
        var campo = document.getElementById(campoId);
        if (!campo) return;
        
        var valor = campo.value.trim();
        if (valor === '') return; // Campo vazio permitido
        
        // Verifica se está na lista de exclusões
        if (exclusoes && exclusoes.includes(valor)) {
            alert('Código não permitido!\n\nEste código está na lista de exclusões do sistema.');
            campo.value = '';
            campo.focus();
            return;
        }
        
        // Para códigos que não estão nas listas conhecidas (verificação extra)
        // Esta validação só acontece se o campo tiver dados mas não encontrar nas listas válidas
        var temDados = campo.value.match(/^\d+\s*-\s*.+/); // Formato: "123 - NOME"
        if (temDados && !window.listaValidacaoExecutada) {
            // Flag para evitar loops de validação
            window.listaValidacaoExecutada = true;
            
            // Se chegou aqui é porque passou pelas validações do autocomplete
            // Só verifica exclusões mesmo
        }
    }
    
    // Aplicar validação em todos os campos quando aparecerem
    function aplicarValidacoes() {
        
        // SERVIDOR CIVIL (sem exclusões especiais)
        if (document.getElementById('servidor7')) {
            $('#servidor7').on('blur autocompleteclose', function() {
                validarCampoRubrica('servidor7', []);
            });
        }
        
        // MILITAR ATIVO (com exclusões completas)
        if (document.getElementById('ativa10')) {
            $('#ativa10').on('blur autocompleteclose', function() {
                validarCampoRubrica('ativa10', exclusoesCompletas);
            });
        }
        
        // PENSIONISTA (com exclusões completas)
        if (document.getElementById('pensionista10')) {
            $('#pensionista10').on('blur autocompleteclose', function() {
                validarCampoRubrica('pensionista10', exclusoesCompletas);
            });
        }
        
        // VETERANO/ANISTIADO (exclusão mínima)
        if (document.getElementById('VeteranoAnistiado10')) {
            $('#VeteranoAnistiado10').on('blur autocompleteclose', function() {
                validarCampoRubrica('VeteranoAnistiado10', ["1200001 - AC CONTAS"]);
            });
        }
    }
    
    // Executar quando documento estiver pronto
    $(document).ready(function() {
        // Aplicar imediatamente
        aplicarValidacoes();
        
        // Aplicar novamente após 2 segundos (para garantir que includes carregaram)
        setTimeout(aplicarValidacoes, 2000);
        
        // Observer para campos que aparecem dinamicamente
        if (window.MutationObserver) {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        aplicarValidacoes();
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    });
}

// Inicializar o sistema de validação
validarRubricaAutomatica();