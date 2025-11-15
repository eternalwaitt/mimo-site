# Versioning Guide

This document explains the versioning system used in the Mimo Site project.

## Version Format

The project uses [Semantic Versioning](https://semver.org/) (SemVer) with the format:

```
MAJOR.MINOR.PATCH
```

Example: `2.0.0`

## Version Components

### MAJOR Version
Increment when you make incompatible API changes or major architectural changes.

**Examples:**
- Migrating to a new framework
- Breaking changes to the template system
- Major refactoring that requires code changes in service pages

### MINOR Version
Increment when you add functionality in a backwards-compatible manner.

**Examples:**
- Adding new features (social media integration, booking system)
- Adding new service pages
- New helper functions
- New build tools

### PATCH Version
Increment when you make backwards-compatible bug fixes.

**Examples:**
- Bug fixes
- Security patches
- CSS/JS fixes
- Documentation updates
- Performance improvements

## Version Constants

Version information is stored in `config.php`:

```php
define('APP_VERSION', '2.0.0');
define('APP_VERSION_MAJOR', 2);
define('APP_VERSION_MINOR', 0);
define('APP_VERSION_PATCH', 0);
```

These constants are available throughout the application.

## Asset Versioning

Separate from application versioning, asset versioning is used for cache busting:

```php
define('ASSET_VERSION', '20250119'); // Format: YYYYMMDD
```

**When to update `ASSET_VERSION`:**
- After modifying CSS files
- After modifying JavaScript files
- After adding/removing assets
- On every deployment (recommended)

**When NOT to update `ASSET_VERSION`:**
- Only PHP changes (no CSS/JS)
- Documentation updates
- Configuration changes

## Version Update Process

### 1. Determine Version Type
- **Major**: Breaking changes or major features
- **Minor**: New features, backwards-compatible
- **Patch**: Bug fixes, small improvements

### 2. Update Version Constants
Edit `config.php`:
```php
// For a minor release (2.0.0 → 2.1.0)
define('APP_VERSION', '2.1.0');
define('APP_VERSION_MAJOR', 2);
define('APP_VERSION_MINOR', 1);
define('APP_VERSION_PATCH', 0);
```

### 3. Update Changelog
Edit `CHANGELOG.md`:
- Add new version section
- List all changes (Added, Changed, Fixed, Removed, Security)
- Follow [Keep a Changelog](https://keepachangelog.com/) format

### 4. Update Asset Version (if needed)
If CSS/JS changed:
```php
define('ASSET_VERSION', '20250120'); // Today's date
```

### 5. Commit and Tag
```bash
git add config.php CHANGELOG.md
git commit -m "Bump version to 2.1.0"
git tag -a v2.1.0 -m "Version 2.1.0"
git push origin main --tags
```

## Version Display

The version is automatically included in HTML meta tags:
```html
<meta name="generator" content="Mimo Site v2.0.0">
```

This helps with:
- Debugging (knowing which version is deployed)
- Support requests
- Version tracking

## Release Checklist

Before releasing a new version:

- [ ] Update version constants in `config.php`
- [ ] Update `CHANGELOG.md` with all changes
- [ ] Update `ASSET_VERSION` if CSS/JS changed
- [ ] Test all changes locally
- [ ] Verify all service pages work
- [ ] Check responsive design
- [ ] Test contact form
- [ ] Review security headers
- [ ] Commit changes
- [ ] Create git tag
- [ ] Deploy to production
- [ ] Verify production deployment

## Version History

See `CHANGELOG.md` for detailed version history.

Quick reference:
- **2.3.9** (2025-01-25): Ajuste de layout dos testimonials, indicadores mais próximos, espaçamento do botão Google
- **2.3.8** (2025-01-24): Correção de sobreposição de texto nos testimonials
- **2.3.6** (2025-01-23): Animações on scroll, suporte AVIF, lazy loading otimizado, correção de caminho da fonte Akrobat
- **2.3.5** (2025-01-22): Dark mode para páginas de contato e outras, ícones SVG no footer, estilos dark mode para componentes genéricos
- **2.3.4** (2025-01-21): Fixed CSP issues, removed broken Tidio chat, fixed font loading, header layout fixes
- **2.3.1** (2025-01-15): Sprint 1 - Performance & Core Web Vitals, image optimization, critical CSS, Service Worker
- **2.3.0** (2025-01-15): Google Reviews system enhancement, smart photo detection, review filtering
- **2.2.9** (2025-01-14): Code documentation, code audit document, contact form improvements
- **2.2.8** (2025-11-14): Footer redesign, social media icons with inline SVG
- **2.2.7** (2025-01-20): Carousel optimization, layout shift fixes
- **2.2.6** (2025-01-19): 404 page improvements, blog post page, branding consistency
- **2.2.5** (2025-01-19): Performance optimizations (font-display, defer scripts), accessibility improvements, removed booking system references
- **2.2.4** (2025-01-19): Added vagas page (job listings) with full integration
- **2.2.3** (2025-01-19): Fixed form submission (removed WhatsApp redirect, now sends to email, AJAX, validation)
- **2.2.2** (2025-01-19): Font-display optimization, print styles, form loading states
- **2.2.1** (2025-01-19): Fixed image paths, header transparency, breadcrumb positioning
- **2.2.0** (2025-01-19): Cache headers, asset helper, minification, breadcrumbs, back-to-top button
- **2.1.0** (2025-01-19): SEO optimization, Schema.org structured data
- **2.0.0** (2025-01-19): Template system, WebP optimization, major refactoring
- **1.0.0** (2018-01-01): Initial release

## Best Practices

1. **Always update version before deployment**
2. **Keep changelog up-to-date** - document every change
3. **Use semantic versioning correctly** - don't skip versions
4. **Tag releases in git** - makes it easy to track versions
5. **Update asset version on CSS/JS changes** - ensures cache busting
6. **Test before version bump** - ensure everything works

## Examples

### Patch Release (Bug Fix)
```
2.0.0 → 2.0.1
- Fixed typo in service page
- Updated CSS for mobile layout
```

### Minor Release (New Feature)
```
2.0.0 → 2.1.0
- Added Instagram feed integration
- New booking widget
- Enhanced form validation
```

### Major Release (Breaking Changes)
```
2.0.0 → 3.0.0
- Migrated to Laravel framework
- Completely new template system
- Breaking changes to service page structure
```

---

For questions or clarifications, refer to:
- [Semantic Versioning Specification](https://semver.org/)
- [Keep a Changelog](https://keepachangelog.com/)

