# Guia Rápido do Tech Stack - Mimo Site

## Visão Geral

Site estático do salão Mimo construído com **Next.js 15** (App Router), **React 19**, **TypeScript** e **Tailwind CSS**. Deploy automático na **Vercel**.

## Stack Principal

### Framework & Runtime
- **Next.js 15.1.0** - Framework React com SSR/SSG
  - **App Router** - Roteamento baseado em arquivos (`app/`)
  - **Image Optimization** - Otimização automática de imagens (WebP/AVIF)
  - **Font Optimization** - Carregamento otimizado de fontes customizadas
  - **Standalone Output** - Build otimizado para deploy

### Linguagem & Tipagem
- **TypeScript 5.7** - Type safety e melhor DX
- **React 19** - Biblioteca UI com hooks e componentes funcionais

### Estilização
- **Tailwind CSS 3.4** - Utility-first CSS framework
  - Classes utilitárias para design rápido
  - Purge automático de CSS não utilizado
  - Customização via `tailwind.config.ts`
- **CSS Custom Properties** - Variáveis CSS para cores e temas (`app/globals.css`)

### Animações
- **Framer Motion 11** - Biblioteca de animações para React
  - Animações de entrada/saída
  - Transições suaves
  - Gestos e interações

### Utilitários
- **clsx** + **tailwind-merge** - Merge inteligente de classes CSS
  - Combina classes Tailwind sem conflitos
  - Condicionais para classes dinâmicas

## Estrutura do Projeto

```
mimo-site/
├── app/                    # App Router (Next.js 15)
│   ├── layout.tsx         # Layout raiz (meta tags, fonts, structured data)
│   ├── page.tsx           # Home page
│   ├── globals.css        # Estilos globais + Tailwind
│   ├── servicos/          # Páginas de serviços (dinâmicas)
│   ├── galeria/           # Galeria de fotos
│   ├── sobre/             # Sobre nós
│   └── trabalhe-aqui/     # Vagas
│
├── components/            # Componentes React reutilizáveis
│   ├── layout/            # Header, Footer
│   ├── sections/          # Seções da home (Hero, Services, CTA)
│   └── ui/                # Componentes base (Button, Image, Cards)
│
├── lib/                   # Utilitários e constantes
│   ├── constants.ts       # Dados do site (serviços, contatos)
│   ├── types.ts           # TypeScript types/interfaces
│   ├── utils.ts           # Funções utilitárias (cn, helpers)
│   └── version.ts         # Versionamento SemVer
│
├── public/                # Assets estáticos
│   ├── images/            # Imagens otimizadas
│   └── fonts/             # Fontes customizadas (Bueno, Satoshi)
│
├── scripts/               # Scripts Node.js
│   ├── pagespeed-test.js  # Teste de performance (PageSpeed API)
│   └── optimize-images.js # Otimização de imagens
│
└── docs/                  # Documentação
```

## Como Funciona

### 1. Roteamento (App Router)
- Cada pasta em `app/` vira uma rota
- `app/page.tsx` = `/` (home)
- `app/servicos/page.tsx` = `/servicos`
- `app/servicos/[slug]/page.tsx` = `/servicos/salao` (dinâmico)

### 2. Componentes
- **Layout Components** (`components/layout/`)
  - `header.tsx` - Header sticky com navegação
  - `footer.tsx` - Footer com links e contatos

- **Section Components** (`components/sections/`)
  - `hero-manifesto.tsx` - Hero da home com imagem full-screen
  - `services-grid.tsx` - Grid de serviços
  - `momento-mimo.tsx` - Seção de influencers/clientes
  - `time-economy.tsx` - Cálculo de tempo economizado
  - `cta-agendamento.tsx` - Call-to-action final

- **UI Components** (`components/ui/`)
  - `button.tsx` - Botão reutilizável (variantes: primary, ghost)
  - `image-with-fallback.tsx` - Imagem com fallback automático
  - `service-card.tsx` - Card de serviço com hover
  - `celebrity-card.tsx` - Card de influencer/cliente

### 3. Dados & Estado
- **Sem estado global** - Props drilling simples
- **Dados estáticos** em `lib/constants.ts`
  - Lista de serviços
  - Contatos e redes sociais
  - Copywriting do site
- **TypeScript types** em `lib/types.ts` para type safety

### 4. Estilização
- **Tailwind classes** diretamente no JSX
- **Variáveis CSS** para cores (`--mimo-brown`, `--mimo-neutral-light`)
- **Fontes customizadas** via `next/font/local`
  - Bueno (display/headings)
  - Satoshi (body text)

### 5. Performance
- **Image Optimization** automática (Next.js Image)
- **Font Optimization** (preload + subset)
- **Code Splitting** automático por rota
- **Static Generation** (SSG) para todas as páginas
- **Cache headers** configurados no `next.config.ts`

## Deploy & CI/CD

### Vercel
- **Deploy automático** via GitHub
- **Preview deployments** para cada PR
- **Edge Network** para CDN global
- **Analytics** e **Speed Insights** integrados

### Build Process
1. `npm run build` - Gera build otimizado
2. Next.js faz:
   - Type checking
   - Code splitting
   - Image optimization
   - CSS minification
   - Bundle optimization
3. Output: pasta `.next/` com assets otimizados

## Scripts Disponíveis

```bash
npm run dev          # Dev server (localhost:3000)
npm run build        # Build de produção
npm run start        # Server de produção local
npm run lint         # ESLint
npm run type-check   # TypeScript check
npm run pagespeed    # Teste de performance (PageSpeed API)
```

## Dependências Principais

### Production
- `next` - Framework
- `react` + `react-dom` - UI library
- `framer-motion` - Animações
- `clsx` + `tailwind-merge` - CSS utilities

### Development
- `typescript` - Type checking
- `tailwindcss` - CSS framework
- `eslint` - Linting
- `@types/*` - Type definitions

## Decisões de Arquitetura

### Por que Next.js?
- ✅ SSR/SSG out-of-the-box
- ✅ Image optimization automática
- ✅ Roteamento simples (App Router)
- ✅ Deploy fácil na Vercel
- ✅ Performance otimizada

### Por que Tailwind?
- ✅ Desenvolvimento rápido
- ✅ Consistência visual
- ✅ Purge automático (CSS pequeno)
- ✅ Responsive design fácil

### Por que TypeScript?
- ✅ Type safety
- ✅ Melhor autocomplete
- ✅ Menos bugs em runtime
- ✅ Documentação implícita

### Por que Framer Motion?
- ✅ Animações declarativas
- ✅ Performance otimizada
- ✅ Gestos e interações
- ✅ API simples

## Fluxo de Dados

```
lib/constants.ts (dados)
    ↓
app/page.tsx (página)
    ↓
components/sections/* (seções)
    ↓
components/ui/* (componentes base)
    ↓
Render no browser
```

## Otimizações Implementadas

1. **CLS (Cumulative Layout Shift)**
   - Altura fixa em containers de imagens
   - Background colors antes do carregamento
   - Aspect ratios definidos

2. **LCP (Largest Contentful Paint)**
   - Preload da hero image
   - `fetchPriority="high"` em recursos críticos
   - DNS prefetch para recursos externos

3. **Cache**
   - Headers de cache para assets estáticos (1 ano)
   - CDN via Vercel Edge Network

4. **Bundle Size**
   - Code splitting automático
   - Tree shaking
   - CSS purge (Tailwind)

## Próximos Passos Possíveis

- [ ] Adicionar estado global (Zustand/Context) se necessário
- [ ] Implementar CMS headless (Contentful/Sanity) para conteúdo dinâmico
- [ ] Adicionar analytics (Plausible/Google Analytics)
- [ ] Implementar testes (Jest + React Testing Library)
- [ ] Adicionar PWA capabilities
- [ ] Implementar dark mode (se necessário)

## Recursos Úteis

- [Next.js Docs](https://nextjs.org/docs)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Framer Motion Docs](https://www.framer.com/motion/)
- [TypeScript Handbook](https://www.typescriptlang.org/docs/)

---

**Versão atual**: 1.2.0  
**Última atualização**: 2025-01-29

