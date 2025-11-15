/**
 * JavaScript Principal do Site Mimo
 * 
 * Desenvolvido por: Victor Penter
 * 
 * Funcionalidades:
 * - Comportamento da navbar ao rolar a página
 * - Suporte a swipe no carousel
 * - Scroll suave
 * - Manipulação do menu mobile
 * - Validação de formulário (redirecionamento WhatsApp)
 */

'use strict';

// Mudança de cor da navbar ao rolar
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
    }

    $(document).ready(function() {
        $(window).on('scroll', function() {
            navbar();
        });
        navbar();
    });


    /**
     * Swipe functions for Bootstrap Carousel
     * 
     * Permite swipe em dispositivos touch para navegar o carousel
     * Plugin bcSwipe carregado de arquivo separado (js/bc-swipe.js)
     * 
     * USO: Aplicado automaticamente a todos os elementos com classe .carousel
     * THRESHOLD: 50px - distância mínima de swipe para ativar
     * 
     * NOTA: O plugin bcSwipe é carregado via script tag antes deste arquivo
     */

    // Aplicar swipe a todos os carousels (verificar se bcSwipe está disponível)
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
     * Fechar menu mobile ao clicar em link
     * 
     * Quando um link do menu mobile é clicado, fecha automaticamente o menu
     * Melhora UX em dispositivos móveis
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
         * Scroll suave para âncoras
         * 
         * @param {string} target - Seletor do elemento alvo (ex: '#about')
         */
        function scrollTo(target){
            var position = $(target).position();

            // Em mobile, fechar menu antes de scrollar
            if (isMobile.matches) {
                $('#navbar2').removeClass('show');
            }
            // Scroll com offset de 100px para compensar navbar fixa
            $(window).scrollTop(position.top - 100);
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
         * Atualiza contador em tempo real enquanto usuário digita
         * Muda cor para vermelho se abaixo do mínimo (10) ou acima do máximo (2000)
         * Usado em: contato.php, index.php (formulário de contato)
         */
        var $messageField = $("textarea[name=message]");
        var $messageCounter = $("#message-counter");
        
        if ($messageField.length && $messageCounter.length) {
            function updateCounter() {
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
            }
            
            // Atualizar contador ao digitar
            $messageField.on('input', updateCounter);
            
            // Atualizar contador inicial
            updateCounter();
        }
        
        /**
         * Validação e envio AJAX para formulário de contato
         * 
         * Intercepta submit do formulário, valida campos client-side,
         * envia via AJAX e atualiza UI sem recarregar página
         * 
         * VALIDAÇÕES:
         * - Nome: 2-100 caracteres
         * - Email: formato válido (regex)
         * - Assunto: deve ser selecionado
         * - Mensagem: 10-2000 caracteres
         * 
         * USO: Aplicado a todos os forms com method="post"
         * Páginas: contato.php, index.php (seção de contato)
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
                    var errorHtml = '<div class="alert alert-danger" role="alert">' +
                        '<strong>Erro:</strong>' +
                        '<ul class="mb-0 mt-2">';
                    errors.forEach(function(error) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $form.find('h4').after(errorHtml);
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
                    
                    // Criar um elemento temporário para parsear o HTML
                    var $response = $('<div>').html(response);
                    
                    // Extrair apenas a área do formulário da resposta
                    var $formResponse = $response.find('.wrap-contact100');
                    
                    if ($formResponse.length) {
                        // Substituir apenas o conteúdo do formulário
                        $formContainer.html($formResponse.html());
                        
                        // Re-inicializar contador de caracteres
                        var $newMessageField = $("textarea[name=message]");
                        var $newMessageCounter = $("#message-counter");
                        if ($newMessageField.length && $newMessageCounter.length) {
                            function updateCounter() {
                                var length = $newMessageField.val().length;
                                $newMessageCounter.text(length + ' / 2000');
                                if (length < 10) {
                                    $newMessageCounter.css('color', '#ff6b6b');
                                } else if (length > 2000) {
                                    $newMessageCounter.css('color', '#ff6b6b');
                                } else {
                                    $newMessageCounter.css('color', 'rgba(255, 255, 255, 0.6)');
                                }
                            }
                            $newMessageField.on('input', updateCounter);
                            updateCounter();
                        }
                        
                        // Scroll suave até o formulário
                        $('html, body').animate({
                            scrollTop: $formContainer.offset().top - 100
                        }, 500);
                    } else {
                        // Se não encontrou o formulário na resposta, mostrar erro genérico
                        var errorHtml = '<div class="alert alert-warning" role="alert">' +
                            '<strong>Erro:</strong> Não foi possível processar sua mensagem. Tente novamente.</div>';
                        $form.find('h4').after(errorHtml);
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