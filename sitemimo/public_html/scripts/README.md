# Scripts de Automação

## Google Reviews Scraper

Script para buscar reviews do Google Maps periodicamente e salvar em JSON para uso no site.

### Setup

1. **Instalar dependências:**
```bash
pip install selenium undetected-chromedriver
pip install git+https://github.com/georgekhananaev/google-reviews-scraper-pro.git
```

2. **Rodar manualmente quando necessário:**
```bash
cd sitemimo/public_html/scripts
python3 fetch-google-reviews.py --place-url "https://maps.app.goo.gl/SEU_LINK" --output ../cache/google_reviews_scraped.json
```

### Uso no PHP

O PHP já está configurado para ler o arquivo `cache/google_reviews_scraped.json` se existir, priorizando sobre a API limitada.

### Vantagens

- ✅ Acesso a mais reviews (não limitado a 5)
- ✅ Reviews reais e variados
- ✅ Prioriza reviews com foto REAL de perfil (não placeholders)
- ✅ Prioriza 5 estrelas e textos de tamanho médio (100-500 chars)
- ✅ Randomização: mostra reviews diferentes a cada carregamento (sempre entre os melhores)
- ✅ Filtragem inteligente: remove COVID, autores excluídos, notas baixas
- ✅ Cache local reduz chamadas à API
- ✅ Controle manual (roda quando você quiser)

### Riscos

- ⚠️ Viola ToS do Google (use por sua conta e risco)
- ⚠️ Pode ser bloqueado se usado muito frequentemente
- ⚠️ Requer manutenção quando Google atualiza interface

### Recomendações

- Rode manualmente quando quiser atualizar os reviews (1x por semana recomendado)
- Use modo headless (`--headless`) para rodar em background
- Configure delays razoáveis no scraper
- Monitore se está funcionando
- Tenha fallback para API oficial (já implementado)
- O script filtra automaticamente: apenas 4 e 5 estrelas, textos > 10 chars
- Use `scripts/limpar-reviews.php` para limpar reviews indesejados da base

### Documentação

- `COMO-USAR-SCRAPER.md` - Guia completo de uso do scraper
- `TROUBLESHOOTING-SCRAPER.md` - Solução de problemas
- `COMO-LIMPAR-REVIEWS.md` - Como limpar reviews da base
- `FREQUENCIA-ATUALIZACAO.md` - Frequência recomendada de atualização

