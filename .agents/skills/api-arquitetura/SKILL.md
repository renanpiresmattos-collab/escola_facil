# API Arquitetura

## Objetivo
Usar esta skill para revisar e alterar os fluxos de API do projeto em CodeIgniter 4: login, logout, menu, RUP, usuários, filtros de autenticação, seeds, migrations e helpers de resposta.

## Onde olhar primeiro
1. `app/Config/Routes.php`
2. `app/Filters/AuthFilter.php` e `app/Filters/ApiAuthFilter.php`
3. `app/Controllers/Api/*`
4. `app/Services/*`
5. `app/Repositories/*`
6. `app/Helpers/response_helper.php`
7. `app/Database/Migrations/*`
8. `app/Database/Seeds/*`

## Padrões do projeto
- Controllers devem ser finos e chamar services.
- Services concentram regra de negócio e lançam exceções previsíveis.
- Respostas devem seguir o envelope `status`, `message`, `data` e `meta` quando houver paginação.
- Login usa sessão; logout destrói a sessão.
- Endpoints protegidos devem respeitar `api-auth`.
- A listagem da RUP precisa suportar filtro e paginação na consulta.

## Fluxos reais deste repositório
- `POST /api/login`: recebe JSON ou form data, valida credenciais, verifica `status = ATIVO` e grava sessão.
- `POST /api/logout`: encerra a sessão.
- `GET /api/menu`: devolve a navegação da sidebar protegida por autenticação.
- `GET /api/usuarios`: lista usuários autenticados.
- `GET /api/rup`: lista registros paginados com filtro por CPF ou nome.
- `GET /api/rup/{id}`: carrega um registro para edição.
- `POST /api/rup` e `PUT /api/rup/{id}`: salvam dados da RUP.

## Como trabalhar
- Antes de alterar um endpoint, trace a rota até service e repository.
- Prefira reutilizar `api_service_response()` para tratamento de exceções, códigos HTTP e metadados.
- Preserve mensagens curtas e objetivas em português.
- Se mudar contrato de resposta, ajuste front e testes juntos.
- Quando houver seed novo, documente a senha em comentário no arquivo do seed.

## Checklist
- Verificar payload de entrada.
- Verificar status HTTP esperado.
- Verificar sessão, filtros e status de usuário.
- Verificar `meta` de paginação.
- Verificar impacto na tela que consome a API.
