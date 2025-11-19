# Build Scripts - Otimizações de Performance

Scripts para otimizar assets e melhorar performance do site.

## Scripts Disponíveis

### 1. `minify-assets.sh` - Minificação de CSS e JavaScript

**Uso:**
```bash
cd sitemimo/public_html
./build/minify-assets.sh
```

**O que faz:**
- Minifica todos os arquivos CSS em `css/` e `css/modules/`
- Minifica todos os arquivos JavaScript em `js/`
- Salva arquivos minificados em `minified/`
- Calcula economia de bytes para cada arquivo

**Pré-requisitos:**
- Node.js e npm instalados
- `terser` instalado globalmente: `npm install -g terser`
- `csso-cli` instalado globalmente: `npm install -g csso-cli`

**Nota:** O script instala automaticamente as dependências se não estiverem disponíveis.

### 2. `purge-css.sh` - Remover CSS Não Utilizado

**Uso:**
```bash
cd sitemimo/public_html
./build/purge-css.sh
```

**O que faz:**
- Analisa todos os arquivos PHP para encontrar classes CSS usadas
- Remove CSS não utilizado de `product.css`, `dark-mode.css`, `animations.css`
- Salva arquivos purificados em `css/purged/`
- Calcula economia de bytes

**Pré-requisitos:**
- Node.js e npm instalados
- `purgecss` instalado globalmente: `npm install -g purgecss`

**⚠️ IMPORTANTE:** Revise os arquivos purificados antes de usar em produção! Alguns estilos podem ser removidos incorretamente se usados via JavaScript.

**Safelist:** O script mantém automaticamente estas classes:
- `carousel`, `fade-in`, `fade-out`, `dark-mode`, `light-mode`, `visible`, `hidden`

### 3. `generate-avif-main-images.sh` - Gerar Imagens AVIF

**Uso:**
```bash
cd sitemimo/public_html/build
./generate-avif-main-images.sh
```

**O que faz:**
- Converte imagens principais (JPG/PNG) para formato AVIF
- Gera versões 1x, 2x, 3x se existirem
- Salva em `../img/` com extensão `.avif`

**Pré-requisitos:**
- `avifenc` (libavif) ou ImageMagick instalado

## Workflow Recomendado

### Para Desenvolvimento:
1. Desative `USE_MINIFIED` em `config.php`
2. Trabalhe normalmente com arquivos originais

### Para Produção:
1. Execute `minify-assets.sh` para gerar arquivos minificados
2. (Opcional) Execute `purge-css.sh` e revise os arquivos purificados
3. Ative `USE_MINIFIED` em `config.php`
4. Atualize `ASSET_VERSION` em `config.php` para forçar cache busting
5. Faça deploy

## Estrutura de Arquivos

```
sitemimo/public_html/
├── build/
│   ├── minify-assets.sh
│   ├── purge-css.sh
│   ├── generate-avif-main-images.sh
│   └── README.md
├── minified/
│   ├── product.min.css
│   ├── main.min.js
│   └── ...
├── css/
│   ├── purged/          # Arquivos CSS purificados (após purge-css.sh)
│   └── modules/
└── js/
```

## Troubleshooting

### Erro: "command not found: terser"
```bash
npm install -g terser
```

### Erro: "command not found: csso-cli"
```bash
npm install -g csso-cli
```

### Erro: "command not found: purgecss"
```bash
npm install -g purgecss
```

### Arquivos minificados não são usados
1. Verifique se `USE_MINIFIED` está `true` em `config.php`
2. Verifique se os arquivos existem em `minified/`
3. Verifique se os nomes dos arquivos correspondem (ex: `product.css` → `product.min.css`)

## Economia Esperada

- **Minificação CSS**: ~10-30% de redução
- **Minificação JS**: ~20-40% de redução
- **PurgeCSS**: ~30-60% de redução (depende do CSS não utilizado)
- **Total**: Pode reduzir payload em 50-70% para CSS/JS

## Referências

- [Terser Documentation](https://terser.org/)
- [CSSO Documentation](https://github.com/css/csso)
- [PurgeCSS Documentation](https://purgecss.com/)
