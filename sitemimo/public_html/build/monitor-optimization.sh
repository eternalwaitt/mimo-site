#!/bin/bash

# Script para monitorar o progresso do optimize-all-images.sh em tempo real

LOG_FILE=$(ls -t /tmp/optimize-images-*.log 2>/dev/null | head -1)

if [ ! -f "$LOG_FILE" ]; then
    echo "❌ Log file não encontrado. O script está rodando?"
    exit 1
fi

echo "📊 STATUS DO SCRIPT DE OTIMIZAÇÃO DE IMAGENS"
echo "=========================================="
echo ""

# Extrair informações do log
TOTAL_IMAGES=$(grep "Total de imagens encontradas" "$LOG_FILE" | tail -1 | grep -oE '[0-9]+' | head -1)
PROCESSED=$(grep "✅ Concluído:" "$LOG_FILE" | wc -l | tr -d ' ')
SKIPPED=$(grep "⏭️.*já otimizado" "$LOG_FILE" | wc -l | tr -d ' ')
CURRENT_IMG=$(grep "🔄 Processando:" "$LOG_FILE" | tail -1 | sed 's/.*Processando: //' | sed 's/ .*//' || echo "N/A")

# Calcular progresso
if [ -n "$TOTAL_IMAGES" ] && [ "$TOTAL_IMAGES" -gt 0 ]; then
    TOTAL_PROCESSED=$((PROCESSED + SKIPPED))
    PERCENT=$(echo "scale=1; ($TOTAL_PROCESSED * 100) / $TOTAL_IMAGES" | bc 2>/dev/null || echo "0")
    REMAINING=$((TOTAL_IMAGES - TOTAL_PROCESSED))
    
    echo "📈 Progresso Geral:"
    echo "   Processadas: $PROCESSED"
    echo "   Já otimizadas (puladas): $SKIPPED"
    echo "   Total: $TOTAL_PROCESSED / $TOTAL_IMAGES ($PERCENT%)"
    echo "   Restam: $REMAINING imagens"
    echo ""
fi

# Calcular tempo
START_LINE=$(grep "Início:" "$LOG_FILE" | tail -1)
if [ -n "$START_LINE" ]; then
    START_TIME=$(echo "$START_LINE" | grep -oE '[0-9]{2}:[0-9]{2}:[0-9]{2}' || echo "N/A")
    echo "⏰ Iniciado: $START_TIME"
fi

LAST_PROGRESS=$(grep "⏱️  Progresso:" "$LOG_FILE" | tail -1)
if [ -n "$LAST_PROGRESS" ]; then
    ELAPSED=$(echo "$LAST_PROGRESS" | grep -oE '[0-9]+s decorridos' | grep -oE '[0-9]+' || echo "0")
    IMG_COUNT=$(echo "$LAST_PROGRESS" | grep -oE '\[[0-9]+/[0-9]+\]' | grep -oE '[0-9]+' | head -1)
    
    if [ -n "$ELAPSED" ] && [ "$ELAPSED" -gt 0 ] && [ -n "$IMG_COUNT" ] && [ "$IMG_COUNT" -gt 0 ]; then
        HOURS=$((ELAPSED / 3600))
        MINS=$(((ELAPSED % 3600) / 60))
        SECS=$((ELAPSED % 60))
        
        echo "⏱️  Tempo decorrido: ${HOURS}h ${MINS}m ${SECS}s"
        
        # Calcular previsão
        AVG_TIME=$(echo "scale=0; $ELAPSED / $IMG_COUNT" | bc 2>/dev/null || echo "0")
        if [ "$AVG_TIME" -gt 0 ] && [ "$REMAINING" -gt 0 ]; then
            ESTIMATED=$((AVG_TIME * REMAINING))
            EST_HOURS=$((ESTIMATED / 3600))
            EST_MINS=$(((ESTIMATED % 3600) / 60))
            
            echo "📊 Tempo médio por imagem: ${AVG_TIME}s"
            echo "⏰ Previsão de conclusão: ~${EST_HOURS}h ${EST_MINS}m"
        fi
    fi
fi

echo ""
echo "🔄 Processando agora: ${CURRENT_IMG}"
echo ""

# Economia acumulada
TOTAL_SAVED=$(grep "AVIF criado:" "$LOG_FILE" | grep -oE '[0-9]+B economizados' | grep -oE '[0-9]+' | awk '{sum+=$1} END {print sum}' || echo "0")
if [ "$TOTAL_SAVED" -gt 0 ]; then
    TOTAL_MB=$(echo "scale=2; $TOTAL_SAVED / 1048576" | bc 2>/dev/null || echo "0")
    TOTAL_GB=$(echo "scale=3; $TOTAL_SAVED / 1073741824" | bc 2>/dev/null || echo "0")
    echo "💰 Economia acumulada: ${TOTAL_MB}MB (${TOTAL_GB}GB)"
    echo ""
fi

# Processos ativos
ACTIVE_PROCS=$(ps aux | grep -E "optimize-all-images|avifenc|optipng|cwebp" | grep -v grep | wc -l | tr -d ' ')
if [ "$ACTIVE_PROCS" -gt 0 ]; then
    echo "🔄 Processos ativos: $ACTIVE_PROCS"
    ps aux | grep -E "optimize-all-images|avifenc|optipng|cwebp" | grep -v grep | head -3 | while read line; do
        CMD=$(echo "$line" | awk '{for(i=11;i<=NF;i++) printf "%s ", $i; print ""}')
        CPU=$(echo "$line" | awk '{print $3}')
        MEM=$(echo "$line" | awk '{print $4}')
        echo "   CPU: ${CPU}% | MEM: ${MEM}% | $CMD"
    done
    echo ""
fi

# Últimas ações
echo "📋 Últimas ações:"
tail -10 "$LOG_FILE" | grep -E "✅|🔄|⏱️|📊|⚠️" | tail -5 | sed 's/^/   /'

echo ""
echo "💡 Para ver o log completo: tail -f $LOG_FILE"

