<?php
/**
 * TESTE DE CONECTIVIDADE DGOM
 *
 * Objetivo:
 * - NÃO cria GRU
 * - NÃO grava banco
 * - NÃO envia payload funcional
 * - Testa apenas caminho técnico até a DGOM
 *
 * O que verifica:
 * 1. Resolução DNS
 * 2. TCP/TLS até o host
 * 3. Uso de proxy
 * 4. Resposta HTTP do endpoint informado
 * 5. Detalhes do cURL para diagnóstico
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$host = 'pagtesouro.dgom.mb';
$porta = 3000;
$rota = '/'; // altere para '/handle' apenas se quiser testar o endpoint HTTP, sem payload real
$url = "https://{$host}:{$porta}{$rota}";

// ===== CONFIGURAÇÃO DE PROXY =====
// Modo automático: usa variáveis de ambiente do sistema (https_proxy/http_proxy/no_proxy)
$usarProxyAutomatico = true;

// Se quiser forçar manualmente, coloque false acima e preencha abaixo.
$proxyUrl = '';        // Ex: http://proxy-1dn.mb:6060
$proxyCredenciais = ''; // Ex: usuario:senha
$noProxyHosts = [
    // Ex: 'pagtesouro.dgom.mb'
];

// ===== CONFIGURAÇÃO SSL =====
$verificarSSL = true; // deixe true para teste real; mude para false só se quiser validar hipótese de cadeia/certificado

function h($valor): string {
    if (is_array($valor) || is_object($valor)) {
        $valor = print_r($valor, true);
    }
    return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function linha(string $titulo, $valor): void {
    echo '<tr><td><strong>' . h($titulo) . '</strong></td><td>' . nl2br(h($valor)) . '</td></tr>';
}

$inicioGeral = microtime(true);
$dnsResolvido = gethostbyname($host);
$dnsOk = ($dnsResolvido !== $host);

$httpsProxyEnv = getenv('https_proxy') ?: getenv('HTTPS_PROXY') ?: '';
$httpProxyEnv  = getenv('http_proxy') ?: getenv('HTTP_PROXY') ?: '';
$noProxyEnv    = getenv('no_proxy') ?: getenv('NO_PROXY') ?: '';

$proxyEfetivo = '';
$proxyCredenciaisEfetivas = '';

if ($usarProxyAutomatico) {
    $proxyEfetivo = $httpsProxyEnv ?: $httpProxyEnv;
} else {
    $proxyEfetivo = $proxyUrl;
    $proxyCredenciaisEfetivas = $proxyCredenciais;
}

$headersResposta = [];
$verboseStream = fopen('php://temp', 'w+');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: */*',
    'Cache-Control: no-cache',
]);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_STDERR, $verboseStream);

if ($proxyEfetivo !== '') {
    curl_setopt($ch, CURLOPT_PROXY, $proxyEfetivo);
    if (!$usarProxyAutomatico && $proxyCredenciaisEfetivas !== '') {
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyCredenciaisEfetivas);
    }
}

if (!$usarProxyAutomatico && !empty($noProxyHosts)) {
    curl_setopt($ch, CURLOPT_NOPROXY, implode(',', $noProxyHosts));
}

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verificarSSL);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verificarSSL ? 2 : 0);

$corpoEHeaders = curl_exec($ch);
$fim = microtime(true);
$duracaoMs = round(($fim - $inicioGeral) * 1000, 2);

$curlErrno = curl_errno($ch);
$curlErro = curl_error($ch);
$info = curl_getinfo($ch);

rewind($verboseStream);
$verboseLog = stream_get_contents($verboseStream);
fclose($verboseStream);

$headersBrutos = '';
$corpo = '';
if ($corpoEHeaders !== false) {
    $headerSize = $info['header_size'] ?? 0;
    $headersBrutos = substr($corpoEHeaders, 0, $headerSize);
    $corpo = substr($corpoEHeaders, $headerSize);
}

curl_close($ch);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de Conectividade DGOM</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .box { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        h1, h2 { color: #003366; }
        .ok { background: #d4edda; border: 1px solid #28a745; padding: 12px; border-radius: 6px; margin: 12px 0; }
        .warn { background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin: 12px 0; }
        .err { background: #f8d7da; border: 1px solid #dc3545; padding: 12px; border-radius: 6px; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        td, th { border: 1px solid #ddd; padding: 10px; vertical-align: top; }
        th { background: #003366; color: white; }
        pre { background: #111; color: #eee; padding: 14px; border-radius: 6px; overflow-x: auto; white-space: pre-wrap; word-break: break-word; }
        code { background: #eef; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="box">
    <h1>Teste de Conectividade DGOM</h1>

    <div class="warn">
        <strong>Este arquivo não cria GRU.</strong><br>
        Ele só testa caminho técnico até <code><?= h($url) ?></code>.
    </div>

    <?php if ($dnsOk): ?>
        <div class="ok"><strong>DNS OK:</strong> <?= h($host) ?> resolve para <?= h($dnsResolvido) ?></div>
    <?php else: ?>
        <div class="err"><strong>DNS FALHOU:</strong> não foi possível resolver <?= h($host) ?></div>
    <?php endif; ?>

    <?php if ($curlErrno === 0): ?>
        <div class="ok"><strong>cURL executou sem erro de biblioteca.</strong></div>
    <?php else: ?>
        <div class="err"><strong>Erro cURL:</strong> [<?= h((string)$curlErrno) ?>] <?= h($curlErro) ?></div>
    <?php endif; ?>

    <h2>Resumo técnico</h2>
    <table>
        <tr><th>Item</th><th>Valor</th></tr>
        <?php
            linha('URL testada', $url);
            linha('Host', $host);
            linha('Porta', (string)$porta);
            linha('Rota', $rota);
            linha('DNS resolvido', $dnsOk ? $dnsResolvido : 'falhou');
            linha('Tempo total (ms)', (string)$duracaoMs);
            linha('HTTP code', (string)($info['http_code'] ?? 'N/A'));
            linha('Content-Type', $info['content_type'] ?? 'N/A');
            linha('IP remoto', $info['primary_ip'] ?? 'N/A');
            linha('Porta remota', (string)($info['primary_port'] ?? 'N/A'));
            linha('Proxy automático', $usarProxyAutomatico ? 'sim' : 'não');
            linha('https_proxy (ambiente)', $httpsProxyEnv !== '' ? $httpsProxyEnv : 'não definido');
            linha('http_proxy (ambiente)', $httpProxyEnv !== '' ? $httpProxyEnv : 'não definido');
            linha('no_proxy (ambiente)', $noProxyEnv !== '' ? $noProxyEnv : 'não definido');
            linha('Proxy efetivo no teste', $proxyEfetivo !== '' ? $proxyEfetivo : 'nenhum');
            linha('SSL verify peer', $verificarSSL ? 'habilitado' : 'desabilitado');
            linha('SSL verify host', $verificarSSL ? 'habilitado (2)' : 'desabilitado');
        ?>
    </table>

    <h2>Como interpretar</h2>
    <div class="warn">
        <ul>
            <li><strong>Erro de DNS</strong>: problema de resolução de nome.</li>
            <li><strong>Timeout / couldn't connect</strong>: bloqueio de rede, proxy ou destino indisponível.</li>
            <li><strong>SSL certificate problem</strong>: cadeia/certificado/AC local.</li>
            <li><strong>HTTP 404 em <code>/</code></strong>: servidor está de pé; a rota raiz não existe.</li>
            <li><strong>HTTP 401/403</strong>: conectividade existe, mas a aplicação exige autenticação/permissão.</li>
            <li><strong>HTTP 502 do proxy</strong>: quem está falhando é o gateway/proxy, não necessariamente a DGOM.</li>
        </ul>
    </div>

    <h2>Headers da resposta</h2>
    <pre><?= h($headersBrutos !== '' ? $headersBrutos : 'Sem headers capturados.') ?></pre>

    <h2>Corpo da resposta</h2>
    <pre><?= h($corpo !== '' ? $corpo : 'Sem corpo capturado.') ?></pre>

    <h2>Log verboso do cURL</h2>
    <pre><?= h($verboseLog !== '' ? $verboseLog : 'Sem log verboso.') ?></pre>
</div>
</body>
</html>
