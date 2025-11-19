# PageSpeed Insights - Páginas Principais (Após Otimizações)
**Data**: 2025-11-16 22:04:08
**Timestamp**: 20251116-215825

## Resumo Executivo

### Homepage (`/`)
- **Mobile**: Performance 59 (⚠️ piorou -5 pontos), CLS 0.760 (🔴 melhorou -0.093)
- **Desktop**: Performance 75 (⚠️ piorou -14 pontos), CLS 0.180 (⚠️ manteve)

### Contato (`/contato.php`)
- **Mobile**: Performance 70 (⚠️ melhorou +2 pontos), CLS 0.022 (✅ excelente)
- **Desktop**: Performance 98 (✅ excelente), CLS 0.003 (✅ excelente)

### Vagas (`/vagas.php`)
- **Mobile**: Performance 59 (⚠️ melhorou +5 pontos), CLS 0.438 (🔴 ainda alto)
- **Desktop**: Performance 77 (⚠️ melhorou -7 pontos), CLS 0.419 (🔴 ainda alto)

---

## Análise Detalhada

### Homepage (`/`)

#### MOBILE ⚠️
- **Performance**: 59 (📉 -5 vs anterior: 64)
- **FCP**: 2.56s
- **LCP**: 3.98s (⚠️ acima do ideal de 2.5s)
- **CLS**: 0.760 🔴 (📉 -0.093 vs anterior: 0.853) - **Melhorou, mas ainda crítico**
- **TBT**: 0ms ✅

**Observações**:
- CLS melhorou ligeiramente (-0.093), mas ainda está muito alto
- Performance piorou, possivelmente devido a mudanças não deployadas ou variação natural
- LCP está alto (3.98s), precisa investigar

#### DESKTOP ⚠️
- **Performance**: 75 (📉 -14 vs anterior: 89) - **Regressão significativa**
- **FCP**: 0.27s ✅
- **LCP**: 2.56s (⚠️ acima do ideal de 2.5s)
- **CLS**: 0.180 ⚠️ (📈 +0.000 vs anterior: 0.180) - **Manteve**
- **TBT**: 0ms ✅

**Observações**:
- Performance regrediu significativamente (-14 pontos)
- Possível causa: mudanças não deployadas em produção ou variação natural do PageSpeed
- CLS manteve, mas ainda precisa melhorar

### Contato (`/contato.php`)

#### MOBILE ⚠️
- **Performance**: 70 (📈 +2 vs anterior: 68)
- **FCP**: 2.56s
- **LCP**: 6.63s (🔴 muito alto)
- **CLS**: 0.022 ✅ (📈 +0.022 vs anterior: 0.000) - **Ainda excelente**
- **TBT**: 18ms ✅

**Observações**:
- CLS continua excelente (0.022)
- Performance melhorou ligeiramente
- LCP está muito alto (6.63s), precisa investigar

#### DESKTOP ✅
- **Performance**: 98 (📈 +6 vs anterior: 92) - **Excelente!**
- **FCP**: 0.69s ✅
- **LCP**: 0.99s ✅
- **CLS**: 0.003 ✅ (📈 +0.000 vs anterior: 0.003) - **Excelente!**
- **TBT**: 0ms ✅

**Observações**:
- Performance excelente (98)
- CLS excelente (0.003)
- Página está otimizada

### Vagas (`/vagas.php`)

#### MOBILE ⚠️
- **Performance**: 59 (📈 +5 vs anterior: 54)
- **FCP**: 0.77s ✅
- **LCP**: 5.79s (🔴 muito alto)
- **CLS**: 0.438 🔴 (📉 -0.520 vs anterior: 0.958) - **Melhorou significativamente!**
- **TBT**: 19ms ✅

**Observações**:
- CLS melhorou muito (-0.520), mas ainda está alto
- Performance melhorou (+5 pontos)
- LCP está muito alto (5.79s), precisa investigar

#### DESKTOP ⚠️
- **Performance**: 77 (📉 -7 vs anterior: 84)
- **FCP**: 0.24s ✅
- **LCP**: 1.33s ✅
- **CLS**: 0.419 🔴 (📈 +0.177 vs anterior: 0.242) - **Piorou**
- **TBT**: 68ms ✅

**Observações**:
- CLS piorou em desktop (+0.177)
- Performance piorou (-7 pontos)
- Precisa investigar causas

---

## Comparação com Resultados Anteriores

### Homepage
| Métrica | Mobile Antes | Mobile Agora | Desktop Antes | Desktop Agora |
|---------|-------------|-------------|---------------|---------------|
| Performance | 64 | 59 (-5) | 89 | 75 (-14) |
| CLS | 0.853 | 0.760 (-0.093) | 0.180 | 0.180 (0) |

### Contato
| Métrica | Mobile Antes | Mobile Agora | Desktop Antes | Desktop Agora |
|---------|-------------|-------------|---------------|---------------|
| Performance | 68 | 70 (+2) | 92 | 98 (+6) |
| CLS | 0.000 | 0.022 (+0.022) | 0.003 | 0.003 (0) |

### Vagas
| Métrica | Mobile Antes | Mobile Agora | Desktop Antes | Desktop Agora |
|---------|-------------|-------------|---------------|---------------|
| Performance | 54 | 59 (+5) | 84 | 77 (-7) |
| CLS | 0.958 | 0.438 (-0.520) | 0.242 | 0.419 (+0.177) |

---

## Problemas Identificados

### 1. Homepage Desktop - Regressão de Performance
- **Problema**: Performance caiu de 89 para 75 (-14 pontos)
- **Possíveis causas**:
  - Mudanças não deployadas em produção
  - Variação natural do PageSpeed Insights
  - Mudanças no carregamento de CSS (preload + onload pode ter impacto)
- **Ação**: Verificar se mudanças foram deployadas

### 2. Homepage Mobile - CLS Ainda Alto
- **Problema**: CLS 0.760 (ainda muito alto, ideal <0.1)
- **Progresso**: Melhorou de 0.853 para 0.760 (-0.093)
- **Ação**: Continuar investigando elementos causando layout shift

### 3. Vagas Desktop - CLS Piorou
- **Problema**: CLS aumentou de 0.242 para 0.419 (+0.177)
- **Ação**: Investigar o que causou a piora

### 4. LCP Alto em Várias Páginas
- **Problema**: LCP > 2.5s em várias páginas
- **Ações**:
  - Otimizar imagens LCP
  - Verificar se preload está funcionando
  - Considerar inlining CSS crítico

---

## Próximos Passos

### Prioridade 1 (Crítico)
1. **Investigar regressão homepage desktop** (89 → 75)
   - Verificar se mudanças foram deployadas
   - Comparar com versão anterior
   - Testar localmente

2. **Continuar reduzindo CLS homepage mobile** (0.760 → <0.1)
   - Usar Chrome DevTools para identificar elementos causando shift
   - Adicionar min-height em containers dinâmicos
   - Verificar imagens sem dimensões

3. **Investigar CLS vagas desktop** (0.242 → 0.419)
   - Verificar o que mudou
   - Testar localmente

### Prioridade 2 (Alto)
4. **Otimizar LCP** em páginas com LCP > 2.5s
   - Homepage mobile: 3.98s
   - Contato mobile: 6.63s
   - Vagas mobile: 5.79s

5. **Validar otimizações de CSS** (preload + onload)
   - Verificar se estão funcionando corretamente
   - Testar impacto no FCP

### Prioridade 3 (Médio)
6. **Documentar variações** do PageSpeed Insights
7. **Criar baseline** de resultados para comparação futura

---

## Notas Técnicas

- **Variação Natural**: PageSpeed Insights pode variar ±5-10 pontos entre execuções
- **Deploy**: Verificar se mudanças foram deployadas em produção
- **Cache**: Resultados podem ser afetados por cache do servidor/CDN
- **Timing**: Resultados podem variar baseado em carga do servidor

---

## Conclusão

As otimizações aplicadas (preload + onload para Google Fonts e Bootstrap, defer dark-mode.css) mostraram resultados mistos:

✅ **Sucessos**:
- CLS homepage mobile melhorou (-0.093)
- CLS vagas mobile melhorou significativamente (-0.520)
- Contato desktop excelente (98 performance, 0.003 CLS)

⚠️ **Problemas**:
- Homepage desktop regrediu (-14 pontos)
- CLS ainda alto em várias páginas
- LCP alto em várias páginas

**Recomendação**: Continuar investigando e aplicando otimizações, focando em CLS e LCP.

