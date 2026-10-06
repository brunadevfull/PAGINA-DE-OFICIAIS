<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: text/plain; charset=utf-8');

$url = 'https://pagtesouro.dgom.mb:3000/handle';
$token = 'eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.X92vQ2oBESAPKtPYj_1eLFengD7eSUhPUGuBagEHUaX6mVuQ55trbQEHecEXqqi1KSgeQXXY70Rmn1M4FvwjIBbQN9xYAf-NEuVVPq9-QGJy58GK8AcYUrlJCsayIplPJuc6kB7Os6YCvN7c59OC38ATVCcuLBx6u5c3jZ3reZSk0dkBUBMDXJyr4wqhHWEZPtl-JFGBswCyvXUh8XLbOAyj98_n-B_7tS5b-K5-SBu7nbhaweSJ0Z4gLwxp1QYwTJqJzgRX6LKfDb0TEjLLKkYw9CS2uDX9IPEzN1K618HzXnM6tLvZh80kM34d91-rc4W785IhzIC-CwR-4h_HHA';

$payload = [
    "cat" => "PAPEM",
    "codigoServico" => 11859,
    "vencimento" => "15052026",
    "competencia" => "042026",
    "nomeContribuinte" => "Servidor Civil Teste",
    "cnpjCpf" => "13899289773",
    "valorDescontos" => 0,
    "valorOutrasDeducoes" => 0,
    "valorMulta" => 0,
    "valorJuros" => 0,
    "valorOutrosAcrescimos" => 0,
    "valorPrincipal" => 0.07,
    "nomeUG" => "SEGCOL - COMANDO DO 2 ESQUADRAO DE ESCOLTA",
    "cod_om" => "008",
    "cat_servico" => "PAPEM",
    "codRubrica" => "00176",
    "nomeRubrica" => "GRATIFICACAO NATALINA AT",
    "motivo" => "TESTE WEB PAPEM",
    "tributavel" => 1,
    "nomeOC" => "DLSEGU - AGENCIA DA CAPITANIA DOS PORTOS EM PORTO SEGURO",
    "cod_oc" => "015",
    "valorBrutoExercAnt" => 0.03,
    "codSiapeNip" => "1234567",
    "modoNavegacao" => "2",
    "tema" => "tema-light",
    "NatDev" => "Pagamento de Pessoal"
];

$json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$verbose = fopen('php://temp', 'w+');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$errno = curl_errno($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);
fclose($verbose);
curl_close($ch);

echo "===== PAYLOAD ENVIADO =====\n";
echo $json . "\n\n";

echo "===== CURL ERRNO =====\n";
echo $errno . "\n\n";

echo "===== CURL ERROR =====\n";
echo $error . "\n\n";

echo "===== HTTP INFO =====\n";
print_r($info);
echo "\n";

echo "===== VERBOSE LOG =====\n";
echo $verboseLog . "\n";

echo "===== RESPOSTA BRUTA =====\n";
echo $response . "\n";
