#!/bin/bash

# Script para monitorar otimização de imagens em tempo real

LOG_FILE=$(find /tmp -maxdepth 1 -name "optimize-images-*.log" -type f -printf '%T@ %p\n' 2>/dev/null | sort -n | tail -1 | cut -d' ' -f2-)

# Fallback para macOS (stat)
if [[ -z "$LOG_FILE" ]]; then
    LOG_FILE=$(ls -t /tmp/optimize-images-*.log 2>/dev/null | head -1)
fi

if [[ -z "$LOG_FILE" ]] || [[ ! -f "$LOG_FILE" ]]; then
    echo "⚠️  Nenhum log de otimização encontrado."
    echo "💡 O script pode não estar rodando ou o log foi movido."
    exit 1
fi

# Contar imagens processadas
PROCESSED_COUNT=$(grep -c "✅ Concluído:" "$LOG_FILE" 2>/dev/null || echo "0")
SKIPPED_COUNT=$(grep -c "⏭️" "$LOG_FILE" 2>/dev/null || echo "0")

# Contar imagens prioritárias (total esperado)
TOTAL_IMAGES=12

# Calcular progresso
TOTAL_PROCESSED=$((PROCESSED_COUNT + SKIPPED_COUNT))
if [[ $TOTAL_IMAGES -gt 0 ]] && [[ -n "$TOTAL_PROCESSED" ]]; then
    PERCENT_COMPLETE=$((TOTAL_PROCESSED * 100 / TOTAL_IMAGES))
else
    PERCENT_COMPLETE=0
fi

if [[ -n "$TOTAL_PROCESSED" ]] && [[ -n "$TOTAL_IMAGES" ]]; then
    REMAINING_IMAGES=$((TOTAL_IMAGES - TOTAL_PROCESSED))
else
    REMAINING_IMAGES=$TOTAL_IMAGES
fi

# Calcular economia acumulada
TOTAL_SAVED_BYTES=$(grep "💾" "$LOG_FILE" | grep -oE "[0-9]+B" | sed 's/B//' | awk '{sum+=$1} END {print sum+0}' 2>/dev/null || echo "0")

# Verificar se script ainda está rodando
SCRIPT_PID=$(ps aux | grep "optimize-remaining-images" | grep -v grep | grep -v monitor | awk '{print $2}' | head -1)
if [[ -n "$SCRIPT_PID" ]]; then
    STATUS="🔄 Rodando"
    ACTIVE_PROCESSES=$(ps aux | grep -E "cwebp|avifenc|optipng|jpegoptim|convert" | grep -v grep | wc -l | tr -d ' ')
    ACTIVE_PROCESSES_DETAILS=$(ps aux | grep -E "cwebp|avifenc|optipng|jpegoptim|convert" | grep -v grep | head -3 | awk '{print "   - " $11 " (PID: " $2 ")"}')
else
    STATUS="✅ Concluído"
    ACTIVE_PROCESSES=0
    ACTIVE_PROCESSES_DETAILS=""
fi

# Estimar tempo restante (baseado em média de 5-10s por imagem)
if [[ $REMAINING_IMAGES -gt 0 ]] && [[ $PROCESSED_COUNT -gt 0 ]]; then
    # Calcular tempo médio por imagem (assumindo 8s)
    AVG_TIME_PER_IMAGE=8
    ESTIMATED_SECONDS=$((REMAINING_IMAGES * AVG_TIME_PER_IMAGE))
    ESTIMATED_MINUTES=$((ESTIMATED_SECONDS / 60))
    ESTIMATED_SECONDS_REMAINDER=$((ESTIMATED_SECONDS % 60))
    
    if [[ $ESTIMATED_MINUTES -gt 0 ]]; then
        TIME_ESTIMATE="${ESTIMATED_MINUTES}m ${ESTIMATED_SECONDS_REMAINDER}s"
    else
        TIME_ESTIMATE="${ESTIMATED_SECONDS}s"
    fi
else
    TIME_ESTIMATE="N/A"
fi

# Imagem atual sendo processada
CURRENT_IMAGE=$(tail -20 "$LOG_FILE" | grep "🔄 Processando:" | tail -1 | sed 's/.*🔄 Processando: //' | sed 's/$//' || echo "N/A")

# Última ação
LAST_ACTION=$(tail -3 "$LOG_FILE" | grep -E "✅|🔄|💾|⏭️" | tail -1 || echo "Aguardando...")

clear
echo "📊 STATUS DA OTIMIZAÇÃO DE IMAGENS"
echo "=========================================="
echo ""
echo "📈 Progresso Geral:"
echo "   Processadas: $PROCESSED_COUNT"
echo "   Já otimizadas (puladas): $SKIPPED_COUNT"
echo "   Total: $TOTAL_PROCESSED / $TOTAL_IMAGES ($PERCENT_COMPLETE%)"
echo "   Restam: $REMAINING_IMAGES imagens"
echo ""
echo "⏰ Tempo estimado restante: $TIME_ESTIMATE"
echo ""
echo "🔄 Status: $STATUS"
if [[ -n "$CURRENT_IMAGE" ]] && [[ "$CURRENT_IMAGE" != "N/A" ]]; then
    echo "🖼️  Processando agora: $CURRENT_IMAGE"
fi
echo ""
if [[ $TOTAL_SAVED_BYTES -gt 0 ]]; then
    SAVED_MB=$(echo "scale=2; $TOTAL_SAVED_BYTES / 1048576" | bc 2>/dev/null || echo "?")
    echo "💰 Economia acumulada: ${SAVED_MB}MB"
fi
echo ""
if [[ $ACTIVE_PROCESSES -gt 0 ]]; then
    echo "🔄 Processos ativos: $ACTIVE_PROCESSES"
    echo "$ACTIVE_PROCESSES_DETAILS"
    echo ""
fi
echo "📋 Última ação:"
echo "   $LAST_ACTION"
echo ""
echo "💡 Para ver o log completo: tail -f $LOG_FILE"

