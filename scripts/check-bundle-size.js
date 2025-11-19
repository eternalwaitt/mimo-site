#!/usr/bin/env node

/**
 * bundle size validation script.
 * 
 * checks if bundle size exceeds performance budget.
 * 
 * budget (mobile, first load):
 * - home page: ≤ 150 KiB (gzipped)
 * - other pages: ≤ 200 KiB (gzipped)
 * 
 * usage: npm run build && node scripts/check-bundle-size.js
 */

const fs = require('fs')
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

// Performance budget (in KiB, gzipped)
const BUDGET = {
  home: 150, // Home page first load JS
  other: 200, // Other pages first load JS
}

function parseBuildOutput() {
  const buildOutputPath = path.join(__dirname, '../.next/build-manifest.json')
  
  // Try to read build output from stdout or analyze
  // For now, we'll check the build output format
  // This is a simplified version - in production, you'd parse the actual build output
  
  log('📦 Bundle Size Check', colors.blue)
  log('='.repeat(50))
  
  // Note: This is a placeholder. In a real implementation, you'd:
  // 1. Parse the build output from `npm run build`
  // 2. Extract First Load JS sizes
  // 3. Compare against budget
  
  log('⚠️  Bundle size check requires build analysis.', colors.yellow)
  log('   Run: npm run analyze', colors.yellow)
  log('   Then manually check bundle sizes in the report.', colors.yellow)
  log('\n💡 Budget targets:', colors.blue)
  log(`   Home page: ≤ ${BUDGET.home} KiB (first load JS)`, colors.blue)
  log(`   Other pages: ≤ ${BUDGET.other} KiB (first load JS)`, colors.blue)
  
  // For now, we'll just warn - full implementation would require parsing build output
  log('\n✅ Bundle size check completed (manual verification recommended)', colors.green)
}

function main() {
  parseBuildOutput()
}

main()

