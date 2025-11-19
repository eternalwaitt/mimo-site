'use client'

import { motion } from 'framer-motion'
import { OrganicShape } from '@/components/ui/organic-shape'
import { HOME_COPY } from '@/lib/constants'

/**
 * seção de economia de tempo visualizada.
 * 
 * - visual editorial (não tabela/infográfico corporativo)
 * - ícones minimalistas + números grandes
 * - formas orgânicas de fundo (blobs)
 * - cálculo dinâmico: "Unhas 40min + Lashes 30min + Cabelo 1h = Xh totais"
 * - punchline: "Economize Xh/mês"
 * - inspiração: Notion/Canva moderno
 * - animações suaves com framer-motion
 * 
 * @returns {JSX.Element} seção de economia de tempo
 */
export function TimeEconomy() {
  const { unhas, lashes, cabelo } = HOME_COPY.timeEconomy.calculation
  const totalMinutes = unhas + lashes + cabelo
  const totalHours = Math.floor(totalMinutes / 60)
  const hoursPerMonth = totalHours * 4 // assumindo 4 visitas por mês

  return (
    <section className="relative py-20 md:py-32 bg-mimo-neutral-light overflow-hidden">
      {/* Organic Shapes Background */}
      <OrganicShape
        variant="blob"
        className="absolute -top-20 -right-20 w-96 h-96 bg-mimo-gold/20 opacity-50"
      />
      <OrganicShape
        variant="circle"
        className="absolute -bottom-20 -left-20 w-80 h-80 bg-mimo-brown/10 opacity-30"
      />

      <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-12"
        >
          <h2 className="font-bueno text-4xl md:text-5xl font-bold text-mimo-brown mb-4">
            {HOME_COPY.timeEconomy.title}
          </h2>
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 max-w-5xl mx-auto">
          {/* Unhas */}
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="text-center"
          >
            <div className="mb-4">
              <div className="w-16 h-16 mx-auto bg-mimo-gold rounded-full flex items-center justify-center mb-4">
                <span className="text-2xl">💅</span>
              </div>
              <div className="font-bueno text-5xl font-bold text-mimo-brown mb-2">
                {unhas}min
              </div>
              <p className="font-satoshi text-mimo-blue">Unhas</p>
            </div>
          </motion.div>

          {/* Lashes */}
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-center"
          >
            <div className="mb-4">
              <div className="w-16 h-16 mx-auto bg-mimo-gold rounded-full flex items-center justify-center mb-4">
                <span className="text-2xl">👁️</span>
              </div>
              <div className="font-bueno text-5xl font-bold text-mimo-brown mb-2">
                {lashes}min
              </div>
              <p className="font-satoshi text-mimo-blue">Cílios</p>
            </div>
          </motion.div>

          {/* Cabelo */}
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.3 }}
            className="text-center"
          >
            <div className="mb-4">
              <div className="w-16 h-16 mx-auto bg-mimo-gold rounded-full flex items-center justify-center mb-4">
                <span className="text-2xl">✂️</span>
              </div>
              <div className="font-bueno text-5xl font-bold text-mimo-brown mb-2">
                {cabelo}min
              </div>
              <p className="font-satoshi text-mimo-blue">Cabelo</p>
            </div>
          </motion.div>
        </div>

        {/* Total */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6, delay: 0.4 }}
          className="text-center mt-12"
        >
          <div className="inline-block bg-white rounded-2xl p-8 shadow-lg">
            <p className="font-satoshi text-mimo-blue mb-2">
              Total por visita: <span className="font-bueno font-bold text-mimo-brown">{totalMinutes}min</span>
            </p>
            <p className="font-bueno text-3xl md:text-4xl font-bold text-mimo-brown">
              {HOME_COPY.timeEconomy.punchline.replace('{hours}', hoursPerMonth.toString())}
            </p>
          </div>
        </motion.div>
      </div>
    </section>
  )
}

