# Performance Plan - Mobile ≥95 e LCP <2.5s

**Data**: 2025-11-21  
**Baseline**: Performance 82/100, LCP 4.13s  
**Target**: Performance ≥95/100, LCP <2.5s  
**Referência**: `docs/lighthouse-baseline.md`

---

## 🎯 Performance Budget

### Limites Definidos

| Métrica | Atual | Target | Redução Necessária |
|---------|-------|--------|-------------------|
| First Load JS (home) | 125 kB | ≤100 kB | **-25 kB** |
| Unused JavaScript | 59 KiB | ≤20 KiB | **-39 KiB** |
| LCP Mobile | 4.13s | <2.5s | **-1.63s** |
| Performance Score | 82/100 | ≥95/100 | **+13 pontos** |

### LCP Element Target

- **Elemento**: Hero image (`hero-bg-mobile.webp`)
- **Tamanho máximo**: 30 KB (já está em 28 KB ✅)
- **Formato**: WebP (já otimizado ✅)
- **Prioridade**: Máxima (`priority`, `fetchPriority="high"`)

---

## 📋 Plano de Ação por Fases

### Fase 1: Remover Bloqueios de LCP (Impacto Alto, Sem Mudanças Visuais)

**Objetivo**: Reduzir LCP de 4.13s para <2.5s  
**Prioridade**: 🔴 CRÍTICA

#### 1.1 Remover ImageWithFallback do LCP Element

**Problema**: `ImageWithFallback` é client component, bloqueia renderização do LCP.

**Ação**:
- Substituir `ImageWithFallback` por `next/image` direto no hero
- Manter `ImageWithFallback` apenas para imagens abaixo da dobra
- Remover lógica de fallback do LCP (não é necessário para hero image)

**Arquivos**:
- `components/sections/hero-manifesto.tsx`
- `components/ui/image-with-fallback.tsx` (manter para outros usos)

**Impacto esperado**: LCP -1.0s a -1.5s (de 4.13s para ~2.5-3.0s)

**Risco**: Baixo (sem mudança visual)

---

#### 1.2 Otimizar Preload da Hero Image

**Problema**: Preload com media queries pode estar causando delay.

**Ação**:
- Simplificar preload: apenas mobile (sem media query condicional)
- Garantir que preload acontece antes de qualquer JS
- Verificar ordem no `<head>`

**Arquivos**:
- `app/layout.tsx`

**Impacto esperado**: LCP -0.2s a -0.5s

**Risco**: Baixo

---

#### 1.3 Converter Header para Server Component (Parcial)

**Problema**: Header é client component completo, carrega ~15-20 kB de JS no bundle inicial.

**Ação**:
- Criar `HeaderClient` apenas para interatividade (scroll, menu mobile)
- Manter estrutura estática no server component
- Code-split menu mobile (carregar apenas quando necessário)

**Arquivos**:
- `components/layout/header.tsx` → `components/layout/header-server.tsx` + `components/layout/header-client.tsx`

**Impacto esperado**: 
- First Load JS -10 kB a -15 kB
- LCP -0.2s a -0.3s (menos JS bloqueando)

**Risco**: Médio (requer refatoração cuidadosa)

---

### Fase 2: Reduzir JavaScript Não Utilizado (Impacto Alto)

**Objetivo**: Reduzir unused JS de 59 KiB para <20 KiB  
**Prioridade**: 🔴 CRÍTICA

#### 2.1 Analisar Bundle Analyzer em Detalhes

**Ação**:
- Abrir `.next/analyze/client.html` após build
- Identificar exatamente quais módulos estão no bundle
- Mapear cada módulo grande para seu uso real

**Arquivos**:
- Documentar em `docs/bundle-analysis.md`

**Impacto**: Diagnóstico necessário antes de otimizar

---

#### 2.2 Otimizar Imports e Tree-Shaking

**Ação**:
- Verificar se todos os imports são necessários
- Usar named imports quando possível (não `import *`)
- Verificar se `clsx`, `tailwind-merge` estão sendo tree-shaken corretamente

**Arquivos**:
- Todos os componentes client

**Impacto esperado**: Unused JS -10 KiB a -15 KiB

**Risco**: Baixo

---

#### 2.3 Remover Dependências Não Utilizadas

**Ação**:
- Verificar se `framer-motion` está no bundle inicial (não deveria estar)
- Verificar outras dependências grandes
- Remover ou substituir por alternativas menores

**Arquivos**:
- `package.json`
- Verificar imports de framer-motion

**Impacto esperado**: Unused JS -5 KiB a -10 KiB

**Risco**: Baixo (framer-motion já está code-split)

---

#### 2.4 Otimizar Analytics Loading

**Ação**:
- Carregar `AnalyticsProvider` apenas se GA estiver configurado
- Defer analytics scripts (já feito, verificar se está funcionando)
- Remover analytics do bundle inicial se possível

**Arquivos**:
- `components/analytics-provider.tsx`
- `app/layout.tsx`

**Impacto esperado**: First Load JS -3 kB a -5 kB

**Risco**: Baixo

---

### Fase 3: Otimizações Finais (Impacto Médio)

**Objetivo**: Ajustes finos para atingir ≥95  
**Prioridade**: 🟡 MÉDIA

#### 3.1 Otimizar Font Loading

**Ação**:
- Verificar se fontes estão bloqueando renderização
- Garantir `display: 'optional'` (já configurado)
- Preload apenas fonte crítica (Bueno para hero)

**Arquivos**:
- `app/layout.tsx`

**Impacto esperado**: FCP -0.1s a -0.2s

**Risco**: Baixo

---

#### 3.2 Otimizar CSS

**Ação**:
- Verificar se há CSS não utilizado
- Garantir que Tailwind está purgando corretamente
- Verificar se há CSS crítico inline

**Impacto esperado**: Performance +1 a +2 pontos

**Risco**: Baixo

---

#### 3.3 Verificar Resource Hints

**Ação**:
- Manter apenas preconnect crítico (já otimizado)
- Remover dns-prefetch desnecessários
- Garantir ordem correta no `<head>`

**Impacto esperado**: LCP -0.1s a -0.2s

**Risco**: Baixo

---

## 🎯 Critérios de Sucesso por Fase

### Fase 1 (LCP <2.5s)
- ✅ LCP mobile < 2.5s
- ✅ Performance ≥ 90/100
- ✅ Sem regressões visuais

### Fase 2 (Unused JS <20 KiB)
- ✅ Unused JavaScript < 20 KiB
- ✅ First Load JS < 100 kB
- ✅ Performance ≥ 93/100

### Fase 3 (Performance ≥95)
- ✅ Performance mobile ≥ 95/100
- ✅ LCP < 2.5s
- ✅ Todos os Core Web Vitals no verde

---

## 📊 Métricas de Acompanhamento

Após cada fase, documentar:

1. **Lighthouse Score** (mobile)
   - Performance, LCP, FCP, TBT, CLS

2. **Bundle Size**
   - First Load JS
   - Unused JavaScript
   - Tamanho dos chunks principais

3. **LCP Element**
   - Qual elemento está sendo medido como LCP
   - Tempo de carregamento
   - Tamanho do arquivo

4. **Regressões**
   - Verificar se não quebrou nada visualmente
   - Verificar se não piorou outras métricas

---

## ⚠️ Riscos e Mitigações

### Risco 1: Quebrar Funcionalidade Visual
- **Mitigação**: Testar em dev antes de deploy
- **Rollback**: Manter branch anterior

### Risco 2: Piorar LCP ao Otimizar JS
- **Mitigação**: Fase 1 primeiro (LCP), depois Fase 2 (JS)
- **Monitoramento**: Lighthouse após cada mudança

### Risco 3: Analytics Parar de Funcionar
- **Mitigação**: Testar analytics após otimizações
- **Verificação**: Plausible Analytics dashboard

---

## 🚀 Ordem de Implementação

1. **Fase 1.1** - Remover ImageWithFallback do LCP (maior impacto)
2. **Fase 1.2** - Otimizar preload
3. **Teste** - Lighthouse para verificar LCP <2.5s
4. **Fase 1.3** - Otimizar Header (se LCP ainda não estiver OK)
5. **Fase 2** - Reduzir unused JS
6. **Fase 3** - Ajustes finos

**Regra**: Não avançar para próxima fase sem atingir critérios de sucesso da fase atual.

---

## 📝 Notas de Implementação

- Sempre testar com `npm run build && npm run start` antes de deploy
- Usar `npm run lighthouse:home` para validar métricas
- Documentar resultados em `docs/lighthouse/` após cada fase
- Não fazer múltiplas mudanças de uma vez (isolar impacto)

