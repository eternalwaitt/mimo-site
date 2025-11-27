# Pesquisa de Fontes de Imagens

Data: 2025-01-29

## Fontes Pesquisadas

### 1. Unsplash
- **Licença**: Unsplash License (gratuita, uso comercial permitido)
- **Qualidade**: Alta (fotos profissionais)
- **API**: Sim, disponível
- **Limitações**: 50 requests/hora (sem autenticação), ilimitado com autenticação
- **Vantagens**: 
  - Qualidade profissional
  - Grande variedade
  - API bem documentada
  - Já configurado no projeto
- **Desvantagens**:
  - Pode ter imagens genéricas
  - Necessita atribuição (opcional mas recomendado)

### 2. Pexels
- **Licença**: Gratuita, uso comercial permitido
- **Qualidade**: Alta
- **API**: Sim, disponível
- **Limitações**: 200 requests/hora (gratuito)
- **Vantagens**:
  - Boa qualidade
  - API simples
  - Sem necessidade de atribuição
- **Desvantagens**:
  - Menor variedade que Unsplash
  - Limite de requests menor

### 3. Pixabay
- **Licença**: Gratuita, uso comercial permitido
- **Qualidade**: Variada (boa a excelente)
- **API**: Sim, disponível
- **Limitações**: 100 requests/hora (gratuito)
- **Vantagens**:
  - Inclui ilustrações e vetores
  - Boa variedade
- **Desvantagens**:
  - Qualidade mais variada
  - API menos intuitiva

### 4. Freepik
- **Licença**: Gratuita com atribuição OU paga
- **Qualidade**: Alta (ilustrações e fotos)
- **API**: Não (apenas download manual)
- **Vantagens**:
  - Ilustrações profissionais
  - Estilo único
- **Desvantagens**:
  - Requer atribuição ou pagamento
  - Sem API (processo manual)

### 5. Shutterstock
- **Licença**: Paga
- **Qualidade**: Excelente
- **API**: Sim (paga)
- **Vantagens**:
  - Qualidade premium
  - Grande variedade
- **Desvantagens**:
  - Custo alto
  - Não recomendado para projeto atual

## Recomendação

**Estratégia**: Usar **Unsplash** e **Pexels** como fontes principais.

**Justificativa**:
- Ambos têm APIs gratuitas
- Qualidade alta e consistente
- Licenças permissivas
- Boa variedade de imagens relacionadas a beleza/salão

**Processo**:
1. Buscar imagens via API ou manualmente
2. Baixar e otimizar localmente (WebP, AVIF)
3. Armazenar em `public/images/` organizado por categoria

## Próximos Passos

1. Criar script de busca de imagens (opcional)
2. Mapear todas as imagens faltantes
3. Baixar e otimizar imagens
4. Atualizar referências no código

