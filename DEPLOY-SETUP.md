# Guia de Setup de Deploy Automático

Este guia explica como configurar o deploy automático via GitHub Actions para o servidor Locaweb.

## 📋 Pré-requisitos

- Repositório no GitHub
- Acesso ao painel da Locaweb
- Credenciais SFTP da Locaweb

## 🚀 Passo 1: Criar o Workflow

O arquivo `.github/workflows/deploy.yml` já foi criado. Ele faz:
- Minificação automática de CSS/JS antes do deploy
- Upload via SFTP para o servidor
- Exclusão de arquivos desnecessários

## 🔐 Passo 2: Configurar Secrets no GitHub

1. Acesse seu repositório no GitHub
2. Vá em **Settings** → **Secrets and variables** → **Actions**
3. Clique em **New repository secret**
4. Adicione os seguintes secrets:

### Secrets Necessários

| Secret | Descrição | Exemplo |
|--------|-----------|---------|
| `SFTP_HOST` | Hostname do servidor SFTP | `ftp.locaweb.com.br` |
| `SFTP_USER` | Usuário SFTP | `seu_usuario` |
| `SFTP_PASSWORD` | Senha SFTP | `sua_senha` |
| `SFTP_PORT` | Porta SFTP (opcional, padrão: 22) | `22` ou `2222` |

### Como Obter as Credenciais

1. Acesse o painel da Locaweb
2. Vá em **FTP/SFTP** ou **Acesso SSH**
3. Procure por:
   - **Servidor FTP/SFTP**: Use como `SFTP_HOST`
   - **Usuário**: Use como `SFTP_USER`
   - **Senha**: Use como `SFTP_PASSWORD`
   - **Porta**: Use como `SFTP_PORT` (geralmente 22 ou 2222)

## ✅ Passo 3: Testar o Deploy

### Deploy Automático

O deploy acontece automaticamente quando você faz push para `main` ou `master`:

```bash
git add .
git commit -m "feat: nova feature"
git push origin main
```

### Deploy Manual

1. Vá em **Actions** no GitHub
2. Selecione **Deploy to Locaweb** no menu lateral
3. Clique em **Run workflow**
4. Selecione a branch e clique em **Run workflow**

## 📁 Estrutura do Deploy

O workflow faz deploy apenas da pasta `sitemimo/public_html/` para `/public_html` no servidor.

### Arquivos Excluídos

Os seguintes arquivos **não** são enviados:
- `.git/`, `.env`, `node_modules/`
- `wp-*/` (arquivos WordPress)
- `x6f7689/` (credenciais antigas)
- `cache/`, `img_backup_*/`
- Documentação (`*.md`, `README*`, etc.)
- `build/` (scripts de build)

### Arquivos Incluídos

- Todos os arquivos PHP
- CSS/JS minificados (`minified/`)
- Imagens e assets
- Configurações necessárias

## 🔍 Verificar Deploy

Após o deploy:

1. Acesse o site em produção
2. Verifique se as mudanças estão visíveis
3. Teste funcionalidades críticas:
   - Formulário de contato
   - Navegação
   - Páginas de serviço
   - Imagens carregando

## 🐛 Troubleshooting

### Erro: "Connection refused"
- Verifique se `SFTP_PORT` está correto
- Confirme que o servidor permite conexões SFTP
- Tente porta 2222 se 22 não funcionar

### Erro: "Authentication failed"
- Verifique se `SFTP_USER` e `SFTP_PASSWORD` estão corretos
- Confirme que as credenciais são de SFTP, não FTP

### Erro: "Permission denied"
- Verifique se o usuário SFTP tem permissão de escrita
- Confirme que o caminho `remote_path` está correto

### Build falha
- Verifique os logs do GitHub Actions
- Confirme que os scripts de build têm permissão de execução
- Teste os scripts localmente primeiro

## 🔄 Workflow do Deploy

```
Push para main/master
    ↓
GitHub Actions inicia
    ↓
Checkout do código
    ↓
Setup Node.js
    ↓
Minifica CSS (build/minify-css.sh)
    ↓
Minifica JS (build/minify-js.sh)
    ↓
Deploy via SFTP
    ↓
Site atualizado! 🎉
```

## 📝 Notas Importantes

1. **`.env` nunca é enviado**: O arquivo `.env` está no `.gitignore` e não é enviado no deploy. Configure as variáveis de ambiente diretamente no servidor.

2. **Cache de assets**: Após deploy, atualize `ASSET_VERSION` em `config.php` para forçar cache busting.

3. **Backup antes de deploy**: Sempre faça backup antes de fazer deploy de mudanças grandes.

4. **Teste localmente**: Teste todas as mudanças localmente antes de fazer push.

## 🎯 Próximos Passos

- [ ] Configurar notificações de deploy (Slack/Email)
- [ ] Adicionar staging environment
- [ ] Implementar rollback automático
- [ ] Adicionar testes antes do deploy

## 📚 Referências

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [SFTP Deploy Action](https://github.com/wlixcc/SFTP-Deploy-Action)
- [Locaweb FTP/SFTP Guide](https://www.locaweb.com.br/suporte/)

