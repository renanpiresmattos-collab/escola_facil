# UI UX

## Objetivo
Usar esta skill para revisar a experiência das telas administrativas com foco em clareza, hierarquia, feedback, consistência visual, sidebar, formulários e listagens paginadas.

## Onde olhar primeiro
1. Login
2. Dashboard
3. Sidebar
4. RUP listagem
5. RUP formulário
6. Estados de erro, sucesso e carregamento
7. Espaçamento e hierarquia de conteúdo

## Critérios de avaliação
- A ação principal precisa estar evidente.
- O usuário precisa entender onde está e o que pode fazer a seguir.
- A interface precisa funcionar bem em tela pequena sem esconder informação essencial.
- O texto deve ser direto e orientar a próxima ação.
- Estados de feedback devem ser visíveis, curtos e consistentes.
- Em tabelas e listas, a paginação precisa ser clara e previsível.

## Fluxos reais deste repositório
- Entrar com email e senha.
- Navegar pela dashboard após autenticação.
- Abrir e fechar a sidebar no mobile.
- Ler mensagens de erro/sucesso nas ações da API.
- Pesquisar registros da RUP por CPF ou nome.
- Visualizar paginação, trocar de página e manter o estado do filtro.
- Incluir e editar RUP sem misturar lógica de dados no controller web.

## Como trabalhar
- Começar pela hierarquia: título, navegação, conteúdo e ações secundárias.
- Remover ruído visual antes de adicionar novos elementos.
- Verificar contraste, espaçamento, alvos de toque e estados ativos.
- Preservar o padrão já usado nas telas existentes quando ele fizer sentido.
- Priorizar consistência entre o que a API devolve e o que a tela mostra.

## Checklist
- A tela responde bem em mobile.
- O formulário tem labels claros e feedback visível.
- A sidebar deixa claro o item ativo.
- A listagem mostra paginação e resumo.
- Não há dependência visual que esconda informação importante.
