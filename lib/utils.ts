import { type ClassValue, clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

/**
 * combina classes tailwind de forma segura, evitando conflitos.
 * 
 * usa clsx para combinar classes condicionalmente e twMerge para resolver
 * conflitos entre classes tailwind (ex: p-4 e p-8 -> mantém apenas p-8).
 * 
 * @param {...ClassValue} inputs - classes tailwind a serem combinadas
 * @returns {string} string de classes combinadas e otimizadas
 * 
 * @example
 * ```tsx
 * cn('p-4', 'text-red-500', isActive && 'bg-blue-500')
 * // retorna: 'p-4 text-red-500 bg-blue-500' (se isActive for true)
 * ```
 */
export function cn(...inputs: ClassValue[]): string {
  return twMerge(clsx(inputs))
}

