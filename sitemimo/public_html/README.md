# Mimo Site - Codebase Documentation

## Overview

Mimo Site is a PHP-based website for a beauty and aesthetics center. The site features a homepage with contact form, service pages for different treatment categories, and a responsive design using Bootstrap 4.

## Architecture

### Technology Stack
- **Backend**: PHP 7.1+ (production), PHP 8.4+ (development)
- **Frontend**: Bootstrap 4.5.2, jQuery 3.3.1, Custom CSS/JS
- **Email**: PHPMailer with Mailgun SMTP
- **Image Optimization**: WebP format with automatic fallbacks via `<picture>` elements
- **Build Tools**: Shell scripts for WebP conversion, CSS/JS minification
- **Deployment**: FTP/SFTP to shared hosting
- **Template System**: PHP-based service page template to reduce code duplication
- **SEO**: Schema.org structured data, Open Graph, Twitter Cards, sitemap.xml, robots.txt
- **Reviews System**: Hybrid Google Reviews (API + manual reviews) with optimized carousel display

### Directory Structure

```
public_html/
├── index.php                    # Homepage with contact form
├── config.php                   # Configuration and environment variables
├── product.css                  # Main stylesheet
├── servicos.css                 # Service-specific styles
├── main.js                      # Main JavaScript functionality
├── inc/                         # Shared includes
│   ├── header.php               # Homepage navigation
│   ├── header-inner.php         # Service page navigation
│   ├── gtm-head.php            # Google Tag Manager (head)
│   ├── gtm-body.php            # Google Tag Manager (body)
│   ├── security-headers.php    # Security HTTP headers
│   ├── image-helper.php        # WebP image helper functions
│   ├── service-template.php    # Service page template
│   ├── critical-css.php        # Above-the-fold CSS
│   └── seo-helper.php          # SEO helper functions (meta tags, Schema.org)
├── cilios/                      # Cílios e Design service page
├── esmalteria/                  # Esmalteria service page
├── estetica/                    # Estética Corporal service page
├── esteticafacial/              # Estética Facial service page
├── micropigmentacao/            # Micropigmentação service page
├── salao/                       # Salão service page
├── form/                        # Contact form assets (CSS/JS)
├── bootstrap/                   # Local Bootstrap/jQuery (fallback)
├── build/                       # Build scripts
│   ├── convert-webp.sh         # WebP conversion script
│   ├── minify-css.sh           # CSS minification script
│   └── minify-js.sh            # JS minification script
├── vendor/                      # Composer dependencies
│   ├── phpmailer/              # PHPMailer library
│   └── sendgrid/               # SendGrid library (legacy)
└── x6f7689/                     # Mailgun credentials (legacy - use .env instead)
```

## CSS Architecture

### Main Stylesheets

1. **product.css** - Global styles, layout, navigation, typography
   - Linked on all pages
   - Cache-busted with version parameter

2. **servicos.css** - Service page specific styles
   - Header backgrounds for each service category
   - Service image classes
   - Responsive breakpoints
   - Linked on all service pages

3. **form/main.css** - Contact form styles
   - Form validation styles
   - Input/textarea styling
   - Button styles

### CSS Linking Pattern

#### Homepage (`index.php`)
```html
<!-- Bootstrap from CDN -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Main CSS with cache busting -->
<link href="product.css?<?php echo ASSET_VERSION; ?>" rel="stylesheet">
<!-- Form CSS -->
<link href="form/main.css" rel="stylesheet">
```

#### Service Pages
```html
<!-- Bootstrap from CDN -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Main CSS with cache busting -->
<link href="../product.css?<?php echo ASSET_VERSION; ?>" rel="stylesheet">
<!-- Service CSS with cache busting -->
<link href="../servicos.css?<?php echo ASSET_VERSION; ?>" rel="stylesheet">
<!-- Form CSS -->
<link href="../form/main.css" rel="stylesheet">
```

### Key CSS Classes

- `.service-content` - Responsive layout for service page content (replaces inline `margin-left: 240px`)
- `.textPink` - Brand pink color (#ccb7bc)
- `.textDarkGrey` - Dark grey text (#3a505a)
- `.backgroundGrey` - Grey background (#ccb7bc)
- `.backgroundPink` - Pink background (#3a505a)
- Service header classes: `.micro-header`, `.esmal-header`, `.salao-header`, `.corporal-header`, `.cilios-header`, `.facial-header`

## Navigation Structure

### Homepage Navigation (`inc/header.php`)
- **Logo**: Links to `/` (homepage)
- **Menu Items**:
  - HOME → `/`
  - SOBRE → `#about` (anchor)
  - SERVIÇOS → `#services` (anchor)
  - CONTATO → `#contact` (anchor)
  - FAQ → `/faq/`
  - WHATSAPP → External link

### Service Page Navigation (`inc/header-inner.php`)
- **Logo**: Links to `../` (homepage)
- **Menu Items**: Same as homepage but with `../` prefix for relative paths

### Service Page Internal Navigation
Each service page uses Bootstrap pills for tabbed navigation:
- **Cílios**: Design de Sobrancelha, Mimo Lash Lift, Combos
- **Esmalteria**: Alongamentos, Blindagem, Manicure & Pedicure
- **Estética**: Aparelhos, Massagem
- **Estética Facial**: Limpeza de Pele, Microagulhamento, Mimo Cuidados
- **Micropigmentação**: Sobrancelhas, Lábios, Despigmentação
- **Salão**: Mimo Colors, Mimo All Salon, Mimo Alisa, Mimo Mega Hair, Let Coesta

## JavaScript Structure

### Main Scripts

1. **main.js** - Core functionality
   - Navbar scroll behavior (compression on scroll)
   - Carousel swipe support
   - Smooth scrolling
   - Mobile menu handling
   - Form validation (WhatsApp redirect)

2. **form/main.js** - Form validation
   - Input validation
   - Email format validation
   - Visual error indicators

### External Scripts
- jQuery 3.3.1 (CDN with local fallback)
- Bootstrap 4.5.2 JS (local files)
- Tidio chat widget
- Google Tag Manager

## Configuration

### Environment Variables

Create a `.env` file in `public_html/` directory (copy from `.env.example`):

```env
# Environment (development, staging, production)
# Em development, emails são salvos em arquivo ao invés de serem enviados
APP_ENV=development

# Mailgun SMTP Credentials (necessário apenas em produção)
MAILGUN_USERNAME=your_mailgun_username_here
MAILGUN_PASSWORD=your_mailgun_password_here

# Site Configuration
SITE_URL=https://minhamimo.com.br
```

**Modos de Operação:**
- **Development** (`APP_ENV=development`): Emails são salvos em `dev-emails/` ao invés de serem enviados
- **Production** (`APP_ENV=production` ou não definido): Emails são enviados via SMTP usando credenciais do Mailgun

The `config.php` file loads these variables and provides fallback to the legacy credentials file for backward compatibility.

### Asset Versioning

Cache busting is handled via `ASSET_VERSION` constant in `config.php`. Update this value when deploying CSS/JS changes.

## Security

### Security Headers
All pages include security headers via `inc/security-headers.php`:
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block
- Referrer-Policy: strict-origin-when-cross-origin
- Content-Security-Policy (configured for site needs)
- Permissions-Policy

### Input Sanitization
- Uses `FILTER_SANITIZE_FULL_SPECIAL_CHARS` (PHP 8.1+ compatible)
- Email validation via `FILTER_SANITIZE_EMAIL`

## Deployment

### FTP/SFTP Deployment
1. Update `ASSET_VERSION` in `config.php` if CSS/JS changed
2. Create/update `.env` file on server with production credentials
3. Upload files via SFTP (preferred) or FTP
4. Ensure `.env` file is not publicly accessible (should be in `.htaccess` deny list)

### File Permissions
- PHP files: 644
- Directories: 755
- `.env` file: 600 (restricted access)

## Development

### Local Development
```bash
cd sitemimo/public_html
php -S localhost:8000
```

### Testing Checklist
- [ ] All service pages load correctly
- [ ] Navigation links work
- [ ] Contact form submits successfully
- [ ] Responsive design works on mobile
- [ ] Security headers are present
- [ ] No console errors

## Known Issues & Future Improvements

### Completed
- ✅ Removed `phpinfo()` from test file
- ✅ Moved credentials to environment variables
- ✅ Fixed deprecated `FILTER_SANITIZE_STRING`
- ✅ Removed deprecated `E_STRICT` constant (PHP 8.4 compatibility)
- ✅ Standardized Bootstrap loading (CDN)
- ✅ Implemented consistent cache busting
- ✅ Added security headers
- ✅ Fixed responsive layout issues
- ✅ Removed unused files
- ✅ Created comprehensive documentation
- ✅ Implemented template system for service pages (all 6 service pages migrated)
- ✅ Converted images to WebP format with fallbacks
- ✅ Created image helper functions for WebP support
- ✅ Updated homepage to use WebP images
- ✅ Applied WebP optimization to all service pages
- ✅ Implemented lazy loading for images
- ✅ Added resource hints (DNS prefetch, preconnect, preload)
- ✅ Created critical CSS for above-the-fold content
- ✅ Complete SEO optimization (meta tags, Open Graph, Twitter Cards, Schema.org)
- ✅ Created sitemap.xml and robots.txt
- ✅ Implemented canonical URLs
- ✅ Added structured data (LocalBusiness, Service, BreadcrumbList)
- ✅ Implemented cache headers system for static assets
- ✅ Created asset helper with automatic minification support
- ✅ Activated CSS/JS minification in production
- ✅ Added "Back to Top" button component
- ✅ Implemented breadcrumbs with Schema.org structured data
- ✅ Optimized testimonials carousel (v2.2.7)
  - Reduced height from 650px to 550px for more compact design
  - Fixed layout shift issues during transitions
  - All cards use consistent `position: absolute` for smooth transitions
  - Improved spacing and font sizes for better space utilization
- ✅ Hybrid Google Reviews system (API + manual reviews)
  - Automatic fetching from Google Places API
  - Manual reviews for quality control
  - Prioritized display (photos, 5-star, longer texts, older dates)
  - Optimized carousel with fixed heights to prevent layout shifts

### Recommended Future Improvements

#### High Priority (Performance & User Experience)
1. **Image Optimization**
   - [x] Convert images to WebP format with fallbacks
   - [x] Implement lazy loading for images
   - [x] Add responsive image srcsets (via picture_webp helper)
   - [x] Applied WebP to all service pages
   - [ ] Optimize existing images (compress without quality loss)
   - [ ] Add responsive srcsets for different screen sizes (1x, 2x, etc.)

2. **Asset Optimization**
   - [x] Minify CSS/JS for production
   - [x] Asset helper system with automatic minification detection
   - [x] Cache headers for static assets
   - [ ] Combine and concatenate CSS files where possible
   - [x] Implement critical CSS inlining
   - [x] Add resource hints (preload, prefetch, dns-prefetch)

3. **Template System Implementation**
   - [x] Migrate service pages to use `inc/service-template.php`
   - [x] Reduce code duplication across service pages
   - [ ] Create reusable component system for common UI elements

#### Medium Priority (Features & Integration)
4. **Social Media Integration**
   - [ ] **Instagram Feed Integration**: Use [instagram-php-scraper](https://github.com/postaddictme/instagram-php-scraper) to display recent Instagram posts
     - Display latest posts from @minhamimo account
     - Show before/after treatment photos
     - Embed Instagram stories (if available)
     - Cache Instagram data to reduce API calls
   - [ ] **Multi-Platform Scraper**: Consider [Ultimate-Social-Scrapers](https://github.com/harismuneer/Ultimate-Social-Scrapers) for broader social media integration
     - Support for Instagram, Facebook, Twitter, TikTok
     - Unified API for multiple platforms
     - Better error handling and rate limiting
   - [ ] Implement social media feed widget on homepage
   - [ ] Add social proof section with recent Instagram posts

5. **Content Management**
   - [ ] Create admin panel for updating service prices/content
   - [ ] Implement content versioning
   - [ ] Add image upload interface for service photos

#### Low Priority (Infrastructure & Modernization)
6. **Testing & Quality Assurance**
   - [ ] Add automated testing (PHPUnit)
   - [ ] Implement browser testing (Selenium/Playwright)
   - [ ] Set up code quality checks (PHPStan, PHP CS Fixer)

7. **CI/CD & Deployment**
   - [ ] Set up CI/CD pipeline (GitHub Actions)
   - [ ] Automated deployment via SFTP/SSH
   - [ ] Staging environment setup
   - [ ] Automated backup before deployments

8. **Framework Migration (Optional)**
   - [ ] Consider migrating to modern PHP framework (Laravel, Symfony)
   - [ ] Implement proper MVC architecture
   - [ ] Add dependency injection container
   - [ ] Implement proper routing system

#### Social Media Integration Details

**Instagram PHP Scraper** ([github.com/postaddictme/instagram-php-scraper](https://github.com/postaddictme/instagram-php-scraper))
- **Use Case**: Display Instagram feed on homepage, showcase before/after photos
- **Features**:
  - Get account information, photos, videos, stories
  - No Instagram API approval needed (uses web scraping)
  - Session caching support
  - Proxy support for rate limiting
- **Implementation Notes**:
  - Requires authentication for private accounts
  - Cache results to avoid rate limiting
  - Handle Instagram's anti-scraping measures
  - Consider using official Instagram Basic Display API if available

**Ultimate Social Scrapers** ([github.com/harismuneer/Ultimate-Social-Scrapers](https://github.com/harismuneer/Ultimate-Social-Scrapers))
- **Use Case**: Multi-platform social media integration
- **Features**:
  - Support for Instagram, Facebook, Twitter, TikTok, YouTube
  - Unified interface for multiple platforms
  - Better error handling
  - Rate limiting built-in
- **Implementation Notes**:
  - More comprehensive than single-platform scrapers
  - Better for long-term maintenance
  - Requires evaluation of platform-specific terms of service
  - May need proxy rotation for production use

**Legal & Ethical Considerations**:
- Review Instagram/Facebook Terms of Service before implementation
- Implement rate limiting to avoid IP bans
- Cache data appropriately to reduce requests
- Consider official APIs when available
- Respect user privacy and data protection laws (LGPD in Brazil)

## Development Workflow

### Adding New Images
1. Add image file (JPG/PNG) to appropriate directory
2. Run WebP conversion: `./build/convert-webp.sh 85 [directory]`
3. Use `picture_webp()` helper in PHP code
4. Test in browser to verify WebP loading

### Adding New Service Page
1. Create new directory (e.g., `newservice/`)
2. Create `index.php` with service configuration:
   ```php
   <?php
   require_once '../inc/image-helper.php';
   $serviceName = 'Service Name';
   $headerClass = 'service-header';
   $headerTitle = 'SERVICE TITLE';
   $tabs = [/* tab definitions */];
   // Define tab content using ob_start()/ob_get_clean()
   include '../inc/service-template.php';
   ```
3. Add header CSS class to `servicos.css`
4. Add service to homepage navigation

### Updating Content
- Service pages: Edit individual `index.php` files in service directories (now using `inc/service-template.php`)
- Navigation: Edit `inc/header.php` or `inc/header-inner.php`
- Styles: Edit `product.css` or `servicos.css`
- Images: Use `picture_webp()` helper, convert to WebP after adding

### Build Process
1. **Before deployment:**
   - Update `ASSET_VERSION` in `config.php`
   - Run WebP conversion for new images
   - (Optional) Run minification scripts if `USE_MINIFIED` is enabled
   - Test locally
2. **Deployment:**
   - Upload files via SFTP
   - Ensure `.env` file exists on server
   - Verify file permissions

### Image Optimization

#### Converting Images to WebP
Use the provided script to convert images:
```bash
cd sitemimo/public_html
./build/convert-webp.sh [quality] [directory]
# Example: ./build/convert-webp.sh 85 img
```

Quality: 0-100 (default: 85). Higher quality = larger file size.

#### Using WebP Images in Code
Use the `picture_webp()` helper function:
```php
<?php require_once 'inc/image-helper.php'; ?>
<?php echo picture_webp('img/example.png', 'Alt text', 'css-class'); ?>
```

The function automatically:
- Checks if WebP version exists
- Generates `<picture>` element with WebP source and fallback
- Includes lazy loading by default
- Preserves all attributes (class, style, etc.)
- Remember to update `ASSET_VERSION` after CSS/JS changes

### Backup Strategy
- Daily automated backups recommended
- Backup `.env` file separately (contains sensitive data)
- Version control via Git (exclude `.env` and `vendor/`)

## Versioning

This project uses [Semantic Versioning](https://semver.org/) (MAJOR.MINOR.PATCH).

- **Current Version**: See `APP_VERSION` constant in `config.php`
- **Changelog**: See `CHANGELOG.md` for detailed version history
- **Version Constants**: Available in `config.php`:
  - `APP_VERSION` - Full version string (e.g., "2.0.0")
  - `APP_VERSION_MAJOR` - Major version number
  - `APP_VERSION_MINOR` - Minor version number
  - `APP_VERSION_PATCH` - Patch version number

### Version Update Workflow
1. Update version constants in `config.php`
2. Update `CHANGELOG.md` with changes
3. Update `ASSET_VERSION` if CSS/JS changed
4. Commit with version tag: `git tag v2.0.0`
5. Deploy to production

## Additional Documentation

- **AI Development Guide**: See `AI-DEVELOPMENT-GUIDE.md` - **MASTER DOCUMENTATION** for IAs and developers (start here!)
- **Changelog**: See `CHANGELOG.md` for version history and changes
- **Versioning Guide**: See `VERSIONING.md` for versioning system documentation
- **Future Improvements**: See `IMPROVEMENTS.md` for comprehensive roadmap of enhancements, optimizations, and modern additions
- **SEO Optimization**: See `SEO-OPTIMIZATION.md` for complete SEO documentation and best practices
- **Build Scripts**: See `build/README.md` for build script documentation

## Support

For issues or questions, refer to:
- Configuration: `config.php`
- Security: `inc/security-headers.php`
- Form handling: `index.php` (contact form section)
- Image optimization: `inc/image-helper.php` and `build/convert-webp.sh`
- Service pages: `inc/service-template.php`

