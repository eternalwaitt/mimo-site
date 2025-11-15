# Análise de Variação - PageSpeed Insights v2.6.6

**Data**: 2025-11-15  
**Observação**: PageSpeed Insights mostra variação natural entre testes

## 📊 Variação Observada

| Teste | Performance | FCP | LCP | CLS | TBT | SI | Network Payload |
|-------|------------|-----|-----|-----|-----|----|----------------|
| **Teste 1** | 66 | 4.1s | 6.3s | 0.000 | 0ms | 5.2s | 1,667 KiB |
| **Teste 2** | 56 | 3.3s | 4.6s | 0.401 | 0ms | 4.6s | 1,667 KiB |
| **Teste 3** | 51 | 4.1s | 4.6s | 0.557 | 0ms | 4.6s | 1,667 KiB |
| **Média** | **58** | **3.8s** | **5.2s** | **0.319** | **0ms** | **4.8s** | **1,667 KiB** |

## 🔍 Análise

### Variação de Performance
- **Range**: 51-66 (variação de 15 pontos)
- **Média**: 58
- **Causa**: Variação natural do PageSpeed Insights (diferentes condições de rede, servidor, etc.)

### Variação de CLS
- **Range**: 0.000-0.557 (variação significativa)
- **Média**: 0.319
- **Causa**: Layout shifts podem ocorrer de forma inconsistente dependendo do timing de carregamento

### Problemas Consistentes
Os seguintes problemas aparecem em TODOS os testes:
1. **Reduce unused CSS**: 86 KiB
2. **Reduce unused JavaScript**: 33 KiB
3. **Minify CSS**: 23 KiB
4. **Minify JavaScript**: 7 KiB
5. **Network Payload**: 1,667 KiB (consistente)

## 💡 Conclusão

**Variação é normal** no PageSpeed Insights devido a:
- Condições de rede variáveis
- Tempo de resposta do servidor variável
- Timing de carregamento de recursos
- Cache do navegador

**Problemas consistentes** (que aparecem em todos os testes):
- Unused CSS/JS (149 KiB)
- Minify CSS/JS (30 KiB)
- Network Payload (1,667 KiB)

**Ações necessárias** (independente da variação):
1. Verificar se arquivos purgados/minificados estão em produção
2. Criar build customizado do Bootstrap
3. Otimizar render-blocking resources

## 📋 Recomendação

**Usar média ou melhor resultado** para análise:
- **Performance**: 58 (média) ou 66 (melhor)
- **CLS**: 0.319 (média) ou 0.000 (melhor)
- **Foco**: Resolver problemas consistentes (unused CSS/JS, minify)

