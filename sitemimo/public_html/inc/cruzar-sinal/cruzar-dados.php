<?php
/**
 * Cruzamento de dados - converte lógica de cruzar_dados.py para PHP
 */

require_once __DIR__ . '/validacao.php';

/**
 * Normaliza telefone removendo caracteres especiais
 */
function normalizar_telefone($telefone) {
    if ($telefone === null || $telefone === '') {
        return null;
    }
    
    $telefone_str = trim((string)$telefone);
    $telefone_limpo = preg_replace('/\D/', '', $telefone_str);
    
    if (strlen($telefone_limpo) < 8) {
        return null;
    }
    
    return $telefone_limpo;
}

/**
 * Converte valor monetário para float
 */
function converter_valor_monetario($valor) {
    if ($valor === null || $valor === '') {
        return 0.0;
    }
    
    if (is_numeric($valor)) {
        return (float)$valor;
    }
    
    if (is_string($valor)) {
        $valor_limpo = str_replace(['R$', '$', ' '], '', $valor);
        $valor_limpo = str_replace(',', '.', $valor_limpo);
        $valor_limpo = trim($valor_limpo);
        
        if (is_numeric($valor_limpo)) {
            return (float)$valor_limpo;
        }
    }
    
    return 0.0;
}

/**
 * Identifica colunas relevantes no arquivo de agendamentos
 */
function identificar_colunas_agendamentos($df) {
    return [
        'nome' => identificar_coluna($df, ['nome', 'cliente', 'name']),
        'telefone' => identificar_coluna($df, ['telefone', 'fone', 'phone', 'celular', 'whatsapp']),
        'quem_agendou' => identificar_coluna($df, ['quem', 'agendou', 'agendador', 'responsavel']),
        'data_cadastro' => identificar_coluna($df, ['data', 'cadastro', 'date', 'criado']),
    ];
}

/**
 * Identifica colunas relevantes no arquivo de crédito/débito
 */
function identificar_colunas_credito_debito($df) {
    $credito = identificar_coluna($df, ['credito', 'crédito', 'credit', 'a receber']);
    $debito = identificar_coluna($df, ['debito', 'débito', 'debit', 'a pagar', 'divida', 'dívida']);
    
    // Se não encontrou crédito ou débito separados, procura coluna genérica
    $valor_generico = null;
    if (!$credito && !$debito) {
        $valor_generico = identificar_coluna($df, ['valor', 'saldo', 'total', 'crédito ou dívida']);
    }
    
    return [
        'nome' => identificar_coluna($df, ['nome', 'cliente', 'name']),
        'telefone' => identificar_coluna($df, ['telefone', 'fone', 'phone', 'celular', 'whatsapp']),
        'credito' => $credito,
        'debito' => $debito,
        'valor_generico' => $valor_generico,
    ];
}

/**
 * Lê arquivo Excel e retorna array
 */
function ler_excel_arquivo($arquivo_path) {
    global $phpspreadsheet_available;
    
    // Verificar novamente se está disponível
    if (!$phpspreadsheet_available) {
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $phpspreadsheet_available = class_exists('PhpOffice\PhpSpreadsheet\IOFactory');
        }
    }
    
    if (!$phpspreadsheet_available) {
        throw new Exception('PhpSpreadsheet não está instalado. Execute: composer require phpoffice/phpspreadsheet');
    }
    
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($arquivo_path);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = $worksheet->toArray();
    
    if (empty($data)) {
        return [];
    }
    
    // Primeira linha são os cabeçalhos
    $headers = array_shift($data);
    $headers = array_map('trim', $headers);
    
    // Converter para array associativo
    $result = [];
    foreach ($data as $row) {
        $row_assoc = [];
        foreach ($headers as $index => $header) {
            $row_assoc[$header] = $row[$index] ?? null;
        }
        $result[] = $row_assoc;
    }
    
    return $result;
}

/**
 * Cruza dados de agendamentos com crédito/débito
 */
function cruzar_dados($arquivo_agendamentos_path, $arquivo_credito_debito_path) {
    // Ler arquivos
    $df_agendamentos = ler_excel_arquivo($arquivo_agendamentos_path);
    $df_credito_debito = ler_excel_arquivo($arquivo_credito_debito_path);
    
    // Identifica colunas
    $cols_agend = identificar_colunas_agendamentos($df_agendamentos);
    $cols_cred = identificar_colunas_credito_debito($df_credito_debito);
    
    // Valida colunas obrigatórias
    if (!$cols_agend['nome']) {
        throw new Exception("coluna de nome não encontrada no arquivo de agendamentos");
    }
    
    if (!$cols_cred['nome']) {
        throw new Exception("coluna de nome não encontrada no arquivo de crédito/débito");
    }
    
    // Prepara dados de agendamentos
    $df_agend_clean = [];
    foreach ($df_agendamentos as $row) {
        $nome_norm = normalizar_nome($row[$cols_agend['nome']] ?? null);
        if ($nome_norm !== null) {
            $row['nome_normalizado'] = $nome_norm;
            if ($cols_agend['telefone']) {
                $row['telefone_normalizado'] = normalizar_telefone($row[$cols_agend['telefone']] ?? null);
            }
            $df_agend_clean[] = $row;
        }
    }
    
    // Tenta usar telefone se disponível
    $usar_telefone = $cols_agend['telefone'] && $cols_cred['telefone'];
    
    // Prepara dados de crédito/débito
    $df_cred_clean = [];
    foreach ($df_credito_debito as $row) {
        $nome_norm = normalizar_nome($row[$cols_cred['nome']] ?? null);
        if ($nome_norm !== null) {
            $row['nome_normalizado'] = $nome_norm;
            if ($usar_telefone && $cols_cred['telefone']) {
                $row['telefone_normalizado'] = normalizar_telefone($row[$cols_cred['telefone']] ?? null);
            }
            
            // Converte valores monetários
            if ($cols_cred['valor_generico']) {
                // Coluna genérica: valor positivo = crédito, negativo = débito
                $valor = converter_valor_monetario($row[$cols_cred['valor_generico']] ?? 0);
                $row['credito_valor'] = $valor >= 0 ? $valor : 0.0;
                $row['debito_valor'] = $valor < 0 ? abs($valor) : 0.0;
            } elseif ($cols_cred['credito'] && $cols_cred['debito'] && $cols_cred['credito'] === $cols_cred['debito']) {
                // Mesma coluna para crédito e débito (valor positivo = crédito, negativo = débito)
                $valor = converter_valor_monetario($row[$cols_cred['credito']] ?? 0);
                $row['credito_valor'] = $valor >= 0 ? $valor : 0.0;
                $row['debito_valor'] = $valor < 0 ? abs($valor) : 0.0;
            } else {
                // Colunas separadas para crédito e débito
                $row['credito_valor'] = $cols_cred['credito'] ? converter_valor_monetario($row[$cols_cred['credito']] ?? 0) : 0.0;
                $row['debito_valor'] = $cols_cred['debito'] ? converter_valor_monetario($row[$cols_cred['debito']] ?? 0) : 0.0;
            }
            
            $df_cred_clean[] = $row;
        }
    }
    
    // Agrega crédito/débito por chave de match
    $df_cred_agg = [];
    if ($usar_telefone) {
        $grupo_key = 'telefone_normalizado';
        $agregados = [];
        foreach ($df_cred_clean as $row) {
            $key = $row[$grupo_key] ?? null;
            if ($key === null) {
                continue;
            }
            if (!isset($agregados[$key])) {
                $agregados[$key] = [
                    'nome' => $row[$cols_cred['nome']] ?? $key,
                    'credito_valor' => 0.0,
                    'debito_valor' => 0.0,
                ];
            }
            $agregados[$key]['credito_valor'] += $row['credito_valor'];
            $agregados[$key]['debito_valor'] += $row['debito_valor'];
        }
        foreach ($agregados as $key => $agg) {
            $agg[$grupo_key] = $key;
            $agg['total'] = $agg['credito_valor'] - $agg['debito_valor'];
            $df_cred_agg[$key] = $agg;
        }
    } else {
        $grupo_key = 'nome_normalizado';
        $agregados = [];
        foreach ($df_cred_clean as $row) {
            $key = $row[$grupo_key] ?? null;
            if ($key === null) {
                continue;
            }
            if (!isset($agregados[$key])) {
                $agregados[$key] = [
                    'nome' => $row[$cols_cred['nome']] ?? $key,
                    'credito_valor' => 0.0,
                    'debito_valor' => 0.0,
                ];
            }
            $agregados[$key]['credito_valor'] += $row['credito_valor'];
            $agregados[$key]['debito_valor'] += $row['debito_valor'];
        }
        foreach ($agregados as $key => $agg) {
            $agg[$grupo_key] = $key;
            $agg['total'] = $agg['credito_valor'] - $agg['debito_valor'];
            $df_cred_agg[$key] = $agg;
        }
    }
    
    // Faz o cruzamento
    $resultado = [];
    foreach ($df_agend_clean as $row_agend) {
        $match_key = $usar_telefone ? ($row_agend['telefone_normalizado'] ?? null) : ($row_agend['nome_normalizado'] ?? null);
        
        $row_resultado = $row_agend;
        
        if ($match_key && isset($df_cred_agg[$match_key])) {
            $cred_data = $df_cred_agg[$match_key];
            $row_resultado['credito_valor'] = $cred_data['credito_valor'];
            $row_resultado['debito_valor'] = $cred_data['debito_valor'];
            $row_resultado['total'] = $cred_data['total'];
            
            // Calcula confiança
            if ($usar_telefone) {
                $row_resultado['confianca_match'] = ($row_resultado['total'] != 0.0) ? 'ALTA' : 'NENHUMA';
            } else {
                // Conta quantos registros têm o mesmo nome
                $count = 0;
                foreach ($df_cred_clean as $r) {
                    if (($r['nome_normalizado'] ?? null) === $match_key) {
                        $count++;
                    }
                }
                
                if ($count === 1 && $row_resultado['total'] != 0.0) {
                    $row_resultado['confianca_match'] = 'ALTA';
                } elseif ($count > 1 && $row_resultado['total'] != 0.0) {
                    $row_resultado['confianca_match'] = 'MÉDIA';
                } else {
                    $row_resultado['confianca_match'] = 'NENHUMA';
                }
            }
        } else {
            $row_resultado['credito_valor'] = 0.0;
            $row_resultado['debito_valor'] = 0.0;
            $row_resultado['total'] = 0.0;
            $row_resultado['confianca_match'] = 'NENHUMA';
        }
        
        $resultado[] = $row_resultado;
    }
    
    // Seleciona colunas para o relatório final
    $df_final = [];
    foreach ($resultado as $row) {
        $row_final = [];
        
        // Nome do cliente
        $nome_col = $cols_agend['nome'] . '_agend';
        if (isset($row[$nome_col])) {
            $row_final['nome_cliente'] = $row[$nome_col];
        } elseif (isset($row[$cols_agend['nome']])) {
            $row_final['nome_cliente'] = $row[$cols_agend['nome']];
        }
        
        // Telefone
        if ($cols_agend['telefone']) {
            $tel_col = $cols_agend['telefone'] . '_agend';
            if (isset($row[$tel_col])) {
                $row_final['telefone'] = $row[$tel_col];
            } elseif (isset($row[$cols_agend['telefone']])) {
                $row_final['telefone'] = $row[$cols_agend['telefone']];
            }
        } elseif ($cols_cred['telefone']) {
            $tel_col_cred = $cols_cred['telefone'] . '_cred';
            if (isset($row[$tel_col_cred])) {
                $row_final['telefone'] = $row[$tel_col_cred];
            } elseif (isset($row[$cols_cred['telefone']])) {
                $row_final['telefone'] = $row[$cols_cred['telefone']];
            }
        }
        
        // Quem agendou
        if ($cols_agend['quem_agendou']) {
            $quem_col = $cols_agend['quem_agendou'] . '_agend';
            if (isset($row[$quem_col])) {
                $row_final['quem_agendou'] = $row[$quem_col];
            } elseif (isset($row[$cols_agend['quem_agendou']])) {
                $row_final['quem_agendou'] = $row[$cols_agend['quem_agendou']];
            }
        }
        
        // Data cadastro
        if ($cols_agend['data_cadastro']) {
            $data_col = $cols_agend['data_cadastro'] . '_agend';
            if (isset($row[$data_col])) {
                $row_final['data_cadastro'] = $row[$data_col];
            } elseif (isset($row[$cols_agend['data_cadastro']])) {
                $row_final['data_cadastro'] = $row[$cols_agend['data_cadastro']];
            }
        }
        
        // Valores
        $row_final['credito_valor'] = $row['credito_valor'] ?? 0.0;
        $row_final['debito_valor'] = $row['debito_valor'] ?? 0.0;
        $row_final['total'] = $row['total'] ?? 0.0;
        $row_final['confianca_match'] = $row['confianca_match'] ?? 'NENHUMA';
        
        $df_final[] = $row_final;
    }
    
    // Calcula estatísticas
    $total_agendamentos = count($df_final);
    $com_credito_debito = 0;
    $sem_credito_debito = 0;
    $confianca_alta = 0;
    $confianca_media = 0;
    $confianca_nenhuma = 0;
    $credito_total = 0.0;
    $debito_total = 0.0;
    $saldo_liquido = 0.0;
    
    foreach ($df_final as $row) {
        if ($row['total'] != 0.0) {
            $com_credito_debito++;
        } else {
            $sem_credito_debito++;
        }
        
        if ($row['confianca_match'] === 'ALTA') {
            $confianca_alta++;
        } elseif ($row['confianca_match'] === 'MÉDIA') {
            $confianca_media++;
        } else {
            $confianca_nenhuma++;
        }
        
        $credito_total += $row['credito_valor'];
        $debito_total += $row['debito_valor'];
        $saldo_liquido += $row['total'];
    }
    
    $estatisticas = [
        'total_agendamentos' => $total_agendamentos,
        'com_credito_debito' => $com_credito_debito,
        'sem_credito_debito' => $sem_credito_debito,
        'confianca_alta' => $confianca_alta,
        'confianca_media' => $confianca_media,
        'confianca_nenhuma' => $confianca_nenhuma,
        'credito_total' => $credito_total,
        'debito_total' => $debito_total,
        'saldo_liquido' => $saldo_liquido,
        'usou_telefone' => $usar_telefone,
    ];
    
    return [
        'df_resultado' => $df_final,
        'estatisticas' => $estatisticas
    ];
}

/**
 * Salva resultado em arquivo Excel
 */
function salvar_resultado_excel($df_resultado, $arquivo_saida) {
    global $phpspreadsheet_available;
    
    // Verificar novamente se está disponível
    if (!$phpspreadsheet_available) {
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $phpspreadsheet_available = class_exists('PhpOffice\PhpSpreadsheet\IOFactory');
        }
    }
    
    if (!$phpspreadsheet_available) {
        throw new Exception('PhpSpreadsheet não está instalado. Execute: composer require phpoffice/phpspreadsheet');
    }
    
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    if (empty($df_resultado)) {
        throw new Exception('Nenhum dado para salvar');
    }
    
    // Cabeçalhos (primeira linha do resultado)
    $headers = array_keys($df_resultado[0]);
    $col = 1;
    foreach ($headers as $header) {
        // Renomear colunas para melhor legibilidade
        $header_formatado = ucwords(str_replace('_', ' ', $header));
        $sheet->setCellValueByColumnAndRow($col, 1, $header_formatado);
        $col++;
    }
    
    // Dados
    $row = 2;
    foreach ($df_resultado as $data_row) {
        $col = 1;
        foreach ($headers as $header) {
            $value = $data_row[$header] ?? '';
            $sheet->setCellValueByColumnAndRow($col, $row, $value);
            $col++;
        }
        $row++;
    }
    
    // Salvar
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($arquivo_saida);
}

