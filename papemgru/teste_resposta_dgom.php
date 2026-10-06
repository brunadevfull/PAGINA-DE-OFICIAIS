<?php
/**
 * ========================================
 * TESTE DE RESPOSTA DA DGOM
 * ========================================
 * 
 * Este script cria UMA requisição de teste para a DGOM
 * e APENAS EXIBE a resposta na tela.
 * 
 * NÃO salva no banco
 * NÃO abre URL automaticamente
 * NÃO afeta o sistema principal
 * 
 * INSTRUÇÕES:
 * 1. Configure os dados de teste abaixo
 * 2. Execute UMA VEZ no navegador
 * 3. Analise a resposta
 * 4. Se necessário, cancele a GRU depois no sistema da DGOM
 * 
 * ========================================
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Teste DGOM - Análise de Resposta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #003366;
            border-bottom: 3px solid #003366;
            padding-bottom: 10px;
        }
        h2 {
            color: #0066cc;
            margin-top: 30px;
        }
        .warning {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .success {
            background-color: #d4edda;
            border: 2px solid #28a745;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .error {
            background-color: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        pre {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            overflow-x: auto;
            font-size: 13px;
        }
        .highlight {
            background-color: #ffff00;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
        .btn {
            background-color: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #004080;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #003366;
            color: white;
        }
        .step {
            background-color: #f0f0f0;
            padding: 10px 15px;
            margin: 10px 0;
            border-left: 4px solid #003366;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Teste de Integração DGOM - Análise de Resposta</h1>
        
        <div class="warning">
            <strong>⚠️ ATENÇÃO:</strong>
            <ul>
                <li>Este teste criará UMA GRU real no sistema da DGOM</li>
                <li>Use dados de teste (veja configuração abaixo)</li>
                <li>Valor sugerido: R$ 0,01 (um centavo)</li>
                <li>Você pode cancelar a GRU depois se necessário</li>
                <li>Execute apenas UMA VEZ</li>
            </ul>
        </div>

<?php

// ========================================
// CONFIGURAÇÃO DO TESTE
// ========================================

// Ambiente (descomente o que vai usar)
// $ambiente = 'homologacao';
$ambiente = 'producao';

if ($ambiente === 'homologacao') {
    $url_dgom = 'https://pagtesourohmg.dgom.mb:3000/handle';
    echo '<div class="success">✅ Usando ambiente de <strong>HOMOLOGAÇÃO</strong></div>';
} else {
    $url_dgom = 'https://pagtesouro.dgom.mb:3000/handle';
    echo '<div class="warning">⚠️ Usando ambiente de <strong>PRODUÇÃO</strong></div>';
}


$chave_api = "eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.X92vQ2oBESAPKtPYj_1eLFengD7eSUhPUGuBagEHUaX6mVuQ55trbQEHecEXqqi1KSgeQXXY70Rmn1M4FvwjIBbQN9xYAf-NEuVVPq9-QGJy58GK8AcYUrlJCsayIplPJuc6kB7Os6YCvN7c59OC38ATVCcuLBx6u5c3jZ3reZSk0dkBUBMDXJyr4wqhHWEZPtl-JFGBswCyvXUh8XLbOAyj98_n-B_7tS5b-K5-SBu7nbhaweSJ0Z4gLwxp1QYwTJqJzgRX6LKfDb0TEjLLKkYw9CS2uDX9IPEzN1K618HzXnM6tLvZh80kM34d91-rc4W785IhzIC-CwR-4h_HHA";

// ========================================
// DADOS DE TESTE
// ========================================
// ========================================
// DADOS DE TESTE - FORMATO CORRETO COM HÍFEN
// ========================================
// ========================================
// DADOS DE TESTE - FORMATO OFICIAL
// ========================================
$dados_teste = [
    // Identificação
    "cat" => "PAPEM",
    "codigoServico" => 11859,
    
    // Contribuinte
    "nomeContribuinte" => "Servidor Civil Teste",
    "cnpjCpf" => "13899289773",  // 11 dígitos para CPF
    
    // Valores
    "valorPrincipal" => 0.07,
    "valorBruto" => 0.03,
    
    // Natureza e Rubrica
    "NatDev" => "GRATIFICAÇÃO NATALINA",
    "codRubrica" => "00176",
    "nomeRubrica" => "GRATIFICAÇÃO NATALINA AT",
    
    // Organização
    "nomeUG" => "SEGCOL - COMANDO DO 2 ESQUADRAO DE ESCOLTA",
    "codUG" => "008",
    "nomeOC" => "DLSEGU - AGENCIA DA CAPITANIA DOS PORTOS EM PORTO SEGURO",
    "codOC" => "015",
    
    // Datas - FORMATO OFICIAL (SEM SEPARADORES) ✅
    "vencimento" => date('dmY', strtotime('+30 days')),  // 09112025
    "competencia" => date('mY'),                          // 102025
    
    // Identificação Servidor
    "codSiapeNip" => "1234567",
    
    // Tributação
    "tipoTributo" => "nao_tributavel",
    
    // Outros
    "motivoStoryPP" => "Motivo do recolhimento - Teste",
    "observacao" => "TESTE SISTEMA - PODE CANCELAR"
];

echo '<h2>📋 Dados que Serão Enviados:</h2>';
echo '<table>';
echo '<tr><th>Campo</th><th>Valor</th></tr>';
foreach ($dados_teste as $campo => $valor) {
    echo "<tr><td><strong>{$campo}</strong></td><td>" . htmlspecialchars($valor) . "</td></tr>";
}
echo '</table>';

// ========================================
// VERIFICAÇÃO DE SEGURANÇA
// ========================================
if (!isset($_GET['confirmar'])) {
    ?>
    <div class="info-box">
        <h3>✋ Antes de Continuar:</h3>
        <ol>
            <li>Verifique se os dados acima estão corretos</li>
            <li>Confirme que é um teste (valor R$ 0,01)</li>
            <li>Certifique-se que colocou sua chave de API</li>
            <li>Clique no botão abaixo para executar</li>
        </ol>
        <form method="GET">
            <input type="hidden" name="confirmar" value="sim">
            <button type="submit" class="btn">▶️ EXECUTAR TESTE</button>
        </form>
    </div>
    <?php
    exit;
}

echo '<div class="success">✅ Teste confirmado. Executando...</div>';

// ========================================
// EXECUTAR REQUISIÇÃO
// ========================================

echo '<h2>🔄 Enviando Requisição para DGOM...</h2>';

$data_string = json_encode($dados_teste);

$ch = curl_init($url_dgom);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // ⬅️ ADICIONE ISSO
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // ⬅️ ADICIONE ISSO
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $chave_api
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$tempo_inicio = microtime(true);
$result = curl_exec($ch);
$tempo_fim = microtime(true);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$erro_curl = curl_error($ch);
curl_close($ch);

$tempo_resposta = round(($tempo_fim - $tempo_inicio) * 1000); // ms

echo "<div class='step'>⏱️ Tempo de resposta: <strong>{$tempo_resposta} ms</strong></div>";
echo "<div class='step'>📡 HTTP Code: <strong>{$http_code}</strong></div>";

// ========================================
// VERIFICAR ERROS
// ========================================

if ($result === false) {
    echo '<div class="error">';
    echo '<h3>❌ Erro de Conexão (cURL)</h3>';
    echo '<p><strong>Mensagem:</strong> ' . htmlspecialchars($erro_curl) . '</p>';
    echo '<h4>Possíveis Causas:</h4>';
    echo '<ul>';
    echo '<li>Certificado SSL não encontrado ou inválido</li>';
    echo '<li>Firewall bloqueando conexão</li>';
    echo '<li>URL da DGOM incorreta</li>';
    echo '<li>Servidor DGOM fora do ar</li>';
    echo '</ul>';
    echo '</div>';
    exit;
}

if ($http_code !== 200) {
    echo '<div class="error">';
    echo '<h3>⚠️ Erro HTTP: ' . $http_code . '</h3>';
    echo '<p>A DGOM retornou um código de erro.</p>';
    echo '</div>';
}

// ========================================
// EXIBIR RESPOSTA BRUTA
// ========================================

echo '<h2>📄 Resposta Bruta (JSON Original):</h2>';
echo '<pre>' . htmlspecialchars($result) . '</pre>';

// ========================================
// DECODIFICAR E ANALISAR
// ========================================

$response = json_decode($result);

echo '<h2>🔍 Análise da Resposta:</h2>';

if (json_last_error() !== JSON_ERROR_NONE) {
    echo '<div class="error">';
    echo '<h3>❌ Erro ao Decodificar JSON</h3>';
    echo '<p><strong>Erro:</strong> ' . json_last_error_msg() . '</p>';
    echo '</div>';
    exit;
}

// ========================================
// CENÁRIO 1: ERRO (Array)
// ========================================

if (is_array($response)) {
    echo '<div class="error">';
    echo '<h3>❌ A DGOM Retornou Erros:</h3>';
    echo '<ul>';
    foreach ($response as $erro) {
        echo '<li><strong>Código:</strong> ' . htmlspecialchars($erro->codigo ?? 'N/A') . ' - ';
        echo '<strong>Descrição:</strong> ' . htmlspecialchars($erro->descricao ?? 'N/A') . '</li>';
    }
    echo '</ul>';
    echo '<h4>📋 O Que Fazer:</h4>';
    echo '<ol>';
    echo '<li>Corrija os dados com base nos erros acima</li>';
    echo '<li>Verifique se os códigos de rubrica/OM/OC estão corretos</li>';
    echo '<li>Confirme se o CPF é válido</li>';
    echo '<li>Execute o teste novamente</li>';
    echo '</ol>';
    echo '</div>';
    exit;
}

// ========================================
// CENÁRIO 2: SUCESSO (Object)
// ========================================

if (is_object($response)) {
    
    echo '<div class="success">';
    echo '<h3>✅ GRU Criada com Sucesso!</h3>';
    echo '</div>';
    
    // Listar todos os campos recebidos
    echo '<h3>📊 Campos Recebidos da DGOM:</h3>';
    echo '<table>';
    echo '<tr><th>Campo</th><th>Valor</th></tr>';
    
    foreach ($response as $campo => $valor) {
        $valor_exibir = is_string($valor) ? htmlspecialchars($valor) : json_encode($valor);
        echo "<tr><td><strong>{$campo}</strong></td><td>{$valor_exibir}</td></tr>";
    }
    echo '</table>';
    
    // ========================================
    // TENTAR EXTRAIR NÚMERO DA GRU
    // ========================================
    
    echo '<h2>🔢 Extração do Número da GRU:</h2>';
    
    $numero_gru = null;
    $metodo_extracao = null;
    
    // Tentativa 1: Campo direto
    $campos_possiveis = ['numero_gru', 'numeroGru', 'numero', 'id', 'id_pgto', 'protocolo'];
    
    foreach ($campos_possiveis as $campo) {
        if (isset($response->$campo) && !empty($response->$campo)) {
            $numero_gru = $response->$campo;
            $metodo_extracao = "Campo direto: <span class='highlight'>{$campo}</span>";
            break;
        }
    }
    
    // Tentativa 2: Extrair da URL
    if (empty($numero_gru) && isset($response->proximaUrl)) {
        $url = $response->proximaUrl;
        
        // Método 1: Query string
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
            $chaves_possiveis = ['numero', 'gru', 'id', 'protocolo', 'n'];
            
            foreach ($chaves_possiveis as $chave) {
                if (isset($params[$chave])) {
                    $numero_gru = $params[$chave];
                    $metodo_extracao = "Query string da URL: <span class='highlight'>?{$chave}={$numero_gru}</span>";
                    break;
                }
            }
        }
        
        // Método 2: Regex no path
        if (empty($numero_gru)) {
            $padroes = [
                '/\/gru\/([a-zA-Z0-9\-_]+)/',
                '/\/visualizar\/([a-zA-Z0-9\-_]+)/',
                '/\/(\d{10,20})/'
            ];
            
            foreach ($padroes as $padrao) {
                if (preg_match($padrao, $url, $matches)) {
                    $numero_gru = $matches[1];
                    $metodo_extracao = "Path da URL com regex: <span class='highlight'>{$padrao}</span>";
                    break;
                }
            }
        }
    }
    
    // ========================================
    // RESULTADO DA EXTRAÇÃO
    // ========================================
    
    if (!empty($numero_gru)) {
        echo '<div class="success">';
        echo '<h3>✅ Número da GRU Extraído com Sucesso!</h3>';
        echo '<p><strong>Número:</strong> <span class="highlight" style="font-size: 18px;">' . htmlspecialchars($numero_gru) . '</span></p>';
        echo '<p><strong>Método de Extração:</strong> ' . $metodo_extracao . '</p>';
        echo '</div>';
        
        // Código para usar no sistema
        echo '<h3>💻 Código para Implementar no Seu Sistema:</h3>';
        
        echo '<pre>';
        echo 'php
// Após receber resposta da DGOM:
$response = json_decode($result);

if (is_object($response)) {
    $numero_gru = null;
    
    // Método 1: Campo direto
    if (isset($response->' . explode(':', $metodo_extracao)[0] . ')) {
        $numero_gru = $response->' . explode(':', $metodo_extracao)[0] . ';
    }
    
    // Método 2: Extrair da URL (se campo direto não existir)
    if (empty($numero_gru) && isset($response->proximaUrl)) {
        $parsed = parse_url($response->proximaUrl);
        if (isset($parsed[\'query\'])) {
            parse_str($parsed[\'query\'], $params);
            $numero_gru = $params[\'numero\'] ?? null;
        }
    }
    
    // Salvar no banco
    if (!empty($numero_gru)) {
        $sql = "UPDATE papem_requisicoes 
                SET numero_gru = ?,
                    url_pagamento = ?,
                    status_requisicao = \'aguardando_pagamento\'
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", 
            $numero_gru, 
            $response->proximaUrl, 
            $requisicao_id
        );
        $stmt->execute();
    }
}
';
        echo '</pre>';
        
    } else {
        echo '<div class="error">';
        echo '<h3>❌ Não Foi Possível Extrair o Número da GRU</h3>';
        echo '<p>A resposta não contém o número em nenhum formato reconhecido.</p>';
        echo '<h4>📋 O Que Fazer:</h4>';
        echo '<ol>';
        echo '<li>Entre em contato com a DGOM</li>';
        echo '<li>Pergunte: "Em qual campo da resposta vem o número da GRU?"</li>';
        echo '<li>Mostre a resposta acima para eles</li>';
        echo '</ol>';
        echo '</div>';
    }
    
    // ========================================
    // URL DE PAGAMENTO
    // ========================================
    
    if (isset($response->proximaUrl)) {
        echo '<h3>🔗 URL de Pagamento:</h3>';
        echo '<div class="info-box">';
        echo '<p><strong>URL:</strong></p>';
        echo '<pre>' . htmlspecialchars($response->proximaUrl) . '</pre>';
        echo '<p><small>⚠️ Esta é a URL que você abrirá para o usuário pagar. <strong>NÃO vamos abrir agora</strong> para evitar confusão no teste.</small></p>';
        echo '</div>';
    }
    
    // ========================================
    // PRÓXIMOS PASSOS
    // ========================================
    
    echo '<h2>📋 Próximos Passos:</h2>';
    echo '<div class="info-box">';
    echo '<ol>';
    echo '<li><strong>✅ Anote o número da GRU:</strong> ' . ($numero_gru ? $numero_gru : 'N/A') . '</li>';
    echo '<li><strong>✅ Implemente o código acima</strong> no seu sistema principal</li>';
    echo '<li><strong>✅ Configure o cron</strong> para consultar status periodicamente</li>';
    echo '<li><strong>⚠️ Cancele esta GRU de teste</strong> (se necessário) no sistema da DGOM</li>';
    echo '<li><strong>🎯 Execute um teste real</strong> com usuário real depois</li>';
    echo '</ol>';
    echo '</div>';
}

?>

        <hr style="margin: 40px 0;">
        
        <h2>📞 Informações de Contato DGOM:</h2>
        <div class="info-box">
            <p>Se encontrar problemas ou tiver dúvidas sobre a integração, entre em contato com a Assessoria do Plano Diretor da DGOM.</p>
        </div>
        
        <div style="text-align: center; margin-top: 40px; padding: 20px; background-color: #f0f0f0; border-radius: 5px;">
            <p><strong>Script de Teste - PAPEM</strong></p>
            <p><small>Data: <?php echo date('d/m/Y H:i:s'); ?></small></p>
        </div>
        
    </div>
</body>
</html>