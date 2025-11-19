# Testes Automatizados - Site Mimo

## Instalação

```bash
# Instalar PHPUnit
composer require --dev phpunit/phpunit

# Ou via npm (se usar npm)
npm install --save-dev phpunit/phpunit
```

## Executar Testes

```bash
# Todos os testes
vendor/bin/phpunit tests/

# Teste específico
vendor/bin/phpunit tests/FormValidationTest.php

# Com cobertura de código
vendor/bin/phpunit --coverage-html coverage/ tests/
```

## Estrutura de Testes

- `FormValidationTest.php` - Testes de validação de formulários
- `README.md` - Este arquivo

## Adicionar Novos Testes

1. Criar arquivo `tests/SeuTesteTest.php`
2. Estender `PHPUnit\Framework\TestCase`
3. Adicionar métodos `test*()` para cada teste
4. Executar: `vendor/bin/phpunit tests/SeuTesteTest.php`

## Exemplo

```php
<?php
require_once __DIR__ . '/../inc/form-security.php';

class MeuTesteTest extends PHPUnit\Framework\TestCase
{
    public function testAlgo()
    {
        $this->assertTrue(true);
    }
}
```

