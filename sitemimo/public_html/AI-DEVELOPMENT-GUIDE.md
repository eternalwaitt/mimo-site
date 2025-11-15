# Mimo Site - AI Development Guide

**Documentação Master para Desenvolvimento com IA**

Esta é a documentação principal para desenvolvimento do site MIMO Estética. Otimizada para uso por IAs e desenvolvedores humanos.

**Última Atualização**: 2025-01-14  
**Versão Atual**: 2.2.9  
**Desenvolvedor**: Victor Penter

---

## 📋 Índice

1. [Estado Atual do Projeto](#estado-atual)
2. [Arquitetura e Estrutura](#arquitetura)
3. [Helpers e Funções Disponíveis](#helpers)
4. [Guia de Uso para IAs](#guia-ia)
5. [Roadmap e Melhorias Futuras](#roadmap)
6. [Ideias Criativas](#ideias)
7. [Versionamento](#versionamento)
8. [Checklist de Desenvolvimento](#checklist)

---

## 🎯 Estado Atual do Projeto {#estado-atual}

### Versão: 2.2.8 (2025-11-14)

### ✅ Implementações Completas

#### Performance & Otimização
- ✅ **WebP Image Optimization**: Todas as imagens usam formato WebP com fallback automático
- ✅ **Lazy Loading**: Imagens abaixo da dobra carregam sob demanda
- ✅ **Critical CSS**: CSS acima da dobra inline no `<head>`
- ✅ **Resource Hints**: DNS prefetch, preconnect, preload implementados
- ✅ **Template System**: Sistema de templates para páginas de serviço (redução de 70% de código duplicado)
- ✅ **Carousel Optimization**: Testimonials carousel otimizado (v2.2.7)
  - Altura reduzida de 650px para 550px (design mais compacto)
  - Fix de layout shift durante transições
  - Todos os cards usam `position: absolute` consistentemente
  - Transições suaves sem "pulos" visuais
- ✅ **Google Reviews System**: Sistema híbrido de reviews
  - Integração com Google Places API
  - Reviews manuais para controle de qualidade
  - Priorização inteligente (fotos, 5 estrelas, textos longos, datas antigas)

#### SEO & Otimização de Busca
- ✅ **Meta Tags Dinâmicas**: Títulos e descrições otimizadas por página
- ✅ **Open Graph**: Tags completas para compartilhamento em redes sociais
- ✅ **Twitter Cards**: Suporte completo para Twitter
- ✅ **Schema.org Structured Data**:
  - LocalBusiness (BeautySalon) na homepage
  - Service schema em todas as páginas de serviço
  - BreadcrumbList para navegação hierárquica
- ✅ **Sitemap.xml**: Mapa completo do site
- ✅ **Robots.txt**: Configuração otimizada para crawlers
- ✅ **Canonical URLs**: URLs canônicas em todas as páginas

#### Segurança
- ✅ **Security Headers**: X-Frame-Options, CSP, X-XSS-Protection, etc.
- ✅ **Input Sanitization**: FILTER_SANITIZE_FULL_SPECIAL_CHARS (PHP 8.1+)
- ✅ **Environment Variables**: Credenciais movidas para `.env`
- ✅ **Error Handling**: Compatibilidade PHP 8.4

#### Estrutura & Organização
- ✅ **Service Template System**: 6 páginas migradas para template único
- ✅ **Image Helper Functions**: Funções centralizadas para imagens
- ✅ **SEO Helper Functions**: Funções centralizadas para SEO
- ✅ **Configuration System**: Sistema de configuração centralizado
- ✅ **Versionamento**: Semantic Versioning implementado

### 📊 Estatísticas do Projeto

- **Páginas de Serviço**: 6 (todas usando template system)
- **Helpers Disponíveis**: 3 principais (image, SEO, service-template)
- **Build Scripts**: 3 (WebP, CSS minify, JS minify)
- **Documentação**: 6 arquivos principais
- **Redução de Código**: ~70% menos duplicação

---

## 🏗️ Arquitetura e Estrutura {#arquitetura}

### Stack Tecnológico

```
Backend:     PHP 7.1.33+ (production) / PHP 8.4 (development)
Frontend:    HTML5, CSS3, JavaScript (ES5+)
Framework:   Bootstrap 4.5.2
Libraries:   jQuery 3.3.1, PHPMailer, SendGrid (legacy)
Email:       Mailgun SMTP via PHPMailer
Images:      WebP com fallback automático
SEO:         Schema.org, Open Graph, Twitter Cards
Build:       Shell scripts (bash)
Deploy:      FTP/SFTP para shared hosting
```

### Estrutura de Diretórios

```
public_html/
├── index.php                    # Homepage (formulário de contato)
├── config.php                   # Configuração central (versão, env vars)
├── product.css                  # CSS principal (global)
├── servicos.css                 # CSS específico de serviços
├── main.js                      # JavaScript principal
├── sitemap.xml                  # Sitemap para SEO
├── robots.txt                   # Instruções para crawlers
│
├── inc/                         # Includes compartilhados
│   ├── header.php               # Navegação homepage
│   ├── header-inner.php         # Navegação páginas internas
│   ├── gtm-head.php            # Google Tag Manager (head)
│   ├── gtm-body.php            # Google Tag Manager (body)
│   ├── security-headers.php    # Cabeçalhos de segurança
│   ├── critical-css.php        # CSS crítico inline
│   ├── image-helper.php        # Funções de imagem WebP
│   ├── seo-helper.php          # Funções de SEO
│   └── service-template.php    # Template de páginas de serviço
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
├── form/                        # Assets do formulário
├── build/                       # Scripts de build
│   ├── convert-webp.sh
│   ├── minify-css.sh
│   └── minify-js.sh
│
├── vendor/                      # Dependências Composer
│   ├── phpmailer/
│   └── sendgrid/               # Legacy
│
└── .env                        # Variáveis de ambiente (não versionado)
```

### Fluxo de Carregamento

#### Homepage (`index.php`)
```
1. error_reporting() - Suprimir warnings
2. security-headers.php - Cabeçalhos de segurança
3. config.php - Carregar configuração e env vars
4. image-helper.php - Funções de imagem
5. seo-helper.php - Funções de SEO
6. vendor/autoload.php - PHPMailer
7. Processar formulário (se POST)
8. Renderizar HTML com includes
```

#### Páginas de Serviço (`[servico]/index.php`)
```
1. Definir variáveis ($serviceName, $headerClass, $tabs, etc.)
2. Incluir service-template.php
3. service-template.php carrega:
   - security-headers.php
   - config.php
   - image-helper.php
   - seo-helper.php
   - header-inner.php
   - Renderiza estrutura completa
```

---

## 🛠️ Helpers e Funções Disponíveis {#helpers}

### 1. Image Helper (`inc/image-helper.php`)

#### `picture_webp($src, $alt, $class, $attributes, $lazy)`

Gera elemento `<picture>` com WebP e fallback automático.

**Parâmetros:**
- `$src` (string, obrigatório): Caminho da imagem original (jpg/png)
- `$alt` (string, opcional): Texto alternativo
- `$class` (string, opcional): Classes CSS
- `$attributes` (array, opcional): Atributos HTML adicionais
- `$lazy` (bool, opcional, default: true): Lazy loading

**Retorna:** String HTML com `<picture>` element

**Exemplo:**
```php
<?php require_once 'inc/image-helper.php'; ?>
<?php echo picture_webp('img/example.png', 'Descrição', 'img-fluid', ['style' => 'max-width: 100%']); ?>
```

**Comportamento:**
- Verifica automaticamente se WebP existe
- Resolve paths relativos corretamente
- Adiciona lazy loading por padrão
- Preserva todos os atributos

#### `responsive_image($basePath, $ext, $alt, $class, $sizes, $lazy)`

Gera imagem responsiva com srcset (não usado atualmente, disponível para futuro).

---

### 2. SEO Helper (`inc/seo-helper.php`)

#### `generate_seo_meta_tags($title, $description, $keywords)`

Gera meta tags básicas de SEO.

**Parâmetros:**
- `$title` (string): Título da página
- `$description` (string): Meta description
- `$keywords` (string, opcional): Palavras-chave

**Retorna:** String HTML com meta tags

**Exemplo:**
```php
echo generate_seo_meta_tags(
    'MIMO Estética - Centro de Beleza',
    'Descrição otimizada para SEO',
    'estética, são paulo, beleza'
);
```

#### `generate_open_graph_tags($title, $description, $image, $url, $type)`

Gera meta tags Open Graph para redes sociais.

**Parâmetros:**
- `$title` (string): Título
- `$description` (string): Descrição
- `$image` (string, opcional): URL da imagem (1200x630px recomendado)
- `$url` (string, opcional): URL canônica
- `$type` (string, opcional, default: 'website'): Tipo OG

**Retorna:** String HTML com meta tags OG

#### `generate_twitter_cards($title, $description, $image, $cardType)`

Gera meta tags Twitter Cards.

**Parâmetros:**
- `$title` (string): Título
- `$description` (string): Descrição
- `$image` (string, opcional): URL da imagem
- `$cardType` (string, opcional, default: 'summary_large_image'): Tipo de card

**Retorna:** String HTML com meta tags Twitter

#### `generate_local_business_schema($options)`

Gera Schema.org JSON-LD para LocalBusiness (BeautySalon).

**Parâmetros:**
- `$options` (array, opcional): Opções personalizadas
  - `name`: Nome do negócio
  - `description`: Descrição
  - `address`: Array com endereço completo
  - `telephone`: Telefone
  - `openingHours`: Array de horários
  - `geo`: Array com latitude/longitude
  - `sameAs`: Array de redes sociais

**Retorna:** String HTML com script JSON-LD

**Exemplo:**
```php
echo generate_local_business_schema([
    'geo' => [
        'latitude' => '-23.5505',
        'longitude' => '-46.6333'
    ]
]);
```

#### `generate_service_schema($serviceName, $description, $priceRange, $image)`

Gera Schema.org JSON-LD para Service.

**Parâmetros:**
- `$serviceName` (string): Nome do serviço
- `$description` (string): Descrição
- `$priceRange` (string, opcional): Faixa de preço (ex: "$$")
- `$image` (string, opcional): URL da imagem

**Retorna:** String HTML com script JSON-LD

#### `generate_breadcrumb_schema($breadcrumbs)`

Gera Schema.org JSON-LD para BreadcrumbList.

**Parâmetros:**
- `$breadcrumbs` (array): Array de ['name' => 'Nome', 'url' => 'url']

**Retorna:** String HTML com script JSON-LD

**Exemplo:**
```php
$breadcrumbs = [
    ['name' => 'Início', 'url' => '/'],
    ['name' => 'Serviços', 'url' => '/#services'],
    ['name' => 'Esmalteria', 'url' => '/esmalteria/']
];
echo generate_breadcrumb_schema($breadcrumbs);
```

#### `generate_canonical_url($url)`

Gera tag canonical URL.

**Parâmetros:**
- `$url` (string, opcional): URL canônica (usa REQUEST_URI se não fornecido)

**Retorna:** String HTML com tag canonical

---

### 3. Service Template (`inc/service-template.php`)

Template reutilizável para páginas de serviço.

#### Variáveis Obrigatórias

```php
$serviceName = 'Esmalteria';           // Nome do serviço
$headerClass = 'esmal-header';         // Classe CSS do header
$headerTitle = 'ESMALTERIA';           // Título do banner
$tabs = [                               // Array de abas
    [
        'id' => 'alongamentos',
        'label' => 'Alongamentos',
        'active' => true
    ],
    [
        'id' => 'blindagem',
        'label' => 'Blindagem',
        'active' => false
    ]
];
$tabContent = [                         // Conteúdo das abas
    'alongamentos' => '<div>...</div>',
    'blindagem' => '<div>...</div>'
];
```

#### Variáveis Opcionais

```php
$includeGTM = true;                    // Incluir GTM (default: true)
$tabIdPrefix = 'pills-';              // Prefixo dos IDs das abas
$tabListId = 'pills-tab';             // ID da lista de abas
$tabContentId = 'pills-tabContent';   // ID do container de conteúdo
$tabContentClass = 'mb-5';            // Classe do container
$footerInsideTabContent = false;      // Footer dentro do tab-content
$customHeadContent = '';              // HTML customizado no <head>
$customBodyStartContent = '';         // HTML customizado no início do <body>
$customContentBeforeBanner = '';      // HTML antes do banner (modais, etc.)
```

#### Exemplo de Uso Completo

```php
<?php
require_once '../inc/image-helper.php';

$serviceName = 'Esmalteria';
$headerClass = 'esmal-header';
$headerTitle = 'ESMALTERIA';
$includeGTM = true;

// Definir abas
$tabs = [
    ['id' => 'alongamentos', 'label' => 'Alongamentos', 'active' => true],
    ['id' => 'blindagem', 'label' => 'Blindagem', 'active' => false],
    ['id' => 'manicure', 'label' => 'Manicure & Pedicure', 'active' => false]
];

// Definir conteúdo das abas usando output buffering
ob_start();
?>
<div class="container my-5">
    <h3>Alongamento de Unhas</h3>
    <p>Conteúdo da aba...</p>
</div>
<?php
$tabContent['alongamentos'] = ob_get_clean();

ob_start();
?>
<div class="container my-5">
    <h3>Blindagem</h3>
    <p>Conteúdo da aba...</p>
</div>
<?php
$tabContent['blindagem'] = ob_get_clean();

// Incluir template
include '../inc/service-template.php';
?>
```

#### Notas Importantes

- **IDs das Abas**: O template gera automaticamente IDs com padrão `pills-[id]s` para nav links e `pills-[id]` para tab panes (compatibilidade com código original)
- **SEO Automático**: Meta tags e Schema.org são gerados automaticamente baseados em `$serviceName`
- **Path Resolution**: Imagens devem usar paths relativos (`../img/...`)

---

### 4. Configuration (`config.php`)

#### Constantes Disponíveis

```php
APP_VERSION          // Versão completa (ex: "2.1.0")
APP_VERSION_MAJOR   // Major version (ex: 2)
APP_VERSION_MINOR   // Minor version (ex: 1)
APP_VERSION_PATCH   // Patch version (ex: 0)
ASSET_VERSION       // Versão de assets para cache busting (ex: "20250119")
SITE_URL            // URL do site (ex: "https://minhamimo.com.br")
USE_MINIFIED        // Usar assets minificados (boolean)
```

#### Variáveis de Ambiente (`.env`)

```env
MAILGUN_USERNAME=seu_username
MAILGUN_PASSWORD=sua_senha
SITE_URL=https://minhamimo.com.br
```

---

## 🤖 Guia de Uso para IAs {#guia-ia}

### Como Trabalhar com Este Projeto

#### 1. Antes de Fazer Mudanças

1. **Ler este documento completamente**
2. **Verificar versão atual** em `config.php`
3. **Entender estrutura** do arquivo que será modificado
4. **Verificar dependências** (helpers, includes)

#### 2. Ao Adicionar Novas Funcionalidades

1. **Usar helpers existentes** sempre que possível
2. **Seguir padrões** do código existente
3. **Adicionar comentários** em português brasileiro
4. **Atualizar documentação** se necessário
5. **Testar localmente** antes de finalizar

#### 3. Ao Modificar Páginas de Serviço

1. **Usar service-template.php** - NÃO criar HTML do zero
2. **Definir variáveis** corretamente ($serviceName, $tabs, etc.)
3. **Usar picture_webp()** para todas as imagens
4. **Manter estrutura** de abas consistente
5. **Testar navegação** entre abas

#### 4. Ao Adicionar Imagens

1. **Adicionar imagem original** (JPG/PNG)
2. **Converter para WebP**: `./build/convert-webp.sh 85 [diretorio]`
3. **Usar picture_webp()** no código
4. **Verificar paths** relativos corretos
5. **Testar carregamento** WebP vs fallback

#### 5. Ao Modificar CSS/JS

1. **Atualizar ASSET_VERSION** em `config.php`
2. **Testar em diferentes navegadores**
3. **Verificar responsividade** mobile/desktop
4. **Não quebrar** estilos existentes

#### 6. Ao Implementar SEO

1. **Usar funções do seo-helper.php**
2. **Gerar meta tags** dinamicamente
3. **Adicionar Schema.org** apropriado
4. **Atualizar sitemap.xml** se nova página
5. **Testar** com Google Rich Results Test

#### 7. Checklist Antes de Finalizar

- [ ] Código segue padrões do projeto
- [ ] Comentários em português brasileiro
- [ ] Versão atualizada (se necessário)
- [ ] ASSET_VERSION atualizado (se CSS/JS mudou)
- [ ] Testado localmente
- [ ] Sem erros de sintaxe PHP
- [ ] Imagens usam picture_webp()
- [ ] SEO implementado (se nova página)
- [ ] Documentação atualizada

### Padrões de Código

#### PHP

```php
<?php
/**
 * Descrição do arquivo/função
 * 
 * Desenvolvido por: Victor Penter
 * Versão: <?php echo APP_VERSION; ?>
 */

// Comentários em português brasileiro
// Usar snake_case para variáveis
// Usar camelCase para funções
```

#### HTML

```html
<!-- Comentários descritivos -->
<!-- Usar classes Bootstrap quando possível -->
<!-- Sempre incluir alt text em imagens -->
```

#### CSS

```css
/**
 * Descrição do estilo
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.0.0
 */

/* Comentários em português quando necessário */
```

#### Otimização de Carousels e Prevenção de Layout Shift

**Padrão para Carousels com Transições:**

1. **Container sempre com altura fixa:**
```css
.carousel-container {
    height: 550px; /* Altura fixa - nunca muda */
    min-height: 550px;
    max-height: 550px;
    position: relative;
    overflow: hidden;
}
```

2. **Todos os cards sempre `position: absolute`:**
```css
.carousel-item {
    position: absolute; /* SEMPRE absolute - nunca muda para relative */
    width: 100%;
    height: 550px; /* Mesma altura do container */
    top: 0;
    left: 0;
    opacity: 0;
    z-index: 0;
    transition: opacity 0.6s ease-in-out;
}

.carousel-item.active {
    position: absolute; /* MANTÉM absolute */
    opacity: 1;
    z-index: 1; /* Apenas z-index muda */
}
```

3. **Container NÃO deve usar `display: flex` com elementos absolutos:**
```css
/* ❌ ERRADO - causa problemas com absolute */
.carousel-container {
    display: flex;
}

/* ✅ CORRETO - usar block */
.carousel-container {
    display: block;
    position: relative;
}
```

4. **Elementos internos com alturas fixas:**
```css
.carousel-content {
    height: 500px; /* Altura fixa */
    min-height: 500px;
    max-height: 500px;
    /* Evita que conteúdo mude altura durante transição */
}
```

**Por que isso é importante:**
- Evita Cumulative Layout Shift (CLS) - métrica importante do Core Web Vitals
- Transições suaves sem "pulos" visuais
- Melhor experiência do usuário
- Performance melhorada (menos repaints)

### Comandos Úteis

```bash
# Testar sintaxe PHP
php -l arquivo.php

# Converter imagens para WebP
cd sitemimo/public_html
./build/convert-webp.sh 85 img

# Servidor local
cd sitemimo/public_html
php -S localhost:8000

# Verificar versão
php -r "require 'config.php'; echo APP_VERSION;"
```

---

## 🗺️ Roadmap e Melhorias Futuras {#roadmap}

### Fase 1: Quick Wins (1-2 semanas)

#### Prioridade Alta - Baixo Esforço

1. **Página 404 Personalizada**
   - Criar `404.php` com design consistente
   - Links para páginas principais
   - SEO-friendly

2. **Botão "Voltar ao Topo"**
   - Botão flutuante com scroll suave
   - Aparece após scroll de 300px
   - Animação suave

3. **Breadcrumbs Visuais**
   - Adicionar breadcrumbs HTML (além do Schema.org)
   - Melhorar navegação
   - Estilo consistente

4. **Font Display Optimization**
   - Adicionar `font-display: swap` nas fontes
   - Melhorar FCP (First Contentful Paint)

5. **Favicon Completo**
   - Verificar todos os tamanhos
   - Adicionar manifest icons

### Fase 2: Performance (2-3 semanas)

#### Prioridade Alta - Alto Impacto

1. **Minificação CSS/JS**
   - Ativar scripts de minificação
   - Habilitar `USE_MINIFIED` em produção
   - Criar build script completo

2. **Cache Headers**
   - Implementar cache headers para assets
   - ETags para validação
   - Cache-Control apropriado

3. **Compressão de Imagens**
   - Comprimir PNGs/JPGs originais
   - Reduzir tamanho sem perda visível
   - Script automatizado

4. **CDN Integration**
   - Configurar Cloudflare (grátis)
   - Servir assets estáticos via CDN
   - Cache purging automático

### Fase 3: SEO Local (2-4 semanas)

#### Prioridade Alta - Alto Impacto

1. **Google My Business**
   - Criar/otimizar perfil completo
   - Adicionar fotos de alta qualidade
   - Coletar avaliações
   - Postar regularmente

2. **Schema.org Review/Rating**
   - Adicionar schema de avaliações
   - Exibir estrelas nos resultados
   - Integrar com Google My Business

3. **Consistência NAP**
   - Garantir nome, endereço, telefone idênticos
   - Verificar em todos os diretórios
   - Atualizar onde necessário

4. **Diretórios Locais**
   - Cadastrar em Google Maps
   - Yelp, TripAdvisor, GuiaMais
   - Apontador, etc.

### Fase 4: UX e Conversão (3-4 semanas)

#### Prioridade Média - Alto Impacto

1. **Melhorias no Formulário**
   - Validação em tempo real
   - Mensagens de erro claras
   - Indicador de progresso
   - Confirmação visual

2. **Integração de Agendamento**
   - Embed melhorado do agendamento.salaovip.com.br
   - Widget de calendário nas páginas
   - CTA mais visível

3. **Instagram Feed**
   - Exibir últimos posts do @minhamimo
   - Galeria de antes/depois
   - Cache de API responses
   - Usar instagram-php-scraper

4. **Dark Mode**
   - Toggle de tema escuro
   - CSS variables para cores
   - Persistir preferência
   - Transição suave

### Fase 5: Conteúdo e Marketing (4-6 semanas)

#### Prioridade Média

1. **Blog/Conteúdo**
   - Criar seção de blog
   - Artigos sobre dicas de beleza
   - Publicar 1-2x por semana
   - SEO otimizado

2. **Páginas de Serviço Expandidas**
   - Descrições mais detalhadas
   - FAQs por serviço
   - Imagens antes/depois
   - Vídeos (opcional)

3. **Newsletter**
   - Formulário de inscrição
   - Integração Mailchimp/SendGrid
   - Emails automáticos
   - Segmentação

### Fase 6: Modernização Técnica (6-8 semanas)

#### Prioridade Baixa - Longo Prazo

1. **PWA (Progressive Web App)**
   - Service Worker
   - Web App Manifest
   - "Add to Home Screen"
   - Offline support

2. **Acessibilidade**
   - ARIA labels completos
   - Navegação por teclado
   - Contraste WCAG AA
   - Screen reader optimization

3. **Monitoramento**
   - Core Web Vitals tracking
   - Error tracking (Sentry)
   - Heatmaps (Hotjar)
   - Analytics avançado

---

## 💡 Ideias Criativas {#ideias}

### Experiência do Usuário

1. **Quiz Interativo "Qual Tratamento é Ideal para Você?"**
   - Perguntas sobre objetivos, tipo de pele, etc.
   - Resultado personalizado
   - CTA para agendamento
   - Compartilhável em redes sociais

2. **Calculadora de Preços**
   - Estimativa de custo por tratamento
   - Comparação entre serviços
   - Opções de pacotes
   - Integração com agendamento

3. **Galeria Interativa de Antes/Depois**
   - Slider interativo
   - Filtros por tipo de tratamento
   - Lightbox para detalhes
   - Compartilhamento social

4. **Agendamento Inteligente**
   - Sugestões de horários baseadas em histórico
   - Lembretes automáticos
   - Confirmação por WhatsApp
   - Cancelamento fácil

### Marketing e Engajamento

5. **Programa de Fidelidade Digital**
   - Pontos por agendamentos
   - Descontos progressivos
   - Badges e conquistas
   - Compartilhamento social

6. **Conteúdo Interativo**
   - Vídeos tutoriais
   - Lives no Instagram
   - Webinars sobre cuidados
   - E-books gratuitos

7. **Sistema de Avaliações**
   - Coleta automática após atendimento
   - Exibição no site
   - Schema.org Review/Rating
   - Moderação de conteúdo

8. **Chatbot Inteligente**
   - Respostas automáticas
   - Agendamento via chat
   - Integração com WhatsApp
   - IA para sugestões

### Tecnologia

9. **Realidade Aumentada (AR)**
   - Visualizar tratamentos antes
   - Testar cores de cabelo
   - Ver resultados de micropigmentação
   - Compartilhável

10. **App Mobile Nativo**
    - Versão PWA primeiro
    - Depois considerar nativo
    - Push notifications
    - Agendamento offline

11. **Integração com Wearables**
    - Lembretes de cuidados
    - Tracking de rotina
    - Dicas personalizadas
    - Integração com saúde

12. **IA para Recomendações**
    - Análise de fotos
    - Sugestões personalizadas
    - Previsão de resultados
    - Otimização de tratamentos

### Social e Comunidade

13. **Comunidade Online**
    - Fórum de discussão
    - Grupos de WhatsApp
    - Eventos e workshops
    - Networking

14. **Programa de Influenciadores**
    - Parcerias com micro-influenciadores
    - Descontos especiais
    - Conteúdo colaborativo
    - Tracking de conversões

15. **Gamificação**
    - Desafios de beleza
    - Competições
    - Rankings
    - Prêmios

---

## 📝 Versionamento {#versionamento}

### Sistema de Versionamento

**Formato**: Semantic Versioning (MAJOR.MINOR.PATCH)

- **MAJOR**: Mudanças incompatíveis ou arquiteturais grandes
- **MINOR**: Novas funcionalidades compatíveis
- **PATCH**: Correções de bugs compatíveis

### Versão Atual

```
APP_VERSION: 2.1.0
APP_VERSION_MAJOR: 2
APP_VERSION_MINOR: 1
APP_VERSION_PATCH: 0
ASSET_VERSION: 20250119
```

### Processo de Atualização

1. Determinar tipo de mudança (Major/Minor/Patch)
2. Atualizar constantes em `config.php`
3. Atualizar `CHANGELOG.md`
4. Atualizar `ASSET_VERSION` se CSS/JS mudou
5. Commit e tag no Git
6. Deploy para produção

### Histórico de Versões

- **2.1.0** (2025-01-19): SEO completo, Schema.org, Open Graph
- **2.0.0** (2025-01-19): Template system, WebP, refatoração major
- **1.0.0** (2018-01-01): Lançamento inicial

Ver `CHANGELOG.md` para detalhes completos.

---

## ✅ Checklist de Desenvolvimento {#checklist}

### Antes de Começar

- [ ] Ler este documento completamente
- [ ] Verificar versão atual do projeto
- [ ] Entender estrutura do arquivo a modificar
- [ ] Verificar dependências e helpers disponíveis

### Durante o Desenvolvimento

- [ ] Seguir padrões de código do projeto
- [ ] Usar helpers existentes quando possível
- [ ] Adicionar comentários em português brasileiro
- [ ] Testar localmente continuamente
- [ ] Verificar sintaxe PHP (`php -l`)

### Antes de Finalizar

- [ ] Código testado e funcionando
- [ ] Sem erros de sintaxe
- [ ] Responsividade verificada (mobile/desktop)
- [ ] Imagens usam `picture_webp()`
- [ ] SEO implementado (se nova página)
- [ ] Versão atualizada (se necessário)
- [ ] ASSET_VERSION atualizado (se CSS/JS)
- [ ] Documentação atualizada
- [ ] CHANGELOG.md atualizado

### Antes de Deploy

- [ ] Todos os testes passando
- [ ] Versão commitada e taggeada
- [ ] .env configurado no servidor
- [ ] Permissões de arquivo corretas
- [ ] Backup realizado
- [ ] Deploy testado em staging (se disponível)

---

## 📚 Documentação Adicional

### Arquivos de Documentação

- **README.md**: Documentação geral do projeto
- **CHANGELOG.md**: Histórico de versões e mudanças
- **VERSIONING.md**: Guia de versionamento
- **IMPROVEMENTS.md**: Roadmap detalhado de melhorias
- **SEO-OPTIMIZATION.md**: Documentação completa de SEO
- **build/README.md**: Documentação dos scripts de build
- **AI-DEVELOPMENT-GUIDE.md**: Este arquivo (guia para IAs)

### Links Úteis

- **Site**: https://minhamimo.com.br
- **Instagram**: @minhamimo
- **Facebook**: /mimocuidadoebeleza
- **Agendamento**: agendamento.salaovip.com.br

---

## 🎯 Próximos Passos Recomendados

### Imediato (Esta Semana) ✅ COMPLETO
1. ✅ Implementar Quick Wins (404, botão topo, breadcrumbs)
2. ✅ Ativar minificação CSS/JS
3. ✅ Implementar cache headers

### Curto Prazo (Próximas 2 Semanas)
1. Google My Business setup
2. Schema.org Review/Rating
3. Compressão de imagens
4. Melhorias no formulário

### Médio Prazo (Próximo Mês)
1. CDN (Cloudflare)
2. Instagram feed
3. Integração de agendamento melhorada
4. Blog/Conteúdo (estrutura)

### Longo Prazo (Próximos 3 Meses)
1. PWA completo
2. Dark mode
3. Newsletter
4. Acessibilidade completa

---

**Última Atualização**: 2025-01-19  
**Versão do Documento**: 1.1.0  
**Mantido por**: Victor Penter

---

*Este documento é a fonte de verdade para desenvolvimento do site MIMO Estética. Sempre consulte este guia antes de fazer mudanças significativas.*

