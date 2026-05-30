# Front Arquitetura

## Objetivo
Usar esta skill para ajustar views, CSS e JavaScript do front server-rendered com foco em mobile first, sidebar em drawer, e telas que consomem a API via `fetch`.

## Onde olhar primeiro
1. `app/Views/login.php`
2. `app/Views/dashboard.php`
3. `app/Views/rup/index.php`
4. `app/Views/rup/form.php`
5. `app/Views/components/header.php`
6. `app/Views/components/sidebar.php`
7. `public/assets/css/app.css`
8. `public/assets/css/sidebar.css`
9. `public/assets/js/login.js`
10. `public/assets/js/sidebar.js`
11. `public/assets/js/rup.js`
12. `public/assets/js/rup-form.js`

## Padrões do projeto
- Começar pelo menor viewport.
- Evitar alturas fixas e larguras rígidas.
- Usar navegação em drawer no mobile quando houver sidebar.
- Manter formulários legíveis e com alvos de toque confortáveis.
- Não introduzir dependências visuais externas sem necessidade.
- Telas de dados devem buscar e salvar via `fetch`, não via controller web.

## Fluxos reais deste repositório
- Login em tela única com card centralizado.
- Dashboard com sidebar carregada via API.
- RUP listando registros com filtro e paginação via API.
- RUP incluindo e editando em tela própria, com `fetch` para carregar e salvar.
- Header com botão de menu no mobile.

## Como trabalhar
- Inspecionar overflow, `100vh`, `margin-left` fixo e grids travados primeiro.
- Ajustar espaçamento, empilhamento e visibilidade em breakpoint pequeno.
- Garantir que o JavaScript trate elementos ausentes sem quebrar a página.
- Preservar filtros, paginação e estado de navegação na URL quando fizer sentido.
- Validar máscara de CPF, telefone e CEP tanto na exibição quanto na digitação.

## Checklist
- Verificar comportamento em celular.
- Verificar navegação da sidebar.
- Verificar inputs, botões e mensagens no login.
- Verificar listagem paginada da RUP.
- Verificar formulário de inclusão/edição via API.
