# Mimo Site - Roadmap de Melhorias

**Última Atualização**: 2025-01-21  
**Versão Atual**: 2.3.4

Este documento apresenta um roadmap completo de melhorias, otimizações e novas funcionalidades para o site Mimo, baseado em melhores práticas modernas de desenvolvimento web.

---

## 🎯 Status Atual

### Versão 2.3.4 (2025-01-21) - Correções de Layout e Performance
- ✅ Correção de CSP para Font Awesome (cdnjs.cloudflare.com)
- ✅ Remoção de script Tidio quebrado (404 errors)
- ✅ Correção de carregamento de fonte Akrobat
- ✅ Correção de links HTTP para HTTPS na FAQ
- ✅ Correção de erro bcSwipe não encontrado
- ✅ Correção de caminho do manifest.json
- ✅ Correção de layout do header (dark mode toggle)
- ✅ Correção de logo distorcido no navbar
- ✅ Correção de seção hero para ocupar largura total
- ✅ Correção de imagem da página principal (mimo5.png) para ocupar espaço completo

### Versão 2.3.3 (2025-01-21) - Dark Mode Profissional
- ✅ Dark Mode implementado seguindo boas práticas
- ✅ Sistema de variáveis CSS para temas (mantém identidade da marca)
- ✅ Cores da marca preservadas (#ccb7bc rosa, #3a505a cinza)
- ✅ Light mode: off-white (#fafafa) em vez de branco puro
- ✅ Dark mode: Material Design (#121212) em vez de preto puro
- ✅ Toggle button no navbar com persistência localStorage
- ✅ Detecção automática de prefers-color-scheme
- ✅ Transições suaves entre temas
- ✅ Contraste adequado (WCAG AA) em ambos os modos
- ✅ Remoção completa de sistema de agendamento (agendamento.salaovip.com.br)

### Versão 2.2.7 (2025-01-20)
- ✅ Carousel de reviews otimizado (altura reduzida, layout shift fixado)
- ✅ Sistema híbrido de Google Reviews (API + manual)
- ✅ Branding consistente ("Mimo" em todo o site)
- ✅ Página 404 melhorada com cards de serviços
- ✅ Blog post template criado

### Performance Atual
- Lighthouse Score: ~75 (meta: 90+)
- First Contentful Paint: ~2.5s (meta: <1.5s)
- Largest Contentful Paint: ~3.5s (meta: <2.5s)
- Cumulative Layout Shift: <0.1 ✅

---

## 🚀 Fase 1: Performance & Core Web Vitals (Alta Prioridade)

### 1.1 Otimização de Imagens Avançada
**Impacto**: Alto - Redução de 40-60% no tamanho de imagens  
**Estimativa**: 2-3 dias

- [ ] Implementar `srcset` com múltiplos tamanhos (1x, 2x, 3x)
- [ ] Adicionar suporte AVIF (30% menor que WebP)
- [ ] Lazy loading nativo com `loading="lazy"`
- [ ] Compressão automática no build pipeline
- [ ] Gerar thumbnails automáticos para galerias

**Tecnologias**: Sharp, ImageMagick, ou similar

### 1.2 Critical CSS Expandido
**Impacto**: Alto - FCP 30-50% mais rápido  
**Estimativa**: 1-2 dias

- [ ] Extrair CSS crítico para hero section e above-the-fold
- [ ] Inline critical CSS, defer rest
- [ ] Automatizar extração com ferramentas (critical, purgecss)
- [ ] Reduzir CSS não utilizado

**Ferramentas**: critical, purgecss, uncss

### 1.3 Service Worker & PWA
**Impacto**: Alto - Experiência mobile premium, offline support  
**Estimativa**: 3-4 dias

- [ ] Service worker para cache offline
- [ ] Web App Manifest completo
- [ ] "Add to Home Screen" funcional
- [ ] Offline fallback page
- [ ] Background sync para formulários

**Benefícios**: App-like experience, melhor engajamento mobile

### 1.4 Lazy Loading Nativo
**Impacto**: Alto - Redução de carga inicial  
**Estimativa**: 1 dia

- [ ] Implementar `loading="lazy"` em todas as imagens abaixo do fold
- [ ] Lazy loading para iframes (Google Maps, etc)
- [ ] Intersection Observer para conteúdo dinâmico
- [ ] Placeholder blur-up para melhor UX

**ROI**: ⭐⭐⭐⭐

---

## 🎨 Fase 2: UX & Design Moderno (Média Prioridade)

### 2.1 Dark Mode ✅ CONCLUÍDO
**Impacto**: Médio - Modernização, preferência usuário  
**Status**: Implementado em v2.3.3

- [x] CSS Variables para tema (sistema completo em `_variables.css`)
- [x] Toggle no header (botão com ícone lua/sol)
- [x] Persistência com localStorage
- [x] Prefers-color-scheme detection
- [x] Transições suaves
- [x] Cores da marca preservadas em ambos os modos
- [x] Boas práticas aplicadas (Material Design #121212, off-white #fafafa)

**ROI**: ⭐⭐⭐

### 2.2 Animações & Micro-interações
**Impacto**: Médio - Engajamento, percepção de qualidade  
**Estimativa**: 2-3 dias

- [ ] Scroll-triggered animations (AOS ou GSAP)
- [ ] Hover effects aprimorados em cards
- [ ] Loading skeletons
- [ ] Smooth scroll behavior
- [ ] Parallax sutil no hero

**Bibliotecas**: AOS (Animate On Scroll), GSAP, ou CSS puro

### 2.3 Acessibilidade (WCAG AA)
**Impacto**: Médio - Compliance, alcance ampliado  
**Estimativa**: 3-4 dias

- [ ] ARIA labels completos
- [ ] Navegação por teclado
- [ ] Contraste de cores (WCAG AA)
- [ ] Screen reader optimization
- [ ] Skip to content link

**Ferramentas**: axe DevTools, WAVE, Lighthouse Accessibility

### 2.4 Formulário Multi-step
**Impacto**: Médio - Melhor conversão  
**Estimativa**: 2-3 dias

- [ ] Dividir em 2-3 steps
- [ ] Progress indicator
- [ ] Validação em tempo real
- [ ] Save draft (localStorage)
- [ ] Analytics de abandono

**ROI**: ⭐⭐⭐

---

## 📱 Fase 3: Features & Funcionalidades (Média Prioridade)

### 3.1 Página Dedicada de Contato
**Impacto**: Alto - Acessibilidade muito melhorada, melhor SEO local  
**Estimativa**: 2-3 dias

- [ ] Criar `contato.php` como página dedicada
- [ ] Mapa do Google Maps integrado
- [ ] Todas as informações de contato (endereço, telefone, horário)
- [ ] Formulário de contato
- [ ] Links para redes sociais
- [ ] Botões de ação (WhatsApp, ligar, rotas)
- [ ] Adicionar link "CONTATO" no menu principal
- [ ] Design moderno e responsivo
- [ ] Schema.org LocalBusiness com coordenadas
- [ ] Adicionar informações de contato no footer (sempre visível)
- [ ] Horário de funcionamento com indicador visual (aberto/fechado)

**ROI**: ⭐⭐⭐⭐⭐

### 3.2 Instagram Feed Integrado
**Impacto**: Médio - Social proof, conteúdo dinâmico  
**Estimativa**: 2-3 dias

- [ ] API do Instagram (ou scraper alternativo)
- [ ] Grid de posts recentes
- [ ] Before/After gallery
- [ ] Auto-refresh cache
- [ ] Fallback se API falhar

**ROI**: ⭐⭐⭐

### 3.3 Sistema de Busca
**Impacto**: Médio - Melhor navegação  
**Estimativa**: 2-3 dias

- [ ] Search bar no header
- [ ] Busca por serviços
- [ ] Filtros por categoria/preço
- [ ] Resultados em tempo real
- [ ] Highlight de termos

**ROI**: ⭐⭐⭐

### 3.4 ~~Booking Widget Integrado~~ (Removido)
**Status**: Sistema de agendamento via website removido. Agendamentos são feitos via WhatsApp.

---

## 🔧 Fase 4: Technical Debt & Modernização (Baixa-Média Prioridade)

### 4.1 Modernizar JavaScript
**Impacto**: Baixo-Médio - Manutenibilidade  
**Estimativa**: 3-4 dias

- [ ] ES6+ syntax
- [ ] Modules (import/export)
- [ ] Async/await
- [ ] Optional: TypeScript ou Alpine.js
- [ ] Build tool moderno (Vite, Webpack)

**ROI**: ⭐⭐

### 4.2 CSS Moderno
**Impacto**: Baixo-Médio - Manutenibilidade  
**Estimativa**: 2-3 dias

- [ ] CSS Variables para tema
- [ ] CSS Grid onde faz sentido
- [ ] Remover código legacy
- [ ] Organizar em módulos
- [ ] PostCSS com autoprefixer

**ROI**: ⭐⭐

### 4.3 Testing & QA
**Impacto**: Médio - Qualidade garantida  
**Estimativa**: 5-7 dias

- [ ] PHPUnit para backend
- [ ] Browser testing (Playwright)
- [ ] Visual regression
- [ ] Performance budgets
- [ ] Lighthouse CI

**ROI**: ⭐⭐⭐

### 4.4 Monitoring & Analytics
**Impacto**: Médio - Insights valiosos  
**Estimativa**: 2-3 dias

- [ ] Real User Monitoring (RUM)
- [ ] Core Web Vitals tracking
- [ ] Error tracking (Sentry)
- [ ] Custom events GA4
- [ ] Heatmaps (Hotjar)

**ROI**: ⭐⭐⭐

---

## 💡 Fase 5: Inovações (Longo Prazo)

### 5.1 AI Chatbot
**Impacto**: Alto - Atendimento 24/7  
**Estimativa**: 5-7 dias

- [ ] Integração com ChatGPT API ou similar
- [ ] Treinado com FAQs da Mimo
- [ ] Agendamento via chat
- [ ] Fallback para WhatsApp
- [ ] Analytics de conversas

**ROI**: ⭐⭐⭐⭐

### 5.2 CMS Simples
**Impacto**: Alto - Autonomia operacional  
**Estimativa**: 7-10 dias

- [ ] Admin panel básico
- [ ] Edição de serviços sem código
- [ ] Upload de imagens
- [ ] Gerenciamento de vagas
- [ ] Preview antes de publicar

**ROI**: ⭐⭐⭐⭐

### 5.3 Database Migration
**Impacto**: Alto - Escalabilidade  
**Estimativa**: 10-14 dias

- [ ] Migrar dados para DB
- [ ] ORM (Doctrine/Eloquent)
- [ ] Migrations system
- [ ] Backup automático
- [ ] Query optimization

**ROI**: ⭐⭐⭐⭐

---

## 📊 Priorização Recomendada

### Sprint 1 (Próximas 2 semanas) 🎯
1. Otimização de imagens (srcset, AVIF)
2. Critical CSS expandido
3. Lazy loading nativo
4. Service Worker & PWA básico

**Impacto esperado**: +30% performance, melhor mobile

### Sprint 2 (2-4 semanas) 🎯
1. Service Worker & PWA
2. Instagram Feed
3. Animações básicas
4. ✅ Dark Mode (CONCLUÍDO v2.3.3)
5. Formulário multi-step

**Impacto esperado**: +20% conversão, melhor mobile

### Sprint 3 (1-2 meses) 🎯
1. Acessibilidade completa (WCAG AA)
2. Sistema de busca
3. Monitoring & Analytics
4. Testing básico

**Impacto esperado**: Compliance, autonomia, insights

### Longo Prazo (3-6 meses) 🎯
1. CMS simples
2. AI Chatbot
3. Modernização completa de JS/CSS
4. Database migration

**Impacto esperado**: Escalabilidade, autonomia total

---

## 🎯 Performance Budgets

### Metas de Performance
- **Lighthouse Score**: 90+ (atual: ~75)
- **First Contentful Paint**: <1.5s (atual: ~2.5s)
- **Largest Contentful Paint**: <2.5s (atual: ~3.5s)
- **Time to Interactive**: <3.5s (atual: ~4.5s)
- **Cumulative Layout Shift**: <0.1 ✅ (já alcançado)

### Metas de Negócio
- **Form conversion rate**: +20%
- **WhatsApp conversion**: +15%
- **Bounce rate**: -10%
- **Time on site**: +30%
- **Pages per session**: +25%

---

## 🛠️ Ferramentas Recomendadas

### Performance
- **WebPageTest** - Análise detalhada
- **Lighthouse CI** - Monitoramento contínuo
- **Bundle Analyzer** - Análise de tamanho de assets

### Design
- **Figma** - Design system
- **Storybook** - Component library (se migrar para componentes)

### Development
- **Vite** - Build tool moderno (se migrar JS)
- **Prettier** - Code formatting
- **ESLint** - Code quality
- **PHPStan** - PHP static analysis

### Analytics
- **Google Analytics 4** - Já configurado
- **Hotjar** - Heatmaps, recordings
- **Sentry** - Error tracking

---

## 📝 Notas Importantes

- **Versionamento**: Semantic Versioning rigorosamente seguido
- **Documentação**: Sempre atualizada junto com código
- **Testing**: Implementar antes de features grandes
- **Performance**: Sempre medir antes/depois
- **Acessibilidade**: Prioridade desde o início
- **Compatibilidade**: Manter PHP 7.1+ (produção)

---

## 🔄 Processo de Atualização

Este roadmap deve ser revisado:
- Após cada sprint
- Quando novas tecnologias relevantes surgirem
- Quando métricas de negócio indicarem necessidade
- Quando feedback de usuários sugerir melhorias

**Próxima revisão**: Após Sprint 1 (2 semanas)

---

## 📚 Referências

- [Web.dev Performance](https://web.dev/performance/)
- [Core Web Vitals](https://web.dev/vitals/)
- [WCAG Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [Google Search Central](https://developers.google.com/search)

