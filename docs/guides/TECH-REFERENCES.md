# Referências Técnicas - Next.js, React, TypeScript, Tailwind CSS

Referências rápidas e recursos úteis para as tecnologias usadas no projeto.

---

## 📘 Next.js 15

### Documentação Oficial
- **Docs**: https://nextjs.org/docs
- **App Router**: https://nextjs.org/docs/app
- **API Reference**: https://nextjs.org/docs/app/api-reference
- **Learn Course**: https://nextjs.org/learn

### Recursos Essenciais

#### App Router (Atual)
- [Routing](https://nextjs.org/docs/app/building-your-application/routing) - Sistema de roteamento baseado em arquivos
- [Layouts](https://nextjs.org/docs/app/building-your-application/routing/pages-and-layouts) - Layouts aninhados
- [Loading UI](https://nextjs.org/docs/app/building-your-application/routing/loading-ui-and-streaming) - Loading states
- [Error Handling](https://nextjs.org/docs/app/building-your-application/routing/error-handling) - Error boundaries
- [Metadata](https://nextjs.org/docs/app/building-your-application/optimizing/metadata) - SEO e metadata

#### Otimizações
- [Image Optimization](https://nextjs.org/docs/app/building-your-application/optimizing/images) - `next/image`
- [Font Optimization](https://nextjs.org/docs/app/building-your-application/optimizing/fonts) - `next/font`
- [Static Generation](https://nextjs.org/docs/app/building-your-application/rendering/static-and-dynamic-rendering) - SSG
- [Server Components](https://nextjs.org/docs/app/building-your-application/rendering/server-components) - RSC

#### Configuração
- [next.config.js](https://nextjs.org/docs/app/api-reference/next-config-js) - Configuração do Next.js
- [Environment Variables](https://nextjs.org/docs/app/building-your-application/configuring/environment-variables) - Variáveis de ambiente

### Cheat Sheet
```typescript
// Roteamento
app/page.tsx              → /
app/about/page.tsx        → /about
app/blog/[slug]/page.tsx  → /blog/:slug

// Metadata
export const metadata: Metadata = {
  title: 'Page Title',
  description: 'Page description',
}

// Server Component (padrão)
export default function Page() {
  return <div>Content</div>
}

// Client Component
'use client'
import { useState } from 'react'
export default function ClientPage() {
  const [count, setCount] = useState(0)
  return <button onClick={() => setCount(count + 1)}>{count}</button>
}
```

### Versão no Projeto
- **Versão**: 15.1.0
- **App Router**: ✅ Usado
- **Server Components**: ✅ Padrão
- **Image Optimization**: ✅ Ativo

---

## ⚛️ React 19

### Documentação Oficial
- **Docs**: https://react.dev
- **API Reference**: https://react.dev/reference/react
- **Hooks**: https://react.dev/reference/react/hooks

### Recursos Essenciais

#### Conceitos Fundamentais
- [Components](https://react.dev/learn/your-first-component) - Componentes funcionais
- [JSX](https://react.dev/learn/writing-markup-with-jsx) - Sintaxe JSX
- [Props](https://react.dev/learn/passing-props-to-a-component) - Passando props
- [State](https://react.dev/learn/state-a-components-memory) - Gerenciamento de estado
- [Events](https://react.dev/learn/responding-to-events) - Event handlers

#### Hooks Principais
- [useState](https://react.dev/reference/react/useState) - Estado local
- [useEffect](https://react.dev/reference/react/useEffect) - Side effects
- [useContext](https://react.dev/reference/react/useContext) - Context API
- [useRef](https://react.dev/reference/react/useRef) - Referências
- [useMemo](https://react.dev/reference/react/useMemo) - Memoização
- [useCallback](https://react.dev/reference/react/useCallback) - Callback memoizado

#### Padrões
- [Composition](https://react.dev/learn/passing-data-deeply-with-context) - Composição de componentes
- [Conditional Rendering](https://react.dev/learn/conditional-rendering) - Renderização condicional
- [Lists and Keys](https://react.dev/learn/rendering-lists) - Renderizar listas

### Cheat Sheet
```typescript
// Componente funcional
function MyComponent({ name }: { name: string }) {
  return <div>Hello {name}</div>
}

// Com estado
function Counter() {
  const [count, setCount] = useState(0)
  return (
    <button onClick={() => setCount(count + 1)}>
      Count: {count}
    </button>
  )
}

// Com efeito
function DataFetcher() {
  useEffect(() => {
    // Side effect
    return () => {
      // Cleanup
    }
  }, [])
  return <div>Data</div>
}
```

### Versão no Projeto
- **Versão**: 19.0.0
- **Hooks**: ✅ Usado
- **Server Components**: ✅ Via Next.js
- **Concurrent Features**: ✅ Suportado

---

## 📝 TypeScript 5.7

### Documentação Oficial
- **Handbook**: https://www.typescriptlang.org/docs/handbook/intro.html
- **API Reference**: https://www.typescriptlang.org/docs/handbook/declaration-files/introduction.html
- **Playground**: https://www.typescriptlang.org/play

### Recursos Essenciais

#### Tipos Básicos
- [Basic Types](https://www.typescriptlang.org/docs/handbook/2/everyday-types.html) - Tipos primitivos
- [Interfaces](https://www.typescriptlang.org/docs/handbook/2/everyday-types.html#interfaces) - Interfaces
- [Types](https://www.typescriptlang.org/docs/handbook/2/everyday-types.html#type-aliases) - Type aliases
- [Unions](https://www.typescriptlang.org/docs/handbook/2/everyday-types.html#union-types) - Union types
- [Generics](https://www.typescriptlang.org/docs/handbook/2/generics.html) - Generics

#### Recursos Avançados
- [Utility Types](https://www.typescriptlang.org/docs/handbook/utility-types.html) - Partial, Pick, Omit, etc.
- [Type Guards](https://www.typescriptlang.org/docs/handbook/2/narrowing.html) - Type narrowing
- [Mapped Types](https://www.typescriptlang.org/docs/handbook/2/mapped-types.html) - Tipos mapeados
- [Conditional Types](https://www.typescriptlang.org/docs/handbook/2/conditional-types.html) - Tipos condicionais

#### React + TypeScript
- [React TypeScript Cheatsheet](https://react-typescript-cheatsheet.netlify.app/) - Guia completo
- [Component Props](https://react-typescript-cheatsheet.netlify.app/docs/basic/getting-started/basic_type_example) - Tipando props
- [Hooks](https://react-typescript-cheatsheet.netlify.app/docs/basic/getting-started/hooks) - Tipando hooks

### Cheat Sheet
```typescript
// Tipos básicos
type User = {
  id: number
  name: string
  email?: string  // Opcional
}

// Interface
interface Product {
  id: string
  name: string
  price: number
}

// Union types
type Status = 'pending' | 'approved' | 'rejected'

// Generics
function identity<T>(arg: T): T {
  return arg
}

// Utility types
type PartialUser = Partial<User>
type UserEmail = Pick<User, 'email'>
type UserWithoutId = Omit<User, 'id'>

// React component props
interface ButtonProps {
  label: string
  onClick: () => void
  disabled?: boolean
}

function Button({ label, onClick, disabled }: ButtonProps) {
  return <button onClick={onClick} disabled={disabled}>{label}</button>
}
```

### Configuração no Projeto
- **Versão**: 5.7
- **Strict Mode**: ✅ Habilitado
- **Path Aliases**: ✅ `@/*` configurado
- **Module Resolution**: `bundler` (Next.js)

---

## 🎨 Tailwind CSS 3.4

### Documentação Oficial
- **Docs**: https://tailwindcss.com/docs
- **Utility Classes**: https://tailwindcss.com/docs/utility-first
- **Configuration**: https://tailwindcss.com/docs/configuration

### Recursos Essenciais

#### Conceitos
- [Utility-First](https://tailwindcss.com/docs/utility-first) - Filosofia do Tailwind
- [Responsive Design](https://tailwindcss.com/docs/responsive-design) - Breakpoints
- [Dark Mode](https://tailwindcss.com/docs/dark-mode) - Modo escuro
- [Customization](https://tailwindcss.com/docs/theme) - Customizar tema

#### Classes Principais
- [Layout](https://tailwindcss.com/docs/display) - Display, flexbox, grid
- [Spacing](https://tailwindcss.com/docs/padding) - Padding, margin
- [Typography](https://tailwindcss.com/docs/font-size) - Font size, weight, etc.
- [Colors](https://tailwindcss.com/docs/text-color) - Cores de texto e background
- [Borders](https://tailwindcss.com/docs/border-width) - Bordas e radius
- [Effects](https://tailwindcss.com/docs/box-shadow) - Shadows, opacity

### Cheat Sheet
```tsx
// Layout
<div className="flex items-center justify-between">
<div className="grid grid-cols-1 md:grid-cols-3 gap-4">

// Spacing
<div className="p-4 m-2">           // padding: 1rem, margin: 0.5rem
<div className="px-6 py-8">        // padding horizontal/vertical

// Typography
<h1 className="text-4xl font-bold text-gray-900">
<p className="text-lg text-gray-600 leading-relaxed">

// Colors (customizadas no projeto)
<div className="bg-mimo-brown text-white">
<div className="text-mimo-blue hover:text-mimo-blue-hover">

// Responsive
<div className="text-sm md:text-base lg:text-lg">
<div className="hidden md:block">  // Esconder em mobile

// Hover/Active states
<button className="bg-blue-500 hover:bg-blue-600 active:scale-95">
```

### Customização no Projeto
```typescript
// tailwind.config.ts
theme: {
  extend: {
    colors: {
      'mimo-brown': '#3D1F12',
      'mimo-blue': '#400303',
      'mimo-neutral-light': '#F4EFEB',
      'mimo-neutral-medium': '#CBB9A4',
      'mimo-gold': '#FFF4B9',
    },
    fontFamily: {
      'bueno': ['var(--font-bueno)'],
      'satoshi': ['var(--font-satoshi)'],
    },
  },
}
```

### Versão no Projeto
- **Versão**: 3.4.17
- **JIT Mode**: ✅ Ativo (padrão)
- **Purge CSS**: ✅ Automático
- **Custom Colors**: ✅ Configurado

---

## 🔗 Recursos Adicionais

### Ferramentas Úteis
- **Tailwind Play**: https://play.tailwindcss.com/ - Testar classes online
- **TypeScript Playground**: https://www.typescriptlang.org/play - Testar TypeScript
- **React DevTools**: https://react.dev/learn/react-developer-tools - Extensão do browser

### Comunidades
- **Next.js Discord**: https://nextjs.org/discord
- **React Community**: https://react.dev/community
- **Tailwind Discord**: https://tailwindcss.com/discord

### Tutoriais Recomendados
- [Next.js Learn](https://nextjs.org/learn) - Curso oficial do Next.js
- [React Beta Docs](https://react.dev/learn) - Nova documentação do React
- [TypeScript Deep Dive](https://basarat.gitbook.io/typescript/) - Guia avançado

### Cheat Sheets Externos
- [Next.js Cheat Sheet](https://devhints.io/nextjs)
- [React Cheat Sheet](https://devhints.io/react)
- [TypeScript Cheat Sheet](https://devhints.io/typescript)
- [Tailwind CSS Cheat Sheet](https://devhints.io/tailwindcss)

---

## 📚 Padrões do Projeto

### Estrutura de Componentes
```typescript
// Server Component (padrão)
import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Page Title',
}

export default function Page() {
  return <div>Content</div>
}

// Client Component (quando necessário)
'use client'
import { useState } from 'react'

export default function ClientComponent() {
  const [state, setState] = useState(0)
  return <div>{state}</div>
}
```

### Tipagem de Props
```typescript
// Interface para props
interface ComponentProps {
  title: string
  description?: string
  onClick: () => void
}

export function Component({ title, description, onClick }: ComponentProps) {
  return <div>{title}</div>
}
```

### Classes Tailwind com cn()
```typescript
import { cn } from '@/lib/utils'

function Button({ className, variant }: ButtonProps) {
  return (
    <button
      className={cn(
        'px-4 py-2 rounded-lg',  // Base
        variant === 'primary' && 'bg-blue-500',  // Condicional
        className  // Custom classes
      )}
    >
      Click
    </button>
  )
}
```

---

**Última atualização**: 2025-01-30  
**Versão do projeto**: 1.5.2

