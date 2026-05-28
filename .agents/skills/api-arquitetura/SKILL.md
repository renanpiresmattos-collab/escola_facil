# Arquitetura de API — CodeIgniter 4

## Resumo

Este documento define a arquitetura, padrões e fluxos para construir endpoints REST na aplicação. A estrutura segue uma separação clara de responsabilidades: **Controller → Service → Model/Repository**.

---

## Stack Tecnológico

- **Framework**: CodeIgniter 4
- **Linguagem**: PHP 8.0+
- **Padrão HTTP**: REST
- **Resposta**: JSON
- **Autenticação**: Sessão (filtro `api-auth`)
- **Validação**: Serviços com exceções tipadas

---

## Arquitetura em Camadas

```
HTTP Request
    ↓
[Controller] → extrai parâmetros, orquestra chamada ao service
    ↓
[Service] → lógica de negócio, lançar exceções em erros
    ↓
[Model] → CRUD simples (create, read, update, delete)
[Repository] → queries SQL complexas (filtros avançados, joins)
    ↓
HTTP Response (formato padronizado)
```

### 1. Controller (Apresentação)

**Responsabilidades:**
- Receber requisições HTTP
- Extrair parâmetros (JSON, POST, query string)
- Chamar o service via `api_service_response()`
- Retornar resposta HTTP padronizada

**Localização:** `app/Controllers/Api/`

**Exemplo:**
```php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\ProdutoService;
use CodeIgniter\HTTP\ResponseInterface;

class ProdutoController extends BaseController
{
    private ProdutoService $produtoService;

    public function __construct()
    {
        helper('response_helper');
        $this->produtoService = service('produtoService');
    }

    public function index(): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->produtoService->listar()
        );
    }

    public function show($id): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->produtoService->obterPorId($id)
        );
    }

    public function store(): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->produtoService->criar($this->request)
        );
    }

    public function update($id): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->produtoService->atualizar($id, $this->request)
        );
    }

    public function delete($id): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->produtoService->deletar($id)
        );
    }
}
```

**Pontos-chave:**
- Sempre usar `helper('response_helper')` no `__construct`.
- Chamar services via `api_service_response()` para centralizar tratamento de erros.
- Não fazer lógica de negócio no controller.

---

### 2. Service (Lógica de Negócio)

**Responsabilidades:**
- Validar entrada (lançar `InvalidArgumentException` para erros 400)
- Aplicar regras de negócio
- Coordenar operações (Model/Repository)
- Lançar exceções tipadas em erros previsíveis
- Retornar dados estruturados (`['message' => '...', 'data' => ...]`)

**Localização:** `app/Services/`

**Exceções Tipadas:**
- `InvalidArgumentException` → HTTP 400 (entrada inválida)
- `DomainException` → HTTP 401 (não autorizado, credenciais inválidas)
- `OutOfBoundsException` → HTTP 404 (recurso não encontrado)
- `Throwable` genérico → HTTP 500 (erro interno)

**Exemplo:**
```php
namespace App\Services;

use App\Models\ProdutoModel;
use App\Repositories\ProdutoRepository;
use CodeIgniter\HTTP\IncomingRequest;

class ProdutoService
{
    private ProdutoModel $produtoModel;
    private ProdutoRepository $produtoRepository;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->produtoRepository = new ProdutoRepository();
    }

    public function listar(): array
    {
        // Consulta simples → use Model direto
        return [
            'message' => 'Produtos listados com sucesso.',
            'data' => $this->produtoModel->findAll(),
        ];
    }

    public function obterPorId(int $id): array
    {
        $produto = $this->produtoModel->find($id);
        
        if (!$produto) {
            throw new \OutOfBoundsException("Produto não encontrado.");
        }

        return [
            'message' => 'Produto obtido com sucesso.',
            'data' => $produto,
        ];
    }

    public function criar(IncomingRequest $request): array
    {
        $data = $this->extrairDados($request);
        
        // Validação
        if (empty($data['nome'])) {
            throw new \InvalidArgumentException('Nome do produto é obrigatório.');
        }
        if ($data['preco'] < 0) {
            throw new \InvalidArgumentException('Preço não pode ser negativo.');
        }

        // Criar usando Model
        $id = $this->produtoModel->insert($data);

        return [
            'message' => 'Produto criado com sucesso.',
            'data' => ['id' => $id],
        ];
    }

    public function atualizar(int $id, IncomingRequest $request): array
    {
        $produto = $this->produtoModel->find($id);
        
        if (!$produto) {
            throw new \OutOfBoundsException("Produto não encontrado.");
        }

        $data = $this->extrairDados($request);

        $this->produtoModel->update($id, $data);

        return [
            'message' => 'Produto atualizado com sucesso.',
        ];
    }

    public function deletar(int $id): array
    {
        $produto = $this->produtoModel->find($id);
        
        if (!$produto) {
            throw new \OutOfBoundsException("Produto não encontrado.");
        }

        $this->produtoModel->delete($id);

        return [
            'message' => 'Produto deletado com sucesso.',
        ];
    }

    // Consulta complexa → use Repository
    public function listarComFiltros(array $filtros): array
    {
        $produtos = $this->produtoRepository->buscarComFiltros($filtros);

        return [
            'message' => 'Produtos filtrados com sucesso.',
            'data' => $produtos,
        ];
    }

    private function extrairDados(IncomingRequest $request): array
    {
        $data = $request->getJSON(true) ?: $request->getPost();

        return [
            'nome' => $data['nome'] ?? null,
            'preco' => $data['preco'] ?? null,
            'descricao' => $data['descricao'] ?? null,
        ];
    }
}
```

**Pontos-chave:**
- Services não retornam response HTTP; apenas dados.
- Sempre estruturar retorno com `['message' => '...', 'data' => ...]` (data é opcional se não houver dados dinâmicos).
- Lançar exceções tipadas para cenários previstos (400, 401, 404).
- Coordenar entre Model (CRUD simples) e Repository (SQL complexo).

---

### 3. Model (CRUD Simples)

**Responsabilidades:**
- Mapear tabela do banco
- Fornecer métodos CRUD básicos (find, findAll, insert, update, delete)
- Validação de campos (optional)

**Localização:** `app/Models/`

**Quando usar:**
- Queries simples: `SELECT * FROM tabela`, `SELECT * WHERE id = ?`
- Insert, Update, Delete padrão
- Operações sem joins complexos ou agregações

**Exemplo:**
```php
namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'produtos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nome',
        'preco',
        'descricao',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
```

**Métodos disponíveis (CodeIgniter):**
- `find($id)` — buscar por ID
- `findAll()` — retornar todos
- `insert($data)` — criar novo registro
- `update($id, $data)` — atualizar registro
- `delete($id)` — deletar registro
- `where()`, `orderBy()`, `limit()` — construir queries customizadas

---

### 4. Repository (SQL Complexo)

**Responsabilidades:**
- Encapsular queries SQL complexas
- Joins entre tabelas
- Agregações (COUNT, SUM, etc.)
- Filtros avançados

**Localização:** `app/Repositories/`

**Quando usar:**
- Relatórios com múltiplos joins
- Consultas com lógica condicional complexa
- Queries com subconsultas
- Filtros avançados (intervalo de datas, range de preço, etc.)

**Exemplo:**
```php
namespace App\Repositories;

use App\Models\ProdutoModel;
use App\Models\CategoriaModel;

class ProdutoRepository
{
    private ProdutoModel $produtoModel;
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Buscar produtos com filtros avançados
     */
    public function buscarComFiltros(array $filtros): array
    {
        $query = $this->produtoModel
            ->select('p.id, p.nome, p.preco, c.nome as categoria')
            ->join('categorias c', 'p.categoria_id = c.id', 'left')
            ->orderBy('p.nome', 'ASC');

        // Filtros opcionais
        if (!empty($filtros['categoria_id'])) {
            $query->where('p.categoria_id', $filtros['categoria_id']);
        }

        if (!empty($filtros['preco_min'])) {
            $query->where('p.preco >=', $filtros['preco_min']);
        }

        if (!empty($filtros['preco_max'])) {
            $query->where('p.preco <=', $filtros['preco_max']);
        }

        if (!empty($filtros['termo'])) {
            $query->like('p.nome', $filtros['termo']);
        }

        return $query->findAll();
    }

    /**
     * Relatório: produtos por categoria
     */
    public function relatoriosPorCategoria(): array
    {
        return $this->produtoModel
            ->select('c.nome, COUNT(p.id) as total, AVG(p.preco) as preco_medio')
            ->join('categorias c', 'p.categoria_id = c.id')
            ->groupBy('c.id')
            ->orderBy('total', 'DESC')
            ->findAll();
    }
}
```

---

## Fluxo Padrão de Resposta HTTP

**Helper:** `app/Helpers/response_helper.php`

Todas as respostas seguem este padrão:

```json
{
  "status": "success",
  "message": "Descrição da operação",
  "data": {...}  // opcional, presente apenas se houver dados
}
```

Ou em erro:

```json
{
  "status": "error",
  "message": "Descrição do erro"
}
```

**Mapeamento de Exceções → HTTP Status:**

| Exceção | Código HTTP | Caso de Uso |
|---------|-----------|-----------|
| `InvalidArgumentException` | 400 | Email vazio, formato inválido, dados obrigatórios faltando |
| `DomainException` | 401 | Credenciais inválidas, usuário não autorizado |
| `OutOfBoundsException` | 404 | Recurso não encontrado (usuário, produto, etc.) |
| Outras `Throwable` | 500 | Erro interno não tratado |

---

## Roteamento

**Localização:** `app/Config/Routes.php`

**Padrão RESTful:**

```php
$routes->group('api', static function ($routes) {
    // Autenticação
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('logout', 'Api\AuthController::logout', ['filter' => 'api-auth']);

    // Recursos (com autenticação)
    $routes->get('usuarios', 'Api\UsuarioController::index', ['filter' => 'api-auth']);
    $routes->get('usuarios/(:num)', 'Api\UsuarioController::show/$1', ['filter' => 'api-auth']);
    $routes->post('usuarios', 'Api\UsuarioController::store', ['filter' => 'api-auth']);
    $routes->put('usuarios/(:num)', 'Api\UsuarioController::update/$1', ['filter' => 'api-auth']);
    $routes->delete('usuarios/(:num)', 'Api\UsuarioController::delete/$1', ['filter' => 'api-auth']);

    // Produtos
    $routes->get('produtos', 'Api\ProdutoController::index', ['filter' => 'api-auth']);
    $routes->get('produtos/(:num)', 'Api\ProdutoController::show/$1', ['filter' => 'api-auth']);
    $routes->post('produtos', 'Api\ProdutoController::store', ['filter' => 'api-auth']);
    $routes->put('produtos/(:num)', 'Api\ProdutoController::update/$1', ['filter' => 'api-auth']);
    $routes->delete('produtos/(:num)', 'Api\ProdutoController::delete/$1', ['filter' => 'api-auth']);
});
```

---

## Filtros (Middleware)

**Localização:** `app/Filters/ApiAuthFilter.php`

O filtro valida autenticação em endpoints protegidos:

```php
public function before(RequestInterface $request, $arguments = null)
{
    $cookieName = config('Session')->cookieName;
    $sessionCookie = $_COOKIE[$cookieName] ?? null;
    
    if (!$sessionCookie) {
        helper('response_helper');
        $response = service('response');
        return api_service_response($response, function () {
            throw new \DomainException('Acesso negado. Faça login primeiro.');
        });
    }
    
    if (!session()->get('logged_in')) {
        helper('response_helper');
        $response = service('response');
        return api_service_response($response, function () {
            throw new \DomainException('Acesso negado. Faça login primeiro.');
        });
    }
}
```

---

## Checklist para Novos Endpoints

### 1. Criar Model (se não existir)
- [ ] Estender `CodeIgniter\Model`
- [ ] Definir `$table`, `$primaryKey`, `$allowedFields`
- [ ] Adicionar timestamps (`$useTimestamps = true`, se necessário)

### 2. Criar Repository (se houver SQL complexo)
- [ ] Extends com Model
- [ ] Métodos para queries com joins, agregações, filtros

### 3. Criar Service
- [ ] Injetar Model (ou Repository se SQL complexo)
- [ ] Validar entrada (lançar `InvalidArgumentException`)
- [ ] Coordenar operações
- [ ] Retornar `['message' => '...', 'data' => ...]`
- [ ] Lançar exceções tipadas (`DomainException`, `OutOfBoundsException`)

### 4. Criar Controller
- [ ] Estender `BaseController`
- [ ] Chamar `helper('response_helper')` no `__construct`
- [ ] Injetar Service
- [ ] Usar `api_service_response()` em cada método

### 5. Adicionar Roteamento
- [ ] Em `app/Config/Routes.php`
- [ ] Respeitar padrão RESTful
- [ ] Aplicar filtro `api-auth` se necessário

### 6. Testar
- [ ] Requisições bem-sucedidas (200)
- [ ] Entrada inválida (400)
- [ ] Não autorizado (401)
- [ ] Não encontrado (404)
- [ ] Erro interno (500)

---

## Exemplo Completo: CRUD de Categorias

### Model: `app/Models/CategoriaModel.php`
```php
namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nome', 'descricao'];
    protected $useTimestamps = true;
}
```

### Service: `app/Services/CategoriaService.php`
```php
namespace App\Services;

use App\Models\CategoriaModel;
use CodeIgniter\HTTP\IncomingRequest;

class CategoriaService
{
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function listar(): array
    {
        return [
            'message' => 'Categorias listadas com sucesso.',
            'data' => $this->categoriaModel->findAll(),
        ];
    }

    public function obterPorId(int $id): array
    {
        $categoria = $this->categoriaModel->find($id);
        
        if (!$categoria) {
            throw new \OutOfBoundsException('Categoria não encontrada.');
        }

        return [
            'message' => 'Categoria obtida com sucesso.',
            'data' => $categoria,
        ];
    }

    public function criar(IncomingRequest $request): array
    {
        $nome = trim($request->getVar('nome') ?? '');
        $descricao = trim($request->getVar('descricao') ?? '');

        if (empty($nome)) {
            throw new \InvalidArgumentException('Nome da categoria é obrigatório.');
        }

        $id = $this->categoriaModel->insert([
            'nome' => $nome,
            'descricao' => $descricao,
        ]);

        return [
            'message' => 'Categoria criada com sucesso.',
            'data' => ['id' => $id],
        ];
    }

    public function atualizar(int $id, IncomingRequest $request): array
    {
        if (!$this->categoriaModel->find($id)) {
            throw new \OutOfBoundsException('Categoria não encontrada.');
        }

        $this->categoriaModel->update($id, [
            'nome' => $request->getVar('nome'),
            'descricao' => $request->getVar('descricao'),
        ]);

        return ['message' => 'Categoria atualizada com sucesso.'];
    }

    public function deletar(int $id): array
    {
        if (!$this->categoriaModel->find($id)) {
            throw new \OutOfBoundsException('Categoria não encontrada.');
        }

        $this->categoriaModel->delete($id);

        return ['message' => 'Categoria deletada com sucesso.'];
    }
}
```

### Controller: `app/Controllers/Api/CategoriaController.php`
```php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\CategoriaService;
use CodeIgniter\HTTP\ResponseInterface;

class CategoriaController extends BaseController
{
    private CategoriaService $categoriaService;

    public function __construct()
    {
        helper('response_helper');
        $this->categoriaService = service('categoriaService');
    }

    public function index(): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->categoriaService->listar()
        );
    }

    public function show($id): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->categoriaService->obterPorId((int)$id)
        );
    }

    public function store(): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->categoriaService->criar($this->request)
        );
    }

    public function update($id): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->categoriaService->atualizar((int)$id, $this->request)
        );
    }

    public function delete($id): ResponseInterface
    {
        return api_service_response($this->response, 
            fn () => $this->categoriaService->deletar((int)$id)
        );
    }
}
```

### Roteamento: `app/Config/Routes.php`
```php
$routes->group('api', static function ($routes) {
    $routes->get('categorias', 'Api\CategoriaController::index', ['filter' => 'api-auth']);
    $routes->get('categorias/(:num)', 'Api\CategoriaController::show/$1', ['filter' => 'api-auth']);
    $routes->post('categorias', 'Api\CategoriaController::store', ['filter' => 'api-auth']);
    $routes->put('categorias/(:num)', 'Api\CategoriaController::update/$1', ['filter' => 'api-auth']);
    $routes->delete('categorias/(:num)', 'Api\CategoriaController::delete/$1', ['filter' => 'api-auth']);
});
```

---

## Dicas Finais

1. **Separação de responsabilidades**: Controller → orquestra, Service → valida e lógica, Model/Repository → dados.
2. **Exceções tipadas**: Use `InvalidArgumentException`, `DomainException`, `OutOfBoundsException` para fluxos previstos.
3. **Model vs Repository**: Model para CRUD simples; Repository para SQL complexo (joins, agregações, filtros avançados).
4. **Consistência**: Todas as respostas seguem `{ status, message, data? }`.
5. **Reusabilidade**: Services podem ser chamados por múltiplos controllers ou jobs assíncronos.

