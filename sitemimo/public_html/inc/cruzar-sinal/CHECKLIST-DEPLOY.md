# ✅ Checklist de Deploy - Cruzar Sinal

## Arquivos para Upload

### 1. Arquivos Principais
- [ ] `cruzar-sinal-xyz123.php` → `public_html/cruzar-sinal-xyz123.php`
- [ ] `cruzar-sinal-download.php` → `public_html/cruzar-sinal-download.php`

### 2. Arquivos de Lógica
- [ ] `inc/cruzar-sinal/validacao.php` → `public_html/inc/cruzar-sinal/validacao.php`
- [ ] `inc/cruzar-sinal/cruzar-dados.php` → `public_html/inc/cruzar-sinal/cruzar-dados.php`
- [ ] `inc/cruzar-sinal/README.md` → `public_html/inc/cruzar-sinal/README.md` (opcional)

### 3. Configuração
- [ ] `composer.json` → `public_html/composer.json` (verificar se PhpSpreadsheet está listado)

## Ações no Servidor

### 1. Instalar Dependências
```bash
cd public_html
php composer.phar install
```

**OU** fazer upload do diretório `vendor/` completo (se não tiver acesso SSH)

### 2. Criar Diretórios
- [ ] Criar `public_html/cruzar-sinal-uploads/` (permissão 755)
- [ ] Criar `public_html/cruzar-sinal-outputs/` (permissão 755)

### 3. Verificar Permissões
- [ ] Diretórios: 755
- [ ] Arquivos PHP: 644

## Teste Pós-Deploy

- [ ] Acessar: `https://minhamimo.com.br/cruzar-sinal-xyz123.php`
- [ ] Verificar se aparece o formulário (sem erros PHP)
- [ ] Fazer upload de arquivo de teste
- [ ] Verificar se processa corretamente
- [ ] Verificar se gera e permite download do Excel

## Problemas Comuns

### Erro: "PhpSpreadsheet não está instalado"
**Solução**: Executar `composer install` ou fazer upload do `vendor/`

### Erro: "Permission denied"
**Solução**: Verificar permissões dos diretórios (deve ser 755)

### Erro: "Class not found"
**Solução**: Verificar se `vendor/autoload.php` existe e está sendo carregado

---

**Data**: 2025-12-01  
**Status**: Pronto para deploy

