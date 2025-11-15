# ⚠️ API Key Necessária para Executar Testes

Para continuar com a implementação do plano de otimização do PageSpeed Insights, é necessário uma **API Key do Google Cloud Platform**.

## Status Atual

✅ **Preparação Completa**:
- Scripts de teste criados e configurados
- Scripts de análise preparados
- Estrutura de diretórios pronta
- Workflow completo implementado

⏳ **Aguardando**: API Key para executar testes

## Como Obter a API Key

1. **Acesse**: https://console.cloud.google.com/apis/credentials
2. **Faça login** na sua conta Google
3. **Selecione ou crie um projeto**
4. **Crie credenciais**:
   - Clique em "Criar credenciais" > "Chave de API"
   - Copie a chave gerada
5. **Habilite a API**:
   - Vá para: https://console.cloud.google.com/apis/library
   - Procure por "PageSpeed Insights API"
   - Clique em "Ativar"

## Como Executar os Testes

Depois de obter a API key, execute:

```bash
# Opção 1: Variável de ambiente
export PAGESPEED_API_KEY='sua-chave-aqui'
cd sitemimo/public_html
./build/pagespeed-complete-workflow.sh

# Opção 2: Como argumento
cd sitemimo/public_html
./build/pagespeed-complete-workflow.sh 'sua-chave-aqui'
```

## O Que Será Executado

1. **18 Testes Automatizados**:
   - 9 páginas × 2 estratégias (mobile + desktop)
   - Páginas testadas: `/`, `/contato.php`, `/vagas.php`, `/esteticafacial/`, `/estetica/`, `/esmalteria/`, `/salao/`, `/micropigmentacao/`, `/cilios/`

2. **Análise Automática**:
   - Extração de scores (Performance, Accessibility, Best Practices, SEO)
   - Extração de Core Web Vitals (FCP, LCP, CLS, TBT, SI)
   - Identificação de todas as oportunidades de melhoria

3. **Relatórios Gerados**:
   - `pagespeed-results/report-*.md`: Relatório consolidado
   - `pagespeed-results/all-issues-*.md`: Lista completa de problemas
   - `pagespeed-results/*.json`: Resultados brutos de cada teste

## Próximos Passos Após Obter a API Key

1. Execute o workflow completo
2. Analise os resultados gerados
3. Implemente as correções identificadas
4. Re-teste para validar melhorias

## Scripts Disponíveis

- `build/pagespeed-complete-workflow.sh`: Workflow completo (testes + análise + extração)
- `build/pagespeed-api-test.sh`: Apenas testes
- `build/pagespeed-analyze.sh`: Apenas análise de resultados existentes
- `build/pagespeed-extract-issues.sh`: Apenas extração de problemas

