import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Mimo Hub | Produtos Recomendados',
  description: 'Descubra produtos cuidadosamente selecionados pela Mimo e nossas influencers. Produtos de beleza, cuidados e acessórios que recomendamos com carinho.',
  alternates: {
    canonical: 'https://mimo-site.vercel.app/mimo-hub',
  },
  openGraph: {
    title: 'Mimo Hub | Produtos Recomendados | Mimo Salão',
    description: 'Descubra produtos cuidadosamente selecionados pela Mimo e nossas influencers. Produtos de beleza, cuidados e acessórios que recomendamos com carinho.',
    url: 'https://mimo-site.vercel.app/mimo-hub',
    type: 'website',
  },
}

export default function MimoHubLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return children
}

