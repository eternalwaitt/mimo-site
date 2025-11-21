# Quick Start - Performance Optimization

Guia rápido para aplicar otimizações de performance em novos projetos usando o mesmo processo do site Mimo.

## 🎯 Para Novos Projetos

### Passo 1: Copie o Prompt Template

Abra o arquivo [`PERFORMANCE-PROMPT-TEMPLATE.md`](./PERFORMANCE-PROMPT-TEMPLATE.md) e copie o prompt completo.

Cole no início da conversa com Cursor/AI quando começar a trabalhar em performance.

### Passo 2: Execute o Processo

O prompt guia automaticamente através de:

1. ✅ Baseline local (build + Lighthouse)
2. ✅ Bundle analysis (identificar chunks)
3. ✅ Server/Client islands (refatorar componentes)
4. ✅ Reduzir JS (dynamic imports)
5. ✅ Otimizar LCP (imagens, preload)
6. ✅ Analytics (Plausible, não bloquear)
7. ✅ CI guardrails (scripts, workflows)
8. ✅ Documentação (relatórios, baseline)

### Passo 3: Valide Resultados

```bash
# Teste local
DISABLE_ANALYTICS=true npm run lighthouse:local

# Deve passar:
# - Performance ≥ 95
# - LCP < 2.5s
```

## 🔧 Para Projetos Existentes

### Se Performance Está Abaixo do Target

1. **Rode baseline**:
   ```bash
   npm run build
   npm run start
   DISABLE_ANALYTICS=true npm run lighthouse:local
   ```

2. **Identifique problemas**:
   - LCP alto? → Otimize hero image
   - Unused JS alto? → Bundle analysis + dynamic imports
   - TBT alto? → Reduza JS inicial, use server components

3. **Use o prompt template** como checklist:
   - Execute cada seção em ordem
   - Documente resultados em `docs/performance/PERFORMANCE-OPTIMIZATION-REPORT.md`

### Se Performance Está OK Mas Quer Manter

1. **Configure CI guardrails** (se ainda não tiver):
   - Script `lighthouse:local` que falha se Performance < 95 ou LCP > 2.5s
   - Workflow CI que roda Lighthouse em cada PR

2. **Documente performance budget**:
   - Crie `docs/performance/PERFORMANCE-GUIDE.md`
   - Defina metas e regras para novas páginas

3. **Monitore continuamente**:
   - Rode `npm run lighthouse:local` antes de merge
   - Revise bundle size com `ANALYZE=true npm run build`

## 📋 Checklist Rápido

Antes de considerar performance "pronta":

- [ ] Performance Score ≥ 95 (Lighthouse mobile)
- [ ] LCP < 2.5s
- [ ] FCP < 1.8s
- [ ] TBT < 200ms
- [ ] CLS < 0.1
- [ ] Unused JS < 60 KiB
- [ ] Elemento de LCP identificado e otimizado
- [ ] Bundle analyzer rodado
- [ ] CI guardrails configurados
- [ ] Performance budget documentado

## 🎓 Aprendizados do Site Mimo

### O Que Funcionou Bem

1. **Dynamic import do HeaderClient**
   - Reduziu bundle inicial em ~15-20 KB
   - Interatividade não bloqueia renderização

2. **Button sem `cn`**
   - Removida dependência pesada (clsx + tailwind-merge)
   - Redução de ~2-5 KB

3. **Server components + Client islands**
   - Estrutura estática no servidor
   - Apenas interatividade no cliente

4. **Plausible analytics**
   - Leve, não bloqueia FCP/LCP
   - Flag `DISABLE_ANALYTICS` para testes

### O Que Evitar

1. ❌ Importar client components diretamente no bundle inicial
2. ❌ Usar libs pesadas (framer-motion, etc) acima da dobra
3. ❌ Analytics bloqueando renderização (GA4, Clarity)
4. ❌ Imagens sem `priority`/`fetchPriority` no LCP
5. ❌ Componentes abaixo da dobra sem dynamic import

## 📚 Referências

- **Template de Prompt**: [`PERFORMANCE-PROMPT-TEMPLATE.md`](./PERFORMANCE-PROMPT-TEMPLATE.md)
- **Guia Completo**: [`PERFORMANCE-GUIDE.md`](./PERFORMANCE-GUIDE.md)
- **Relatório Mimo**: [`PERFORMANCE-OPTIMIZATION-REPORT.md`](./PERFORMANCE-OPTIMIZATION-REPORT.md)
- **Baseline Mimo**: [`../perf-baseline.md`](../perf-baseline.md)

---

**Dica**: Salve o prompt template como snippet ou bookmark para acesso rápido em novos projetos!

