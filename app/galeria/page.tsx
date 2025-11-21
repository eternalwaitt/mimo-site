'use client'

import { useState } from 'react'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import { SERVICES } from '@/lib/constants/index'

/**
 * página galeria.
 * 
 * - masonry grid usando CSS columns (layout responsivo)
 * - filtros por serviço/tipo com estado local
 * - lightbox modal para visualização ampliada
 * - imagens 100% locais (seguindo estratégia documentada)
 * - lazy loading automático via next/image
 */
export default function GaleriaPage() {
  const [selectedFilter, setSelectedFilter] = useState<string>('todos')
  const [selectedImage, setSelectedImage] = useState<string | null>(null)

  const filters = [
    { id: 'todos', label: 'Todos' },
    ...SERVICES.map((service) => ({ id: service.slug, label: service.title })),
  ]

  // Galeria de imagens - 100% local (seguindo estratégia documentada em docs/IMAGE-STRATEGY.md)
  const galleryImages = [
    // Imagens de categoria (já existentes)
    { src: '/images/servicos/salao/categoria-salao.webp', alt: 'Salão Mimo', category: 'salao' },
    { src: '/images/servicos/esmalteria/categoria-esmalteria.webp', alt: 'Esmalteria Mimo', category: 'esmalteria' },
    { src: '/images/servicos/cilios/categoria-cilios.webp', alt: 'Cílios Mimo', category: 'cilios' },
    
    // Salão
    { src: '/images/galeria/salao/salao-corte-1.webp', alt: 'Corte de cabelo profissional', category: 'salao' },
    { src: '/images/galeria/salao/salao-coloracao-1.webp', alt: 'Coloração de cabelo moderna', category: 'salao' },
    { src: '/images/galeria/salao/salao-estilo-1.webp', alt: 'Estilo de cabelo profissional', category: 'salao' },
    
    // Esmalteria
    { src: '/images/galeria/esmalteria/esmalteria-manicure-1.webp', alt: 'Manicure profissional', category: 'esmalteria' },
    { src: '/images/galeria/esmalteria/esmalteria-gel-1.webp', alt: 'Esmaltação em gel', category: 'esmalteria' },
    { src: '/images/galeria/esmalteria/esmalteria-design-1.webp', alt: 'Design de unhas artístico', category: 'esmalteria' },
    
    // Cílios
    { src: '/images/galeria/cilios/cilios-extensao-1.webp', alt: 'Extensão de cílios', category: 'cilios' },
    { src: '/images/galeria/cilios/cilios-design-1.webp', alt: 'Design de cílios', category: 'cilios' },
    
    // Micropigmentação
    { src: '/images/galeria/micropigmentacao/micro-sobrancelhas-1.webp', alt: 'Microblading de sobrancelhas', category: 'micropigmentacao' },
    { src: '/images/galeria/micropigmentacao/micro-pigmentacao-1.webp', alt: 'Micropigmentação profissional', category: 'micropigmentacao' },
    
    // Estética Facial
    { src: '/images/galeria/estetica-facial/facial-tratamento-1.webp', alt: 'Tratamento facial profissional', category: 'estetica-facial' },
    { src: '/images/galeria/estetica-facial/facial-skincare-1.webp', alt: 'Skincare profissional', category: 'estetica-facial' },
    { src: '/images/galeria/estetica-facial/facial-cuidados-1.webp', alt: 'Cuidados com a pele', category: 'estetica-facial' },
    
    // Estética Corporal
    { src: '/images/galeria/estetica-corporal/corporal-massagem-1.webp', alt: 'Massagem relaxante', category: 'estetica-corporal' },
    { src: '/images/galeria/estetica-corporal/corporal-tratamento-1.webp', alt: 'Tratamento corporal', category: 'estetica-corporal' },
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
                      : 'bg-mimo-neutral-light text-mimo-brown hover:bg-mimo-neutral-medium'
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

