# Próximos Passos - Site Mimo

**Última Atualização**: 2025-01-21  
**Versão Atual**: 2.3.3

---

## ✅ Mudanças Recentes

### v2.3.3 (2025-01-21) - Dark Mode Profissional
- ✅ Dark Mode implementado seguindo boas práticas de design
- ✅ Sistema de variáveis CSS completo (`_variables.css`)
- ✅ Cores da marca preservadas (#ccb7bc rosa, #3a505a cinza)
- ✅ Light mode: off-white (#fafafa) - Material Design
- ✅ Dark mode: #121212 (Material Design) - não preto puro
- ✅ Toggle button no navbar com persistência
- ✅ Detecção automática de prefers-color-scheme
- ✅ Transições suaves entre temas
- ✅ Contraste adequado (WCAG AA) em ambos os modos
- ✅ Remoção completa de sistema de agendamento (agendamento.salaovip.com.br)

### v2.3.1 (2025-01-15) - Sprint 1 Concluído
- ✅ Otimização de imagens: srcset, AVIF, scripts de build
- ✅ Critical CSS expandido: FCP otimizado
- ✅ Service Worker & PWA: Cache offline, manifest, offline page
- ✅ Correções: Review text extraction, estrelas sempre visíveis

### v2.3.0 (2025-01-15) - Sistema de Reviews Aprimorado
- ✅ Detecção inteligente de fotos reais vs placeholders
- ✅ Randomização de reviews (varia a cada carregamento)
- ✅ Filtragem avançada (COVID, autores excluídos, notas baixas)
- ✅ Priorização por qualidade (foto real > texto médio > rating > recência)
- ✅ Scripts de limpeza e documentação completa

### Status Atual
- ✅ Code audit completo
- ✅ Linting automatizado configurado
- ✅ Testes básicos implementados
- ✅ Documentação atualizada
- ✅ Sistema de reviews otimizado
- ✅ Sprint 1: Performance & Core Web Vitals (75% - falta apenas CDN)

---

## 🎯 Próximas Prioridades

### 1. Performance & Core Web Vitals (Alta Prioridade)

#### 1.1 Otimização de Imagens Avançada
**Impacto**: Alto | **Esforço**: 2-3 dias | **ROI**: ⭐⭐⭐⭐⭐

- [ ] Implementar `srcset` com múltiplos tamanhos (1x, 2x, 3x)
- [ ] Adicionar suporte AVIF (30% menor que WebP)
- [ ] Compressão automática no build pipeline
- [ ] Gerar thumbnails automáticos para galerias

**Ferramentas**: Sharp, ImageMagick

#### 1.2 Critical CSS Expandido
**Impacto**: Alto | **Esforço**: 1-2 dias | **ROI**: ⭐⭐⭐⭐⭐

- [ ] Extrair CSS crítico para hero section e above-the-fold
- [ ] Inline critical CSS, defer rest
- [ ] Automatizar extração com ferramentas (critical, purgecss)
- [ ] Reduzir CSS não utilizado

**Ferramentas**: critical, purgecss, uncss

#### 1.3 Service Worker & PWA
**Impacto**: Alto | **Esforço**: 3-4 dias | **ROI**: ⭐⭐⭐⭐

- [ ] Service worker para cache offline
- [ ] Web App Manifest completo
- [ ] "Add to Home Screen" funcional
- [ ] Offline fallback page
- [ ] Background sync para formulários

**Benefícios**: App-like experience, melhor engajamento mobile

#### 1.4 CDN Integration
**Impacto**: Alto | **Esforço**: 1 dia | **ROI**: ⭐⭐⭐⭐

- [ ] Configurar Cloudflare ou similar
- [ ] Mover assets estáticos para CDN
- [ ] Auto WebP conversion no CDN
- [ ] Cache purging automático no deploy

---

### 2. UX & Design Moderno (Média Prioridade)

#### 2.1 Dark Mode ✅ CONCLUÍDO (v2.3.3)
**Impacto**: Médio | **Esforço**: 2 dias | **ROI**: ⭐⭐⭐

- [x] CSS Variables para tema (sistema completo)
- [x] Toggle no header (botão lua/sol)
- [x] Persistência com localStorage
- [x] Prefers-color-scheme detection
- [x] Transições suaves
- [x] Cores da marca preservadas
- [x] Boas práticas aplicadas (Material Design)

#### 2.2 Animações & Micro-interações
**Impacto**: Médio | **Esforço**: 2-3 dias | **ROI**: ⭐⭐⭐

- [ ] Scroll-triggered animations (AOS ou GSAP)
- [ ] Hover effects aprimorados em cards
- [ ] Loading skeletons
- [ ] Smooth scroll behavior
- [ ] Parallax sutil no hero

**Bibliotecas**: AOS (Animate On Scroll), GSAP, ou CSS puro

#### 2.3 Acessibilidade (WCAG AA)
**Impacto**: Médio | **Esforço**: 3-4 dias | **ROI**: ⭐⭐⭐

- [ ] ARIA labels completos
- [ ] Navegação por teclado
- [ ] Contraste de cores (WCAG AA)
- [ ] Screen reader optimization
- [ ] Skip to content link

**Ferramentas**: axe DevTools, WAVE, Lighthouse Accessibility

#### 2.4 Formulário Multi-step
**Impacto**: Médio | **Esforço**: 2-3 dias | **ROI**: ⭐⭐⭐

- [ ] Dividir em 2-3 steps
- [ ] Progress indicator
- [ ] Validação em tempo real
- [ ] Save draft (localStorage)
- [ ] Analytics de abandono

---

### 3. Features & Funcionalidades (Média Prioridade)

#### 3.1 Instagram Feed Integrado
**Impacto**: Médio | **Esforço**: 2-3 dias | **ROI**: ⭐⭐⭐

- [ ] API do Instagram (ou scraper alternativo)
- [ ] Grid de posts recentes
- [ ] Before/After gallery
- [ ] Auto-refresh cache
- [ ] Fallback se API falhar

#### 3.2 Sistema de Busca
**Impacto**: Médio | **Esforço**: 2-3 dias | **ROI**: ⭐⭐⭐

- [ ] Search bar no header
- [ ] Busca por serviços
- [ ] Filtros por categoria/preço
- [ ] Resultados em tempo real
- [ ] Highlight de termos

#### 3.3 ~~Booking Widget Integrado~~ (Removido)
**Status**: Sistema de agendamento via website removido. Agendamentos são feitos via WhatsApp.

---

### 4. Technical Debt & Modernização (Baixa-Média Prioridade)

#### 4.1 Modernizar JavaScript
**Impacto**: Baixo-Médio | **Esforço**: 3-4 dias | **ROI**: ⭐⭐

- [ ] ES6+ syntax
- [ ] Modules (import/export)
- [ ] Async/await
- [ ] Optional: TypeScript ou Alpine.js
- [ ] Build tool moderno (Vite, Webpack)

#### 4.2 CSS Moderno
**Impacto**: Baixo-Médio | **Esforço**: 2-3 dias | **ROI**: ⭐⭐

- [ ] CSS Variables para tema
- [ ] CSS Grid onde faz sentido
- [ ] Remover código legacy
- [ ] Organizar em módulos
- [ ] PostCSS com autoprefixer

#### 4.3 Testing & QA
**Impacto**: Médio | **Esforço**: 5-7 dias | **ROI**: ⭐⭐⭐

- [ ] PHPUnit para backend
- [ ] Browser testing (Playwright)
- [ ] Visual regression
- [ ] Performance budgets
- [ ] Lighthouse CI

#### 4.4 Monitoring & Analytics
**Impacto**: Médio | **Esforço**: 2-3 dias | **ROI**: ⭐⭐⭐

- [ ] Real User Monitoring (RUM)
- [ ] Core Web Vitals tracking
- [ ] Error tracking (Sentry)
- [ ] Custom events GA4
- [ ] Heatmaps (Hotjar)

---

## 📊 Roadmap Recomendado

### Sprint 1 (Próximas 2 semanas) 🎯
1. Otimização de imagens (srcset, AVIF)
2. Critical CSS expandido
3. Service Worker & PWA básico
4. CDN Integration

**Impacto esperado**: +30% performance, melhor mobile

### Sprint 2 (2-4 semanas) 🎯
1. ✅ Dark Mode (CONCLUÍDO v2.3.3)
2. Animações básicas
3. Instagram Feed
4. Formulário multi-step

**Impacto esperado**: +20% conversão, melhor UX

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
4. Database migration (se necessário)

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

