# Prompts de IA para Gerar Ícones Modernos - Mimo

## Prompt Principal para Ícones de Redes Sociais

```
Crie um conjunto de ícones de redes sociais minimalistas e elegantes para um salão de beleza e estética feminino chamado "Mimo". 

Especificações:
- Estilo: Minimalista, elegante, moderno, com linhas finas e suaves
- Cores: Monocromático (preto/cinza escuro) para uso em footer escuro, ou versão branca para fundos escuros
- Formato: SVG vetorial, escalável
- Tamanho base: 24x24px, mas escalável
- Plataformas necessárias: Instagram, Facebook, WhatsApp
- Estética: Alinhado com identidade de marca de beleza/estética (cores principais: #ccb7bc rosa suave, #3a505a cinza escuro)
- Linhas: 1.5-2px de espessura, cantos levemente arredondados
- Espaçamento: Ícones devem ter padding interno adequado para não parecerem apertados
- Estilo visual: Similar a Feather Icons ou Heroicons, mas com toque mais feminino e suave

Gere 3 versões de cada ícone:
1. Versão outline (contorno)
2. Versão filled (preenchido)
3. Versão com gradiente sutil (opcional)

Todos os ícones devem manter consistência visual entre si.
```

## Prompt para Ícones de Serviços (404 Page)

```
Crie ícones minimalistas e modernos para serviços de beleza e estética:

1. **Cílios e Sobrancelhas**: Ícone de olho estilizado ou cílios curvados
2. **Esmalteria**: Ícone de unha estilizada ou pincel de esmalte
3. **Estética Facial**: Ícone de rosto feminino estilizado ou gota de produto
4. **Estética Corporal**: Ícone de silhueta feminina ou símbolo de bem-estar
5. **Micropigmentação**: Ícone de pincel fino ou símbolo de precisão
6. **Salão**: Ícone de tesoura estilizada ou símbolo de cabelo

Especificações:
- Estilo: Minimalista, linha fina (1.5-2px), elegante
- Cores: Monocromático (#3a505a cinza escuro) ou versão com gradiente rosa suave (#ccb7bc)
- Formato: SVG vetorial
- Tamanho: 48x48px base, mas escalável
- Estética: Moderna, feminina, alinhada com identidade de marca de beleza
- Linhas: Suaves, curvas elegantes, sem cantos muito agudos
- Estilo visual: Similar a Lucide Icons ou Heroicons, mas com toque mais delicado e feminino

Todos os ícones devem funcionar bem em cards brancos com sombra sutil.
```

## Prompt para Ícones de Contato

```
Crie ícones minimalistas e modernos para informações de contato:

1. **Endereço/Localização**: Pin de localização estilizado
2. **Telefone**: Telefone moderno ou símbolo de chamada
3. **Horário**: Relógio estilizado ou símbolo de tempo
4. **Redes Sociais**: Conjunto de ícones sociais (Instagram, Facebook, WhatsApp)
5. **Email**: Envelope estilizado ou símbolo @
6. **Como chegar**: Seta de navegação ou mapa estilizado

Especificações:
- Estilo: Minimalista, linha fina, elegante
- Cores: #3a505a (cinza escuro) para uso em cards brancos
- Formato: SVG vetorial
- Tamanho: 24x24px base
- Consistência: Todos os ícones devem seguir o mesmo estilo visual
- Estética: Moderna, limpa, profissional, alinhada com identidade de marca de beleza
```

## Bibliotecas de Ícones Modernas Recomendadas

### 1. **Lucide Icons** (Recomendado)
- Site: https://lucide.dev
- Estilo: Minimalista, moderno, linha fina
- Licença: ISC (livre)
- CDN disponível

### 2. **Heroicons**
- Site: https://heroicons.com
- Estilo: Minimalista, moderno
- Licença: MIT
- Duas versões: outline e solid

### 3. **Phosphor Icons**
- Site: https://phosphoricons.com
- Estilo: Moderno, elegante
- Licença: MIT
- Múltiplos pesos (thin, light, regular, bold)

### 4. **Tabler Icons**
- Site: https://tabler.io/icons
- Estilo: Minimalista, linha fina
- Licença: MIT
- Mais de 4000 ícones

### 5. **Feather Icons**
- Site: https://feathericons.com
- Estilo: Minimalista, linha fina
- Licença: MIT
- Clássico e elegante

## Implementação Sugerida

### Opção 1: Usar Lucide Icons (Mais Moderno)
```html
<!-- Adicionar no <head> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.css">

<!-- Usar no HTML -->
<i data-lucide="instagram"></i>
<i data-lucide="facebook"></i>
<i data-lucide="message-circle"></i> <!-- WhatsApp -->

<!-- Inicializar no JS -->
<script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
<script>lucide.createIcons();</script>
```

### Opção 2: SVG Inline Customizado (Mais Controle)
Criar arquivos SVG customizados baseados nos prompts acima e usar inline:
```html
<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
  <!-- paths do ícone -->
</svg>
```

### Opção 3: Font Awesome 6 (Atualização)
Atualizar para Font Awesome 6 (mais moderno que a versão 5 atual):
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
```

## Recomendação Final

**Para o site Mimo, recomendo:**
1. **Lucide Icons** para redes sociais (mais moderno e minimalista)
2. **SVG customizado gerado por IA** para ícones de serviços (mais personalizado)
3. Manter Font Awesome apenas se necessário para compatibilidade

Isso dará um visual mais moderno e elegante, alinhado com a identidade da marca.

