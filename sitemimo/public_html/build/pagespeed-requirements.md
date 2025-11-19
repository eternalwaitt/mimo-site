# PageSpeed Insights API - Requisitos

## API Key Necessária

Para executar os testes automatizados, você precisa de uma API Key do Google Cloud Platform.

### Como Obter a API Key

1. Acesse: https://console.cloud.google.com/apis/credentials
2. Faça login na sua conta Google
3. Selecione ou crie um projeto
4. Clique em "Criar credenciais" > "Chave de API"
5. Copie a chave gerada
6. **IMPORTANTE**: Habilite a API "PageSpeed Insights API":
   - Vá para: https://console.cloud.google.com/apis/library
   - Procure por "PageSpeed Insights API"
   - Clique em "Ativar"

### Como Usar a API Key

**Opção 1: Variável de Ambiente (Recomendado)**
```bash
export PAGESPEED_API_KEY='sua-chave-aqui'
./build/pagespeed-run-tests.sh
```

**Opção 2: Argumento do Script**
```bash
./build/pagespeed-run-tests.sh 'sua-chave-aqui'
```

**Opção 3: Direto no script de teste**
```bash
./build/pagespeed-api-test.sh 'sua-chave-aqui'
```

### Limites da API

- **Quota gratuita**: 25,000 requisições por dia
- **Rate limit**: ~1 requisição por segundo (nosso script aguarda 1s entre requisições)
- **Custo**: Gratuito dentro da quota

### Teste Rápido

Para testar se a API key está funcionando:

```bash
curl "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=https://minhamimo.com.br&strategy=mobile&key=SUA_CHAVE"
```

Se funcionar, você verá um JSON com os resultados.

