<?php
// Suppress deprecation warnings (PHP 8.4 compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);

// Image helper functions for WebP support
require_once '../inc/image-helper.php';

// Service configuration
$serviceName = 'Salão';
$headerClass = 'salao-header';
$headerTitle = 'MIMO SALON';
$includeGTM = false; // Using custom GTM

// Custom GTM in head
$customHeadContent = '
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({
                \'gtm.start\':
                    new Date().getTime(), event: \'gtm.js\'
            }); var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != \'dataLayer\' ? \'&l=\' + l : \'\'; j.async = true; j.src =
                    \'https://www.googletagmanager.com/gtm.js?id=\' + i + dl; f.parentNode.insertBefore(j, f);
        })(window, document, \'script\', \'dataLayer\', \'GTM-WR677CTN\');</script>
    <!-- End Google Tag Manager -->
';

// Custom GTM noscript in body
$customBodyStartContent = '
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WR677CTN" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
';

// Custom modals
ob_start();
?>
<!-- Modal -->
<div class="modal fade" id="modalBlond1_new" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Modal removido - agendamento via WhatsApp -->
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenterNav2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Modal removido - agendamento via WhatsApp -->
            </div>
        </div>
    </div>
</div>
<?php
$customContentBeforeBanner = ob_get_clean();

// Custom tab configuration for salao (no "pills-" prefix)
$tabIdPrefix = ''; // No prefix
$tabListId = 'pills-tab1';
$tabContentId = 'pills-tabContent';
$tabContentClass = 'mt-lg-5 mb-lg-5';
$footerInsideTabContent = true;

// Define tabs with custom IDs
$tabs = [
    ['id' => 'mimocolors', 'label' => 'MIMO COLORS', 'active' => true, 'navLinkId' => 'mimocolors', 'tabPaneId' => 'mimocolorss'],
    ['id' => 'allsalon', 'label' => 'MIMO ALL SALON', 'active' => false, 'navLinkId' => 'allsalon', 'tabPaneId' => 'allsalonn'],
    ['id' => 'alisamentos', 'label' => 'MIMO ALISA', 'active' => false, 'navLinkId' => 'alisamentos', 'tabPaneId' => 'alisamentoss'],
    ['id' => 'mimo-mega-hair', 'label' => 'MIMO MEGA HAIR', 'active' => false, 'navLinkId' => 'mimo-mega-hair', 'tabPaneId' => 'mimo-mega-hairr'],
    ['id' => 'let-coesta', 'label' => 'LET COESTA', 'active' => false, 'navLinkId' => 'let-coesta', 'tabPaneId' => 'let-coestaa']
];

// Tab content - MIMO COLORS
ob_start();
?>
<div class="col-md-12 container my-5">
    <div class="col-xs-12 imgOmbre" style="float:left;"></div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">MECHAS MIMO</h3>
        <p style="letter-spacing: 0.8px">
            O serviço de Mechas leva um tempo médio de serviço de 6h a 8h, varia conforme comprimento e
            quantidade de cabelo.<br /><br />
            Inclui descoloração, 01 tonalização, reconstrução e finalização.
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            a partir de R$500,00
        </p>
        <div class="modal fade" id="consultarComprimento" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="bottom: inherit;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 1080px;" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="overflow: hidden;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="textPink text-center my-3">ATENÇÃO!</h3>
                        <?php echo picture_webp('comprimento.png', '', '', ['style' => 'max-width: 100%;display: block;']); ?>
                        <p>*O orçamento passado pelo profissional é baseado no tamanho e volume do cabelo,
                            tabela ilustrativa.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalBlond1" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Modal removido - agendamento via WhatsApp -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 container my-5">
    <div class="col-xs-12" style="float:left;width: 200px; height: 200px; overflow: hidden;">
        <?php echo picture_webp('Mimo-Summer.png', 'Iluminado Mimo', '', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
    </div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">ILUMINADO MIMO</h3>
        <p style="letter-spacing: 0.8px">
            O Iluminado Mimo ou morena iluminada leva um tempo médio de serviço de 4h a 6h, varia conforme
            comprimento e quantidade de cabelo.<br /><br />
            Inclui clareamento, 01 tonalização, reconstrução e finalização.
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            a partir de R$550,00
        </p>
    </div>
</div>

<div class="col-md-12 container my-5">
    <div class="col-xs-12" style="float:left;width: 200px; height: 200px; overflow: hidden;">
        <?php echo picture_webp('Mimo-AllBlond.png', 'Descoloração Global Mimo', '', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
    </div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">DESCOLORAÇÃO GLOBAL MIMO</h3>
        <p style="letter-spacing: 0.8px">
            O Descoloração Global ou platinado leva um tempo médio de serviço de 4h a 10h, varia conforme
            comprimento e quantidade de cabelo.<br /><br />
            Inclui a descoloração, 01 tonalização, reconstrução e finalização.
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            a partir de R$500,00
        </p>
    </div>
</div>

<div class="col-md-12 container my-5">
    <div class="col-xs-12" style="float:left;width: 200px; height: 200px; overflow: hidden;">
        <?php echo picture_webp('fantasy.png', 'Colorido Mimo', '', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
    </div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">COLORIDO MIMO</h3>
        <p style="letter-spacing: 0.8px">
            O Colorido Mimo ou cabelo fantasia leva um tempo médio de serviço de 6h a 8h, varia conforme
            comprimento e quantidade de cabelo e quantidade de cores.<br /><br />
            Inclui descoloração, 01 tonalização, reconstrução e finalização incluso, demais tonalizações são
            cobradas à parte <br />
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            a partir de R$600,00
        </p>
    </div>
</div>

<div class="col-md-12 container my-5">
    <div class="col-xs-12" style="float:left;width: 200px; height: 200px; overflow: hidden;">
        <?php echo picture_webp('Ruivo-Mimo.png', 'Coloração Mimo', '', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
    </div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">COLORAÇÃO MIMO</h3>
        <p style="letter-spacing: 0.8px">
            A Coloração Mimo ou o serviço para pintar o cabelo leva um tempo médio de serviço de 4h a 6h,
            varia conforme comprimento e quantidade de cabelo.<br />
            Tempo mínimo para retoque: 20 dias e máximo de 45 dias, passando será cobrado valor do serviço
            total novamente. <br />
            Inclui coloração, 01 tonalização, hidratação e finalização. <br />
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            a partir de R$400,00<br /><br />

            <b>MANUTENÇÃO:</b> <br />
            a partir de R$300,00<br />
            Inclui coloração, 01 tonalização, hidratação e finalização
        </p>
    </div>
</div>
<?php
$tabContent['mimocolors'] = ob_get_clean();

// Tab content - MIMO ALL SALON
ob_start();
?>
<div class="col-md-12 container my-5">
    <div class="col-xs-12 imgMimoCut" style="float:left;"></div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">CORTE MIMO - LISAS & CACHEADAS</h3>
        <p style="letter-spacing: 0.8px">
            <b>Corte Mimo</b><br>
            R$ 129,90<br><br>

            <b>Combos:</b><br>
            Corte + Tratamento wella<br>
            R$ 149,90<br><br>

            Corte + SPA Detox (c/ argiloterapia)<br>
            R$ 229,00<br><br>

            Corte + Tratamento c/ ozônioterapia<br>
            R$ 199,90<br><br>

            Consultoria, lavagem, corte e finalização incluso em todos os serviços.
        </p>
    </div>
</div>
<?php
$tabContent['allsalon'] = ob_get_clean();

// Tab content - MIMO ALISA
ob_start();
?>
<div class="col-md-12 container my-5">
    <div class="col-xs-12 imgagripaaaarot" style="float:left;"></div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">MIMO ALISA (Progressiva sem Formol + Tratamento de
            Reconstrução)</h3>
        <p style="letter-spacing: 0.8px">
            Nosso Alisamento sem Formol pode ser feito em gestantes e lactantes! Sua manutenção pode ser
            feita a cada 3 meses, dependendo do número de lavagens.<br /><br />
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            R$349,00 (para todos comprimentos de cabelos)
        </p>
    </div>
</div>

<div class="col-md-12 container my-5">
    <div class="col-xs-12 imgagripaaaarot" style="float:left;"></div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">MIMO SELAGEM</h3>
        <p style="letter-spacing: 0.8px">
            A Selagem é um procedimento que reduz frizz e volume do cabelo, porém não é um alisamento. A
            finalização está inclusa.<br />
        </p>
        <p class="textDarkGrey" style="font-size: 17px;">
            a partir de R$270,00
        </p>
    </div>
</div>
<?php
$tabContent['alisamentos'] = ob_get_clean();

// Tab content - MIMO MEGA HAIR
ob_start();
?>
<div class="col-md-12 container my-5">
    <p style="letter-spacing: 0.8px;text-align: center;">
        O Mimo Mega Hair é um procedimento de extensão de cabelo, onde podemos trabalhar o comprimento,
        volume, correção de cortes tradicionais e corte químico, com naturalidade e praticidade.
        Atualmente
        trabalhamos com a técnica de fita adesiva.
    </p>
</div>

<div class="col-md-12 container my-5">
    <div class="col-xs-12 img-fitaades" style="float:left;"></div>
    <div class="col-md-8 col-xs-12 service-content">
        <h3 class="textPink font-weight-bold">FITA ADESIVA</h3>
        <p style="letter-spacing: 0.8px">
            Somente em avaliação presencial para que nossa profissional consiga te passar um orçamento
            personalizado conforme comprimento e volume mais adequado para você!
        </p>
    </div>
</div>

<div class="col-md-12 container my-5">
    <p style="letter-spacing: 0.8px;text-align: center;">
        É necessária avaliação presencial para a profissional indicar qual o modelo certo de cabelo pela
        textura, tom e praticidade do dia a dia. <br>
        Agende uma avaliação para se obter o orçamento correto, pois varia de acordo com o comprimento e
        quantidade de cabelo
    </p>
</div>
<?php
$tabContent['mimo-mega-hair'] = ob_get_clean();

// Tab content - LET COESTA
ob_start();
?>
<div class="col-md-12 container my-5">
    <div class="col-md-8 col-xs-12" style="margin: auto;">
        <h3 class="textPink font-weight-bold">CORTE</h3>
        <p style="letter-spacing: 0.8px">
            R$199,90
        </p>

        <h3 class="textPink font-weight-bold mt-4">COMBOS</h3>
        <p style="letter-spacing: 0.8px">
            Corte + Tratamento Wella: R$249,90<br>
            Corte + Tratamento Wella + SPA Detox (argiloterapia): R$299,90<br>
            Consultoria, lavagem, corte e finalização incluso em todos os serviços.
        </p>

        <h3 class="textPink font-weight-bold mt-4">SERVIÇOS DE COR E DESCOLORAÇÃO</h3>
        <p style="letter-spacing: 0.8px">
            <b>Summer:</b> a partir de R$700,00<br>
            Consultoria, iluminado sem pó descolorante e baixa manutenção, finalização.<br><br>

            <b>Iluminado:</b> a partir de R$800,00<br>
            Consultoria, iluminado com pó descolorante, finalização.<br><br>

            <b>Mechas:</b> a partir de R$900,00<br>
            Consultoria, descoloração com pó, finalização.<br><br>

            <b>Criação de ruivo e Cherry:</b> a partir de R$700,00<br>
            Manutenção até 45 dias: R$500,00
        </p>
    </div>
</div>
<?php
$tabContent['let-coesta'] = ob_get_clean();

// Include the template
include '../inc/service-template.php';
?>
