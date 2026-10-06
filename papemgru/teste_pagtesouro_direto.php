<?php
/**
 * ARQUIVO DE TESTE - PagTesouro Direto (sem DGOM)
 * 
 * Este arquivo permite testar a conexão direta com o PagTesouro
 * sem modificar o sistema principal.
 */
$forcar_desabilitar_ssl = true;
// ============================================
// CONFIGURAÇÕES DE TESTE
// ============================================

// Ambiente: 'H' = Homologação/Testes | 'P' = Produção
$ambiente = 'H';

// Token JWT (pegue do SISGRU)
$chave = "eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";

// ============================================
// CONFIGURAÇÕES DE PROXY (MARINHA)
// ============================================

// Usar proxy? true = sim | false = não
$usar_proxy = true;

// Configurações do proxy da Marinha
$proxy_host = "proxy-1dn.mb";
$proxy_port = "6060";
$proxy_usuario = "11111062";
$proxy_senha = "BruN%402025GlowUp"; // ⚠️ SENHA EXPOSTA - ALTERAR APÓS TESTES!

// Montar URL do proxy
$proxy_url = "http://{$proxy_usuario}:{$proxy_senha}@{$proxy_host}:{$proxy_port}";

// ============================================
// OPÇÃO DE EMERGÊNCIA: FORÇAR DESABILITAR SSL
// ============================================
// Se mesmo com certificados do sistema não funcionar,
// ative esta opção (APENAS PARA TESTES!)
$forcar_desabilitar_ssl = false; // Mude para true se necessário

// ============================================
// URLS - CONFORME DOCUMENTAÇÃO OFICIAL
// ============================================

// Escolha qual URL usar:
// - true = URL via DGOM (servidor da Marinha - usa certificado MB)
// - false = URL direta PagTesouro (servidor do Tesouro Nacional - precisa certificados públicos)
$usar_url_dgom = true;  // ⚠️ MUDE AQUI!

if ($usar_url_dgom) {
    // URLs via DGOM (servidor da Marinha)
    if ($ambiente == 'H') {
        $url = 'https://dpagtesourohmg.mb:3000/handle';
        $codigoServico = 1541; // Código de teste
    } else {
        $url = 'https://pagtesouro.dgom.mb:3000/handle';
        $codigoServico = 11859; // Seu código real
    }
    echo "<div style='background: #e7f3ff; padding: 10px; border-left: 4px solid #2196F3; margin-bottom: 15px;'>";
    echo "<strong>🔄 Usando URL via DGOM (Marinha)</strong><br>";
    echo "Esta URL usa o certificado da Marinha e deve funcionar!";
    echo "</div>";
} else {
    // URLs diretas PagTesouro (servidor do Tesouro Nacional)
    if ($ambiente == 'H') {
        $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
        $codigoServico = 23; // Código de teste
    } else {
        $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
        $codigoServico = 11859; // Seu código real
    }
    echo "<div style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin-bottom: 15px;'>";
    echo "<strong>🌐 Usando URL direta PagTesouro (Tesouro Nacional)</strong><br>";
    echo "Esta URL precisa de certificados de CAs públicas.";
    echo "</div>";
}

// ============================================
// DADOS DE TESTE
// ============================================

$dadosTeste = array(
    "codigoServico" => $codigoServico,
    "referencia" => "123456",
    "competencia" => date("m") . date("Y"), // Mês/Ano atual
    "vencimento" => date("dmY", strtotime("+30 days")), // 30 dias à frente
    "cnpjCpf" => "00000000000191", // CPF de teste
    "nomeContribuinte" => "Teste PagTesouro Direto",
    "valorPrincipal" => "100.00",
    "valorDescontos" => "0",
    "valorOutrasDeducoes" => "0",
    "valorMulta" => "0",
    "valorJuros" => "0",
    "valorOutrosAcrescimos" => "0",
    "modoNavegacao" => "2",
    "urlNotificacao" => "" // Deixe vazio se não tiver
);

// ============================================
// FUNÇÃO DE TESTE
// ============================================

function testarPagTesouro($url, $chave, $dados) {
    
    echo "<h2>🔍 Teste de Conexão Direta com PagTesouro</h2>";
    echo "<hr>";
    
    echo "<h3>📋 Dados da Requisição:</h3>";
    echo "<strong>URL:</strong> " . $url . "<br>";
    echo "<strong>Ambiente:</strong> " . ($GLOBALS['ambiente'] == 'H' ? 'Homologação' : 'Produção') . "<br><br>";
    
    echo "<strong>Dados enviados:</strong><br>";
    echo "<pre>" . json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    echo "<hr>";
    
    // Preparar dados JSON
    $data_string = json_encode($dados);
    
    // Iniciar cURL
    $ch = curl_init($url);
    
    // ============================================
    // CONFIGURAR PROXY (se habilitado)
    // ============================================
    if ($GLOBALS['usar_proxy']) {
        curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['proxy_url']);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        
        echo "<div style='background: #e6f3ff; padding: 10px; border-left: 4px solid #0066cc; margin-bottom: 15px;'>";
        echo "<strong>🔄 Usando Proxy:</strong> {$GLOBALS['proxy_host']}:{$GLOBALS['proxy_port']}<br>";
        echo "<strong>Usuário:</strong> {$GLOBALS['proxy_usuario']}";
        echo "</div>";
    }
    
    // ============================================
    // CONFIGURAR CERTIFICADO SSL
    // ============================================
    
    // Verificar se deve forçar desabilitar SSL
    if ($GLOBALS['forcar_desabilitar_ssl']) {
        echo "<div style='background: #ffcccc; padding: 10px; border-left: 4px solid #ff0000; margin-bottom: 15px;'>";
        echo "<strong>🔓 SSL DESABILITADO FORÇADAMENTE!</strong><br>";
        echo "A verificação SSL foi desabilitada manualmente (apenas para testes!)";
        echo "</div>";
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
    } else {
        // Escolher certificados baseado na URL
        if ($GLOBALS['usar_url_dgom']) {
            // Para URLs da Marinha (*.mb), priorizar certificado da Marinha
            $possiveisCaminhos = [
                "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem",
                dirname(__FILE__) . "/MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem",
                "../MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem",
                "/etc/pki/tls/certs/ca-bundle.crt",
                "/etc/ssl/certs/ca-certificates.crt"
            ];
        } else {
            // Para URLs públicas (*.gov.br), priorizar certificados do sistema
            $possiveisCaminhos = [
                "/etc/pki/tls/certs/ca-bundle.crt",
                "/etc/ssl/certs/ca-certificates.crt",
                "cacert.pem",
                dirname(__FILE__) . "/cacert.pem",
                "../cacert.pem"
            ];
        }
        
        $certificadoEncontrado = false;
        foreach ($possiveisCaminhos as $caminho) {
            if (file_exists($caminho)) {
                curl_setopt($ch, CURLOPT_CAINFO, $caminho);
                $certificadoEncontrado = true;
                echo "<div style='background: #d4edda; padding: 10px; border-left: 4px solid #28a745; margin-bottom: 15px;'>";
                echo "<strong>✅ Certificado encontrado e configurado:</strong><br>{$caminho}";
                echo "</div>";
                break;
            }
        }
        
        // Se não encontrou certificado, desabilitar verificação SSL (APENAS PARA TESTES!)
        if (!$certificadoEncontrado) {
            echo "<div style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffaa00; margin-bottom: 15px;'>";
            echo "<strong>⚠️ AVISO:</strong> Certificado SSL não encontrado nos caminhos padrão.<br>";
            echo "<strong>Verificação SSL DESABILITADA automaticamente (apenas para testes!)</strong><br>";
            echo "<small>Para produção, configure o certificado adequadamente.</small>";
            echo "</div>";
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
    }
    
    // Configurar requisição
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $chave
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Obter informações de debug
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    
    // Executar requisição
    echo "<h3>⏳ Executando requisição...</h3>";
    $inicio = microtime(true);
    $result = curl_exec($ch);
    $tempo = round((microtime(true) - $inicio) * 1000, 2);
    
    // Obter informações da requisição
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headersSent = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    
    echo "<h3>📊 Resultado:</h3>";
    echo "<strong>Tempo de resposta:</strong> {$tempo}ms<br>";
    echo "<strong>HTTP Status Code:</strong> " . $httpCode . "<br><br>";
    
    // Verificar erros de cURL
    if ($result === false) {
        echo "<div style='background: #ffcccc; padding: 15px; border-left: 4px solid #ff0000;'>";
        echo "<strong>❌ Erro de Conexão cURL:</strong><br>";
        echo curl_error($ch);
        echo "</div>";
        
        curl_close($ch);
        return;
    }
    
    curl_close($ch);
    
    // Decodificar resposta
    $resultado = json_decode($result);
    
    // Exibir resposta
    if ($httpCode == 200) {
        echo "<div style='background: #ccffcc; padding: 15px; border-left: 4px solid #00ff00;'>";
        echo "<strong>✅ SUCESSO!</strong><br><br>";
        echo "<strong>Resposta do PagTesouro:</strong><br>";
        echo "<pre>" . json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        
        if (isset($resultado->proximaUrl)) {
            echo "<br><strong>🔗 URL para pagamento:</strong><br>";
            echo "<a href='{$resultado->proximaUrl}' target='_blank'>{$resultado->proximaUrl}</a>";
        }
        echo "</div>";
        
    } else if ($httpCode == 422) {
        echo "<div style='background: #ffffcc; padding: 15px; border-left: 4px solid #ffaa00;'>";
        echo "<strong>⚠️ ERRO DE VALIDAÇÃO (422)</strong><br><br>";
        
        if (is_array($resultado)) {
            echo "<strong>Erros encontrados:</strong><br>";
            foreach ($resultado as $erro) {
                echo "• [" . $erro->codigo . "] " . $erro->descricao . "<br>";
            }
        } else {
            echo "<pre>" . json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        }
        echo "</div>";
        
    } else {
        echo "<div style='background: #ffcccc; padding: 15px; border-left: 4px solid #ff0000;'>";
        echo "<strong>❌ ERRO HTTP {$httpCode}</strong><br><br>";
        echo "<strong>Resposta:</strong><br>";
        echo "<pre>" . htmlspecialchars($result) . "</pre>";
        echo "</div>";
    }
    
    // Exibir headers enviados (debug)
    echo "<br><details>";
    echo "<summary><strong>🔧 Debug - Headers Enviados</strong></summary>";
    echo "<pre>" . htmlspecialchars($headersSent) . "</pre>";
    echo "</details>";
}

// ============================================
// EXECUTAR TESTE
// ============================================

// Função auxiliar para verificar ambiente
function verificarAmbiente() {
    echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3; margin-bottom: 20px;'>";
    echo "<h3 style='margin-top: 0;'>🔍 Diagnóstico do Ambiente</h3>";
    
    // Versão do PHP
    echo "<strong>Versão do PHP:</strong> " . phpversion() . "<br>";
    
    // cURL disponível?
    echo "<strong>cURL:</strong> " . (function_exists('curl_version') ? '✅ Disponível' : '❌ Não disponível') . "<br>";
    
    if (function_exists('curl_version')) {
        $curl_info = curl_version();
        echo "<strong>Versão cURL:</strong> " . $curl_info['version'] . "<br>";
        echo "<strong>OpenSSL:</strong> " . $curl_info['ssl_version'] . "<br>";
    }
    
    // Verificar certificados
    $caminhosCert = [
        "/etc/pki/tls/certs/ca-bundle.crt" => "Sistema RedHat/CentOS (✅ Funciona para PagTesouro)",
        "/etc/ssl/certs/ca-certificates.crt" => "Sistema Debian/Ubuntu (✅ Funciona para PagTesouro)",
        "cacert.pem" => "Baixado do curl.se (✅ Funciona para PagTesouro)",
        "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem" => "Marinha (⚠️ Apenas para sites *.mb)"
    ];
    
    echo "<strong>Certificados encontrados:</strong><br>";
    $algumEncontrado = false;
    foreach ($caminhosCert as $cert => $descricao) {
        if (file_exists($cert)) {
            echo "&nbsp;&nbsp;✅ {$cert}<br>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em style='color: #666;'>{$descricao}</em><br>";
            $algumEncontrado = true;
        }
    }
    
    if (!$algumEncontrado) {
        echo "&nbsp;&nbsp;⚠️ Nenhum certificado encontrado nos caminhos padrão<br>";
    }
    
    // Verificar php.ini
    $phpIniCainfo = ini_get('curl.cainfo');
    echo "<strong>curl.cainfo (php.ini):</strong> " . ($phpIniCainfo ?: '❌ Não configurado') . "<br>";
    
    echo "</div>";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste PagTesouro Direto</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h2 { color: #003366; }
        h3 { color: #0066cc; margin-top: 20px; }
        pre {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        hr {
            border: none;
            border-top: 2px solid #ddd;
            margin: 20px 0;
        }
        details {
            margin-top: 20px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        summary {
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php
// Exibir diagnóstico do ambiente
verificarAmbiente();

// Executar o teste
testarPagTesouro($url, $chave, $dadosTeste);
?>

<hr>
<h3>📝 Instruções de Uso:</h3>
<ol>
    <li><strong>Escolha qual URL usar:</strong>
        <div style='background: #e7f3ff; padding: 10px; margin: 10px 0; border-left: 4px solid #2196F3;'>
            <strong>No topo do arquivo, configure:</strong><br><br>
            
            Para usar <strong>via DGOM</strong> (recomendado - usa certificado da Marinha):<br>
            <code style='background: #ccffcc; padding: 2px 5px;'>$usar_url_dgom = true;</code><br><br>
            
            Para usar <strong>direto PagTesouro</strong> (oficial - precisa certificados públicos):<br>
            <code>$usar_url_dgom = false;</code>
        </div>
    </li>
    <li><strong>Configure o ambiente:</strong> Altere a variável <code>$ambiente</code> para 'H' (teste) ou 'P' (produção)</li>
    <li><strong>Atualize o token:</strong> Substitua o valor de <code>$chave</code> pelo seu token JWT válido do SISGRU</li>
    <li><strong>Configure o proxy:</strong> 
        <ul>
            <li>Defina <code>$usar_proxy = true;</code></li>
            <li>⚠️ <strong>IMPORTANTE:</strong> Altere <code>$proxy_senha</code> para sua senha real</li>
            <li>Verifique se <code>$proxy_usuario</code> está correto</li>
        </ul>
    </li>
    <li><strong>Se persistir erro SSL:</strong>
        <div style='background: #fff3cd; padding: 10px; margin: 10px 0; border-left: 4px solid #ffc107;'>
            <strong>⚡ Solução Rápida:</strong><br>
            No topo do arquivo, mude:<br>
            <code>$forcar_desabilitar_ssl = false;</code><br>
            para<br>
            <code style='background: #ffcccc; padding: 2px 5px;'>$forcar_desabilitar_ssl = true;</code><br>
            <br>
            <strong>Isso desabilita a verificação SSL (apenas para testes!).</strong>
        </div>
    </li>
    <li><strong>Ajuste os dados:</strong> Modifique o array <code>$dadosTeste</code> conforme necessário</li>
    <li><strong>Execute:</strong> Acesse este arquivo pelo navegador</li>
</ol>

<h3>🔒 Alerta de Segurança:</h3>
<div style='background: #ffcccc; padding: 15px; border-left: 4px solid #ff0000;'>
    <strong>⚠️ NUNCA compartilhe sua senha em chats, e-mails ou documentos!</strong><br>
    Como você expôs sua senha no chat, recomendamos <strong>alterá-la imediatamente</strong> após os testes.
</div>

<hr>

<h3>🔧 Troubleshooting - Erro de Certificado SSL:</h3>
<div style='background: #f8f9fa; padding: 15px; border: 1px solid #ddd;'>
    <h4>Se aparecer: "SSL certificate problem: unable to get local issuer certificate"</h4>
    
    <div style='background: #fff3cd; padding: 10px; margin: 10px 0; border-left: 4px solid #ffc107;'>
        <strong>⚠️ Problema Identificado:</strong><br>
        O certificado <code>MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem</code> serve apenas para sites da Marinha (*.mb).<br>
        O PagTesouro (<code>*.tesouro.gov.br</code>) usa certificados de CAs públicas diferentes!
    </div>
    
    <p><strong>Solução Automática Aplicada:</strong></p>
    <p>O código agora <strong>prioriza o certificado do sistema Linux</strong> (<code>/etc/pki/tls/certs/ca-bundle.crt</code>) que foi encontrado no seu servidor. Este certificado contém todas as CAs públicas reconhecidas e deve funcionar!</p>
    
    <hr style='border-color: #ccc;'>
    
    <p><strong>Solução Permanente (para produção):</strong></p>
    
    <ol>
        <li><strong>Opção 1 - Usar certificado do sistema:</strong>
            <ul>
                <li><strong>Linux:</strong> Certificados geralmente estão em <code>/etc/ssl/certs/ca-certificates.crt</code></li>
                <li><strong>Windows:</strong> Configure no <code>php.ini</code>:
                    <pre style='background: #f5f5f5; padding: 10px;'>curl.cainfo = "C:\caminho\para\cacert.pem"</pre>
                </li>
            </ul>
        </li>
        
        <li><strong>Opção 2 - Baixar certificado CA Bundle:</strong>
            <ul>
                <li>Baixe de: <a href="https://curl.se/docs/caextract.html" target="_blank">https://curl.se/docs/caextract.html</a></li>
                <li>Ou clique aqui para baixar direto: <a href="https://curl.se/ca/cacert.pem" target="_blank" download="cacert.pem"><strong>📥 Baixar cacert.pem</strong></a></li>
                <li>Salve como <code>cacert.pem</code> na mesma pasta deste arquivo</li>
                <li>Configure no <code>php.ini</code>: <code>curl.cainfo = "/caminho/completo/cacert.pem"</code></li>
                <li>Reinicie o servidor web (Apache/Nginx)</li>
            </ul>
        </li>
        
        <li><strong>Opção 3 - Usar o certificado da Marinha:</strong>
            <ul>
                <li>Certifique-se de que o arquivo <code>MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem</code> está na mesma pasta</li>
                <li>Verifique as permissões de leitura do arquivo</li>
            </ul>
        </li>
    </ol>
    
    <hr style='border-color: #ccc;'>
    
    <p><strong>Verificar configuração do PHP:</strong></p>
    <pre style='background: #2d2d2d; color: #f8f8f2; padding: 10px;'>
# Ver configuração atual do curl.cainfo
php -i | grep curl.cainfo

# Ou criar um arquivo phpinfo.php com:
&lt;?php phpinfo(); ?&gt;</pre>
</div>

<h3>🔄 Comparação de URLs:</h3>
<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin-bottom: 15px;'>
    <strong>💡 Qual URL usar?</strong><br><br>
    
    <strong>Via DGOM (Marinha):</strong><br>
    ✅ Usa o certificado da Marinha que você já tem<br>
    ✅ Já está funcionando no seu sistema<br>
    ✅ Passa pelo servidor da MB (proxy interno)<br>
    ⚠️ Não é a URL oficial da documentação do Tesouro<br><br>
    
    <strong>Direta PagTesouro (Tesouro Nacional):</strong><br>
    ✅ É a URL oficial da documentação<br>
    ✅ Conexão direta com o Tesouro<br>
    ⚠️ Precisa de certificados de CAs públicas<br>
    ⚠️ Precisa configurar certificados do sistema<br><br>
    
    <strong>🎯 Recomendação:</strong> Use <code>$usar_url_dgom = true;</code> (via DGOM) que já funciona!
</div>

<table border="1" cellpadding="10" style="width:100%; background: white;">
    <tr style="background: #003366; color: white;">
        <th>Ambiente</th>
        <th>Via DGOM (servidor MB)</th>
        <th>Direto PagTesouro (servidor Tesouro)</th>
        <th>Certificado</th>
    </tr>
    <tr>
        <td><strong>Homologação</strong></td>
        <td style="background: #ccffcc;">https://dpagtesourohmg.mb:3000/handle</td>
        <td>https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento</td>
        <td>MarinhaBrasil...pem<br>(você tem ✅)</td>
    </tr>
    <tr>
        <td><strong>Produção</strong></td>
        <td style="background: #ccffcc;">https://pagtesouro.dgom.mb:3000/handle</td>
        <td>https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento</td>
        <td>ca-bundle.crt<br>(sistema Linux)</td>
    </tr>
</table>

<h3>🌐 Configuração de Proxy (Alternativa via Variáveis de Ambiente):</h3>
<div style='background: #f8f9fa; padding: 15px; border: 1px solid #ddd;'>
    <p>Se preferir, você pode configurar o proxy via variáveis de ambiente do sistema operacional:</p>
    <pre style='background: #2d2d2d; color: #f8f8f2; padding: 10px;'>
# Linux/Mac (bash)
export http_proxy="http://usuario:senha@proxy-1dn.mb:6060"
export https_proxy="http://usuario:senha@proxy-1dn.mb:6060"

# Windows (cmd)
set http_proxy=http://usuario:senha@proxy-1dn.mb:6060
set https_proxy=http://usuario:senha@proxy-1dn.mb:6060

# Windows (PowerShell)
$env:http_proxy="http://usuario:senha@proxy-1dn.mb:6060"
$env:https_proxy="http://usuario:senha@proxy-1dn.mb:6060"</pre>
    <p><strong>⚠️ Nota:</strong> Para usar variáveis de ambiente no PHP/Apache, você precisará configurar no <code>php.ini</code> ou <code>.htaccess</code></p>
</div>

</body>
</html>
