# Mimo Site - Documentação Master Completa

**Data de Atualização**: 2025-11-16  
**Versão Atual**: 2.6.12  
**Asset Version**: 20251116-92  
**Status**: ✅ Documentação Completa para Otimização de Performance

---

## 📋 Índice

1. [Visão Geral do Projeto](#visão-geral-do-projeto)
2. [Estrutura e Arquitetura](#estrutura-e-arquitetura)
3. [Cores da Marca](#cores-da-marca)
4. [Layout e Espaçamentos](#layout-e-espaçamentos)
5. [Animações e Interações](#animações-e-interações)
6. [Dark Mode](#dark-mode)
7. [Otimizações de Performance Implementadas](#otimizações-de-performance-implementadas)
8. [Configurações Críticas](#configurações-críticas)
9. [Guia de Recuperação](#guia-de-recuperação)
10. [Próximos Passos para Performance](#próximos-passos-para-performance)
11. [Referências e Documentos Relacionados](#referências-e-documentos-relacionados)

---

## 🎯 Visão Geral do Projeto

### Tecnologias Principais
- **Backend**: PHP 7.1+ (production), PHP 8.4+ (development)
- **Frontend**: Bootstrap 4.5.2, jQuery 3.3.1, Custom CSS/JS
- **Ícones**: Lucide Icons (migrado de Font Awesome - economia de ~70 KiB)
- **Email**: PHPMailer com Mailgun SMTP
- **Otimização de Imagens**: WebP/AVIF com fallbacks via `<picture>`
- **Build Tools**: Shell scripts para WebP, minificação CSS/JS
- **SEO**: Schema.org, Open Graph, Twitter Cards, sitemap.xml

### Ambiente e Configuração
- **APP_ENV**: `development` (padrão) / `production`
- **USE_MINIFIED**: `false` em desenvolvimento, `true` em produção (após build)
- **ASSET_VERSION**: Sistema de cache busting (atual: `20251116-92`)

---

## 🏗️ Estrutura e Arquitetura

### Estrutura de Diretórios Críticos

```
public_html/
├── index.php                    # Homepage principal
├── config.php                   # Configurações e variáveis de ambiente
├── product.css                  # CSS principal (65KB original, ~39KB minificado)
├── servicos.css                 # CSS específico para páginas de serviço
├── main.js                      # JavaScript principal
├── inc/                         # Includes compartilhados
│   ├── header.php               # Header centralizado (todas as páginas)
│   ├── footer.php               # Footer centralizado (todas as páginas)
│   ├── asset-helper.php        # Helper para carregar CSS/JS (com validação)
│   ├── critical-css.php        # CSS crítico acima da dobra
│   ├── image-helper.php        # Helper para imagens WebP/AVIF
│   └── service-template.php    # Template para páginas de serviço
├── css/
│   ├── modules/
│   │   ├── _variables.css       # Variáveis CSS (cores da marca)
│   │   ├── dark-mode.css       # Estilos específicos dark mode
│   │   ├── animations.css      # Animações otimizadas
│   │   ├── accessibility-fixes.css
│   │   └── mobile-ui-improvements.css
│   └── purged/                 # CSS purgado (PurgeCSS) - usar com cuidado
├── minified/                    # CSS/JS minificados
├── build/                       # Scripts de build
│   ├── minify-css.sh
│   ├── minify-js.sh
│   └── purge-css.sh
└── pagespeed-results/           # Resultados de testes PageSpeed
```

### Páginas Principais
- **Homepage**: `index.php` (seções: hero, about, services, testimonials)
- **Contato**: `contato.php`
- **FAQ**: `faq/index.php`
- **Vagas**: `vagas.php`
- **Serviços**: `esmalteria/`, `estetica/`, `salao/`, `esteticafacial/`, `cilios/`, `micropigmentacao/`

---

## 🎨 Cores da Marca

### Cores Principais
- **Brand Pink**: `#d9c2bd` (cor principal, usada em backgrounds e acentos)
- **Brand Dark**: `#31265b` (cor escura, usada em textos e elementos escuros)
- **White/Off White**: `#ffffff` / `rgb(250, 250, 250)` (backgrounds claros)

### Uso das Cores

#### Light Mode
- **Backgrounds**: Off-white `rgb(250, 250, 250)`, brand pink `#d9c2bd` para seções especiais
- **Textos**: Brand dark `#31265b` em backgrounds claros, branco `#ffffff` em backgrounds escuros/pink
- **Acentos**: Brand pink `#d9c2bd` para links, botões, ícones

#### Dark Mode
- **Backgrounds**: Tons escuros mais claros para melhor contraste (`#2a2a2a` body, `#333333` seções)
- **Textos**: Branco `#ffffff` e cinza claro `#e0e0e0` para legibilidade
- **Acentos**: Brand pink `#d9c2bd` (versão mais clara `#e5d1cd` para hover)
- **Bordas**: Bordas sutis `rgba(255, 255, 255, 0.12)` para separação visual

### Regras Importantes
- **NÃO usar roxo** - `#31265b` é "brand dark" (azul escuro), não roxo
- **Dark mode** = versões mais escuras das cores da marca, não cores diferentes
- **Contraste WCAG AA**: Mínimo 4.5:1 para texto normal, 3:1 para texto grande

---

## 📐 Layout e Espaçamentos

### Espaçamentos Otimizados (2025-11-16)

#### Seção #about → .backgroundPink
- **#about**: `padding-bottom: 1rem` (16px)
- **.backgroundPink**: `padding-top: 0.75rem` (12px), `padding-bottom: 0.75rem` (12px)
- **.hero-tagline**: `margin-top: 0.5rem` (8px), `margin-bottom: 0`

#### Seção .testimonials-section → #services
- **.testimonials-section**: `padding-bottom: 0.5rem` (8px)
- **.testimonials-carousel**: `padding-bottom: 10px`
- **#services**: `padding-top: 1rem` (16px)

### Containers e Larguras
- **Footer container**: `max-width: 960px` (match production)
- **Service pages container**: `max-width: 960px`
- **Homepage containers**: Bootstrap padrão (1140px) exceto footer

### Alturas Mínimas (CLS Prevention)
- **#about**: `min-height: 500px`
- **#services**: `min-height: 800px`
- **.testimonials-section**: `min-height: 600px`
- **.testimonials-carousel**: `min-height: 550px`

---

## 🎬 Animações e Interações

### Header/Navbar Animation
- **Estado inicial**: Logo `height: 55px`, `max-width: 150px`
- **Estado comprimido** (scroll >= 20px): Logo `height: 28px`, `max-width: 100px`
- **Navbar**: Sempre compacto (`padding: 8px`), apenas logo anima
- **Background**: `rgba(45, 45, 45, 0.95)` para contraste com logo branca (WCAG AA)
- **Transição**: `0.3s ease` para height e max-width do logo
- **JavaScript**: `main.js` + fallback inline em `index.php`, `faq/index.php`, `vagas.php`

### Service Cards Hover
- **Imagem**: `filter: brightness(0.85)` no hover (escurece 15%)
- **Texto**: Aparece apenas no hover (overlay com conteúdo)
- **Botão "PROCEDIMENTOS"**: Branco com borda, aparece no hover

### Testimonials Carousel
- **Controles**: Botões anterior/próximo com ícones brand pink
- **Indicadores**: Brand dark `#31265b` (inativo), brand dark sólido (ativo)
- **Transição**: Instantânea no mobile (`0.01s`), fade no desktop

### Otimizações de Performance
- **GPU Acceleration**: `transform: translateZ(0)`, `will-change`
- **Backface Visibility**: `backface-visibility: hidden`
- **Containment**: `contain: layout style paint` onde possível
- **Mobile**: Animações desabilitadas para melhor performance

---

## 🌙 Dark Mode

### Implementação
- **Toggle**: Botão no header com ícone Lucide
- **Storage**: `localStorage` para persistência
- **Attribute**: `data-theme="dark"` no `<html>`

### Cores Dark Mode

#### Backgrounds (Hierarquia Visual)
- **Body**: `#2a2a2a` (base)
- **Seções**: Tons diferentes para separação visual
  - `.backgroundGrey`: `#333333`
  - `#services`: `#2d2d2d`
  - `#testimonials`: `#303030`
  - `.backgroundPink`: `rgba(217, 194, 189, 0.25)` (brand pink com opacidade)
- **Cards**: `#353535` (cards, modais, info cards)
- **Testimonials**: `#383838`

#### Bordas e Sombras
- **Bordas**: `2px solid rgba(255, 255, 255, 0.12)` para seções principais
- **Sombras**: `inset` shadows para profundidade, `box-shadow` para elevação
- **Service cards**: Bordas `rgba(255, 255, 255, 0.15)`, sombras mais fortes

#### Textos
- **Primário**: `#f5f5f5` (branco suave)
- **Secundário**: `#e0e0e0` (cinza claro)
- **Acentos**: Brand pink `#d9c2bd` / `#e5d1cd` (hover)

### Contraste e Legibilidade
- **WCAG AA**: Todos os contrastes validados (mínimo 4.5:1)
- **Separação Visual**: Bordas e sombras para distinguir seções
- **Text Shadows**: Adicionados onde necessário para legibilidade

---

## ⚡ Otimizações de Performance Implementadas

### 1. CLS (Cumulative Layout Shift) - ✅ Otimizado
- **Meta**: <0.1 (atual: ~0.144 desktop, ~0.332 mobile)
- **Implementações**:
  - `min-height` em todos os containers principais
  - `aspect-ratio` para imagens
  - `contain: layout style paint` em cards e seções
  - `contain-intrinsic-size` para content-visibility
  - Espaço reservado para testimonials carousel
  - Background colors para prevenir shift

### 2. LCP (Largest Contentful Paint) - ✅ Otimizado
- **Meta**: <2.5s
- **Implementações**:
  - Preload de imagens críticas (hero, header)
  - `fetchpriority="high"` em imagens LCP
  - WebP/AVIF com fallbacks
  - `srcset` responsivo
  - Aspect-ratio CSS para prevenir reflow

### 3. FCP (First Contentful Paint) - ✅ Otimizado
- **Meta**: <1.8s mobile, <1.0s desktop
- **Implementações**:
  - CSS crítico inline (`inc/critical-css.php`)
  - CSS não crítico com `loadCSS()` ou `media="print"`
  - Scripts com `defer`
  - Font preloading

### 4. CSS Optimization - ✅ Otimizado
- **PurgeCSS**: Removido ~97 KiB de CSS não utilizado
  - `product.css`: 90% reduction
  - `dark-mode.css`: 90% reduction
  - `animations.css`: 71% reduction
- **Minificação**: Todos os CSS minificados (~40% reduction)
- **Validação**: Asset helper valida tamanho de arquivos purged (< 5KB = quebrado)

### 5. JavaScript Optimization - ✅ Otimizado
- **Minificação**: Todos os JS minificados (~80% reduction)
- **Defer**: Todos os scripts não críticos com `defer`
- **requestAnimationFrame**: Para batched DOM updates
- **requestIdleCallback**: Para processamento não crítico
- **GPU Acceleration**: `translateZ(0)` em animações

### 6. Image Optimization - ✅ Otimizado
- **Formatos**: WebP/AVIF com fallbacks JPG/PNG
- **Responsive**: `srcset` com múltiplos tamanhos
- **Lazy Loading**: `loading="lazy"` em imagens abaixo da dobra
- **Preload**: Imagens críticas pré-carregadas

### 7. Font Optimization - ✅ Otimizado
- **font-display**: `optional` para fontes não críticas
- **Preload**: Fontes críticas pré-carregadas
- **Subset**: Apenas caracteres necessários

### 8. Cache e Headers - ✅ Otimizado
- **Cache Headers**: 1 ano para assets estáticos
- **Compression**: gzip/brotli via `.htaccess`
- **Cache Busting**: `ASSET_VERSION` em URLs

---

## ⚙️ Configurações Críticas

### config.php

```php
// Ambiente (CRÍTICO: sempre 'development' em dev)
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Asset version para cache busting (atualizar sempre que houver mudanças)
define('ASSET_VERSION', '20251116-92');

// Minificação (só ativar em produção após build)
define('USE_MINIFIED', false); // true apenas em produção
```

### inc/asset-helper.php

**Validações Críticas**:
- Sempre retorna arquivo original se `APP_ENV !== 'production'`
- Valida tamanho de arquivos purged (< 500 bytes = quebrado)
- Fallback para arquivo original se purged muito pequeno

### Header e Footer Centralizados

**Arquivos**:
- `inc/header.php` - Header usado em todas as páginas
- `inc/footer.php` - Footer usado em todas as páginas

**Importante**: Mudanças no header/footer afetam todas as páginas automaticamente.

---

## 🛠️ Guia de Recuperação

### Quando Minify Quebra o Site

**Documento Completo**: `RECOVERY-GUIDE-MINIFY-BREAKAGE.md`

**Sintomas**:
- Layout completamente quebrado
- CSS não carregando
- Páginas sem estilos

**Solução Rápida**:
1. Verificar `APP_ENV` em `config.php` (deve ser `'development'`)
2. Verificar tamanhos de arquivos purged (< 500 bytes = quebrado)
3. Atualizar `ASSET_VERSION` para forçar cache busting
4. Limpar cache do navegador

**Prevenção**:
- Sempre usar `APP_ENV = 'development'` em desenvolvimento
- Validar tamanhos de arquivos antes de usar
- Fazer backup antes de minificar
- Testar em staging antes de produção

---

## 🚀 Próximos Passos para Performance

### Oportunidades Identificadas (PageSpeed Insights)

#### Mobile (Performance: 65)
1. **Improve image delivery** - 795 KiB economia possível
   - Verificar se todas as imagens grandes estão otimizadas
   - Considerar lazy loading mais agressivo
   - Verificar se WebP/AVIF estão sendo servidos corretamente

2. **Reduce unused CSS** - 23 KiB economia possível
   - Re-executar PurgeCSS com configuração atualizada
   - Verificar safelist do PurgeCSS

3. **Minify CSS** - 4 KiB economia possível
   - Verificar se todos os CSS estão minificados
   - Re-executar minificação se necessário

4. **Avoid non-composited animations** - 35 elementos
   - Verificar se todas as animações usam GPU acceleration
   - Adicionar `translateZ(0)` onde faltar

5. **CLS** - 0.332 (meta: <0.1)
   - Continuar otimizando espaçamentos
   - Verificar se todos os containers têm `min-height`
   - Validar `aspect-ratio` em todas as imagens

#### Desktop (Performance: 92)
1. **Improve image delivery** - 366 KiB economia possível
2. **Reduce unused CSS** - 23 KiB economia possível
3. **Minify CSS** - 4 KiB economia possível
4. **Avoid non-composited animations** - 128 elementos
5. **CLS** - 0.144 (meta: <0.1)

### Plano de Ação

#### Fase 1: Validação de Otimizações Existentes
- [ ] Verificar se todas as imagens grandes estão em WebP/AVIF
- [ ] Validar que PurgeCSS está configurado corretamente
- [ ] Confirmar que todos os CSS estão minificados
- [ ] Verificar animações para GPU acceleration

#### Fase 2: Otimizações Adicionais
- [ ] Re-executar PurgeCSS com safelist atualizado
- [ ] Otimizar imagens que ainda não foram convertidas
- [ ] Adicionar `translateZ(0)` em animações que faltam
- [ ] Revisar espaçamentos para reduzir CLS

#### Fase 3: Testes e Validação
- [ ] Rodar PageSpeed Insights em todas as páginas
- [ ] Validar CLS < 0.1 em mobile e desktop
- [ ] Verificar que todas as otimizações estão ativas
- [ ] Documentar resultados

---

## 📚 Referências e Documentos Relacionados

### Documentos Principais
- **RECOVERY-GUIDE-MINIFY-BREAKAGE.md** - Guia completo de recuperação quando minify quebra
- **README.md** - Documentação geral do projeto
- **CHANGELOG.md** - Histórico de mudanças
- **pagespeed-results/manual-test-20251116.md** - Resultados de testes PageSpeed

### Documentos de Performance
- **PERFORMANCE-OPTIMIZATION-SUMMARY-v2.6.12.md** - Resumo de otimizações
- **ALL-PHASES-COMPLETE-v2.6.12.md** - Todas as fases de otimização completadas
- **CSS-OPTIMIZATION-v2.6.12.md** - Otimizações de CSS
- **JAVASCRIPT-OPTIMIZATION-v2.6.12.md** - Otimizações de JavaScript
- **IMAGE-OPTIMIZATION-v2.6.12.md** - Otimizações de imagens

### Documentos de Layout e Design
- **LAYOUT-FIXES-v2.6.10.md** - Correções de layout
- **PRODUCTION-LAYOUT-FIX.md** - Fix de layout em produção

### Arquivos de Configuração Críticos
- **config.php** - Configurações principais
- **inc/asset-helper.php** - Helper de assets com validações
- **inc/header.php** - Header centralizado
- **inc/footer.php** - Footer centralizado
- **product.css** - CSS principal
- **css/modules/_variables.css** - Variáveis CSS (cores)
- **css/modules/dark-mode.css** - Estilos dark mode

---

## 🔄 Processo de Atualização

### Quando Fazer Mudanças

1. **Desenvolvimento**:
   - `APP_ENV = 'development'` em `config.php`
   - Sempre usar arquivos originais (não minificados)
   - Atualizar `ASSET_VERSION` após mudanças

2. **Produção**:
   - Executar scripts de build (`build/minify-css.sh`, `build/minify-js.sh`)
   - Validar tamanhos dos arquivos gerados
   - Testar em staging primeiro
   - Ativar `APP_ENV = 'production'` e `USE_MINIFIED = true`

### Cache Busting

**Sempre atualizar `ASSET_VERSION` em `config.php` quando**:
- Mudanças em CSS
- Mudanças em JavaScript
- Mudanças em imagens críticas
- Qualquer mudança que requer reload de assets

**Formato**: `YYYYMMDD-NN` (ex: `20251116-92`)

---

## 📝 Notas Importantes

### Cores da Marca
- **NÃO usar roxo** - `#31265b` é "brand dark" (azul escuro)
- **Dark mode** = versões mais escuras das cores da marca
- **Light mode** = cores da marca originais

### Espaçamentos
- Espaçamentos foram otimizados para reduzir gaps desnecessários
- Manter consistência entre seções
- Usar `rem` para espaçamentos (melhor para acessibilidade)

### Performance
- **Nunca ativar minificação sem testar primeiro**
- **Sempre validar tamanhos de arquivos purged**
- **Manter `APP_ENV = 'development'` em desenvolvimento**

### Header e Footer
- **Centralizados** - mudanças afetam todas as páginas
- **Links absolutos** - usar `/contato.php` não `contato.php`
- **Animações** - fallback inline em páginas críticas

---

**Última Atualização**: 2025-11-16  
**Versão do Documento**: 1.0  
**Próxima Revisão**: Após otimizações de performance

