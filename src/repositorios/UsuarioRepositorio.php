<?php
namespace App\Repositorios;

class UsuarioRepositorio
{
    private $caminho = __DIR__ . '/../../dados/usuarios.json';

    private function ler_todos(): array
    {
        if (!file_exists($this->caminho)) {
            return [];
        }
        $conteudo = file_get_contents($this->caminho);
        $dados = json_decode($conteudo, true);
        return is_array($dados) ? $dados : [];
    }

    private function salvar_todos(array $usuarios): void
    {
        $fp = fopen($this->caminho, 'c+');
        if (!$fp) {
            throw new \Exception("Não foi possível abrir arquivo de usuários", 500);
        }

        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            throw new \Exception("Não foi possível travar arquivo de usuários", 500);
        }

        $conteudo = stream_get_contents($fp);
        $ativos = [];
        if ($conteudo !== false && strlen($conteudo) > 0) {
            $ativos = json_decode($conteudo, true);
            if (!is_array($ativos)) {
                $ativos = [];
            }
        }

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private function normalizar_username(string $username): string
    {
        return mb_strtolower(trim($username));
    }

    public function criar(array $usuario): void
    {
        $todos = $this->ler_todos();

        $novo = $this->normalizar_username($usuario['username']);
        foreach ($todos as $u) {
            if ($this->normalizar_username($u['username']) === $novo) {
                throw new \Exception("Username já existe", 400);
            }
        }
        $todos[] = $usuario;
        $this->salvar_todos($todos);
    }

    public function buscar_por_username(string $username): ?array
    {
        $todos = $this->ler_todos();
        $busca = $this->normalizar_username($username);
        foreach ($todos as $u) {
            if ($this->normalizar_username($u['username']) === $busca) {
                return $u;
            }
        }
        return null;
    }

    public function listar_todos(): array
    {
        return $this->ler_todos();
    }
}
