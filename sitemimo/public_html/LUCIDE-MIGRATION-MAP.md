# Mapeamento Font Awesome → Lucide

Este documento mapeia todos os ícones Font Awesome usados no site para seus equivalentes Lucide.

## Ícones Identificados (30 únicos)

| Font Awesome | Lucide | Notas |
|--------------|--------|-------|
| `fa-arrow-left` | `arrow-left` | Direto |
| `fa-briefcase` | `briefcase` | Direto |
| `fa-building` | `building` | Direto |
| `fa-calendar` | `calendar` | Direto |
| `fa-chevron-up` | `chevron-up` | Direto |
| `fa-circle` | `circle` | Direto |
| `fa-clock` | `clock` | Direto |
| `fa-cut` | `scissors` | Lucide usa "scissors" |
| `fa-envelope` | `mail` | Lucide usa "mail" |
| `fa-eye` | `eye` | Direto |
| `fa-facebook-f` | `facebook` | Lucide usa "facebook" (sem -f) |
| `fa-google` | `chrome` ou `globe` | Google pode ser representado por chrome ou globe |
| `fa-graduation-cap` | `graduation-cap` | Direto |
| `fa-hand-sparkles` | `sparkles` | Lucide usa "sparkles" |
| `fa-heart` | `heart` | Direto |
| `fa-info-circle` | `info` | Lucide usa "info" |
| `fa-instagram` | `instagram` | Direto |
| `fa-map-marker-alt` | `map-pin` | Lucide usa "map-pin" |
| `fa-money-bill-wave` | `dollar-sign` ou `coins` | Lucide não tem money-bill-wave, usar dollar-sign ou coins |
| `fa-palette` | `palette` | Direto |
| `fa-paper-plane` | `send` | Lucide usa "send" |
| `fa-phone` | `phone` | Direto |
| `fa-route` | `route` | Direto |
| `fa-share-alt` | `share-2` | Lucide usa "share-2" |
| `fa-smile` | `smile` | Direto |
| `fa-spa` | `sparkles` ou `flower` | Lucide não tem spa, usar sparkles ou flower |
| `fa-star` | `star` | Direto |
| `fa-tasks` | `check-square` ou `list-checks` | Lucide não tem tasks, usar check-square ou list-checks |
| `fa-user` | `user` | Direto |
| `fa-whatsapp` | `message-circle` | WhatsApp pode ser representado por message-circle |

## Ícones que Precisam de Atenção

### `fa-google`
- **Opção 1**: `chrome` (ícone do Chrome, representa Google)
- **Opção 2**: `globe` (genérico)
- **Recomendação**: Verificar contexto de uso

### `fa-money-bill-wave`
- **Opção 1**: `dollar-sign` (símbolo de dinheiro)
- **Opção 2**: `coins` (moedas)
- **Recomendação**: Verificar contexto de uso

### `fa-spa`
- **Opção 1**: `sparkles` (brilhos, representa bem-estar)
- **Opção 2**: `flower` (flor, representa spa)
- **Recomendação**: Verificar contexto de uso

### `fa-tasks`
- **Opção 1**: `check-square` (lista com checkboxes)
- **Opção 2**: `list-checks` (lista com checks)
- **Recomendação**: `list-checks` é mais próximo visualmente

## Implementação

Todos os ícones serão implementados usando a função `lucide_icon()` em `inc/icon-helper.php`.

## Verificação

Após a migração, verificar visualmente:
- [ ] Todos os ícones aparecem corretamente
- [ ] Ícones têm tamanho adequado
- [ ] Ícones têm cor adequada (light/dark mode)
- [ ] Ícones são clicáveis (se aplicável)
- [ ] Ícones não causam layout shift

