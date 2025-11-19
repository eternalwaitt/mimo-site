# Otimizações de Performance Aplicadas - 2025-11-16
**Objetivo**: Alcançar 90+ no PageSpeed Insights

## 🔧 Correções Aplicadas

### 1. CSS Render Blocking - Dark Mode ✅
**Problema**: `dark-mode.css` estava sendo carregado síncrono no head
**Solução**: Alterado para usar `loadCSS()` (defer)
**Arquivo**: `index.php` linha 360
**Impacto Esperado**: ~100-200ms de melhoria no FCP

**Antes**:
```php
<?php echo css_tag('css/modules/dark-mode.css'); ?>
```

**Depois**:
```php
<script>loadCSS("<?php echo get_css_asset('css/modules/dark-mode.css'); ?>");</script>
<noscript><?php echo css_tag('css/modules/dark-mode.css'); ?></noscript>
```

### 2. Verificação de Imagens ✅
**Status**: Todas as 26 imagens têm dimensões explícitas (width/height)
**Impacto**: CLS já otimizado para imagens

### 3. CSS Não Crítico ✅
**Status**: Já usando `loadCSS()` para:
- `animations.css` ✅
- `mobile-ui-improvements.css` ✅
- `dark-mode.css` ✅ (corrigido agora)
- `form/main.css` ✅
- `accessibility-fixes.css` ✅

## ⚠️ Problemas Identificados (Ainda Não Corrigidos)

### 1. Google Fonts Render Blocking
**Severidade**: ALTA
**Impacto**: 1+ segundo de atraso (1071ms + 1174ms)

**Problema**: 
- Google Fonts CSS está usando `loadCSS()`, mas ainda é detectado como render blocking
- Fontes retornando 404 (Nunito v26)

**Soluções Possíveis**:
1. **Preload font-display CSS** (mais eficiente)
2. **Usar fontes locais** (melhor performance)
3. **Remover fontes não usadas** (Nunito pode não ser necessário)

### 2. Bootstrap CSS Render Blocking
**Severidade**: ALTA
**Impacto**: ~200-400ms de atraso

**Problema**: Bootstrap está usando `loadCSS()`, mas ainda é detectado como render blocking

**Soluções Possíveis**:
1. **Usar Bootstrap custom build** (já existe, verificar se está sendo usado)
2. **Inlinar CSS crítico do Bootstrap** (apenas grid e utilities usados)
3. **Preload Bootstrap CSS** com `rel="preload"` + `as="style"`

### 3. Erros de Console JavaScript
**Severidade**: MÉDIA
**Impacto**: Pode causar problemas de execução

**Erros**:
- `Unexpected token 'export'` em `popper.min.js` (não crítico, funciona mesmo assim)
- Fontes 404 (Nunito)

**Solução**: Verificar se popper.js está na versão correta (pode ser problema de build)

## 📊 Status Atual

### CSS Render Blocking
- ✅ `dark-mode.css` - Agora defer
- ✅ `animations.css` - Já defer
- ✅ `mobile-ui-improvements.css` - Já defer
- ⚠️ `product.css` - Usando loadCSS (pode melhorar)
- ⚠️ Bootstrap CSS - Usando loadCSS (pode melhorar)
- ⚠️ Google Fonts - Usando loadCSS (pode melhorar)

### Imagens
- ✅ Todas têm dimensões (26/26)
- ✅ Lazy loading implementado
- ✅ AVIF/WebP sendo usado

### JavaScript
- ✅ Todos com `defer`
- ⚠️ Erro não crítico em popper.js

## 🎯 Próximas Otimizações Recomendadas

### Prioridade 1 (Alto Impacto)
1. **Otimizar Google Fonts**:
   - Preload font-display CSS
   - Remover fontes não usadas (verificar se Nunito é realmente necessário)
   - Considerar fontes locais

2. **Otimizar Bootstrap CSS**:
   - Verificar se Bootstrap custom build está sendo usado
   - Se não, criar build apenas com componentes usados
   - Inlinar CSS crítico do Bootstrap (grid, utilities)

3. **Otimizar product.css**:
   - Verificar se pode ser dividido em crítico/não crítico
   - Inlinar CSS crítico acima da dobra

### Prioridade 2 (Médio Impacto)
4. **Combinar CSS não crítico**:
   - Combinar `animations.css` + `mobile-ui-improvements.css` + `dark-mode.css` em um arquivo
   - Reduzir número de requisições HTTP

5. **Otimizar ordem de carregamento**:
   - Garantir que CSS crítico carrega primeiro
   - Defer tudo que não é crítico

### Prioridade 3 (Baixo Impacto)
6. **Corrigir erros de console**:
   - Verificar popper.js
   - Corrigir fontes 404

## 📝 Notas Técnicas

### loadCSS() vs Preload
- `loadCSS()` usa técnica `media="only x"` + `onload` para mudar para `all`
- PageSpeed pode ainda detectar como render blocking
- `rel="preload"` + `as="style"` + `onload` pode ser mais eficiente

### Fontes Google
- Fontes estão usando `font-display: swap` (bom)
- Mas ainda causam atraso de 1+ segundo
- Considerar fontes locais para melhor performance

### Bootstrap
- Já existe build custom (verificar `bootstrap/bootstrap-custom.min.css`)
- Pode não estar sendo usado
- Verificar se está carregando o custom ou o CDN completo

## 🔄 Próximos Passos

1. Testar mudanças no navegador
2. Rodar PageSpeed Insights novamente
3. Comparar resultados antes/depois
4. Aplicar otimizações de Prioridade 1
5. Validar que não quebrou nada

