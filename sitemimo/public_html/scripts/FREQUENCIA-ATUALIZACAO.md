# Frequência de Atualização dos Reviews

## Como Funciona Atualmente

### Cache da API (Fallback)
- **Duração**: 24 horas
- **O que faz**: Se não houver arquivo scraped, usa a API do Google (limitada a 5 reviews)
- **Custo**: ~$0.017 por chamada = ~$0.50/mês (dentro dos $200 grátis)

### Scraper (Principal)
- **Arquivo**: `cache/google_reviews_scraped.json`
- **Prioridade**: O PHP sempre verifica este arquivo primeiro
- **Configuração**: `overwrite_existing: false` (adiciona apenas reviews novos)

## Frequência Recomendada

### ✅ Recomendação: **1x por semana** (ou 2x por mês)

**Por quê?**
- Reviews não mudam com frequência alta
- Clientes geralmente deixam reviews após o serviço (não todo dia)
- Evita sobrecarga no Google (risco de bloqueio)
- Economiza recursos do servidor

### Opções de Frequência

#### Opção 1: Semanal (Recomendado)
```bash
# Rodar toda segunda-feira, por exemplo
cd scripts/temp-scraper
source venv/bin/activate
python3 start.py --config config-mimo.yaml
```

**Vantagens:**
- Reviews sempre atualizados
- Não sobrecarrega o Google
- Captura novos reviews regularmente

#### Opção 2: Quinzenal (2x por mês)
```bash
# Rodar no dia 1 e 15 de cada mês
```

**Vantagens:**
- Menos trabalho manual
- Ainda captura novos reviews
- Reduz risco de bloqueio

#### Opção 3: Mensal
```bash
# Rodar no início de cada mês
```

**Vantagens:**
- Mínimo de trabalho
- Funciona se reviews mudam pouco

**Desvantagens:**
- Reviews podem ficar desatualizados
- Pode perder reviews novos por mais tempo

## Como Rodar Automaticamente (Opcional)

### Cron Job (Linux/Mac)
```bash
# Editar crontab
crontab -e

# Rodar toda segunda-feira às 9h
0 9 * * 1 cd /caminho/para/sitemimo/public_html/scripts/temp-scraper && source venv/bin/activate && python3 start.py --config config-mimo.yaml --headless
```

### GitHub Actions (se repo no GitHub)
Criar `.github/workflows/update-reviews.yml`:
```yaml
name: Update Google Reviews
on:
  schedule:
    - cron: '0 9 * * 1'  # Toda segunda às 9h
  workflow_dispatch:  # Permite rodar manualmente

jobs:
  update:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-python@v4
        with:
          python-version: '3.11'
      - run: |
          cd scripts/temp-scraper
          pip install -r requirements.txt
          python3 start.py --config config-mimo.yaml --headless
      - name: Commit changes
        run: |
          git config --local user.email "action@github.com"
          git config --local user.name "GitHub Action"
          git add cache/google_reviews_scraped.json
          git commit -m "Update reviews" || exit 0
          git push
```

## Quando Rodar Manualmente

Rode imediatamente se:
- ✅ Você sabe que recebeu reviews novos importantes
- ✅ Quer atualizar após uma campanha de marketing
- ✅ Notou que os reviews no site estão desatualizados
- ✅ Fez alguma mudança na filtragem/ordenação

## Monitoramento

### Verificar Última Atualização
```bash
# Ver quando o arquivo foi atualizado pela última vez
ls -lh cache/google_reviews_scraped.json

# Ver quantos reviews temos
python3 -c "import json; print(len(json.load(open('cache/google_reviews_scraped.json'))))"
```

### Verificar Reviews Novos
O scraper com `overwrite_existing: false` adiciona apenas reviews novos. Você pode comparar:
```bash
# Antes de rodar
cp cache/google_reviews_scraped.json cache/google_reviews_scraped.json.backup

# Depois de rodar
python3 -c "
import json
old = json.load(open('cache/google_reviews_scraped.json.backup'))
new = json.load(open('cache/google_reviews_scraped.json'))
print(f'Reviews antes: {len(old)}')
print(f'Reviews depois: {len(new)}')
print(f'Novos reviews: {len(new) - len(old)}')
"
```

## Resumo

| Frequência | Trabalho | Atualização | Risco Bloqueio |
|------------|----------|-------------|----------------|
| **Semanal** | ⭐⭐ | ⭐⭐⭐ | ⭐ |
| **Quinzenal** | ⭐ | ⭐⭐ | ⭐ |
| **Mensal** | ⭐ | ⭐ | ⭐ |

**Recomendação final**: Comece com **semanal** e ajuste conforme necessário. Se notar que reviews não mudam muito, pode reduzir para quinzenal.

