# Arquivos Removidos

**Data**: 2025-01-15  
**Versão**: 2.3.0

## Arquivos Removidos

### `scripts/fetch-google-reviews.py`
**Motivo**: Substituído pelo scraper `temp-scraper/` que é mais completo e configurável.

**Alternativa**: Use `scripts/temp-scraper/start.py --config config-mimo.yaml`

**Documentação**: Ver `scripts/COMO-USAR-SCRAPER.md`

---

## Arquivos Obsoletos (Podem ser removidos)

### `scripts/google_reviews.json` e `scripts/google_reviews.ids`
**Status**: Arquivos vazios/obsoletos do scraper antigo

**Motivo**: O scraper atual (`temp-scraper`) salva em `cache/google_reviews_scraped.json` e `cache/google_reviews_seen.ids`

**Ação**: Podem ser removidos se não forem mais necessários

---

## Notas

- Todos os arquivos removidos foram substituídos por alternativas melhores
- Documentação atualizada para refletir as mudanças
- Nenhuma funcionalidade foi perdida

