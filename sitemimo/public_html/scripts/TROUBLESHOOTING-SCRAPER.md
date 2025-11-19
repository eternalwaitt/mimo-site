# Troubleshooting do Scraper

## Erro: "no such window: target window already closed"

Este erro geralmente acontece quando o Chrome fecha inesperadamente durante a execução. Aqui estão as soluções:

### Solução 1: Executar sem Headless (Recomendado para Debug)

Edite `config-mimo.yaml` e mude:
```yaml
headless: false
```

Isso permite ver o que está acontecendo no Chrome e identificar o problema.

### Solução 2: Atualizar Chrome e ChromeDriver

```bash
# Verificar versão do Chrome
/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --version

# Atualizar ChromeDriver (o undetected-chromedriver faz isso automaticamente)
# Mas você pode tentar:
pip install --upgrade undetected-chromedriver selenium
```

### Solução 3: Executar com Menos Recursos

Se o problema for memória, tente reduzir o número de threads de download:

Edite `config-mimo.yaml`:
```yaml
download_images: false  # Desabilitar download de imagens temporariamente
download_threads: 1     # Reduzir threads
```

### Solução 4: Verificar Permissões no Mac

O Chrome pode precisar de permissões no macOS:

1. Vá em **Configurações do Sistema** > **Privacidade e Segurança**
2. Verifique se o Terminal/Python tem permissão para controlar o computador
3. Se necessário, adicione manualmente

### Solução 5: Executar com Timeout Maior

Se o problema for que o Chrome está demorando muito para carregar, você pode aumentar o timeout editando `modules/scraper.py` (linha ~1118):

```python
wait = WebDriverWait(driver, 40)  # Aumentar de 20 para 40
```

### Solução 6: Usar Chrome Estável ao Invés de Beta/Dev

Se você está usando uma versão beta ou dev do Chrome, tente usar a versão estável:

```bash
# Verificar qual Chrome está sendo usado
which google-chrome
# ou
which chromium

# Se necessário, instalar Chrome estável
brew install --cask google-chrome
```

### Solução 7: Executar em Modo Verbose

Para ver mais detalhes do que está acontecendo:

```bash
cd scripts/temp-scraper
python3 start.py --config config-mimo.yaml 2>&1 | tee scraper.log
```

Isso salva todos os logs em `scraper.log` para análise.

## Outros Erros Comuns

### "ChromeDriver version mismatch"

```bash
pip install --upgrade undetected-chromedriver
```

### "Permission denied"

```bash
chmod +x scripts/temp-scraper/start.py
```

### "Module not found"

```bash
cd scripts/temp-scraper
pip3 install -r requirements.txt
```

## Dicas Gerais

1. **Primeira execução**: Sempre execute sem `headless` primeiro para ver o que acontece
2. **Memória**: Se você tem pouca RAM, desabilite `download_images`
3. **Rede lenta**: Aumente os timeouts no código
4. **Muitos reviews**: O scraper pode demorar vários minutos para coletar todos

## Se Nada Funcionar

1. Tente executar o scraper em uma máquina virtual ou container Docker
2. Use a API do Google Places (já implementada no site) como alternativa
3. Considere usar um serviço de scraping terceirizado

