import { MetadataRoute } from 'next'
import { SERVICES } from '@/lib/constants/index'

/**
 * sitemap gerado dinamicamente.
 * 
 * - inclui todas as páginas estáticas (home, serviços, sobre, etc)
 * - gera entradas dinâmicas para cada serviço
 * - prioridades e frequências configuradas para SEO
 * - atualizado automaticamente quando serviços mudam
 */
export default function sitemap(): MetadataRoute.Sitemap {
  const baseUrl = 'https://minhamimo.com.br'

  const staticPages: MetadataRoute.Sitemap = [
    {
      url: baseUrl,
      lastModified: new Date(),
      changeFrequency: 'weekly',
      priority: 1,
    },
    {
      url: `${baseUrl}/servicos`,
      lastModified: new Date(),
      changeFrequency: 'weekly',
      priority: 0.9,
    },
    {
      url: `${baseUrl}/sobre`,
      lastModified: new Date(),
      changeFrequency: 'monthly',
      priority: 0.8,
    },
    {
      url: `${baseUrl}/galeria`,
      lastModified: new Date(),
      changeFrequency: 'weekly',
      priority: 0.8,
    },
    {
      url: `${baseUrl}/trabalhe-aqui`,
      lastModified: new Date(),
      changeFrequency: 'monthly',
      priority: 0.7,
    },
  ]

  const servicePages: MetadataRoute.Sitemap = SERVICES.map((service) => ({
    url: `${baseUrl}/servicos/${service.slug}`,
    lastModified: new Date(),
    changeFrequency: 'monthly',
    priority: 0.8,
  }))

  return [...staticPages, ...servicePages]
}

