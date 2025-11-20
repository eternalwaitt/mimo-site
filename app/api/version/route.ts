import { NextResponse } from 'next/server'
import { APP_VERSION, APP_VERSION_MAJOR, APP_VERSION_MINOR, APP_VERSION_PATCH, BUILD_DATE } from '@/lib/version'

/**
 * API route para obter a versão atual do site.
 * 
 * GET /api/version
 * 
 * retorna JSON com informações de versão.
 */
export async function GET() {
  return NextResponse.json({
    version: APP_VERSION,
    major: APP_VERSION_MAJOR,
    minor: APP_VERSION_MINOR,
    patch: APP_VERSION_PATCH,
    buildDate: BUILD_DATE,
  }, {
    headers: {
      'Cache-Control': 'public, max-age=3600', // Cache por 1 hora
    },
  })
}

