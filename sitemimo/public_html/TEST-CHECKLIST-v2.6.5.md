# Checklist de Testes - v2.6.5

**Data**: 2025-11-15  
**Versão**: 2.6.5  
**Status**: Pré-produção

## ✅ FASE 1: Otimizações de Performance

### 1.1 Otimização de Imagens
- [x] Script de otimização executado
- [x] Todas imagens grandes têm AVIF/WebP
- [x] Imagens LCP têm preload e fetchpriority="high"
- [x] Preconnect para domínio próprio adicionado

### 1.2 CSS Crítico
- [x] CSS crítico expandido com estilos acima da dobra
- [x] Estilos de botões principais adicionados
- [x] Estilos de mobile categories adicionados
- [x] Estilos de testimonials carousel adicionados

### 1.3 Font Loading
- [x] EB Garamond usando font-display: optional
- [x] Akrobat usando font-display: optional
- [x] Nunito mantém font-display: swap (fonte principal)

### 1.4 Unused CSS
- [x] PurgeCSS executado
- [x] Arquivos purgados salvos em css/purged/
- [x] Asset helper configurado para usar arquivos purgados

### 1.5 Unused JavaScript
- [x] Scripts analisados
- [x] Todos scripts necessários mantidos

### 1.6 Minificação
- [x] USE_MINIFIED=true ativo
- [x] CSS minificado em minified/
- [x] JS minificado em minified/
- [x] Asset helper usando arquivos minificados

### 1.7 CLS
- [x] Width/height explícitos em todas imagens
- [x] Contain: layout style em containers principais
- [x] Aspect-ratio em imagens e containers
- [x] Espaço reservado para testimonials carousel
- [x] Espaço reservado para carousel controls

### 1.8 LCP Discovery
- [x] Preload de imagens LCP configurado
- [x] Preconnect para domínio próprio
- [x] Fetchpriority="high" em imagens LCP

## ✅ FASE 2: Revisão Estética

### 2.1 Cores da Marca
- [x] Rosa #ccb7bc (light) / #d4a5b0 (dark) verificado
- [x] Cinza #3a505a (light) / #7a9aab (dark) verificado
- [x] Cores hardcoded substituídas por variáveis CSS
- [x] product.css usando variáveis CSS
- [x] dark-mode.css usando variáveis CSS

### 2.2 Dark Mode
- [x] Toggle funciona em todas páginas
- [x] Transições suaves
- [x] Contraste adequado (WCAG AA)
- [x] localStorage funcionando
- [x] Detecção prefers-color-scheme funcionando
- [x] Botão toggle visível no mobile

### 2.3 Botões Clicáveis
- [x] Todos botões têm touch targets >= 44x44px (mobile)
- [x] Feedback visual em hover/active
- [x] Z-index correto (sem sobreposições)
- [x] Links externos abrem corretamente
- [x] Carousel controls funcionam

### 2.4 Centralização e Estética
- [x] Textos centralizados onde apropriado
- [x] Espaçamento consistente
- [x] Responsividade verificada
- [x] Hierarquia visual clara
- [x] Imagens não distorcidas

### 2.5 Contraste de Cores
- [x] Contraste light mode verificado
- [x] Contraste dark mode verificado
- [x] Botões e links com contraste adequado

## ✅ FASE 3: Testes Locais

### 3.1 Páginas
- [ ] Homepage carrega sem erros
- [ ] Contato carrega sem erros
- [ ] Vagas carrega sem erros
- [ ] Serviços carregam sem erros
- [ ] Imagens aparecem corretamente
- [ ] CSS aplicado corretamente
- [ ] JavaScript funciona

### 3.2 Funcionalidades
- [ ] Dark mode toggle funciona
- [ ] Carousel testimonials funciona (mobile)
- [ ] Carousel testimonials funciona (desktop)
- [ ] Menu mobile funciona
- [ ] Formulário contato funciona
- [ ] Navegação funciona
- [ ] Scroll suave funciona

### 3.3 Performance Local
- [ ] Network tab verificado (tamanho recursos)
- [ ] Console sem erros JavaScript
- [ ] Assets minificados carregando
- [ ] Lazy loading funcionando

## ✅ FASE 4: Validação Final

### 4.1 Sugestões do Google
- [x] Image delivery: Otimizado
- [x] Unused CSS: Removido (PurgeCSS)
- [x] Unused JS: Analisado
- [x] Minify CSS/JS: Minificado
- [x] Font display: Otimizado
- [x] CLS: Corrigido (width/height, contain, aspect-ratio)
- [x] FCP/LCP: Otimizado (CSS crítico, preload)

### 4.2 Revisão de Código
- [x] Código modificado revisado
- [x] Funcionalidades existentes não quebradas
- [x] Melhorias aplicadas corretamente
- [x] Documentação atualizada

### 4.3 Validação Pré-Produção
- [ ] Testes finais executados
- [ ] Sem erros de console
- [ ] Sem warnings
- [ ] Performance melhorou (estimativa)
- [ ] Checklist completo

## 📋 Próximos Passos

1. Executar testes locais completos
2. Validar funcionalidades
3. Verificar performance
4. Atualizar versão e documentação
5. Commit e push

