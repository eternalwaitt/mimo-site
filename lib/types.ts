/**
 * tipos typescript para o site mimo.
 * 
 * definições de dados para serviços, celebridades, vagas e produtos.
 */

/**
 * tipo para serviços oferecidos pelo salão.
 * 
 * @property {string} id - identificador único do serviço
 * @property {string} slug - slug usado na URL (ex: "salao", "cilios")
 * @property {string} title - nome do serviço exibido
 * @property {string} description - descrição completa do serviço
 * @property {string} [shortDescription] - descrição curta para cards (opcional)
 * @property {string} price - preço formatado (ex: "A partir de R$ 80")
 * @property {string} image - caminho da imagem principal
 * @property {string} imageAlt - texto alternativo da imagem
 * @property {Array<string>} benefits - lista de benefícios do serviço
 * @property {Array<{name: string, image: string, imageAlt: string}>} [procedures] - procedimentos específicos (opcional)
 * @property {Array<{image: string, imageAlt: string, context?: string}>} [portfolio] - portfólio de trabalhos (opcional)
 */
export type Service = {
  id: string
  slug: string
  title: string
  description: string
  shortDescription?: string
  price: string
  image: string
  imageAlt: string
  benefits: Array<string>
  procedures?: Array<{
    name: string
    image: string
    imageAlt: string
  }>
  portfolio?: Array<{
    image: string
    imageAlt: string
    context?: string
  }>
}

/**
 * tipo para celebridades e influencers exibidos no #MomentoMIMO.
 * 
 * @property {string} id - identificador único da celebridade
 * @property {string} name - nome da pessoa
 * @property {string} image - caminho da imagem estática (fallback)
 * @property {string} imageAlt - texto alternativo da imagem
 * @property {string} service - serviço utilizado (ex: "Coloração", "Corte")
 * @property {string} [instagram] - URL do perfil do Instagram (opcional)
 * @property {string} [reelUrl] - URL do Reel do Instagram (opcional, prioridade sobre instagram)
 * @property {string} [reelThumbnail] - caminho local para thumbnail do reel (opcional, ex: /images/reels/DBACXKPOvd0.webp)
 * @property {string} [quote] - depoimento ou frase da pessoa (opcional)
 */
export type Celebrity = {
  id: string
  name: string
  image: string
  imageAlt: string
  service: string
  instagram?: string
  reelUrl?: string
  reelThumbnail?: string
  quote?: string
}

/**
 * tipo para vagas de emprego disponíveis.
 * 
 * @property {string} id - identificador único da vaga
 * @property {string} slug - slug usado na URL (ex: "esteticista-facial")
 * @property {string} title - título da vaga
 * @property {string} area - área da vaga (ex: "Estética Facial", "Salão")
 * @property {string} description - descrição resumida da vaga
 * @property {string} [fullDescription] - descrição completa da vaga (opcional)
 * @property {Array<string>} requirements - lista de requisitos obrigatórios
 * @property {Array<string>} [responsibilities] - lista de responsabilidades (opcional)
 * @property {Array<string>} [benefits] - lista de benefícios oferecidos (opcional)
 * @property {'whatsapp' | 'email'} contactMethod - método de contato para candidatura
 */
export type JobOpening = {
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
}

/**
 * tipo para produtos recomendados no mimo hub.
 * 
 * @property {string} id - identificador único do produto
 * @property {string} slug - slug usado na URL (ex: "shampoo-reparador")
 * @property {string} title - nome do produto exibido
 * @property {string} description - descrição completa do produto
 * @property {string} [shortDescription] - descrição curta para cards (opcional)
 * @property {string} image - caminho da imagem principal
 * @property {string} imageAlt - texto alternativo da imagem
 * @property {string} affiliateUrl - link de afiliado para o produto
 * @property {string} category - categoria do produto (ex: "beleza", "cuidados", "acessorios", "tratamentos")
 * @property {string} recommendedBy - quem recomendou (ex: "Mimo", "Influencer X")
 * @property {string} [brand] - marca do produto (opcional)
 * @property {string} [price] - preço formatado (opcional, ex: "R$ 45,90")
 * @property {Array<string>} [tags] - tags para filtros futuros (opcional)
 */
export type Product = {
  id: string
  slug: string
  title: string
  description: string
  shortDescription?: string
  image: string
  imageAlt: string
  affiliateUrl: string
  category: string
  recommendedBy: string
  brand?: string
  price?: string
  tags?: Array<string>
}

