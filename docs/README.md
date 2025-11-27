# Documentação do Projeto Mimo Site

Este diretório contém toda a documentação técnica do projeto, organizada por categoria.

## Estrutura

```
docs/
├── README.md (este arquivo)
├── CODE-REVIEW-REPORT.md
├── PROGRESS-TRACKING.md
├── EXECUTIVE-SUMMARY.md
├── IMPLEMENTATION-SUMMARY.md
├── PENDING-TASKS.md
├── IMAGE-SOURCES.md
├── IMAGE-STRATEGY.md
├── IMAGE-AUDIT.md
├── BROWSER-TEST-RESULTS.md
├── FINAL-TEST-REPORT.md
├── SECURITY-API-KEY-EXPOSED.md
├── performance/          # Documentação de performance
│   ├── PERFORMANCE-SUMMARY.md
│   ├── PERFORMANCE-GUIDE.md
│   ├── PERFORMANCE-CHECKLIST.md
│   ├── performance-guide-mimo.md
│   ├── PERFORMANCE-OPTIMIZATION-RESULTS.md
│   ├── PERFORMANCE-FIXES-APPLIED.md
│   ├── PERFORMANCE-LOCAL-ANALYSIS.md
│   ├── PERFORMANCE-MOBILE-ANALYSIS.md
│   ├── PERFORMANCE-OPTIMIZATION-PLAN.md
│   ├── lighthouse-*.md
│   ├── lighthouse/      # Relatórios JSON do Lighthouse
│   └── *.json           # Relatórios de performance
├── ux/                  # Documentação UX/UI
│   ├── UX-AUDIT.md
│   ├── UX-IMPROVEMENTS.md
│   └── MOBILE-UX-RESEARCH.md
└── guides/              # Guias e comparações
    ├── ADDING-NEW-PAGES.md
    ├── ANALYTICS-SETUP.md
    ├── TECH-STACK-GUIDE.md
    ├── TECH-REFERENCES.md
    ├── TECH-STACK-ALTERNATIVES.md
    ├── DATA-FORMATS-COMPARISON.md
    ├── CSS-FRAMEWORKS-COMPARISON.md
    ├── TOOL-EVALUATION.md
    └── templates/       # Templates de código
```

## Índice de Documentos

### Documentos Principais
- **[EXECUTIVE-SUMMARY.md](./EXECUTIVE-SUMMARY.md)** - Resumo executivo do projeto
- **[IMPLEMENTATION-SUMMARY.md](./IMPLEMENTATION-SUMMARY.md)** - Resumo de implementações
- **[CODE-REVIEW-REPORT.md](./CODE-REVIEW-REPORT.md)** - Relatório completo de revisão de código
- **[PROGRESS-TRACKING.md](./PROGRESS-TRACKING.md)** - Acompanhamento de progresso das fases
- **[PENDING-TASKS.md](./PENDING-TASKS.md)** - Tarefas pendentes

### Imagens
- **[IMAGE-SOURCES.md](./IMAGE-SOURCES.md)** - Pesquisa de fontes de imagens (Unsplash, Pexels, etc)
- **[IMAGE-STRATEGY.md](./IMAGE-STRATEGY.md)** - Estratégia 100% local para imagens
- **[IMAGE-AUDIT.md](./IMAGE-AUDIT.md)** - Auditoria de imagens faltantes

### Performance
- **[performance/PERFORMANCE-SUMMARY.md](./performance/PERFORMANCE-SUMMARY.md)** - Resumo de otimizações
- **[performance/PERFORMANCE-GUIDE.md](./performance/PERFORMANCE-GUIDE.md)** - Guia completo de performance
- **[performance/PERFORMANCE-CHECKLIST.md](./performance/PERFORMANCE-CHECKLIST.md)** - Checklist de performance
- **[performance/performance-guide-mimo.md](./performance/performance-guide-mimo.md)** - Guia específico do projeto
- Relatórios em `performance/lighthouse/`

### UX/UI
- **[ux/UX-AUDIT.md](./ux/UX-AUDIT.md)** - Auditoria UX/UI atual do site
- **[ux/UX-IMPROVEMENTS.md](./ux/UX-IMPROVEMENTS.md)** - Lista priorizada de melhorias UX/UI
- **[ux/MOBILE-UX-RESEARCH.md](./ux/MOBILE-UX-RESEARCH.md)** - Pesquisa de práticas UX/UI mobile 2025

### Guias
- **[guides/ADDING-NEW-PAGES.md](./guides/ADDING-NEW-PAGES.md)** - Como adicionar novas páginas
- **[guides/ANALYTICS-SETUP.md](./guides/ANALYTICS-SETUP.md)** - Configuração de analytics (Plausible)
- **[guides/TECH-STACK-GUIDE.md](./guides/TECH-STACK-GUIDE.md)** - Guia da stack tecnológica
- **[guides/TECH-REFERENCES.md](./guides/TECH-REFERENCES.md)** - Referências rápidas (Next.js, React, TypeScript, Tailwind)
- **[guides/TECH-STACK-ALTERNATIVES.md](./guides/TECH-STACK-ALTERNATIVES.md)** - Comparação de tecnologias
- **[guides/DATA-FORMATS-COMPARISON.md](./guides/DATA-FORMATS-COMPARISON.md)** - Comparação de formatos de dados
- **[guides/CSS-FRAMEWORKS-COMPARISON.md](./guides/CSS-FRAMEWORKS-COMPARISON.md)** - Comparação de frameworks CSS
- **[guides/TOOL-EVALUATION.md](./guides/TOOL-EVALUATION.md)** - Avaliação de ferramentas
- **[guides/templates/](./guides/templates/)** - Templates de código

## Como Usar

### Executar Análise de Performance
```bash
npm run pagespeed
```

### Otimizar Imagens
```bash
# Instalar sharp primeiro
npm install sharp

# Otimizar todas as imagens
npm run optimize-images

# Ou otimizar diretório específico
node scripts/optimize-images.js public/images/servicos
```

### Verificar Progresso
Consulte [PENDING-TASKS.md](./PENDING-TASKS.md) para status atual das tarefas.

### Adicionar Nova Página
Siga o guia em [guides/ADDING-NEW-PAGES.md](./guides/ADDING-NEW-PAGES.md) e use os templates em [guides/templates/](./guides/templates/).
