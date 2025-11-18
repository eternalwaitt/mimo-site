'use client'

import { motion } from 'framer-motion'
import { ServiceCard } from '@/components/ui/service-card'
import { SERVICES } from '@/lib/constants'

/**
 * serviços grid - cards grandes com foto dominante.
 * 
 * nome BUENO, preço sutil.
 * hover: revela frase curta + "Ver detalhes"
 * link para subpáginas.
 */
export function ServicesGrid() {
  return (
    <section className="py-20 md:py-32 bg-white">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-12"
        >
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
            Nossos Serviços
          </h2>
          <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
            Tudo que você precisa em um só lugar, com processos ágeis e baixa manutenção
          </p>
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
          {SERVICES.map((service, index) => (
            <motion.div
              key={service.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
            >
              <ServiceCard service={service} />
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  )
}

