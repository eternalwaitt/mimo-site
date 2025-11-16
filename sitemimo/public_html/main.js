/**
 * JavaScript Principal do Site Mimo
 * 
 * Desenvolvido por: Victor Penter
 * Versão: 2.6.9
 * 
 * FUNCIONALIDADES:
 * - Comportamento da navbar ao rolar a página (compression, color change)
 * - Suporte a swipe no carousel Bootstrap (via bcSwipe plugin)
 * - Scroll suave para âncoras
 * - Manipulação do menu mobile (auto-close, color fix)
 * - Validação de formulário client-side (AJAX submit)
 * - Contador de caracteres para campo de mensagem
 * 
 * DEPENDÊNCIAS:
 * - jQuery 3.3.1+ (CDN ou local)
 * - Bootstrap 4.5.2+ (carousel, collapse)
 * - bcSwipe plugin (js/bc-swipe.js)
 * 
 * ONDE É USADO:
 * - Todas as páginas do site (index.php, contato.php, vagas.php, 404.php)
 * - Páginas de serviço (via service-template.php)
 * - Carregado no final do <body> com defer
 * 
 * PERFORMANCE:
 * - Usa event delegation quando possível
 * - Debounce em eventos de scroll (via jQuery)
 * - Lazy initialization de plugins
 */

'use strict';

/**
 * Gerencia o comportamento da navbar ao rolar a página
 * 
 * COMPORTAMENTO:
 * - Homepage: Navbar transparente no topo, escura ao scrollar
 * - Páginas internas: Navbar sempre escura (não muda)
 * 
 * CLASSES APLICADAS:
 * - .compressed: Reduz altura da navbar
 * - .changecolormenu: Muda cor dos links do menu
 * - .changecolorlogo: Muda cor do logo
 * 
 * THRESHOLD: 20px de scroll para ativar na homepage
 * 
 * @returns {void}
 */
function navbar(){
        // Verificar se a página tem .page-hero (páginas internas)
        const hasPageHero = $('.page-hero').length > 0;
        
        // Em páginas internas, SEMPRE manter o fundo escuro (nunca remover)
        if (hasPageHero) {
            $('.navbar').addClass('compressed');
            $('.navbar-nav').addClass('changecolormenu');
            $('.navbar-brand').addClass('changecolorlogo');
            return; // Sair da função para não executar a lógica da homepage
        }
        
        // FIX: Usar requestAnimationFrame para evitar forced reflow
        requestAnimationFrame(function() {
            // Lógica apenas para homepage (sem .page-hero)
            if ($(window).scrollTop() >= 20) {
                // Na homepage, só aplicar quando scrollar
                $('.navbar').addClass('compressed');
                $('.navbar-nav').addClass('changecolormenu');
                $('.navbar-brand').addClass('changecolorlogo');
            } else {
                // Na homepage no topo, remover classes
                $('.navbar').removeClass('compressed');
                $('.navbar-nav').removeClass('changecolormenu');
                $('.navbar-brand').removeClass('changecolorlogo');
            }
        });
    }

    $(document).ready(function() {
        $(window).on('scroll', function() {
            navbar();
        });
        navbar();
    });


    /**
     * Inicializa suporte a swipe em carousels Bootstrap
     * 
     * Permite navegação via swipe em dispositivos touch (mobile/tablet).
     * Usa o plugin bcSwipe carregado de js/bc-swipe.js.
     * 
     * COMPORTAMENTO:
     * - Aplicado automaticamente a todos os elementos .carousel
     * - Threshold de 50px (distância mínima de swipe)
     * - Funciona com qualquer carousel Bootstrap
     * 
     * FALLBACK:
     * - Se bcSwipe não estiver carregado, tenta novamente após 100ms
     * - Permite carregamento assíncrono do plugin
     * 
     * @returns {void}
     */
    if (typeof $.fn.bcSwipe === 'function') {
        $('.carousel').bcSwipe({ threshold: 50 });
    } else {
        // Se bcSwipe não estiver carregado, tentar novamente após um delay
        setTimeout(function() {
            if (typeof $.fn.bcSwipe === 'function') {
                $('.carousel').bcSwipe({ threshold: 50 });
            }
        }, 100);
    }


    /**
     * Fecha menu mobile automaticamente ao clicar em link
     * 
     * Melhora UX em dispositivos móveis fechando o menu após navegação.
     * Usa event delegation para funcionar com elementos adicionados dinamicamente.
     * 
     * @event click
     * @listens .navbar-collapse a
     * @returns {void}
     */
    $('.navbar-collapse a').click(function (e) {
        $('.navbar-collapse').collapse('toggle');
    });


    /**
     * Fix navbar toggle color on mobile
     * 
     * Ajusta classes do navbar baseado no tamanho da tela
     * Em mobile: adiciona classes para menu escuro
     * Em desktop: remove classes de mobile
     */

    $( document ).ready(function() {
        var isMobile = window.matchMedia("only screen and (max-width: 760px)");


        if (isMobile.matches) {
            $('#navbar2').addClass('collapse');
            $('#navbar2').addClass('navbar-collapse');
            $('.navbar').addClass('navbar-dark');

        } else {
            $('#navbar2').removeClass('collapse');
            $('#navbar2').removeClass('navbar-collapse');
            $('.navbar').removeClass('navbar-dark');
        }

        /**
         * Faz scroll suave até um elemento alvo
         * 
         * Scrolla a página até o elemento especificado com offset de 100px
         * para compensar a navbar fixa. Em mobile, fecha o menu antes de scrollar.
         * 
         * @param {string} target - Seletor CSS do elemento alvo (ex: '#about', '.section')
         * @returns {void}
         * 
         * @example
         * scrollTo('#about'); // Scrolla até elemento com id="about"
         */
        function scrollTo(target){
            // FIX: Usar requestAnimationFrame para evitar forced reflow
            requestAnimationFrame(function() {
                var position = $(target).position();

                // Em mobile, fechar menu antes de scrollar
                if (isMobile.matches) {
                    $('#navbar2').removeClass('show');
                }
                // Scroll com offset de 100px para compensar navbar fixa
                $(window).scrollTop(position.top - 100);
            });
        }

        /**
         * Handler para links com classe .scroll
         * 
         * Links com classe .scroll fazem scroll suave para âncoras
         * Exemplo: <a href="#about" class="scroll">Sobre</a>
         */
        $('a.scroll').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            scrollTo(target);
        });

        /**
         * Scroll automático para hash na URL
         * 
         * Se a URL contém hash (ex: /#about), faz scroll automático
         * Aguarda window.onload para garantir que o DOM está pronto
         */
        if (window.location.hash) {
            var scrollToTop = function () {
                $(window).scrollTop(0);
            };
            $(window).one('scroll', scrollToTop);

            window.onload = function() {
                scrollTo(window.location.hash);
            };
        }
        
        /**
         * Contador de caracteres para campo de mensagem
         * 
         * Atualiza contador em tempo real enquanto usuário digita.
         * Validação visual: vermelho se < 10 ou > 2000 caracteres.
         * 
         * LIMITES:
         * - Mínimo: 10 caracteres
         * - Máximo: 2000 caracteres
         * 
         * ONDE É USADO:
         * - contato.php (formulário de contato)
         * - index.php (seção de contato)
         * 
         * ELEMENTOS REQUERIDOS:
         * - textarea[name="message"]: Campo de mensagem
         * - #message-counter: Elemento que exibe o contador
         * 
         * @returns {void}
         */
        var $messageField = $("textarea[name=message]");
        var $messageCounter = $("#message-counter");
        
        if ($messageField.length && $messageCounter.length) {
            var updateCounter = function() {
                // Use requestAnimationFrame to avoid blocking main thread
                requestAnimationFrame(function() {
                    var length = $messageField.val().length;
                    $messageCounter.text(length + ' / 2000');
                    
                    // Mudar cor se estiver abaixo do mínimo ou acima do máximo
                    if (length < 10) {
                        $messageCounter.css('color', '#ff6b6b');
                    } else if (length > 2000) {
                        $messageCounter.css('color', '#ff6b6b');
                    } else {
                        $messageCounter.css('color', 'rgba(255, 255, 255, 0.6)');
                    }
                });
            };
            
            // Debounce input events to reduce main thread blocking
            var updateCounterDebounced = (function() {
                var timeout;
                return function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(updateCounter, 100); // Update max once per 100ms
                };
            })();
            
            // Atualizar contador ao digitar (debounced)
            $messageField.on('input', updateCounterDebounced);
            
            // Atualizar contador inicial
            updateCounter();
        }
        
        /**
         * Validação e envio AJAX para formulário de contato
         * 
         * Intercepta submit do formulário, valida campos client-side,
         * envia via AJAX e atualiza UI sem recarregar página.
         * 
         * FLUXO:
         * 1. Previne submit padrão
         * 2. Valida todos os campos
         * 3. Mostra erros se houver
         * 4. Envia via AJAX se válido
         * 5. Atualiza UI com resposta
         * 6. Scrolla até formulário
         * 
         * VALIDAÇÕES:
         * - Nome: 2-100 caracteres (trim aplicado)
         * - Email: formato válido (regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/)
         * - Assunto: deve ser selecionado (não vazio)
         * - Mensagem: 10-2000 caracteres (trim aplicado)
         * 
         * FEEDBACK VISUAL:
         * - .alert-validate: Classe adicionada a campos inválidos
         * - .loading: Classe adicionada ao botão durante envio
         * - .alert: Mensagens de sucesso/erro
         * 
         * ONDE É USADO:
         * - contato.php (formulário de contato)
         * - index.php (seção de contato)
         * 
         * @event submit
         * @listens form[method="post"]
         * @param {Event} e - Evento de submit
         * @returns {boolean} false para prevenir submit padrão
         */
        $('form[method="post"]').on('submit', function(e) {
            e.preventDefault(); // Sempre prevenir submit padrão
            
            var $form = $(this);
            var $btn = $form.find('.contact100-form-btn');
            var $formContainer = $form.closest('.wrap-contact100');
            var name = $("input[name=name]").val().trim();
            var email = $("input[name=email]").val().trim();
            var subject = $("select[name=subject]").val();
            var message = $("textarea[name=message]").val().trim();
            
            // Validação client-side
            var isValid = true;
            var errors = [];
            
            // Limpar erros anteriores
            $form.find('.alert-validate').removeClass('alert-validate');
            $formContainer.find('.alert').remove();
            
            // Validar nome
            if(name == '' || name.length < 2) {
                $("input[name=name]").parent().addClass('alert-validate');
                errors.push('Nome deve ter pelo menos 2 caracteres');
                isValid = false;
            } else if (name.length > 100) {
                $("input[name=name]").parent().addClass('alert-validate');
                errors.push('Nome deve ter no máximo 100 caracteres');
                isValid = false;
            } else {
                $("input[name=name]").parent().removeClass('alert-validate');
            }
            
            // Validar email
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(email == '' || !emailRegex.test(email)) {
                $("input[name=email]").parent().addClass('alert-validate');
                errors.push('Email inválido');
                isValid = false;
            } else {
                $("input[name=email]").parent().removeClass('alert-validate');
            }
            
            // Validar assunto
            if(!subject || subject == '') {
                $("select[name=subject]").parent().addClass('alert-validate');
                errors.push('Selecione um assunto');
                isValid = false;
            } else {
                $("select[name=subject]").parent().removeClass('alert-validate');
            }
            
            // Validar mensagem
            if(message == '' || message.length < 10) {
                $("textarea[name=message]").parent().addClass('alert-validate');
                errors.push('Mensagem deve ter pelo menos 10 caracteres');
                isValid = false;
            } else if (message.length > 2000) {
                $("textarea[name=message]").parent().addClass('alert-validate');
                errors.push('Mensagem deve ter no máximo 2000 caracteres');
                isValid = false;
            } else {
                $("textarea[name=message]").parent().removeClass('alert-validate');
            }
            
            // Se validação falhou, mostrar erros e parar
            if (!isValid) {
                if (errors.length > 0) {
                    // Use requestAnimationFrame to avoid blocking main thread
                    requestAnimationFrame(function() {
                        var errorHtml = '<div class="alert alert-danger" role="alert">' +
                            '<strong>Erro:</strong>' +
                            '<ul class="mb-0 mt-2">';
                        for (var i = 0; i < errors.length; i++) {
                            errorHtml += '<li>' + errors[i] + '</li>';
                        }
                        errorHtml += '</ul></div>';
                        $form.find('h4').after(errorHtml);
                    });
                }
                return false;
            }
            
            // Se passou validação, mostrar loading
            if ($btn.length) {
                $btn.addClass('loading');
            }
            
            // Preparar dados do formulário
            var formData = new FormData($form[0]);
            
            // Enviar via AJAX
            $.ajax({
                url: window.location.pathname + '#contact',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Remover loading
                    $btn.removeClass('loading');
                    
                    // Use requestIdleCallback or setTimeout to defer non-critical DOM updates
                    var processResponse = function() {
                        // Criar um elemento temporário para parsear o HTML
                        var $response = $('<div>').html(response);
                        
                        // Extrair apenas a área do formulário da resposta
                        var $formResponse = $response.find('.wrap-contact100');
                        
                        if ($formResponse.length) {
                            // Use requestAnimationFrame for DOM updates
                            requestAnimationFrame(function() {
                                // Substituir apenas o conteúdo do formulário
                                $formContainer.html($formResponse.html());
                        
                        // Re-inicializar contador de caracteres (optimized)
                        var $newMessageField = $("textarea[name=message]");
                        var $newMessageCounter = $("#message-counter");
                        if ($newMessageField.length && $newMessageCounter.length) {
                            var updateCounter = function() {
                                requestAnimationFrame(function() {
                                    var length = $newMessageField.val().length;
                                    $newMessageCounter.text(length + ' / 2000');
                                    if (length < 10) {
                                        $newMessageCounter.css('color', '#ff6b6b');
                                    } else if (length > 2000) {
                                        $newMessageCounter.css('color', '#ff6b6b');
                                    } else {
                                        $newMessageCounter.css('color', 'rgba(255, 255, 255, 0.6)');
                                    }
                                });
                            };
                            var updateCounterDebounced = (function() {
                                var timeout;
                                return function() {
                                    clearTimeout(timeout);
                                    timeout = setTimeout(updateCounter, 100);
                                };
                            })();
                            $newMessageField.on('input', updateCounterDebounced);
                            updateCounter();
                        }
                        
                                // Scroll suave até o formulário (defer to avoid blocking)
                                setTimeout(function() {
                                    $('html, body').animate({
                                        scrollTop: $formContainer.offset().top - 100
                                    }, 500);
                                }, 0);
                            });
                        } else {
                            // Se não encontrou o formulário na resposta, mostrar erro genérico
                            requestAnimationFrame(function() {
                                var errorHtml = '<div class="alert alert-warning" role="alert">' +
                                    '<strong>Erro:</strong> Não foi possível processar sua mensagem. Tente novamente.</div>';
                                $form.find('h4').after(errorHtml);
                            });
                        }
                    };
                    
                    // Defer response processing if requestIdleCallback is available
                    if ('requestIdleCallback' in window) {
                        requestIdleCallback(processResponse, { timeout: 1000 });
                    } else {
                        setTimeout(processResponse, 0);
                    }
                },
                error: function(xhr, status, error) {
                    // Remover loading
                    $btn.removeClass('loading');
                    
                    // Mostrar erro
                    var errorHtml = '<div class="alert alert-danger" role="alert">' +
                        '<strong>Erro:</strong> Ocorreu um erro ao enviar sua mensagem. Tente novamente mais tarde.</div>';
                    $form.find('h4').after(errorHtml);
                    
                    console.error('Erro AJAX:', error);
                }
            });
            
            return false;
        });
    });


    //Fechar modais de impedimento

    $("#exampleModalCenterImpedimentoGel>.btnAgendamento").on("click", function(e) {

        if (e.target !== this)
            return;

        $("#exampleModalCenterImpedimentoGel").removeClass('show');
    });