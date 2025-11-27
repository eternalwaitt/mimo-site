# Estratégia de Imagens: 100% Local

Data: 2025-01-29

## Decisão: Todas as Imagens Localmente

### Justificativa

**Performance**:
- Controle total sobre otimização (WebP, AVIF, múltiplos tamanhos)
- Sem dependência de serviços externos
- Cache controlado pelo Next.js
- LCP (Largest Contentful Paint) otimizado

**Confiabilidade**:
- Sem risco de imagens quebradas por mudanças em APIs externas
- Offline-first approach
- Controle sobre disponibilidade

**Manutenção**:
- Fácil de versionar no Git (com LFS se necessário)
- Processo de atualização controlado
- Sem limites de rate limiting

## Processo de Implementação

### 1. Busca de Imagens
- Fontes: Unsplash, Pexels (pesquisa manual ou via API)
- Categorias: salão, esmalteria, cílios, estética facial, estética corporal, micropigmentação
- Critérios: qualidade alta, relevância, estilo consistente

### 2. Download
- Baixar imagens em alta resolução
- Organizar por categoria em pastas temporárias

### 3. Otimização
- Usar `sharp` para processamento batch
- Gerar múltiplos formatos: WebP, AVIF
- Gerar múltiplos tamanhos: 400px, 800px, 1200px, 1920px
- Compressão otimizada (qualidade 80-85 para WebP)

### 4. Armazenamento
- Estrutura: `public/images/[categoria]/[nome].webp`
- Manter versões AVIF também: `public/images/[categoria]/[nome].avif`
- Organização por categoria de serviço

### 5. Integração
- Atualizar referências no código
- Usar `ImageWithFallback` com paths locais
- Configurar `sizes` adequadamente

## Script de Otimização

Criar `scripts/optimize-images.js` para:
- Processar batch de imagens
- Gerar WebP e AVIF
- Gerar tamanhos responsivos
- Manter estrutura de pastas

## Estrutura Final

```
public/images/
├── servicos/
│   ├── salao/
│   │   ├── hero-800.webp
│   │   ├── hero-1200.webp
│   │   └── hero-1920.webp
│   ├── esmalteria/
│   └── ...
├── galeria/
│   ├── image-1.webp
│   └── ...
└── depo/
    └── ...
```

## Vantagens da Abordagem Local

1. **Performance**: Imagens otimizadas e servidas localmente são mais rápidas
2. **Controle**: Total controle sobre qualidade e tamanhos
3. **Confiabilidade**: Sem dependências externas
4. **SEO**: Imagens locais são melhor indexadas
5. **Privacidade**: Sem requisições externas para imagens

## Trade-offs

**Desvantagens**:
- Mais espaço no repositório (mitigado com Git LFS se necessário)
- Processo manual de atualização (mas mais controlado)
- Necessita ferramentas de otimização (sharp)

**Solução para espaço**:
- Usar Git LFS para imagens grandes
- Ou armazenar apenas versões otimizadas
- Ou usar CDN próprio no futuro

## Conclusão

A estratégia 100% local é a melhor opção para este projeto, priorizando performance, controle e confiabilidade sobre conveniência.

