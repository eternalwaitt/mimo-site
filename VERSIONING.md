# Guia de Versionamento

Este documento explica o sistema de versionamento usado no projeto Mimo Site.

## Formato de Versão

O projeto usa [Semantic Versioning](https://semver.org/) (SemVer) com o formato:

```
MAJOR.MINOR.PATCH
```

Exemplo: `1.0.0`

## Componentes da Versão

### MAJOR (Versão Principal)
Incrementa quando há mudanças incompatíveis ou arquiteturais significativas.

**Exemplos:**
- Migração para novo framework
- Mudanças que quebram compatibilidade de API
- Refatoração arquitetural que requer mudanças em múltiplos arquivos

### MINOR (Versão Menor)
Incrementa quando há novas funcionalidades compatíveis com versões anteriores.

**Exemplos:**
- Adição de novas páginas ou seções
- Novos componentes reutilizáveis
- Novas funcionalidades (integrações, formulários, etc)
- Melhorias de performance sem breaking changes

### PATCH (Correção)
Incrementa quando há correções de bugs ou pequenas melhorias compatíveis.

**Exemplos:**
- Correção de bugs
- Correções de acessibilidade
- Ajustes de CSS/estilo
- Melhorias de performance menores
- Atualizações de documentação

## Localização da Versão

A versão é armazenada em dois lugares:

1. **`package.json`** - Campo `version` (usado pelo npm)
2. **`lib/version.ts`** - Constantes exportáveis (usado pelo app)

Ambos devem ser atualizados simultaneamente.

## Processo de Atualização de Versão

### 1. Determinar o Tipo de Mudança
- **Major**: Breaking changes ou mudanças arquiteturais
- **Minor**: Novas funcionalidades compatíveis
- **Patch**: Correções e melhorias

### 2. Atualizar Arquivos
Atualize os seguintes arquivos:

**`package.json`**:
```json
{
  "version": "1.1.0"
}
```

**`lib/version.ts`**:
```typescript
export const APP_VERSION = '1.1.0'
export const APP_VERSION_MAJOR = 1
export const APP_VERSION_MINOR = 1
export const APP_VERSION_PATCH = 0
export const BUILD_DATE = '2025-01-XX'
```

### 3. Atualizar CHANGELOG.md
Adicione uma nova seção no topo do arquivo seguindo o formato:

```markdown
## [1.1.0] - 2025-01-XX

### Added
- Nova funcionalidade X

### Changed
- Melhoria em Y

### Fixed
- Correção de bug Z
```

### 4. Commit e Tag
```bash
git add package.json lib/version.ts CHANGELOG.md
git commit -m "chore: bump version to 1.1.0"
git tag -a v1.1.0 -m "Version 1.1.0"
git push origin main --tags
```

## Script de Bump Automatizado

Um script `scripts/bump-version.js` pode ser criado para automatizar o processo (futuro).

## Exibição da Versão

A versão é incluída automaticamente em:
- Meta tag `generator` no HTML (app/layout.tsx)
- Opcionalmente no footer em ambiente de desenvolvimento

## Checklist de Release

Antes de fazer release de uma nova versão:

- [ ] Versão atualizada em `package.json`
- [ ] Versão atualizada em `lib/version.ts`
- [ ] `CHANGELOG.md` atualizado com todas as mudanças
- [ ] Testes locais executados (`npm run build`, `npm run type-check`)
- [ ] Lint passou (`npm run lint`)
- [ ] Commit criado com mensagem apropriada
- [ ] Tag git criada
- [ ] Push realizado com tags

## Histórico de Versões

Veja `CHANGELOG.md` para o histórico completo de versões.

