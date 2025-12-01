/**
 * função reutilizável para cruzar dados de agendamentos com crédito/débito
 */

import * as XLSX from 'xlsx'
import { normalizarTelefone, normalizarNome, converterValorMonetario } from './utils'

export interface Estatisticas {
  totalAgendamentos: number
  comCreditoDebito: number
  semCreditoDebito: number
  confiancaAlta: number
  confiancaMedia: number
  confiancaNenhuma: number
  creditoTotal: number
  debitoTotal: number
  saldoLiquido: number
  usouTelefone: boolean
}

export interface LinhaResultado {
  [key: string]: string | number | null
}

/**
 * identifica coluna por palavras-chave (case insensitive)
 */
function identificarColuna(colunas: string[], palavrasChave: string[]): string | null {
  const colunasLower = new Map<string, string>()
  colunas.forEach((col) => {
    colunasLower.set(col.toLowerCase(), col)
  })

  for (const palavra of palavrasChave) {
    const palavraLower = palavra.toLowerCase()
    // busca exata
    if (colunasLower.has(palavraLower)) {
      return colunasLower.get(palavraLower)!
    }
    // busca parcial
    for (const [colLower, colOriginal] of colunasLower.entries()) {
      if (colLower.includes(palavraLower) || palavraLower.includes(colLower)) {
        return colOriginal
      }
    }
  }

  return null
}

interface ColunasAgendamentos {
  nome: string | null
  telefone: string | null
  quemAgendou: string | null
  dataCadastro: string | null
}

interface ColunasCreditoDebito {
  nome: string | null
  telefone: string | null
  credito: string | null
  debito: string | null
}

function identificarColunasAgendamentos(colunas: string[]): ColunasAgendamentos {
  return {
    nome: identificarColuna(colunas, ['nome', 'cliente', 'name']),
    telefone: identificarColuna(colunas, [
      'telefone',
      'fone',
      'phone',
      'celular',
      'whatsapp',
    ]),
    quemAgendou: identificarColuna(colunas, [
      'quem',
      'agendou',
      'agendador',
      'responsavel',
    ]),
    dataCadastro: identificarColuna(colunas, ['data', 'cadastro', 'date', 'criado']),
  }
}

function identificarColunasCreditoDebito(colunas: string[]): ColunasCreditoDebito {
  return {
    nome: identificarColuna(colunas, ['nome', 'cliente', 'name']),
    telefone: identificarColuna(colunas, [
      'telefone',
      'fone',
      'phone',
      'celular',
      'whatsapp',
    ]),
    credito: identificarColuna(colunas, [
      'credito',
      'crédito',
      'credit',
      'saldo',
      'a receber',
    ]),
    debito: identificarColuna(colunas, [
      'debito',
      'débito',
      'debit',
      'a pagar',
      'divida',
      'dívida',
    ]),
  }
}

/**
 * cruza dados de agendamentos com crédito/débito
 */
export function cruzarDados(
  workbookAgendamentos: XLSX.WorkBook,
  workbookCreditoDebito: XLSX.WorkBook
): { resultado: LinhaResultado[]; estatisticas: Estatisticas } {
  // pega primeira planilha de cada workbook
  const sheetAgend = workbookAgendamentos.SheetNames[0]
  const sheetCred = workbookCreditoDebito.SheetNames[0]

  const worksheetAgend = workbookAgendamentos.Sheets[sheetAgend]
  const worksheetCred = workbookCreditoDebito.Sheets[sheetCred]

  // converte para JSON
  const dadosAgend = XLSX.utils.sheet_to_json(worksheetAgend) as Record<string, unknown>[]
  const dadosCred = XLSX.utils.sheet_to_json(worksheetCred) as Record<string, unknown>[]

  // identifica colunas
  const cabecalhoAgend = Object.keys(dadosAgend[0] || {})
  const cabecalhoCred = Object.keys(dadosCred[0] || {})

  const colsAgend = identificarColunasAgendamentos(cabecalhoAgend)
  const colsCred = identificarColunasCreditoDebito(cabecalhoCred)

  // valida colunas obrigatórias
  if (!colsAgend.nome) {
    throw new Error('coluna de nome não encontrada no arquivo de agendamentos')
  }

  if (!colsCred.nome) {
    throw new Error('coluna de nome não encontrada no arquivo de crédito/débito')
  }

  // prepara dados de agendamentos
  const agendamentosLimpos = dadosAgend
    .map((row) => {
      const nome = normalizarNome(row[colsAgend.nome!] as string)
      if (!nome) return null

      const telefone = colsAgend.telefone
        ? normalizarTelefone(row[colsAgend.telefone] as string)
        : null

      return {
        ...row,
        nomeNormalizado: nome,
        telefoneNormalizado: telefone,
      }
    })
    .filter((row): row is NonNullable<typeof row> => row !== null)

  // prepara dados de crédito/débito
  const usarTelefone = !!(colsAgend.telefone && colsCred.telefone)

  const creditoDebitoLimpos = dadosCred
    .map((row) => {
      const nome = normalizarNome(row[colsCred.nome!] as string)
      if (!nome) return null

      const telefone = colsCred.telefone
        ? normalizarTelefone(row[colsCred.telefone] as string)
        : null

      // processa valores
      let creditoValor = 0.0
      let debitoValor = 0.0

      if (colsCred.credito && colsCred.debito && colsCred.credito === colsCred.debito) {
        // mesma coluna para crédito e débito
        const valor = converterValorMonetario(row[colsCred.credito] as any)
        if (valor >= 0) {
          creditoValor = valor
          debitoValor = 0.0
        } else {
          creditoValor = 0.0
          debitoValor = Math.abs(valor)
        }
      } else {
        if (colsCred.credito) {
          creditoValor = converterValorMonetario(row[colsCred.credito] as any)
        }
        if (colsCred.debito) {
          debitoValor = converterValorMonetario(row[colsCred.debito] as any)
        }
      }

      return {
        ...row,
        nomeNormalizado: nome,
        telefoneNormalizado: telefone,
        creditoValor,
        debitoValor,
        total: creditoValor - debitoValor,
      }
    })
    .filter((row): row is NonNullable<typeof row> => row !== null)

  // agrega crédito/débito por chave de match
  const creditoDebitoAgregado = new Map<
    string,
    {
      nome: string
      creditoValor: number
      debitoValor: number
      total: number
      count: number
    }
  >()

  for (const row of creditoDebitoLimpos) {
    const chave = usarTelefone && row.telefoneNormalizado
      ? row.telefoneNormalizado
      : row.nomeNormalizado

    if (!chave) continue

    const existente = creditoDebitoAgregado.get(chave)
    if (existente) {
      existente.creditoValor += row.creditoValor
      existente.debitoValor += row.debitoValor
      existente.total = existente.creditoValor - existente.debitoValor
      existente.count++
    } else {
      creditoDebitoAgregado.set(chave, {
        nome: ((row as any)[colsCred.nome!] as string) || chave,
        creditoValor: row.creditoValor,
        debitoValor: row.debitoValor,
        total: row.total,
        count: 1,
      })
    }
  }

  // conta quantos nomes têm cada chave (para calcular confiança)
  const contagemNomesCred = new Map<string, number>()
  for (const row of creditoDebitoLimpos) {
    const chave = usarTelefone && row.telefoneNormalizado
      ? row.telefoneNormalizado
      : row.nomeNormalizado
    if (chave) {
      contagemNomesCred.set(chave, (contagemNomesCred.get(chave) || 0) + 1)
    }
  }

  // faz o cruzamento
  const resultado: LinhaResultado[] = []

  for (const agend of agendamentosLimpos) {
    const chave = usarTelefone && agend.telefoneNormalizado
      ? agend.telefoneNormalizado
      : agend.nomeNormalizado

    if (!chave) continue

    const creditoDebito = creditoDebitoAgregado.get(chave)

    const linha: LinhaResultado = {}

    // nome do cliente
    linha['Nome Cliente'] = ((agend as any)[colsAgend.nome!] as string) || null

    // telefone se disponível
    if (colsAgend.telefone && (agend as any)[colsAgend.telefone]) {
      linha['Telefone'] = String((agend as any)[colsAgend.telefone])
    } else if (colsCred.telefone && creditoDebito) {
      // tenta pegar do crédito/débito se não tiver no agendamento
      const credRow = creditoDebitoLimpos.find(
        (r) =>
          (usarTelefone && r.telefoneNormalizado === chave) ||
          (!usarTelefone && r.nomeNormalizado === chave)
      )
      if (credRow && (credRow as any)[colsCred.telefone]) {
        linha['Telefone'] = String((credRow as any)[colsCred.telefone])
      }
    }

    // quem agendou
    if (colsAgend.quemAgendou && (agend as any)[colsAgend.quemAgendou]) {
      linha['Quem Agendou'] = String((agend as any)[colsAgend.quemAgendou])
    }

    // data cadastro
    if (colsAgend.dataCadastro && (agend as any)[colsAgend.dataCadastro]) {
      linha['Data Cadastro'] = String((agend as any)[colsAgend.dataCadastro])
    }

    // valores
    linha['Credito Valor'] = creditoDebito?.creditoValor || 0.0
    linha['Debito Valor'] = creditoDebito?.debitoValor || 0.0
    linha['Total'] = creditoDebito?.total || 0.0

    // confiança do match
    if (!creditoDebito || creditoDebito.total === 0.0) {
      linha['Confianca Match'] = 'NENHUMA'
    } else if (usarTelefone) {
      linha['Confianca Match'] = 'ALTA'
    } else {
      const count = contagemNomesCred.get(chave) || 0
      if (count === 1) {
        linha['Confianca Match'] = 'ALTA'
      } else if (count > 1) {
        linha['Confianca Match'] = 'MÉDIA'
      } else {
        linha['Confianca Match'] = 'NENHUMA'
      }
    }

    resultado.push(linha)
  }

  // calcula estatísticas
  const matches = resultado.filter((r) => r.Total !== 0.0).length
  const semMatch = resultado.filter((r) => r.Total === 0.0).length
  const confiancaAlta = resultado.filter((r) => r['Confianca Match'] === 'ALTA').length
  const confiancaMedia = resultado.filter((r) => r['Confianca Match'] === 'MÉDIA').length
  const confiancaNenhuma = resultado.filter((r) => r['Confianca Match'] === 'NENHUMA').length

  const creditoTotal = resultado.reduce((sum, r) => sum + (Number(r['Credito Valor']) || 0), 0)
  const debitoTotal = resultado.reduce((sum, r) => sum + (Number(r['Debito Valor']) || 0), 0)
  const saldoLiquido = resultado.reduce((sum, r) => sum + (Number(r.Total) || 0), 0)

  const estatisticas: Estatisticas = {
    totalAgendamentos: resultado.length,
    comCreditoDebito: matches,
    semCreditoDebito: semMatch,
    confiancaAlta,
    confiancaMedia,
    confiancaNenhuma,
    creditoTotal,
    debitoTotal,
    saldoLiquido,
    usouTelefone: usarTelefone,
  }

  return { resultado, estatisticas }
}

