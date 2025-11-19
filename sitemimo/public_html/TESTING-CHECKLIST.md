# Checklist de Testes - Performance 90+

Este checklist deve ser executado após cada fase de otimização para garantir que nada quebrou.

## Testes Funcionais Básicos

### Menu Mobile
- [ ] Menu hamburger abre ao clicar
- [ ] Menu fecha ao clicar novamente
- [ ] Menu fecha ao clicar fora
- [ ] Links do menu funcionam corretamente
- [ ] Menu não sobrepõe conteúdo
- [ ] Menu é acessível (teclado, screen reader)

### Dark Mode Toggle
- [ ] Toggle aparece corretamente (mobile e desktop)
- [ ] Toggle alterna entre light/dark mode
- [ ] Preferência é salva (localStorage)
- [ ] Preferência é respeitada em recarregamento
- [ ] Toggle tem contraste adequado em ambos os modos
- [ ] Toggle não causa layout shift ao aparecer

### Carousel de Testimonials
- [ ] Carousel funciona no mobile (mesmo com animações desabilitadas)
- [ ] Carousel funciona no desktop
- [ ] Navegação (setas, dots) funciona
- [ ] Swipe funciona no mobile
- [ ] Carousel não quebra layout

### Formulários
- [ ] Formulário de contato funciona
- [ ] Validação funciona
- [ ] Mensagens de erro aparecem corretamente
- [ ] Submit funciona
- [ ] Campos são acessíveis (labels, ARIA)

### Botões e Links
- [ ] Todos os botões são clicáveis
- [ ] Todos os links funcionam
- [ ] Botões têm tamanho adequado para touch (mobile)
- [ ] Hover states funcionam (desktop)
- [ ] Focus states são visíveis (acessibilidade)

## Testes Visuais

### Layout Desktop
- [ ] Layout não quebrou
- [ ] Elementos estão centralizados/alinhados
- [ ] Espaçamentos estão corretos
- [ ] Imagens aparecem corretamente
- [ ] Texto está legível
- [ ] Não há sobreposição de elementos

### Layout Mobile
- [ ] Layout responsivo funciona
- [ ] Elementos não estão sobrepostos
- [ ] Texto está legível (tamanho adequado)
- [ ] Imagens são responsivas
- [ ] Menu mobile funciona
- [ ] Dark mode toggle está em lugar adequado

### Cores e Contraste
- [ ] Cores da marca estão sendo usadas
- [ ] Light mode está agradável aos olhos
- [ ] Dark mode está agradável aos olhos
- [ ] Contraste está adequado (WCAG AA+)
- [ ] Links são distinguíveis do texto
- [ ] Botões têm contraste adequado

### Ícones
- [ ] Ícones aparecem corretamente
- [ ] Ícones são visíveis em light/dark mode
- [ ] Ícones têm contraste adequado
- [ ] Ícones são clicáveis (se aplicável)
- [ ] Ícones não causam layout shift

## Testes de Performance

### Console do Navegador
- [ ] Sem erros críticos (vermelho)
- [ ] Avisos são aceitáveis (amarelo)
- [ ] Sem erros de JavaScript
- [ ] Sem erros de CSS
- [ ] Sem erros de recursos não encontrados

### Network Tab
- [ ] Recursos críticos carregam primeiro
- [ ] Imagens LCP não têm lazy loading
- [ ] Imagens abaixo da dobra têm lazy loading
- [ ] CSS crítico está inline
- [ ] CSS não crítico está defer

### Lighthouse/PageSpeed
- [ ] Performance score melhorou ou manteve
- [ ] FCP melhorou ou manteve
- [ ] LCP melhorou ou manteve
- [ ] CLS melhorou ou manteve
- [ ] TBT melhorou ou manteve

## Testes de Acessibilidade

### ARIA Attributes
- [ ] Elementos interativos têm roles adequados
- [ ] Elementos têm labels adequados
- [ ] Navegação por teclado funciona
- [ ] Screen readers podem navegar

### Contraste
- [ ] Texto tem contraste WCAG AA+ (4.5:1)
- [ ] Texto grande tem contraste WCAG AA+ (3:1)
- [ ] Botões têm contraste adequado
- [ ] Links têm contraste adequado

## Testes Específicos por Fase

### Após Fase 1 (Migração Lucide)
- [ ] Todos os ícones aparecem
- [ ] Ícones têm estilo correto
- [ ] Font Awesome CSS foi removido
- [ ] Lucide está carregando corretamente

### Após Fase 2 (Otimização de Imagens)
- [ ] Imagens aparecem corretamente
- [ ] Imagens estão em AVIF/WebP (onde aplicável)
- [ ] Imagens não quebraram layout
- [ ] Lazy loading funciona

### Após Fase 3 (Reduzir CLS)
- [ ] Não há layout shift visível
- [ ] Imagens têm width/height
- [ ] Fontes carregam sem FOIT/FOUT
- [ ] Conteúdo dinâmico não causa shift

### Após Fase 4 (Otimizar FCP)
- [ ] Primeira renderização é rápida
- [ ] CSS crítico está inline
- [ ] Fontes críticas carregam rápido

### Após Fase 5 (Otimizar LCP)
- [ ] Imagem LCP carrega rápido
- [ ] Preload está funcionando
- [ ] LCP está abaixo de 2.5s

### Após Fase 6 (Reduzir Network Payload)
- [ ] Unused CSS foi removido
- [ ] Unused JavaScript foi removido
- [ ] Arquivos estão minificados
- [ ] Network payload está reduzido

### Após Fase 7 (Otimizações Avançadas)
- [ ] Cache headers estão configurados
- [ ] Font-display está aplicado
- [ ] Forced reflows foram corrigidos

## Como Executar os Testes

1. **Testes Locais**:
   ```bash
   cd sitemimo/public_html
   php -S localhost:8000
   ```
   - Abrir http://localhost:8000 no navegador
   - Testar todas as funcionalidades manualmente
   - Verificar console do navegador (F12)

2. **Testes PageSpeed Insights**:
   ```bash
   ./build/pagespeed-test-all.sh [API_KEY]
   ```
   - Comparar resultados com baseline
   - Verificar se scores melhoraram

3. **Testes de Acessibilidade**:
   - Usar Lighthouse (Chrome DevTools)
   - Verificar contraste com ferramentas online
   - Testar navegação por teclado

## Critérios de Sucesso

- ✅ Todas as funcionalidades funcionando
- ✅ Layout não quebrou
- ✅ Cores e contrastes corretos
- ✅ Performance melhorou ou manteve
- ✅ Sem erros críticos no console
- ✅ Acessibilidade mantida ou melhorada

