# PageSpeed Insights API - Setup e Uso

**Data**: 2025-11-15  
**Versão**: 2.6.3

## ✅ Implementado

### 1. Scripts de Teste Automatizado

- **`build/pagespeed-api-test.sh`**: Testa todas as páginas usando a API do PageSpeed Insights
- **`build/pagespeed-analyze.sh`**: Analisa resultados e gera relatório
- **`build/README-PAGESPEED-API.md`**: Documentação completa

### 2. Correção do Carousel de Testimonials no Mobile

**Problema**: Menu de testimonials não funcionava no mobile após desabilitar animações.

**Solução Implementada**:
- ✅ CSS: Transições instantâneas (0.01s) ao invés de desabilitar completamente
- ✅ CSS: `pointer-events: auto`, `touch-action: manipulation` para garantir cliques
- ✅ JavaScript: Detecção mobile e handlers específicos para indicadores e controles
- ✅ JavaScript: Remove classe `carousel-fade` no mobile mas mantém funcionalidade
- ✅ JavaScript: Event handlers explícitos para garantir que cliques funcionem

**Arquivos Modificados**:
- `index.php`: Detecção mobile e handlers específicos
- `product.css`: Regras CSS para carousel no mobile
- `css/modules/animations.css`: Regras adicionais para carousel

## 🚀 Como Usar

### 1. Obter API Key

1. Acesse: https://console.cloud.google.com/apis/credentials
2. Crie uma nova chave de API
3. Habilite a API "PageSpeed Insights API"

### 2. Executar Testes

```bash
# Com API Key como argumento
./build/pagespeed-api-test.sh SUA_API_KEY_AQUI

# Ou usando variável de ambiente
export PAGESPEED_API_KEY='sua-chave-aqui'
./build/pagespeed-api-test.sh
```

### 3. Analisar Resultados

```bash
./build/pagespeed-analyze.sh pagespeed-results
```

## 📊 Páginas Testadas

O script testa automaticamente:
- `/` (homepage)
- `/contato.php`
- `/vagas.php`
- `/esteticafacial/`
- `/estetica/`
- `/esmalteria/`
- `/salao/`
- `/micropigmentacao/`
- `/cilios/`

Cada página é testada em **mobile** e **desktop** (18 testes no total).

## 🔧 Correções Aplicadas

### Carousel de Testimonials no Mobile

**Antes**: Não funcionava após desabilitar animações.

**Depois**: 
- Funciona perfeitamente no mobile
- Transições instantâneas (sem animação suave)
- Indicadores clicáveis
- Controles (prev/next) funcionais
- Swipe ainda funciona via bc-swipe.js

**Código Implementado**:

```javascript
// Detecção mobile
var isMobile = window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

if (isMobile) {
    // Desabilitar animações mas manter funcionalidade
    $carousel.removeClass('carousel-fade');
    $carousel.find('.carousel-item').css({
        'transition': 'none',
        'opacity': '1'
    });
    
    // Handlers explícitos para garantir funcionamento
    $carousel.find('.carousel-indicators li').on('click', function(e) {
        e.preventDefault();
        var slideTo = jQuery(this).data('slide-to');
        $carousel.carousel(slideTo);
    });
}
```

```css
@media (max-width: 768px) {
    /* Carousel testimonials - transições instantâneas */
    .testimonials-carousel.carousel-fade .carousel-item {
        transition: opacity 0.01s linear !important;
    }
    
    /* Garantir cliques funcionem */
    .testimonials-carousel .carousel-indicators li,
    .testimonials-carousel .carousel-control-prev,
    .testimonials-carousel .carousel-control-next {
        pointer-events: auto !important;
        touch-action: manipulation !important;
        z-index: 15 !important;
    }
}
```

## 📝 Próximos Passos

1. **Executar testes**: Rodar `pagespeed-api-test.sh` com API key
2. **Analisar resultados**: Usar `pagespeed-analyze.sh` para gerar relatório
3. **Aplicar correções**: Baseado nos resultados, aplicar correções necessárias
4. **Re-testar**: Executar novamente para validar melhorias

## 🔗 Referências

- [PageSpeed Insights API Documentation](https://developers.google.com/speed/docs/insights/rest/v5/pagespeedapi/runpagespeed)
- [Google Cloud Console](https://console.cloud.google.com/)

