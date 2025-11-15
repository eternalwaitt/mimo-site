# Guia de Linting - Site Mimo

## Instalação

### PHP (PHP_CodeSniffer)

```bash
composer require --dev squizlabs/php_codesniffer
```

### JavaScript (ESLint)

```bash
npm install --save-dev eslint
```

### CSS (Stylelint)

```bash
npm install --save-dev stylelint stylelint-config-standard
```

## Uso

### PHP

```bash
# Verificar todos os arquivos
vendor/bin/phpcs --standard=phpcs.xml .

# Verificar arquivo específico
vendor/bin/phpcs --standard=phpcs.xml inc/form-security.php

# Auto-corrigir (quando possível)
vendor/bin/phpcbf --standard=phpcs.xml .
```

### JavaScript

```bash
# Verificar todos os arquivos JS
npx eslint js/ main.js

# Auto-corrigir
npx eslint --fix js/ main.js
```

### CSS

```bash
# Verificar todos os arquivos CSS
npx stylelint "**/*.css"

# Auto-corrigir
npx stylelint --fix "**/*.css"
```

## Configuração

- `.eslintrc.js` - Configuração ESLint
- `.stylelintrc.json` - Configuração Stylelint
- `phpcs.xml` - Configuração PHP_CodeSniffer

## Integração com Git (lint-staged)

Para executar linters automaticamente antes de commits:

```bash
npm install --save-dev lint-staged husky
npx husky install
npx husky add .husky/pre-commit "npx lint-staged"
```

## Regras Personalizadas

### PHP
- PSR-12 coding standard
- Linha máxima: 120 caracteres (warning), 150 (error)
- Permite TODO comments

### JavaScript
- ES6+ suportado
- jQuery como global
- Indentação: tabs
- Aspas: simples

### CSS
- Aviso para uso de `!important`
- Indentação: tabs
- Aspas: duplas
- Hex colors: lowercase, long format

