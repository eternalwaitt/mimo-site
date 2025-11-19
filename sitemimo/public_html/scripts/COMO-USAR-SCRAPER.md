# Como Usar o Scraper de Google Reviews

## Instalação Rápida

```bash
# 1. Ir para o diretório do scraper
cd scripts/temp-scraper

# 2. Criar ambiente virtual (OBRIGATÓRIO no macOS com Homebrew)
python3 -m venv venv

# 3. Ativar ambiente virtual
source venv/bin/activate  # No Mac/Linux
# ou
venv\Scripts\activate  # No Windows

# 4. Instalar dependências
pip install -r requirements.txt
```

**Nota:** No macOS, você DEVE usar um ambiente virtual. O Python gerenciado pelo Homebrew não permite instalação de pacotes diretamente no sistema.

## Uso Básico

### Opção 1: Usar Configuração Pronta (Recomendado para Mimo)

Já existe um arquivo de configuração pronto: `config-mimo.yaml`

**IMPORTANTE:** Por padrão, o config está com `headless: false` para maior estabilidade. Se você quiser executar em background, mude para `true`.

```bash
cd scripts/temp-scraper

# IMPORTANTE: Ativar o ambiente virtual primeiro!
source venv/bin/activate

# Executar com a configuração do Mimo (com janela visível - mais estável)
python3 start.py --config config-mimo.yaml
```

Isso vai:
- Buscar reviews do Google Maps da Mimo
- Ordenar por rating mais alto (5 estrelas primeiro)
- Salvar em `../../cache/google_reviews_scraped.json`
- Baixar imagens para `../../cache/review_images/`
- Mostrar a janela do Chrome (para debug e estabilidade)

**Dica:** Se você tiver problemas com o Chrome fechando, execute SEM `headless` primeiro para ver o que está acontecendo.

### Opção 2: Comando Simples (sem config)

```bash
# Buscar reviews do Google Maps da Mimo
python3 start.py \
  --url "https://www.google.com/maps/place/?q=place_id:ChIJkVYWuB1XzpQRjbjBjyb4H6M" \
  --headless \
  --sort highest \
  --use-mongodb false
```

**Nota:** Sem o arquivo de config, os reviews serão salvos em `google_reviews.json` na pasta do scraper. Você precisará mover manualmente para `../../cache/google_reviews_scraped.json`.

### Opção 3: Criar Seu Próprio Config

1. Copie o exemplo:
```bash
cp examples/config-example.txt config.yaml
```

2. Edite `config.yaml`:
```yaml
url: "https://maps.app.goo.gl/SEU_URL_AQUI"
headless: true
sort_by: "highest"  # ou "newest" para mais recentes
backup_to_json: true
json_path: "../../cache/google_reviews_scraped.json"
download_images: true
image_dir: "../../cache/review_images"
```

3. Execute:
```bash
python3 start.py --config config.yaml
```

## Configuração para o Site Mimo

### Usar Config Pronta (Mais Fácil)

```bash
cd scripts/temp-scraper
python3 start.py --config config-mimo.yaml
```

### Buscar Mais Reviews

O scraper rola automaticamente e coleta todos os reviews visíveis. Pode demorar alguns minutos dependendo da quantidade.

## Opções Úteis

### Buscar Apenas Reviews Novos

```bash
python start.py --url "SUA_URL" --headless --sort newest --stop-on-match
```

### Baixar Imagens dos Reviews

```bash
python start.py \
  --url "SUA_URL" \
  --headless \
  --download-images true \
  --image-dir "../../cache/review_images" \
  --json-path "../../cache/google_reviews_scraped.json"
```

### Ver Ajuda Completa

```bash
python start.py --help
```

## Onde os Reviews São Salvos

- **JSON**: `sitemimo/public_html/cache/google_reviews_scraped.json`
- **Imagens**: `sitemimo/public_html/cache/review_images/` (se baixar imagens)

## Formato dos Reviews

O scraper salva reviews no formato:
```json
{
  "author": "Nome do Cliente",
  "rating": 5,
  "text": "Texto do review...",
  "profile_photo": "URL da foto",
  "has_photo": true,
  "text_length": 150,
  "time": 1234567890
}
```

## Dicas

1. **Primeira vez**: Execute sem `--headless` para ver o que está acontecendo
2. **Produção**: Use `--headless` para rodar em background
3. **Mais reviews**: O scraper rola automaticamente, mas pode demorar
4. **Atualizar**: Execute periodicamente (semanalmente) para pegar reviews novos

## Troubleshooting

### Chrome não encontrado
```bash
# Mac
brew install --cask google-chrome

# Linux
sudo apt-get install google-chrome-stable
```

### Erro de dependências
```bash
pip install --upgrade -r requirements.txt
```

### Scraper muito lento
- Use `--headless` (mais rápido)
- Reduza `--download-threads` se baixar muitas imagens

