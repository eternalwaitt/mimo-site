'use client'

import { motion } from 'framer-motion'
import { CelebrityCard } from '@/components/ui/celebrity-card'
import { CELEBRITIES } from '@/lib/constants'

/**
 * #MomentoMIMO - grid/carrossel celebridades.
 * 
 * foto + nome + serviço.
 * estilo editorial (não testemunhal genérico).
 */
export function MomentoMimo() {
  return (
    <section className="py-20 md:py-32 bg-mimo-neutral-light">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-12"
        >
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
            #MomentoMIMO
          </h2>
          <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
            Influencers e pessoas incríveis que confiam na Mimo para seus momentos especiais
          </p>
        </motion.div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          {CELEBRITIES.map((celebrity, index) => (
            <motion.div
              key={celebrity.id}
              initial={{ opacity: 0, scale: 0.9 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
            >
              <CelebrityCard celebrity={celebrity} />
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  )
}

