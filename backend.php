<?php
header("Access-Control-Allow-Origin: *"); // Permite requisições de qualquer origem
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Permite POST e OPTIONS
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Se a requisição for OPTIONS, apenas retornamos 200 OK (para evitar bloqueios de CORS)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// Apenas aceita requisições POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit();
}

// Suas credenciais dos Correios
$usuario = "SEU_USUARIO";
$senha = "SUA_SENHA";

// Codifica usuário e senha em Base64
$auth = base64_encode("$usuario:$senha");

// URL da API dos Correios
$url = "https://api.correios.com.br/token/v1/autentica";

// Configuração da requisição
$options = [
    "http" => [
        "method"  => "POST",
        "header"  => "Authorization: Basic $auth\r\n" .
                     "Content-Type: application/json\r\n" .
                     "User-Agent: Mozilla/5.0\r\n",
        "ignore_errors" => true // Garante que erros também sejam capturados
    ]
];

// Faz a requisição
$response = file_get_contents($url, false, stream_context_create($options));
$statusCode = http_response_code();

// Se a resposta estiver vazia, enviamos um JSON de erro
if ($response === FALSE || empty($response)) {
    http_response_code(500);
    echo json_encode(["erro" => "Falha na requisição à API dos Correios", "status" => $statusCode]);
    exit();
}

// Tenta decodificar a resposta para verificar se é um JSON válido
$jsonData = json_decode($response, true);

// Se a API dos Correios não retornar JSON válido, retornamos um erro
if ($jsonData === null) {
    http_response_code(500);
    echo json_encode(["erro" => "A resposta da API não é um JSON válido", "status" => $statusCode, "resposta" => $response]);
    exit();
}

// Retorna a resposta correta
echo json_encode($jsonData);
?>
