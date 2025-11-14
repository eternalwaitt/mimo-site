# Configuração da Google Places API

## Status Atual

✅ **API Key configurada**: `AIzaSyBHKeuRbKzA_ehEXmBvxAceghhpJw6ND6g`  
✅ **Place ID configurado**: `ChIJkVYWuB1XzpQRjbjBjyb4H6M`

## Próximos Passos Necessários

### 1. Habilitar Places API (New) no Google Cloud

1. Acesse [Google Cloud Console](https://console.cloud.google.com/)
2. Selecione o projeto da sua API key
3. Vá em **APIs & Services** > **Library**
4. Procure por **"Places API (New)"**
5. Clique em **Enable** (Ativar)

**IMPORTANTE**: Não use "Places API" (Legacy), use **"Places API (New)"**

### 2. Configurar Billing (Obrigatório)

Mesmo com créditos grátis, o Google exige billing configurado:

1. Vá em **Billing** no Google Cloud Console
2. Clique em **Link a billing account**
3. Adicione um cartão de crédito
4. Configure alertas de gasto ($5, $10, etc)

**Não se preocupe**: Com cache de 24h, você nunca vai passar dos $200 grátis/mês.

### 3. Restringir API Key (Segurança)

1. Vá em **APIs & Services** > **Credentials**
2. Clique na sua API key
3. Em **Application restrictions**:
   - Selecione **HTTP referrers**
   - Adicione: `https://minhamimo.com.br/*`
4. Em **API restrictions**:
   - Selecione **Restrict key**
   - Marque apenas **Places API (New)**

## Teste

Após configurar, teste com:

```bash
curl -X POST "https://places.googleapis.com/v1/places/ChIJkVYWuB1XzpQRjbjBjyb4H6M" \
  -H "Content-Type: application/json" \
  -H "X-Goog-Api-Key: AIzaSyBHKeuRbKzA_ehEXmBvxAceghhpJw6ND6g" \
  -H "X-Goog-FieldMask: id,displayName,rating,userRatingCount,reviews" \
  -d '{"languageCode":"pt-BR"}'
```

## Alternativa: Reviews Manuais

Se não quiser configurar billing agora, o sistema já está usando **reviews manuais** automaticamente. Você pode:

1. Editar `inc/manual-reviews.php`
2. Adicionar reviews reais do Google Maps
3. O Schema.org funciona igual para SEO

## Custo Estimado

Com cache de 24h:
- **1 chamada/dia** = 30 chamadas/mês
- **Custo**: ~$0.50/mês (Places API New)
- **Dentro dos créditos grátis**: ✅

