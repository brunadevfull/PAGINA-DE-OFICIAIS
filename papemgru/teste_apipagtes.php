<?php
// Teste com formato OFICIAL da documentação do Tesouro Nacional

function configurarProxy($ch) {
    curl_setopt($ch, CURLOPT_PROXY, "proxy-1dn.mb:6060");
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "11111062:BruN%402025GlowUp");
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
    return $ch;
}

function testeFormatoOficial() {
    echo "=== TESTE COM FORMATO OFICIAL DA DOCUMENTAÇÃO ===\n";
    echo "Baseado no exemplo oficial: codigoServico = '23'\n\n";
    
    $chave = "eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
    
    $cpf_valido = "12345678901";
    
    // URLs dos ambientes
    $ambientes = [
        'HOMOLOGACAO' => 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento',
        'PRODUCAO' => 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento'
    ];
    
    // Códigos para testar (baseado na documentação e seus códigos DGOM)
    $codigos_teste = [
        "23" => "Exemplo da documentação oficial",
        "1541" => "Código homologação (sabemos que funciona)",
        "11859" => "Código DGOM como STRING",
        "11860" => "Código SISRES DGOM como STRING"
    ];
    
    foreach ($ambientes as $ambiente => $url) {
        echo "🌍 AMBIENTE: $ambiente\n";
        echo "URL: $url\n\n";
        
        foreach ($codigos_teste as $codigo => $descricao) {
            echo "  🧪 Testando código '$codigo' ($descricao):\n";
            
            // Formato EXATO da documentação oficial
            $data = [
                "codigoServico" => $codigo,              // STRING (não number!)
                "referencia" => (string)time(),          // STRING
                "competencia" => "032024",               // MMAAAA
                "vencimento" => date('dmY', strtotime('+30 days')), // DDMMAAAA
                "cnpjCpf" => $cpf_valido,               // STRING
                "nomeContribuinte" => "TESTE OFICIAL $codigo",
                "valorPrincipal" => "100",               // STRING (não number!)
                "valorDescontos" => "0",                 // STRING
                "valorOutrasDeducoes" => "0",            // STRING
                "valorMulta" => "0",                     // STRING
                "valorJuros" => "0",                     // STRING
                "valorOutrosAcrescimos" => "0"           // STRING
            ];
            
            $resultado = executarTeste($url, $data, $chave, $ambiente == 'PRODUCAO');
            echo "    Resultado: $resultado\n\n";
        }
        
        echo str_repeat('-', 70) . "\n\n";
    }
}

function testeCredenciamentoSISGRU() {
    echo "=== ANÁLISE: CREDENCIAMENTO SISGRU vs TOKEN ATUAL ===\n\n";
    
    echo "📋 DESCOBERTAS DA DOCUMENTAÇÃO:\n";
    echo "1. Token deve ser obtido via SISGRU\n";
    echo "2. Código de serviço deve ser cadastrado no SISGRU\n";
    echo "3. UG deve solicitar autorização específica\n";
    echo "4. Processo oficial de credenciamento\n\n";
    
    echo "🔍 SEU TOKEN ATUAL:\n";
    echo "- Subject: '773200' (código PAPEM)\n";
    echo "- Origem: DGOM (não SISGRU)\n";
    echo "- Funcionamento: Apenas via DGOM\n\n";
    
    echo "💡 CONCLUSÃO:\n";
    echo "Seu token atual é específico para DGOM!\n";
    echo "Para acesso direto ao Tesouro, precisa:\n";
    echo "1. Cadastrar PAPEM no SISGRU\n";
    echo "2. Obter códigos de serviço oficiais\n";
    echo "3. Obter token JWT do SISGRU\n";
    echo "4. Implementar com formato oficial\n\n";
    
    echo "🎯 RECOMENDAÇÃO:\n";
    echo "Teste os códigos no formato correto primeiro.\n";
    echo "Se não funcionar, confirma que precisa credenciamento SISGRU.\n";
}

function executarTeste($url, $data, $chave, $usar_proxy = true) {
    $data_string = json_encode($data, JSON_PRETTY_PRINT);
    echo "    Dados enviados:\n";
    echo "    " . str_replace("\n", "\n    ", $data_string) . "\n";
    
    $ch = curl_init($url);
    
    if ($usar_proxy) {
        configurarProxy($ch);
    }
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', 
        'Authorization: Bearer '.$chave
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return "❌ ERRO cURL: $error";
    }
    
    if ($result) {
        $json_decoded = json_decode($result, true);
        
        if ($json_decoded) {
            if (isset($json_decoded['proximaUrl'])) {
                return "🎉 SUCESSO! HTTP $http_code - GRU seria gerada!";
            } elseif (isset($json_decoded[0]['codigo'])) {
                $erro = $json_decoded[0];
                return "❌ HTTP $http_code - {$erro['codigo']}: {$erro['descricao']}";
            } else {
                return "❓ HTTP $http_code - Resposta: " . substr($result, 0, 100);
            }
        } else {
            return "❌ HTTP $http_code - JSON inválido: " . substr($result, 0, 100);
        }
    }
    
    return "❌ HTTP $http_code - Sem resposta";
}

function extrairInformacoesChave() {
    echo "\n=== INFORMAÇÕES IMPORTANTES DA DOCUMENTAÇÃO ===\n\n";
    
    $info_importantes = [
        "🔑 Autenticação" => [
            "Token JWT obtido via SISGRU",
            "UG deve solicitar autorização",
            "Token específico por órgão"
        ],
        "📝 Formato API" => [
            "Todos os valores como STRING",
            "codigoServico deve existir no SISGRU", 
            "Competência formato MMAAAA",
            "Vencimento formato DDMMAAAA"
        ],
        "🌐 Endpoints Oficiais" => [
            "Homologação: valpagtesouro.tesouro.gov.br",
            "Produção: pagtesouro.tesouro.gov.br",
            "Simulador disponível para testes"
        ],
        "🏛️ Domínios Permitidos" => [
            ".mil.br (Forças Armadas) ✅",
            ".gov.br (Governo Federal)",
            ".def.br, .jus.br, .leg.br, etc."
        ],
        "⚠️ Códigos de Erro" => [
            "C0015: Token de acesso inválido",
            "C0005: Serviço inexistente", 
            "C0010: Parâmetros incompatíveis",
            "C0020: UG do token ≠ UG do serviço"
        ]
    ];
    
    foreach ($info_importantes as $categoria => $itens) {
        echo "$categoria:\n";
        foreach ($itens as $item) {
            echo "  • $item\n";
        }
        echo "\n";
    }
}

// Executar testes
echo "TESTE COM DOCUMENTAÇÃO OFICIAL DO TESOURO NACIONAL\n";
echo "=================================================\n\n";

testeCredenciamentoSISGRU();
testeFormatoOficial();
extrairInformacoesChave();

echo "\n🎯 PRÓXIMOS PASSOS:\n";
echo "1. Se códigos falharem = confirma necessidade de SISGRU\n";
echo "2. Se formato estiver correto = problema é credenciamento\n";
echo "3. Avaliar viabilidade de credenciamento oficial\n";
echo "4. Ou manter DGOM como solução ideal\n";
?>