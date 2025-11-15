/**
 * ESLint Configuration
 * 
 * Configuração para linting de JavaScript
 * 
 * INSTALAÇÃO:
 * npm install --save-dev eslint
 * 
 * USO:
 * npx eslint js/ main.js
 */

module.exports = {
	env: {
		browser: true,
		jquery: true,
		es6: true
	},
	extends: 'eslint:recommended',
	parserOptions: {
		ecmaVersion: 2018,
		sourceType: 'module'
	},
	rules: {
		'indent': ['error', 'tab'],
		'linebreak-style': ['error', 'unix'],
		'quotes': ['error', 'single'],
		'semi': ['error', 'always'],
		'no-unused-vars': ['warn'],
		'no-console': ['warn'],
		'no-undef': ['error'],
		'strict': ['error', 'never']
	},
	globals: {
		'$': 'readonly',
		'jQuery': 'readonly'
	}
};

