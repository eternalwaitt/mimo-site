'use client'

import { useState } from 'react'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { SERVICES } from '@/lib/constants'

/**
 * página galeria.
 * 
 * masonry grid (CSS Grid).
 * filtros por serviço/tipo.
 * lightbox elegante.
 * lazy load images.
 */
export default function GaleriaPage() {
  const [selectedFilter, setSelectedFilter] = useState<string>('todos')
  const [selectedImage, setSelectedImage] = useState<string | null>(null)

  const filters = [
    { id: 'todos', label: 'Todos' },
    ...SERVICES.map((service) => ({ id: service.slug, label: service.title })),
  ]

  // Galeria de imagens - combinando imagens locais com Unsplash
  const galleryImages = [
    // Imagens locais
    { src: '/images/servicos/salao/categoria-salao.webp', alt: 'Salão Mimo', category: 'salao' },
    { src: '/images/servicos/esmalteria/categoria-esmalteria.webp', alt: 'Esmalteria Mimo', category: 'esmalteria' },
    { src: '/images/servicos/cilios/categoria-cilios.webp', alt: 'Cílios Mimo', category: 'cilios' },
    
    // Salão - Unsplash (URLs validadas)
    { src: 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80&auto=format&fit=crop', alt: 'Corte de cabelo profissional', category: 'salao' },
    { src: 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80&auto=format&fit=crop', alt: 'Coloração de cabelo', category: 'salao' },
    { src: 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80&auto=format&fit=crop', alt: 'Estilo de cabelo', category: 'salao' },
    { src: 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80&auto=format&fit=crop', alt: 'Tratamento capilar', category: 'salao' },
    
    // Esmalteria - Unsplash (URLs validadas)
    { src: 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80&auto=format&fit=crop', alt: 'Unhas decoradas', category: 'esmalteria' },
    { src: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&q=80&auto=format&fit=crop', alt: 'Manicure profissional', category: 'esmalteria' },
    { src: 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80&auto=format&fit=crop&crop=center', alt: 'Esmaltação em gel', category: 'esmalteria' },
    { src: 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=800&q=80&auto=format&fit=crop&crop=top', alt: 'Design de unhas', category: 'esmalteria' },
    
    // Cílios - Unsplash (URLs validadas)
    { src: 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=800&q=80&auto=format&fit=crop', alt: 'Cílios postiços', category: 'cilios' },
    { src: 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=800&q=80&auto=format&fit=crop', alt: 'Design de sobrancelhas', category: 'cilios' },
    { src: 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=800&q=80&auto=format&fit=crop&crop=face', alt: 'Extensão de cílios', category: 'cilios' },
    
    // Micropigmentação - Unsplash (URLs validadas)
    { src: 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=800&q=80&auto=format&fit=crop', alt: 'Microblading de sobrancelhas', category: 'micropigmentacao' },
    { src: 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=800&q=80&auto=format&fit=crop&crop=face', alt: 'Micropigmentação', category: 'micropigmentacao' },
    
    // Estética Facial - Unsplash (URLs validadas)
    { src: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&q=80&auto=format&fit=crop', alt: 'Tratamento facial', category: 'estetica-facial' },
    { src: 'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=800&q=80&auto=format&fit=crop', alt: 'Skincare profissional', category: 'estetica-facial' },
    { src: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&q=80&auto=format&fit=crop&crop=face', alt: 'Cuidados com a pele', category: 'estetica-facial' },
    
    // Estética Corporal - Unsplash (URLs validadas)
    { src: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=800&q=80&auto=format&fit=crop', alt: 'Massagem relaxante', category: 'estetica-corporal' },
    { src: 'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=800&q=80&auto=format&fit=crop', alt: 'Tratamento corporal', category: 'estetica-corporal' },
  ]

  const filteredImages =
    selectedFilter === 'todos'
      ? galleryImages
      : galleryImages.filter((img) => img.category === selectedFilter)

  return (
    <>
      <Header />
      <main className="pt-20">
        {/* Hero */}
        <section className="relative h-[40vh] min-h-[250px] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-4">
              Galeria
            </h1>
            <p className="font-satoshi text-xl text-mimo-blue max-w-2xl mx-auto">
              Veja alguns dos nossos trabalhos
            </p>
          </div>
        </section>

        {/* Filtros */}
        <section className="py-8 bg-white border-b border-mimo-neutral-medium">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-wrap gap-4 justify-center">
              {filters.map((filter) => (
                <button
                  key={filter.id}
                  onClick={() => setSelectedFilter(filter.id)}
                  className={`px-6 py-2 rounded-full font-satoshi transition-all ${
                    selectedFilter === filter.id
                      ? 'bg-mimo-brown text-white'
                      : 'bg-mimo-neutral-light text-mimo-blue hover:bg-mimo-neutral-medium'
                  }`}
                >
                  {filter.label}
                </button>
              ))}
            </div>
          </div>
        </section>

        {/* Masonry Grid */}
        <section className="py-12 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <div className="columns-1 md:columns-2 lg:columns-3 gap-4">
              {filteredImages.map((image, index) => (
                <div
                  key={index}
                  className="mb-4 break-inside-avoid cursor-pointer"
                  onClick={() => setSelectedImage(image.src)}
                >
                  <div className="relative rounded-lg overflow-hidden group">
                    <ImageWithFallback
                      src={image.src}
                      alt={image.alt}
                      width={400}
                      height={600}
                      className="w-full h-auto transition-transform duration-300 group-hover:scale-105"
                      sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                    />
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Lightbox */}
        {selectedImage && (
          <div
            className="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
            onClick={() => setSelectedImage(null)}
          >
            <button
              className="absolute top-4 right-4 text-white text-4xl"
              onClick={() => setSelectedImage(null)}
            >
              ×
            </button>
            <div className="max-w-5xl max-h-[90vh]">
              <ImageWithFallback
                src={selectedImage}
                alt="Gallery image"
                width={1200}
                height={800}
                className="w-full h-auto rounded-lg"
                sizes="90vw"
              />
            </div>
          </div>
        )}
      </main>
      <Footer />
    </>
  )
}

