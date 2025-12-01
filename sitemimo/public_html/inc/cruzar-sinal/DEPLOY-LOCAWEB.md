# Deploy Cruzar Sinal - Locaweb

## ✅ Status: Pronto para Deploy

A ferramenta **Cruzar Sinal** está integrada ao site PHP e pronta para ser deployada na Locaweb.

## 📋 Checklist Pré-Deploy

### 1. Arquivos PHP
- [x] `cruzar-sinal-xyz123.php` - Página principal
- [x] `cruzar-sinal-download.php` - Endpoint de download
- [x] `inc/cruzar-sinal/validacao.php` - Validação de arquivos
- [x] `inc/cruzar-sinal/cruzar-dados.php` - Lógica de cruzamento
- [x] `composer.json` - Dependências (PhpSpreadsheet)

### 2. Dependências
- [x] PhpSpreadsheet adicionado ao `composer.json`
- [ ] **AÇÃO**: Instalar dependências no servidor via Composer

### 3. Diretórios
- [ ] **AÇÃO**: Criar diretórios de upload e output no servidor
- [ ] **AÇÃO**: Configurar permissões (755 para diretórios, 644 para arquivos)

## 🚀 Passos de Deploy

### Passo 1: Upload dos Arquivos

Via FTP/SFTP, enviar os seguintes arquivos para `public_html/`:

```
public_html/
├── cruzar-sinal-xyz123.php          ← NOVO
├── cruzar-sinal-download.php        ← NOVO
├── composer.json                    ← ATUALIZAR (já existe, adicionar PhpSpreadsheet)
└── inc/
    └── cruzar-sinal/                ← NOVO
        ├── validacao.php
        ├── cruzar-dados.php
        └── README.md
```

**Nota**: O `composer.json` já existe no servidor. Você precisa **adicionar** a linha do PhpSpreadsheet se ainda não estiver lá.

### Passo 2: Instalar Dependências PHP (Composer)

**Opção A: Via SSH (se tiver acesso)**

```bash
cd /home/usuario/public_html
php composer.phar install
```

**Opção B: Via Painel Locaweb (se disponível)**

1. Acesse o painel da Locaweb
2. Vá em "Composer" ou "Gerenciador de Dependências"
3. Execute `composer install` no diretório `public_html`

**Opção C: Upload do vendor/ (se não tiver Composer no servidor)**

Se a Locaweb não permitir executar Composer, você pode fazer upload do diretório `vendor/` completo:

```bash
# No seu ambiente local
cd sitemimo/public_html
php composer.phar install

# Depois, fazer upload do diretório vendor/ inteiro para o servidor
```

### Passo 3: Criar Diretórios e Configurar Permissões

**Via FTP/SFTP:**

1. Criar diretório `cruzar-sinal-uploads/` em `public_html/`
2. Criar diretório `cruzar-sinal-outputs/` em `public_html/`
3. Configurar permissões:
   - Diretórios: **755**
   - Arquivos: **644**

**Via SSH (se tiver acesso):**

```bash
cd /home/usuario/public_html
mkdir -p cruzar-sinal-uploads
mkdir -p cruzar-sinal-outputs
chmod 755 cruzar-sinal-uploads
chmod 755 cruzar-sinal-outputs
```

### Passo 4: Verificar Configuração PHP

A ferramenta requer:
- PHP 7.1+ (o site já usa PHP 7.1.33+)
- Extensão `zip` (necessária para PhpSpreadsheet ler Excel)
- Extensão `xml` (necessária para PhpSpreadsheet)
- Extensão `gd` ou `imagick` (opcional, para imagens)

**Verificar no servidor:**

Criar arquivo `phpinfo.php` temporário:

```php
<?php phpinfo(); ?>
```

Acessar `https://minhamimo.com.br/phpinfo.php` e verificar se as extensões estão instaladas.

**Remover o arquivo `phpinfo.php` após verificar** (segurança).

## ✅ Verificação Pós-Deploy

### 1. Testar Acesso

Acesse: `https://minhamimo.com.br/cruzar-sinal-xyz123.php`

Deve aparecer:
- ✅ Header e Footer do site
- ✅ Formulário com dois campos de upload
- ✅ Sem erros de PHP

### 2. Testar Upload

1. Fazer upload do arquivo de agendamentos
2. Fazer upload do arquivo de crédito/débito
3. Clicar em "Processar"
4. Verificar se gera o relatório Excel

### 3. Verificar Erros

Se aparecer erro sobre PhpSpreadsheet:

```
PhpSpreadsheet não está instalado. Execute: composer require phpoffice/phpspreadsheet
```

**Solução:**
- Verificar se `vendor/autoload.php` existe
- Verificar se `vendor/phpoffice/phpspreadsheet/` existe
- Executar `composer install` novamente

### 4. Verificar Permissões

Se aparecer erro de permissão ao salvar arquivos:

```
Warning: mkdir(): Permission denied
```

**Solução:**
- Verificar permissões dos diretórios (deve ser 755)
- Verificar se o usuário do PHP tem permissão de escrita

## 🔒 Segurança

### Link Secreto

A URL `cruzar-sinal-xyz123.php` é um link secreto. Para aumentar a segurança:

1. **Não adicionar ao sitemap.xml** (já não está)
2. **Não criar links públicos** para esta página
3. **Considerar adicionar autenticação básica** (se necessário):

```apache
# .htaccess (opcional)
<Files "cruzar-sinal-xyz123.php">
    AuthType Basic
    AuthName "Acesso Restrito"
    AuthUserFile /home/usuario/.htpasswd
    Require valid-user
</Files>
```

### Limpeza de Arquivos

Os arquivos temporários são limpos automaticamente:
- Após download do Excel gerado
- Ao limpar arquivos salvos na sessão

## 📝 Notas Técnicas

### Compatibilidade

- ✅ PHP 7.1+ (compatível com produção)
- ✅ PhpSpreadsheet 1.29+ (compatível com PHP 7.1)
- ✅ Sessões PHP (já configuradas no site)
- ✅ Upload de arquivos (já funciona no site)

### Limitações da Locaweb

- **Tamanho máximo de upload**: Verificar `upload_max_filesize` e `post_max_size` no PHP
- **Timeout**: Arquivos muito grandes podem dar timeout (padrão: 30s)
- **Memória**: Verificar `memory_limit` (recomendado: 128M+)

### Troubleshooting

**Erro: "Class 'PhpOffice\PhpSpreadsheet\IOFactory' not found"**
- Solução: Executar `composer install` no servidor

**Erro: "Permission denied" ao criar diretórios**
- Solução: Criar diretórios manualmente via FTP e dar permissão 755

**Erro: "File too large"**
- Solução: Aumentar `upload_max_filesize` e `post_max_size` no `php.ini` (se tiver acesso)

**Erro: "Timeout" ao processar**
- Solução: Aumentar `max_execution_time` no `php.ini` (se tiver acesso)

## 🎯 Resumo Rápido

1. ✅ Upload dos arquivos PHP
2. ✅ Instalar PhpSpreadsheet via Composer (`composer install`)
3. ✅ Criar diretórios `cruzar-sinal-uploads/` e `cruzar-sinal-outputs/`
4. ✅ Configurar permissões (755)
5. ✅ Testar em `https://minhamimo.com.br/cruzar-sinal-xyz123.php`

## 📞 Suporte

Se encontrar problemas:
1. Verificar logs de erro do PHP (se disponível)
2. Verificar se todas as dependências estão instaladas
3. Verificar permissões de arquivos e diretórios
4. Testar com arquivos pequenos primeiro

---

**Última atualização**: 2025-12-01  
**Versão**: 1.0.0

