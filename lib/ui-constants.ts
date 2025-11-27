/**
 * constantes de UI - aspect ratios, delays de animação, etc.
 * centraliza magic numbers para facilitar manutenção.
 */

/** aspect ratios comuns usados no site */
export const ASPECT_RATIOS = {
  serviceCard: '4/3' as const,
  celebrityCard: '9/16' as const,
  hero: '16/9' as const,
  square: '1/1' as const,
} as const

/** delays de animação em segundos */
export const ANIMATION_DELAYS = {
  stagger: 0.1, // delay entre itens em grid/list
  fadeIn: 0.2,
  hover: 0.3,
} as const

/** durações de transição em milissegundos */
export const TRANSITION_DURATIONS = {
  fast: 200,
  normal: 300,
  slow: 400,
  verySlow: 500,
} as const

/** alturas máximas para expansão de conteúdo */
export const MAX_HEIGHTS = {
  serviceCardHover: '500px',
  modal: '90vh',
} as const

/** z-index layers */
export const Z_INDEX = {
  base: 0,
  dropdown: 100,
  sticky: 200,
  overlay: 300,
  modal: 400,
  tooltip: 500,
} as const

