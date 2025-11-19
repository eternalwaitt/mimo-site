<?php
/**
 * Cabeçalho das Páginas Internas (Serviços)
 * Navegação para páginas de serviços
 * 
 * Desenvolvido por: Victor Penter
 */

/*if($_GET['teste']) {

} else {
    
    <style>
        img {
            margin: 0 auto;
            max-width: 400px;
            display: block;
        }   
        p {
            text-align: center;
        }
    </style>
    <img src="../img/logobranco.png" alt="">
   <p>Site em manutenção, entre em contato <a target="_blank" href="https://api.whatsapp.com/send?1=pt_BR&amp;phone=5511994781012" style="letter-spacing: 1.3px; color:#ccb7bc;">aqui</a>
    </p>
    <?php
    die();
} */
?>
<nav class="navbar navbar-expand-sm fixed-top navbar-light">
    <div class="container">
        <a class="navbar-brand" href="../"><img class="logonav"
                src="../img/logobranco1.png" alt="logo-mimo" width="120" height="22" /></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar2">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar2">
            <ul class="navbar-nav ml-auto  ">
                <li class="nav-item active">
                    <a class="nav-link" href="../">HOME</a>
                </li>
                <li class="nav-item ml-md-2">
                    <a class="nav-link" href="../#about">SOBRE</a>
                </li>
                <li class="nav-item ml-md-2">
                    <a class="nav-link" href="../#services">SERVIÇOS</a>
                </li>
                <!--                 <li class="nav-item ml-md-2">
                    <a class="nav-link" href="../#promo">PROMOÇÕES</a>
                </li> -->
                <li class="nav-item ml-md-2">
                    <a class="nav-link" href="../contato.php">CONTATO</a>
                </li>
                <li class="nav-item ml-md-2">
                    <a class="nav-link" href="../faq/">FAQ</a>
                </li>
                <!-- <li class="nav-item ml-md-3">
                    <a class="btn btn-rosa" data-toggle="modal" data-target="#exampleModalCenterNav" style="letter-spacing: 1.3px; color:white;">AGENDAR</a>
                </li> -->
                <li class="nav-item ml-md-3">
                    <a class="btn btn-rosa" target="_blank"
                        href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                        style="letter-spacing: 1.3px; color:white;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; vertical-align: middle;">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>WHATSAPP</a>
                </li>
            </ul>
        </div>
    </div>
</nav>