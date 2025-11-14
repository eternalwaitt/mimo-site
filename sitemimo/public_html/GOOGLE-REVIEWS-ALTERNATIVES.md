# Alternativas para Google Reviews (Sem Billing)

Se você não quer configurar billing no Google Cloud, aqui estão alternativas:

## Opção 1: Schema.org Manual (Recomendado - Grátis)

Você pode adicionar reviews manualmente com Schema.org sem usar API:

```php
// No index.php, adicione reviews manuais
$manualReviews = [
    [
        'author' => 'Maria Silva',
        'rating' => 5,
        'text' => 'Atendimento excelente! Profissionais muito atenciosas.',
        'date' => '2025-01-15'
    ],
    // Adicione mais reviews conforme necessário
];
```

**Vantagens:**
- ✅ 100% grátis
- ✅ Controle total sobre quais reviews exibir
- ✅ Funciona imediatamente
- ✅ Schema.org para SEO funciona igual

**Desvantagens:**
- ❌ Precisa atualizar manualmente
- ❌ Não sincroniza automaticamente com Google

## Opção 2: Google Places API (Com Billing)

### Custos Reais:
- **$200 créditos grátis/mês** (suficiente para ~11,000 requisições)
- Cada chamada custa **~$0.017**
- Com cache de 1h, você faria ~24 chamadas/dia = **~$0.40/mês**

### Como Configurar Billing:
1. Google Cloud Console
2. Billing > Link billing account
3. Adicionar cartão (não cobra se ficar dentro do crédito grátis)
4. Configurar alertas de gasto ($5, $10, etc)

**É seguro:** Com cache e uso normal, você nunca vai passar dos $200 grátis.

## Opção 3: Scraping (Não Recomendado)

Scraping do Google pode:
- ❌ Violar termos de serviço
- ❌ Quebrar a qualquer momento
- ❌ Ser bloqueado pelo Google

## Opção 4: Widget do Google (Mais Simples)

Google oferece widget oficial que você pode embedar:

```html
<!-- Widget oficial do Google -->
<script src="https://apis.google.com/js/platform.js"></script>
<div class="g-review" data-href="https://www.google.com/maps/place/..."></div>
```

**Vantagens:**
- ✅ Grátis
- ✅ Oficial do Google
- ✅ Atualiza automaticamente

**Desvantagens:**
- ❌ Menos controle visual
- ❌ Não filtra por rating

## Recomendação

Para começar, use **Opção 1 (Schema Manual)** - é grátis, funciona imediatamente e você pode migrar para API depois se quiser.

Se quiser automatizar, a **Opção 2 (Places API)** é barata e segura com os créditos grátis.

