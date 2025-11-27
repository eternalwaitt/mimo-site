/**
 * informações de contato e empresa da mimo.
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

