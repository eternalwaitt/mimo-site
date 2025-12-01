<?php
/**
 * Validação de arquivos Excel para cruzar-sinal
 * Converte lógica de validacao.py para PHP
 */

// Verificar se PhpSpreadsheet está disponível
// NÃO carregar automaticamente - deixar o arquivo principal decidir
$phpspreadsheet_available = false;

// Só tentar carregar se já não foi carregado e se PHP >= 8.3
if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
    $php_version = phpversion();
    $php_version_ok = version_compare($php_version, '8.3.0', '>=');
    
    if ($php_version_ok && file_exists(__DIR__ . '/../../vendor/autoload.php')) {
        try {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $phpspreadsheet_available = class_exists('PhpOffice\PhpSpreadsheet\IOFactory');
        } catch (Throwable $e) {
            // Silenciar erro - será tratado pelo arquivo principal
            error_log('Erro ao carregar vendor em validacao.php: ' . $e->getMessage());
        }
    }
} else {
    $phpspreadsheet_available = true;
}

/**
 * Identifica coluna por palavras-chave (case insensitive)
 */
function identificar_coluna($df, $palavras_chave) {
    if (empty($df)) {
        return null;
    }
    
    $colunas = array_keys($df[0] ?? []);
    $colunas_lower = [];
    foreach ($colunas as $col) {
        $colunas_lower[strtolower($col)] = $col;
    }
    
    foreach ($palavras_chave as $palavra) {
        $palavra_lower = strtolower($palavra);
        // Busca exata
        if (isset($colunas_lower[$palavra_lower])) {
            return $colunas_lower[$palavra_lower];
        }
        // Busca parcial
        foreach ($colunas_lower as $col_lower => $col_original) {
            if (strpos($col_lower, $palavra_lower) !== false || strpos($palavra_lower, $col_lower) !== false) {
                return $col_original;
            }
        }
    }
    
    return null;
}

/**
 * Normaliza nome para comparação
 */
function normalizar_nome($nome) {
    if ($nome === null || $nome === '') {
        return null;
    }
    
    $nome_str = trim((string)$nome);
    $nome_limpo = preg_replace('/\s+/', ' ', $nome_str);
    $nome_limpo = strtolower($nome_limpo);
    
    if (strlen($nome_limpo) < 2) {
        return null;
    }
    
    return $nome_limpo;
}

/**
 * Lê arquivo Excel e retorna array de arrays associativos
 */
function ler_excel($arquivo_bytes, $filename) {
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
    
    $temp_file = tempnam(sys_get_temp_dir(), 'excel_');
    file_put_contents($temp_file, $arquivo_bytes);
    
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($temp_file);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();
        
        unlink($temp_file);
        
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
    } catch (Exception $e) {
        if (file_exists($temp_file)) {
            unlink($temp_file);
        }
        throw new Exception('Erro ao ler arquivo Excel: ' . $e->getMessage());
    }
}

/**
 * Valida arquivo de agendamentos
 */
function validar_arquivo_agendamentos($arquivo_bytes, $filename) {
    $erros = [];
    $df = null;
    
    // Verifica extensão
    if (!preg_match('/\.(xlsx|xls)$/i', $filename)) {
        $erros[] = "arquivo deve ser Excel (.xlsx ou .xls), recebido: {$filename}";
        return ['valido' => false, 'erros' => $erros, 'dataframe' => null];
    }
    
    try {
        $df = ler_excel($arquivo_bytes, $filename);
    } catch (Exception $e) {
        $erros[] = 'erro ao ler arquivo Excel: ' . $e->getMessage();
        return ['valido' => false, 'erros' => $erros, 'dataframe' => null];
    }
    
    // Verifica se tem dados
    if (empty($df)) {
        $erros[] = 'arquivo está vazio (sem linhas de dados)';
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    $colunas_disponiveis = array_keys($df[0] ?? []);
    
    // Verifica colunas obrigatórias
    $col_nome = identificar_coluna($df, ['nome', 'cliente', 'name']);
    if (!$col_nome) {
        $erros[] = 'coluna de nome/cliente não encontrada. colunas disponíveis: ' . implode(', ', $colunas_disponiveis);
    }
    
    // Verifica se tem dados válidos na coluna de nome
    if ($col_nome) {
        $nomes_validos = 0;
        foreach ($df as $row) {
            if (normalizar_nome($row[$col_nome] ?? null) !== null) {
                $nomes_validos++;
            }
        }
        if ($nomes_validos === 0) {
            $erros[] = "coluna '{$col_nome}' não possui dados válidos";
        }
    }
    
    // Verifica se NÃO é arquivo de crédito/débito (detecção de arquivo trocado)
    $col_credito = identificar_coluna($df, ['credito', 'crédito', 'credit', 'saldo', 'a receber']);
    $col_debito = identificar_coluna($df, ['debito', 'débito', 'debit', 'a pagar', 'divida', 'dívida']);
    $col_generica_valor = identificar_coluna($df, ['valor', 'saldo', 'total', 'crédito ou dívida']);
    
    $tem_colunas_credito_debito = $col_credito || $col_debito || $col_generica_valor;
    
    // Colunas opcionais típicas de agendamentos
    $col_quem_agendou = identificar_coluna($df, ['quem', 'agendou', 'agendador', 'responsavel', 'quem cadastrou']);
    $col_data_cadastro = identificar_coluna($df, ['data', 'cadastro', 'date', 'criado', 'data cadastro']);
    $col_profissional = identificar_coluna($df, ['profissional', 'prof', 'terapeuta', 'funcionario']);
    $col_servico = identificar_coluna($df, ['servico', 'serviço', 'service', 'procedimento']);
    $tem_colunas_agendamentos = $col_quem_agendou || $col_data_cadastro || $col_profissional || $col_servico;
    
    // Se tem colunas de crédito/débito mas não tem colunas típicas de agendamentos, arquivo está trocado
    if ($tem_colunas_credito_debito && !$tem_colunas_agendamentos) {
        $erros[] = '⚠️ ATENÇÃO: este arquivo parece ser de crédito/débito, não de agendamentos! verifique se os arquivos estão na ordem correta.';
        if (!$col_nome) {
            return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
        }
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    // Se não tem nenhuma coluna típica de agendamentos, avisa (mas não bloqueia se não tiver coluna de crédito)
    if (!$tem_colunas_agendamentos && !$tem_colunas_credito_debito) {
        $erros[] = '⚠️ AVISO: não foram encontradas colunas típicas de agendamentos (quem agendou, data cadastro, profissional, serviço). verifique se é o arquivo correto.';
    }
    
    // Retorna True mesmo com avisos, desde que tenha coluna de nome
    if (!$col_nome) {
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    return ['valido' => empty($erros), 'erros' => $erros, 'dataframe' => $df];
}

/**
 * Valida arquivo de crédito/débito
 */
function validar_arquivo_credito_debito($arquivo_bytes, $filename) {
    $erros = [];
    $df = null;
    
    // Verifica extensão
    if (!preg_match('/\.(xlsx|xls)$/i', $filename)) {
        $erros[] = "arquivo deve ser Excel (.xlsx ou .xls), recebido: {$filename}";
        return ['valido' => false, 'erros' => $erros, 'dataframe' => null];
    }
    
    try {
        $df = ler_excel($arquivo_bytes, $filename);
    } catch (Exception $e) {
        $erros[] = 'erro ao ler arquivo Excel: ' . $e->getMessage();
        return ['valido' => false, 'erros' => $erros, 'dataframe' => null];
    }
    
    // Verifica se tem dados
    if (empty($df)) {
        $erros[] = 'arquivo está vazio (sem linhas de dados)';
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    $colunas_disponiveis = array_keys($df[0] ?? []);
    
    // Verifica colunas obrigatórias
    $col_nome = identificar_coluna($df, ['nome', 'cliente', 'name']);
    if (!$col_nome) {
        $erros[] = 'coluna de nome/cliente não encontrada. colunas disponíveis: ' . implode(', ', $colunas_disponiveis);
    }
    
    // Verifica se tem dados válidos na coluna de nome
    if ($col_nome) {
        $nomes_validos = 0;
        foreach ($df as $row) {
            if (normalizar_nome($row[$col_nome] ?? null) !== null) {
                $nomes_validos++;
            }
        }
        if ($nomes_validos === 0) {
            $erros[] = "coluna '{$col_nome}' não possui dados válidos";
        }
    }
    
    // Verifica coluna de crédito/débito (OBRIGATÓRIA)
    $col_credito = identificar_coluna($df, ['credito', 'crédito', 'credit', 'saldo', 'a receber']);
    $col_debito = identificar_coluna($df, ['debito', 'débito', 'debit', 'a pagar', 'divida', 'dívida']);
    
    // Pode ser uma coluna só ou duas separadas
    $col_generica = null;
    if (!$col_credito && !$col_debito) {
        // Tenta encontrar coluna genérica
        $col_generica = identificar_coluna($df, ['valor', 'saldo', 'total', 'crédito ou dívida']);
        if (!$col_generica) {
            $erros[] = 'coluna de crédito/débito não encontrada. procure por: crédito, débito, saldo, valor';
        } else {
            // Verifica se tem dados válidos
            $valores_validos = 0;
            foreach ($df as $row) {
                if (isset($row[$col_generica]) && $row[$col_generica] !== null && $row[$col_generica] !== '') {
                    $valores_validos++;
                }
            }
            if ($valores_validos === 0) {
                $erros[] = "coluna '{$col_generica}' não possui dados válidos";
            }
        }
    }
    
    // Verifica se tem coluna de valor (obrigatória)
    $tem_coluna_valor = $col_credito || $col_debito || $col_generica;
    
    // Verifica se NÃO é arquivo de agendamentos (detecção de arquivo trocado)
    $col_quem_agendou = identificar_coluna($df, ['quem', 'agendou', 'agendador', 'responsavel', 'quem cadastrou']);
    $col_data_cadastro = identificar_coluna($df, ['data', 'cadastro', 'date', 'criado', 'data cadastro']);
    $col_profissional = identificar_coluna($df, ['profissional', 'prof', 'terapeuta', 'funcionario']);
    $col_servico = identificar_coluna($df, ['servico', 'serviço', 'service', 'procedimento']);
    $col_data_reserva = identificar_coluna($df, ['data reserva', 'data reserva', 'agendamento']);
    
    $tem_colunas_agendamentos = $col_quem_agendou || $col_data_cadastro || $col_profissional || $col_servico || $col_data_reserva;
    
    // Se parece ser arquivo de agendamentos mas não tem coluna de valor, arquivo está trocado
    if ($tem_colunas_agendamentos && !$tem_coluna_valor) {
        $erros[] = '⚠️ ATENÇÃO: este arquivo parece ser de agendamentos, não de crédito/débito! verifique se os arquivos estão na ordem correta.';
        if (!$col_nome) {
            return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
        }
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    if (!$col_nome) {
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    // Se não tem coluna de valor, não pode processar (erro crítico)
    if (!$tem_coluna_valor) {
        return ['valido' => false, 'erros' => $erros, 'dataframe' => $df];
    }
    
    return ['valido' => empty($erros), 'erros' => $erros, 'dataframe' => $df];
}

