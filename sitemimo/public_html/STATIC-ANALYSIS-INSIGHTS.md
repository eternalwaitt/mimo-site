# Insights de Análise Estática - Aplicáveis ao Projeto

**Data**: 2025-11-16  
**Baseado em**: SwiftLint, static-analysis, awesome-guidelines

---

## 🎯 Conceitos Aplicáveis

### 1. Análise Estática para Performance

**Conceito do SwiftLint**: Análise estática que detecta problemas antes da execução.

**Aplicação no Projeto**:
- ✅ Já temos: PHP_CodeSniffer, ESLint, Stylelint
- ⚠️ **Faltando**: Ferramentas específicas para detectar problemas de performance

**Ferramentas Recomendadas**:

#### PHP
- **PHPStan** (já mencionado em PROXIMOS-PASSOS.md)
  - Detecta problemas de performance (queries N+1, loops ineficientes)
  - Nível recomendado: 5-6 (médio-alto)
  
- **Psalm** (alternativa ao PHPStan)
  - Foco em segurança e performance
  - Detecta problemas de memória

#### JavaScript
- **ESLint Performance Plugin** (eslint-plugin-perf)
  - Detecta loops ineficientes
  - Identifica operações custosas
  - Recomendado adicionar ao `.eslintrc.js`

- **Bundle Analyzer** (webpack-bundle-analyzer)
  - Já mencionado em PROXIMOS-PASSOS.md
  - Analisa tamanho de bundles JS

#### CSS
- **PurgeCSS** (já configurado em `purgecss.config.js`)
  - Remove CSS não utilizado
  - **Status**: ✅ Já implementado

- **Stylelint Performance Plugin**
  - Detecta propriedades CSS custosas
  - Identifica animações não otimizadas

---

## 🔧 Auto-Correção (Conceito do SwiftLint)

**Conceito**: Ferramentas que corrigem automaticamente problemas detectados.

**Aplicação**:
- ✅ Já temos: `phpcbf`, `eslint --fix`, `stylelint --fix`
- ⚠️ **Melhorar**: Adicionar regras de performance que podem ser auto-corrigidas

**Exemplos de Auto-Correção de Performance**:

### JavaScript
```javascript
// Antes (ineficiente)
for (let i = 0; i < array.length; i++) {
  // ...
}

// Depois (auto-corrigido)
for (let i = 0, len = array.length; i < len; i++) {
  // ...
}
```

### CSS
```css
/* Antes (causa reflow) */
.element {
  width: 100px;
  height: 100px;
}

/* Depois (otimizado) */
.element {
  width: 100px;
  height: 100px;
  contain: layout style; /* Auto-adicionado */
}
```

---

## 📋 Guidelines de Performance (awesome-guidelines)

### Web Performance Guidelines Aplicáveis

1. **Image Optimization**
   - ✅ Já implementado: WebP/AVIF, lazy loading
   - ⚠️ **Melhorar**: Adicionar validação automática de dimensões

2. **CSS Optimization**
   - ✅ Já implementado: PurgeCSS
   - ⚠️ **Melhorar**: Minificação automática (já temos scripts, mas pode ser automatizado)

3. **JavaScript Optimization**
   - ✅ Já implementado: Defer/async
   - ⚠️ **Melhorar**: Tree-shaking, code splitting

4. **Font Loading**
   - ✅ Já implementado: font-display: optional/swap
   - ✅ Já implementado: size-adjust

---

## 🚀 Ferramentas Adicionais Recomendadas

### 1. Lighthouse CI (Mencionado em PROXIMOS-PASSOS.md)

**Por que adicionar**:
- Monitoramento contínuo de performance
- Integração com CI/CD
- Detecta regressões automaticamente

**Implementação**:
```bash
npm install --save-dev @lhci/cli
```

### 2. Bundle Analyzer

**Por que adicionar**:
- Identifica JS/CSS grandes
- Mostra dependências pesadas
- Ajuda a decidir o que otimizar

**Implementação**:
```bash
npm install --save-dev webpack-bundle-analyzer
```

### 3. Performance Budget

**Conceito**: Definir limites máximos para recursos.

**Implementação**:
- Adicionar ao `package.json` ou criar `.performance-budget.json`
- Integrar com Lighthouse CI

---

## 🔍 Regras de Linting para Performance

### ESLint - Adicionar Regras de Performance

**Arquivo**: `.eslintrc.js`

```javascript
module.exports = {
  // ... configuração existente
  plugins: ['perf'],
  rules: {
    'perf/avoid-array-methods': 'warn',
    'perf/avoid-object-spread': 'warn',
    'no-await-in-loop': 'error', // Performance anti-pattern
  }
};
```

### Stylelint - Adicionar Regras de Performance

**Arquivo**: `.stylelintrc.json`

```json
{
  "rules": {
    "declaration-no-important": "warn",
    "property-no-vendor-prefix": "warn",
    "selector-max-specificity": ["warn", 3],
    "no-descending-specificity": "warn"
  }
}
```

---

## 📊 Análise Estática Específica para CLS

### Problemas que Análise Estática Pode Detectar

1. **Imagens sem width/height**
   - ✅ Já resolvido: `picture_webp()` detecta automaticamente
   - ⚠️ **Melhorar**: Adicionar validação no linting

2. **CSS sem contain: layout**
   - ⚠️ **Adicionar**: Regra Stylelint para containers críticos

3. **Fontes sem font-display**
   - ✅ Já resolvido: font-display configurado
   - ⚠️ **Melhorar**: Validação automática

4. **JavaScript causando reflow**
   - ⚠️ **Adicionar**: Regra ESLint para detectar `offsetWidth`, `offsetHeight`, etc.

---

## 🎯 Plano de Ação

### Curto Prazo (Esta Sprint)

1. **Adicionar ESLint Performance Plugin**
   ```bash
   npm install --save-dev eslint-plugin-perf
   ```
   - Adicionar regras ao `.eslintrc.js`
   - Executar e corrigir problemas encontrados

2. **Adicionar Validação de Imagens**
   - Criar script PHP que valida todas as imagens têm width/height
   - Adicionar ao lint-staged

3. **Adicionar Regras Stylelint para Performance**
   - Atualizar `.stylelintrc.json`
   - Adicionar regras para detectar CSS problemático

### Médio Prazo (Próxima Sprint)

4. **PHPStan Level 5**
   - Instalar PHPStan
   - Configurar para detectar problemas de performance
   - Integrar com CI/CD

5. **Lighthouse CI**
   - Configurar monitoramento contínuo
   - Definir performance budgets
   - Alertas para regressões

### Longo Prazo

6. **Bundle Analyzer**
   - Analisar bundles JS/CSS
   - Identificar oportunidades de code splitting
   - Otimizar dependências pesadas

---

## 📝 Checklist de Implementação

### ESLint Performance
- [ ] Instalar `eslint-plugin-perf`
- [ ] Adicionar regras ao `.eslintrc.js`
- [ ] Executar e corrigir problemas
- [ ] Adicionar ao lint-staged

### Stylelint Performance
- [ ] Adicionar regras de performance ao `.stylelintrc.json`
- [ ] Executar e corrigir problemas
- [ ] Adicionar ao lint-staged

### PHPStan
- [ ] Instalar PHPStan
- [ ] Configurar nível 5
- [ ] Adicionar regras de performance
- [ ] Integrar com CI/CD

### Validação de Imagens
- [ ] Criar script de validação
- [ ] Adicionar ao lint-staged
- [ ] Executar em todos os arquivos

### Lighthouse CI
- [ ] Instalar @lhci/cli
- [ ] Configurar performance budgets
- [ ] Integrar com CI/CD
- [ ] Configurar alertas

---

## 🔗 Referências

- [SwiftLint](https://github.com/realm/SwiftLint) - Conceitos de análise estática
- [static-analysis](https://github.com/analysis-tools-dev/static-analysis) - Lista de ferramentas
- [awesome-guidelines](https://github.com/Kristories/awesome-guidelines) - Guidelines de qualidade
- [ESLint Performance Plugin](https://github.com/kirill-konshin/eslint-plugin-perf)
- [PHPStan](https://phpstan.org/)
- [Lighthouse CI](https://github.com/GoogleChrome/lighthouse-ci)

---

## 💡 Conclusão

Os repositórios analisados fornecem conceitos valiosos:

1. **Análise Estática Proativa**: Detectar problemas antes de deploy
2. **Auto-Correção**: Corrigir automaticamente quando possível
3. **Guidelines Estruturadas**: Seguir melhores práticas consistentemente
4. **Monitoramento Contínuo**: Lighthouse CI para detectar regressões

**Próximo Passo**: Implementar ESLint Performance Plugin e validação de imagens (curto prazo).

---

## 🔧 Aplicação Imediata - Regras de Performance

### ESLint - Adicionar Regras de Performance

**Arquivo**: `.eslintrc.js`

Adicionar ao `rules`:
```javascript
rules: {
  // ... regras existentes
  'no-await-in-loop': 'error', // Performance anti-pattern
  'no-loop-func': 'warn', // Evita closures em loops
  'prefer-const': 'error', // Evita reatribuições desnecessárias
}
```

### Stylelint - Adicionar Regras de Performance

**Arquivo**: `.stylelintrc.json`

Adicionar regras para detectar:
- Propriedades que causam reflow (width, height sem contain)
- Animações não otimizadas
- Uso excessivo de !important

### Validação de Imagens - Script PHP

Criar script que valida:
- Todas as imagens têm width/height
- Todas as imagens usam picture_webp()
- Lazy loading configurado corretamente

