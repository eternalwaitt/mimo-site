# Como Limpar a Base de Reviews

## O que o script faz

O script `limpar-reviews.php` remove permanentemente da base de dados:

1. **Reviews que mencionam COVID/pandemia** - Remove referências a COVID, quarentena, máscaras, etc.
2. **Reviews com nota baixa** - Remove reviews com menos de 4 estrelas
3. **Reviews sem texto** - Remove reviews vazios ou sem conteúdo
4. **Reviews com texto muito curto** - Remove reviews com menos de 10 caracteres

## Como usar

```bash
cd sitemimo/public_html
php scripts/limpar-reviews.php
```

## O que acontece

1. O script lê o arquivo `cache/google_reviews_scraped.json`
2. Filtra e remove os reviews indesejados
3. Cria um backup automático do arquivo original (com timestamp)
4. Salva o arquivo limpo

## Exemplo de saída

```
📖 Lendo arquivo de reviews...
📊 Total de reviews inicial: 473

🧹 Limpeza concluída!

📊 Estatísticas:
   - Reviews removidos por COVID: 10
   - Reviews removidos por nota baixa (< 4): 78
   - Reviews removidos sem texto: 188
   - Reviews removidos texto muito curto (< 10 chars): 5
   - Total removido: 281
   - Total restante: 192

💾 Criando backup: google_reviews_scraped.json.backup.2025-11-15_004532
💾 Salvando arquivo limpo...
✅ Concluído! Arquivo limpo salvo.
```

## Restaurar backup

Se precisar restaurar o arquivo original:

```bash
cd sitemimo/public_html/cache
cp google_reviews_scraped.json.backup.2025-11-15_004532 google_reviews_scraped.json
```

## Quando executar

Execute o script sempre que:
- O scraper coletar novos reviews
- Quiser limpar a base de dados
- Precisar remover reviews antigos ou indesejados

## Nota

O script cria um backup automático antes de modificar o arquivo, então você sempre pode restaurar se necessário.

