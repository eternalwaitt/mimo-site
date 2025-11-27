# Site Mimo

Site institucional da Mimo - Salão de Beleza em São Paulo.

## 🚀 Setup

### Pré-requisitos

- Node.js 18+ 
- npm, yarn ou pnpm

### Instalação

```bash
# Instalar dependências
npm install

# Rodar em desenvolvimento
npm run dev

# Build para produção
npm run build

# Iniciar servidor de produção
npm start

# Lint
npm run lint
```

O site estará disponível em `http://localhost:3000`

## 📁 Estrutura de Pastas

```
mimo-site/
├── app/                    # Next.js App Router
│   ├── layout.tsx         # Root layout com metadata e fontes
│   ├── page.tsx           # Home page
│   ├── globals.css        # Estilos globais e tokens
│   ├── servicos/          # Páginas de serviços
│   ├── sobre/             # Página sobre
│   ├── galeria/           # Galeria de fotos
│   ├── trabalhe-aqui/     # Página de carreiras
│   ├── blog/              # Blog (estrutura fase 2)
│   └── sitemap.ts         # Sitemap dinâmico
├── components/
│   ├── layout/            # Header e Footer
│   ├── sections/          # Seções da home
│   └── ui/                # Componentes reutilizáveis
├── lib/
│   ├── constants/         # Constantes organizadas por módulo
│   │   ├── index.ts      # Re-exporta todos os módulos
│   │   ├── services.ts   # Serviços
│   │   ├── celebrities.ts # Celebridades/influencers
│   │   ├── jobs.ts       # Vagas
│   │   ├── contact.ts    # Contato e empresa
│   │   └── home.ts       # Copy da home
│   ├── utils.ts           # Funções utilitárias
│   └── types.ts           # Tipos TypeScript
└── public/
    ├── fonts/             # Fontes BUENO e Satoshi
    └── images/            # Imagens do site
```

## 🎨 Design System

### Cores

- **mimo-brown** (#3D1F12): Cor protagonista - títulos, botões primários
- **mimo-blue** (#400303): Apoio estrutural - header, footer
- **mimo-neutral-light** (#F4EFEB): Fundos principais
- **mimo-neutral-medium** (#CBB9A4): Fundos alternativos
- **mimo-gold** (#FFF4B9): Acentos e detalhes

### Tipografia

- **BUENO**: Headlines, títulos, CTAs (font-bueno)
- **Satoshi**: Corpo de texto, descrições (font-satoshi)

### Formas Orgânicas

Componente `OrganicShape` com variants:
- `blob`: Formas irregulares orgânicas
- `circle`: Círculos
- `ellipse`: Elipses

## 📝 Como Adicionar Conteúdo

### Adicionar Novo Serviço

1. Editar `lib/constants/services.ts` e adicionar ao array `SERVICES`:

```typescript
{
  id: 'novo-servico',
  slug: 'novo-servico',
  title: 'Novo Serviço',
  description: 'Descrição completa...',
  shortDescription: 'Descrição curta',
  price: 'A partir de R$ X',
  image: '/images/servicos/novo-servico/categoria.webp',
  imageAlt: 'Novo Serviço Mimo',
  benefits: [
    'Benefício 1',
    'Benefício 2',
  ],
}
```

2. A página será gerada automaticamente em `/servicos/novo-servico`

### Adicionar Celebridade ao #MomentoMIMO

1. Editar `lib/constants/celebrities.ts` e adicionar ao array `CELEBRITIES`:

```typescript
{
  id: 'nome',
  name: 'Nome da Pessoa',
  image: '/images/depo/nome.webp',
  imageAlt: 'Nome - cliente Mimo',
  service: 'Serviço',
  quote: 'Quote opcional',
}
```

### Adicionar Vaga em Aberto

1. Editar `lib/constants/jobs.ts` e adicionar ao array `JOB_OPENINGS`:

```typescript
{
  id: 'vaga-id',
  title: 'Título da Vaga',
  area: 'Área',
  description: 'Descrição da vaga',
  requirements: [
    'Requisito 1',
    'Requisito 2',
  ],
  contactMethod: 'whatsapp' | 'email',
}
```

## 🛠️ Padrões de Código

### Componentização

- Componentes reutilizáveis em `components/ui/`
- Seções específicas em `components/sections/`
- Layout em `components/layout/`

### Acessibilidade

- HTML semântico (`<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`)
- ARIA labels quando necessário
- Navegação por teclado funcional
- Foco visível (outline customizado)
- Contraste WCAG AA mínimo

### Performance

- `next/image` para todas as imagens (otimização automática)
- Lazy loading abaixo do fold
- Font-display: swap
- Core Web Vitals otimizados:
  - LCP < 2.5s
  - CLS = 0
  - INP < 200ms

### SEO

- Metadata em cada página
- Schema.org (LocalBusiness, Service, JobPosting)
- URLs semânticas
- Alt text descritivo em todas imagens
- Sitemap dinâmico

## 📋 Checklist Pré-Deploy

### Performance

- [ ] Lighthouse Score > 90
- [ ] LCP < 2.5s
- [ ] CLS = 0
- [ ] INP < 200ms
- [ ] Imagens otimizadas (WebP/AVIF)
- [ ] Fontes carregando corretamente

### SEO

- [ ] Metadata completo em todas páginas
- [ ] Schema.org validado
- [ ] Sitemap.xml funcionando
- [ ] Robots.txt configurado
- [ ] Alt text em todas imagens
- [ ] URLs semânticas

### Acessibilidade

- [ ] Contraste WCAG AA
- [ ] Navegação por teclado
- [ ] Foco visível
- [ ] ARIA labels quando necessário
- [ ] HTML semântico

### Funcionalidades

- [ ] Todos os links funcionando
- [ ] WhatsApp links pré-preenchidos
- [ ] Formulários funcionando (se houver)
- [ ] Imagens carregando
- [ ] Animações suaves

### Conteúdo

- [ ] Textos revisados
- [ ] Preços atualizados
- [ ] Contatos atualizados
- [ ] Redes sociais corretas
- [ ] Endereço correto

## 🚢 Deploy

### Vercel (Recomendado)

1. Conectar repositório GitHub
2. Configurar variáveis de ambiente (se necessário)
3. Deploy automático a cada push

### Outros

O projeto usa `output: 'standalone'` no `next.config.ts`, facilitando deploy em qualquer plataforma.

## 📚 Tecnologias

- **Next.js 15**: Framework React
- **TypeScript**: Tipagem estática
- **Tailwind CSS**: Estilização
- **Framer Motion**: Animações
- **Next/Image**: Otimização de imagens

## 📖 Documentação

Documentação técnica essencial disponível em [`docs/`](./docs/):
- Guias práticos para desenvolvimento
- Documentação de performance
- Templates de código
- Configurações e setup

Veja [`docs/README.md`](./docs/README.md) para índice completo.

## ⚡ Performance First

Este projeto mantém **Performance ≥95** e **LCP <2.5s** em todas as páginas.

### 🎯 Performance Budget

- **Performance Score**: ≥95 (Lighthouse mobile)
- **LCP**: <2.5s
- **FCP**: <1.8s
- **TBT**: <200ms
- **CLS**: <0.1
- **Unused JS**: <60 KiB
- **Home JS Bundle**: ≤125 KiB (first load, mobile)
- **Hero Image**: ≤30 KiB (mobile, WebP/AVIF)

### 🚀 Otimização de Performance para Novos Projetos

**Para garantir que futuros projetos sigam os mesmos padrões de performance:**

1. **Use o template de prompt**: [`docs/performance/PERFORMANCE-PROMPT-TEMPLATE.md`](./docs/performance/PERFORMANCE-PROMPT-TEMPLATE.md)
   - Copie o prompt completo para o início da conversa com Cursor/AI
   - O prompt guia todo o processo de otimização passo a passo
   - Garante que todas as metas sejam atingidas antes de encerrar

2. **Siga o processo documentado**:
   - Baseline local → Bundle analysis → Server/Client islands → Reduzir JS → Otimizar LCP → Analytics → CI guardrails
   - Cada etapa tem comandos específicos e critérios de sucesso

3. **Use `.cursorrules`**: Este projeto já tem regras de performance configuradas
   - Cursor automaticamente sugere otimizações baseadas nessas regras
   - Metas e padrões estão documentados no arquivo

### Quick Checklist

Antes de criar uma nova página:
- [ ] Li `docs/guides/ADDING-NEW-PAGES.md`
- [ ] Usei template de `docs/guides/templates/`
- [ ] Validei com `npm run pre-deploy`

### Validação Automática

- **Pre-commit**: Valida lint e type-check automaticamente
- **CI/CD**: Valida build e Lighthouse em cada PR
- **Quality Gates**: Performance ≥95, LCP <2.5s
- **Lighthouse Local**: `DISABLE_ANALYTICS=true npm run lighthouse:local`

### 📚 Recursos de Performance

- 🎯 **Performance Guide**: [`docs/performance/PERFORMANCE-GUIDE.md`](./docs/performance/PERFORMANCE-GUIDE.md)
- 📋 **Prompt Template**: [`docs/performance/PERFORMANCE-PROMPT-TEMPLATE.md`](./docs/performance/PERFORMANCE-PROMPT-TEMPLATE.md) ⭐ **Use para novos projetos**
- 📊 **Relatório de Otimização**: [`docs/performance/PERFORMANCE-OPTIMIZATION-REPORT.md`](./docs/performance/PERFORMANCE-OPTIMIZATION-REPORT.md)
- ✅ **Checklist Rápido**: [`docs/performance/PERFORMANCE-CHECKLIST.md`](./docs/performance/PERFORMANCE-CHECKLIST.md)
- 📖 **Guia de Páginas**: [`docs/guides/ADDING-NEW-PAGES.md`](./docs/guides/ADDING-NEW-PAGES.md)
- 📝 **Templates**: [`docs/guides/templates/`](./docs/guides/templates/)

## 🧪 Scripts Disponíveis

```bash
# Type checking
npm run type-check

# Lint
npm run lint

# Validação pré-deploy (recomendado antes de push)
npm run pre-deploy

# PageSpeed Insights (requer .env.local com API key)
npm run pagespeed

# Lighthouse CI (requer .env.local com API key)
npm run lighthouse:home

# Análise de bundle
npm run analyze

# Otimizar imagens (requer sharp instalado)
node scripts/optimize-images.js
```

## 📞 Suporte

Para dúvidas ou problemas, entre em contato com a equipe de desenvolvimento.

