# Como Verificar se Arquivos Minificados Foram Deployados

## Método 1: Script Automatizado

Execute o script de verificação:

```bash
./build/verify-deployment.sh
```

O script verifica:
- ✅ Arquivos minificados (CSS/JS)
- ✅ Arquivos purgados (CSS)
- ✅ Imagens AVIF/WebP
- ✅ Asset version no HTML
- ✅ Headers de cache

## Método 2: Verificação Manual via Browser

### 1. Verificar Source do HTML

1. Abra https://minhamimo.com.br/
2. View Source (Ctrl+U / Cmd+U)
3. Procure por:
   - `minified/` ou `css/purged/`
   - `v=20251115-2` (asset version)

**Se encontrar**: Arquivos estão sendo referenciados ✅  
**Se não encontrar**: USE_MINIFIED pode estar false ou arquivos não existem ❌

### 2. Verificar Network Tab (DevTools)

1. Abra DevTools (F12)
2. Vá para Network tab
3. Recarregue a página (Ctrl+R / Cmd+R)
4. Procure por arquivos CSS/JS:
   - Devem ter `.min.css` ou `.min.js` no nome
   - Devem vir de `minified/` ou `css/purged/`
   - Devem ter `v=20251115-2` na URL

**Exemplos de URLs esperadas**:
- `https://minhamimo.com.br/minified/product.min.css?v=20251115-2`
- `https://minhamimo.com.br/css/purged/product.min.css?v=20251115-2`
- `https://minhamimo.com.br/minified/main.min.js?v=20251115-2`

### 3. Verificar Tamanho dos Arquivos

Arquivos minificados devem ser **menores** que os originais:

| Arquivo | Original | Minificado | Redução Esperada |
|---------|----------|------------|------------------|
| product.css | ~58 KiB | ~45 KiB | ~22% |
| main.js | ~4.4 KiB | ~3.5 KiB | ~20% |

**Como verificar**:
1. Network tab → Clique no arquivo CSS/JS
2. Veja o tamanho em "Size" ou "Transferred"
3. Compare com tamanho original

## Método 3: Verificação via cURL

### Verificar se arquivo existe e tamanho:

```bash
# CSS minificado
curl -I "https://minhamimo.com.br/minified/product.min.css?v=20251115-2"

# JS minificado
curl -I "https://minhamimo.com.br/minified/main.min.js?v=20251115-2"

# CSS purgado
curl -I "https://minhamimo.com.br/css/purged/product.min.css?v=20251115-2"
```

**Resposta esperada**: `HTTP/2 200` com `Content-Type: text/css` ou `application/javascript`

### Verificar conteúdo (deve estar minificado):

```bash
# Deve retornar CSS minificado (sem quebras de linha, espaços reduzidos)
curl "https://minhamimo.com.br/minified/product.min.css?v=20251115-2" | head -c 200
```

**Se minificado**: Conteúdo sem quebras de linha, espaços reduzidos ✅  
**Se não minificado**: Conteúdo formatado com quebras de linha ❌

## Método 4: Verificar Asset Helper

### Verificar se USE_MINIFIED está ativo:

1. Abra https://minhamimo.com.br/
2. View Source
3. Procure por `get_css_asset` ou `get_js_asset`
4. Verifique se URLs apontam para `minified/` ou `css/purged/`

**Exemplo esperado**:
```html
<link rel="stylesheet" href="minified/product.min.css?v=20251115-2">
```

**Se não minificado**:
```html
<link rel="stylesheet" href="product.css?v=20251115-2">
```

## Método 5: Verificar Imagens AVIF/WebP

### Verificar se imagens otimizadas existem:

```bash
# Verificar AVIF
curl -I "https://minhamimo.com.br/img/bgheader.avif"
curl -I "https://minhamimo.com.br/img/mimo5.avif"

# Verificar WebP
curl -I "https://minhamimo.com.br/img/bgheader.webp"
curl -I "https://minhamimo.com.br/img/mimo5.webp"
```

**Resposta esperada**: `HTTP/2 200` com `Content-Type: image/avif` ou `image/webp`

### Verificar no HTML (picture element):

1. View Source
2. Procure por `<picture>` elements
3. Devem ter `<source type="image/avif">` e `<source type="image/webp">`

## Checklist de Verificação

- [ ] Arquivos minificados existem (HTTP 200)
- [ ] Arquivos minificados são menores que originais
- [ ] HTML referencia arquivos minificados
- [ ] Asset version está correto (20251115-2)
- [ ] Imagens AVIF/WebP existem
- [ ] Picture elements usam AVIF/WebP
- [ ] USE_MINIFIED=true em produção

## Problemas Comuns

### Arquivos retornam 404
**Causa**: Arquivos não foram deployados  
**Solução**: Fazer deploy dos arquivos em `minified/` e `css/purged/`

### Arquivos existem mas não são minificados
**Causa**: USE_MINIFIED=false ou asset helper não está usando arquivos corretos  
**Solução**: Verificar `config.php` em produção, garantir `USE_MINIFIED=true`

### Asset version está errado
**Causa**: `config.php` não foi atualizado em produção  
**Solução**: Atualizar `ASSET_VERSION` em produção para `20251115-2`

### Imagens AVIF/WebP não existem
**Causa**: Imagens não foram deployadas  
**Solução**: Fazer deploy das imagens otimizadas em `img/`

## Próximos Passos Após Verificação

1. **Se tudo OK**: Aguardar 15-30 min para cache propagar, depois re-testar
2. **Se arquivos faltando**: Fazer deploy dos arquivos faltantes
3. **Se config errado**: Atualizar `config.php` em produção
4. **Re-testar**: Após correções, re-executar PageSpeed Insights

