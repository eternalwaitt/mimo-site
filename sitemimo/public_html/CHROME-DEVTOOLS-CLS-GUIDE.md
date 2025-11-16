# Guia: Identificar CLS com Chrome DevTools

**Objetivo**: Identificar elementos específicos causando Cumulative Layout Shift (CLS)

---

## 📋 Método 1: Performance Tab (Recomendado)

### Passo 1: Abrir Chrome DevTools
1. Abra o site em Chrome
2. Pressione `F12` ou `Cmd+Option+I` (Mac) / `Ctrl+Shift+I` (Windows/Linux)
3. Vá para a aba **Performance**

### Passo 2: Gravar Performance
1. Clique no botão **Record** (círculo vermelho) ou pressione `Cmd+E` (Mac) / `Ctrl+E` (Windows/Linux)
2. **Recarregue a página** (F5 ou Cmd+R)
3. Aguarde a página carregar completamente
4. Clique em **Stop** (ou pressione `Cmd+E` / `Ctrl+E` novamente)

### Passo 3: Analisar Layout Shifts
1. Na timeline, procure por **"Layout Shift"** ou **"LS"** (ícone de quadrado com seta)
2. Clique em cada evento de Layout Shift
3. No painel inferior, você verá:
   - **Elemento afetado**: HTML/CSS do elemento
   - **Antes/Depois**: Dimensões e posição antes e depois do shift
   - **Causa**: O que causou o shift (imagem carregando, fonte, etc.)

### Passo 4: Identificar Padrões
- Anote os elementos que aparecem frequentemente
- Verifique se são imagens, fontes, ou conteúdo dinâmico
- Veja o timestamp de quando ocorrem (durante carregamento inicial?)

---

## 📋 Método 2: Rendering Tab (Visual)

### Passo 1: Habilitar Rendering
1. Abra Chrome DevTools
2. Pressione `Cmd+Shift+P` (Mac) / `Ctrl+Shift+P` (Windows/Linux)
3. Digite "Show Rendering"
4. Selecione **"Show Rendering"**

### Passo 2: Habilitar Layout Shift Regions
1. No painel Rendering que aparece, marque:
   - ✅ **"Layout Shift Regions"** - Mostra áreas que mudaram de posição
2. Recarregue a página
3. Você verá **retângulos coloridos** indicando áreas que causaram layout shift

### Passo 3: Analisar Visualmente
- **Azul**: Áreas que mudaram de posição
- Clique nos retângulos para ver detalhes no console
- Anote quais seções da página estão causando mais shifts

---

## 📋 Método 3: Console API (Programático)

### Adicionar ao Código
Adicione este código temporariamente ao final do `<body>` em `index.php`:

```javascript
// Registrar todos os layout shifts
new PerformanceObserver((list) => {
    for (const entry of list.getEntries()) {
        if (!entry.hadRecentInput) {
            console.log('Layout Shift:', {
                value: entry.value,
                sources: entry.sources.map(s => ({
                    node: s.node,
                    previousRect: s.previousRect,
                    currentRect: s.currentRect
                }))
            });
        }
    }
}).observe({type: 'layout-shift', buffered: true});
```

### Ver Resultados
1. Abra o Console (F12 > Console)
2. Recarregue a página
3. Veja os logs de cada layout shift com detalhes

---

## 📋 Método 4: Lighthouse (Já Temos)

### Usar Script de Análise
```bash
cd sitemimo/public_html
node scripts/analyze-cls.js pagespeed-results/validation-mobile-*.json
```

Este script extrai informações detalhadas dos dados do Lighthouse.

---

## 🎯 O Que Procurar

### Elementos Comuns que Causam CLS:

1. **Imagens sem dimensões**
   - Procure por `<img>` sem `width`/`height`
   - Solução: Adicionar dimensões explícitas

2. **Fontes carregando**
   - Shifts durante carregamento de fontes
   - Solução: `font-display: optional` ou `size-adjust`

3. **Conteúdo dinâmico**
   - Google Reviews, carousels, etc.
   - Solução: Reservar espaço desde o início

4. **CSS assíncrono**
   - Shifts quando CSS carrega
   - Solução: Inline CSS crítico

5. **JavaScript manipulando DOM**
   - Shifts causados por JS
   - Solução: Usar `requestAnimationFrame`

---

## 📊 Interpretando Resultados

### CLS Score:
- **< 0.1**: ✅ Excelente
- **0.1 - 0.25**: ⚠️ Precisa melhorar
- **> 0.25**: ❌ Ruim

### Layout Shift Value:
- Cada shift tem um valor (0.0 - 1.0+)
- Soma de todos = CLS total
- Foque nos shifts com maior valor

---

## 🔧 Correções Baseadas em Resultados

### Se for Imagem:
```html
<!-- ❌ Ruim -->
<img src="image.jpg" alt="...">

<!-- ✅ Bom -->
<img src="image.jpg" alt="..." width="800" height="600" style="aspect-ratio: 4/3;">
```

### Se for Fonte:
```css
/* ✅ Bom */
@font-face {
    font-family: 'MyFont';
    font-display: optional; /* ou swap */
    size-adjust: 100%;
    ascent-override: 90%;
}
```

### Se for Conteúdo Dinâmico:
```css
/* ✅ Reservar espaço */
.container {
    min-height: 500px; /* Altura esperada */
    contain: layout;
}
```

---

## 📝 Checklist de Análise

- [ ] Gravar performance com DevTools
- [ ] Identificar todos os layout shifts
- [ ] Anotar elementos causando shifts
- [ ] Verificar se são imagens, fontes, ou JS
- [ ] Aplicar correções específicas
- [ ] Re-testar para verificar melhorias

---

## 🚀 Próximos Passos

1. **Execute a análise** usando um dos métodos acima
2. **Anote os elementos** que mais causam CLS
3. **Aplique correções específicas** para cada elemento
4. **Re-teste** para verificar melhorias

---

## 📚 Referências

- [Web.dev: Debug Layout Shifts](https://web.dev/debug-layout-shifts/)
- [Chrome DevTools: Performance](https://developer.chrome.com/docs/devtools/performance/)
- [CLS Debugger Extension](https://chrome.google.com/webstore/detail/cls-debugger/bfcfoeggeijacgchlhmfhggokldhhlgc)

