import type { NextConfig } from 'next'

/**
 * configuração do next.js.
 * 
 * - output standalone: otimizado para deploy em containers
 * - images: suporte para avif/webp + Unsplash
 * - remotePatterns: permite imagens externas do Unsplash
 * - compress: compressão gzip/brotli habilitada
 * - poweredByHeader: remove header X-Powered-By para segurança
 */
const nextConfig: NextConfig = {
  output: 'standalone',
  compress: true,
  poweredByHeader: false,
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
    minimumCacheTTL: 60,
    deviceSizes: [640, 750, 828, 1080, 1200, 1920, 2048, 3840],
    imageSizes: [16, 32, 48, 64, 96, 128, 256, 384],
  },
}

export default nextConfig

