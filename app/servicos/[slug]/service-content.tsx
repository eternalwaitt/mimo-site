'use client'

import { motion } from 'framer-motion'
import { Button } from '@/components/ui/button'
import { ImageWithFallback } from '@/components/ui/image-with-fallback'
import type { Service } from '@/lib/types'

type ServiceContentProps = {
  service: Service
  whatsappUrl: string
}

/**
 * conteúdo da página de serviço (client component).
 * 
 * - hero com imagem de fundo e overlay
 * - descrição principal destacada
 * - grid de procedimentos com imagens (se disponível)
 * - seção de benefícios em cards visuais
 * - portfolio em grid (se disponível)
 * - CTA final com botões de agendamento
 * - animações com framer-motion
 */
export function ServiceContent({ service, whatsappUrl }: ServiceContentProps) {
  return (
    <main className="pt-20">
      {/* Hero */}
      <section className="relative h-[70vh] min-h-[500px] flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0">
          <ImageWithFallback
            src={service.image}
            alt={service.imageAlt}
            fill
            sizes="100vw"
            className="object-cover"
            priority
          />
        </div>
        <div className="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-black/40 z-10" />
        <div className="relative z-20 text-center text-white container mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
          >
            <h1 className="font-bueno text-5xl md:text-6xl lg:text-7xl font-bold mb-4 drop-shadow-lg">
              {service.title}
            </h1>
            <p className="font-satoshi text-xl md:text-2xl lg:text-3xl mb-6 drop-shadow-md">
              {service.price}
            </p>
            <p className="font-satoshi text-lg md:text-xl text-white/90 max-w-2xl mx-auto drop-shadow">
              {service.shortDescription || service.description}
            </p>
          </motion.div>
        </div>
      </section>

      {/* Descrição Principal */}
      <section className="py-16 md:py-20 bg-mimo-neutral-light">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="text-center"
          >
            <p className="font-satoshi text-xl md:text-2xl text-mimo-blue leading-relaxed">
              {service.description}
            </p>
          </motion.div>
        </div>
      </section>

      {/* Procedimentos */}
      {service.procedures && service.procedures.length > 0 && (
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
              className="text-center mb-12"
            >
              <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
                O que oferecemos
              </h2>
              <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
                Cada detalhe pensado para você se sentir incrível
              </p>
            </motion.div>
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
              {service.procedures.map((procedure, index) => (
                <motion.div
                  key={index}
                  initial={{ opacity: 0, scale: 0.9 }}
                  whileInView={{ opacity: 1, scale: 1 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: index * 0.1 }}
                  className="group relative aspect-square rounded-xl overflow-hidden bg-mimo-neutral-light shadow-md hover:shadow-xl transition-all duration-300"
                >
                  <div className="absolute inset-0">
                    <ImageWithFallback
                      src={procedure.image}
                      alt={procedure.imageAlt}
                      fill
                      sizes="(max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
                      className="object-cover transition-transform duration-500 group-hover:scale-110"
                    />
                  </div>
                  <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent" />
                  <div className="absolute bottom-0 left-0 right-0 p-4 text-white">
                    <p className="font-satoshi font-semibold text-sm md:text-base drop-shadow-lg">
                      {procedure.name}
                    </p>
                  </div>
                </motion.div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Benefícios */}
      <section className="py-16 md:py-24 bg-mimo-neutral-light">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="text-center mb-12"
          >
            <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
              O que faz a diferença
            </h2>
          </motion.div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {service.benefits.map((benefit, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: index * 0.1 }}
                className="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300"
              >
                <div className="flex items-start gap-4">
                  <div className="flex-shrink-0 w-8 h-8 rounded-full bg-mimo-gold/20 flex items-center justify-center">
                    <span className="text-mimo-gold text-xl">✓</span>
                  </div>
                  <p className="font-satoshi text-lg text-mimo-blue flex-1 pt-1">
                    {benefit}
                  </p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Portfolio */}
      {service.portfolio && service.portfolio.length > 0 && (
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
              className="text-center mb-12"
            >
              <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
                Alguns dos nossos trabalhos
              </h2>
              <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
                Resultados reais de clientes que confiam na gente
              </p>
            </motion.div>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {service.portfolio.map((item, index) => (
                <motion.div
                  key={index}
                  initial={{ opacity: 0, scale: 0.9 }}
                  whileInView={{ opacity: 1, scale: 1 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: index * 0.1 }}
                  className="group relative aspect-square rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300"
                >
                  <div className="absolute inset-0">
                    <ImageWithFallback
                      src={item.image}
                      alt={item.imageAlt}
                      fill
                      sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                      className="object-cover transition-transform duration-500 group-hover:scale-110"
                    />
                  </div>
                  {item.context && (
                    <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                      <div className="p-6 text-white w-full">
                        <p className="font-satoshi text-sm md:text-base">{item.context}</p>
                      </div>
                    </div>
                  )}
                </motion.div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* CTA Final */}
      <section className="py-16 md:py-24 bg-mimo-brown">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="max-w-3xl mx-auto"
          >
            <h2 className="font-bueno text-4xl md:text-5xl font-bold text-white mb-6">
              Pronta para se sentir completa?
            </h2>
            <p className="font-satoshi text-xl text-white/90 mb-8">
              Vem fazer parte da nossa história de cuidado e beleza
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button
                variant="primary"
                href={whatsappUrl}
                external
                className="bg-white text-mimo-brown hover:bg-mimo-neutral-light text-xl px-10 py-5"
              >
                Agendar {service.title}
              </Button>
              <Button
                variant="ghost"
                href="/servicos"
                className="border-2 border-white text-white hover:bg-white/10 text-xl px-10 py-5"
              >
                Ver outros serviços
              </Button>
            </div>
          </motion.div>
        </div>
      </section>
    </main>
  )
}

