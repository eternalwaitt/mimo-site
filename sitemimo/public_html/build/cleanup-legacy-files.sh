#!/bin/bash
#
# Script de Limpeza de Arquivos Legacy
# Remove arquivos WordPress, backups antigos e arquivos não utilizados
#
# Desenvolvido por: Victor Penter
# Versão: 1.0.0
# Data: 2025-01-20
#

set -e  # Parar em caso de erro

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Diretório base
BASE_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$BASE_DIR"

echo -e "${YELLOW}=== Limpeza de Arquivos Legacy ===${NC}\n"

# Contador de arquivos removidos
REMOVED_COUNT=0
TOTAL_SIZE=0

# Função para remover arquivo/diretório e contar
remove_item() {
    local item="$1"
    local description="$2"
    
    if [ -e "$item" ]; then
        local size=$(du -sk "$item" 2>/dev/null | cut -f1)
        TOTAL_SIZE=$((TOTAL_SIZE + size))
        
        echo -e "${YELLOW}Removendo:${NC} $description"
        rm -rf "$item"
        REMOVED_COUNT=$((REMOVED_COUNT + 1))
        echo -e "${GREEN}✓ Removido${NC}\n"
    else
        echo -e "${YELLOW}Já removido:${NC} $description\n"
    fi
}

# 1. Arquivos WordPress na raiz
echo -e "${YELLOW}1. Removendo arquivos WordPress...${NC}\n"
for file in wp-*.php wp-admin wp-content wp-includes; do
    if [ -e "$file" ]; then
        remove_item "$file" "WordPress: $file"
    fi
done

# 2. Arquivos _index.php e __index.php na raiz
echo -e "${YELLOW}2. Removendo arquivos de backup PHP...${NC}\n"
for file in _index.php __index.php; do
    if [ -e "$file" ]; then
        remove_item "$file" "Backup PHP: $file"
    fi
done

# 3. Arquivos _index.html e __index.html em subdiretórios
echo -e "${YELLOW}3. Removendo arquivos de backup HTML...${NC}\n"
find . -type f \( -name "_index.html" -o -name "__index.html" \) -not -path "./vendor/*" -not -path "./.git/*" | while read -r file; do
    remove_item "$file" "Backup HTML: $file"
done

# 4. Backups de imagens antigos (manter apenas o mais recente se necessário)
echo -e "${YELLOW}4. Removendo backups de imagens antigos...${NC}\n"
# Manter apenas o backup mais recente se houver múltiplos
BACKUP_DIRS=$(find . -maxdepth 1 -type d -name "img_backup_*" | sort -r)
BACKUP_COUNT=$(echo "$BACKUP_DIRS" | wc -l | tr -d ' ')

if [ "$BACKUP_COUNT" -gt 1 ]; then
    # Manter o primeiro (mais recente) e remover os outros
    echo "$BACKUP_DIRS" | tail -n +2 | while read -r dir; do
        remove_item "$dir" "Backup de imagens antigo: $(basename "$dir")"
    done
elif [ "$BACKUP_COUNT" -eq 1 ]; then
    # Se houver apenas um, perguntar se quer manter (por padrão mantém)
    echo -e "${YELLOW}Encontrado 1 backup de imagens. Mantendo por padrão.${NC}\n"
fi

# 5. Verificar vendor/sendgrid (se não for usado)
echo -e "${YELLOW}5. Verificando vendor/sendgrid...${NC}\n"
if [ -d "vendor/sendgrid" ]; then
    # Verificar se é usado no código
    if ! grep -r "sendgrid\|SendGrid" --include="*.php" . 2>/dev/null | grep -v "vendor/sendgrid" | grep -v "README\|CHANGELOG\|IMPROVEMENTS" | grep -q "require\|include\|use"; then
        echo -e "${YELLOW}SendGrid não parece ser usado. Removendo...${NC}\n"
        remove_item "vendor/sendgrid" "SendGrid (não utilizado)"
    else
        echo -e "${GREEN}SendGrid pode estar em uso. Mantendo.${NC}\n"
    fi
fi

# Resumo
echo -e "\n${GREEN}=== Limpeza Concluída ===${NC}\n"
echo -e "Arquivos/diretórios removidos: ${GREEN}$REMOVED_COUNT${NC}"
echo -e "Espaço liberado: ${GREEN}$(numfmt --to=iec-i --suffix=B $((TOTAL_SIZE * 1024)))${NC}\n"

echo -e "${YELLOW}Nota:${NC} Arquivos removidos não podem ser recuperados facilmente."
echo -e "Se necessário, restaure do Git: ${GREEN}git checkout HEAD -- <arquivo>${NC}\n"

