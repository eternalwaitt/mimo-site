# Plano de Otimização para 95+ em Todas as Categorias

**Objetivo**: Alcançar 95+ em Performance, Accessibility, Best Practices e SEO tanto no Mobile quanto no Desktop

## 📊 Status Atual

### Mobile
- Performance: **68** → Meta: **95** (+27 pontos)
- Accessibility: **89** → Meta: **95** (+6 pontos)
- Best Practices: **96** ✅
- SEO: **100** ✅

### Desktop
- Performance: **94** → Meta: **95** (+1 ponto)
- Accessibility: **90** → Meta: **95** (+5 pontos)
- Best Practices: **96** ✅
- SEO: **100** ✅

## 🎯 Plano de Ação

### Fase 1: Performance Mobile (+27 pontos)

#### 1.1 Otimizar FCP (4.1s → <2.0s) - +10 pontos
- [ ] Preload crítico de fontes (Akrobat)
- [ ] Inline CSS crítico completo
- [ ] Remover scripts bloqueantes do `<head>`
- [ ] Otimizar hero image (LCP element)

#### 1.2 Otimizar LCP (6.1s → <2.5s) - +10 pontos
- [ ] Preload hero image (AVIF/WebP)
- [ ] Otimizar header mobile image
- [ ] Reduzir tamanho de imagens grandes
- [ ] Usar `fetchpriority="high"` no LCP element

#### 1.3 Otimizar SI (4.1s → <3.4s) - +3 pontos
- [ ] Reduzir render blocking
- [ ] Otimizar CSS crítico
- [ ] Lazy load imagens abaixo do fold

#### 1.4 Remover JS não utilizado (83 KiB) - +4 pontos
- [ ] Analisar `main.js` e remover código morto
- [ ] Verificar uso de jQuery plugins
- [ ] Remover `jquery.touchswipe` se não usado
- [ ] Tree-shaking para JS customizado

### Fase 2: Accessibility (+6 mobile, +5 desktop)

#### 2.1 Corrigir ARIA Issues
- [ ] Validar todos os atributos `aria-*`
- [ ] Corrigir estrutura ARIA (roles e children)
- [ ] Adicionar `aria-label` onde necessário
- [ ] Corrigir `aria-selected` no carousel

#### 2.2 Corrigir Contraste
- [ ] Verificar todos os textos (WCAG AA)
- [ ] Ajustar cores de baixo contraste
- [ ] Garantir contraste em dark mode

#### 2.3 Corrigir Heading Order
- [ ] Verificar todas as páginas
- [ ] Garantir h1 → h2 → h3 sequencial
- [ ] Não pular níveis

#### 2.4 Corrigir Alt Attributes
- [ ] Revisar todas as imagens
- [ ] Remover alt redundante
- [ ] Adicionar alt descritivo onde falta

#### 2.5 Corrigir List Items
- [ ] Verificar estrutura de listas
- [ ] Garantir `<li>` dentro de `<ul>/<ol>`

### Fase 3: Otimizações Finais

#### 3.1 Font Display (30ms savings)
- [ ] Adicionar `font-display: swap` em todas as fontes
- [ ] Verificar Google Fonts
- [ ] Verificar fontes locais (Akrobat)

#### 3.2 Long Main-Thread Tasks
- [ ] Identificar task longo
- [ ] Code splitting
- [ ] Defer/async otimizado

#### 3.3 Image Optimization
- [ ] Mobile: 781 KiB → <500 KiB
- [ ] Desktop: 186 KiB → <100 KiB
- [ ] Verificar todas as imagens usando AVIF/WebP

#### 3.4 CSS/JS Minification
- [ ] Verificar se minificação está ativa
- [ ] Garantir que `USE_MINIFIED = true`
- [ ] Verificar arquivos minificados existem

## 📝 Implementação

### Prioridade Alta (Impacto Direto no Score)
1. ✅ Remover JS não utilizado (83 KiB)
2. ✅ Otimizar FCP (preload, inline CSS)
3. ✅ Otimizar LCP (preload hero image)
4. ✅ Corrigir ARIA issues
5. ✅ Corrigir contraste

### Prioridade Média
6. ✅ Corrigir heading order
7. ✅ Corrigir alt attributes
8. ✅ Font display optimization
9. ✅ Image optimization final

### Prioridade Baixa
10. ✅ Long main-thread tasks
11. ✅ Cache lifetimes
12. ✅ Document request latency

## 🎯 Métricas de Sucesso

### Mobile
- Performance: 68 → **95+** (+27 pontos)
- Accessibility: 89 → **95+** (+6 pontos)

### Desktop
- Performance: 94 → **95+** (+1 ponto)
- Accessibility: 90 → **95+** (+5 pontos)

## ⏱️ Timeline

- **Fase 1**: Performance Mobile (2-3 horas)
- **Fase 2**: Accessibility (1-2 horas)
- **Fase 3**: Otimizações Finais (1 hora)
- **Total**: 4-6 horas

