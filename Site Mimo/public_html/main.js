'use strict';
// Navbar color change
    function navbar(){
        if ($(window).scrollTop() >= 20) {
            $('.navbar').addClass('compressed');
            $('.navbar-nav').addClass('changecolormenu');
            $('.navbar-brand').addClass('changecolorlogo');

        } else {
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


    // Swipe functions for Bootstrap Carousel

    !function(t){t.fn.bcSwipe=function(e){var n={threshold:50};return e&&t.extend(n,e),this.each(function(){function e(t){1==t.touches.length&&(u=t.touches[0].pageX,c=!0,this.addEventListener("touchmove",o,!1))}function o(e){if(c){var o=e.touches[0].pageX,i=u-o;Math.abs(i)>=n.threshold&&(h(),t(this).carousel(i>0?"next":"prev"))}}function h(){this.removeEventListener("touchmove",o),u=null,c=!1}var u,c=!1;"ontouchstart"in document.documentElement&&this.addEventListener("touchstart",e,!1)}),this}}(jQuery);

    $('.carousel').bcSwipe({ threshold: 50 });


    $('.navbar-collapse a').click(function (e) {
        $('.navbar-collapse').collapse('toggle');
    });


    // Fix navbar toggle color on mobile

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

        function scrollTo(target){
            var position = $(target).position();

            if (isMobile.matches) {
                $('#navbar2').removeClass('show');
            }
            $(window).scrollTop(position.top - 100);
        }

        $('a.scroll').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            scrollTo(target);
        });

        if (window.location.hash) {
            var scrollToTop = function () {
                $(window).scrollTop(0);
            };
            $(window).one('scroll', scrollToTop);

            window.onload = function() {
                scrollTo(window.location.hash);
            };
        }
        $(".contact100-form-btn").click(function() {
            var name = $("input[name=name]").val();
            var email = $("input[name=email]").val();
            var subject = $("select[name=subject]").val();
            var message = $("textarea[name=message]").val();
            if(name == '' || name == undefined) {
                $("input[name=name]").parent().addClass('alert-validate');
                return false;
            }
            if(email == '' || email == undefined) {
                $("input[name=email]").parent().addClass('alert-validate');
                return false;
            }
            if(message == '' || message == undefined) {
                $("textarea[name=message]").parent().addClass('alert-validate');
                return false;
            }
            console.log(name, email, subject, message);
            var win = window.open('https://api.whatsapp.com/send?1=pt_BR&phone=5511994781012&text='+name+'%20'+email+'%20'+subject+'%20'+message, '_blank');
            if (win) {
                //Browser has allowed it to be opened
                win.focus();
            } else {
                //Browser has blocked it
                alert('Por favor permita os popups deste site para enviar sua msg por WhatsApp para a Mimo Estética.');
            }
            return false;
        });
    });


    //Fechar modais de impedimento

    $("#exampleModalCenterImpedimentoGel>.btnAgendamento").on("click", function(e) {

        if (e.target !== this)
            return;

        $("#exampleModalCenterImpedimentoGel").removeClass('show');
    });