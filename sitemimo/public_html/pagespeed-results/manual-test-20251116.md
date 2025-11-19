# PageSpeed Insights - Teste Manual Completo (Produção)
**Data**: 2025-11-16  
**Método**: Web Interface Manual  
**Ambiente**: Produção (https://minhamimo.com.br)  
**Report ID**: bm7cuzovxw

---

## Homepage (/) - Mobile ⚠️

**URL**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/bm7cuzovxw?form_factor=mobile

### Scores
- **Performance**: 65 ⚠️
- **Accessibility**: 96 ✅
- **Best Practices**: 96 ✅
- **SEO**: 100 ✅

### Core Web Vitals
- **FCP** (First Contentful Paint): **0.9s** ✅ (meta: <1.8s mobile)
- **LCP** (Largest Contentful Paint): **3.3s** ⚠️ (meta: <2.5s)
- **TBT** (Total Blocking Time): **0ms** ✅
- **CLS** (Cumulative Layout Shift): **0.846** 🔴 (meta: <0.1) - **CRÍTICO**
- **SI** (Speed Index): **4.8s** ⚠️

### Principais Oportunidades (Insights)
1. **Improve image delivery** - Est savings of **1,022 KiB** 🔴
2. **Render blocking requests** - Est savings of **400ms** ⚠️
3. **Layout shift culprits** - Identificado 🔴
4. **LCP breakdown** - Identificado ⚠️
5. **Network dependency tree** - Identificado

### Diagnósticos
1. **Reduce unused CSS** - Est savings of **39 KiB**
2. **Minify CSS** - Est savings of **16 KiB**
3. **Avoid non-composited animations** - **86 animated elements found**
4. **Use efficient cache lifetimes** - Est savings of **21 KiB**
5. **3rd parties** - Identificado

### Problemas Críticos
- **CLS extremamente alto (0.846)** - precisa ser reduzido para <0.1
- **LCP acima do ideal (3.3s)** - precisa ser reduzido para <2.5s
- **Imagens não otimizadas (1,022 KiB)** - maior oportunidade de economia

---

## Homepage (/) - Desktop ✅

**URL**: https://pagespeed.web.dev/analysis/https-minhamimo-com-br/bm7cuzovxw?form_factor=desktop

### Scores
- **Performance**: 88 ✅
- **Accessibility**: 96 ✅
- **Best Practices**: 96 ✅
- **SEO**: 100 ✅

### Core Web Vitals
- **FCP** (First Contentful Paint): **0.7s** ✅ (meta: <1.0s desktop)
- **LCP** (Largest Contentful Paint): **0.9s** ✅ (meta: <2.5s)
- **TBT** (Total Blocking Time): **0ms** ✅
- **CLS** (Cumulative Layout Shift): **0.177** ⚠️ (meta: <0.1)
- **SI** (Speed Index): **1.9s** ✅

### Principais Oportunidades (Insights)
1. **Layout shift culprits** - Identificado ⚠️
2. **Forced reflow** - Identificado ⚠️
3. **Network dependency tree** - Identificado
4. **Use efficient cache lifetimes** - Est savings of **47 KiB**
5. **Improve image delivery** - Est savings of **422 KiB** ⚠️
6. **Render blocking requests** - Identificado
7. **LCP breakdown** - Identificado
8. **3rd parties** - Identificado

### Diagnósticos
1. **Minify CSS** - Est savings of **16 KiB**
2. **Reduce unused CSS** - Est savings of **35 KiB**
3. **Avoid enormous network payloads** - Total size was **3,214 KiB** ⚠️
4. **Avoid non-composited animations** - **106 animated elements found**
5. **Avoid long main-thread tasks** - **1 long task found**

### Problemas Identificados
- **CLS acima do ideal (0.177)** - precisa ser reduzido para <0.1
- **Network payload grande (3,214 KiB)** - considerar otimizações de assets
- **Imagens não otimizadas (422 KiB)** - oportunidade de economia

## Contato (/contato.php) - Mobile
*Aguardando teste...*

## Contato (/contato.php) - Desktop
*Aguardando teste...*

## Vagas (/vagas.php) - Mobile
*Aguardando teste...*

## Vagas (/vagas.php) - Desktop
*Aguardando teste...*

## Estética Facial (/esteticafacial/) - Mobile
*Aguardando teste...*

## Estética Facial (/esteticafacial/) - Desktop
*Aguardando teste...*

## Estética (/estetica/) - Mobile
*Aguardando teste...*

## Estética (/estetica/) - Desktop
*Aguardando teste...*

## Esmalteria (/esmalteria/) - Mobile
*Aguardando teste...*

## Esmalteria (/esmalteria/) - Desktop
*Aguardando teste...*

## Salão (/salao/) - Mobile
*Aguardando teste...*

## Salão (/salao/) - Desktop
*Aguardando teste...*

## Micropigmentação (/micropigmentacao/) - Mobile
*Aguardando teste...*

## Micropigmentação (/micropigmentacao/) - Desktop
*Aguardando teste...*

## Cílios (/cilios/) - Mobile
*Aguardando teste...*

## Cílios (/cilios/) - Desktop
*Aguardando teste...*

---

## Resumo Geral

### Performance Score Médio
- Mobile: **65** (1/9 páginas testadas) ⚠️
- Desktop: **88** (1/9 páginas testadas) ✅

### Principais Problemas Identificados

#### Homepage Mobile 🔴
1. **CLS extremamente alto**: **0.846** (meta: <0.1) 🔴 **CRÍTICO**
2. **LCP alto**: **3.3s** (meta: <2.5s) ⚠️
3. **FCP**: **0.9s** ✅ (melhorou de 2.6s)
4. **Imagens não otimizadas**: **1,022 KiB** de economia possível 🔴
5. **CSS não utilizado**: **39 KiB**
6. **Animações não compositadas**: **86 elementos**
7. **Render blocking requests**: **400ms** de economia possível

#### Homepage Desktop ⚠️
1. **CLS acima do ideal**: **0.177** (meta: <0.1) ⚠️
2. **FCP**: **0.7s** ✅ (meta: <1.0s desktop)
3. **LCP**: **0.9s** ✅ (meta: <2.5s)
4. **Imagens não otimizadas**: **422 KiB** de economia possível
5. **CSS não utilizado**: **35 KiB**
6. **Animações não compositadas**: **106 elementos**
7. **Network payload grande**: **3,214 KiB** total ⚠️
8. **Forced reflow**: Identificado ⚠️

### Análise Comparativa (vs. Teste Anterior)

#### Mobile
- **FCP**: Melhorou de 2.6s → **0.9s** ✅
- **LCP**: Melhorou de 4.2s → **3.3s** ✅
- **CLS**: Piorou de 0.332 → **0.846** 🔴 **REGRESSÃO CRÍTICA**
- **SI**: Piorou de 3.4s → **4.8s** ⚠️

#### Desktop
- **Performance Score**: Piorou de 92 → **88** ⚠️
- **FCP**: Manteve **0.7s** ✅
- **LCP**: Piorou de 0.7s → **0.9s** ⚠️
- **CLS**: Piorou de 0.144 → **0.177** ⚠️
- **SI**: Piorou de 1.5s → **1.9s** ⚠️

### Prioridades de Correção

#### 🔴 Crítico (Mobile)
1. **CLS (0.846)** - Reduzir para <0.1
   - Investigar layout shift culprits
   - Adicionar dimensões fixas para imagens
   - Prevenir mudanças de layout durante carregamento
2. **Otimização de imagens (1,022 KiB)** - Maior impacto
   - Converter para WebP/AVIF
   - Implementar lazy loading
   - Redimensionar imagens para viewport

#### ⚠️ Importante (Ambos)
1. **LCP Mobile (3.3s)** - Reduzir para <2.5s
2. **CLS Desktop (0.177)** - Reduzir para <0.1
3. **Render blocking requests (400ms mobile)** - Defer/async CSS crítico
4. **Network payload (3,214 KiB desktop)** - Otimizar assets

#### ✅ Manutenção
1. **CSS não utilizado (35-39 KiB)** - PurgeCSS
2. **Minify CSS (16 KiB)** - Ativar minificação
3. **Animações não compositadas** - Usar `transform` e `opacity`

### Próximos Passos
1. Continuar testes das demais páginas
2. Analisar padrões comuns entre páginas
3. Priorizar correções baseadas em impacto

---

## 📚 Documentação Relacionada

Para documentação completa do projeto, incluindo todas as otimizações implementadas, configurações, cores da marca, layout, animações, dark mode, e guia de recuperação, consulte:

- **[PROJECT-MASTER-DOCUMENTATION.md](../PROJECT-MASTER-DOCUMENTATION.md)** - Documentação master completa
- **[RECOVERY-GUIDE-MINIFY-BREAKAGE.md](../RECOVERY-GUIDE-MINIFY-BREAKAGE.md)** - Guia de recuperação quando minify quebra

