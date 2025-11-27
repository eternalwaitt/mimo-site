# PageSpeed Insights - Páginas Principais (Após Verificação CLS)
**Data**: 2025-11-16 21:45:56
**ASSET_VERSION**: 20251116-95

## Resumo Executivo

### Performance Score
- **Homepage (/)**: Mobile 63 ⚠️ | Desktop 76 ⚠️
- **Contato**: Mobile 71 ⚠️ | Desktop 95 ✅
- **FAQ**: Mobile 88 ⚠️ | Desktop 99 ✅
- **Vagas**: Mobile 54 ⚠️ | Desktop 83 ⚠️

### CLS (Cumulative Layout Shift)
- **Homepage (/)**: Mobile 0.853 🔴 | Desktop 0.173 ⚠️
- **Contato**: Mobile 0.019 ✅ | Desktop 0.004 ✅
- **FAQ**: Mobile 0.000 ✅ | Desktop 0.001 ✅
- **Vagas**: Mobile 0.958 🔴 | Desktop 0.241 ⚠️

## Comparação com Teste Anterior

### Homepage (/)
- **Mobile**:
  - Performance: 64 → 63 (📉 -1)
  - CLS: 0.774 → 0.853 (📈 +0.079) ⚠️ **PIOROU**
- **Desktop**:
  - Performance: 89 → 76 (📉 -13) ⚠️ **PIOROU SIGNIFICATIVAMENTE**
  - CLS: 0.180 → 0.173 (📉 -0.007) ✅ Melhorou ligeiramente

### Vagas
- **Mobile**:
  - Performance: 54 → 54 (➡️ sem mudança)
  - CLS: 0.730 → 0.958 (📈 +0.228) ⚠️ **PIOROU SIGNIFICATIVAMENTE**
- **Desktop**:
  - Performance: 84 → 83 (📉 -1)
  - CLS: 0.242 → 0.241 (📉 -0.001) ✅ Melhorou ligeiramente

## Análise

### ⚠️ Problemas Identificados

1. **CLS Homepage Mobile piorou** (0.774 → 0.853)
   - Possíveis causas:
     - Mudanças ainda não deployadas em produção
     - Variação natural do PageSpeed Insights
     - Outros elementos causando layout shift não identificados

2. **CLS Vagas Mobile piorou significativamente** (0.730 → 0.958)
   - Possíveis causas:
     - Conteúdo dinâmico carregando após renderização inicial
     - Imagens sem dimensões explícitas
     - Fontes carregando e causando reflow

3. **Performance Desktop Homepage piorou** (89 → 76)
   - Possíveis causas:
     - LCP aumentou (0.74s → 2.52s) ⚠️
     - Outros fatores de performance não relacionados a CLS

### ✅ Pontos Positivos

1. **Contato e FAQ mantêm CLS excelente** (<0.1)
2. **Desktop CLS melhorou ligeiramente** na homepage e vagas
3. **FAQ tem performance excelente** (99 desktop, 88 mobile)

## Próximos Passos

1. **Investigar CLS Homepage Mobile**:
   - Usar Chrome DevTools Performance tab para identificar elementos causando shift
   - Verificar se há conteúdo inserido dinamicamente via JavaScript
   - Verificar se todas as imagens têm width/height explícitos

2. **Investigar CLS Vagas Mobile**:
   - Verificar se há conteúdo dinâmico (vagas carregadas via JS?)
   - Verificar dimensões de imagens
   - Verificar fontes e font-display

3. **Investigar LCP Desktop Homepage**:
   - Verificar se LCP image está sendo carregada corretamente
   - Verificar fetchpriority e preload

4. **Validar que mudanças foram deployadas**:
   - Verificar ASSET_VERSION em produção
   - Verificar se CSS/JS atualizados estão sendo servidos

## Notas

- As correções de CLS verificadas foram apenas confirmações de código existente
- Nenhuma mudança real foi feita além de atualizar ASSET_VERSION
- O aumento do CLS pode ser devido a:
  - Variação natural do PageSpeed Insights
  - Mudanças não deployadas ainda
  - Outros fatores não relacionados às correções verificadas

## Referências

- Análise anterior: `pagespeed-results/api-results-20251116-212541.md`
- Correções aplicadas: `backups/20251116/CLS-FIXES-APPLIED.md`
- Plano: `css-layout-fixes.plan.md`

