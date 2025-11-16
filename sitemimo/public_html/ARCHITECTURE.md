# Arquitetura do Site MIMO - Documentação Master para IA

**Versão**: 2.6.9  
**Última Atualização**: 2025-11-16  
**Objetivo**: Documentação completa e estruturada para facilitar entendimento por IA

---

## 📋 Índice

1. [Visão Geral](#visao-geral)
2. [Stack Tecnológico](#stack)
3. [Estrutura de Diretórios](#estrutura)
4. [Fluxo de Carregamento](#fluxo)
5. [Sistema de Helpers](#helpers)
6. [Padrões de Código](#padroes)
7. [Sistema de Configuração](#config)
8. [Performance e Otimizações](#performance)
9. [SEO e Meta Tags](#seo)
10. [Segurança](#seguranca)

---

## 🎯 Visão Geral {#visao-geral}

### Propósito do Site
Site institucional para centro de estética e beleza (MIMO Estética), com:
- Homepage com formulário de contato
- 6 páginas de serviços (Cílios, Esmalteria, Estética Corporal, Estética Facial, Micropigmentação, Salão)
- Sistema de reviews do Google
- SEO otimizado
- Performance otimizada (meta: 90+ no PageSpeed)

### Tecnologias Principais
- **Backend**: PHP 7.1+ (produção), PHP 8.4 (desenvolvimento)
- **Frontend**: HTML5, CSS3, JavaScript (ES5+)
- **Framework CSS**: Bootstrap 4.5.2
- **JavaScript**: jQuery 3.3.1
- **Email**: PHPMailer + Mailgun SMTP
- **Build**: Shell scripts (bash)

---

## 🛠️ Stack Tecnológico {#stack}

### Backend
```
PHP 7.1.33+ (produção)
PHP 8.4 (desenvolvimento)
Composer (gerenciamento de dependências)
```

### Frontend
```
HTML5 (semântico)
CSS3 (com variáveis CSS)
JavaScript ES5+ (compatível com navegadores antigos)
Bootstrap 4.5.2 (CDN + local fallback)
jQuery 3.3.1 (CDN + local fallback)
Lucide Icons (substituiu Font Awesome)
```

### Build & Deploy
```
Shell scripts (bash)
- convert-webp.sh: Conversão de imagens
- minify-css.sh: Minificação CSS
- minify-js.sh: Minificação JS
- purge-css.sh: Remoção de CSS não usado
```

### Dependências PHP (Composer)
```
phpmailer/phpmailer: Envio de emails
```

---

## 📁 Estrutura de Diretórios {#estrutura}

```
public_html/
├── index.php                    # Homepage (formulário de contato)
├── contato.php                  # Página de contato dedicada
├── vagas.php                    # Página de vagas
├── 404.php                      # Página de erro 404
├── config.php                   # ⭐ Configuração central (versão, env vars)
├── product.css                  # CSS principal (global)
├── servicos.css                 # CSS específico de serviços
├── main.js                      # JavaScript principal
├── sitemap.xml                  # Sitemap para SEO
├── robots.txt                   # Instruções para crawlers
│
├── inc/                         # ⭐ Includes compartilhados (HELPERS)
│   ├── header.php               # Navegação homepage
│   ├── header-inner.php         # Navegação páginas internas
│   ├── gtm-head.php            # Google Tag Manager (head)
│   ├── gtm-body.php            # Google Tag Manager (body)
│   ├── security-headers.php    # Cabeçalhos de segurança HTTP
│   ├── cache-headers.php      # Cache headers para assets
│   ├── critical-css.php        # CSS crítico inline (above-the-fold)
│   ├── image-helper.php        # ⭐ Funções de imagem WebP/AVIF
│   ├── seo-helper.php          # ⭐ Funções de SEO (meta tags, Schema.org)
│   ├── asset-helper.php        # ⭐ Funções de assets (CSS/JS com minificação)
│   ├── icon-helper.php         # ⭐ Funções de ícones Lucide
│   ├── form-security.php       # Validação e sanitização de formulários
│   ├── google-reviews.php      # Sistema de reviews do Google
│   ├── manual-reviews.php      # Reviews manuais (fallback)
│   ├── breadcrumbs.php         # Breadcrumbs com Schema.org
│   ├── back-to-top.php         # Botão "voltar ao topo"
│   └── service-template.php    # ⭐ Template de páginas de serviço
│
├── [servicos]/                  # Páginas de serviço (6 total)
│   ├── cilios/
│   ├── esmalteria/
│   ├── estetica/
│   ├── esteticafacial/
│   ├── micropigmentacao/
│   └── salao/
│       └── index.php           # Cada uma usa service-template.php
│
├── img/                         # Imagens do site
│   ├── servicos/               # Imagens por serviço
│   ├── depo/                   # Depoimentos
│   └── [outras categorias]
│
├── css/                         # CSS modular
│   └── modules/
│       ├── _variables.css      # Variáveis CSS (design tokens)
│       ├── dark-mode.css        # Estilos dark mode
│       └── mobile-ui-improvements.css
│
├── js/                          # JavaScript modular
│   └── bc-swipe.js             # Plugin Bootstrap Carousel Swipe
│
├── form/                        # Assets do formulário
│   ├── main.css
│   └── main.js
│
├── build/                       # Scripts de build
│   ├── convert-webp.sh
│   ├── convert-avif.sh
│   ├── minify-css.sh
│   ├── minify-js.sh
│   ├── purge-css.sh
│   └── [outros scripts]
│
├── vendor/                      # Dependências Composer
│   └── phpmailer/
│
├── scripts/                     # Scripts utilitários
│   ├── validate-images.php     # Validação de imagens
│   └── [outros scripts]
│
└── .env                        # Variáveis de ambiente (não versionado)
```

---

## 🔄 Fluxo de Carregamento {#fluxo}

### Homepage (`index.php`)

```
1. error_reporting() - Suprimir warnings de depreciação
2. security-headers.php - Cabeçalhos de segurança HTTP
3. config.php - Carregar configuração e env vars
4. image-helper.php - Funções de imagem WebP/AVIF
5. seo-helper.php - Funções de SEO
6. vendor/autoload.php - PHPMailer
7. Processar formulário (se POST)
   - Validar e sanitizar inputs
   - Enviar email via PHPMailer
   - Redirecionar para WhatsApp
8. Renderizar HTML
   - inc/header.php - Navegação
   - Conteúdo principal
   - inc/google-reviews.php - Reviews
   - Footer
```

### Páginas de Serviço (`[servico]/index.php`)

```
1. Definir variáveis obrigatórias:
   - $serviceName = 'Nome do Serviço'
   - $headerClass = 'classe-css-header'
   - $headerTitle = 'TÍTULO DO BANNER'
   - $tabs = [array de abas]
   - $tabContent = [array de conteúdo]

2. Incluir service-template.php:
   ├── security-headers.php
   ├── config.php
   ├── cache-headers.php
   ├── image-helper.php
   ├── seo-helper.php
   ├── asset-helper.php
   ├── breadcrumbs.php
   ├── header-inner.php
   └── Renderiza estrutura completa
```

### Ordem de Carregamento de Assets

```
HEAD:
1. Critical CSS (inline via critical-css.php)
2. Preconnect/DNS prefetch
3. Preload (LCP images, fonts)
4. Bootstrap CSS (CDN)
5. product.css (com cache busting)
6. servicos.css (páginas de serviço)
7. form/main.css (se formulário presente)
8. Google Fonts (defer via loadCSS)

BODY (end):
1. jQuery (CDN com fallback local)
2. Bootstrap JS (local)
3. Lucide Icons (CDN)
4. main.js
5. form/main.js (se formulário presente)
6. Google Tag Manager
```

---

## 🛠️ Sistema de Helpers {#helpers}

### 1. Image Helper (`inc/image-helper.php`)

**Propósito**: Gerenciar imagens otimizadas (WebP/AVIF) com fallbacks automáticos.

**Funções Principais**:

#### `picture_webp($src, $alt, $class, $attributes, $lazy, $generateSrcset, $sizes)`
Gera elemento `<picture>` com AVIF, WebP e fallback original.

**Parâmetros**:
- `$src` (string, obrigatório): Caminho da imagem original
- `$alt` (string, opcional): Texto alternativo
- `$class` (string, opcional): Classes CSS
- `$attributes` (array, opcional): Atributos HTML (pode incluir 'width' e 'height')
- `$lazy` (bool, default: true): Lazy loading
- `$generateSrcset` (bool, default: true): Gerar srcset responsivo
- `$sizes` (string, default: '100vw'): Atributo sizes

**Retorna**: String HTML com `<picture>` element

**Comportamento**:
- Detecta automaticamente dimensões da imagem (width/height)
- Tenta múltiplos caminhos para encontrar arquivo
- Gera srcset com 1x, 2x, 3x se disponível
- Adiciona aspect-ratio CSS como fallback se dimensões não detectadas
- Prioriza AVIF > WebP > Original

**Exemplo**:
```php
<?php require_once 'inc/image-helper.php'; ?>
<?php echo picture_webp(
    'img/example.png',
    'Descrição da imagem',
    'img-fluid',
    ['width' => '500', 'height' => '400'],
    false // Não lazy (above-the-fold)
); ?>
```

#### `image_file_exists($filePath, $rootPath)`
Verifica se arquivo existe tentando múltiplos caminhos.

**Uso Interno**: Usado por `picture_webp()` para verificar formatos alternativos.

---

### 2. SEO Helper (`inc/seo-helper.php`)

**Propósito**: Gerar meta tags, Open Graph, Twitter Cards e Schema.org JSON-LD.

**Funções Principais**:

#### `generate_open_graph_tags($title, $description, $image, $url, $type)`
Gera meta tags Open Graph para redes sociais.

**Parâmetros**:
- `$title` (string): Título da página
- `$description` (string): Descrição
- `$image` (string, opcional): URL da imagem (1200x630px recomendado)
- `$url` (string, opcional): URL canônica
- `$type` (string, default: 'website'): Tipo OG

**Retorna**: String HTML com meta tags OG

#### `generate_twitter_cards($title, $description, $image, $cardType)`
Gera meta tags Twitter Cards.

**Parâmetros**: Similar ao Open Graph

**Retorna**: String HTML com meta tags Twitter

#### `generate_local_business_schema($options)`
Gera Schema.org JSON-LD para LocalBusiness (BeautySalon).

**Parâmetros**:
- `$options` (array, opcional): Opções personalizadas
  - `name`, `description`, `address`, `telephone`, `openingHours`, `geo`, `sameAs`

**Retorna**: String HTML com script JSON-LD

#### `generate_service_schema($serviceName, $description, $priceRange, $image)`
Gera Schema.org JSON-LD para Service.

**Retorna**: String HTML com script JSON-LD

#### `generate_breadcrumb_schema($breadcrumbs)`
Gera Schema.org JSON-LD para BreadcrumbList.

**Parâmetros**:
- `$breadcrumbs` (array): Array de ['name' => 'Nome', 'url' => 'url']

**Retorna**: String HTML com script JSON-LD

---

### 3. Asset Helper (`inc/asset-helper.php`)

**Propósito**: Carregar assets (CSS/JS) com suporte a minificação automática.

**Funções Principais**:

#### `css_tag($filePath, $attributes = [])`
Gera tag `<link>` para CSS com cache busting e minificação.

**Parâmetros**:
- `$filePath` (string): Caminho relativo do CSS
- `$attributes` (array, opcional): Atributos HTML adicionais

**Retorna**: String HTML com tag `<link>`

**Comportamento**:
- Detecta automaticamente se está em subdiretório (páginas de serviço)
- Adiciona `../` se necessário
- Usa versão minificada se `USE_MINIFIED` estiver ativo
- Adiciona `ASSET_VERSION` para cache busting

#### `js_tag($filePath, $attributes = [])`
Gera tag `<script>` para JS com cache busting e minificação.

**Parâmetros**: Similar ao `css_tag()`

**Retorna**: String HTML com tag `<script>`

---

### 4. Icon Helper (`inc/icon-helper.php`)

**Propósito**: Gerenciar ícones Lucide (substituiu Font Awesome).

**Funções Principais**:

#### `lucide_icon($name, $attributes = [])`
Gera HTML para ícone Lucide.

**Parâmetros**:
- `$name` (string): Nome do ícone Lucide
- `$attributes` (array, opcional): Atributos HTML (width, height, stroke-width, etc.)

**Retorna**: String HTML com `<i data-lucide="...">`

**Exemplo**:
```php
<?php require_once 'inc/icon-helper.php'; ?>
<?php echo lucide_icon('briefcase', ['width' => '24', 'height' => '24']); ?>
```

---

### 5. Service Template (`inc/service-template.php`)

**Propósito**: Template reutilizável para páginas de serviço (reduz 70% de duplicação).

**Variáveis Obrigatórias**:
```php
$serviceName = 'Esmalteria';           // Nome do serviço
$headerClass = 'esmal-header';         // Classe CSS do header
$headerTitle = 'ESMALTERIA';           // Título do banner
$tabs = [                               // Array de abas
    ['id' => 'alongamentos', 'label' => 'Alongamentos', 'active' => true],
    ['id' => 'blindagem', 'label' => 'Blindagem', 'active' => false]
];
$tabContent = [                         // Conteúdo das abas
    'alongamentos' => '<div>...</div>',
    'blindagem' => '<div>...</div>'
];
```

**Variáveis Opcionais**:
```php
$includeGTM = true;                    // Incluir GTM
$customHeadContent = '';              // HTML customizado no <head>
$customBodyStartContent = '';         // HTML no início do <body>
```

**Uso**:
```php
<?php
// Definir variáveis
$serviceName = 'Esmalteria';
$headerClass = 'esmal-header';
$headerTitle = 'ESMALTERIA';
$tabs = [/* ... */];
$tabContent = [/* ... */];

// Incluir template
include '../inc/service-template.php';
?>
```

---

## 📝 Padrões de Código {#padroes}

### PHP

**Comentários**:
```php
<?php
/**
 * Descrição do arquivo/função
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 * 
 * FUNCIONALIDADES:
 * - Lista de funcionalidades
 * 
 * ONDE É USADO:
 * - Lista de arquivos que usam
 * 
 * EXEMPLO DE USO:
 * código de exemplo
 */

/**
 * Descrição da função
 * 
 * @param string $param1 Descrição do parâmetro
 * @param array $param2 Descrição do parâmetro
 * @return string Descrição do retorno
 */
function minha_funcao($param1, $param2 = []) {
    // Comentários em português brasileiro
    // Explicar o "porquê", não apenas o "o quê"
}
```

**Nomenclatura**:
- Variáveis: `snake_case` (ex: `$service_name`)
- Funções: `snake_case` (ex: `generate_seo_tags()`)
- Constantes: `UPPER_SNAKE_CASE` (ex: `APP_VERSION`)
- Classes: `PascalCase` (não usado atualmente)

### JavaScript

**Comentários JSDoc**:
```javascript
/**
 * Descrição da função
 * 
 * @param {string} param1 - Descrição do parâmetro
 * @param {Object} param2 - Descrição do parâmetro
 * @param {boolean} [param2.optional] - Parâmetro opcional
 * @returns {string} Descrição do retorno
 * 
 * @example
 * minhaFuncao('valor', { optional: true });
 */
function minhaFuncao(param1, param2 = {}) {
    // Comentários em português quando necessário
}
```

**Nomenclatura**:
- Variáveis: `camelCase` (ex: `isMobile`)
- Funções: `camelCase` (ex: `handleNavbar()`)
- Constantes: `UPPER_SNAKE_CASE` (ex: `API_URL`)

### CSS

**Comentários**:
```css
/**
 * Descrição do estilo
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.0.0
 * 
 * ONDE É USADO:
 * - Lista de páginas/componentes
 */

/* Comentários inline em português quando necessário */
.selector {
    /* Explicar decisões de design não óbvias */
    property: value;
}
```

**Organização**:
- Variáveis CSS em `css/modules/_variables.css`
- Estilos globais em `product.css`
- Estilos de serviço em `servicos.css`
- Módulos específicos em `css/modules/`

---

## ⚙️ Sistema de Configuração {#config}

### Arquivo `config.php`

**Constantes Principais**:
```php
APP_VERSION          // Versão completa (ex: "2.6.9")
APP_VERSION_MAJOR   // Major version (ex: 2)
APP_VERSION_MINOR   // Minor version (ex: 6)
APP_VERSION_PATCH   // Patch version (ex: 9)
ASSET_VERSION       // Versão de assets para cache busting (ex: "20251115-7")
SITE_URL            // URL do site (ex: "https://minhamimo.com.br")
USE_MINIFIED        // Usar assets minificados (boolean)
APP_ENV             // Ambiente (development, staging, production)
```

### Arquivo `.env`

**Variáveis de Ambiente**:
```env
# Email
MAILGUN_USERNAME=seu_username
MAILGUN_PASSWORD=sua_senha

# Site
SITE_URL=https://minhamimo.com.br

# Ambiente
APP_ENV=production

# Google Places API (opcional)
GOOGLE_PLACES_API_KEY=sua_chave
GOOGLE_PLACE_ID=seu_place_id
```

**Carregamento**:
- Carregado automaticamente por `config.php`
- Fallback para arquivo legado se `.env` não existir
- Não versionado (está no `.gitignore`)

---

## ⚡ Performance e Otimizações {#performance}

### Imagens
- **Formato**: AVIF > WebP > Original (JPG/PNG)
- **Lazy Loading**: Ativado por padrão (exceto LCP images)
- **Dimensões**: Detectadas automaticamente, fallback com aspect-ratio
- **Srcset**: Gerado automaticamente (1x, 2x, 3x)

### CSS
- **Critical CSS**: Inline no `<head>` via `critical-css.php`
- **Minificação**: Ativada via `USE_MINIFIED` (quando disponível)
- **PurgeCSS**: Remove CSS não utilizado
- **Cache Busting**: Via `ASSET_VERSION`

### JavaScript
- **Defer/Async**: Scripts não críticos carregados assincronamente
- **Minificação**: Ativada via `USE_MINIFIED`
- **jQuery**: CDN com fallback local
- **Lucide Icons**: CDN (UMD version)

### Fontes
- **Google Fonts**: Carregadas via `loadCSS()` (defer)
- **Font Display**: `swap` (Nunito), `optional` (EB Garamond, Akrobat)
- **Size Adjust**: Configurado para prevenir layout shift

### Cache
- **Cache Headers**: Configurados via `cache-headers.php`
- **ETags**: Validação de cache
- **Cache-Control**: Configurado por tipo de asset

---

## 🔍 SEO e Meta Tags {#seo}

### Meta Tags Básicas
- Título dinâmico por página
- Meta description otimizada
- Keywords (quando relevante)

### Open Graph
- `og:title`, `og:description`, `og:image`, `og:url`, `og:type`
- Gerado via `generate_open_graph_tags()`

### Twitter Cards
- `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`
- Gerado via `generate_twitter_cards()`

### Schema.org JSON-LD
- **LocalBusiness**: Homepage (BeautySalon)
- **Service**: Páginas de serviço
- **BreadcrumbList**: Navegação hierárquica
- **Review**: Reviews do Google (quando disponível)

### Outros
- **Canonical URLs**: Todas as páginas
- **Sitemap.xml**: Mapa completo do site
- **Robots.txt**: Instruções para crawlers

---

## 🔒 Segurança {#seguranca}

### Security Headers
Configurados via `inc/security-headers.php`:
- `X-Frame-Options: SAMEORIGIN`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Content-Security-Policy`: Configurado para necessidades do site
- `Permissions-Policy`: Restrições de recursos

### Input Sanitization
- `FILTER_SANITIZE_FULL_SPECIAL_CHARS` (PHP 8.1+ compatible)
- Email validation via `FILTER_SANITIZE_EMAIL`
- Validação adicional via `inc/form-security.php`

### Environment Variables
- Credenciais em `.env` (não versionado)
- Fallback para arquivo legado (backward compatibility)
- `.env` deve estar no `.htaccess` deny list

---

## 📚 Documentação Adicional

### Arquivos Principais
- **README.md**: Documentação geral
- **ARCHITECTURE.md**: Este arquivo (arquitetura completa)
- **AI-DEVELOPMENT-GUIDE.md**: Guia para desenvolvimento com IA
- **CHANGELOG.md**: Histórico de versões
- **PERFORMANCE-PROGRESS.md**: Progresso de otimizações
- **STATIC-ANALYSIS-INSIGHTS.md**: Insights de análise estática

### Links Úteis
- Site: https://minhamimo.com.br
- PageSpeed: https://pagespeed.web.dev/analysis?url=https://minhamimo.com.br

---

**Última Atualização**: 2025-11-16  
**Versão do Documento**: 1.0.0  
**Mantido por**: Victor Penter

