/**
 * constantes do site mimo.
 * informações reais coletadas do site minhamimo.com.br
 */

export const MIMO_CONTACT = {
  whatsapp: '5511994781012', // (11) 99478-1012
  phone: '(11) 3062-8295',
  email: 'atendimento@minhamimo.com.br',
  address: {
    street: 'Rua Heitor Penteado, 626',
    neighborhood: 'Vila Madalena',
    city: 'São Paulo',
    state: 'SP',
    zipCode: '05436-100',
  },
  hours: {
    weekdays: '08:30 - 22:00', // Terça a Sábado
    saturday: '08:30 - 22:00',
    sunday: 'fechado',
    note: 'Terça-Feira à Sábado',
  },
}

export const MIMO_COMPANY = {
  name: 'Mimo',
  legalName: 'Mimo',
  cnpj: '57.659.472/0001-78',
  mission: 'Simplificar o cuidado, devolver tempo e transformar a relação com a beleza em um ritual leve, consciente e sustentável. Beleza aqui é ferramenta de autonomia, não futilidade.',
  values: [
    {
      title: 'diversidade',
      description: 'acolhemos todos os tipos de beleza, cabelos, corpos e identidades',
    },
    {
      title: 'acolhimento',
      description: 'criamos um espaço seguro onde você pode ser você mesma',
    },
    {
      title: 'qualidade',
      description: 'processos ágeis e baixa manutenção, sem comprometer o resultado',
    },
  ],
}

export const MIMO_SOCIAL = {
  instagram: 'https://www.instagram.com/minhamimo/',
  facebook: 'https://www.facebook.com/mimocuidadoebeleza/',
  hashtag: 'momentomimo',
}

/**
 * helpers para gerar links de whatsapp com mensagem pré-formatada.
 */
export function getWhatsAppUrl(message: string = 'Olá, vim pelo site e queria mais informações'): string {
  const encodedMessage = encodeURIComponent(message)
  return `https://wa.me/${MIMO_CONTACT.whatsapp}?text=${encodedMessage}`
}

export function getWhatsAppBookingUrl(): string {
  return getWhatsAppUrl('Olá, vim pelo site e queria agendar um horário')
}

export function getWhatsAppContactUrl(): string {
  return getWhatsAppUrl('Olá, vim pelo site e queria mais informações')
}

export function getWhatsAppJobApplicationUrl(jobTitle: string): string {
  return getWhatsAppUrl(`Olá, tenho interesse na vaga de ${jobTitle}. Vim pelo site e gostaria de me candidatar`)
}

/**
 * copy para seções da home.
 */
export const HOME_COPY = {
  hero: {
    headline: 'Beleza que cabe na vida real',
    subheadline: 'Seu tempo de volta, sua melhor versão sempre',
    ctaPrimary: 'Agendar agora',
    ctaSecondary: 'Conhecer serviços',
  },
  timeEconomy: {
    title: 'Economize tempo na sua rotina de beleza',
    calculation: {
      unhas: 40,
      lashes: 30,
      cabelo: 60,
    },
    punchline: 'Economize {hours}h/mês na sua rotina de beleza',
  },
  ctaAgendamento: {
    headline: 'Pronta pra seu momento?',
    ctaWhatsApp: 'Agendar no WhatsApp',
    ctaEquipe: 'Falar com a equipe',
  },
}

/**
 * serviços da mimo.
 * preços são "a partir de" - valores reais devem ser confirmados.
 */
import type { Service } from './types'

export const SERVICES: Array<Service> = [
  {
    id: 'salao',
    slug: 'salao',
    title: 'Salão',
    description: 'Cortes, coloração e tratamentos capilares personalizados para todos os tipos de cabelo.',
    shortDescription: 'Cortes e coloração que valorizam seu estilo único',
    price: 'A partir de R$ 80',
    image: '/images/servicos/salao/SALAO_HEADER.webp',
    imageAlt: 'Salão de beleza Mimo - cortes e coloração',
    benefits: [
      'Cortes personalizados para seu tipo de cabelo',
      'Coloração com técnicas modernas',
      'Tratamentos capilares',
      'Profissionais especializados em cabelos cacheados e crespos',
    ],
    procedures: [
      { name: 'Mimo Cut', image: '/images/servicos/salao/MimoCut.webp', imageAlt: 'Mimo Cut - corte de cabelo' },
      { name: 'Mimo Gloss', image: '/images/servicos/salao/MimoGloss.webp', imageAlt: 'Mimo Gloss - gloss capilar' },
      { name: 'Amonia Free', image: '/images/servicos/salao/AmoniaFree.webp', imageAlt: 'Coloração Amonia Free' },
      { name: 'Blond', image: '/images/servicos/salao/Blond.webp', imageAlt: 'Coloração Blond' },
      { name: 'Full Blond', image: '/images/servicos/salao/FullBlond.webp', imageAlt: 'Coloração Full Blond' },
      { name: 'Full Fantasy', image: '/images/servicos/salao/FullFantasy.webp', imageAlt: 'Coloração Full Fantasy' },
      { name: 'Ombre', image: '/images/servicos/salao/Ombre.webp', imageAlt: 'Coloração Ombre' },
      { name: 'Royal', image: '/images/servicos/salao/Royal.webp', imageAlt: 'Coloração Royal' },
      { name: 'Ruivo Deluxe', image: '/images/servicos/salao/RuivoDeluxe.webp', imageAlt: 'Coloração Ruivo Deluxe' },
      { name: 'Ruivo Mimo', image: '/images/servicos/salao/RuivoMimo.webp', imageAlt: 'Coloração Ruivo Mimo' },
      { name: 'Summer', image: '/images/servicos/salao/Summer.webp', imageAlt: 'Coloração Summer' },
      { name: 'Tonalização', image: '/images/servicos/salao/Tonalização.webp', imageAlt: 'Tonalização capilar' },
    ],
  },
  {
    id: 'esmalteria',
    slug: 'esmalteria',
    title: 'Esmalteria',
    description: 'Manicure e pedicure com esmaltação em gel, duradoura e de alta qualidade.',
    shortDescription: 'Unhas impecáveis que duram semanas',
    price: 'A partir de R$ 50',
    image: '/images/servicos/esmalteria/ESMALTERIA_HEADER.webp',
    imageAlt: 'Esmalteria Mimo - manicure e pedicure',
    benefits: [
      'Esmaltação em gel duradoura',
      'Manicure e pedicure completos',
      'Cuidados com cutículas',
      'Designs personalizados',
    ],
    procedures: [
      { name: 'Gel com Molde', image: '/images/servicos/esmalteria/GELCOMMOLDE.webp', imageAlt: 'Gel com molde' },
      { name: 'Gel com Protese', image: '/images/servicos/esmalteria/GELCOMPROTESE.webp', imageAlt: 'Gel com prótese' },
      { name: 'Acrigel com Molde', image: '/images/servicos/esmalteria/ACRIGELCOMMOLDE.webp', imageAlt: 'Acrigel com molde' },
      { name: 'Acrigel com Protese', image: '/images/servicos/esmalteria/ACRIGELCOMPROTESE.webp', imageAlt: 'Acrigel com prótese' },
      { name: 'Fibra de Vidro', image: '/images/servicos/esmalteria/FIBRADEVIDRO.webp', imageAlt: 'Fibra de vidro' },
      { name: 'Porcelana', image: '/images/servicos/esmalteria/PORCELANA.webp', imageAlt: 'Porcelana' },
      { name: 'Banho de Gel', image: '/images/servicos/esmalteria/banho_gel.webp', imageAlt: 'Banho de gel' },
      { name: 'Banho de Acrigel', image: '/images/servicos/esmalteria/banho_agrigel.webp', imageAlt: 'Banho de acrigel' },
    ],
  },
  {
    id: 'cilios',
    slug: 'cilios',
    title: 'Cílios e Design',
    description: 'Alongamento de cílios fio a fio e design de sobrancelhas com técnicas que garantem resultado natural e duradouro.',
    shortDescription: 'Cílios volumosos e sobrancelhas perfeitas',
    price: 'A partir de R$ 120',
    image: '/images/servicos/cilios/header_categoria_ciliosedesign.webp',
    imageAlt: 'Cílios e Design Mimo',
    benefits: [
      'Alongamento fio a fio',
      'Resultado natural e duradouro',
      'Manutenção a cada 3 semanas',
      'Técnicas modernas e seguras',
      'Design de sobrancelhas',
    ],
    procedures: [
      { name: 'Mimo', image: '/images/servicos/cilios/mimo.webp', imageAlt: 'Cílios Mimo' },
      { name: 'Mimo Mega', image: '/images/servicos/cilios/mimomega.webp', imageAlt: 'Cílios Mimo Mega' },
      { name: 'Super Mimo', image: '/images/servicos/cilios/supermimo.webp', imageAlt: 'Cílios Super Mimo' },
      { name: 'Volume Russo', image: '/images/servicos/cilios/volumerusso.webp', imageAlt: 'Volume Russo' },
      { name: 'Design Novo', image: '/images/servicos/cilios/designnovo.webp', imageAlt: 'Design de sobrancelhas novo' },
      { name: 'Design Sobrancelha', image: '/images/servicos/cilios/designsob2r.webp', imageAlt: 'Design de sobrancelhas' },
      { name: 'Lash Lifting', image: '/images/servicos/cilios/lashliftingcilios.webp', imageAlt: 'Lash Lifting' },
      { name: 'Lift Sobrancelha', image: '/images/servicos/cilios/liftsobrancelha.webp', imageAlt: 'Lift de sobrancelhas' },
    ],
  },
  {
    id: 'micropigmentacao',
    slug: 'micropigmentacao',
    title: 'Micropigmentação',
    description: 'Micropigmentação de sobrancelhas e outras áreas com técnicas que garantem resultado natural e duradouro.',
    shortDescription: 'Sobrancelhas perfeitas que duram',
    price: 'A partir de R$ 400',
    image: '/images/servicos/micro/header.webp',
    imageAlt: 'Micropigmentação Mimo',
    benefits: [
      'Micropigmentação de sobrancelhas',
      'Resultado natural e duradouro',
      'Técnicas modernas',
      'Retoque após 30 dias',
      'Micropigmentação labial',
    ],
    procedures: [
      { name: 'Microblading', image: '/images/servicos/micro/Microblading.webp', imageAlt: 'Microblading de sobrancelhas' },
      { name: 'Micropigmentação Labial', image: '/images/servicos/micro/lips.webp', imageAlt: 'Micropigmentação labial' },
    ],
  },
  {
    id: 'estetica-facial',
    slug: 'estetica-facial',
    title: 'Estética Facial',
    description: 'Tratamentos faciais personalizados para todos os tipos de pele com técnicas modernas e produtos de alta qualidade.',
    shortDescription: 'Pele radiante e cuidada',
    price: 'A partir de R$ 150',
    image: '/images/servicos/facial/FACIAL_HEADER.webp',
    imageAlt: 'Estética facial Mimo',
    benefits: [
      'Limpeza de pele profunda',
      'Tratamentos personalizados',
      'Produtos de alta qualidade',
      'Profissionais especializados',
      'Microagulhamento',
      'Peelings químicos',
    ],
    procedures: [
      { name: 'Limpeza Mimo', image: '/images/servicos/facial/LIMPEZAMIMO.webp', imageAlt: 'Limpeza de pele Mimo' },
      { name: 'Limpeza LED', image: '/images/servicos/facial/LIMPEZALED.webp', imageAlt: 'Limpeza de pele com LED' },
      { name: 'Mimo VIP', image: '/images/servicos/facial/MIMOVIP.webp', imageAlt: 'Tratamento facial Mimo VIP' },
      { name: 'Mimo Diamante', image: '/images/servicos/facial/MIMODIAMANTE.webp', imageAlt: 'Tratamento facial Mimo Diamante' },
      { name: 'Diamante', image: '/images/servicos/facial/DIAMANTE.webp', imageAlt: 'Tratamento facial com diamante' },
      { name: 'Peeling Químico', image: '/images/servicos/facial/QUIMICO.webp', imageAlt: 'Peeling químico' },
      { name: 'Microagulhamento', image: '/images/servicos/facial/microagulhamento.webp', imageAlt: 'Microagulhamento facial' },
      { name: 'Limpeza Microagulhamento', image: '/images/servicos/facial/limpezamicroagulhamento.webp', imageAlt: 'Limpeza com microagulhamento' },
      { name: 'Peeling de Cristal', image: '/images/servicos/facial/peeling-de-cristaL.webp', imageAlt: 'Peeling de cristal' },
      { name: 'Depilação Egípcia', image: '/images/servicos/facial/depilacao_egipcia.webp', imageAlt: 'Depilação egípcia' },
    ],
  },
  {
    id: 'estetica-corporal',
    slug: 'estetica-corporal',
    title: 'Estética Corporal',
    description: 'Tratamentos corporais para bem-estar e cuidados com o corpo com técnicas modernas e equipamentos de última geração.',
    shortDescription: 'Cuidados completos para o corpo',
    price: 'A partir de R$ 200',
    image: '/images/servicos/corporal/CORPORAL_HEADER.webp',
    imageAlt: 'Estética corporal Mimo',
    benefits: [
      'Tratamentos personalizados',
      'Técnicas modernas',
      'Foco em bem-estar',
      'Profissionais especializados',
      'Equipamentos de última geração',
    ],
    procedures: [
      { name: 'Drenagem Modeladora', image: '/images/servicos/corporal/drenamodeladora.webp', imageAlt: 'Drenagem modeladora' },
      { name: 'Drenagem Detox Power', image: '/images/servicos/corporal/drenodetoxpower.webp', imageAlt: 'Drenagem detox power' },
      { name: 'Linfática', image: '/images/servicos/corporal/linfatica.webp', imageAlt: 'Drenagem linfática' },
      { name: 'Mimo Detox', image: '/images/servicos/corporal/mimodetox.webp', imageAlt: 'Mimo Detox' },
      { name: 'Modeladora', image: '/images/servicos/corporal/modeladora.webp', imageAlt: 'Massagem modeladora' },
      { name: 'Relaxante', image: '/images/servicos/corporal/relaxante.webp', imageAlt: 'Massagem relaxante' },
      { name: 'Radiofrequência', image: '/images/servicos/corporal/radiofrequencia.webp', imageAlt: 'Radiofrequência corporal' },
      { name: 'Criofrequência', image: '/images/servicos/corporal/criofrequencia.webp', imageAlt: 'Criofrequência' },
      { name: 'Corrente Russa', image: '/images/servicos/corporal/Corrente-Russa.webp', imageAlt: 'Corrente russa' },
      { name: 'Pump', image: '/images/servicos/corporal/pump.webp', imageAlt: 'Pump corporal' },
      { name: 'Ultrassom', image: '/images/servicos/corporal/ultrassom.webp', imageAlt: 'Ultrassom estético' },
      { name: 'Endermoterapia', image: '/images/servicos/corporal/endermoterapia.webp', imageAlt: 'Endermoterapia' },
      { name: 'Estrias', image: '/images/servicos/corporal/estrias.webp', imageAlt: 'Tratamento de estrias' },
      { name: 'Área Íntima', image: '/images/servicos/corporal/areaintima.webp', imageAlt: 'Tratamento área íntima' },
    ],
  },
]

/**
 * celebridades/influencers para #MomentoMIMO.
 * fotos devem estar em /images/depo/
 * instagram: link para perfil do Instagram
 */
export const CELEBRITIES: Array<{
  id: string
  name: string
  image: string
  imageAlt: string
  service: string
  instagram?: string
  reelUrl?: string
  quote?: string
}> = [
  {
    id: 'brunahuli',
    name: 'Bruna Huli',
    // Se tiver reelUrl, o CelebrityCard vai buscar thumbnail automático
    // Fallback para placeholder se não conseguir
    image: '/images/placeholder.svg',
    imageAlt: 'Bruna Huli - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/brunahuli/',
    reelUrl: 'https://www.instagram.com/reel/DBACXKPOvd0/',
  },
  {
    id: 'tainacosta',
    name: 'Tainá Costa',
    image: '/images/depo/tainacosta.webp',
    imageAlt: 'Tainá Costa - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/tainacosta/',
  },
  {
    id: 'karolqueiroz',
    name: 'Karol Queiroz',
    image: '/images/depo/karolqueiroz.webp',
    imageAlt: 'Karol Queiroz - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/karolqueiroz/',
  },
  {
    id: 'letvasconcelos',
    name: 'Let Vasconcelos',
    image: '/images/depo/letvasconcelos.webp',
    imageAlt: 'Let Vasconcelos - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/letvasconcelos/',
  },
  {
    id: 'pathydosreis',
    name: 'Pathy dos Reis',
    image: '/images/depo/pathydosreis.webp',
    imageAlt: 'Pathy dos Reis - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/pathydosreis/',
  },
  {
    id: 'frednicacio',
    name: 'Fred Nicacio',
    image: '/images/depo/frednicacio.webp',
    imageAlt: 'Fred Nicacio - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/frednicacio/',
  },
  {
    id: 'amanda',
    name: 'Amanda',
    image: '/images/depo/amanda.webp',
    imageAlt: 'Amanda - cliente Mimo',
    service: 'Salão',
  },
  {
    id: 'barbara',
    name: 'Bárbara',
    image: '/images/depo/barbara.webp',
    imageAlt: 'Bárbara - cliente Mimo',
    service: 'Esmalteria',
  },
  {
    id: 'cathamendonca',
    name: 'Cath Mendonça',
    image: '/images/depo/cathamendonca.webp',
    imageAlt: 'Cath Mendonça - cliente Mimo',
    service: 'Salão',
  },
  {
    id: 'livcordeiro',
    name: 'Liv Cordeiro',
    image: '/images/depo/livcordeiro.webp',
    imageAlt: 'Liv Cordeiro - cliente Mimo',
    service: 'Cílios',
  },
  {
    id: 'mamoderoso',
    name: 'Mamó de Roso',
    image: '/images/depo/mamoderoso.webp',
    imageAlt: 'Mamó de Roso - cliente Mimo',
    service: 'Salão',
  },
  {
    id: 'pdamora',
    name: 'P. D\'Amora',
    image: '/images/depo/pdamora.webp',
    imageAlt: 'P. D\'Amora - cliente Mimo',
    service: 'Esmalteria',
  },
]

/**
 * vagas em aberto para trabalhe-aqui.
 */
export const JOB_OPENINGS: Array<{
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
}> = [
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

