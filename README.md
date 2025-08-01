# API de Gerenciamento de Irrigação

Projeto em **PHP 8+** desenvolvido em (monolito em camadas) para gerenciar pivôs de irrigação e registros de aplicação de água, com **autenticação JWT**.
Persistência feita em **arquivos JSON** para simplificar o teste do desafio.

Dentro do projeto, optei por questao de poupar tempo, deixar a chave JWT publica no codigo, ao inves de usar o .env

Projeto possui uma segunda branch onde fiz a persistencia de dados, via SQLite como bonus alem tbm de dockerizar a aplicacao via compose do docker

---

## 📦 Estrutura do Projeto

```
/
├─ dados/
│  ├─ usuarios.json
│  ├─ pivos.json
│  └─ irrigacoes.json
├─ src/
│  ├─ Config/
│  ├─ Controladores/
│  ├─ Middlewares/
│  ├─ Repositorios/
│  ├─ Servicos/
│  └─ Utils/
├─ index.php
├─ composer.json
└─ README.md
```

---

## 🚀 Como Rodar Localmente

###### pré requisito: PHP 8+
1. Instale as dependências:

```bash
composer install
```

2. Inicie o servidor PHP:

```bash
php -S localhost:8000
```
3. Teste as requisicoes via CURL ou Postman
---
## 🐳 Como Rodar com Docker Compose

###### pré requisito: Docker e Docker Compose

1. **Build e subir o container**:
   ```bash
    docker compose up --build
    ```

2. **Testar API**:
   ```bash
    http://localhost:8000
    ```

---
## 🔑 Autenticação

**Lembrete: existem dados já criados na pasta dados, caso queira criar tudo desde o inicio apenas apague o conteudo dentro de cada arquivo dados/.json**

- `POST http://localhost:8000/auth/register` → cria usuário
##### Exemplo de usuario já criado
``` json
{
  "username": "joao",
  "password": "senha123"
}

```

##### Exemplo de usuario para criar
``` json
{
  "username": "carlos",
  "password": "senha123"
}

```
- `POST http://localhost:8000/auth/login` → retorna JWT

##### Exemplo de usuario já criado, para autenticar
```json
{
  "username": "joao",
  "password": "senha123"
}

```
##### Resposta do token
###### Token vai mudar quando logar novamente
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhcGktaXJyaWdhY2"
}

```


Todos os outros endpoints exigem cabeçalho (TOKEN):

```
Authorization: Bearer <seu_token>
```

---

## Pivôs (Necessário TOKEN)
- `GET http://localhost:8000/pivots`

##### Resposta do endpoint
```json
{
  "pivos": [
    {
      "id": "72ede0a5-c174-4fd2-b53b-196e317b15e8",
      "description": "Pivô Fazenda A",
      "flowRate": 150.5,
      "minApplicationDepth": 5,
      "userId": "81cf6cab-7efd-440b-8de3-0a7cd122f7ef"
    },
    {
      "id": "3d36b32d-e508-42f9-8d58-9d24ccaf5f52",
      "description": "Pivô Fazenda C",
      "flowRate": 150.5,
      "minApplicationDepth": 5,
      "userId": "81cf6cab-7efd-440b-8de3-0a7cd122f7ef"
    }
  ]
}
```

- `GET http://localhost:8000/pivots/72ede0a5-c174-4fd2-b53b-196e317b15e8`
##### Resposta do endpoint
```json
{
    "pivo": {
        "id": "72ede0a5-c174-4fd2-b53b-196e317b15e8",
        "description": "Pivô Fazenda A",
        "flowRate": 150.5,
        "minApplicationDepth": 5,
        "userId": "30f1696e-38d6-4d75-849b-189968513006"
    }
}
```
- `POST http://localhost:8000/pivots`
##### Exemplo de Pivo já criado
```json
{
  "description": "Pivô Fazenda A",
  "flowRate": 150.5,
  "minApplicationDepth": 5.0
}
```
##### Exemplo de Pivo para criar
```json
{
  "description": "Pivô Fazenda C",
  "flowRate": 170.5,
  "minApplicationDepth": 5.0
}
```

- `PUT http://localhost:8000/pivots/72ede0a5-c174-4fd2-b53b-196e317b15e8`
##### Exemplo para alterar Pivo
```json
{
  "description": "Pivô Fazenda A Alterado",
  "flowRate": 160.5,
  "minApplicationDepth": 6.0
}
```
- `DELETE http://localhost:8000/pivots/72ede0a5-c174-4fd2-b53b-196e317b15e8`
##### Resposta do endpoint
```json
{
  "message": "Pivô deletado com sucesso"
}
```

### Irrigações (Necessário TOKEN)
- `GET http://localhost:8000/irrigations`
```json
{
  "irrigacoes": [
    {
      "id": "44b97e58-5575-4761-97ca-e7daebed8010",
      "pivotId": "72ede0a5-c174-4fd2-b53b-196e317b15e8",
      "applicationAmount": 30,
      "irrigationDate": "2025-08-01T10:00:00Z",
      "userId": "30f1696e-38d6-4d75-849b-189968513006"
    },
    {
      "id": "17b636ca-a46d-458f-b2b1-ba3d41d2aefb",
      "pivotId": "3d36b32d-e508-42f9-8d58-9d24ccaf5f52",
      "applicationAmount": 30,
      "irrigationDate": "2025-08-01T10:00:00Z",
      "userId": "81cf6cab-7efd-440b-8de3-0a7cd122f7ef"
    }
  ]
}
```
- `GET http://localhost:8000/irrigations/44b97e58-5575-4761-97ca-e7daebed8010`
##### Resposta do endpoint
```json
{
    "irrigacao": {
        "id": "44b97e58-5575-4761-97ca-e7daebed8010",
        "pivotId": "3d36b32d-e508-42f9-8d58-9d24ccaf5f52",
        "applicationAmount": 20,
        "irrigationDate": "2025-07-01T10:00:00Z",
        "userId": "81cf6cab-7efd-440b-8de3-0a7cd122f7ef"
    }
}
```
- `POST http://localhost:8000/irrigations`
##### Body
```json
{
  "pivotId": "72ede0a5-c174-4fd2-b53b-196e317b15e8",
  "applicationAmount": 20.0,
  "irrigationDate": "2025-07-01T10:00:00Z"
}
```
##### Resposta do endpoint
```json
{
    "mensagem": "Registro de irrigação criado com sucesso!",
    "irrigacao": {
        "id": "b53e472f-eafe-4517-b003-a7b6ee44e4c3",
        "pivotId": "3d36b32d-e508-42f9-8d58-9d24ccaf5f52",
        "applicationAmount": 20,
        "irrigationDate": "2025-07-01T10:00:00Z",
        "userId": "81cf6cab-7efd-440b-8de3-0a7cd122f7ef"
    }
}
```

- `DELETE http://localhost:8000/irrigations/b53e472f-eafe-4517-b003-a7b6ee44e4c`
##### Resposta do endpoint
```json
{
  "message": "Registro de irrigação deletado com sucesso"
}
```

---


## 📄 Observações

- Dados salvos em arquivos JSON em `dados/`, ou na branch `sqlite` em um banco SQLite
- `jwt.php` contém a chave pública para autenticação JWT, para simplificar o teste
- Testado com **Postman** e **curl**  
- Dockerização em ambas branchs, com Docker Compose para facilitar o ambiente de desenvolvimento
