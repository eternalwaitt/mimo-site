# Documentação do Projeto Mimo Site

Este diretório contém a documentação técnica essencial do projeto, organizada para auxiliar desenvolvedores e IA a fazer as melhores mudanças.

## Estrutura

```
docs/
├── README.md (este arquivo)
├── performance/          # Documentação de performance
│   ├── PERFORMANCE-GUIDE.md
│   ├── PERFORMANCE-CHECKLIST.md
│   ├── PERFORMANCE-PROMPT-TEMPLATE.md
│   └── PERFORMANCE-OPTIMIZATION-REPORT.md
└── guides/              # Guias práticos
    ├── ADDING-NEW-PAGES.md
    ├── ANALYTICS-SETUP.md
    └── templates/       # Templates de código
        ├── page-template.tsx
        └── section-template.tsx
```

## Índice de Documentos

### Performance

Documentação essencial para manter e melhorar a performance do site:

- **[performance/PERFORMANCE-GUIDE.md](./performance/PERFORMANCE-GUIDE.md)** - Guia completo de performance com metas, budget e otimizações
- **[performance/PERFORMANCE-CHECKLIST.md](./performance/PERFORMANCE-CHECKLIST.md)** - Checklist rápido de performance
- **[performance/PERFORMANCE-PROMPT-TEMPLATE.md](./performance/PERFORMANCE-PROMPT-TEMPLATE.md)** - Template de prompt para otimização de performance em novos projetos
- **[performance/PERFORMANCE-OPTIMIZATION-REPORT.md](./performance/PERFORMANCE-OPTIMIZATION-REPORT.md)** - Relatório de otimizações aplicadas e resultados

### Guias Práticos

Guias para desenvolvimento e configuração:

- **[guides/ADDING-NEW-PAGES.md](./guides/ADDING-NEW-PAGES.md)** - Como adicionar novas páginas mantendo padrões de qualidade e performance
- **[guides/ANALYTICS-SETUP.md](./guides/ANALYTICS-SETUP.md)** - Configuração de analytics (Plausible)
- **[guides/templates/](./guides/templates/)** - Templates de código para páginas e seções

## Como Usar

### Adicionar Nova Página

1. Leia o guia: [guides/ADDING-NEW-PAGES.md](./guides/ADDING-NEW-PAGES.md)
2. Use o template: `guides/templates/page-template.tsx`
3. Valide antes de fazer merge: `npm run pre-deploy`

### Otimizar Performance

1. Use o template de prompt: [performance/PERFORMANCE-PROMPT-TEMPLATE.md](./performance/PERFORMANCE-PROMPT-TEMPLATE.md)
2. Siga o guia: [performance/PERFORMANCE-GUIDE.md](./performance/PERFORMANCE-GUIDE.md)
3. Valide com: `npm run lighthouse:local`

### Configurar Analytics

Siga o guia: [guides/ANALYTICS-SETUP.md](./guides/ANALYTICS-SETUP.md)

## Metas de Performance

O projeto mantém as seguintes metas (Lighthouse Mobile):

- **Performance Score**: ≥ 95
- **LCP**: < 2.5s
- **FCP**: < 1.8s
- **TBT**: < 200ms
- **CLS**: < 0.1
- **Unused JS**: < 60 KiB

Veja [performance/PERFORMANCE-GUIDE.md](./performance/PERFORMANCE-GUIDE.md) para detalhes completos.

## Documentação Adicional

Para informações sobre:
- **Setup e instalação**: Veja [README.md](../README.md) na raiz do projeto
- **Deploy**: Veja [DEPLOY.md](../DEPLOY.md)
- **Versionamento**: Veja [VERSIONING.md](../VERSIONING.md)
- **Histórico de mudanças**: Veja [CHANGELOG.md](../CHANGELOG.md)
