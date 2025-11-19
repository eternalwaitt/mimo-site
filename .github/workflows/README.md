# GitHub Actions Workflows

Este diretório contém os workflows de CI/CD para o projeto Mimo Site.

## Workflows Disponíveis

### deploy.yml

Workflow de deploy automático para Locaweb via SFTP.

**Trigger:**
- Push para branch `main` ou `master`
- Deploy manual via GitHub Actions UI

**Processo:**
1. Checkout do código
2. Setup Node.js para scripts de build
3. Minificação de CSS (`build/minify-css.sh`)
4. Minificação de JS (`build/minify-js.sh`)
5. Deploy via SFTP para servidor Locaweb

## Configuração de Secrets

Para usar este workflow, você precisa configurar os seguintes secrets no GitHub:

1. Vá em **Settings** → **Secrets and variables** → **Actions**
2. Clique em **New repository secret**
3. Adicione os seguintes secrets:

### Secrets Necessários

- `SFTP_HOST`: Hostname do servidor SFTP (ex: `ftp.locaweb.com.br`)
- `SFTP_USER`: Usuário SFTP
- `SFTP_PASSWORD`: Senha SFTP
- `SFTP_PORT`: Porta SFTP (padrão: 22, opcional)

### Como Obter as Credenciais

1. Acesse o painel da Locaweb
2. Vá em **FTP/SFTP** ou **Acesso SSH**
3. Copie as credenciais fornecidas

## Arquivos Excluídos do Deploy

O workflow exclui automaticamente:
- Arquivos de desenvolvimento (`.git`, `.env`, `node_modules`)
- Arquivos do WordPress (`wp-*`)
- Backups e cache (`img_backup_*`, `cache/`)
- Documentação (`*.md`, `README*`, `CHANGELOG*`)
- Arquivos de build (`build/`)
- Arquivos sensíveis (`x6f7689/`, `.env`)

## Deploy Manual

Para fazer deploy manual:

1. Vá em **Actions** no GitHub
2. Selecione **Deploy to Locaweb**
3. Clique em **Run workflow**
4. Selecione a branch e clique em **Run workflow**

## Troubleshooting

### Erro de conexão SFTP
- Verifique se as credenciais estão corretas
- Confirme que o servidor permite conexões SFTP
- Verifique se a porta está correta (geralmente 22 ou 2222)

### Erro de permissão
- Verifique se o usuário SFTP tem permissão de escrita no diretório remoto
- Confirme que o caminho `remote_path` está correto

### Build falha
- Verifique se os scripts de build têm permissão de execução
- Confirme que Node.js está instalado e funcionando

## Próximos Passos

- [ ] Adicionar notificações de deploy (Slack, Email)
- [ ] Adicionar rollback automático em caso de erro
- [ ] Adicionar testes antes do deploy
- [ ] Adicionar staging environment

