# Configuração de Analytics

Este documento explica como configurar o Plausible Analytics no site.

## Variáveis de Ambiente

Crie um arquivo `.env.local` na raiz do projeto com a seguinte variável:

```bash
# Plausible Analytics
# Obtenha seu domain em https://plausible.io
# Formato: seu-dominio.com.br
NEXT_PUBLIC_PLAUSIBLE_DOMAIN=minhamimo.com.br

# Opcional: Desabilitar analytics (útil para desenvolvimento local)
# DISABLE_ANALYTICS=true
```

## Setup

### 1. Plausible Analytics

**Configuração do Plausible:**

1. Acesse [Plausible Analytics](https://plausible.io) e crie uma conta
2. Adicione seu site (`minhamimo.com.br`)
3. Copie o **Domain** (o domínio que você adicionou)
4. Adicione `NEXT_PUBLIC_PLAUSIBLE_DOMAIN=minhamimo.com.br` no `.env.local` ou nas variáveis de ambiente do Vercel

**O que você ganha:**
- Analytics privacy-friendly (sem cookies, sem banner de consentimento necessário)
- Conformidade automática com LGPD/GDPR
- Métricas essenciais (pageviews, visitantes únicos, bounce rate, etc)
- Dashboard simples e intuitivo
- Dados agregados (não coleta dados pessoais)
- Script leve (~1KB) que não bloqueia renderização

**Vantagens sobre GA4/Clarity:**
- ✅ Não requer banner de consentimento (LGPD/GDPR compliant)
- ✅ Não usa cookies
- ✅ Não coleta dados pessoais (IPs são hasheados)
- ✅ Script carregado com `lazyOnload` (não bloqueia FCP/LCP)
- ✅ Mais leve e performático

## Eventos Trackados

O sistema tracka automaticamente os seguintes eventos:

### Automáticos
- `pageview` - Visualização de página (atualiza automaticamente em mudanças de rota do Next.js)

### Customizados (via API)
- `cta_click` - Cliques em CTAs (WhatsApp, agendamento)
  - Propriedades: `cta_type`, `location`
- `service_view` - Visualização de página de serviço
  - Propriedades: `service_slug`
- `navigation_click` - Cliques em itens de navegação
  - Propriedades: `menu_item`, `href`
- `scroll_depth` - Profundidade de scroll (25%, 50%, 75%, 100%)
  - Propriedades: `depth_percent`
- `time_on_page` - Tempo na página (30s, 1min, 2min+)
  - Propriedades: `seconds`

## Uso no Código

### Trackar evento customizado

```typescript
import { trackEvent } from '@/lib/analytics'

trackEvent('meu_evento', {
  propriedade1: 'valor1',
  propriedade2: 123,
})
```

### Trackar CTA click

```typescript
import { trackCTAClick } from '@/lib/analytics'

trackCTAClick('whatsapp_booking', 'header')
```

### Trackar visualização de serviço

```typescript
import { trackServiceView } from '@/lib/analytics'

trackServiceView('salao')
```

### Trackar scroll depth (automático)

O componente `AnalyticsPageTracker` já inicializa tracking automático de scroll depth. Se precisar fazer manualmente:

```typescript
import { initScrollDepthTracking } from '@/lib/analytics'

useEffect(() => {
  const cleanup = initScrollDepthTracking()
  return cleanup
}, [])
```

### Trackar tempo na página (automático)

O componente `AnalyticsPageTracker` já inicializa tracking automático de tempo na página. Se precisar fazer manualmente:

```typescript
import { initTimeOnPageTracking } from '@/lib/analytics'

useEffect(() => {
  const cleanup = initTimeOnPageTracking()
  return cleanup
}, [])
```

## Verificação

Após configurar a variável de ambiente:

1. Reinicie o servidor de desenvolvimento (`npm run dev`)
2. Navegue pelo site
3. Verifique no dashboard do Plausible se os eventos estão sendo coletados
4. Eventos aparecem em tempo real no dashboard

## Privacidade e LGPD

### Plausible Analytics
- ✅ **Não requer banner de consentimento** (LGPD/GDPR compliant)
- ✅ Não usa cookies
- ✅ Não coleta dados pessoais (IPs são hasheados, não armazenados)
- ✅ Dados agregados apenas
- ✅ Open source e auditável
- ✅ Dados ficam nos servidores do Plausible (ou self-hosted se configurado)

## Desabilitar Analytics

Para desabilitar analytics (útil em desenvolvimento local):

```bash
# No .env.local
DISABLE_ANALYTICS=true
```

Isso desabilita completamente o carregamento do script do Plausible e todas as funções de tracking retornam sem fazer nada.

## Troubleshooting

### Eventos não aparecem no Plausible
- Verifique se `NEXT_PUBLIC_PLAUSIBLE_DOMAIN` está configurado corretamente
- Verifique se `DISABLE_ANALYTICS` não está definido como `true`
- Verifique se o script está carregando (Network tab no DevTools)
- Verifique o console do browser por erros
- Aguarde alguns minutos - eventos podem levar tempo para aparecer no dashboard

### Script não está carregando
- Verifique se `NEXT_PUBLIC_PLAUSIBLE_DOMAIN` está configurado
- Verifique se `DISABLE_ANALYTICS` não está definido
- Verifique o Network tab no DevTools para ver se há requisições bloqueadas
- Verifique se ad blockers não estão bloqueando o script

### Verificar se analytics está funcionando

No console do browser:
```javascript
// Verificar se plausible está disponível
console.log(window.plausible)

// Verificar variável de ambiente (apenas em desenvolvimento)
console.log(process.env.NEXT_PUBLIC_PLAUSIBLE_DOMAIN)
```

## Migração de GA4/Clarity

O projeto migrou de Google Analytics 4 e Microsoft Clarity para Plausible Analytics por questões de:
- **Performance**: Script mais leve, não bloqueia renderização
- **Privacidade**: Não requer banner de consentimento (LGPD compliant)
- **Simplicidade**: Dashboard mais simples, focado no essencial

A API de eventos (`trackEvent`, `trackCTAClick`, etc) foi mantida compatível para facilitar a migração.
