# Performance Guide

Este guia documenta padrões e melhores práticas para manter scores altos de Lighthouse (90+) no site Mimo.

## Como Executar Testes Lighthouse

### Testes Locais

```bash
# Testar home page (mobile + desktop)
npm run lighthouse:home

# Modo CI (falha se scores < 90)
npm run lighthouse:ci

# Teste completo de todas as páginas
npm run pagespeed
```

### Interpretando Resultados

Os resultados são salvos em `docs/lighthouse/`:
- `lighthouse-baseline-mobile.json` - Baseline mobile
- `lighthouse-baseline-desktop.json` - Baseline desktop
- `lighthouse-ci-*.json` - Resultados do CI

**Scores mínimos esperados:**
- Performance: ≥ 95
- Accessibility: ≥ 90
- Best Practices: ≥ 90
- SEO: ≥ 90

## Performance Budget

### Métricas Críticas

**Core Web Vitals:**
- **LCP (Largest Contentful Paint)**: < 2.5s (target: < 2.0s)
- **FCP (First Contentful Paint)**: < 1.8s
- **CLS (Cumulative Layout Shift)**: < 0.1
- **TBT (Total Blocking Time)**: < 200ms

**Bundle Size:**
- **First Load JS (home)**: < 125 kB (target: < 100 kB)
- **Unused JavaScript**: < 60 KiB (target: < 20 KiB)

**Imagens:**
- **Hero image (mobile)**: < 30 KiB
- **Hero image (desktop)**: < 150 KiB

### Regras para Novas Páginas

1. **Evitar client components no topo** - usar server components quando possível
2. **Usar islands pattern** - separar lógica interativa em client components mínimos
3. **Sempre usar `<Image>` com `sizes` corretos** - otimização automática do Next.js
4. **Dynamic import para componentes abaixo da dobra** - reduzir bundle inicial
5. **Rodar `npm run lighthouse:local`** quando mexer na home ou componentes críticos
6. **Verificar bundle size** com `ANALYZE=true npm run build` antes de merge

### CI Guardrails

O CI falha automaticamente se:
- Performance < 95
- LCP > 2.5s

Para rodar lighthouse localmente com analytics desabilitado:
```bash
DISABLE_ANALYTICS=true npm run lighthouse:local
```

## Padrões para Adicionar Imagens

### ✅ Correto

```tsx
import { ImageWithFallback } from '@/components/ui/image-with-fallback'

// Imagem acima da dobra (LCP candidate)
<ImageWithFallback
  src="/images/hero.webp"
  alt="Descrição significativa"
  fill
  priority
  fetchPriority="high"
  sizes="100vw"
/>

// Imagem abaixo da dobra
<ImageWithFallback
  src="/images/gallery.webp"
  alt="Descrição significativa"
  width={800}
  height={600}
  sizes="(max-width: 768px) 100vw, 50vw"
/>
```

### ❌ Evitar

```tsx
// NÃO usar <img> diretamente
<img src="/images/photo.jpg" alt="photo" />

// NÃO esquecer alt text
<ImageWithFallback src="/images/photo.webp" />

// NÃO usar priority em imagens abaixo da dobra
<ImageWithFallback src="/images/footer.webp" priority />
```

### Regras

1. **Sempre use `ImageWithFallback`** (não `<img>` direto)
2. **Alt text obrigatório** - descritivo e significativo
3. **Dimensões explícitas** - `width`/`height` ou `fill` com container com dimensões
4. **Priority apenas acima da dobra** - hero, primeira imagem visível
5. **fetchPriority="high"** - apenas para LCP candidate
6. **sizes correto** - descreve tamanho em diferentes viewports

## Padrões para Adicionar Seções

### Estrutura de Seção

```tsx
export function NovaSecao() {
  return (
    <section className="py-20 md:py-32 bg-white">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 className="font-bueno text-4xl font-bold text-mimo-brown mb-4">
          Título da Seção
        </h2>
        {/* Conteúdo */}
      </div>
    </section>
  )
}
```

### Seções Abaixo da Dobra

Se a seção estiver abaixo da dobra, use dynamic import:

```tsx
// app/page.tsx
import dynamic from 'next/dynamic'

const NovaSecao = dynamic(
  () => import('@/components/sections/nova-secao').then(mod => ({ default: mod.NovaSecao })),
  { ssr: true }
)
```

### Regras

1. **Semântica HTML** - use `<section>`, `<article>`, etc.
2. **Heading hierarchy** - h1 → h2 → h3 (sem pular níveis)
3. **Container responsivo** - sempre use container com padding
4. **Lazy load abaixo da dobra** - use dynamic import para componentes pesados

## Framer Motion - Melhores Práticas

### ✅ Correto

```tsx
'use client'
import { motion } from 'framer-motion'

// Animações simples com whileInView
<motion.div
  initial={{ opacity: 0, y: 30 }}
  whileInView={{ opacity: 1, y: 0 }}
  viewport={{ once: true }}
  transition={{ duration: 0.6 }}
>
  {/* Conteúdo */}
</motion.div>
```

### ❌ Evitar

```tsx
// NÃO animar tudo na inicialização
<motion.div
  animate={{ x: [0, 100, 0] }} // Loop contínuo
  transition={{ repeat: Infinity }}
/>

// NÃO usar will-change desnecessariamente
<div style={{ willChange: 'transform' }}> {/* Só quando animando ativamente */}
```

### Regras

1. **Use `whileInView`** - anima apenas quando visível
2. **`viewport={{ once: true }}`** - anima apenas uma vez
3. **Animações simples** - opacity, transform (não layout)
4. **Lazy load abaixo da dobra** - componentes com Framer Motion podem ser code-split

## Fontes - Diretrizes

### Configuração

As fontes são configuradas em `app/layout.tsx`:

```tsx
const bueno = localFont({
  src: [{ path: '../public/fonts/bueno-regular.woff2', weight: '400' }],
  variable: '--font-bueno',
  display: 'swap', // ✅ Importante para performance
  fallback: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
})
```

### Regras

1. **display: 'swap'** - sempre configurado
2. **Apenas pesos necessários** - não carregar weights não usados
3. **Fallback adequado** - system fonts como fallback
4. **Preload crítico** - Next.js já otimiza, mas preloads manuais são OK

## Acessibilidade - Checklist

Ao adicionar novos componentes:

- [ ] **Alt text** em todas as imagens (ou `alt=""` se decorativa)
- [ ] **Heading hierarchy** correta (h1 → h2 → h3)
- [ ] **Contraste de cores** - WCAG AA mínimo (4.5:1 normal, 3:1 large)
- [ ] **Navegação por teclado** - todos os links/botões focáveis
- [ ] **Focus visível** - outline customizado já configurado
- [ ] **ARIA labels** - apenas quando necessário (semantic HTML primeiro)
- [ ] **Skip links** - já implementado no header

## Bundle Size - Monitoramento

### Analisar Bundle

```bash
npm run analyze
```

Isso abre um relatório visual do bundle. Verifique:
- Dependências grandes não esperadas
- Duplicação de código
- Bibliotecas não usadas

### Reduzir Bundle

1. **Dynamic imports** - componentes abaixo da dobra
2. **Tree shaking** - import apenas o necessário
3. **Remover dependências não usadas**
4. **Code splitting** - rotas separadas já fazem isso automaticamente

## Caching e Headers

Headers de cache já estão configurados em `next.config.ts`:
- Imagens: `max-age=31536000, immutable`
- Fontes: `max-age=31536000, immutable`
- Static assets: `max-age=31536000, immutable`

Não é necessário modificar, mas saiba que:
- Assets estáticos são cached agressivamente
- HTML não é cached (SSG regenera quando necessário)

## Troubleshooting

### Performance < 90

1. Verifique bundle size: `npm run analyze`
2. Verifique imagens: todas têm `priority`/`fetchPriority` correto?
3. Verifique code splitting: componentes abaixo da dobra são lazy loaded?
4. Verifique animações: Framer Motion está otimizado?

### Accessibility < 90

1. Verifique alt text: todas as imagens têm alt?
2. Verifique headings: hierarchy correta?
3. Verifique contraste: use ferramenta de contraste
4. Verifique keyboard nav: todos os elementos são focáveis?

### Best Practices < 90

1. Verifique console: há erros no console?
2. Verifique dimensões: todas as imagens têm width/height?
3. Verifique HTTPS: tudo está sobre HTTPS?
4. Verifique APIs: nenhuma API deprecada?

### SEO < 90

1. Verifique metadata: todas as páginas têm title/description?
2. Verifique canonical: URLs canônicas configuradas?
3. Verifique Open Graph: tags OG configuradas?
4. Verifique structured data: schema.org válido?

## Recursos

- [Lighthouse Documentation](https://developers.google.com/web/tools/lighthouse)
- [Next.js Image Optimization](https://nextjs.org/docs/app/api-reference/components/image)
- [Web Vitals](https://web.dev/vitals/)
- [WCAG Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

