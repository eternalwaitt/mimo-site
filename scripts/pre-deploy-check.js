#!/usr/bin/env node

/**
 * pre-deploy validation script.
 * 
 * runs quick validations before pushing to ensure code quality:
 * - type check
 * - lint
 * - build (without deploying)
 * 
 * usage: npm run pre-deploy
 */

const { execSync } = require('child_process')
const path = require('path')

const colors = {
  reset: '\x1b[0m',
  green: '\x1b[32m',
  red: '\x1b[31m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
}

function log(message, color = colors.reset) {
  console.log(`${color}${message}${colors.reset}`)
}

function runCommand(command, description) {
  log(`\n${description}...`, colors.blue)
  try {
    execSync(command, { stdio: 'inherit', cwd: path.join(__dirname, '..') })
    log(`✅ ${description} passed`, colors.green)
    return true
  } catch (error) {
    log(`❌ ${description} failed`, colors.red)
    return false
  }
}

function main() {
  log('🚀 Pre-deploy Validation', colors.blue)
  log('=' .repeat(50))

  const checks = [
    { command: 'npm run type-check', description: 'Type check' },
    { command: 'npm run lint', description: 'Lint' },
    { command: 'npm run build', description: 'Build' },
  ]

  let allPassed = true

  for (const check of checks) {
    if (!runCommand(check.command, check.description)) {
      allPassed = false
      break // Stop on first failure
    }
  }

  log('\n' + '='.repeat(50))

  if (allPassed) {
    log('✅ All pre-deploy checks passed! Ready to push.', colors.green)
    process.exit(0)
  } else {
    log('❌ Pre-deploy checks failed. Please fix errors before pushing.', colors.red)
    log('\n💡 Tip: Run individual checks to see detailed errors:', colors.yellow)
    log('   npm run type-check', colors.yellow)
    log('   npm run lint', colors.yellow)
    log('   npm run build', colors.yellow)
    process.exit(1)
  }
}

main()

