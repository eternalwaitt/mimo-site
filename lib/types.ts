/**
 * tipos typescript para o site mimo.
 * 
 * definições de dados para serviços, celebridades e vagas.
 */

export type Service = {
  id: string
  slug: string
  title: string
  description: string
  shortDescription?: string
  price: string
  image: string
  imageAlt: string
  benefits: Array<string>
  procedures?: Array<{
    name: string
    image: string
    imageAlt: string
  }>
  portfolio?: Array<{
    image: string
    imageAlt: string
    context?: string
  }>
}

export type Celebrity = {
  id: string
  name: string
  image: string
  imageAlt: string
  service: string
  instagram?: string
  reelUrl?: string
  reelThumbnail?: string // caminho local para thumbnail (ex: /images/reels/DBACXKPOvd0.webp)
  quote?: string
}

export type JobOpening = {
  id: string
  slug: string
  title: string
  area: string
  description: string
  fullDescription?: string
  requirements: Array<string>
  responsibilities?: Array<string>
  benefits?: Array<string>
  contactMethod: 'whatsapp' | 'email'
}

