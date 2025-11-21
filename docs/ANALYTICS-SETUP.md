# Configuração de Analytics

Este documento explica como configurar as ferramentas de analytics e inteligência de usuário no site.

## Variáveis de Ambiente

Crie um arquivo `.env.local` na raiz do projeto com as seguintes variáveis:

```bash
# Google Analytics 4 (GA4)
# Obtenha seu Measurement ID em https://analytics.google.com
# Formato: G-XXXXXXXXXX
NEXT_PUBLIC_GA_MEASUREMENT_ID=G-KN32FXHXW8

# Microsoft Clarity
# Obtenha seu project ID em https://clarity.microsoft.com
# Free tier: sessões e gravações ilimitadas
NEXT_PUBLIC_CLARITY_PROJECT_ID=your_project_id_here
```

## Setup

### 1. Google Analytics 4 (GA4)

**Configuração do Google Analytics:**

1. Acesse [Google Analytics](https://analytics.google.com) e faça login com sua conta Google
2. Clique em "Criar" para criar uma nova propriedade (se ainda não tiver)
3. Configure:
   - Nome da propriedade: "Mimo Site" (ou o que preferir)
   - Fuso horário: (GMT-03:00) Brasília
   - Moeda: Real brasileiro (BRL)
4. Configure o fluxo de dados:
   - Escolha "Web"
   - URL do site: `https://minhamimo.com.br`
   - Nome do fluxo: "Mimo Site"
5. Copie o **Measurement ID** (formato: `G-XXXXXXXXXX`)
   - ID atual: `G-KN32FXHXW8`
6. Adicione `NEXT_PUBLIC_GA_MEASUREMENT_ID=G-KN32FXHXW8` no `.env.local` ou nas variáveis de ambiente do Vercel

**⚠️ Importante - Banner de Consentimento (LGPD/GDPR):**

Google Analytics usa cookies e coleta dados pessoais, então você **precisa** de um banner de consentimento no site para estar em conformidade com LGPD/GDPR.

**O que você ganha:**
- Analytics completo e gratuito
- Funis de conversão avançados
- Integração com Google Ads e Search Console
- Análises detalhadas de comportamento
- Dados em tempo real
- Sem limites de uso

**O que você precisa:**
- Banner de consentimento (obrigatório por LGPD)
- Configurar políticas de privacidade

### 2. Microsoft Clarity

1. Acesse [Microsoft Clarity](https://clarity.microsoft.com) e faça login com conta Microsoft
2. Crie um novo projeto
3. Copie o Project ID
4. Adicione `NEXT_PUBLIC_CLARITY_PROJECT_ID` no `.env.local`

**O que você ganha:**
- Heatmaps (click, scroll, move)
- Session recordings ilimitados
- Insights automáticos (rage clicks, dead clicks, etc)

## Eventos Trackados

O sistema tracka automaticamente os seguintes eventos:

### Automáticos
- `page_view` - Visualização de página (GA4 automático, atualiza em mudanças de rota)
- `scroll_depth` - Profundidade de scroll (25%, 50%, 75%, 100%)
- `time_on_page` - Tempo na página (30s, 1min, 2min+)

### Manuais
- `cta_click` - Cliques em CTAs (WhatsApp, agendamento)
  - Propriedades: `cta_type`, `location`
- `service_view` - Visualização de página de serviço
  - Propriedades: `service_slug`
- `navigation_click` - Cliques em itens de navegação
  - Propriedades: `menu_item`, `href`

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

## Verificação

Após configurar as variáveis de ambiente:

1. Reinicie o servidor de desenvolvimento (`npm run dev`)
2. Navegue pelo site
3. Verifique no dashboard do Google Analytics (Relatórios > Tempo Real) se os eventos estão sendo coletados
4. Verifique no dashboard do Clarity se as sessões estão sendo gravadas

## Privacidade e LGPD

### Google Analytics
- ⚠️ **Requer banner de consentimento** (obrigatório por LGPD/GDPR)
- Usa cookies para rastreamento
- Coleta dados pessoais (IP, user agent, etc)
- Dados ficam nos servidores do Google
- Configure política de privacidade no site

### Microsoft Clarity
- Pode precisar de banner de consentimento (verificar LGPD)
- Grava sessões (pode conter dados pessoais)
- Dados ficam nos servidores da Microsoft

## Migração Futura para Plausible

A API de eventos (`trackEvent`, `trackCTAClick`, etc) foi projetada para ser provider-agnostic. Para migrar para Plausible no futuro:

1. Atualize `lib/analytics.ts` para usar `window.plausible()` ao invés de `window.gtag()`
2. Atualize `components/analytics-provider.tsx` para carregar script do Plausible
3. Atualize `app/layout.tsx` para incluir script do Plausible
4. Remova variável `NEXT_PUBLIC_GA_MEASUREMENT_ID` e adicione `NEXT_PUBLIC_PLAUSIBLE_*`

## Troubleshooting

### Eventos não aparecem no Google Analytics
- Verifique se `NEXT_PUBLIC_GA_MEASUREMENT_ID` está configurado corretamente (formato: `G-XXXXXXXXXX`)
- ID atual: `G-KN32FXHXW8`
- Verifique se o script está carregando (Network tab no DevTools)
- Verifique o console do browser por erros
- No GA4, vá em Relatórios > Tempo Real para ver eventos imediatamente
- Aguarde alguns minutos para eventos aparecerem em relatórios padrão

### Clarity não está gravando
- Verifique se `NEXT_PUBLIC_CLARITY_PROJECT_ID` está configurado
- Aguarde alguns minutos - pode levar tempo para aparecer no dashboard
- Verifique o console do browser por erros
