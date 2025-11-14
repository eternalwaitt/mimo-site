# Configuração de Google Reviews

Este guia explica como configurar a integração com Google My Business para exibir reviews no site.

## Pré-requisitos

1. **Google My Business** configurado e verificado
2. **Google Cloud Platform** conta
3. **Places API** habilitada no GCP

## Passo a Passo

### 1. Obter Google Place ID

1. Acesse [Google Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id)
2. Digite o endereço do negócio: `Rua Heitor Penteado, 626, Vila Madalena, São Paulo`
3. Copie o **Place ID** (ex: `ChIJ...`)

### 2. Criar API Key no Google Cloud

1. Acesse [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione existente
3. Vá em **APIs & Services** > **Library**
4. Procure por **Places API** e habilite
5. Vá em **APIs & Services** > **Credentials**
6. Clique em **Create Credentials** > **API Key**
7. Copie a API Key gerada

### 3. Configurar no Site

Adicione no arquivo `.env`:

```env
GOOGLE_PLACES_API_KEY=sua_api_key_aqui
GOOGLE_PLACE_ID=seu_place_id_aqui
```

Ou configure diretamente no `config.php` (não recomendado para produção).

### 4. Restringir API Key (Recomendado)

Para segurança, restrinja a API Key:

1. No Google Cloud Console, edite a API Key
2. Em **Application restrictions**, selecione **HTTP referrers**
3. Adicione: `https://minhamimo.com.br/*`
4. Em **API restrictions**, selecione **Restrict key**
5. Selecione apenas **Places API**

## Como Funciona

- **Cache**: Reviews são cacheados por 1 hora para reduzir chamadas à API
- **Filtro**: Apenas reviews de 4 e 5 estrelas são exibidos
- **Schema.org**: Gera structured data para SEO
- **Limite**: Máximo de 10 reviews exibidos

## Uso no Código

```php
// Buscar reviews (filtra apenas 4 e 5 estrelas)
$reviews = get_google_reviews(GOOGLE_PLACE_ID, GOOGLE_PLACES_API_KEY, 4, 10);

// Renderizar HTML
echo render_google_reviews($reviews);

// Gerar Schema.org
echo generate_aggregate_rating_schema(4.8, 127);
```

## Custos e Créditos

### Créditos Mensais
- **$200 créditos grátis/mês** (resetam todo mês)
- Créditos são **mensais**, não acumulam
- Se não usar tudo, perde no fim do mês

### Custos Reais
- **Places API (Legacy)**: ~$0.032 por chamada (Place Details)
- **Places API (New)**: ~$0.017 por chamada (mais barato, mas mais complexo)

### Economia com Cache
- **Cache de 24h**: ~1 chamada/dia = **~$1.00/mês** (30 chamadas)
- **Cache de 7 dias**: ~4 chamadas/mês = **~$0.13/mês**
- **Cache de 30 dias**: ~1 chamada/mês = **~$0.03/mês**

**Recomendação**: Use cache de 7-30 dias. Reviews não mudam com frequência, então não precisa atualizar todo dia.

## Places API vs Places API (New)

### Places API (Legacy) - USADO NO CÓDIGO ✅
- Mais simples de implementar
- Estável e bem documentada
- ~$0.032 por chamada
- Funciona perfeitamente para reviews

### Places API (New) - ALTERNATIVA
- Mais barato (~$0.017 por chamada)
- Requer autenticação mais complexa
- Versão mais nova

**O código usa Places API (Legacy)** por simplicidade. Com cache longo, a diferença de custo é mínima.

## Troubleshooting

**Reviews não aparecem?**
- Verifique se Place ID está correto
- Confirme que Places API está habilitada
- Verifique logs de erro do PHP

**Erro de API Key?**
- Verifique se a key está correta
- Confirme restrições de HTTP referrer
- Verifique se Places API está habilitada

## Exemplo de Integração

Para substituir ou complementar os testimonials atuais, adicione após a seção de depoimentos:

```php
<?php if (defined('GOOGLE_PLACES_API_KEY') && !empty(GOOGLE_PLACES_API_KEY)): ?>
    <div class="google-reviews-section">
        <h3>AVALIAÇÕES DO GOOGLE</h3>
        <?php 
        $reviews = get_google_reviews(GOOGLE_PLACE_ID, GOOGLE_PLACES_API_KEY, 4, 6);
        echo render_google_reviews($reviews);
        ?>
    </div>
<?php endif; ?>
```

