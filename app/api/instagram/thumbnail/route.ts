import { NextRequest, NextResponse } from 'next/server'
import { fetchReelThumbnail } from '@/lib/instagram'

/**
 * API route para buscar thumbnail de Instagram Reel.
 * 
 * GET /api/instagram/thumbnail?url={reel_url}
 * 
 * retorna JSON com thumbnail_url ou erro.
 * 
 * cache: 24h (86400s)
 * rate limiting: simples por IP (pode ser melhorado com Redis/Vercel KV)
 */
const CACHE_MAX_AGE = 86400 // 24 horas
const RATE_LIMIT_REQUESTS = 10 // requests por minuto
const RATE_LIMIT_WINDOW = 60 * 1000 // 1 minuto em ms

// Simple in-memory rate limiting (reset on server restart)
const rateLimitMap = new Map<string, { count: number; resetAt: number }>()

function checkRateLimit(ip: string): boolean {
  const now = Date.now()
  const record = rateLimitMap.get(ip)

  if (!record || now > record.resetAt) {
    rateLimitMap.set(ip, { count: 1, resetAt: now + RATE_LIMIT_WINDOW })
    return true
  }

  if (record.count >= RATE_LIMIT_REQUESTS) {
    return false
  }

  record.count++
  return true
}

function getClientIP(request: NextRequest): string {
  const forwarded = request.headers.get('x-forwarded-for')
  const realIP = request.headers.get('x-real-ip')
  return forwarded?.split(',')[0] || realIP || 'unknown'
}

export async function GET(request: NextRequest) {
  const searchParams = request.nextUrl.searchParams
  const reelUrl = searchParams.get('url')

  if (!reelUrl) {
    return NextResponse.json(
      { error: 'URL do reel é obrigatória' },
      { 
        status: 400,
        headers: {
          'Cache-Control': 'no-store',
        },
      }
    )
  }

  // Rate limiting
  const clientIP = getClientIP(request)
  if (!checkRateLimit(clientIP)) {
    return NextResponse.json(
      { error: 'Muitas requisições. Tente novamente em alguns instantes.' },
      { 
        status: 429,
        headers: {
          'Cache-Control': 'no-store',
          'Retry-After': '60',
        },
      }
    )
  }

  try {
    const thumbnailUrl = await fetchReelThumbnail(reelUrl)
    
    if (!thumbnailUrl) {
      return NextResponse.json(
        { error: 'Thumbnail não encontrado' },
        { 
          status: 404,
          headers: {
            'Cache-Control': `public, max-age=${CACHE_MAX_AGE}`,
          },
        }
      )
    }

    return NextResponse.json(
      { thumbnail_url: thumbnailUrl },
      {
        headers: {
          'Cache-Control': `public, max-age=${CACHE_MAX_AGE}, s-maxage=${CACHE_MAX_AGE}`,
          'CDN-Cache-Control': `max-age=${CACHE_MAX_AGE}`,
        },
      }
    )
  } catch (error) {
    console.error('Erro ao buscar thumbnail:', error)
    return NextResponse.json(
      { error: 'Erro ao buscar thumbnail' },
      { 
        status: 500,
        headers: {
          'Cache-Control': 'no-store',
        },
      }
    )
  }
}

