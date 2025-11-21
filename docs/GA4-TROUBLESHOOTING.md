# Troubleshooting Google Analytics 4

## Problema: "No data received from your website yet"

Se você está vendo essa mensagem no Google Analytics, siga estes passos:

### 1. Verificar Variável de Ambiente

**No Vercel (Produção):**
1. Acesse o dashboard do Vercel
2. Vá em Settings > Environment Variables
3. Verifique se `NEXT_PUBLIC_GA_MEASUREMENT_ID` está configurado
4. Valor deve ser: `G-H4JTDSJPSQ` (sem aspas)
5. Se não estiver, adicione e faça um novo deploy

**Localmente:**
1. Verifique se `.env.local` existe na raiz do projeto
2. Deve conter: `NEXT_PUBLIC_GA_MEASUREMENT_ID=G-H4JTDSJPSQ`
3. Reinicie o servidor (`npm run dev`)

### 2. Verificar se o Script Está Carregando

1. Abra o site em produção (não localhost)
2. Abra DevTools (F12)
3. Vá na aba Network
4. Filtre por "gtag" ou "google-analytics"
5. Você deve ver requisições para:
   - `https://www.googletagmanager.com/gtag/js?id=G-H4JTDSJPSQ`
   - `https://www.google-analytics.com/g/collect?...`

Se não aparecer, o script não está carregando.

### 3. Verificar Console do Browser

1. Abra DevTools (F12)
2. Vá na aba Console
3. Procure por erros relacionados a `gtag` ou `dataLayer`
4. Verifique se `window.gtag` existe:
   ```javascript
   console.log(window.gtag)
   console.log(window.dataLayer)
   ```

### 4. Verificar no Código Fonte

1. Abra o site em produção
2. View Source (Ctrl+U ou Cmd+Option+U)
3. Procure por "G-H4JTDSJPSQ" no HTML
4. Deve aparecer em dois lugares:
   - No script src: `https://www.googletagmanager.com/gtag/js?id=G-H4JTDSJPSQ`
   - No script inline com `gtag('config', 'G-H4JTDSJPSQ')`

### 5. Testar em Produção

**Importante:** Google Analytics pode não funcionar bem em localhost. Teste sempre em produção.

1. Faça deploy no Vercel
2. Acesse o site em produção
3. Navegue por algumas páginas
4. Aguarde 1-2 minutos
5. Vá no GA4 > Relatórios > Tempo Real
6. Você deve ver eventos aparecendo

### 6. Verificar Configuração do GA4

1. No Google Analytics, vá em Admin
2. Verifique se o Measurement ID está correto: `G-H4JTDSJPSQ`
3. Verifique se o domínio está configurado corretamente
4. Vá em Data Streams > Web > seu fluxo
5. Verifique se a URL está correta

### 7. Usar Google Tag Assistant

1. Instale a extensão [Google Tag Assistant](https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejbdjndbnbjgmefkgdddjlbokphdefk)
2. Acesse o site em produção
3. Clique no ícone da extensão
4. Deve mostrar se o GA4 está configurado corretamente

### 8. Verificar Ad Blockers

1. Desative ad blockers temporariamente
2. Alguns ad blockers bloqueiam Google Analytics
3. Teste em modo anônimo/privado

### Checklist Rápido

- [ ] Variável `NEXT_PUBLIC_GA_MEASUREMENT_ID=G-H4JTDSJPSQ` configurada no Vercel
- [ ] Novo deploy feito após adicionar variável
- [ ] Testando em produção (não localhost)
- [ ] Script do gtag aparece no Network tab
- [ ] Sem erros no console
- [ ] Aguardou 1-2 minutos após acessar o site
- [ ] Verificando em "Tempo Real" no GA4 (não relatórios padrão)

### Se Nada Funcionar

1. Verifique se o código está correto no repositório
2. Verifique se o build está passando sem erros
3. Tente adicionar o script manualmente no HTML para testar
4. Verifique logs do Vercel para erros de build

