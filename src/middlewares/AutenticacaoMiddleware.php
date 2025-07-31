<?php
namespace App\Middlewares;

use App\Servicos\AuthServico;
use App\Utils\Helpers;

class AutenticacaoMiddleware
{
    public static function verificar(): array
    {
        $header = Helpers::obter_cabecalho_autorizacao();
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            throw new \Exception("Token ausente", 401);
        }
        $token = substr($header, 7);
        $auth = new AuthServico();
        $dados = $auth->verificar_token($token);
        return $dados;
    }
}
