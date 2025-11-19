# Mapeamento Completo de Assets

**Data**: 2025-11-16  
**Total**: ~118MB de assets

---

## Estrutura Atual (Espalhada)

### 📁 Imagens (`img/`) - **116MB**
```
img/
├── bgheader.* (avif, webp, jpg, png)
├── header_dezembro_mobile.* (avif, webp, png)
├── logobranco.* (avif, webp, png)
├── categoria_*.avif (cilios, facial, etc.)
├── depo/ (depoimentos - avif, webp, jpeg)
├── promocional/ (desktop - dez/, jan/)
├── mobile_promocional/ (mobile - dez/, jan/)
└── servicos/
    ├── cilios/ (32 arquivos)
    ├── corporal/ (45 arquivos)
    ├── esmalteria/ (33 arquivos)
    ├── facial/ (33 arquivos)
    ├── micro/ (9 arquivos)
    └── salao/ (47 arquivos)
```

**Uso**: Referenciadas diretamente em HTML via `<img>` e `picture_webp()`

---

### 📁 CSS (`css/`) - **156KB**
```
css/
├── modules/
│   ├── _variables.css
│   ├── accessibility-fixes.css
│   ├── animations.css
│   ├── dark-mode.css
│   ├── mobile-ui-improvements.css
│   └── testimonials-overrides.css
├── purged/ (arquivos purged e minificados)
│   ├── product.css / product.min.css
│   ├── animations.css / animations.min.css
│   ├── dark-mode.css / dark-mode.min.css
│   ├── accessibility-fixes.css / accessibility-fixes.min.css
│   └── mobile-ui-improvements.css / mobile-ui-improvements.min.css
└── combined-non-critical.min.css
```

**Uso**: Carregados via `get_css_asset()` e `css_tag()` em `inc/asset-helper.php`

**Arquivos principais**:
- `product.css` (raiz) - CSS principal do site
- `servicos.css` (raiz) - CSS para páginas de serviços
- `inc/critical-css.php` - CSS crítico inline

---

### 📁 JavaScript (`js/`) - **32KB**
```
js/
├── animations.js
├── bc-swipe.js
├── combined.min.js
├── dark-mode.js
└── loadcss-polyfill.js
```

**Uso**: Carregados via `get_js_asset()` e `js_tag()` em `inc/asset-helper.php`

**Arquivos principais**:
- `main.js` (raiz) - JS principal do site
- `form/main.js` - JS para formulários

---

### 📁 Bootstrap (`bootstrap/`) - **7.2MB**
```
bootstrap/
├── bootstrap/
│   ├── dist/
│   │   ├── css/ (não usado - usa CDN)
│   │   └── js/ (bootstrap.min.js usado)
│   └── js/src/ (código fonte)
├── jquery/
│   └── dist/ (jquery.slim.min.js - fallback)
└── popper.js/
    └── dist/ (popper.min.js usado)
```

**Uso**: 
- CSS: CDN (`stackpath.bootstrapcdn.com`)
- JS: Local (`bootstrap/bootstrap/dist/js/bootstrap.min.js`)
- jQuery: CDN com fallback local
- Popper: Local (`bootstrap/popper.js/dist/popper.min.js`)

---

### 📁 Formulários (`form/`) - **1.4MB**
```
form/
├── css/
│   ├── font-awesome.css / font-awesome.min.css
│   └── main.css
├── fonts/ (Font Awesome)
│   ├── fontawesome-webfont.woff2
│   ├── fontawesome-webfont.woff
│   ├── fontawesome-webfont.ttf
│   ├── fontawesome-webfont.svg
│   ├── fontawesome-webfont.eot
│   └── FontAwesome.otf
└── main.js
```

**Uso**: Carregados em páginas com formulários

---

### 📁 Favicons (`favicon/`) - **100KB**
```
favicon/
├── apple-touch-icon.png
├── favicon-32x32.png
├── favicon-16x16.png
└── ... (outros tamanhos)
```

**Uso**: Referenciados no `<head>` de todas as páginas

---

### 📁 Scripts (`scripts/`) - **118MB** ⚠️
```
scripts/
├── analyze-all-issues.js
├── analyze-cls.js
├── google_reviews.* (ids, json)
├── limpar-reviews.php
├── validate-images.php
└── temp-scraper/ (118MB - ambiente virtual Python)
    ├── venv/ (⚠️ NÃO DEVERIA ESTAR EM PRODUÇÃO)
    ├── modules/ (scraper Python)
    └── config-mimo.yaml
```

**⚠️ ATENÇÃO**: 
- `temp-scraper/venv/` (118MB) é um ambiente virtual Python
- **NÃO DEVERIA estar em produção** - adicionar ao `.gitignore`
- Scripts PHP/JS são pequenos (~20KB total)

---

### 📁 Vendor (`vendor/`) - **536KB**
```
vendor/
└── (dependências PHP - provavelmente Composer)
```

**Uso**: Dependências PHP (não assets frontend)

---

## Estrutura Proposta (Consolidada)

Se quiser consolidar tudo em uma pasta `assets/`:

```
assets/
├── img/              (116MB - mover de img/)
├── css/              (156KB - mover de css/)
├── js/               (32KB - mover de js/)
├── fonts/            (1.4MB - mover de form/fonts/)
├── favicon/          (100KB - mover de favicon/)
└── lib/              (7.2MB - mover de bootstrap/)
    ├── bootstrap/
    ├── jquery/
    └── popper.js/
```

**Vantagens**:
- ✅ Tudo em um lugar
- ✅ Mais fácil de gerenciar
- ✅ Mais fácil de fazer backup
- ✅ Mais fácil de otimizar

**Desvantagens**:
- ⚠️ Precisa atualizar todos os caminhos no código
- ⚠️ Precisa atualizar `inc/asset-helper.php`
- ⚠️ Precisa atualizar referências em HTML

---

## Referências no Código

### CSS
- `get_css_asset('product.css')` → `product.css` (raiz)
- `get_css_asset('css/modules/dark-mode.css')` → `css/modules/dark-mode.css`
- `get_css_asset('servicos.css')` → `servicos.css` (raiz)
- `get_css_asset('form/main.css')` → `form/main.css`

### JavaScript
- `get_js_asset('main.js')` → `main.js` (raiz)
- `get_js_asset('js/dark-mode.js')` → `js/dark-mode.js`
- `get_js_asset('form/main.js')` → `form/main.js`
- `get_js_asset('js/bc-swipe.js')` → `js/bc-swipe.js`

### Imagens
- `picture_webp()` em `inc/image-helper.php` → `img/`
- Referências diretas em HTML → `img/`

### Bootstrap
- CSS: CDN (`stackpath.bootstrapcdn.com`)
- JS: `bootstrap/bootstrap/dist/js/bootstrap.min.js`
- jQuery: CDN + fallback `bootstrap/jquery/dist/jquery.slim.min.js`
- Popper: `bootstrap/popper.js/dist/popper.min.js`

---

## Próximos Passos

### Opção 1: Manter Estrutura Atual
- ✅ Funciona bem
- ✅ Código já está configurado
- ✅ Não precisa mudar nada

### Opção 2: Consolidar em `assets/`
- ⚠️ Requer refatoração completa
- ⚠️ Precisa atualizar:
  - `inc/asset-helper.php`
  - `inc/image-helper.php`
  - Todas as referências em HTML/PHP
  - Scripts de build (PurgeCSS, minify)

### Opção 3: Criar Símbolo/Atalho
- Criar `assets/` como symlink para estrutura atual
- Melhor dos dois mundos

---

## Comandos Úteis

### Listar todos os assets
```bash
find img css js bootstrap form favicon -type f | wc -l
```

### Tamanho total
```bash
du -sh img css js bootstrap form favicon
```

### Encontrar referências a assets
```bash
grep -r "get_css_asset\|get_js_asset\|picture_webp" --include="*.php" .
```

---

## Referências

- `inc/asset-helper.php` - Funções de carregamento de assets
- `inc/image-helper.php` - Funções de imagens
- `config.php` - Configurações (ASSET_VERSION, USE_MINIFIED)
- `purgecss.config.js` - Configuração do PurgeCSS

