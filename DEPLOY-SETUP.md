# Guia de Setup de Deploy Automático

Este guia explica como configurar o deploy automático via GitHub Actions para o servidor Locaweb.

## 📋 Pré-requisitos

- Repositório no GitHub
- Acesso ao painel da Locaweb
- Credenciais FTP/FTPS da Locaweb

## 🚀 Passo 1: Criar o Workflow

O arquivo `.github/workflows/deploy.yml` já foi criado. Ele faz:
- Minificação automática de CSS/JS antes do deploy
- Upload via FTPS (porta 21) para o servidor
- Exclusão de arquivos desnecessários

## 🔐 Passo 2: Configurar Secrets no GitHub

1. Acesse seu repositório no GitHub
2. Vá em **Settings** → **Secrets and variables** → **Actions**
3. Clique em **New repository secret**
4. Adicione os seguintes secrets:

### Secrets Necessários

| Secret | Descrição | Valor para MIMO |
|--------|-----------|-----------------|
| `FTP_HOST` | Hostname do servidor FTP | `ftp.minhamimo.com.br` |
| `FTP_USER` | Usuário FTP | `esteticamimo` |
| `FTP_PASSWORD` | Senha FTP | `Mimomimo123123!` |

**⚠️ IMPORTANTE**: A senha acima está exposta aqui apenas como referência. Configure os secrets no GitHub com esses valores.

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
- Verifique se a porta está correta (21 para FTPS)
- Confirme que o servidor permite conexões FTPS
- Verifique se o firewall não está bloqueando a conexão

### Erro: "Authentication failed"
- Verifique se `FTP_USER` e `FTP_PASSWORD` estão corretos nos secrets
- Confirme que as credenciais são de FTP/FTPS
- Verifique se o usuário tem permissão de acesso ao diretório remoto

### Erro: "Permission denied"
- Verifique se o usuário FTP tem permissão de escrita no diretório remoto
- Confirme que o caminho `server-dir` está correto (`./public_html/`)
- Verifique as permissões do diretório no servidor

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
Deploy via FTPS (porta 21)
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
- [FTP-Deploy-Action](https://github.com/SamKirkland/FTP-Deploy-Action) (SamKirkland)
- [Locaweb FTP/FTPS Guide](https://www.locaweb.com.br/suporte/)

## ⚙️ Configuração Técnica

### Protocolo e Porta
- **Protocolo**: FTPS (FTP sobre SSL/TLS)
- **Porta**: 21 (padrão para FTPS)
- **Action**: `SamKirkland/FTP-Deploy-Action@4.0.0`

**Nota**: A action `SamKirkland/FTP-Deploy-Action` não suporta SFTP (porta 22), apenas FTP/FTPS. Para hospedagem compartilhada na Locaweb, use FTPS na porta 21.

