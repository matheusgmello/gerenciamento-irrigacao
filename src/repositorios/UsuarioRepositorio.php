<?php
namespace App\Repositorios;

class UsuarioRepositorio
{
    private $caminho = __DIR__ . '/../../dados/usuarios.json';

    private function ler_todos(): array
    {
        $conteudo = file_get_contents($this->caminho);
        $dados = json_decode($conteudo, true);
        return is_array($dados) ? $dados : [];
    }

    private function salvar_todos(array $usuarios): void
    {
        file_put_contents($this->caminho, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function criar(array $usuario): void
    {
        $todos = $this->ler_todos();
        $todos[] = $usuario;
        $this->salvar_todos($todos);
    }

    public function buscar_por_username(string $username): ?array
    {
        $todos = $this->ler_todos();
        foreach ($todos as $u) {
            if ($u['username'] === $username) {
                return $u;
            }
        }
        return null;
    }

    // Metodo do debug para listar todos os usuarios
    // Apenas para listar usuarios (desenvolvimento), descomente para usar caso necessário
    // public function listar_todos(): array
    // {
    //     return $this->ler_todos();
    // }
}
