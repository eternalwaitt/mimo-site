import { NextRequest, NextResponse } from 'next/server'
import { fetchReelThumbnail } from '@/lib/instagram'

/**
 * API route para buscar thumbnail de Instagram Reel.
 * 
 * GET /api/instagram/thumbnail?url={reel_url}
 * 
 * retorna JSON com thumbnail_url ou erro.
 */
export async function GET(request: NextRequest) {
  const searchParams = request.nextUrl.searchParams
  const reelUrl = searchParams.get('url')

  if (!reelUrl) {
    return NextResponse.json(
      { error: 'URL do reel é obrigatória' },
      { status: 400 }
    )
  }

  try {
    const thumbnailUrl = await fetchReelThumbnail(reelUrl)
    
    if (!thumbnailUrl) {
      return NextResponse.json(
        { error: 'Thumbnail não encontrado' },
        { status: 404 }
      )
    }

    return NextResponse.json({ thumbnail_url: thumbnailUrl })
  } catch (error) {
    console.error('Erro ao buscar thumbnail:', error)
    return NextResponse.json(
      { error: 'Erro ao buscar thumbnail' },
      { status: 500 }
    )
  }
}

