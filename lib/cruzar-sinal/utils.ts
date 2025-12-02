/**
 * funções utilitárias para normalização de dados
 */

/**
 * normaliza nome: remove espaços extras, converte para maiúsculas, remove acentos
 */
export function normalizarNome(nome: string): string {
  if (!nome) return ''
  return nome
    .trim()
    .toUpperCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '') // remove acentos
    .replace(/\s+/g, ' ') // normaliza espaços
}

/**
 * normaliza telefone: remove caracteres não numéricos
 */
export function normalizarTelefone(telefone: string): string {
  if (!telefone) return ''
  return telefone.replace(/\D/g, '') // remove tudo que não é dígito
}

/**
 * converte valor monetário (string) para número
 * suporta formatos como "R$ 100,00", "100.00", "100,00", etc.
 */
export function converterValorMonetario(valor: any): number {
  if (typeof valor === 'number') return valor
  if (!valor) return 0
  
  const str = String(valor)
    .replace(/[R$\s]/g, '') // remove R$ e espaços
    .replace(/\./g, '') // remove pontos (milhares)
    .replace(',', '.') // substitui vírgula por ponto
  
  const num = parseFloat(str)
  return isNaN(num) ? 0 : num
}

