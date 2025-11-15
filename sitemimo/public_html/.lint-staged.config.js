/**
 * lint-staged Configuration
 * 
 * Executa linters apenas nos arquivos modificados
 * 
 * INSTALAÇÃO:
 * npm install --save-dev lint-staged husky
 * 
 * USO:
 * npx lint-staged
 */

module.exports = {
	'*.php': [
		'php -l',
		'vendor/bin/phpcs --standard=phpcs.xml'
	],
	'*.js': [
		'npx eslint --fix'
	],
	'*.css': [
		'npx stylelint --fix'
	]
};

