## Resumo

Este documento descreve, em termos de UI/UX (sem código), as características visuais e comportamentais que devem ser mantidas nas próximas páginas do produto, tomando como referência a tela de login atual. O objetivo é garantir consistência em comportamento, paleta de cores, tipografia, espaçamento, acessibilidade e micro-interações.

## Comportamento (Interações e Fluxo)

- Entrada do usuário: campos com rótulos claros e placeholders informativos; foco visual destacado (borda ou glow sutil) ao focar o campo.
- Validação: validação em tempo real mínima (ex.: formato de email) e validação completa ao submeter; mensagens de erro concisas e específicas abaixo do campo afetado.
- Feedback: mostrar estados de carregamento no botão principal (spinner discreto + texto), e desabilitar inputs enquanto a requisição está em curso.
- Recuperação de erro: mensagens globais (alerta) aparecem no topo do card do formulário; oferecer instruções claras sobre como proceder.
- Acessibilidade do teclado: navegar por `Tab` entre campos e botões, `Enter` envia o formulário quando o foco está nos campos.
- Foco persistente: ao retornar à página, não preencher automaticamente campos sensíveis; manter comportamento consistente de foco no primeiro campo.

## Paleta de Cores (Padrão)

- Primária: cor do botão de ação principal (ex.: azul forte) — usar para CTA principal.
- Secundária: para botões de menor prioridade e links (ex.: cinza-escuro ou tom neutro).
- Fundo: fundo geral claro (ex.: branco ou off-white) com áreas de contraste moderado para cartões.
- Texto: alto contraste para títulos e conteúdo primário; texto secundário em tom neutro (60–80% de opacidade).
- Erro: vermelho para estados de erro (inputs e mensagens), acompanhado de ícone de alerta.
- Sucesso: verde para confirmações e mensagens positivas.
- Sombra/elevação: sombra sutil sob o card do formulário para separação do fundo.

Observação: todas as cores devem atender aos requisitos mínimos de contraste WCAG AA para texto e componentes interativos.

## Tipografia

- Família: usar a mesma família tipográfica do produto (sem serifa ou sans-serif consistente).
- Hierarquia:
	- Título do card/login: 20–24px, peso seminegrito/600.
	- Rótulos de campo: 12–14px, peso normal/400.
	- Texto do botão: 14–16px, peso 600.
	- Mensagens de erro: 12–13px, peso 400, cor de erro.
- Espaçamento entre linhas e legibilidade: 1.2–1.4 line-height.

## Layout e Espaçamento

- Container: card centralizado, largura máxima responsiva (ex.: 360–420px mobile, até 480–540px desktop compacto).
- Margens: espaçamento externo generoso para manter foco no formulário; alinhamento central vertical em telas de login.
- Campos: altura consistente (ex.: 44–48px), espaçamento vertical uniforme entre campos (12–16px).
- Botões: botão principal ocupa 100% da largura do card por padrão; botão secundário/link alinhado abaixo ou ao lado com contraste menor.
- Ícones: usar ícones discretos dentro de inputs apenas quando agregam valor (ex.: visualizar senha).

## Componentes e Estados

- Input: estados — default, foco, preenchido, erro, desabilitado.
- Botão CTA: estados — default, hover (escurecer levemente), ativo (pressionado), desabilitado (baixa opacidade).
- Link: estado hover com sublinhado ou mudança de cor suave.
- Alertas: banner de erro/sucesso no topo do card, com ícone e mensagem curta; possibilidade de fechá-lo.

## Microinterações e Animações

- Transições suaves (150–250ms) para hover e foco em inputs e botões.
- Feedback visual para ações bem-sucedidas/erros (ex.: shake leve no card em erro crítico, aparição/desaparecimento suave de alertas).

## Responsividade

- Mobile: layout em coluna única, botões grandes o suficiente para toque confortável (mín. 44px de altura), campos com espaçamento ampliado.
- Tablet/Desktop: centralizar o card; permitir largura maior, mantendo comfortable reading line-length.
- Comportamento adaptativo: esconder elementos não essenciais em telas pequenas (ex.: textos auxiliares longos).

## Acessibilidade

- Contraste mínimo WCAG AA para textos e elementos interativos.
- Rótulos visíveis e/ou text alternatives (aria-label) para ícones interativos como "mostrar senha".
- Estados de foco visíveis e navegabilidade por teclado completa.
- Mensagens de erro e sucesso anunciáveis por leitores de tela (usar regiões ARIA apropriadas).

## Microcopy e Tom

- Tom: claro, direto e amigável.
- Placeholders: apenas exemplos; não substituir o rótulo.
- Mensagens de erro: explicar o problema e próximo passo possível (ex.: "Senha incorreta. Tente novamente ou recupere sua senha.").

## Itens Persistentes entre Páginas

- Barra de cores, logotipo e estilo do card devem permanecer consistentes.
- Espaçamento, tipografia e comportamento de botões/inputs devem ser reaplicados em páginas subsequentes (cadastro, recuperação de senha, perfil).

## Exemplos de Aplicação (diretrizes rápidas)

- Página de cadastro: mesma largura de card, mesma hierarquia tipográfica, botões primários consistentes com o login.
- Recuperação de senha: manter mesmo padrão de validação, alertas e microinterações.

## Observações Finais

- Priorizar consistência visual e previsibilidade de interação.
- Sempre validar mudanças contra acessibilidade e usabilidade (teste com teclado e leitores de tela).

Se desejar, posso transformar essas diretrizes em checklists ou em tokens de design (cores, espaçamentos e tipografia) para facilitar a implementação nas próximas telas.
