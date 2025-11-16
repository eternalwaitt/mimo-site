# Índice de Documentação - Site MIMO

**Última Atualização**: 2025-11-16  
**Versão**: 1.0.0

Este índice organiza toda a documentação do projeto para facilitar navegação e entendimento por IA e desenvolvedores.

---

## 📚 Documentação Principal

### 🎯 Início Rápido
1. **[ARCHITECTURE.md](ARCHITECTURE.md)** ⭐ **COMECE AQUI**
   - Arquitetura completa do sistema
   - Stack tecnológico
   - Estrutura de diretórios
   - Fluxo de carregamento
   - Sistema de helpers
   - Padrões de código

2. **[AI-DEVELOPMENT-GUIDE.md](AI-DEVELOPMENT-GUIDE.md)**
   - Guia específico para desenvolvimento com IA
   - Estado atual do projeto
   - Helpers disponíveis
   - Padrões de código
   - Checklist de desenvolvimento

3. **[README.md](README.md)**
   - Visão geral do projeto
   - Tecnologias utilizadas
   - Estrutura de diretórios
   - Guia de desenvolvimento
   - Deployment

---

## 🛠️ Documentação Técnica

### Helpers e Funções

#### PHP Helpers
- **[inc/image-helper.php](inc/image-helper.php)**
  - `picture_webp()`: Gera elementos <picture> com AVIF/WebP/Original
  - `image_file_exists()`: Verifica existência de arquivos
  - `responsive_image()`: Imagens responsivas com srcset

- **[inc/seo-helper.php](inc/seo-helper.php)**
  - `generate_open_graph_tags()`: Meta tags Open Graph
  - `generate_twitter_cards()`: Meta tags Twitter Cards
  - `generate_local_business_schema()`: Schema.org LocalBusiness
  - `generate_service_schema()`: Schema.org Service
  - `generate_breadcrumb_schema()`: Schema.org BreadcrumbList

- **[inc/asset-helper.php](inc/asset-helper.php)**
  - `css_tag()`: Gera tags <link> para CSS com minificação
  - `js_tag()`: Gera tags <script> para JS com minificação
  - `get_css_asset()`: Resolve caminho de CSS
  - `get_js_asset()`: Resolve caminho de JS

- **[inc/icon-helper.php](inc/icon-helper.php)**
  - `lucide_icon()`: Gera ícones Lucide
  - Mapeamento Font Awesome → Lucide

- **[inc/service-template.php](inc/service-template.php)**
  - Template reutilizável para páginas de serviço
  - Reduz 70% de duplicação de código

#### JavaScript
- **[main.js](main.js)**
  - Comportamento da navbar
  - Swipe em carousels
  - Scroll suave
  - Validação de formulário AJAX
  - Contador de caracteres

- **[js/bc-swipe.js](js/bc-swipe.js)**
  - Plugin Bootstrap Carousel Swipe
  - Suporte touch para dispositivos móveis

#### CSS
- **[product.css](product.css)**
  - Estilos globais
  - Layout e navegação
  - Componentes principais

- **[servicos.css](servicos.css)**
  - Estilos específicos de páginas de serviço
  - Headers por serviço
  - Responsividade

- **[css/modules/_variables.css](css/modules/_variables.css)**
  - Variáveis CSS (design tokens)
  - Cores da marca
  - Espaçamentos

---

## 📊 Documentação de Performance

### Otimizações
- **[PERFORMANCE-PROGRESS.md](PERFORMANCE-PROGRESS.md)**
  - Progresso de otimizações
  - Fases implementadas
  - Resultados de testes

- **[PERFORMANCE-PHASE1-RESULTS.md](PERFORMANCE-PHASE1-RESULTS.md)**
  - Resultados da FASE 1 (Fix CLS)
  - Métricas antes/depois
  - Problemas identificados

- **[PERFORMANCE-FIX-PLAN.md](PERFORMANCE-FIX-PLAN.md)**
  - Plano de correção de performance
  - Fases prioritizadas
  - Ações específicas

- **[STATIC-ANALYSIS-INSIGHTS.md](STATIC-ANALYSIS-INSIGHTS.md)**
  - Insights de análise estática
  - Ferramentas recomendadas
  - Regras de linting para performance

- **[CSS-FRAMEWORKS-INSIGHTS.md](CSS-FRAMEWORKS-INSIGHTS.md)**
  - Análise de awesome-css-frameworks
  - Ferramentas complementares aplicáveis
  - Padrões e práticas recomendadas

### Análises
- **[PAGESPEED-ANALYSIS-v2.6.8.md](PAGESPEED-ANALYSIS-v2.6.8.md)**
  - Análise detalhada do PageSpeed
  - Oportunidades de melhoria
  - Métricas Core Web Vitals

- **[FRAMEWORK-CSS-ANALYSIS.md](FRAMEWORK-CSS-ANALYSIS.md)**
  - Análise de frameworks CSS
  - Comparação Bootstrap vs alternativas
  - Recomendações

---

## 🔧 Configuração e Setup

### Configuração
- **[config.php](config.php)**
  - Constantes do sistema
  - Variáveis de ambiente
  - Versionamento

### Build Scripts
- **[build/README.md](build/README.md)**
  - Documentação dos scripts de build
  - Como usar cada script
  - Ordem de execução

### Linting
- **[LINTING.md](LINTING.md)**
  - Configuração de linters
  - PHP_CodeSniffer
  - ESLint
  - Stylelint

---

## 📝 Histórico e Versionamento

### Versionamento
- **[CHANGELOG.md](CHANGELOG.md)**
  - Histórico completo de versões
  - Mudanças por versão
  - Datas de release

- **[VERSIONING.md](VERSIONING.md)**
  - Sistema de versionamento
  - Semantic Versioning
  - Processo de atualização

### Roadmaps
- **[ROADMAP.md](ROADMAP.md)**
  - Roadmap geral do projeto
  - Features futuras
  - Melhorias planejadas

- **[IMPROVEMENTS.md](IMPROVEMENTS.md)**
  - Lista detalhada de melhorias
  - Prioridades
  - Estimativas

- **[PROXIMOS-PASSOS.md](PROXIMOS-PASSOS.md)**
  - Próximos passos imediatos
  - Tarefas pendentes
  - Ações recomendadas

---

## 🔍 SEO e Otimização

### SEO
- **[SEO-OPTIMIZATION.md](SEO-OPTIMIZATION.md)**
  - Guia completo de SEO
  - Meta tags
  - Schema.org
  - Sitemap e robots.txt

### Google
- **[GOOGLE-API-SETUP.md](GOOGLE-API-SETUP.md)**
  - Setup da API do Google Places
  - Configuração de reviews
  - Credenciais

- **[GOOGLE-REVIEWS-SETUP.md](GOOGLE-REVIEWS-SETUP.md)**
  - Sistema de reviews do Google
  - Integração com API
  - Reviews manuais

---

## 🧪 Testes e Qualidade

### Testes
- **[TESTING-CHECKLIST.md](TESTING-CHECKLIST.md)**
  - Checklist de testes
  - Testes manuais
  - Testes automatizados

- **[tests/README.md](tests/README.md)**
  - Documentação de testes
  - Como executar
  - Cobertura

### Qualidade
- **[CODE-AUDIT.md](CODE-AUDIT.md)**
  - Auditoria de código
  - Problemas identificados
  - Correções aplicadas

---

## 🚀 Deployment

### Deploy
- **[DEPLOY-SETUP.md](DEPLOY-SETUP.md)**
  - Setup de deployment
  - Configuração do servidor
  - Processo de deploy

- **[DEPLOYMENT-VERIFICATION-v2.6.5.md](DEPLOYMENT-VERIFICATION-v2.6.5.md)**
  - Verificação de deployment
  - Checklist pós-deploy
  - Validações

---

## 📖 Guias Específicos

### Migrações
- **[LUCIDE-MIGRATION-MAP.md](LUCIDE-MIGRATION-MAP.md)**
  - Mapeamento Font Awesome → Lucide
  - Ícones migrados
  - Guia de migração

### Recursos
- **[DESIGN-RESOURCES.md](DESIGN-RESOURCES.md)**
  - Recursos de design
  - Cores da marca
  - Tipografia

- **[ICON-PROMPTS.md](ICON-PROMPTS.md)**
  - Prompts para geração de ícones
  - Referências visuais

---

## 🔗 Links Úteis

### Externos
- Site: https://minhamimo.com.br
- PageSpeed: https://pagespeed.web.dev/analysis?url=https://minhamimo.com.br
- Google My Business: [Perfil do negócio]

### Internos
- Estrutura: Ver [ARCHITECTURE.md](ARCHITECTURE.md)
- Helpers: Ver seção "Helpers e Funções" acima
- Performance: Ver seção "Documentação de Performance" acima

---

## 🎯 Por Onde Começar?

### Para IAs
1. Leia **[ARCHITECTURE.md](ARCHITECTURE.md)** primeiro
2. Consulte **[AI-DEVELOPMENT-GUIDE.md](AI-DEVELOPMENT-GUIDE.md)** para padrões
3. Veja exemplos em helpers PHP (image-helper.php, seo-helper.php)

### Para Desenvolvedores
1. Leia **[README.md](README.md)** para visão geral
2. Consulte **[ARCHITECTURE.md](ARCHITECTURE.md)** para estrutura
3. Veja **[LINTING.md](LINTING.md)** para padrões de código

### Para Performance
1. Veja **[PERFORMANCE-PROGRESS.md](PERFORMANCE-PROGRESS.md)** para status atual
2. Consulte **[PERFORMANCE-FIX-PLAN.md](PERFORMANCE-FIX-PLAN.md)** para próximos passos
3. Analise **[PAGESPEED-ANALYSIS-v2.6.8.md](PAGESPEED-ANALYSIS-v2.6.8.md)** para detalhes

---

## 📋 Manutenção

### Atualização
Este índice deve ser atualizado quando:
- Nova documentação é criada
- Estrutura de diretórios muda
- Novos helpers são adicionados
- Versão do projeto é atualizada

### Versão
- **Versão do Índice**: 1.0.0
- **Última Atualização**: 2025-11-16
- **Próxima Revisão**: Após mudanças significativas

---

**Mantido por**: Victor Penter  
**Para dúvidas**: Consultar documentação específica ou código fonte com comentários

