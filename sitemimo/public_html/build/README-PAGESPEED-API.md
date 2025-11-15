# PageSpeed Insights API - Scripts de Teste

Scripts para testar todas as páginas usando a API do PageSpeed Insights ao invés do site web.

## 📋 Pré-requisitos

1. **API Key do Google Cloud**:
   - Acesse: https://console.cloud.google.com/apis/credentials
   - Crie uma nova chave de API
   - Habilite a API "PageSpeed Insights API"

2. **Dependências**:
   - `curl` (já vem no macOS/Linux)
   - `jq` (opcional, para análise detalhada): `brew install jq` ou `apt-get install jq`
   - `bc` (opcional, para cálculos): `brew install bc` ou `apt-get install bc`

## 🚀 Uso

### 1. Testar Todas as Páginas

```bash
# Com API Key como argumento
./build/pagespeed-api-test.sh SUA_API_KEY_AQUI

# Ou usando variável de ambiente
export PAGESPEED_API_KEY='sua-chave-aqui'
./build/pagespeed-api-test.sh
```

### 2. Analisar Resultados

```bash
# Analisar todos os resultados JSON
./build/pagespeed-analyze.sh pagespeed-results
```

## 📊 Páginas Testadas

O script testa automaticamente:
- `/` (homepage)
- `/contato.php`
- `/vagas.php`
- `/esteticafacial/`
- `/estetica/`
- `/esmalteria/`
- `/salao/`
- `/micropigmentacao/`
- `/cilios/`

Cada página é testada em **mobile** e **desktop**.

## 📁 Estrutura de Arquivos

```
pagespeed-results/
├── mobile--20250130-120000.json
├── desktop--20250130-120000.json
├── mobile-contato-php-20250130-120000.json
├── desktop-contato-php-20250130-120000.json
├── ...
└── report-20250130-120000.md
```

## 🔍 Exemplo de Saída

```
🚀 Iniciando testes do PageSpeed Insights API
📊 Testando 9 páginas em 2 estratégias

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📱 Estratégia: mobile
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

[1/18] Testando: https://minhamimo.com.br/ (mobile)
  ✅ Sucesso
  📊 Performance: 0.47
  ♿ Accessibility: 0.91
  ✅ Best Practices: 0.96
  🔍 SEO: 1.0
  ⚡ FCP: 4.1s | LCP: 4.5s | CLS: 0.531 | TBT: 0.0s
  💾 Salvo em: pagespeed-results/mobile--20250130-120000.json
```

## 📝 Análise de Resultados

O script `pagespeed-analyze.sh` extrai:
- Scores de Performance, Accessibility, Best Practices, SEO
- Core Web Vitals (FCP, LCP, CLS, TBT)
- Oportunidades de melhoria

## 🔧 Rate Limiting

O script aguarda 1 segundo entre requisições para respeitar os limites da API.

## 📚 Referências

- [PageSpeed Insights API Documentation](https://developers.google.com/speed/docs/insights/rest/v5/pagespeedapi/runpagespeed)
- [Google Cloud Console](https://console.cloud.google.com/)

