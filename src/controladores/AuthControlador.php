<?php
namespace App\Controladores;

use App\Servicos\AuthServico;
use App\Utils\Helpers;

class AuthControlador
{
    private $servico;

    public function __construct()
    {
        $this->servico = new AuthServico();
    }

    public function registrar()
    {
        $dados = Helpers::obter_corpo();
        if (empty($dados['username']) || empty($dados['password'])) {
            Helpers::responder_json(['erro' => 'username e password são obrigatórios'], 400);
        }
        try {
            $usuario = $this->servico->registrar($dados['username'], $dados['password']);
            Helpers::responder_json([
                'mensagem' => 'Usuário criado com sucesso!',
                'usuario' => $usuario
            ], 201);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function login()
    {
        $dados = Helpers::obter_corpo();
        if (empty($dados['username']) || empty($dados['password'])) {
            Helpers::responder_json(['erro' => 'username e password são obrigatórios'], 400);
        }
        try {
            $token = $this->servico->autenticar($dados['username'], $dados['password']);
            Helpers::responder_json(['token' => $token]);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 401);
        }
    }
}
