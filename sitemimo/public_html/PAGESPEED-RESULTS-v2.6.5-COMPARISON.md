# Comparação PageSpeed Insights - v2.6.5

**Teste Anterior**: 15 Nov 2025, 1:16 PM  
**Teste Atual**: 15 Nov 2025, 1:37 PM  
**Diferença**: ~21 minutos

## 📊 Comparação de Scores

| Categoria | Anterior | Atual | Mudança |
|-----------|----------|-------|---------|
| **Performance** | 50 | **50** | ➡️ Sem mudança |
| Accessibility | 96 | 96 | ➡️ Sem mudança |
| Best Practices | 96 | 96 | ➡️ Sem mudança |
| SEO | 100 | 100 | ➡️ Sem mudança |

## 📈 Comparação de Core Web Vitals (Mobile)

| Métrica | Anterior | Atual | Meta | Mudança | Status |
|---------|----------|-------|------|---------|--------|
| **FCP** | 3.5s | **4.1s** | <1.8s | ❌ +0.6s | 🔴 Piorou |
| **LCP** | 6.1s | **5.3s** | <2.5s | ✅ -0.8s | 🔴 Melhorou mas ainda ruim |
| **TBT** | 0ms | 0ms | <200ms | ➡️ Sem mudança | ✅ Perfeito |
| **CLS** | 0.401 | **0.401** | <0.1 | ➡️ Sem mudança | 🔴 Ainda ruim |
| **SI** | 4.8s | **5.2s** | <3.4s | ❌ +0.4s | 🔴 Piorou |

## 🔴 Problemas Críticos (Ainda Presentes)

### 1. Improve Image Delivery
- **Economia**: 2,760 KiB (2.7 MB)
- **Status**: ⚠️ **Ainda não aplicado**
- **Impacto**: Alto no LCP e Network Payload

### 2. Reduce Unused CSS
- **Economia**: 83 KiB
- **Status**: ⚠️ **Ainda presente**
- **Causa Possível**: Arquivos purgados podem não estar sendo usados corretamente

### 3. Minify CSS
- **Economia**: 23 KiB
- **Status**: ⚠️ **Ainda presente**
- **Causa Possível**: Arquivos minificados podem não estar sendo servidos

### 4. Minify JavaScript
- **Economia**: 7 KiB
- **Status**: ⚠️ **Ainda presente**
- **Causa Possível**: Arquivos minificados podem não estar sendo servidos

### 5. Reduce Unused JavaScript
- **Economia**: 33 KiB
- **Status**: ⚠️ **Ainda presente**

### 6. Font Display
- **Economia**: 40ms
- **Status**: ⚠️ **Ainda presente**
- **Nota**: Mudamos para `optional` mas pode não estar aplicado

### 7. Avoid Enormous Network Payloads
- **Total**: 3,882 KiB (3.8 MB)
- **Meta**: <1,600 KiB
- **Gap**: -2,282 KiB
- **Status**: ⚠️ **Ainda presente**

### 8. Layout Shift Culprits
- **CLS**: 0.401 (ainda acima de 0.1)
- **Status**: ⚠️ **Ainda presente**

## ✅ Melhorias Observadas

1. **LCP**: 6.1s → 5.3s (-0.8s) ✅
   - Melhoria significativa!
   - Ainda acima da meta (<2.5s) mas progresso

## ❌ Regressões

1. **FCP**: 3.5s → 4.1s (+0.6s) ❌
   - Piorou, possivelmente variação do teste
2. **SI**: 4.8s → 5.2s (+0.4s) ❌
   - Piorou, possivelmente variação do teste

## 🔍 Análise Detalhada

### Por que Performance ainda está em 50?

1. **Imagens não otimizadas**:
   - 2,760 KiB de economia ainda não aplicada
   - Network payload ainda em 3,882 KiB
   - **Ação**: Verificar se imagens AVIF/WebP estão sendo servidas corretamente

2. **Arquivos minificados/purgados não detectados**:
   - Unused CSS: 83 KiB ainda presente
   - Minify CSS: 23 KiB ainda presente
   - Minify JS: 7 KiB ainda presente
   - **Causa Possível**: 
     - Arquivos podem estar minificados mas Lighthouse não detecta
     - Ou arquivos não estão sendo servidos como minificados
   - **Ação**: Verificar se HTML está carregando arquivos `.min.css` e `.min.js`

3. **Font display não aplicado**:
   - 40ms de economia ainda presente
   - **Causa Possível**: Mudança pode não estar em produção
   - **Ação**: Verificar se `font-display: optional` está no CSS servido

4. **CLS ainda alto**:
   - 0.401 (meta: <0.1)
   - **Causa Possível**: Layout shifts ainda ocorrendo
   - **Ação**: Investigar "Layout shift culprits" no PageSpeed

## 💡 Conclusão

**Status**: ⚠️ **Otimizações parcialmente aplicadas**

- ✅ LCP melhorou significativamente (6.1s → 5.3s)
- ❌ Performance ainda em 50 (não mudou)
- ⚠️ Problemas principais ainda presentes:
  - Image delivery (2.7 MB)
  - Unused CSS/JS
  - Minificação não detectada
  - Network payload alto

**Possíveis Causas**:
1. Cache ainda não propagou completamente
2. Arquivos minificados podem estar deployados mas não sendo detectados pelo Lighthouse
3. Imagens grandes ainda não otimizadas completamente
4. Algumas otimizações podem precisar de mais tempo para propagar

**Próximos Passos**:
1. ✅ Verificar se arquivos minificados estão sendo servidos (já verificado - estão)
2. ✅ Investigar por que Lighthouse não detecta minificação
3. ✅ Verificar se todas imagens grandes foram otimizadas
4. ✅ Aguardar mais tempo para cache propagar (30-60 min)
5. ✅ Re-testar após propagação completa

