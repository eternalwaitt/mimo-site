<?php
// Suppress deprecation warnings (PHP 8.4 compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);

// Image helper functions for WebP support
require_once '../inc/image-helper.php';

// Service configuration
$serviceName = 'Cílios e Design';
$headerClass = 'cilios-header';
$headerTitle = 'CÍLIOS E SOBRANCELHA';
$includeGTM = true;

// Define tabs
$tabs = [
    ['id' => 'dsobrancelha', 'label' => 'DESIGN DE SOBRANCELHA', 'active' => true],
    ['id' => 'lashlif', 'label' => 'MIMO LASH LIFT', 'active' => false],
    ['id' => 'combo', 'label' => 'COMBOS', 'active' => false]
];

// Tab content - Design de Sobrancelha
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container m-auto">
            <p style="margin:15px;">
                A Mimo oferece um design de sobrancelha SEM PADRÃO! Por meio de estudos de visagismo, e entendendo a personalidade de cada cliente, oferecemos um procedimento personalizado, respeitando a individualidade de cada sobrancelha.
            </p>
            <div class="col-md-12 container my-5">
                <div class="col-md-4" style="float:left;">
                    <?php echo picture_webp('../img/servicos/cilios/designnovo.jpg', 'Design de Sobrancelha', '', ['style' => 'width: 100%; height: auto;', 'width' => '400', 'height' => '300']); ?>
                </div>
                <div class="col-md-8 service-content">
                    <h3 class="textPink font-weight-bold">DESIGN DE SOBRANCELHA</h3>
                    <p style="letter-spacing: 0.8px">
                        Inclui higienização, marcação personalizada, epilação com técnica egípcia e pinça, corte estratégico, coloração dos fios*, nutrição para fios, e finalizamos com uma massagem para aplicar uma máscara calmante profissional pra hidratar e acalmar a pele da região pós epilação.
                    </p>
                    <p class="textDarkGrey" style="font-size: 17px;">
                        Valor: R$ 100,00
                    </p>
                    <h3 class="textPink font-weight-bold">DESIGN + ESTÍMULO DOS FIOS</h3>
                    <p style="letter-spacing: 0.8px">
                        Tudo do design tradicional, com um cuidado extra. É um tratamento onde usamos uma ferramenta específica para permeamos diretamente na pele ativos profissionais para estimular o crescimento e fortalecimento dos fios.
                    </p>
                    <p class="textDarkGrey" style="font-size: 17px;">
                        Valor: R$ 130,00
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['dsobrancelha'] = ob_get_clean();

// Tab content - Lash Lift
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imglashlift" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MIMO LASH LIFT</h3>
                <p style="letter-spacing: 0.8px">
                    O Mimo Lash Lift é um tratamento para os cílios, que usa produtos específicos para hidratar e fortificar os fios, ajudando-os a ficarem maiores, mais volumosos e saudáveis. Além disso, o Lash Lift acentua a curvatura dos fios e realçar bastante a cor, criando um efeito de maquiagem (rímel e curvex). <br><br>
                    O Lash Lift preserva a naturalidade e volume do seu olhar, criando um efeito de maquiagem duradouro. <br>
                    É um procedimento que não requer manutenção.
                    <br /><br />
                    Passo a Passo:<br />
                    • Higienização;<br>
                    • Aplicação do Molde de Silicone e Pad;<br>
                    • Aplicação Primer;<br>
                    • Aplicação do Ativo Lift;<br>
                    • Aplicação do Fixador;<br>
                    • Tintura dos cílios, caso desejado;<br>
                    • Tratamento nutritivo;<br>
                    • Queratina.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    Valor: R$ 199,90
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['lashlif'] = ob_get_clean();

// Tab content - Combos
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 imglashlift" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">COMBO DUO - R$249,99</h3>
                <p style="letter-spacing: 0.8px">
                    Monte seu combo: design, brow lamination e lash lift.
                </p>
                <h3 class="textPink font-weight-bold mt-4">COMBO BEAUTY</h3>
                <p style="letter-spacing: 0.8px">
                    Brow Lamination + Lash Lift<br>
                    O combo beauty veio pra trazer praticidade na nossa rotina. Sabemos da importância que a vaidade traz pra nossa autoestima, e sabemos também que o nosso tempo é curto. O combo trouxe o brow lamination pra facilitar na estilização da sua sobrancelha, o lash lift trazendo volume, curvatura e definição pra aposentar ou reduzir o uso de rímel. <br><br>
                </p>
                <h3 class="textPink font-weight-bold mt-4">COMBO BEAUTY DESIGN - R$299,99</h3>
                <p style="letter-spacing: 0.8px">
                    Ao invés de brow lamination, design estratégico e personalizado.
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['combo'] = ob_get_clean();

// Include the template
include '../inc/service-template.php';
?>
