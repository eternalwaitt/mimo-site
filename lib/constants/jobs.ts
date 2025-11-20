/**
 * vagas em aberto e benefícios para trabalhe-aqui.
 */

import type { JobOpening } from '../types'

export const JOB_OPENINGS: Array<JobOpening> = [
  {
    id: 'social-media',
    slug: 'social-media',
    title: 'Social Media',
    area: 'Marketing',
    description: 'Buscamos profissional criativo e estratégico para gerenciar nossas redes sociais e fortalecer a presença digital da Mimo.',
    fullDescription: `A Mimo está em busca de um(a) profissional de Social Media para fazer parte da nossa equipe e fortalecer nossa presença digital. Você será responsável por criar conteúdo autêntico, engajar nossa comunidade e contar a história da Mimo através das redes sociais.

Esta é uma oportunidade única para trabalhar em um ambiente criativo, colaborativo e que valoriza a diversidade e a autenticidade. Na Mimo, acreditamos que cada pessoa tem sua beleza única, e queremos que isso se reflita em tudo que fazemos - inclusive nas nossas redes sociais.`,
    requirements: [
      'Experiência comprovada em gestão de redes sociais (Instagram, Facebook, TikTok)',
      'Conhecimento em criação de conteúdo visual e escrita criativa',
      'Familiaridade com ferramentas de design (Canva, Adobe Creative Suite ou similar)',
      'Capacidade de análise de métricas e resultados',
      'Boa comunicação escrita e verbal',
      'Criatividade e atenção aos detalhes',
      'Conhecimento sobre tendências de beleza e bem-estar',
      'Disponibilidade para trabalhar em horário comercial',
    ],
    responsibilities: [
      'Criar e planejar conteúdo para Instagram, Facebook e outras redes sociais',
      'Desenvolver estratégias de engajamento e crescimento',
      'Produzir textos criativos e autênticos que reflitam a voz da marca',
      'Gerenciar a comunidade online, respondendo comentários e mensagens',
      'Criar e editar conteúdo visual (fotos, vídeos, stories, reels)',
      'Monitorar métricas e relatórios de desempenho',
      'Colaborar com a equipe para capturar momentos do salão',
      'Manter-se atualizado sobre tendências de beleza e redes sociais',
    ],
    benefits: [
      'Ambiente de trabalho acolhedor e inclusivo',
      'Oportunidade de trabalhar com uma marca que valoriza autenticidade',
      'Crescimento profissional e desenvolvimento de habilidades',
      'Flexibilidade para expressar criatividade',
    ],
    contactMethod: 'whatsapp',
  },
]

/**
 * benefícios de trabalhar na mimo.
 */
export const JOB_BENEFITS = [
  {
    title: 'ambiente acolhedor',
    description: 'trabalhamos em um espaço seguro, inclusivo e colaborativo onde você pode crescer profissionalmente',
  },
  {
    title: 'desenvolvimento contínuo',
    description: 'investimos na formação e atualização da nossa equipe com cursos, workshops e treinamentos',
  },
  {
    title: 'reconhecimento',
    description: 'valorizamos o trabalho de cada profissional e reconhecemos o impacto positivo que você faz na vida das pessoas',
  },
  {
    title: 'flexibilidade',
    description: 'entendemos que cada pessoa tem suas necessidades e trabalhamos para criar horários que funcionem para todos',
  },
]

