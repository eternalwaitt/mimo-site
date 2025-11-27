<?php
// Suppress deprecation warnings (PHP 8.4 compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);

// Service configuration
$serviceName = 'Estética';
$headerClass = 'corporal-header';
$headerTitle = 'ESTÉTICA';
$includeGTM = false; // This page doesn't have GTM

// Define tabs (only the 2 that are in navigation)
$tabs = [
    ['id' => 'aparelhos', 'label' => 'aparelhos', 'active' => true],
    ['id' => 'massagem', 'label' => 'massagem', 'active' => false]
];

// Tab content - Aparelhos
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgradiofreq" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">RADIOFREQUÊNCIA</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Radiofrequência</b> é uma técnica de eletroterapia voltada para alterações como flacidez de pele, gordura localizada e celulite. O equipamento Hertix promove um aquecimento regional de diferentes temperaturas que gera efeitos na pele e na gordura, promovendo a formação de colágeno e elastina novos, aumento da circulação linfática e diminuição do tamanho das células de gordura.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Sessão Individual: R$ 130,00 <br>
                    Pacotes com 10% de desconto<br />
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgultrassom" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">ULTRASSOM</h3>
                <p style="letter-spacing: 0.8px">
                    O <b>Ultrassom</b> é um equipamento que emite ondas sonoras de alta frequência que promovem a quebra das células de gordura, melhora da circulação sanguínea e linfática, redução de medidas e tratamento de celulite.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Sessão Individual: R$ 130,00 <br>
                    Pacotes com 10% de desconto<br />
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgendermo" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">ENDERMOTERAPIA</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Endermoterapia</b> é uma técnica de massagem mecânica que utiliza um equipamento específico para realizar a sucção e rolagem da pele, promovendo a quebra das células de gordura, melhora da circulação sanguínea e linfática, redução de medidas e tratamento de celulite.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Sessão Individual: R$ 130,00 <br>
                    Pacotes com 10% de desconto<br />
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgmlinfatica" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">DRENAGEM LINFÁTICA</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Drenagem Linfática</b> manual é uma técnica de massagem que adota movimentos suaves para estimular a circulação linfática, drenagem e eliminação de toxinas e microrganismos desnecessárias para o corpo. Reduzindo o inchaço generalizado ou local e tratando alteração estéticas como a celulite.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor Individual: R$ 110,00 <br>
                    <b>Drenagem Facial</b>: R$ 70,00 <br />
                    <b>Drenagem Corporal + Facial</b>: R$ 170,00 <br />
                    <b>Drenagem Linfática Pós-Operatório Local</b>: R$ 150,00 <br />
                    Pacotes com 10% de desconto<br /><br />
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgmrelaxante" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MASSAGEM RELAXANTE</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Massagem Relaxante</b> é uma técnica de massagem que adota movimentos profundos e relaxantes que promovem a diminuição do estresse/ansiedade, sensação de bem-estar, hidratação da pele, redução de tensões, dores musculares e aumento da circulação sanguínea.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor Individual: R$ 100,00 <br>
                    Pacotes com 10% de desconto<br /><br />
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgmdrenamod" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MASSAGEM MODELADORA</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Massagem Modeladora</b> é uma técnica de massagem com movimentos profundos, com o intuito de modelagem corpórea, melhora de celulite, circulação, oxigenação e nutrição tecidual.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor Individual: R$ 100,00 <br>
                    Pacotes com 10% de desconto<br /><br />
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container">
            <div class="col-md-10 m-auto">
                <p style="letter-spacing: 0.8px; text-align: center;">
                    Observação geral do corporal: Em caso de alterações vasculares e cardíacas em tratamento e com o uso de medicamentos, será realizado o procedimento somente com a autorização médica (apresentar declaração do médico para o esteticista).
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['aparelhos'] = ob_get_clean();

// Tab content - Massagem
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgmlinfatica" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">DRENAGEM LINFÁTICA</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Drenagem Linfática</b> manual é uma técnica de massagem que adota movimentos suaves para estimular a circulação linfática, drenagem e eliminação de toxinas e microrganismos desnecessárias para o corpo. Reduzindo o inchaço generalizado ou local e tratando alteração estéticas como a celulite.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor Individual: R$ 110,00 <br>
                    <b>Drenagem Facial</b>: R$ 70,00 <br />
                    <b>Drenagem Corporal + Facial</b>: R$ 130,00 <br />
                    <b>Drenagem Linfática Pós-Operatório Local</b>: R$ 150,00 <br />
                    Pacotes com 10% de desconto<br /><br />
                </p>
            </div>
        </div>
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgmrelaxante" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MASSAGEM RELAXANTE</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Massagem Relaxante</b> é uma técnica de massagem que adota movimentos profundos e relaxantes que promovem a diminuição do estresse/ansiedade, sensação de bem-estar, hidratação da pele, redução de tensões, dores musculares e aumento da circulação sanguínea.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor Individual: R$ 100,00 <br>
                    Pacotes com 10% de desconto<br /><br />
                </p>
            </div>
        </div>
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imgmdrenamod" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MASSAGEM MODELADORA</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>Massagem Modeladora</b> é uma técnica de massagem com movimentos profundos, com o intuito de modelagem corpórea, melhora de celulite, circulação, oxigenação e nutrição tecidual.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor Individual: R$ 100,00 <br>
                    Pacotes com 10% de desconto<br /><br />
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['massagem'] = ob_get_clean();

// Include the template
include '../inc/service-template.php';
?>
