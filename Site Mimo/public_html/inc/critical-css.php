<?php
/**
 * CSS Crítico
 * CSS acima da dobra para carregamento inicial mais rápido da página
 * Este CSS é inline no <head> para melhorar o carregamento inicial
 * 
 * Desenvolvido por: Victor Penter
 */
?>
<style>
/* Critical CSS - Above the fold content */
.navbar {
    padding: 20px 25px;
    background-color: #3d3d3d;
    margin: 0;
    transition: all .3s;
    z-index: 9999;
}

.navbar-brand {
    display: inline-block;
    padding-top: 0.3125rem;
    padding-bottom: 0.3125rem;
    margin-right: 1rem;
    font-size: 1.25rem;
    line-height: inherit;
    white-space: nowrap;
}

.logonav {
    height: 40px;
    transition: .3s height;
}

.bg-header {
    background-image: url(img/bgheader.jpg?23);
    background-size: cover;
    background-repeat: no-repeat;
    height: 50vh;
    min-height: 350px;
    max-height: 500px;
    background-position: center;
}

@media only screen and (max-width: 750px) {
    .bg-header {
        background-image: url(img/header_dezembro_mobile.png);
        background-position: center;
        height: 40vh;
        min-height: 250px;
        max-height: 350px;
    }
    .navbar {
        padding: 20px 25px;
        background-color: #3d3d3d;
        margin: 0;
        transition: all .3s;
        z-index: 9999;
    }
}
</style>

