# Guia de Deploy no Vercel - Site Mimo

## Método 1: Via Interface Web (Mais Fácil) 🌐

### Passo 1: Preparar o Repositório

1. **Certifique-se que o código está no GitHub**:
   ```bash
   cd mimo-site
   git init  # se ainda não tiver git
   git add .
   git commit -m "feat: site mimo"
   git remote add origin SEU_REPOSITORIO_GITHUB
   git push -u origin main
   ```

### Passo 2: Criar Conta no Vercel

1. Acesse https://vercel.com
2. Clique em **"Sign Up"**
3. Escolha **"Continue with GitHub"** (recomendado)
4. Autorize o Vercel a acessar seus repositórios

### Passo 3: Fazer Deploy

1. No dashboard do Vercel, clique em **"Add New Project"** ou **"New Project"**
2. Selecione o repositório `mimo-site`
3. Na tela de configuração:
   - **Framework Preset**: Next.js (deve detectar automaticamente)
   - **Root Directory**: Deixe vazio (raiz do repositório)
   - **Build Command**: `npm run build` (já vem preenchido)
   - **Output Directory**: `.next` (já vem preenchido)
   - **Install Command**: `npm install` (já vem preenchido)
4. Clique em **"Deploy"**

### Passo 4: Aguardar Build

- O Vercel vai instalar dependências e fazer build
- Leva cerca de 2-5 minutos
- Você verá o progresso em tempo real

### Passo 5: Acessar o Site

- Quando terminar, você receberá um link tipo: `mimo-site-xxx.vercel.app`
- Este link já está funcionando e pode compartilhar!

---

## Método 2: Via CLI (Terminal) 💻

### Passo 1: Instalar Vercel CLI

```bash
npm i -g vercel
```

### Passo 2: Fazer Login

```bash
cd mimo-site
vercel login
```

Isso vai abrir o navegador para você fazer login.

### Passo 3: Fazer Deploy

```bash
vercel
```

O CLI vai fazer algumas perguntas:
- **Set up and deploy?** → `Y`
- **Which scope?** → Selecione sua conta
- **Link to existing project?** → `N` (primeira vez)
- **What's your project's name?** → `mimo-site` (ou deixe o padrão)
- **In which directory is your code located?** → `./` (pressione Enter)

### Passo 4: Deploy em Produção

Para fazer deploy em produção (com link permanente):

```bash
vercel --prod
```

---

## Configurações Importantes

### Variáveis de Ambiente (se necessário)

Se precisar de variáveis de ambiente:
1. No dashboard Vercel, vá em **Settings** → **Environment Variables**
2. Adicione as variáveis necessárias
3. Faça redeploy

### Domínio Customizado (Opcional)

Para usar um domínio customizado:

1. No dashboard, vá em **Settings** → **Domains**
2. Clique em **"Add Domain"**
3. Digite: `minhamimo.com.br` e `www.minhamimo.com.br`
4. Siga as instruções para configurar DNS:
   - Adicione um registro CNAME no painel da Locaweb
   - Aponte para: `cname.vercel-dns.com`

---

## Atualizações Futuras

### Deploy Automático

- Toda vez que você fizer `git push` para a branch `main`, o Vercel faz deploy automático
- Você pode desabilitar isso em **Settings** → **Git**

### Deploy Manual

Se quiser fazer deploy manual:
- Via web: **Deployments** → **Redeploy**
- Via CLI: `vercel --prod`

---

## Troubleshooting

### Erro: "Build Failed"

1. Verifique os logs no Vercel
2. Teste localmente primeiro: `npm run build`
3. Verifique se todas as dependências estão no `package.json`

### Erro: "Module not found"

- Certifique-se que `node_modules` não está no `.gitignore` (não deve estar)
- O Vercel instala automaticamente, mas verifique se não há dependências faltando

### Imagens não aparecem

- Verifique se as imagens estão em `public/images/`
- Use caminhos relativos: `/images/nome.webp`

---

## Link Gerado

Após o deploy, você terá um link tipo:
- `https://mimo-site-xxx.vercel.app`
- Ou se configurar domínio: `https://minhamimo.com.br`

**Este link pode ser compartilhado!** 🎉

