<?php
/**
 * Testes Automatizados para Formulários
 * 
 * Testa validações e segurança dos formulários de contato
 * 
 * INSTALAÇÃO:
 * composer require --dev phpunit/phpunit
 * 
 * USO:
 * vendor/bin/phpunit tests/FormValidationTest.php
 */

require_once __DIR__ . '/../inc/form-security.php';

class FormValidationTest extends PHPUnit\Framework\TestCase
{
	/**
	 * Testa validação de email válido
	 */
	public function testValidateEmailValid()
	{
		$this->assertTrue(validate_email('test@example.com'));
		$this->assertTrue(validate_email('user.name@domain.co.uk'));
	}
	
	/**
	 * Testa validação de email inválido
	 */
	public function testValidateEmailInvalid()
	{
		$this->assertFalse(validate_email('invalid-email'));
		$this->assertFalse(validate_email('@example.com'));
		$this->assertFalse(validate_email('test@'));
	}
	
	/**
	 * Testa validação de nome válido
	 */
	public function testValidateNameValid()
	{
		$this->assertTrue(validate_name('João Silva'));
		$this->assertTrue(validate_name('Maria'));
		$this->assertTrue(validate_name(str_repeat('a', 100))); // Max length
	}
	
	/**
	 * Testa validação de nome inválido
	 */
	public function testValidateNameInvalid()
	{
		$this->assertFalse(validate_name('A')); // Muito curto
		$this->assertFalse(validate_name('')); // Vazio
		$this->assertFalse(validate_name(str_repeat('a', 101))); // Muito longo
	}
	
	/**
	 * Testa validação de mensagem válida
	 */
	public function testValidateMessageValid()
	{
		$this->assertTrue(validate_message('Esta é uma mensagem válida com mais de 10 caracteres'));
		$this->assertTrue(validate_message(str_repeat('a', 2000))); // Max length
	}
	
	/**
	 * Testa validação de mensagem inválida
	 */
	public function testValidateMessageInvalid()
	{
		$this->assertFalse(validate_message('Curta')); // Muito curta
		$this->assertFalse(validate_message('')); // Vazia
		$this->assertFalse(validate_message(str_repeat('a', 2001))); // Muito longa
	}
	
	/**
	 * Testa validação de assunto válido
	 */
	public function testValidateSubjectValid()
	{
		$this->assertTrue(validate_subject('Dúvidas'));
		$this->assertTrue(validate_subject('Agradecimentos/Depoimentos'));
		$this->assertTrue(validate_subject('Outro'));
	}
	
	/**
	 * Testa validação de assunto inválido
	 */
	public function testValidateSubjectInvalid()
	{
		$this->assertFalse(validate_subject('Assunto Inválido'));
		$this->assertFalse(validate_subject(''));
	}
	
	/**
	 * Testa detecção de palavras de spam
	 */
	public function testContainsSpamKeywords()
	{
		$this->assertTrue(contains_spam_keywords('Quer comprar viagra?'));
		$this->assertTrue(contains_spam_keywords('Jogue no casino online'));
		$this->assertTrue(contains_spam_keywords('Empréstimo rápido'));
		$this->assertFalse(contains_spam_keywords('Mensagem normal sem spam'));
	}
	
	/**
	 * Testa sanitização de HTML
	 */
	public function testSanitizeHtml()
	{
		$input = '<script>alert("xss")</script>Teste';
		$output = sanitize_html($input);
		$this->assertStringNotContainsString('<script>', $output);
		$this->assertStringContainsString('Teste', $output);
	}
	
	/**
	 * Testa rate limiting
	 */
	public function testRateLimit()
	{
		// Limpar sessão antes do teste
		if (session_status() === PHP_SESSION_ACTIVE) {
			session_destroy();
		}
		
		// Primeira tentativa deve ser permitida
		$result1 = check_rate_limit(3, 3600);
		$this->assertTrue($result1['allowed']);
		$this->assertEquals(2, $result1['remaining']);
		
		// Simular múltiplas tentativas
		$_SESSION = [];
		$ip = '127.0.0.1';
		$sessionKey = 'form_attempts_' . md5($ip);
		$_SESSION[$sessionKey] = ['count' => 3, 'first_attempt' => time()];
		
		// Após 3 tentativas, deve bloquear
		$result2 = check_rate_limit(3, 3600);
		$this->assertFalse($result2['allowed']);
		$this->assertEquals(0, $result2['remaining']);
	}
}

