# Guia de Deploy - Site Mimo

Este guia cobre todas as opções de deploy disponíveis para o site Mimo.

## Opção 1: Vercel (Recomendado)

A forma mais fácil e recomendada de fazer deploy é usar o Vercel. Você terá um link único tipo `mimo-site.vercel.app` ou pode configurar um domínio customizado.

### Método 1: Via Interface Web (Mais Fácil)

1. **Acesse o Vercel**
   - Vá em: https://vercel.com
   - Clique em **"Sign Up"** (ou faça login se já tiver conta)
   - Escolha **"Continue with GitHub"**

2. **Conecte o Repositório**
   - No dashboard, clique em **"Add New Project"**
   - Selecione o repositório `mimo-site`
   - Se o repositório não aparecer, clique em **"Adjust GitHub App Permissions"** e dê permissão

3. **Configure o Projeto**
   Na tela de configuração:
   - **Framework Preset**: Deixe **Next.js** (detecta automaticamente)
   - **Root Directory**: Deixe vazio (raiz do repositório)
   - **Build Command**: `npm run build` (já vem preenchido)
   - **Output Directory**: `.next` (já vem preenchido)
   - **Install Command**: `npm install` (já vem preenchido)

4. **Deploy!**
   - Clique no botão **"Deploy"**
   - Aguarde 2-5 minutos enquanto faz build
   - Quando terminar, você verá um link tipo: `mimo-site-xxx.vercel.app`

5. **Pronto!**
   - O link já está funcionando
   - Pode compartilhar
   - Toda vez que fizer `git push`, atualiza automaticamente

### Método 2: Via CLI (Terminal)

Se preferir usar terminal:

```bash
# 1. Instalar Vercel CLI
npm i -g vercel

# 2. Entrar na pasta do projeto
cd mimo-site

# 3. Fazer login (abre navegador)
vercel login

# 4. Fazer deploy
vercel

# 5. Para produção (link permanente)
vercel --prod
```

### Configurações Adicionais

#### Variáveis de Ambiente

Se precisar de variáveis de ambiente:
1. No dashboard Vercel, vá em **Settings** → **Environment Variables**
2. Adicione as variáveis necessárias (ex: `NEXT_PUBLIC_GA_MEASUREMENT_ID`)
3. Faça redeploy

#### Domínio Customizado

Para usar um domínio customizado (`minhamimo.com.br`):

1. No dashboard, vá em **Settings** → **Domains**
2. Clique em **"Add Domain"**
3. Digite: `minhamimo.com.br` e `www.minhamimo.com.br`
4. Siga as instruções para configurar DNS:
   - Adicione um registro CNAME no painel da Locaweb
   - Aponte para: `cname.vercel-dns.com`

### Atualizações Automáticas

- Toda vez que você fizer `git push` para a branch `main`, o Vercel faz deploy automático
- Você recebe notificação por email quando termina
- Pode desabilitar isso em **Settings** → **Git**

---

## Opção 2: Build Estático no Servidor Atual

Se quiser colocar no servidor PHP atual, precisa fazer build estático:

1. **Atualizar next.config.ts** para export estático:
```typescript
const nextConfig: NextConfig = {
  output: 'export',
  images: {
    unoptimized: true, // necessário para export estático
  },
}
```

2. **Fazer build**:
```bash
cd mimo-site
npm run build
```

3. **Copiar arquivos**:
```bash
# A pasta out/ será criada com os arquivos estáticos
cp -r out/* /caminho/para/servidor/public_html/
```

4. **Criar .htaccess** na pasta raiz:
```apache
RewriteEngine On
RewriteBase /
RewriteRule ^index\.html$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.html [L]
```

5. **Acessar**: `https://minhamimo.com.br`

**Nota**: Build estático tem limitações (sem API routes, sem server-side rendering dinâmico).

---

## Opção 3: Deploy Automático via GitHub Actions (Locaweb)

Para deploy automático no servidor Locaweb via FTP:

### Pré-requisitos

- Repositório no GitHub
- Acesso ao painel da Locaweb
- Credenciais FTP/FTPS da Locaweb

### Configuração

1. **Configurar Secrets no GitHub**
   - Acesse seu repositório no GitHub
   - Vá em **Settings** → **Secrets and variables** → **Actions**
   - Clique em **New repository secret**
   - Adicione os seguintes secrets:

| Secret | Descrição | Valor |
|--------|-----------|-------|
| `SFTP_HOST` | Hostname do servidor FTP | `ftp.minhamimo.com.br` |
| `SFTP_USER` | Usuário FTP | `esteticamimo` |
| `SFTP_PASSWORD` | Senha FTP | (senha do painel Locaweb) |

2. **Workflow já configurado**
   - O arquivo `.github/workflows/deploy.yml` já está criado
   - Faz minificação automática de CSS/JS antes do deploy
   - Upload via FTP (porta 21) para o servidor

3. **Deploy Automático**
   - O deploy acontece automaticamente quando você faz push para `main` ou `master`

4. **Deploy Manual**
   - Vá em **Actions** no GitHub
   - Selecione **Deploy to Locaweb** no menu lateral
   - Clique em **Run workflow**

### Configuração Técnica

- **Protocolo**: FTP (sem SSL/TLS)
- **Porta**: 21 (padrão para FTP)
- **Pasta raiz**: `/home/esteticamimo/`
- **Diretório remoto**: `./public_html/`

**Nota**: A Locaweb utiliza FTP na porta 21 para publicação de arquivos.

---

## Opção 4: Netlify (Alternativa ao Vercel)

Similar ao Vercel:

1. Acesse https://netlify.com
2. Conecte repositório
3. Configure build: `npm run build` e publish: `.next`
4. Link gerado tipo `mimo-site.netlify.app`

---

## Checklist Antes de Deployar

- [ ] Código commitado no GitHub
- [ ] `npm run build` funciona localmente (teste antes!)
- [ ] Imagens estão em `public/images/`
- [ ] Fontes estão em `public/fonts/`
- [ ] Variáveis de ambiente configuradas (se necessário)

---

## Troubleshooting

### Build falha

- Teste localmente primeiro: `npm run build`
- Verifique os logs no Vercel/GitHub Actions
- Verifique se todas as dependências estão no `package.json`

### Imagens não aparecem

- Certifique-se que estão em `public/images/`
- Use caminhos que começam com `/images/...`

### Erro de módulo não encontrado

- Verifique se todas dependências estão no `package.json`
- O Vercel/Netlify instala automaticamente, mas pode faltar alguma

### Erro: "Connection refused" (FTP)

- Verifique se a porta está correta (21 para FTP)
- Confirme que o servidor permite conexões FTP
- Teste a conexão manualmente com um cliente FTP (FileZilla, etc.)

### Erro: "530 Authentication failed" (FTP)

- Verifique se `SFTP_PASSWORD` está exatamente igual à senha do painel
- Não pode haver espaços extras no início ou fim da senha
- Teste as credenciais manualmente com FileZilla

### Erro: "Permission denied" (FTP)

- Verifique se o usuário FTP tem permissão de escrita no diretório remoto
- Confirme que o caminho `server-dir` está correto (`./public_html/`)

---

## Recomendação

**Use Vercel** - é mais fácil, rápido, e você tem um link único para compartilhar sem mexer no servidor atual. O deploy automático via GitHub Actions é útil se você precisar manter o site no servidor Locaweb atual.

---

## Link Gerado

Após o deploy, você terá um link tipo:
- `https://mimo-site-xxx.vercel.app` (Vercel)
- `https://mimo-site.netlify.app` (Netlify)
- `https://minhamimo.com.br` (se configurar domínio customizado)

**Este link pode ser compartilhado!**
