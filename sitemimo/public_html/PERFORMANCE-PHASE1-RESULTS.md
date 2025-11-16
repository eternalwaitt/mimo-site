# Resultados FASE 1 - Fix CLS

**Data**: 2025-11-16  
**Fase**: 1.1, 1.2, 1.3 (Fix CLS)  
**Teste**: PageSpeed Insights Mobile Homepage  
**Link**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/tp41eoi1bs?form_factor=mobile

---

## 📊 Resultados Após FASE 1

### Métricas Principais

| Métrica | Antes | Depois | Mudança | Status |
|---------|-------|--------|---------|--------|
| **Performance** | 49 | 49 | 0 | ❌ Sem mudança |
| **FCP** | 4.1s | 4.1s | 0s | ❌ Sem mudança |
| **LCP** | 5.8s | 5.7s | -0.1s | ⚠️ Melhorou pouco |
| **CLS** | 0.359 | 0.451 | +0.092 | ❌ **PIOROU** |
| **TBT** | 0ms | 0ms | 0ms | ✅ Mantido |
| **SI** | 5.4s | 4.1s | -1.3s | ✅ Melhorou |

### Análise

**CLS piorou de 0.359 → 0.451** (+0.092)

**Possíveis causas**:
1. Mudanças ainda não estão em produção (cache do servidor/CDN)
2. `contain: layout style` pode estar causando problemas em alguns navegadores
3. `aspect-ratio` pode estar conflitando com `min-height` em alguns containers
4. Teste foi feito antes das mudanças serem deployadas

**Speed Index melhorou** (-1.3s) - indica que renderização está mais rápida

---

## ✅ Implementado na FASE 1

### 1.1 Adicionar width/height em imagens ✅
- ✅ Melhorada função `picture_webp()` com mais caminhos de detecção
- ✅ Fallback com `aspect-ratio` CSS quando dimensões não detectadas
- ✅ Aspect-ratio inferido baseado no tipo de imagem

### 1.2 Reforçar contain: layout style ✅
- ✅ Adicionado em `.bg-header` (hero image)
- ✅ Adicionado em `.testimonials-carousel` e containers relacionados
- ✅ Adicionado em `.sessoes.container` e `.content`
- ✅ Adicionado `aspect-ratio` onde apropriado

### 1.3 Fix font loading ✅
- ✅ Verificado - já estava otimizado

---

## 🚨 Problemas Identificados (Novos ou Persistentes)

### Insights (Oportunidades de Melhoria)

1. **Improve image delivery** - 876 KiB savings
   - Status: ❌ Ainda presente
   - Ação: FASE 2 (LCP)

2. **Font display** - 50ms savings (era 20ms, aumentou)
   - Status: ⚠️ Piorou
   - Ação: Verificar se mudanças causaram regressão

3. **Layout shift culprits**
   - Status: ❌ Ainda presente
   - Ação: Investigar o que está causando layout shift

4. **Forced reflow**
   - Status: ❌ Novo problema identificado
   - Ação: Investigar JavaScript causando reflow

5. **Document request latency** - 64 KiB
   - Status: ❌ Ainda presente
   - Ação: FASE 3 (FCP)

6. **Use efficient cache lifetimes** - 38 KiB
   - Status: ❌ Ainda presente
   - Ação: FASE 4 (Network Payload)

### Diagnostics

1. **Minify CSS** - 54 KiB
   - Status: ❌ Ainda presente
   - Ação: FASE 4

2. **Reduce unused CSS** - 121 KiB
   - Status: ❌ Ainda presente
   - Ação: FASE 4

3. **Minify JavaScript** - 15 KiB
   - Status: ❌ Ainda presente
   - Ação: FASE 4

4. **Reduce unused JavaScript** - 33 KiB
   - Status: ❌ Ainda presente
   - Ação: FASE 4

5. **Avoid non-composited animations** - 90 elementos
   - Status: ❌ Ainda presente
   - Ação: FASE 5

6. **Avoid long main-thread tasks** - 1 long task
   - Status: ⚠️ Novo problema identificado
   - Ação: Investigar qual script está causando

---

## 🔍 Análise do CLS Piorado

**CLS aumentou de 0.359 → 0.451** (+0.092)

**Possíveis causas**:

1. **Cache não atualizado**
   - Mudanças podem não estar em produção ainda
   - CDN pode estar servindo versão antiga
   - **Ação**: Verificar se arquivos foram deployados

2. **Conflito com `contain: layout style`**
   - Alguns navegadores podem ter problemas com `contain: layout style`
   - Pode estar causando reflow em vez de prevenir
   - **Ação**: Testar sem `contain` em alguns containers

3. **Conflito `aspect-ratio` + `min-height`**
   - `aspect-ratio` e `min-height` podem conflitar
   - **Ação**: Remover `min-height` onde `aspect-ratio` está presente

4. **Forced reflow identificado**
   - Novo problema: "Forced reflow"
   - JavaScript pode estar causando layout shift
   - **Ação**: Investigar scripts causando reflow

---

## 📝 Próximos Passos

### Imediato (Antes de continuar)

1. **Verificar se mudanças estão em produção**
   - Verificar se arquivos foram deployados
   - Limpar cache do servidor/CDN
   - Re-testar após deploy

2. **Investigar CLS piorado**
   - Verificar se `contain: layout style` está causando problemas
   - Testar removendo `contain` de alguns containers
   - Verificar se `aspect-ratio` está conflitando

3. **Investigar "Forced reflow"**
   - Identificar qual script está causando
   - Otimizar ou adiar execução

### Continuar FASE 2 (LCP)

**Aguardar**: Resolver problema do CLS antes de continuar

---

## ⚠️ Notas Importantes

1. **CLS piorou** - Precisa investigar antes de continuar
2. **Speed Index melhorou** - Indica que renderização está melhor
3. **Mudanças podem não estar em produção** - Verificar deploy
4. **Novo problema**: "Forced reflow" - precisa investigar

---

## 🔄 Ação Imediata

**ANTES de continuar para FASE 2**:

1. Verificar se arquivos foram deployados
2. Limpar cache
3. Re-testar PageSpeed
4. Se CLS ainda estiver pior, reverter mudanças de `contain: layout style` e testar incrementalmente

