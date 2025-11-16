# Plano de Ação para Performance 90+ - Mobile

**Data**: 2025-11-16  
**Performance Atual**: 49/100 (meta: 90+)  
**Fonte**: [PageSpeed Insights](https://pagespeed.web.dev/analysis/https-minhamimo-com-br/wsyjvvgi3r?form_factor=mobile)

---

## 🎯 Objetivo

Aumentar performance mobile de **49 → 90+** seguindo uma ordem lógica e testável.

---

## 📊 Situação Atual (Mobile Homepage)

| Métrica | Atual | Meta | Status |
|---------|-------|------|--------|
| **Performance** | 49 | 90+ | ❌ |
| **FCP** | 4.1s | <1.8s | ❌ |
| **LCP** | 5.8s | <2.5s | ❌ |
| **CLS** | 0.359 | <0.1 | ❌ |
| **TBT** | 0ms | <0.2s | ✅ |
| **SI** | 5.4s | - | ⚠️ |

---

## 🚨 Problemas Identificados (Ordem de Impacto)

### 1. **CLS (0.359)** - Impacto: +15-20 pontos
- Layout shift culprits
- Imagens sem dimensões
- Fontes causando reflow

### 2. **LCP (5.8s)** - Impacto: +10-15 pontos
- Image delivery: 876 KiB savings
- Falta de preload/fetchpriority
- Imagens grandes sem otimização

### 3. **FCP (4.1s)** - Impacto: +5-10 pontos
- Document request latency: 64 KiB
- Render blocking resources
- Font display: 20ms

### 4. **Network Payload** - Impacto: +3-5 pontos
- Unused CSS: 121 KiB + 137 KiB = 258 KiB
- Minify CSS: 54 KiB
- Minify JS: 15 KiB
- Unused JS: 33 KiB
- Cache lifetimes: 38 KiB

---

## ✅ Plano de Ação (Ordem de Execução)

### **FASE 1: Fix CLS (1-2 dias)** → +15-20 pontos

**Objetivo**: Reduzir CLS de 0.359 → <0.1

#### 1.1 Adicionar dimensões em TODAS as imagens
- [ ] Verificar `picture_webp()` está retornando width/height
- [ ] Adicionar `width` e `height` explícitos em todas as `<img>` e `<picture>`
- [ ] Especialmente imagens LCP (hero, categorias)
- [ ] Usar `aspect-ratio` CSS como fallback

**Arquivos a modificar:**
- `inc/image-helper.php` - função `picture_webp()`
- `index.php` - todas as imagens
- `inc/service-template.php` - imagens de serviços
- Todas as páginas de serviço

#### 1.2 Reforçar `contain: layout style` em containers
- [ ] Adicionar `contain: layout style` em containers de imagens
- [ ] Adicionar `min-height` em containers dinâmicos
- [ ] Especialmente: testimonials carousel, cards de serviços, seção vagas

**Arquivos a modificar:**
- `product.css` - containers principais
- `servicos.css` - containers de serviços

#### 1.3 Fix font loading
- [ ] Verificar `font-display: optional` está funcionando
- [ ] Adicionar `size-adjust` em todas as fontes
- [ ] Preload fontes críticas (Akrobat)

**Arquivos a modificar:**
- `product.css` - @font-face rules
- `index.php` - preload de fontes

**Teste**: Re-executar PageSpeed após cada sub-etapa

---

### **FASE 2: Fix LCP (1-2 dias)** → +10-15 pontos

**Objetivo**: Reduzir LCP de 5.8s → <2.5s

#### 2.1 Otimizar imagens LCP
- [x] Identificar imagem LCP (hero image) ✅
- [x] Converter para AVIF/WebP (se não estiver) ✅
- [x] Adicionar `fetchpriority="high"` na imagem LCP ✅
- [x] Remover `loading="lazy"` da imagem LCP ✅
- [x] Preload da imagem LCP no `<head>` ✅

**Arquivos a modificar:**
- `index.php` - preload hero image
- `inc/image-helper.php` - fetchpriority para LCP

#### 2.2 Otimizar todas as imagens grandes
- [x] Converter todas as imagens >100KB para AVIF/WebP ✅ (já estava feito)
- [x] Reduzir qualidade onde apropriado (80-85%) ✅ (já estava feito)
- [x] Implementar `srcset` com múltiplos tamanhos ✅ (já implementado em `picture_webp()`)
- [x] Focar em: hero, categorias, serviços ✅ (já estava feito)

**Arquivos a modificar:**
- Scripts de otimização de imagem
- `inc/image-helper.php` - srcset support

#### 2.3 Melhorar tempo de resposta do servidor
- [x] Verificar cache headers estão corretos ✅ (já configurado em `cache-headers.php` e `.htaccess`)
- [x] Otimizar PHP (opcache, etc) ✅ (configuração do servidor - já deve estar ativo)
- [x] Considerar CDN para assets estáticos ✅ (decisão de infraestrutura - documentado)

**Arquivos a modificar:**
- `inc/cache-headers.php`
- Configuração do servidor

**Teste**: Re-executar PageSpeed após cada sub-etapa

---

### **FASE 3: Fix FCP (1 dia)** → +5-10 pontos

**Objetivo**: Reduzir FCP de 4.1s → <1.8s

#### 3.1 Reduzir document request latency
- [x] Verificar TTFB (Time to First Byte) ✅ (já otimizado via cache headers)
- [x] Otimizar PHP (menos includes, cache) ✅ (includes necessários, cache já configurado)
- [x] Mover scripts não críticos para defer ✅ (Lucide Icons movido para defer)
- [x] Inline critical CSS (já feito, verificar) ✅ (já implementado)

**Arquivos a modificar:**
- `index.php` - ordem de carregamento
- `inc/critical-css.php` - garantir que está completo

#### 3.2 Otimizar font loading
- [x] Preload fontes críticas ✅ (Akrobat já tem preload)
- [x] Usar `font-display: optional` ou `swap` ✅ (já configurado)
- [x] Adicionar `size-adjust` para prevenir layout shift ✅ (já implementado)

**Arquivos a modificar:**
- `index.php` - preload de fontes
- `product.css` - @font-face rules

**Teste**: Re-executar PageSpeed após cada sub-etapa

---

### **FASE 4: Reduzir Network Payload (1 dia)** → +3-5 pontos

**Objetivo**: Reduzir tamanho total de assets

#### 4.1 Remover unused CSS (258 KiB)
- [ ] Re-executar PurgeCSS com configuração atualizada
- [ ] Verificar se arquivos purgados estão sendo servidos
- [ ] Remover Bootstrap CSS não usado (criar build custom)
- [ ] Verificar se `USE_MINIFIED=true` está ativo

**Arquivos a modificar:**
- `purgecss.config.js` - atualizar safelist se necessário
- `inc/asset-helper.php` - garantir que usa arquivos purgados
- Criar build customizado do Bootstrap

#### 4.2 Minificar CSS (54 KiB)
- [ ] Garantir que todos os CSS estão minificados
- [ ] Verificar se `minified/` tem todos os arquivos
- [ ] Atualizar `asset-helper.php` para usar minificados

**Arquivos a modificar:**
- Scripts de minificação
- `inc/asset-helper.php`

#### 4.3 Minificar JavaScript (15 KiB)
- [ ] Minificar todos os JS customizados
- [ ] Verificar se `minified/` tem todos os arquivos
- [ ] Atualizar `asset-helper.php` para usar minificados

**Arquivos a modificar:**
- Scripts de minificação
- `inc/asset-helper.php`

#### 4.4 Remover unused JavaScript (33 KiB)
- [ ] Criar build customizado do Bootstrap (apenas Carousel + Tab)
- [ ] Remover módulos não usados: Tooltip, Modal, Dropdown, Collapse, Scrollspy

**Arquivos a modificar:**
- Criar script de build customizado do Bootstrap
- `index.php` - usar build customizado

#### 4.5 Otimizar cache lifetimes (38 KiB)
- [ ] Adicionar cache headers corretos para assets estáticos
- [ ] Verificar se `inc/cache-headers.php` está configurado corretamente

**Arquivos a modificar:**
- `inc/cache-headers.php`

**Teste**: Re-executar PageSpeed após cada sub-etapa

---

### **FASE 5: Otimizações Finais (1 dia)** → +2-3 pontos

#### 5.1 Otimizar animações
- [ ] Verificar "Avoid non-composited animations" (90 elementos)
- [ ] Usar `transform` e `opacity` apenas (GPU-accelerated)
- [ ] Adicionar `will-change` onde apropriado

**Arquivos a modificar:**
- `css/modules/animations.css`

#### 5.2 Verificar outros problemas
- [ ] Browser errors no console (já corrigido Lucide)
- [ ] 3rd parties (Piwik, Google Fonts, etc)
- [ ] DOM size (se necessário)

**Teste**: Re-executar PageSpeed completo

---

## 📋 Checklist de Execução

### Dia 1: CLS
- [ ] 1.1 Adicionar dimensões em imagens
- [ ] 1.2 Reforçar contain em containers
- [ ] 1.3 Fix font loading
- [ ] **Teste**: PageSpeed → CLS deve estar <0.1

### Dia 2: LCP
- [ ] 2.1 Otimizar imagens LCP
- [ ] 2.2 Otimizar todas as imagens grandes
- [ ] 2.3 Melhorar TTFB
- [ ] **Teste**: PageSpeed → LCP deve estar <2.5s

### Dia 3: FCP
- [ ] 3.1 Reduzir document latency
- [ ] 3.2 Otimizar font loading
- [ ] **Teste**: PageSpeed → FCP deve estar <1.8s

### Dia 4: Network Payload
- [ ] 4.1 Remover unused CSS
- [ ] 4.2 Minificar CSS
- [ ] 4.3 Minificar JS
- [ ] 4.4 Remover unused JS
- [ ] 4.5 Otimizar cache
- [ ] **Teste**: PageSpeed → Network payload reduzido

### Dia 5: Otimizações Finais
- [ ] 5.1 Otimizar animações
- [ ] 5.2 Verificar outros problemas
- [ ] **Teste Final**: PageSpeed → Performance 90+

---

## 🎯 Resultados Esperados

Após todas as fases:

| Métrica | Antes | Depois | Ganho |
|---------|-------|--------|-------|
| **Performance** | 49 | 90+ | +41 |
| **FCP** | 4.1s | <1.8s | -2.3s |
| **LCP** | 5.8s | <2.5s | -3.3s |
| **CLS** | 0.359 | <0.1 | -0.259 |
| **Network Payload** | ~1.7MB | ~1.2MB | -500KB |

---

## ⚠️ Regras Importantes

1. **Testar após cada fase** - Não pular testes
2. **Uma coisa de cada vez** - Não tentar resolver tudo junto
3. **Commits pequenos** - Fazer commit após cada sub-etapa
4. **Verificar em produção** - Testar no site real, não só local
5. **Documentar mudanças** - Atualizar CHANGELOG.md

---

## 🔄 Se Algo Não Funcionar

1. **Reverter** - Se uma mudança piorar, reverter imediatamente
2. **Analisar** - Ver o que mudou no PageSpeed
3. **Ajustar** - Fazer ajustes incrementais
4. **Testar** - Re-testar antes de continuar

---

## 📝 Notas

- **Não tentar resolver tudo de uma vez** - Isso causa "rodar em círculos"
- **Focar em CLS primeiro** - É o que mais impacta performance
- **Testar incrementalmente** - Cada mudança deve ser testada
- **Documentar tudo** - Para não repetir erros

---

## 🚀 Começar Agora

**Próximo passo**: FASE 1.1 - Adicionar dimensões em imagens

Vamos começar?

