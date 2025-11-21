# Lista de Melhorias UX/UI Mobile Prioritizadas

Data: 2025-01-29

## Críticas (Afetam Conversão)

### 1. Implementar Menu Mobile Funcional

**Problema**: 
Botão de menu hamburger existe mas não abre menu, quebrando navegação principal em mobile.

**Solução Proposta**:
```tsx
// Adicionar estado e funcionalidade
const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)

// Menu slide-in da direita/esquerda
// Overlay escuro quando aberto
// Animações suaves com framer-motion
// Fechar ao clicar em link ou overlay
```

**Impacto Esperado**:
- Navegação funcional em mobile
- Melhora significativa na experiência
- Reduz taxa de rejeição

**Esforço Estimado**: 2-3 horas

**Prioridade**: 🔴 Alta

---

## Importantes (Melhoram Experiência)

### 2. Adicionar Swipe Gestures na Galeria

**Problema**:
Lightbox da galeria não suporta swipe para navegar entre imagens, padrão esperado em mobile.

**Solução Proposta**:
- Usar `framer-motion` para detectar swipe
- Swipe left: próxima imagem
- Swipe right: imagem anterior
- Swipe down: fechar lightbox
- Indicadores de posição (dots)

**Impacto Esperado**:
- UX mais fluida e intuitiva
- Alinhado com expectativas do usuário
- Aumenta tempo na página

**Esforço Estimado**: 2-3 horas

**Prioridade**: 🟡 Média

### 3. Melhorar Filtros da Galeria (Scroll Horizontal)

**Problema**:
Filtros em mobile podem quebrar linha ou ficar muito pequenos.

**Solução Proposta**:
- Filtros em scroll horizontal
- Snap scroll para melhor UX
- Indicador visual de scroll disponível
- Filtro ativo sempre visível

**Impacto Esperado**:
- Melhor usabilidade em mobile
- Filtros mais acessíveis
- Visual mais limpo

**Esforço Estimado**: 1-2 horas

**Prioridade**: 🟡 Média

### 4. Adicionar Skeleton Screens

**Problema**:
Durante carregamento, não há feedback visual adequado, causando percepção de lentidão.

**Solução Proposta**:
- Skeleton para cards de serviços
- Skeleton para imagens da galeria
- Skeleton para seções principais
- Usar `react-loading-skeleton` ou criar custom

**Impacto Esperado**:
- Percepção de velocidade melhorada
- Layout mais estável durante loading
- Reduz CLS (Cumulative Layout Shift)

**Esforço Estimado**: 3-4 horas

**Prioridade**: 🟡 Média

### 5. Melhorar Touch Targets

**Problema**:
Alguns elementos podem ter touch targets menores que 44x44px recomendado.

**Solução Proposta**:
- Auditar todos os elementos clicáveis
- Garantir mínimo 44x44px
- Adicionar padding onde necessário
- Testar em dispositivos reais

**Impacto Esperado**:
- Melhor acessibilidade
- Menos erros de toque
- Melhor experiência geral

**Esforço Estimado**: 1-2 horas

**Prioridade**: 🟡 Média

---

## Nice-to-Have (Polimento)

### 6. Adicionar Active States em Touch

**Problema**:
Falta feedback visual imediato ao tocar em elementos.

**Solução Proposta**:
- Adicionar `active:` states no Tailwind
- Escurecer/iluminar ligeiramente ao tocar
- Transições suaves

**Impacto Esperado**:
- Feedback mais responsivo
- Sensação de app nativo

**Esforço Estimado**: 1 hora

**Prioridade**: 🟢 Baixa

### 7. Considerar Bottom Navigation

**Problema**:
Navegação principal pode não estar facilmente acessível com polegar.

**Solução Proposta**:
- Bottom nav com 4-5 itens principais
- Ícones + labels
- Indicador de página atual
- Apenas em mobile

**Impacto Esperado**:
- Acesso mais rápido
- Melhor ergonomia

**Esforço Estimado**: 4-5 horas

**Prioridade**: 🟢 Baixa (mudança arquitetural)

### 8. Adicionar Pull-to-Refresh

**Problema**:
Não há forma nativa de atualizar conteúdo em mobile.

**Solução Proposta**:
- Implementar pull-to-refresh na galeria
- Feedback visual durante pull
- Atualizar conteúdo

**Impacto Esperado**:
- UX mais nativa
- Funcionalidade esperada

**Esforço Estimado**: 2-3 horas

**Prioridade**: 🟢 Baixa

---

## Resumo de Prioridades

### Fazer Agora (Sprint 1)
1. Menu mobile funcional
2. Swipe gestures na galeria
3. Filtros scrolláveis

### Fazer Depois (Sprint 2)
4. Skeleton screens
5. Touch targets audit
6. Active states

### Considerar Futuro
7. Bottom navigation
8. Pull-to-refresh

## Métricas de Sucesso

- Taxa de rejeição mobile: reduzir 20%
- Tempo na página: aumentar 15%
- Taxa de conversão: aumentar 10%
- PageSpeed mobile: manter >80

