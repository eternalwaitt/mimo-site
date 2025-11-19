import type { NextConfig } from 'next'

// Bundle analyzer (only when ANALYZE env var is set)
const withBundleAnalyzer = require('@next/bundle-analyzer')({
  enabled: process.env.ANALYZE === 'true',
})

/**
 * configuração do next.js.
 * 
 * - output standalone: otimizado para deploy em containers
 * - images: suporte para avif/webp + Unsplash
 * - remotePatterns: permite imagens externas do Unsplash
 * - compress: compressão gzip/brotli habilitada
 * - poweredByHeader: remove header X-Powered-By para segurança
 * - bundle analyzer: ativado com ANALYZE=true
 */
const nextConfig: NextConfig = {
  output: 'standalone',
  compress: true,
  poweredByHeader: false,
  
  // Otimizações de performance (swcMinify é padrão no Next.js 15)
  
  // Headers de cache e compressão
  async headers() {
    return [
      {
        source: '/:path*',
        headers: [
          {
            key: 'X-DNS-Prefetch-Control',
            value: 'on'
          },
        ],
      },
      {
        source: '/images/:path*',
        headers: [
          {
            key: 'Cache-Control',
            value: 'public, max-age=31536000, immutable',
          },
        ],
      },
      {
        source: '/fonts/:path*',
        headers: [
          {
            key: 'Cache-Control',
            value: 'public, max-age=31536000, immutable',
          },
        ],
      },
      {
        source: '/_next/static/:path*',
        headers: [
          {
            key: 'Cache-Control',
            value: 'public, max-age=31536000, immutable',
          },
        ],
      },
    ]
  },
  
  images: {
    formats: ['image/avif', 'image/webp'],
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'images.unsplash.com',
        pathname: '/**',
      },
    ],
    // Otimizações de performance
    minimumCacheTTL: 31536000, // 1 ano
    deviceSizes: [640, 750, 828, 1080, 1200, 1920, 2048, 3840],
    imageSizes: [16, 32, 48, 64, 96, 128, 256, 384],
    // Qualidade otimizada para melhor compressão
    dangerouslyAllowSVG: false,
    contentSecurityPolicy: "default-src 'self'; script-src 'none'; sandbox;",
  },
}

export default withBundleAnalyzer(nextConfig)

