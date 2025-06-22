# Backend do Triviando.me (Laravel)

Este diretório contém o backend da aplicação Triviando.me, desenvolvido com o framework PHP Laravel.

## Funcionalidades

- **Autenticação de Usuários**: Registro, login e logout de usuários utilizando Laravel Sanctum.
- **Gerenciamento de Quizzes**: Criação, leitura, atualização e exclusão de quizzes por usuários autenticados.
- **Gerenciamento de Perguntas**: Criação, leitura, atualização e exclusão de perguntas associadas a quizzes.
- **Tipos de Pergunta**: Gerenciamento de diferentes tipos de perguntas (múltipla escolha, verdadeiro/falso, etc.).
- **Sessões de Jogo**: Criação e gerenciamento de sessões de jogo, incluindo geração de códigos de acesso.

## Configuração e Instalação

1.  **Pré-requisitos**: Certifique-se de ter PHP (versão 8.1 ou superior), Composer e SQLite instalados em seu sistema.

2.  **Navegue até o diretório do backend**:
    ```bash
    cd backend
    ```

3.  **Instale as dependências do Composer**:
    ```bash
    composer install
    ```

4.  **Configure o arquivo `.env`**:
    Copie o arquivo `.env.example` para `.env`:
    ```bash
    cp .env.example .env
    ```
    Certifique-se de que as configurações do banco de dados no `.env` estejam apontando para SQLite:
    ```
    DB_CONNECTION=sqlite
    DB_DATABASE=/caminho/completo/para/o/seu/projeto/backend/database/database.sqlite
    ```
    (Substitua `/caminho/completo/para/o/seu/projeto` pelo caminho real do seu projeto)

5.  **Crie o arquivo do banco de dados SQLite**:
    ```bash
    touch database/database.sqlite
    ```

6.  **Execute as migrações do banco de dados**:
    ```bash
    php artisan migrate
    ```

7.  **Gere a chave da aplicação**:
    ```bash
    php artisan key:generate
    ```

8.  **Inicie o servidor de desenvolvimento**:
    ```bash
    php artisan serve
    ```
    O backend estará disponível em `http://127.0.0.1:8000` (ou outra porta, se especificado).

## Rotas da API

As rotas da API estão definidas em `routes/api.php`.

-   **Autenticação**:
    -   `POST /api/register`: Registrar um novo usuário.
    -   `POST /api/login`: Autenticar um usuário e obter um token de acesso.
    -   `POST /api/logout`: Fazer logout do usuário (requer autenticação).
    -   `GET /api/user`: Obter informações do usuário autenticado (requer autenticação).

-   **Quizzes** (requer autenticação):
    -   `GET /api/quizzes`: Listar todos os quizzes do usuário.
    -   `POST /api/quizzes`: Criar um novo quiz.
    -   `GET /api/quizzes/{quiz}`: Obter detalhes de um quiz específico.
    -   `PUT /api/quizzes/{quiz}`: Atualizar um quiz existente.
    -   `DELETE /api/quizzes/{quiz}`: Excluir um quiz.

-   **Perguntas** (requer autenticação):
    -   `GET /api/quizzes/{quiz}/questions`: Listar perguntas de um quiz específico.
    -   `POST /api/quizzes/{quiz}/questions`: Criar uma nova pergunta para um quiz.
    -   `GET /api/questions/{question}`: Obter detalhes de uma pergunta específica.
    -   `PUT /api/questions/{question}`: Atualizar uma pergunta existente.
    -   `DELETE /api/questions/{question}`: Excluir uma pergunta.

-   **Tipos de Pergunta** (requer autenticação):
    -   `GET /api/question-types`: Listar todos os tipos de pergunta disponíveis.
    -   `GET /api/question-types/{questionType}`: Obter detalhes de um tipo de pergunta específico.

-   **Sessões de Jogo** (requer autenticação):
    -   `POST /api/game-sessions`: Criar uma nova sessão de jogo para um quiz.
    -   `GET /api/game-sessions/{gameSession}`: Obter detalhes de uma sessão de jogo.
    -   `POST /api/game-sessions/{gameSession}/start`: Iniciar uma sessão de jogo.
    -   `POST /api/game-sessions/{gameSession}/next-question`: Avançar para a próxima pergunta na sessão.
    -   `POST /api/game-sessions/{gameSession}/end`: Finalizar uma sessão de jogo.
