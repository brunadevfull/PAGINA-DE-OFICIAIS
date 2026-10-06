<?php
/**
 * TESTE SIMPLES DE TOKEN - PagTesouro
 * Apenas valida se o token está funcionando
 */

// ============================================
// CONFIGURE AQUI
// ============================================

// Cole seu NOVO token do SISGRU PRODUÇÃO aqui:
$token = "eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.X92vQ2oBESAPKtPYj_1eLFengD7eSUhPUGuBagEHUaX6mVuQ55trbQEHecEXqqi1KSgeQXXY70Rmn1M4FvwjIBbQN9xYAf-NEuVVPq9-QGJy58GK8AcYUrlJCsayIplPJuc6kB7Os6YCvN7c59OC38ATVCcuLBx6u5c3jZ3reZSk0dkBUBMDXJyr4wqhHWEZPtl-JFGBswCyvXUh8XLbOAyj98_n-B_7tS5b-K5-SBu7nbhaweSJ0Z4gLwxp1QYwTJqJzgRX6LKfDb0TEjLLKkYw9CS2uDX9IPEzN1K618HzXnM6tLvZh80kM34d91-rc4W785IhzIC-CwR-4h_HHA";


// Ambiente: 'H' = Homologação | 'P' = Produção
$ambiente = 'P';

// Seu código de serviço
$codigoServico = 11859; // Ou 11860 para SISRES

// ============================================
// PROXY (se necessário)
// ============================================
$usar_proxy = true;
$proxy = "http://11111062:BruN%402025GlowUp@proxy-1dn.mb:6060";

// ============================================
// URLS
// ============================================
if ($ambiente == 'H') {
    $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
} else {
    $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}

// ============================================
// DADOS COMPLETOS E VÁLIDOS PARA TESTE
// ============================================

// Calcular datas
$competencia = date("mY"); // MMAAAA (ex: 102025)
$vencimento = date("dmY", strtotime("+30 days")); // DDMMAAAA (ex: 05112025)

// Montar array de dados
$dados = array(
    "codigoServico" => $codigoServico,
    "referencia" => "123456789",
    "competencia" => $competencia,
    "vencimento" => $vencimento,
    "cnpjCpf" => "00000000000191",
    "nomeContribuinte" => "TESTE SISTEMA PAPEM",
    "valorPrincipal" => "100.00",
    "valorDescontos" => "0.00",
    "valorOutrasDeducoes" => "0.00",
    "valorMulta" => "0.00",
    "valorJuros" => "0.00",
    "valorOutrosAcrescimos" => "0.00",
    "modoNavegacao" => "2"
);

// DEBUG: Verificar se referencia está no array
echo "<!-- DEBUG: referencia = " . $dados['referencia'] . " -->";
echo "<!-- DEBUG: array completo = " . print_r($dados, true) . " -->";

// ============================================
// FAZER REQUISIÇÃO
// ============================================

// Converter para JSON
$jsonData = json_encode($dados);

// DEBUG: Mostrar JSON que será enviado
echo "<!-- JSON ENVIADO: " . $jsonData . " -->";

$ch = curl_init($url);

// Proxy
if ($usar_proxy) {
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
}

// Desabilitar SSL (apenas teste!)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// Configurar requisição
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);

// DEBUG
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

// Executar
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$headersSent = curl_getinfo($ch, CURLINFO_HEADER_OUT);
curl_close($ch);

// ============================================
// EXIBIR RESULTADO
// ============================================
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste Token PagTesouro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .sucesso {
            background: #d4edda;
            border-left: 5px solid #28a745;
            padding: 20px;
            margin: 20px 0;
        }
        .erro {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
            padding: 20px;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            border-left: 5px solid #0c5460;
            padding: 20px;
            margin: 20px 0;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        h1 { color: #333; }
        h2 { color: #0066cc; }
        .config {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<h1>🔐 Teste Completo de Token - PagTesouro</h1>

<?php if ($httpCode == 200 && !$error): ?>
<div style="background: #28a745; color: white; padding: 20px; text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 20px; border-radius: 5px; animation: pulse 2s infinite;">
    🎉🎉🎉 SUCESSO TOTAL! SISTEMA 100% FUNCIONAL! 🎉🎉🎉
</div>
<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}
</style>
<?php endif; ?>

<div class="config">
    <h2>⚙️ Configuração:</h2>
    <strong>Ambiente:</strong> <?= $ambiente == 'H' ? '🧪 Homologação' : '🚀 Produção' ?><br>
    <strong>URL:</strong> <?= $url ?><br>
    <strong>Código Serviço:</strong> <?= $codigoServico ?><br>
    <strong>Token:</strong> <?= substr($token, 0, 20) ?>... (<?= strlen($token) ?> caracteres)<br>
    <strong>Proxy:</strong> <?= $usar_proxy ? '✅ Ativado' : '❌ Desativado' ?>
    
    <details style="margin-top: 10px;">
        <summary><strong>📋 Ver Dados Enviados</strong></summary>
        <pre style="background: #f8f8f8; color: #333; padding: 10px; margin-top: 10px;"><?= json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
    </details>
</div>

<hr>

<h2>📊 Resultado:</h2>

<?php if ($error): ?>
    <div class="erro">
        <h3>❌ Erro de Conexão:</h3>
        <p><?= htmlspecialchars($error) ?></p>
    </div>
<?php else: ?>
    <div class="info">
        <strong>HTTP Status:</strong> <?= $httpCode ?>
    </div>

    <?php
    $resultado = json_decode($response);
    
    if ($httpCode == 200): ?>
        <div class="sucesso">
            <h3>✅ SUCESSO TOTAL! PAGAMENTO CRIADO COM SUCESSO!</h3>
            <p style="font-size: 18px; font-weight: bold; color: #28a745;">
                🎉 Token válido + Dados corretos = Sistema 100% funcional!
            </p>
            <hr style="border-color: #28a745;">
            <p><strong>ID do Pagamento:</strong> <code style="background: #fff; padding: 5px; border-radius: 3px;"><?= $resultado->idPagamento ?? 'N/A' ?></code></p>
            <p><strong>Data de Criação:</strong> <?= $resultado->dataCriacao ?? 'N/A' ?></p>
            <p><strong>Situação:</strong> <span style="background: #fff; padding: 5px; border-radius: 3px;"><?= $resultado->situacao->codigo ?? 'N/A' ?></span></p>
            <?php if (isset($resultado->proximaUrl)): ?>
                <p><strong>🔗 URL para Pagamento:</strong><br>
                <a href="<?= $resultado->proximaUrl ?>" target="_blank" style="word-break: break-all;"><?= $resultado->proximaUrl ?></a></p>
                <p style="background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 10px;">
                    <strong>✅ TUDO FUNCIONANDO!</strong><br>
                    Você pode usar esta URL em um iFrame no seu sistema para o usuário fazer o pagamento.
                </p>
            <?php endif; ?>
        </div>
        
    <?php elseif ($httpCode == 422): ?>
        <div class="erro">
            <h3>⚠️ Erro de Validação (Token OK, mas dados incompletos):</h3>
            <?php if (is_array($resultado)): ?>
                <ul>
                <?php foreach ($resultado as $erro): ?>
                    <li><strong>[<?= $erro->codigo ?>]</strong> <?= $erro->descricao ?></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <p><em>Isso significa que o TOKEN está VÁLIDO! ✅</em></p>
        </div>
        
    <?php elseif ($httpCode == 401 || $httpCode == 403): ?>
        <div class="erro">
            <h3>❌ TOKEN INVÁLIDO ou SEM PERMISSÃO</h3>
            <p>O token não é válido para este ambiente ou UG.</p>
        </div>
        
    <?php else: ?>
        <div class="erro">
            <h3>❌ Erro HTTP <?= $httpCode ?></h3>
        </div>
    <?php endif; ?>

    <details style="margin-top: 20px;">
        <summary><strong>🔍 Ver Resposta Completa (JSON)</strong></summary>
        <pre><?= htmlspecialchars(json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
    </details>
<?php endif; ?>

<hr>

<div class="info">
    <h3>📋 Interpretação dos Resultados:</h3>
    <ul>
        <li><strong>HTTP 200:</strong> ✅ <strong>PERFEITO!</strong> Token válido, dados corretos, sistema 100% funcional!</li>
        <li><strong>HTTP 422 com C0001/C0002:</strong> ⚠️ Token válido, mas faltam campos obrigatórios.</li>
        <li><strong>HTTP 422 com C0005:</strong> ❌ Código de serviço não existe ou não está cadastrado para sua UG.</li>
        <li><strong>HTTP 422 com C0011:</strong> ❌ Serviço sem parametrização no SIAFI. Contate área financeira.</li>
        <li><strong>HTTP 401/403:</strong> ❌ Token inválido ou sem permissão.</li>
        <li><strong>HTTP 422 com C0015:</strong> ❌ Token incorreto ou expirado.</li>
        <li><strong>HTTP 422 com C0020:</strong> ❌ UG do token não corresponde à UG do serviço.</li>
    </ul>
    
    <div style="background: #e7f3ff; padding: 15px; margin-top: 15px; border-left: 4px solid #2196F3;">
        <strong>💡 Erros Comuns e Soluções:</strong>
        <ul>
            <li><strong>C0005 (Serviço inexistente):</strong> Verifique se o código 11859/11860 está cadastrado no SISGRU para sua UG.</li>
            <li><strong>C0011 (Sem parametrização):</strong> O serviço existe no SISGRU, mas falta configuração no SIAFI. Contate a área financeira.</li>
            <li><strong>C0020 (UG não corresponde):</strong> O token é de uma UG diferente do código de serviço.</li>
        </ul>
    </div>
</div>

<hr>

<h3>🔄 Testar Novamente:</h3>
<button onclick="location.reload()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 5px;">
    🔄 Recarregar Teste
</button>

<hr>

<div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin-top: 20px;">
    <strong>⚠️ LEMBRETES:</strong>
    <ul>
        <li><strong>HTTP 200 = SUCESSO TOTAL!</strong> ✅ Sistema pronto para uso!</li>
        <li>HTTP 422 com C0001 = Token válido, só faltam dados (já resolvido com dados completos)</li>
        <li>Se aparecer C0005 ou C0011 = problema no código de serviço ou parametrização SIAFI ❌</li>
        <li>SSL está desabilitado (apenas para testes!)</li>
        <li>Troque sua senha do proxy após os testes! 🔐</li>
    </ul>
    
    <div style="background: #e7f3ff; padding: 10px; margin-top: 10px; border-radius: 5px;">
        <strong>🔄 Testar com outro código de serviço:</strong><br>
        Se usar SISRES, mude no código: <code>$codigoServico = 11860;</code><br>
        Para Pagamento de Pessoal, use: <code>$codigoServico = 11859;</code>
    </div>
</div>

</body>
</html>
