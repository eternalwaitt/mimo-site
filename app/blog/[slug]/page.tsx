import type { Metadata } from 'next'
import { notFound } from 'next/navigation'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'

type Props = {
  params: Promise<{ slug: string }>
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params
  return {
    title: slug,
  }
}

/**
 * página individual de blog post - estrutura preparada (fase 2).
 * 
 * - placeholder para futura implementação
 * - em fase 2, buscar post do CMS
 * - retorna 404 por enquanto
 */
export default async function BlogPostPage({ params }: Props) {
  const { slug } = await params
  // Em fase 2, buscar post do CMS
  notFound()
}

