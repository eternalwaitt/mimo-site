import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'galeria',
  description: 'Veja alguns dos nossos trabalhos e resultados.',
}

export default function GaleriaLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return children
}

