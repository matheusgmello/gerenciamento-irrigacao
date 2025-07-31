<?php
namespace App\Controladores;

use App\Servicos\IrrigacaoServico;
use App\Middlewares\AutenticacaoMiddleware;
use App\Utils\Helpers;

class IrrigacaoControlador
{
    private $servico;

    public function __construct()
    {
        $this->servico = new IrrigacaoServico();
    }

    public function listar()
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $registros = $this->servico->listar($user['sub']);
            Helpers::responder_json(['irrigacoes' => $registros]);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 401);
        }
    }

    public function buscar($id)
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $registro = $this->servico->buscar($id, $user['sub']);
            Helpers::responder_json(['irrigacao' => $registro]);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }

    public function criar()
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $dados = Helpers::obter_corpo();
            $registro = $this->servico->criar($dados, $user['sub']);
            Helpers::responder_json([
                'mensagem' => 'Registro de irrigação criado com sucesso!',
                'irrigacao' => $registro
            ], 201);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function remover($id)
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $this->servico->remover($id, $user['sub']);
            Helpers::responder_json(['mensagem' => 'Registro removido com sucesso!']);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}
