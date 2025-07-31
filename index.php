<?php
require __DIR__ . '/vendor/autoload.php';

use App\Controladores\AuthControlador;
use App\Controladores\PivoControlador;
use App\Controladores\IrrigacaoControlador;

$metodo = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = rtrim($uri, '/');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($metodo === 'OPTIONS') {
    http_response_code(200);
    exit;
}

switch (true) {

    // METODOS Autenticação
    case $metodo === 'POST' && $uri === '/auth/register':
        (new AuthControlador())->registrar();
        break;
    case $metodo === 'POST' && $uri === '/auth/login':
        (new AuthControlador())->login();
        break;

    // METODOS Pivos
    case $metodo === 'GET' && $uri === '/pivots':
        (new PivoControlador())->listar();
        break;
    case $metodo === 'GET' && preg_match('#^/pivots/([\w\-]+)$#', $uri, $m):
        (new PivoControlador())->buscar($m[1]);
        break;
    case $metodo === 'POST' && $uri === '/pivots':
        (new PivoControlador())->criar();
        break;
    case ($metodo === 'PUT' || $metodo === 'PATCH') && preg_match('#^/pivots/([\w\-]+)$#', $uri, $m):
        (new PivoControlador())->atualizar($m[1]);
        break;
    case $metodo === 'DELETE' && preg_match('#^/pivots/([\w\-]+)$#', $uri, $m):
        (new PivoControlador())->remover($m[1]);
        break;

    // METODOS Irrigação
    case $metodo === 'GET' && $uri === '/irrigations':
        (new IrrigacaoControlador())->listar();
        break;
    case $metodo === 'GET' && preg_match('#^/irrigations/([\w\-]+)$#', $uri, $m):
        (new IrrigacaoControlador())->buscar($m[1]);
        break;
    case $metodo === 'POST' && $uri === '/irrigations':
        (new IrrigacaoControlador())->criar();
        break;
    case $metodo === 'DELETE' && preg_match('#^/irrigations/([\w\-]+)$#', $uri, $m):
        (new IrrigacaoControlador())->remover($m[1]);
        break;

    // Apenas para listar usuarios (desenvolvimento), descomente para usar caso necessário

    // case $metodo === 'GET' && $uri === '/debug/usuarios':
    // header('Content-Type: application/json');
    // $repo = new \App\Repositorios\UsuarioRepositorio();
    // echo json_encode($repo->listar_todos(), JSON_UNESCAPED_UNICODE);
    // exit;



    default:
        http_response_code(404);
        echo json_encode(['erro' => 'Rota não encontrada']);
        break;
}
