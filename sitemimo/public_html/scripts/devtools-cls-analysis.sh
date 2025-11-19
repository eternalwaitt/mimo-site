#!/bin/bash
# Script para guiar análise de CLS usando Chrome DevTools
# Este script gera um relatório baseado no Lighthouse JSON e fornece instruções

echo "🔍 Análise de CLS usando Chrome DevTools"
echo "=========================================="
echo ""

# Verificar se arquivo JSON foi fornecido
if [ -z "$1" ]; then
    echo "❌ Erro: Arquivo JSON do Lighthouse não fornecido"
    echo ""
    echo "Uso: ./scripts/devtools-cls-analysis.sh <arquivo-json>"
    echo ""
    echo "Exemplo:"
    echo "  ./scripts/devtools-cls-analysis.sh pagespeed-results/validation-mobile-*.json"
    exit 1
fi

JSON_FILE="$1"

if [ ! -f "$JSON_FILE" ]; then
    echo "❌ Erro: Arquivo não encontrado: $JSON_FILE"
    exit 1
fi

echo "📊 Analisando: $JSON_FILE"
echo ""

# Extrair CLS usando Node.js
CLS_DATA=$(node -e "
const fs = require('fs');
const data = JSON.parse(fs.readFileSync('$JSON_FILE', 'utf8'));
const cls = data.audits['cumulative-layout-shift'];
if (cls && cls.details && cls.details.items) {
    console.log(JSON.stringify({
        total: cls.numericValue || 0,
        shifts: cls.details.items.map(item => ({
            score: item.score || 0,
            node: item.node ? {
                snippet: item.node.snippet || '',
                selector: item.node.selector || ''
            } : null
        }))
    }));
} else {
    console.log(JSON.stringify({total: 0, shifts: []}));
}
" 2>/dev/null)

if [ -z "$CLS_DATA" ]; then
    echo "❌ Erro ao analisar arquivo JSON"
    exit 1
fi

TOTAL_CLS=$(echo "$CLS_DATA" | node -e "const d=JSON.parse(require('fs').readFileSync(0,'utf8')); console.log(d.total);")

echo "📋 CLS Total: $TOTAL_CLS"
echo ""

echo "🎯 Elementos Causando Layout Shifts:"
echo ""

echo "$CLS_DATA" | node -e "
const data = JSON.parse(require('fs').readFileSync(0, 'utf8'));
data.shifts.forEach((shift, index) => {
    if (shift.score > 0) {
        console.log(\`\nShift #\${index + 1}:\`);
        console.log(\`  Score: \${shift.score.toFixed(4)}\`);
        if (shift.node) {
            if (shift.node.snippet) {
                const snippet = shift.node.snippet.substring(0, 100);
                console.log(\`  Elemento: \${snippet}\${shift.node.snippet.length > 100 ? '...' : ''}\`);
            }
            if (shift.node.selector) {
                console.log(\`  Seletor: \${shift.node.selector}\`);
            }
        }
    }
});
"

echo ""
echo ""
echo "💡 Como usar Chrome DevTools para investigar:"
echo ""
echo "1. Abra Chrome DevTools (F12 ou Cmd+Option+I)"
echo "2. Vá para a aba 'Performance'"
echo "3. Clique em 'Record' (ou Cmd+E)"
echo "4. Recarregue a página (Cmd+R)"
echo "5. Pare a gravação"
echo "6. Na seção 'Experience', procure por 'Layout Shifts'"
echo "7. Clique em cada shift para ver detalhes:"
echo "   - Quando ocorreu (timeline)"
echo "   - Dimensões antes/depois"
echo "   - Elemento específico causando o shift"
echo ""
echo "8. Para cada elemento identificado acima, verifique:"
echo "   - Se tem width/height explícitos"
echo "   - Se tem min-height definido"
echo "   - Se está sendo inserido dinamicamente via JS"
echo "   - Se CSS está sendo aplicado assincronamente"
echo ""

