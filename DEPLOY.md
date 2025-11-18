# Deploy Site Mimo

## Opção 1: Vercel (Recomendado - Link Próprio)

A forma mais fácil de fazer deploy é usar o Vercel. Você terá um link único tipo `mimo-site.vercel.app` ou pode configurar um domínio customizado.

### Passo a Passo:

1. **Instalar Vercel CLI** (opcional, pode usar interface web):
```bash
npm i -g vercel
```

2. **Fazer deploy**:
```bash
cd mimo-site
vercel
```

3. **Ou usar interface web**:
   - Acesse https://vercel.com
   - Conecte seu repositório GitHub
   - Selecione o repositório `mimo-site`
   - Deploy automático!

4. **Link gerado**: Você receberá um link tipo `mimo-site-xxx.vercel.app`

5. **Configurar domínio customizado** (opcional):
   - No painel Vercel, vá em Settings → Domains
   - Adicione `minhamimo.com.br` ou `www.minhamimo.com.br`
   - Configure DNS no painel da Locaweb apontando para o Vercel

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

## Opção 3: Netlify (Alternativa ao Vercel)

Similar ao Vercel:
1. Acesse https://netlify.com
2. Conecte repositório
3. Configure build: `npm run build` e publish: `.next`
4. Link gerado tipo `mimo-site.netlify.app`

## Recomendação

**Use Vercel** - é mais fácil, rápido, e você tem um link único para compartilhar com a designer sem mexer no servidor atual.

