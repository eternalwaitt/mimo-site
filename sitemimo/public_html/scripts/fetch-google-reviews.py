#!/usr/bin/env python3
"""
Script para buscar reviews do Google Maps usando scraper
Roda periodicamente (ex: 1x por semana) e salva em JSON para o PHP usar

Uso:
    python3 fetch-google-reviews.py --place-url "https://maps.app.goo.gl/..." --output ../cache/google_reviews_scraped.json

Requer:
    pip install selenium undetected-chromedriver
"""

import json
import argparse
import sys
from pathlib import Path
from datetime import datetime

# Nota: Este script requer o google-reviews-scraper-pro clonado
# Clone com: git clone https://github.com/georgekhananaev/google-reviews-scraper-pro.git temp-scraper
# E instale dependências: pip install --break-system-packages -r temp-scraper/requirements.txt

# Adicionar o diretório do scraper ao path
scraper_dir = Path(__file__).parent / 'temp-scraper'
if not scraper_dir.exists():
    print("❌ Erro: google-reviews-scraper-pro não encontrado")
    print("Clone o repositório:")
    print("  cd scripts")
    print("  git clone https://github.com/georgekhananaev/google-reviews-scraper-pro.git temp-scraper")
    print("  pip install --break-system-packages -r temp-scraper/requirements.txt")
    sys.exit(1)

sys.path.insert(0, str(scraper_dir))

try:
    from modules.scraper import GoogleReviewsScraper
except ImportError as e:
    print(f"❌ Erro ao importar GoogleReviewsScraper: {e}")
    print("Instale as dependências:")
    print("  pip install --break-system-packages -r temp-scraper/requirements.txt")
    sys.exit(1)


def filter_and_sort_reviews(reviews, min_rating=4, max_results=50):
    """
    Filtra e ordena reviews por qualidade
    
    Prioridade:
    1. Reviews com foto de perfil
    2. Rating (5 estrelas antes de 4)
    3. Comprimento do texto (mais longos primeiro)
    """
    filtered = []
    
    for review in reviews:
        rating = review.get('rating', 0)
        text = review.get('text', '')
        profile_photo = review.get('profile_photo') or review.get('author_photo')
        
        # Filtrar APENAS 4 e 5 estrelas com texto > 20 chars
        if rating < 4 or rating > 5:
            continue
            
        if len(text) < 20:
            continue
        
        # Calcular score de qualidade
        text_length = len(text)
        word_count = len(text.split())
        
        # Score base: comprimento + palavras + rating
        quality_score = (text_length * 0.4) + (word_count * 2) + (rating * 10)
        
        # Bonus para reviews com foto
        has_photo = bool(profile_photo)
        if has_photo:
            quality_score += 50  # Bonus significativo para reviews com foto
        
        filtered.append({
            'author': review.get('author', 'Anônimo'),
            'rating': rating,
            'text': text,
            'time': review.get('time', int(datetime.now().timestamp())),
            'profile_photo': profile_photo,
            'quality_score': quality_score,
            'text_length': text_length,
            'word_count': word_count,
            'has_photo': has_photo,
            'source': 'Google (Scraped)'
        })
    
    # Ordenar por:
    # 1. Tem foto (True antes de False)
    # 2. Rating (5 antes de 4)
    # 3. Comprimento do texto (mais longos primeiro)
    # 4. Mais antigos primeiro (para ter variedade temporal)
    filtered.sort(key=lambda x: (
        not x['has_photo'],  # False (tem foto) vem antes de True (não tem)
        -x['rating'],         # 5 antes de 4
        -x['text_length'],   # Mais longos primeiro
        x['time']            # Mais antigos primeiro (menor timestamp = mais antigo)
    ))
    
    return filtered[:max_results]


def main():
    parser = argparse.ArgumentParser(description='Busca reviews do Google Maps e salva em JSON')
    parser.add_argument('--place-url', required=True, help='URL do Google Maps do lugar')
    parser.add_argument('--output', required=True, help='Caminho do arquivo JSON de saída')
    parser.add_argument('--min-rating', type=int, default=4, help='Rating mínimo (default: 4)')
    parser.add_argument('--max-results', type=int, default=100, help='Máximo de resultados para buscar (default: 100)')
    parser.add_argument('--headless', action='store_true', help='Rodar em modo headless')
    parser.add_argument('--sort', default='newest', choices=['newest', 'relevance', 'rating'], 
                       help='Ordenação inicial (default: newest)')
    
    args = parser.parse_args()
    
    print(f"🔍 Buscando reviews de: {args.place_url}")
    print(f"📊 Filtros: min_rating={args.min_rating}, max_results={args.max_results}")
    
    try:
        # Configurar scraper (o scraper usa um dict de config)
        # O scraper salva automaticamente em JSON quando backup_to_json=True
        output_dir = Path(args.output).parent
        output_dir.mkdir(parents=True, exist_ok=True)
        
        config = {
            'url': args.place_url,
            'headless': args.headless,
            'sort_by': args.sort,
            'use_mongodb': False,
            'backup_to_json': True,
            'json_file': str(output_dir / 'google_reviews_raw.json'),  # Arquivo temporário
            'convert_dates': True,
            'download_images': False,
            'overwrite_existing': True,
        }
        
        # Inicializar scraper
        scraper = GoogleReviewsScraper(config)
        
        # Buscar reviews
        print("⏳ Scraping reviews... (isso pode levar alguns minutos)")
        scraper.scrape()
        
        # Ler reviews do arquivo JSON gerado pelo scraper
        # O scraper pode salvar em diferentes locais, vamos verificar
        json_file = Path(config.get('json_file', output_dir / 'google_reviews_raw.json'))
        json_path_alt = Path(config.get('json_path', output_dir / 'google_reviews.json'))
        
        # Verificar qual arquivo foi criado
        if not json_file.exists() and json_path_alt.exists():
            json_file = json_path_alt
        elif not json_file.exists():
            # Verificar se há algum arquivo JSON no diretório de output
            json_files = list(output_dir.glob('google_reviews*.json'))
            if json_files:
                json_file = json_files[0]
                print(f"📁 Usando arquivo encontrado: {json_file}")
            else:
                print("⚠️  Arquivo JSON não encontrado após scraping")
                print("   O scraper pode ter falhado ou a estrutura do Google Maps mudou")
                print("   Tente rodar sem --headless para ver o que está acontecendo")
                sys.exit(1)
        
        with open(json_file, 'r', encoding='utf-8') as f:
            scraped_data = json.load(f)
        
        # O scraper salva em formato específico, precisamos extrair os reviews
        # O formato pode ser um dict com 'reviews' ou uma lista direta
        if isinstance(scraped_data, dict):
            # Tentar diferentes chaves possíveis
            reviews = scraped_data.get('reviews', scraped_data.get('data', []))
            if not reviews and len(scraped_data) > 0:
                # Pode ser que os reviews estejam no primeiro item
                first_key = list(scraped_data.keys())[0]
                first_item = scraped_data[first_key]
                if isinstance(first_item, dict) and 'reviews' in first_item:
                    reviews = first_item['reviews']
                elif isinstance(first_item, list):
                    reviews = first_item
        elif isinstance(scraped_data, list):
            reviews = scraped_data
        else:
            reviews = []
        
        # Converter formato do scraper para nosso formato
        converted_reviews = []
        for review in reviews:
            # O scraper pode usar diferentes nomes de campos
            converted = {
                'author': review.get('author_name') or review.get('author') or review.get('name', 'Anônimo'),
                'rating': int(review.get('rating', 0)),
                'text': review.get('text') or review.get('review_text') or review.get('comment', ''),
                'time': review.get('time') or review.get('timestamp') or review.get('date', 0),
                'profile_photo': review.get('profile_photo') or review.get('author_photo') or review.get('photo', None),
            }
            
            # Converter time se for string
            if isinstance(converted['time'], str):
                try:
                    converted['time'] = int(datetime.fromisoformat(converted['time'].replace('Z', '+00:00')).timestamp())
                except:
                    converted['time'] = int(datetime.now().timestamp())
            
            converted_reviews.append(converted)
        
        reviews = converted_reviews
        
        if not reviews:
            print("⚠️  Nenhum review encontrado")
            sys.exit(1)
        
        print(f"✅ {len(reviews)} reviews encontrados")
        
        # Filtrar e ordenar
        filtered = filter_and_sort_reviews(reviews, args.min_rating, args.max_results)
        print(f"✅ {len(filtered)} reviews após filtro (4-5 estrelas, texto > 20 chars)")
        
        # Salvar em JSON
        output_path = Path(args.output)
        output_path.parent.mkdir(parents=True, exist_ok=True)
        
        with open(output_path, 'w', encoding='utf-8') as f:
            json.dump(filtered, f, ensure_ascii=False, indent=2)
        
        print(f"💾 Reviews salvos em: {output_path}")
        print(f"📈 Top 5 reviews:")
        for i, review in enumerate(filtered[:5], 1):
            print(f"   {i}. {review['author']} - {review['rating']}⭐ - {review['text_length']} chars")
        
    except Exception as e:
        print(f"❌ Erro: {e}")
        import traceback
        traceback.print_exc()
        sys.exit(1)


if __name__ == '__main__':
    main()

