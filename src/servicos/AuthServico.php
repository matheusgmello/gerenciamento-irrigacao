<?php
namespace App\Servicos;

use App\Repositorios\UsuarioRepositorio;
use App\Utils\Helpers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthServico
{
    private $repo;
    private $config;

    public function __construct()
    {
        $this->repo = new UsuarioRepositorio();
        $this->config = require __DIR__ . '/../Config/jwt.php';
    }

    public function registrar(string $username, string $senha): array
    {
        $username_normalizado = trim($username);
        if ($this->repo->buscar_por_username($username_normalizado)) {
            throw new \Exception("Usuário já existe", 400);
        }

        $usuario = [
            'id' => Helpers::gerar_uuid(),
            'username' => $username_normalizado,
            'senha_hash' => password_hash($senha, PASSWORD_DEFAULT)
        ];
        $this->repo->criar($usuario);
        unset($usuario['senha_hash']);
        return $usuario;
    }


    public function autenticar(string $username, string $senha): string
    {
        $usuario = $this->repo->buscar_por_username($username);
        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            throw new \Exception("Credenciais inválidas", 401);
        }

        $agora = time();
        $payload = [
            'iss' => 'api-irrigacao',
            'sub' => $usuario['id'],
            'username' => $usuario['username'],
            'iat' => $agora,
            'exp' => $agora + $this->config['expiracao_segundos']
        ];

        return JWT::encode($payload, $this->config['chave_secreta'], $this->config['algoritmo']);
    }

    public function verificar_token(string $token): array
    {
        try {
            $dados = (array) JWT::decode($token, new Key($this->config['chave_secreta'], $this->config['algoritmo']));
            return $dados;
        } catch (\Exception $e) {
            throw new \Exception("Token inválido: " . $e->getMessage(), 401);
        }
    }
}
