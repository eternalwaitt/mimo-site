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
│   ├── constants.ts       # Dados e constantes
│   ├── utils.ts           # Funções utilitárias
│   └── types.ts           # Tipos TypeScript
└── public/
    ├── fonts/             # Fontes BUENO e Satoshi
    └── images/            # Imagens do site
```

## 🎨 Design System

### Cores

- **mimo-brown** (#493125): Cor protagonista - títulos, botões primários
- **mimo-blue** (#1F2A3E): Apoio estrutural - header, footer
- **mimo-neutral-light** (#F4EFEB): Fundos principais
- **mimo-neutral-medium** (#E5DCD3): Fundos alternativos
- **mimo-gold** (#EFDFAC): Acentos e detalhes

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

1. Editar `lib/constants.ts` e adicionar ao array `SERVICES`:

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

1. Editar `lib/constants.ts` e adicionar ao array `CELEBRITIES`:

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

1. Editar `lib/constants.ts` e adicionar ao array `JOB_OPENINGS`:

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

## 📞 Suporte

Para dúvidas ou problemas, entre em contato com a equipe de desenvolvimento.

