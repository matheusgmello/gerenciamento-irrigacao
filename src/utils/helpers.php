<?php
namespace App\Utils;

use Ramsey\Uuid\Uuid;

class Helpers
{
    public static function gerar_uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function responder_json($dados, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function obter_corpo(): array
    {
        $input = file_get_contents('php://input');
        $dados = json_decode($input, true);
        return is_array($dados) ? $dados : [];
    }

    public static function obter_cabecalho_autorizacao(): ?string
    {
        $headers = getallheaders();
        return $headers['Authorization'] ?? $headers['authorization'] ?? null;
    }
}
