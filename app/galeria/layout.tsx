import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'galeria',
  description: 'Veja alguns dos nossos trabalhos e resultados. Galeria de fotos dos serviços da Mimo: salão, esmalteria, cílios e mais.',
  alternates: {
    canonical: 'https://mimo-site.vercel.app/galeria',
  },
  openGraph: {
    title: 'Galeria | Mimo Salão',
    description: 'Veja alguns dos nossos trabalhos e resultados. Galeria de fotos dos serviços da Mimo.',
    url: 'https://mimo-site.vercel.app/galeria',
    type: 'website',
  },
}

export default function GaleriaLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return children
}
