# Cruzar Sinal - Integração PHP

Ferramenta para cruzar agendamentos com clientes que têm crédito ou débito, integrada ao site PHP antigo.

## Instalação

### 1. Instalar PhpSpreadsheet

No diretório `public_html`, execute:

```bash
composer require phpoffice/phpspreadsheet
```

Ou se não tiver composer instalado, baixe manualmente e coloque em `vendor/phpoffice/phpspreadsheet/`.

### 2. Permissões

Certifique-se de que os diretórios têm permissão de escrita:

```bash
chmod 755 cruzar-sinal-uploads
chmod 755 cruzar-sinal-outputs
```

## Uso

Acesse a página secreta:

```
https://minhamimo.com.br/cruzar-sinal-xyz123.php
```

## Estrutura

- `cruzar-sinal-xyz123.php` - Página principal
- `cruzar-sinal-download.php` - Endpoint de download
- `inc/cruzar-sinal/validacao.php` - Validação de arquivos Excel
- `inc/cruzar-sinal/cruzar-dados.php` - Lógica de cruzamento de dados

## Dependências

- PHP 7.1+
- PhpSpreadsheet (via Composer)
- Extensões PHP: zip, xml, gd (geralmente já instaladas)

