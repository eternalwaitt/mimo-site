# Como Fazer Deploy no Vercel - Passo a Passo

## 🚀 Método Rápido (Interface Web)

### 1. Acesse o Vercel
- Vá em: https://vercel.com
- Clique em **"Sign Up"** (ou faça login se já tiver conta)
- Escolha **"Continue with GitHub"**

### 2. Conecte o Repositório
- No dashboard, clique em **"Add New Project"**
- Selecione o repositório `mimo-site`
- Se o repositório não aparecer, clique em **"Adjust GitHub App Permissions"** e dê permissão

### 3. Configure o Projeto
Na tela de configuração:
- **Framework Preset**: Deixe **Next.js** (detecta automaticamente)
- **Root Directory**: Deixe vazio (raiz do repositório)
- **Build Command**: `npm run build` (já vem preenchido)
- **Output Directory**: `.next` (já vem preenchido)
- **Install Command**: `npm install` (já vem preenchido)

### 4. Deploy!
- Clique no botão **"Deploy"**
- Aguarde 2-5 minutos enquanto faz build
- Quando terminar, você verá um link tipo: `mimo-site-xxx.vercel.app`

### 5. Pronto! 🎉
- O link já está funcionando
- Pode compartilhar
- Toda vez que fizer `git push`, atualiza automaticamente

---

## 💻 Método via Terminal (Alternativa)

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

---

## 📝 Checklist Antes de Deployar

- [ ] Código commitado no GitHub
- [ ] `npm run build` funciona localmente (teste antes!)
- [ ] Imagens estão em `public/images/`
- [ ] Fontes estão em `public/fonts/`

---

## 🔗 Depois do Deploy

Você terá um link tipo:
- `https://mimo-site-xxx.vercel.app`

**Este link pode ser compartilhado!**

### Atualizações Automáticas
- Toda vez que você fizer `git push` para `main`, o Vercel faz deploy automático
- Você recebe notificação por email quando termina

---

## ❓ Problemas Comuns

### Build falha
- Teste localmente primeiro: `npm run build`
- Verifique os logs no Vercel (clique no deployment que falhou)

### Imagens não aparecem
- Certifique-se que estão em `public/images/`
- Use caminhos que começam com `/images/...`

### Erro de módulo não encontrado
- Verifique se todas dependências estão no `package.json`
- O Vercel instala automaticamente, mas pode faltar alguma

---

## 🎯 Próximos Passos (Opcional)

### Domínio Customizado
Se quiser usar `minhamimo.com.br`:
1. No Vercel, vá em **Settings** → **Domains**
2. Adicione `minhamimo.com.br` e `www.minhamimo.com.br`
3. Configure DNS no painel da Locaweb apontando para o Vercel

