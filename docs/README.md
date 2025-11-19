# Documentação do Projeto Mimo Site

Este diretório contém toda a documentação técnica do projeto.

## Índice de Documentos

### Revisão e Qualidade
- **[CODE-REVIEW-REPORT.md](./CODE-REVIEW-REPORT.md)** - Relatório completo de revisão de código
- **[PROGRESS-TRACKING.md](./PROGRESS-TRACKING.md)** - Acompanhamento de progresso das fases

### Imagens
- **[IMAGE-SOURCES.md](./IMAGE-SOURCES.md)** - Pesquisa de fontes de imagens (Unsplash, Pexels, etc)
- **[IMAGE-STRATEGY.md](./IMAGE-STRATEGY.md)** - Estratégia 100% local para imagens
- **[IMAGE-AUDIT.md](./IMAGE-AUDIT.md)** - Auditoria de imagens faltantes

### Performance
- **PERFORMANCE-MOBILE-REPORT-*.json** - Relatórios de PageSpeed Insights (gerados pelo script)

### UX/UI
- **[MOBILE-UX-RESEARCH.md](./MOBILE-UX-RESEARCH.md)** - Pesquisa de práticas UX/UI mobile 2025
- **[UX-AUDIT.md](./UX-AUDIT.md)** - Auditoria UX/UI atual do site
- **[UX-IMPROVEMENTS.md](./UX-IMPROVEMENTS.md)** - Lista priorizada de melhorias UX/UI

### Comparação de Tecnologias
- **[CSS-FRAMEWORKS-COMPARISON.md](./CSS-FRAMEWORKS-COMPARISON.md)** - Comparação de frameworks CSS
- **[DATA-FORMATS-COMPARISON.md](./DATA-FORMATS-COMPARISON.md)** - Comparação de formatos de dados
- **[TECH-STACK-ALTERNATIVES.md](./TECH-STACK-ALTERNATIVES.md)** - Comparação de outras tecnologias

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
node scripts/optimize-images.js

# Ou otimizar diretório específico
node scripts/optimize-images.js public/images/servicos
```

### Verificar Progresso
Consulte [PROGRESS-TRACKING.md](./PROGRESS-TRACKING.md) para status atual das fases.

## Estrutura

```
docs/
├── README.md (este arquivo)
├── CODE-REVIEW-REPORT.md
├── PROGRESS-TRACKING.md
├── IMAGE-SOURCES.md
├── IMAGE-STRATEGY.md
├── IMAGE-AUDIT.md
├── MOBILE-UX-RESEARCH.md
├── UX-AUDIT.md
├── UX-IMPROVEMENTS.md
├── CSS-FRAMEWORKS-COMPARISON.md
├── DATA-FORMATS-COMPARISON.md
├── TECH-STACK-ALTERNATIVES.md
└── PERFORMANCE-MOBILE-REPORT-*.json (gerados)
```

