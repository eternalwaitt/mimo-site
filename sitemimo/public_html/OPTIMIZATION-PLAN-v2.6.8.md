# Plano de Otimização v2.6.8

**Data**: 2025-11-15  
**Baseado em**: Análise PageSpeed Insights (18 testes)

## 📊 Status Atual

### Mobile
- **Performance**: 46-72 (média: 57) - Meta: 90+
- **CLS**: 0.19-1.62 (média: 0.83) - Meta: <0.1 ❌
- **LCP**: 1.84-8.40s (média: 6.02s) - Meta: <2.5s ❌
- **FCP**: 0.93-4.05s (média: 1.84s) - Meta: <1.8s ✅

### Desktop
- **Performance**: 57-97 (média: 72) - Meta: 90+
- **CLS**: 0.05-0.95 (média: 0.65) - Meta: <0.1 ❌
- **LCP**: 0.56-3.32s (média: 1.50s) - Meta: <2.5s ✅
- **FCP**: 0.27-0.84s (média: 0.38s) - Meta: <1.8s ✅

## 🎯 Problemas Críticos

### 1. CLS (Cumulative Layout Shift) - PRIORIDADE MÁXIMA

**Páginas com CLS > 1.0 (mobile)**:
- Salão: 1.62 ❌
- Vagas: 1.45 ❌
- Estética Facial: 1.33 ❌
- Cílios: 1.57 ❌

**Ações Imediatas**:

1. **Verificar imagens sem width/height explícitos**
   - Páginas de serviço (salao, cilios, esteticafacial, estetica, esmalteria, micropigmentacao)
   - Verificar se `picture_webp()` está detectando dimensões corretamente
   - Adicionar dimensões manuais onde necessário

2. **Reforçar `contain: layout style` em containers problemáticos**
   - Adicionar em todas as seções de conteúdo
   - Especialmente em páginas de serviço
   - Containers de imagens

3. **Adicionar `min-height` mais específico**
   - Cards de serviços
   - Seções de conteúdo
   - Containers de texto

4. **Verificar font loading**
   - Garantir `font-display: optional` está funcionando
   - Adicionar `size-adjust` em todas as fontes
   - Prevenir FOIT/FOUT

### 2. LCP (Largest Contentful Paint) - Mobile

**Páginas com LCP > 6s (mobile)**:
- Salão: 8.40s ❌
- Vagas: 8.25s ❌
- Contato: 7.88s ❌
- Micropigmentação: 6.84s ❌
- Estética: 6.98s ❌
- Esmalteria: 6.34s ❌

**Ações Imediatas**:

1. **Identificar imagens LCP em cada página**
   - Adicionar `fetchpriority="high"` nas imagens LCP
   - Remover lazy loading de imagens LCP
   - Preload de imagens LCP críticas

2. **Otimizar imagens grandes**
   - Converter todas para AVIF/WebP
   - Reduzir qualidade onde apropriado
   - Implementar srcset com múltiplos tamanhos

3. **Melhorar tempo de resposta do servidor**
   - Verificar cache headers
   - Otimizar PHP
   - Considerar CDN

### 3. Performance Score - Mobile

**Páginas abaixo de 60**:
- Homepage: 48
- Vagas: 47
- Salão: 46

**Ações Imediatas**:

1. **Reduzir unused CSS/JS**
   - Re-executar PurgeCSS
   - Verificar se Bootstrap custom está sendo usado
   - Remover código não utilizado

2. **Minificar tudo**
   - Garantir que USE_MINIFIED está ativo
   - Verificar se arquivos minificados existem
   - Atualizar asset-helper.php se necessário

## ✅ O que já foi feito

1. ✅ Migração Font Awesome → Lucide (completa)
2. ✅ PurgeCSS re-executado
3. ✅ CSS e JS minificados
4. ✅ Dark mode toggle com `contain: layout style`
5. ✅ Cache headers configurados
6. ✅ Font-display configurado

## 📝 Próximas Ações (Ordem de Prioridade)

### Fase 1: Corrigir CLS (Impacto: Alto)
1. Verificar todas as imagens têm width/height
2. Adicionar `contain: layout style` em containers problemáticos
3. Adicionar `min-height` específico
4. Verificar font loading

### Fase 2: Corrigir LCP Mobile (Impacto: Alto)
1. Identificar imagens LCP
2. Adicionar preload e fetchpriority
3. Otimizar imagens grandes
4. Melhorar tempo de resposta

### Fase 3: Reduzir Network Payload (Impacto: Médio)
1. Re-executar PurgeCSS
2. Ativar USE_MINIFIED
3. Verificar Bootstrap custom

### Fase 4: Testes e Iteração
1. Rodar PageSpeed Insights novamente
2. Verificar melhorias
3. Iterar até performance >= 90


