# Deploy Site Mimo v4

## Opção 1: Vercel (Recomendado - Link Próprio)

A forma mais fácil de mostrar para a designer é fazer deploy no Vercel. Você terá um link único tipo `sitemimo-v4.vercel.app` ou pode configurar um domínio customizado.

### Passo a Passo:

1. **Instalar Vercel CLI** (opcional, pode usar interface web):
```bash
npm i -g vercel
```

2. **Fazer deploy**:
```bash
cd sitemimo-v4
vercel
```

3. **Ou usar interface web**:
   - Acesse https://vercel.com
   - Conecte seu repositório GitHub
   - Selecione a pasta `sitemimo-v4`
   - Deploy automático!

4. **Link gerado**: Você receberá um link tipo `sitemimo-v4-xxx.vercel.app`

5. **Configurar domínio customizado** (opcional):
   - No painel Vercel, vá em Settings → Domains
   - Adicione `v4.minhamimo.com.br` ou `preview.minhamimo.com.br`
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
cd sitemimo-v4
npm run build
```

3. **Copiar arquivos**:
```bash
# A pasta out/ será criada com os arquivos estáticos
cp -r out/* /caminho/para/mimo-site/sitemimo/public_html/v4/
```

4. **Criar .htaccess** na pasta v4/:
```apache
RewriteEngine On
RewriteBase /v4/
RewriteRule ^index\.html$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /v4/index.html [L]
```

5. **Acessar**: `https://minhamimo.com.br/v4`

**Nota**: Build estático tem limitações (sem API routes, sem server-side rendering dinâmico).

## Opção 3: Netlify (Alternativa ao Vercel)

Similar ao Vercel:
1. Acesse https://netlify.com
2. Conecte repositório
3. Configure build: `npm run build` e publish: `.next`
4. Link gerado tipo `sitemimo-v4.netlify.app`

## Recomendação

**Use Vercel** - é mais fácil, rápido, e você tem um link único para compartilhar com a designer sem mexer no servidor atual.

