# Performance Checklist

Checklist rápido para revisar antes de cada PR. Imprima ou mantenha aberto durante desenvolvimento.

## 🎯 Targets de Performance

- **Performance Score**: ≥95 (mobile)
- **LCP**: <2.5s
- **FCP**: <1.5s
- **CLS**: <0.1
- **TBT**: <200ms

## ✅ Checklist de Código

### Componentes

- [ ] Componente é server component (sem `'use client'` desnecessário)
- [ ] Framer Motion apenas abaixo do fold ou para animações complexas
- [ ] CSS animations para animações simples (fade, scale, etc.)
- [ ] Imagens usam `ImageWithFallback` ou `next/image`
- [ ] Todas as imagens têm `sizes` attribute correto
- [ ] Imagens acima do fold têm `priority` e `fetchPriority="high"`
- [ ] Imagens abaixo do fold não têm `priority` (lazy loading)

### Páginas

- [ ] Metadata completa e otimizada
- [ ] Estrutura: Header + Main + Footer
- [ ] `id="main-content"` no `<main>` (para skip link)
- [ ] Seções grandes abaixo do fold usam `content-visibility: auto`
- [ ] Sem imports desnecessários de bibliotecas pesadas
- [ ] Dynamic imports para componentes pesados abaixo do fold

### Imagens

- [ ] Formato: WebP ou AVIF (Next.js converte automaticamente)
- [ ] `sizes` attribute com breakpoints mobile/desktop
- [ ] `alt` text descritivo
- [ ] `width` e `height` especificados (ou `fill` com container)
- [ ] Hero image: `priority` + `fetchPriority="high"`
- [ ] Imagens abaixo do fold: sem `priority` (lazy loading)

### Animações

- [ ] CSS animations para acima do fold
- [ ] Framer Motion apenas abaixo do fold
- [ ] Animações não bloqueiam renderização
- [ ] `will-change` apenas quando necessário

### JavaScript

- [ ] Sem client components desnecessários
- [ ] Hooks apenas quando necessário
- [ ] Event handlers apenas quando necessário
- [ ] Browser APIs apenas quando necessário
- [ ] Dynamic imports para bibliotecas pesadas

## 🧪 Validação

### Antes de Commit

- [ ] `npm run type-check` passa
- [ ] `npm run lint` passa
- [ ] Pre-commit hook não falhou

### Antes de Push

- [ ] `npm run pre-deploy` passa
- [ ] Build funciona localmente
- [ ] Página testada no navegador

### Antes de Merge

- [ ] CI/CD passou (GitHub Actions)
- [ ] Lighthouse Score ≥95 (se página importante)
- [ ] LCP <2.5s (se página importante)
- [ ] Sem warnings no console

## 📊 Métricas

### Como Verificar

```bash
# Lighthouse local (requer API key)
npm run lighthouse:home

# Build e análise
npm run build
npm run analyze
```

### O Que Procurar

- **First Load JS**: ≤150 KiB (home), ≤200 KiB (outras)
- **LCP Element**: Hero image (não galeria ou embed)
- **Unused JS**: Minimizar (Lighthouse mostra oportunidades)
- **Image Sizes**: Mobile não deve baixar imagens desktop-size

## 🚨 Red Flags

Se você ver qualquer um destes, **pare e corrija**:

- ❌ Framer Motion em componente acima do fold
- ❌ `'use client'` sem necessidade
- ❌ Imagem sem `sizes` attribute
- ❌ Imagem grande sem otimização
- ❌ Import de biblioteca pesada no layout
- ❌ Animação bloqueando renderização
- ❌ LCP >2.5s
- ❌ Performance <95

## 💡 Quick Tips

1. **Server Components First**: Comece sempre como server component
2. **CSS > JS**: Animações CSS são sempre mais rápidas
3. **Teste Mobile**: Sempre teste em mobile (Lighthouse mobile)
4. **Sizes Correto**: Mobile não deve baixar imagens desktop-size
5. **Lazy Load**: Abaixo do fold = lazy loading automático

## 📚 Referências

- **Guia Completo**: `docs/guides/ADDING-NEW-PAGES.md`
- **Performance Guide**: `docs/performance/PERFORMANCE-GUIDE.md`
- **Templates**: `docs/guides/templates/`

---

**Lembre-se**: Performance não é opcional. É parte do produto.

