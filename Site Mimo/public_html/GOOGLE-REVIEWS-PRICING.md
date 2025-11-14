# Google Places API - Custos e Otimização

## Créditos Mensais

✅ **SIM, os créditos são MENSais**
- **$200 créditos grátis/mês**
- Resetam todo mês (não acumulam)
- Se não usar tudo, perde no fim do mês

## Places API vs Places API (New)

### Places API (New) - ✅ RECOMENDADO
- **Custo**: ~$0.017 por chamada (Place Details)
- Mais barato
- Mais rápido
- Versão atual e suportada
- **O código já usa esta versão**

### Places API (Legacy) - ❌ NÃO RECOMENDADO
- **Custo**: ~$0.032 por chamada
- Mais caro (quase o dobro)
- Versão antiga
- Pode ser descontinuada

## Otimização de Custos

### Cache Configurado
O código usa **cache de 24 horas**, o que significa:
- **1 chamada por dia** = ~30 chamadas/mês
- **Custo**: ~$0.50/mês
- **Dentro dos créditos grátis**: ✅

### Opções de Cache

Você pode ajustar o cache em `inc/google-reviews.php`:

```php
// Cache de 24h (atual) - ~$0.50/mês
$cacheTime = 86400;

// Cache de 7 dias - ~$0.12/mês
$cacheTime = 604800;

// Cache de 30 dias - ~$0.02/mês
$cacheTime = 2592000;
```

### Recomendação

**Use cache de 24h ou 7 dias**:
- Reviews não mudam com frequência
- Não precisa atualizar todo dia
- Economiza créditos
- $200 créditos grátis duram MUITO tempo

## Exemplo de Uso Mensal

Com cache de 24h:
- 30 chamadas/mês
- Custo: ~$0.50
- **Sobra $199.50 de créditos grátis** 🎉

Com cache de 7 dias:
- 4 chamadas/mês
- Custo: ~$0.07
- **Sobra $199.93 de créditos grátis** 🎉

## Conclusão

✅ **É seguro configurar billing**
- Créditos são mensais e generosos
- Com cache, custo é mínimo
- Você nunca vai passar dos $200 grátis
- Pode configurar alertas de gasto ($5, $10, etc)

**Ou use reviews manuais** (100% grátis, sem billing)

