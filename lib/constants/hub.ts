/**
 * produtos recomendados no mimo hub.
 * 
 * produtos que a mimo e suas influencers recomendam, com links de afiliados.
 */

import type { Product } from '../types'

/**
 * categorias de produtos disponíveis no hub.
 */
export const PRODUCT_CATEGORIES = [
  'beleza',
  'cuidados',
  'acessorios',
  'tratamentos',
  'cabelo',
  'pele',
] as const

/**
 * produtos recomendados.
 * 
 * exemplo de estrutura - adicionar produtos reais conforme necessário.
 */
export const PRODUCTS: Array<Product> = [
  {
    id: 'produto-1',
    slug: 'shampoo-reparador',
    title: 'Shampoo Reparador',
    shortDescription: 'Para cabelos danificados e ressecados',
    description: 'Shampoo reparador com queratina e óleos nutritivos que restauram a saúde dos fios, ideal para cabelos que passaram por processos químicos.',
    image: '/images/placeholder.svg',
    imageAlt: 'Shampoo reparador para cabelos danificados',
    affiliateUrl: 'https://exemplo.com/produto?ref=mimo',
    category: 'cabelo',
    recommendedBy: 'Mimo',
    brand: 'Marca X',
    price: 'R$ 45,90',
    tags: ['cabelo', 'reparação', 'queratina'],
  },
  {
    id: 'produto-2',
    slug: 'mascara-hidratante',
    title: 'Máscara Hidratante',
    shortDescription: 'Hidratação profunda para todos os tipos de cabelo',
    description: 'Máscara de tratamento com hidratação profunda que nutre e revitaliza os fios, deixando-os macios e com brilho natural.',
    image: '/images/placeholder.svg',
    imageAlt: 'Máscara hidratante para cabelo',
    affiliateUrl: 'https://exemplo.com/produto?ref=mimo',
    category: 'cabelo',
    recommendedBy: 'Mimo',
    brand: 'Marca Y',
    price: 'R$ 65,00',
    tags: ['cabelo', 'hidratação', 'tratamento'],
  },
  {
    id: 'produto-3',
    slug: 'serum-facial',
    title: 'Sérum Facial',
    shortDescription: 'Antioxidante e anti-idade para pele madura',
    description: 'Sérum facial com vitamina C e ácido hialurônico que combate os sinais de envelhecimento e proporciona luminosidade à pele.',
    image: '/images/placeholder.svg',
    imageAlt: 'Sérum facial antioxidante',
    affiliateUrl: 'https://exemplo.com/produto?ref=mimo',
    category: 'pele',
    recommendedBy: 'Influencer A',
    brand: 'Marca Z',
    price: 'R$ 89,90',
    tags: ['pele', 'anti-idade', 'vitamina-c'],
  },
  {
    id: 'produto-4',
    slug: 'escova-profissional',
    title: 'Escova Profissional',
    shortDescription: 'Escova de cerdas naturais para finalização',
    description: 'Escova profissional com cerdas naturais que distribuem os óleos do couro cabeludo, ideal para finalização e modelagem dos fios.',
    image: '/images/placeholder.svg',
    imageAlt: 'Escova profissional de cerdas naturais',
    affiliateUrl: 'https://exemplo.com/produto?ref=mimo',
    category: 'acessorios',
    recommendedBy: 'Mimo',
    brand: 'Marca W',
    price: 'R$ 120,00',
    tags: ['acessorio', 'escova', 'finalização'],
  },
]

