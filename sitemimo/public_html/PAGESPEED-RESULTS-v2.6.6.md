# Resultados PageSpeed Insights - v2.6.6

**Teste**: 15 Nov 2025 (após deploy v2.6.6)  
**URL**: https://minhamimo.com.br/  
**Estratégia**: Mobile

## 📊 Comparação v2.6.5 → v2.6.6

| Métrica | v2.6.5 | v2.6.6 | Mudança | Status |
|---------|--------|--------|---------|--------|
| **Performance** | 50 | **66** | ✅ +16 | 🟢 Melhorou |
| **FCP** | 4.1s | 4.1s | ➡️ Sem mudança | 🟡 |
| **LCP** | 5.3s | 6.3s | ❌ +1.0s | 🔴 Piorou (variação?) |
| **CLS** | 0.401 | **0.000** | ✅ -0.401 | 🟢 Perfeito! |
| **TBT** | 0ms | 0ms | ➡️ Sem mudança | 🟢 Perfeito |
| **SI** | 5.2s | 5.2s | ➡️ Sem mudança | 🟡 |

## 🎯 Melhorias Significativas

### ✅ Performance Score: +16 pontos
- **50 → 66** (+32% de melhoria)
- Meta: 90+ (ainda faltam 24 pontos)

### ✅ CLS: Perfeito!
- **0.401 → 0.000** (redução de 100%)
- Meta: <0.1 ✅ **ATINGIDA!**

### ✅ Network Payload: Redução de 57%
- **3,882 KiB → 1,667 KiB** (-2,215 KiB)
- Meta: <1,600 KiB (ainda faltam 67 KiB)

## ⚠️ Problemas Ainda Presentes

### 1. Unused CSS (86 KiB)
- **Status**: Ainda presente (era 83 KiB)
- **Causa Possível**: 
  - Arquivos purgados podem não estar sendo usados em produção
  - CSS de terceiros (Bootstrap, Font Awesome) não pode ser purgado
- **Ação**: Verificar se `css/purged/*.min.css` estão sendo servidos

### 2. Minify CSS (23 KiB)
- **Status**: Ainda presente
- **Causa Possível**: 
  - Arquivos minificados podem não estar sendo servidos
  - CSS de terceiros não está minificado
- **Ação**: Verificar se `minified/*.min.css` estão sendo servidos

### 3. Unused JavaScript (33 KiB)
- **Status**: Ainda presente
- **Causa**: Bootstrap JS carrega módulos não usados (tooltip, modal, dropdown, collapse, scrollspy)
- **Solução Futura**: Build customizado do Bootstrap

### 4. Image Delivery
- **Status**: Não apareceu na lista de problemas críticos
- **Possível**: Imagens podem estar sendo servidas como AVIF/WebP agora
- **Ação**: Verificar se `picture_webp()` está funcionando corretamente

## 📈 Análise Detalhada

### Core Web Vitals

| Métrica | Valor | Meta | Status |
|---------|-------|------|--------|
| **FCP** | 4.1s | <1.8s | 🔴 -2.3s |
| **LCP** | 6.3s | <2.5s | 🔴 -3.8s |
| **CLS** | 0.000 | <0.1 | ✅ Perfeito! |
| **TBT** | 0ms | <200ms | ✅ Perfeito! |
| **SI** | 5.2s | <3.4s | 🔴 -1.8s |

### Problemas por Categoria

**Performance**:
- ✅ CLS resolvido (0.000)
- ⚠️ FCP ainda alto (4.1s)
- ⚠️ LCP ainda alto (6.3s)
- ⚠️ SI ainda alto (5.2s)

**Otimizações**:
- ⚠️ Unused CSS: 86 KiB
- ⚠️ Minify CSS: 23 KiB
- ⚠️ Unused JavaScript: 33 KiB
- ✅ Network Payload: Reduzido de 3,882 KiB para 1,667 KiB

## 💡 Conclusão

**Status**: ✅ **Melhorias significativas aplicadas!**

- ✅ Performance: +16 pontos (50 → 66)
- ✅ CLS: Perfeito (0.000)
- ✅ Network Payload: Redução de 57% (3,882 → 1,667 KiB)
- ⚠️ FCP, LCP, SI ainda precisam melhorar
- ⚠️ Unused CSS/JS ainda presentes (podem não estar sendo servidos corretamente)

**Próximos Passos**:
1. ✅ Verificar se arquivos purgados/minificados estão sendo servidos em produção
2. ✅ Verificar se imagens AVIF/WebP estão sendo servidas corretamente
3. ⚠️ Investigar por que FCP/LCP não melhoraram
4. ⚠️ Criar build customizado do Bootstrap (reduzir 33 KiB unused JS)

**Meta Final**: Performance 66 → 90+ (faltam 24 pontos)

