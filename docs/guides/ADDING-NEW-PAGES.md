# Guia: Adicionando Novas Páginas

Este guia garante que todas as novas páginas mantenham o padrão de qualidade e performance (Performance ≥95, LCP <2.5s).

## 📋 Checklist Rápido

Antes de criar uma nova página, verifique:

- [ ] Li o template em `docs/guides/templates/page-template.tsx`
- [ ] Entendi as regras de performance
- [ ] Sei como testar localmente
- [ ] Sei como validar antes de fazer merge

## 🚀 Passo a Passo

### 1. Copiar o Template

```bash
# Copie o template
cp docs/guides/templates/page-template.tsx app/nova-pagina/page.tsx
```

### 2. Preencher Metadata

Edite a metadata da página:

```typescript
export const metadata: Metadata = {
  title: 'Título da Página | mimo salão',
  description: 'Descrição otimizada para SEO (150-160 caracteres)',
  // Adicione mais conforme necessário
}
```

### 3. Adicionar Conteúdo

Siga estas diretrizes:

#### ✅ Server Component (Padrão)

```typescript
// ✅ BOM: Server component
export default function MinhaPage() {
  return <div>Conteúdo</div>
}

// ❌ EVITE: Client component desnecessário
'use client'
export default function MinhaPage() {
  return <div>Conteúdo</div>
}
```

**Use `'use client'` apenas se precisar de:**
- Hooks (useState, useEffect, etc.)
- Event handlers (onClick, onChange, etc.)
- Browser APIs (window, document, etc.)

#### ✅ CSS Animations

```typescript
// ✅ BOM: CSS animation
<div className="animate-fade-in-up">
  <h1>Conteúdo</h1>
</div>

// ❌ EVITE: Framer Motion acima do fold
'use client'
import { motion } from 'framer-motion'
<motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
  <h1>Conteúdo</h1>
</motion.div>
```

**Framer Motion apenas:**
- Abaixo do fold
- Para animações complexas
- Nunca em componentes acima do fold

#### ✅ Imagens Otimizadas

```typescript
// ✅ BOM: ImageWithFallback com sizes correto
<ImageWithFallback
  src="/images/exemplo.webp"
  alt="Descrição"
  width={800}
  height={600}
  sizes="(max-width: 768px) 100vw, 50vw"
  className="object-cover"
/>

// ❌ EVITE: Imagem sem sizes
<ImageWithFallback
  src="/images/exemplo.webp"
  alt="Descrição"
  width={800}
  height={600}
  // Faltando sizes!
/>
```

**Regras de `sizes`:**
- Mobile: `100vw` para imagens full-width
- Desktop: limite máximo (ex: `1920px` para hero, `50vw` para grid)
- Exemplo: `"(max-width: 768px) 100vw, 50vw"`

#### ✅ Lazy Loading Abaixo do Fold

```typescript
// ✅ BOM: content-visibility para seções grandes
<section style={{ contentVisibility: 'auto' }}>
  {/* Conteúdo abaixo do fold */}
</section>

// ✅ BOM: Imagens abaixo do fold não usam priority
<ImageWithFallback
  src="/images/abaixo-fold.webp"
  // Sem priority - lazy loading automático
/>
```

### 4. Testar Localmente

```bash
# 1. Type check
npm run type-check

# 2. Lint
npm run lint

# 3. Build
npm run build

# 4. Validação completa (recomendado antes de push)
npm run pre-deploy
```

### 5. Validar Performance

Se a página for a home ou uma página importante:

```bash
# Testar com Lighthouse (requer API key)
npm run lighthouse:home
```

**Targets:**
- Performance: ≥95
- LCP: <2.5s
- CLS: <0.1
- TBT: <200ms

## 📝 Exemplos

### ✅ Exemplo: Página Otimizada

```typescript
import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'

export const metadata: Metadata = {
  title: 'Nova Página | mimo salão',
  description: 'Descrição otimizada para SEO',
}

export default function NovaPage() {
  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* Hero - acima do fold */}
        <section className="relative py-20 bg-mimo-neutral-light">
          <div className="container mx-auto px-4">
            <h1 className="font-bueno text-4xl font-bold text-mimo-brown">
              Título
            </h1>
            {/* CSS animation */}
            <div className="animate-fade-in-up">
              <ImageWithFallback
                src="/images/hero.webp"
                alt="Hero"
                width={1920}
                height={1080}
                sizes="(max-width: 768px) 100vw, 1920px"
                priority
                fetchPriority="high"
              />
            </div>
          </div>
        </section>

        {/* Conteúdo abaixo do fold */}
        <section 
          className="py-20 bg-white"
          style={{ contentVisibility: 'auto' }}
        >
          <div className="container mx-auto px-4">
            {/* Imagens sem priority - lazy loading */}
            <ImageWithFallback
              src="/images/abaixo-fold.webp"
              alt="Abaixo fold"
              width={800}
              height={600}
              sizes="(max-width: 768px) 100vw, 50vw"
            />
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}
```

### ❌ Exemplo: Página com Problemas

```typescript
'use client' // ❌ Client component desnecessário

import { motion } from 'framer-motion' // ❌ Framer Motion acima do fold
import Image from 'next/image'

export default function PaginaRuim() {
  return (
    <main>
      {/* ❌ Framer Motion acima do fold */}
      <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
        <h1>Título</h1>
      </motion.div>

      {/* ❌ Imagem sem sizes */}
      <Image
        src="/images/exemplo.jpg" // ❌ JPG em vez de WebP
        alt="Exemplo"
        width={1920}
        height={1080}
        // ❌ Faltando sizes
      />
    </main>
  )
}
```

**Problemas:**
1. `'use client'` desnecessário - página poderia ser server component
2. Framer Motion acima do fold - deveria usar CSS animation
3. Imagem sem `sizes` - mobile vai baixar imagem muito grande
4. JPG em vez de WebP - Next.js converte, mas melhor usar WebP direto

## 🔍 Validação Antes de Merge

### Pre-commit Hook (Automático)

O pre-commit hook roda automaticamente e valida:
- ✅ Lint
- ✅ Type check

### Validação Manual (Recomendado)

Antes de fazer push:

```bash
npm run pre-deploy
```

Isso valida:
- ✅ Type check
- ✅ Lint
- ✅ Build

### CI/CD (Automático)

O GitHub Actions valida automaticamente em PRs:
- ✅ Lint
- ✅ Type check
- ✅ Build
- ✅ Lighthouse (Performance ≥95, LCP <2.5s)

## 📚 Recursos

- **Template de Página**: `docs/guides/templates/page-template.tsx`
- **Template de Seção**: `docs/guides/templates/section-template.tsx`
- **Performance Guide**: `docs/performance/PERFORMANCE-GUIDE.md`
- **Checklist Rápido**: `docs/PERFORMANCE-CHECKLIST.md`

## ❓ Dúvidas?

Se tiver dúvidas sobre:
- **Performance**: Consulte `docs/performance/PERFORMANCE-GUIDE.md`
- **Estrutura**: Veja páginas existentes em `app/`
- **Componentes**: Veja exemplos em `components/`

## 🎯 Lembre-se

> **Performance First**: Sempre priorize performance sobre conveniência.  
> **Server Components**: Use por padrão, client components apenas quando necessário.  
> **CSS > JS**: Animações CSS são sempre mais rápidas que JS.  
> **Teste Antes**: Sempre teste localmente antes de fazer merge.

