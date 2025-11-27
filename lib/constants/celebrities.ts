/**
 * celebridades/influencers para #MomentoMIMO.
 * fotos devem estar em /images/depo/
 * instagram: link para perfil do Instagram
 */

import type { Celebrity } from '../types'

export const CELEBRITIES: Array<Celebrity> = [
  {
    id: 'brunahuli',
    name: 'Bruna Huli',
    // reelUrl pode ser usado para buscar thumbnail via API ou cache local
    // reelThumbnail pode ser configurado manualmente se já tiver a imagem baixada
    image: '/images/placeholder.svg',
    imageAlt: 'Bruna Huli - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/brunahuli/',
    reelUrl: 'https://www.instagram.com/reel/DBACXKPOvd0/',
    // reelThumbnail: '/images/reels/DBACXKPOvd0.webp', // descomente quando tiver a imagem baixada
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
    image: '/images/placeholder.svg',
    imageAlt: 'Karol Queiroz - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/karolqueiroz/',
    reelUrl: 'https://www.instagram.com/reel/C9h0HUXxDth/',
  },
  {
    id: 'letvasconcelos',
    name: 'Let Vasconcelos',
    image: '/images/placeholder.svg',
    imageAlt: 'Let Vasconcelos - influencer Mimo',
    service: 'Salão',
    instagram: 'https://www.instagram.com/letvasconcelos/',
    reelUrl: 'https://www.instagram.com/p/DElH799vrV5/',
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
]

