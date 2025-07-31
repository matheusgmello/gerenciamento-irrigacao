<?php
namespace App\Controladores;

use App\Servicos\PivoServico;
use App\Middlewares\AutenticacaoMiddleware;
use App\Utils\Helpers;

class PivoControlador
{
    private $servico;

    public function __construct()
    {
        $this->servico = new PivoServico();
    }

    public function listar()
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $pivos = $this->servico->listar($user['sub']);
            Helpers::responder_json(['pivos' => $pivos]);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 401);
        }
    }

    public function buscar($id)
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $pivo = $this->servico->buscar($id, $user['sub']);
            Helpers::responder_json(['pivo' => $pivo]);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }

    public function criar()
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $dados = Helpers::obter_corpo();
            $pivo = $this->servico->criar($dados, $user['sub']);
            Helpers::responder_json([
                'mensagem' => 'Pivô criado com sucesso!',
                'pivo' => $pivo
            ], 201);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function atualizar($id)
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $dados = Helpers::obter_corpo();
            $pivo = $this->servico->atualizar($id, $dados, $user['sub']);
            Helpers::responder_json(['pivo' => $pivo]);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    public function remover($id)
    {
        try {
            $user = AutenticacaoMiddleware::verificar();
            $this->servico->remover($id, $user['sub']);
            Helpers::responder_json(['mensagem' => 'Pivô removido com sucesso!']);
        } catch (\Exception $e) {
            Helpers::responder_json(['erro' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}
